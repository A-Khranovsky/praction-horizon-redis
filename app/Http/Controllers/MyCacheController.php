<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MyCacheController extends Controller
{
    public function put(Request $request)
    {
        Cache::put('message', $request->message, now()->addMinute());

        return redirect('/');
    }
}
