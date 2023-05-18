<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class AnalysisService {
    public static function execAnalyze($subQuery, $type) {
        $midQuery = $subQuery->where('status', true)->groupBy('id');

        if($type === 'perDay') {
            $query = $midQuery->selectRaw('id, sum(subtotal) as totalPerPurchase,
                    DATE_FORMAT(created_at, "%Y%m%d") as date');
        } else if($type === 'perMonth') {
            $query = $midQuery->selectRaw('id, sum(subtotal) as totalPerPurchase,
                    DATE_FORMAT(created_at, "%Y%m") as date');
        } else if($type === 'perYear') {
            $query = $midQuery->selectRaw('id, sum(subtotal) as totalPerPurchase,
                    DATE_FORMAT(created_at, "%Y") as date');
        }

        $data = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();

        $labels = $data->pluck('date');
        $totals = $data->pluck('total');

        return [$data, $labels, $totals];
    }
}