<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use App\Services\RFMService;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request) {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if($request->type === 'decile') {
            list($data, $labels, $totals) = DecileService::decile($subQuery);
        } else if($request->type === 'rfm') {
            list($data, $totals, $eachCount) = RFMService::rfm($subQuery, $request->rfmPrms, $request->rfmType);

            return response()->json([
                'data' => $data,
                'type' => $request->type,
                'eachCount' => $eachCount,
                'totals'=> $totals,
                'rfmType' => $request->rfmType,
            ], Response::HTTP_OK);
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
