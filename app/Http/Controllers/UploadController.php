<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Upload';
        $this->resources = 'uploader.';
        parent::__construct();
        $this->route = 'uploader.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data['status']=true;
            $requestData = $request->all();
            $upload = new Upload($requestData);
            $upload->save();
            if ($request->file) {
                $upload->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
            $data['data'] = $upload;
            $data['data']->url=$upload->getImage();
        }catch (\Exception $e){
            $data['status']=false;
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data['status']=true;
            $requestData = $request->all();
            $upload = new Upload($requestData);
            $upload->save();
            if ($request->file) {
                $upload->clearMediaCollection();
                $upload->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
            $data['data'] = $upload;
            $data['data']->url=$upload->getImage();
        }catch (\Exception $e){
            $data['status']=false;
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data['status']=true;
            $upload = Upload::findOrFail($id);
            $upload->clearMediaCollection();
            $upload->delete();
        }catch (\Exception $e){
            $data['status']=false;
        }
        return $data;
    }
}
