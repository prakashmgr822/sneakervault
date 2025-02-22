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
                'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => '12.5%'
            ));
            $condition2 = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Shipping Cost',
                'type' => 'shipping',
                'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
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

        return view('front/cart', compact('cartItems', 'tax', 'shipping', 'subTotal', 'total')); // Pass to view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Http\Response
     */
    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        // Use the authenticated user's ID or fallback to the session ID for guests
        $userId = auth()->check() ? auth()->id() : session()->getId();


        // Use the user's session to store the cart
        Cart::session($userId)->add([
            'id'       => $product->id,
            'name'     => $product->name,
            'price'    => $product->price,
            'quantity' => 1,
            'attributes' => [
                'image'      => $product->getImage(),
            ]
        ]);

        // Save the cart to database
        $cart = Cart::session($userId)->getContent()->toArray();

        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );


        return redirect('cart')->with('success', 'Product added to cart!');
    }


    public function clearCart()
    {
        $userId = auth()->id() ?? session()->getId();
        Cart::session($userId)->clear();

        // Remove from database
        ModelCart::where('user_id', $userId)->delete();

        return redirect()->route('cart')->with('success', 'Cart cleared successfully.');
    }

    public function removeItem($id)
    {
        $userId = auth()->id() ?? session()->getId();
        Cart::session($userId)->remove($id);

        // Save updated cart to database
        $cart = Cart::session($userId)->getContent()->toArray();
        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }

    public function increaseQuantity($id)
    {
        $userId = auth()->id() ?? session()->getId();
//        $item = Cart::session($userId)->update($id, ['quantity' => 1]);
        $item = Cart::session($userId)->get($id);
        $product = Product::find($id);

        // Check if product exists
        if (!$product) {
            return redirect()->route('cart')->with('error', 'Product not found.');
        }

        // Check if the item exists in the cart
        if (!$item) {
            return redirect()->route('cart')->with('error', 'Item not found in cart.');
        }

        // Check if increasing quantity exceeds stock
        if ($item->quantity + 1 > $product->stock_quantity) {
            return redirect()->route('cart')->with('error', 'Not enough stock available for '. $product->name .'.');
        }

        // Attempt to update quantity
        $updated = Cart::session($userId)->update($id, [
            'quantity' => ['relative' => true, 'value' => 1]
        ]);

        if (!$updated) {
            return redirect()->route('cart')->with('error', 'Failed to update quantity.');
        }

        // Save the cart to database
        $cart = Cart::session($userId)->getContent()->toArray();

        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect()->route('cart');
    }

    public function decreaseQuantity($id)
    {
        $userId = auth()->id() ?? session()->getId();
        $item = Cart::session($userId)->get($id);

        if ($item->quantity > 1) {
            Cart::session($userId)->update($id, ['quantity' => -1]);
        } else {
            Cart::session($userId)->remove($id);
        }

        // Save the cart to database
        $cart = Cart::session($userId)->getContent()->toArray();

        \App\Models\Cart::updateOrCreate(
            ['user_id' => $userId],
            ['cart_data' => $cart]
        );

        return redirect()->route('cart');
    }


    public function payment() {
        return view('front.payment');
    }
}
