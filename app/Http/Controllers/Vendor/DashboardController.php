<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $data['products'] = Product::where('vendor_id', auth('vendor')->user()->id)->count();

        return view('vendors.home', $data);
    }

    public function changePassword()
    {
        $info['title'] = 'Change Password';
        return view('vendors.changePassword', $info);
    }

    function changePasswordSave(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $vendor = Vendor::findOrFail(auth('vendor')->user()->id);
        if (Hash::check($request->old_password, $vendor->password)) {
            $vendor->password = Hash::make($request->new_password);
            $vendor->save();
            return redirect()->back()->with('success', 'Password Changed Successfully.');
        } else {
            return redirect()->back()->with('error', 'Old Password Mismatched.')->withInput($request->input());
        }
    }
}
