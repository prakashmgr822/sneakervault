<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Products';
        $this->resources = 'vendors.products.';
        parent::__construct();
        $this->route = 'products.';
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
            $data = Product::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $info = $this->crudInfo();
        return view($this->indexResource(), $info);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $info = $this->crudInfo();
        $info['method'] = "create";
        return view($this->createResource(), $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->specifications) {
            $request['specifications'] = json_decode($request->specifications, true);
        }

        if ($request->product_sizes) {
            $request['product_sizes'] = json_decode($request->product_sizes, true);
        }

        $data = $request->all();
        $product = new Product($data);
        $product->vendor_id = auth('vendor')->user()->id;
        $product->save();
        if ($request->image) {
            $product->addMediaFromRequest('image')
                ->toMediaCollection();
        }
        return redirect()->route($this->indexRoute())->with('success', 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Product::findOrFail($id);
        return view($this->showResource(), $info);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Product::findOrFail($id);
        $info['method'] = "edit";
        return view($this->editResource(), $info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->specifications) {
            $request['specifications'] = json_decode($request->specifications, true);
        }

        if ($request->product_sizes) {
            $request['product_sizes'] = json_decode($request->product_sizes, true);
        }

        $data = $request->all();
        $product = Product::findOrFail($id);
        $product->update($data);
        if ($request->image) {
            $product->clearMediaCollection();
            $product->addMediaFromRequest('image')
                ->toMediaCollection();
        }
        return redirect()->route($this->indexRoute())->with('success', 'Product edited successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route($this->indexRoute())->with('success', 'Product deleted successfully.');
    }
}
