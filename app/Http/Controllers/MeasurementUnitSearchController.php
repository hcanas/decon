<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeasurementUnitSearchController extends Controller
{
    public function __invoke(Request $request) : JsonResponse
    {
        return response()->json(MeasurementUnit::search($request->get('keyword'))
            ->take(5)
            ->get()
            ->pluck('value')
        );
    }
}
