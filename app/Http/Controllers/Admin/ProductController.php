<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Products';
        $this->resources = 'admins.products.';
        parent::__construct();
        $this->route = 'admin.products.';
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
            $data = Product::orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('vendor_id', function ($data) {
                    return '<a target="_blank" href="' . route("vendors.show", $data->vendor->id) . '">' . $data->vendor->name . '</a>';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route, 'hideEdit' => true
                    ])->render();
                })
                ->rawColumns(['action', 'vendor_id'])
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
        $info = $this->crudInfo();
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
        $request->validate([
            'sizes' => 'nullable|string'
        ]);
        if ($request->specifications) {
            $request['specifications'] = json_decode($request->specifications, true);
        }

        $data = $request->all();

        if ($request->sizes) {
            $data['sizes'] = implode(', ', array_map('trim', explode(',', $request->sizes)));
        }

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
        return view($this->showResource(), $info, ['hideEdit' => true]);
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
        $request->validate([
            'sizes' => 'nullable|string',
        ]);

        if ($request->specifications) {
            $request['specifications'] = json_decode($request->specifications, true);
        }

        $data = $request->all();

        if ($request->sizes) {
            $data['sizes'] = implode(', ', array_map('trim', explode(',', $request->sizes)));
        }
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
