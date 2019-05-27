<?php

namespace App\Http\Controllers;

use App\Http\Models\ChatLog;
use App\Http\Models\GeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    //
    public function setPublishedAt($value)
    {
        $this->attributes['published_at'] = strtotime($value);
    }
    public function test(Request $request)
    {

        if ($request->isMethod('POST')) {

            $input = $request->all();
            dd($input);
            return $input;

        }
        else{
            $test = ChatLog::where('from_id',100000)->first()->created_at;
            $u =strtotime($test)*1000;
            dd($u);
            return view('test');
        }
    }
}
