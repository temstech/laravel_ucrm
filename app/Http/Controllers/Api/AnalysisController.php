<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request) {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if($request->type === 'decile') {
            list($data, $labels, $totals) = DecileService::decile($subQuery, $request->type);
        } else {
            list($data, $labels, $totals) = AnalysisService::execAnalyze($subQuery, $request->type);
        }

        //Ajax通信なのでJsonで返却する
        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals'=> $totals,
        ], Response::HTTP_OK);
    }
}
