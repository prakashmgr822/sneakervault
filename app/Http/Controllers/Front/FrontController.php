<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Product;
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
            'address' => 'required|string|max:255',
        ]);

        $userId = auth()->check() ? auth()->id() : session()->getId();
        $cartData = Cart::session($userId)->getContent()->toArray();

        // Save or update the cart record with the delivery address.
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            [
                'cart_data' => $cartData,
                'address'   => $request->address,
            ]
        );
        return redirect()->route('payment')->with('success', 'Checkout successful. Please proceed to payment.');
    }


    public function payment() {
        $userId = auth()->id() ?? session()->getId();

        $cartRecord = \App\Models\Cart::where('user_id', $userId)->first();
        $address = $cartRecord ? $cartRecord->address : null;

        $info['tax'] = Cart::getCondition('VAT 12.5%');
        $info['shipping'] = Cart::getCondition('Shipping Cost');
        $info['subTotal'] = Cart::session($userId)->getSubTotal();
        $info['total'] = Cart::session($userId)->getTotal();
        $info['address'] = $address;

        return view('front.payment', $info);
    }

}
