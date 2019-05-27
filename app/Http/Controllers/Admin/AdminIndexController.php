<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminIndexController extends AdminCommonController
{
    //
    public function index()
    {
        return view('admin/index');
    }

    public function admin_login(Request $request)
    {
        if ($request->isMethod('GET')){
            return view('admin.login');
        }
        else{
            







        }

    }
}
