<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Customer\NewOrderPlacedNotification;
use App\Notifications\Vendor\NewOrderNotification;
use Darryldecode\Cart\Exceptions\InvalidConditionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Cart as ModelCart;
use function PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\datedifD;

class FrontController extends Controller
{
    public function home()
    {
        $products = Product::inRandomOrder()->limit(3)->get();
        return view('front/welcome', compact('products'));
    }

    public function product()
    {
        $products = Product::paginate(30);
        return view('front/product', compact('products'));
    }

    public function about()
    {
        return view('front/about');
    }

    public function productDetails($id)
    {
        $product = Product::findOrFail($id);
        return view('front/product-details', compact('product'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('brand', 'LIKE', "%{$query}%")
            ->get();

        // Attach a custom property (e.g., image_url) to each product.
        $products->each(function ($product) {
            $product->image_url = $product->getImage();
        });

        return response()->json($products);
    }

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
        $userId = auth()->id() ?? session()->getId();
        $savedCart = ModelCart::where('user_id', $userId)->first();

        if ($savedCart) {
            Cart::session($userId)->clear();

            foreach ($savedCart->cart_data as $item) {
                Cart::session($userId)->add($item);
            }
        }

        $cartItems = Cart::session($userId)->getContent();
        try {
            $condition1 = new \Darryldecode\Cart\CartCondition([
                'name'   => 'VAT 12.5%',
                'type'   => 'tax',
                'target' => 'total',
                'value'  => '12.5%'
            ]);
            $condition2 = new \Darryldecode\Cart\CartCondition([
                'name'   => 'Shipping Cost',
                'type'   => 'shipping',
                'target' => 'subtotal',
                'value'  => '15'
            ]);
            Cart::condition([$condition1]);
            Cart::condition([$condition2]);
        } catch (InvalidConditionException $e) {
            error_log("cart_error", $e->getMessage());
        }

        $tax = Cart::getCondition('VAT 12.5%');
        $shipping = Cart::getCondition('Shipping Cost');
        $subTotal = Cart::session($userId)->getSubTotal();
        $total = Cart::session($userId)->getTotal();
        $cartDetails = ModelCart::where('user_id', $userId)->first();

        return view('front/cart', compact('cartItems', 'tax', 'shipping', 'subTotal', 'total', 'cartDetails'));
    }

    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $userId = auth()->check() ? auth()->id() : session()->getId();
        $size = trim($request->get('size'));

        $availableSizes = $product->product_sizes;
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
        ModelCart::updateOrCreate(
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

        // Remove the old cart item and add a new one with the updated size
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
        ModelCart::updateOrCreate(
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
        ModelCart::where('user_id', $userId)->delete();

        return redirect()->route('cart')->with('success', 'Cart cleared successfully.');
    }

    public function removeItem($id)
    {
        $userId = auth()->id() ?? session()->getId();
        $item = Cart::session($userId)->get($id);
        if (!$item) {
            return redirect()->route('cart')->with('error', 'Item not found in cart.');
        }

        list($productId, $size) = explode('-', $id);
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

        Cart::session($userId)->remove($id);

        $cart = Cart::session($userId)->getContent()->toArray();
        ModelCart::updateOrCreate(
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
        foreach ($availableSizes as &$entry) {
            if ($entry['name'] == $size) {
                $entry['value']--;
            }
        }
        unset($entry);
        $product->product_sizes = $availableSizes;
        $product->save();

        Cart::session($userId)->update($id, [
            'quantity' => ['relative' => true, 'value' => 1]
        ]);

        $cart = Cart::session($userId)->getContent()->toArray();
        ModelCart::updateOrCreate(
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
        ModelCart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );
        return redirect()->route('cart');
    }

    /**
     * Updated checkout method.
     * Now, we only save the shipping address with the cart without creating an order.
     */
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

        ModelCart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cartData, 'address' => $request->shipping_address]
        );

        return redirect()->route('payment')->with('success', 'Checkout successful. Please proceed to payment.');
    }

    /**
     * Updated payment method.
     * Instead of fetching an order, we calculate an order summary from the cart and pass it to the view.
     */
    public function payment()
    {
        $userId = auth()->id() ?? session()->getId();
        $user = User::where('id', $userId)->first();
        $cart = ModelCart::where('user_id', $userId)->latest()->first();
        if ($cart && !empty($cart->cart_data)) {
            $cartData = is_string($cart->cart_data) ? json_decode($cart->cart_data, true) : $cart->cart_data;
        } else {
            $cartData = [];
        }
        $taxCondition = Cart::getCondition('VAT 12.5%');
        $shippingCondition = Cart::getCondition('Shipping Cost');
        $subTotal = Cart::session($userId)->getSubTotal();
        $taxAmount = $taxCondition ? $taxCondition->getCalculatedValue($subTotal) : 0;
        $shippingCost = $shippingCondition ? $shippingCondition->getCalculatedValue($subTotal) : 0;
        $total = Cart::session($userId)->getTotal();

        // Create a temporary order summary object for the view.
        $orderSummary = (object)[
            'shipping_address' => $cart ? $cart->address : '',
            'shipping_cost'    => $shippingCost,
            'subtotal'         => $subTotal,
            'tax'              => $taxCondition ? $taxCondition->getValue() : '',
            'total'            => $total
        ];

        return view('front.payment', compact('user', 'cartData', 'orderSummary'));
    }

    /**
     * Updated processKhaltiPayment: the order is created (if not already) only when payment is confirmed.
     */
    public function processKhaltiPayment(Request $request)
    {
        $userId = auth()->id();
        $user = User::findOrFail($userId);
        $cartData = Cart::session($userId)->getContent()->toArray();
        $taxCondition = Cart::getCondition('VAT 12.5%');
        $shippingCondition = Cart::getCondition('Shipping Cost');
        $subTotal = Cart::session($userId)->getSubTotal();
        $taxAmount = $taxCondition ? $taxCondition->getCalculatedValue($subTotal) : 0;
        $shippingAmount = $shippingCondition ? $shippingCondition->getCalculatedValue($subTotal) : 0;
        $total = Cart::session($userId)->getTotal();

        // Create order only at payment confirmation if it doesn't already exist.
        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first();
        if (!$order) {
            $cartRecord = ModelCart::where('user_id', $userId)->latest()->first();
            $shippingAddress = $cartRecord ? $cartRecord->address : '';
            $order = Order::create([
                'user_id'          => $userId,
                'subtotal'         => $subTotal,
                'tax'              => $taxAmount,
                'shipping_cost'    => $shippingAmount,
                'total'            => $total,
                'shipping_address' => $shippingAddress,
                'status'           => 'pending',
            ]);
            foreach ($cartData as $item) {
                list($productId, $shoeSize) = explode('-', $item['id']);
                $order->products()->attach($productId, [
                    'size'     => $shoeSize,
                    'quantity' => $item['quantity'],
                ]);
            }
            foreach ($order->products ?? [] as $product) {
                $vendor = $product->vendor;
                $vendor->notify(new NewOrderNotification($order));
            }
        }

        // Process the Khalti payment and update order status.
        $order->status = 'paid';
        $order->save();


        Cart::session($userId)->clear();
        ModelCart::where('user_id', $userId)->delete();

        $user->notify(new NewOrderPlacedNotification($order));

        return redirect()->route('order.success')->with('success', 'Payment successful!');
    }

    /**
     * Updated processCodPayment: the order is created only when the user confirms Cash on Delivery.
     */
    public function processCodPayment(Request $request)
    {
        $userId = auth()->id();
        $user = User::findOrFail($userId);
        $cartData = Cart::session($userId)->getContent()->toArray();
        $taxCondition = Cart::getCondition('VAT 12.5%');
        $shippingCondition = Cart::getCondition('Shipping Cost');
        $subTotal = Cart::session($userId)->getSubTotal();
        $taxAmount = $taxCondition ? $taxCondition->getCalculatedValue($subTotal) : 0;
        $shippingAmount = $shippingCondition ? $shippingCondition->getCalculatedValue($subTotal) : 0;
        $total = Cart::session($userId)->getTotal();

        $order = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first();
        if (!$order) {
            $cartRecord = ModelCart::where('user_id', $userId)->latest()->first();
            $shippingAddress = $cartRecord ? $cartRecord->address : '';
            $order = Order::create([
                'user_id'          => $userId,
                'subtotal'         => $subTotal,
                'tax'              => $taxAmount,
                'shipping_cost'    => $shippingAmount,
                'total'            => $total,
                'shipping_address' => $shippingAddress,
                'status'           => 'pending',
            ]);
            foreach ($cartData as $item) {
                list($productId, $shoeSize) = explode('-', $item['id']);
                $order->products()->attach($productId, [
                    'size'     => $shoeSize,
                    'quantity' => $item['quantity'],
                ]);
            }
            foreach ($order->products ?? [] as $product) {
                $vendor = $product->vendor;
                $vendor->notify(new NewOrderNotification($order));
            }
        }

        $order->status = 'cash_on_delivery';
        $order->save();


        Cart::session($userId)->clear();
        ModelCart::where('user_id', $userId)->delete();

        $user->notify(new NewOrderPlacedNotification($order));

        return redirect()->route('order.success')->with('success', 'Order placed successfully! Cash on Delivery.');
    }

    public function orderSuccess()
    {
        return view("front.order-success");
    }
}
