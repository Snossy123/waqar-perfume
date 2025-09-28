<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Governorate::all());
    }
}