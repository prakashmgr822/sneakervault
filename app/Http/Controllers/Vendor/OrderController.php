<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\datedifD;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Orders';
        $this->resources = 'vendors.orders.';
        parent::__construct();
        $this->route = 'orders.';
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get the authenticated vendor
            $vendor = Vendor::find(auth('vendor')->id());

            // Fetch orders that include at least one product from the vendor
            $orders = Order::whereHas('products', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id); // Filter orders with products from the vendor
            })
                ->with(['products' => function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id); // Load only the vendor's products
                }])
                ->with('user') // Load the user details
                ->select('orders.*'); // Select only orders table columns

            return DataTables::of($orders)
                ->addIndexColumn() // Add DT_RowIndex
                ->addColumn('user', function ($order) {
                    // Display user information
                    return $order->user ? "{$order->user->name} ({$order->user->phone})" : 'N/A';
                })
                ->addColumn('products', function ($order) use ($vendor) {
                    // Filter products to include only the vendor's products
                    $vendorProducts = $order->products->filter(function ($product) use ($vendor) {
                        return $product->vendor_id === $vendor->id;
                    });

                    // Display product details (name, size, quantity) with hyperlink
                    return $vendorProducts->map(function ($product) {
                        $productUrl = route('products.show', $product->id); // Generate product URL
                        return "<a href='{$productUrl}' target='_blank'>{$product->name}</a> [Size: {$product->pivot->size}, Qty: {$product->pivot->quantity}]";
                    })->implode('<hr>');
                })

                ->rawColumns(['products']) // Render HTML in products and action columns
                ->make(true);
        }
        $info = $this->crudInfo();
        return view($this->indexResource(), $info, ['hideCreate' => true]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
