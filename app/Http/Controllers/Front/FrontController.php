<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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

    public function cart() {
        return view('front/cart');
    }
}
