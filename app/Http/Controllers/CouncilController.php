<?php

namespace App\Http\Controllers;

use App\Models\Council;

class CouncilController extends Controller
{
    public function index()
    {
        $councils = Council::select('id', 'name', 'description')->get();
        return response()->json(['status' => 'success', 'data' => $councils]);
    }
}
