<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class VendorController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Vendors';
        $this->resources = 'admins.vendors.';
        parent::__construct();
        $this->route = 'vendors.';
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
            $data = Vendor::orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_sales', function ($row) {
                    return $row->total_sales ?? 'N/A';
                })
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
        $info['routeName'] = "Create";
        return view($this->createResource(), $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        $data = $request->all();
        $vendor = new Vendor($data);
        $vendor->password = bcrypt($data['password']);
        $vendor->save();
        if ($request->image) {
            $vendor->addMediaFromRequest('image')
                ->toMediaCollection();
        }
        return redirect()->route($this->indexRoute())->with('success', 'Vendor added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Vendor::findOrFail($id);
        return view($this->showResource(), $info);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Vendor::findOrFail($id);
        $info['routeName'] = "Edit";
        return view($this->editResource(), $info);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $vendor = Vendor::findOrFail($id);
        $vendor->name = $request->get('name');
        $vendor->email = $request->get('email');
        $vendor->phone = $request->get('phone');
        $vendor->description = $request->get('description');
        $vendor->update();
        if ($request->image) {
            $vendor->clearMediaCollection();
            $vendor->addMediaFromRequest('image')
                ->toMediaCollection();
        }
        return redirect()->route($this->indexRoute())->with('success', 'Vendor updated successfully.');;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->clearMediaCollection();
        $vendor->delete();
        return redirect()->route($this->indexRoute())->with('success', 'Vendor deleted successfully.');
    }
}
