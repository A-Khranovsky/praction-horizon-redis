<?php

namespace App\Services;

use Illuminate\Http\Request;

interface QueueControllerServiceInterface
{
    public function start(Request $request);
}
