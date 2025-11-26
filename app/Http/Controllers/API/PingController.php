<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PingController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'pong']);
    }
}
