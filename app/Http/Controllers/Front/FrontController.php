<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Vendor\NewOrderNotification;
use Darryldecode\Cart\Exceptions\InvalidConditionException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Cart as ModelCart;
use function PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\datedifD;

class FrontController extends Controller
{

    public function home() {

        $products = Product::inRandomOrder()->limit(3)->get();
        return view('front/welcome', compact('products'));
    }

    public function product() {
        $products = Product::paginate(30);
        return view('front/product', compact('products'));
    }

    public function about(){
        return view('front/about');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function productDetails($id) {
        $product = Product::findorfail($id);
        return view('front/product-details', compact('product'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('brand', 'LIKE', "%{$query}%")
            ->get();

        // Attach a custom property (e.g., image_url) to each product using your getImage() method.
        $products->each(function($product) {
            // You can specify a collection name if needed; otherwise, leave it as default.
            $product->image_url = $product->getImage();
        });

        return response()->json($products);
    }

    // Function to get the total cart count
    public function getCartCount()
    {
        $userId = auth()->id();
        $cartCount = Cart::session($userId)->getContent();
        $uniqueProductCount = $cartCount->count();
        return response()->json([
            'count' => $uniqueProductCount > 0 ? $uniqueProductCount : 0
        ]);
    }

    public function cart()
    {
        $userId = auth()->id() ?? session()->getId(); // Use user ID if logged in, else session ID
        $savedCart = \App\Models\Cart::where('user_id', $userId)->first();

        if ($savedCart) {
            Cart::session($userId)->clear();

            foreach ($savedCart->cart_data as $item) {
                Cart::session($userId)->add($item);
            }
        }

        $cartItems = Cart::session($userId)->getContent(); // Fetch cart items
        try {
            $condition1 = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'VAT 12.5%',
                'type' => 'tax',
                'target' => 'total',
                'value' => '12.5%'
            ));
            $condition2 = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Shipping Cost',
                'type' => 'shipping',
                'target' => 'subtotal',
                'value' => '15'
            ));
            Cart::condition([$condition1]);
            Cart::condition([$condition2]);
        } catch (InvalidConditionException $e) {
            error_log("cart_error", $e->getMessage());
        }

//        $order = new Order([
//            'user_id' => auth()->id() ?? session()->getId(),
//            'tax' => Cart::getCondition('VAT 12.5%'),
//            'subTotal' => Cart::session($userId)->getSubTotal(),
//            'total' => auth()->id() ?? session()->getId(),
//            'shipping_cost' => Cart::getCondition('Shipping Cost'),
//        ]);

        $tax = Cart::getCondition('VAT 12.5%');
        $shipping = Cart::getCondition('Shipping Cost');
        $subTotal = Cart::session($userId)->getSubTotal();
        $total = Cart::session($userId)->getTotal();
        $cartDetails = \App\Models\Cart::where('user_id', $userId)->first();

        return view('front/cart', compact('cartItems', 'tax', 'shipping', 'subTotal', 'total', 'cartDetails')); // Pass to view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Http\Response
     */

    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $size = trim($request->get('size'));

        $availableSizes = $product->product_sizes; // e.g. [ ['name'=>'S', 'value'=>10], ... ]
        $sizeEntry = collect($availableSizes)->firstWhere('name', $size);
        if (!$sizeEntry) {
            return redirect()->back()->with('error', 'Invalid size selection.');
        }
        if ($sizeEntry['value'] <= 0) {
            return redirect()->back()->with('error', 'Selected size is out of stock.');
        }

        // Decrement stock for the selected size
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $size) {
                $entry['value']--;
            }
        }
        unset($entry);
        $product->product_sizes = $availableSizes;
        $product->save();

        // Use a composite cart ID: productId-size
        $cartId = $product->id . '-' . $size;
        $cartItems = Cart::session($userId)->getContent();
        $existingItem = $cartItems->firstWhere('id', $cartId);
        if ($existingItem) {
            // Increase quantity if already in cart
            Cart::session($userId)->update($cartId, [
                'quantity' => ['relative' => true, 'value' => 1]
            ]);
            return redirect('cart')->with('success', 'Product quantity updated!');
        }

        Cart::session($userId)->add([
            'id'       => $cartId,
            'name'     => $product->name,
            'price'    => $product->price,
            'quantity' => 1,
            'attributes' => [
                'image' => $product->getImage(),
                'size'  => $size,
            ],
        ]);

        // Save the cart to the database
        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect('cart')->with('success', 'Product added to cart!');
    }

    public function updateSize(Request $request, $itemId)
    {
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $newSize = trim($request->get('size'));

        $cartItems = Cart::session($userId)->getContent();
        $oldItem = $cartItems->firstWhere('id', $itemId);
        if (!$oldItem) {
            return redirect('cart')->with('error', 'Item not found.');
        }

        list($productId, $oldSize) = explode('-', $oldItem->id);
        if ($newSize === $oldSize) {
            // No change in size; nothing to update.
            return redirect('cart');
        }

        $quantity = $oldItem->quantity;
        $product = Product::find($productId);
        if (!$product) {
            return redirect('cart')->with('error', 'Product not found.');
        }
        $availableSizes = $product->product_sizes;

        // Return the reserved quantity to the old size
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $oldSize) {
                $entry['value'] += $quantity;
            }
        }
        unset($entry);

        // Check if the new size has enough available stock
        $newSizeEntry = collect($availableSizes)->firstWhere('name', $newSize);
        if (!$newSizeEntry || $newSizeEntry['value'] < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock for the selected size.');
        }
        // Reserve the quantity for the new size
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $newSize) {
                $entry['value'] -= $quantity;
            }
        }
        unset($entry);
        $product->product_sizes = $availableSizes;
        $product->save();

        // Remove the old cart item and add a new one with the updated size in the composite ID
        Cart::session($userId)->remove($oldItem->id);
        $newCartId = $productId . '-' . $newSize;
        Cart::session($userId)->add([
            'id'       => $newCartId,
            'name'     => $oldItem->name,
            'price'    => $oldItem->price,
            'quantity' => $quantity,
            'attributes' => [
                'image' => $oldItem->attributes->image,
                'size'  => $newSize,
            ],
        ]);

        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect('cart')->with('success', 'Size updated successfully!');
    }

    public function clearCart()
    {
        $userId = auth()->id() ?? session()->getId();
        $cartItems = Cart::session($userId)->getContent();


        foreach ($cartItems as $item) {
            list($productId, $size) = explode('-', $item->id);
            $quantity = $item->quantity;
            $product = Product::find($productId);
            if ($product) {
                $productSizes = $product->product_sizes;
                foreach ($productSizes as &$entry) {
                    if ($entry['name'] == $size) {
                        $entry['value'] += $quantity;
                    }
                }
                unset($entry);
                $product->product_sizes = $productSizes;
                $product->save();
            }
        }

        Cart::session($userId)->clear();
        \App\Models\Cart::where('user_id', $userId)->delete();

        return redirect()->route('cart')->with('success', 'Cart cleared successfully.');
    }

    public function removeItem($id)
    {
        $userId = auth()->id() ?? session()->getId();
        $item = Cart::session($userId)->get($id);
        if (!$item) {
            return redirect()->route('cart')->with('error', 'Item not found in cart.');
        }

        // Extract product ID and size from the composite cart ID (formatted as "productId-size")
        list($productId, $size) = explode('-', $id);
        $quantity = $item->quantity;

        $product = Product::find($productId);
        if ($product) {
            // Retrieve the product_sizes array and return the reserved quantity back to stock
            $productSizes = $product->product_sizes;
            foreach ($productSizes as &$entry) {
                if ($entry['name'] == $size) {
                    $entry['value'] += $quantity;
                }
            }
            unset($entry);
            $product->product_sizes = $productSizes;
            $product->save();
        }

        // Remove the item from the cart
        Cart::session($userId)->remove($id);

        // Update the saved cart data
        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }

    public function increaseQuantity($id)
    {
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $item = Cart::session($userId)->get($id);
        if (!$item) {
            return redirect()->route('cart')->with('error', 'Item not found in cart.');
        }
        list($productId, $size) = explode('-', $id);
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('cart')->with('error', 'Product not found.');
        }
        $availableSizes = $product->product_sizes;
        $sizeEntry = collect($availableSizes)->firstWhere('name', $size);
        if (!$sizeEntry || $sizeEntry['value'] <= 0) {
            return redirect()->route('cart')->with('error', 'Not enough stock available for ' . $product->name . ' in size ' . $size . '.');
        }
        // Decrement available stock by 1
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $size) {
                $entry['value']--;
            }
        }
        unset($entry);
        $product->product_sizes = $availableSizes;
        $product->save();

        // Increase cart quantity by 1
        Cart::session($userId)->update($id, [
            'quantity' => ['relative' => true, 'value' => 1]
        ]);

        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );
        return redirect()->route('cart');
    }

    public function decreaseQuantity($id)
    {
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $item = Cart::session($userId)->get($id);
        if (!$item) {
            return redirect()->route('cart')->with('error', 'Item not found in cart.');
        }
        list($productId, $size) = explode('-', $id);
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('cart')->with('error', 'Product not found.');
        }
        $availableSizes = $product->product_sizes;
        // Return one unit to stock because the user is reducing the cart quantity
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $size) {
                $entry['value']++;
            }
        }
        unset($entry);
        $product->product_sizes = $availableSizes;
        $product->save();

        if ($item->quantity > 1) {
            Cart::session($userId)->update($id, ['quantity' => -1]);
        } else {
            Cart::session($userId)->remove($id);
        }

        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );
        return redirect()->route('cart');
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
        ]);
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $cartData = Cart::session($userId)->getContent()->toArray();
        $cart = ModelCart::findOrFail($request->cart_id);
        $cart->address = $request->shipping_address;
        $cart->update();

//        $tax = Cart::getCondition('VAT 12.5%');
//        $shipping = Cart::getCondition('Shipping Cost');
//        $subTotal = Cart::session($userId)->getSubTotal();
//        $total = Cart::session($userId)->getTotal();
//


        // Save or update the cart record with the delivery address.
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            [
                'cart_data' => $cartData,
            ]
        );
        // Get cart conditions
        $tax = Cart::getCondition('VAT 12.5%');
        $shipping = Cart::getCondition('Shipping Cost');

        // Extract values safely
        $taxAmount = $tax ? $tax->getCalculatedValue(Cart::session($userId)->getSubTotal()) : 0;
        $shippingAmount = $shipping ? $shipping->getCalculatedValue(Cart::session($userId)->getSubTotal()) : 0;


        $subTotal = Cart::session($userId)->getSubTotal();
        $total = Cart::session($userId)->getTotal();

        // Create order record
        $order = Order::updateOrCreate([
            'user_id' => $userId,
            'subtotal' => $subTotal,
            'tax' => $taxAmount,
            'shipping_cost' => $shippingAmount,
            'total' => $total,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending', // Change as needed
        ]);
        $order->save();

        // Loop through cart items and attach products to the order
        foreach ($cartData as $item) {
            // Extract product_id and shoe_size from the composite ID (e.g., "1-39")
            list($productId, $shoeSize) = explode('-', $item['id']);

            // Attach product to the order with shoe_size and quantity
            $order->products()->attach($productId, [
                'size' => $shoeSize, // Shoe size extracted from the composite ID
                'quantity' => $item['quantity'], // Quantity from the cart item
            ]);
        }
        foreach ($order->products ?? [] as $product) {
            $vendor = $product->vendor;
            $vendor->notify(new NewOrderNotification($order));
        }
        return redirect()->route('payment')->with('success', 'Checkout successful. Please proceed to payment.');
    }


    public function payment() {
        $userId = auth()->id() ?? session()->getId();
        $user = User::where('id', $userId)->first();
        $cart = \App\Models\Cart::where('user_id', $userId)->latest()->first();
// Initialize empty cart data

        // Check if cart exists and has valid JSON data
        if ($cart && !empty($cart->cart_data)) {
            // Ensure cart_data is a string before decoding
            if (is_string($cart->cart_data)) {
                $cartData = json_decode($cart->cart_data, true);
            } else {
                // If already an array, use it directly
                $cartData = $cart->cart_data;
            }
        }



        $order = \App\Models\Order::where('user_id', $userId)->latest()->first();
        return view('front.payment', compact('order', 'user', 'cartData'));
    }

}
