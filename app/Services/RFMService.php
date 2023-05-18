<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RFMService {
    public static function rfm($subQuery, $rfmPrms, $rfmType) {
        //1. 購入ID毎にまとめる
        $subQuery = $subQuery->groupBy('id')
            ->selectRaw('id, customer_id, customer_name, 
                sum(subtotal) as totalPerPurchase, created_at');
            
        //2. 会員毎にまとめて最終購入日、回数、合計金額を取得
        $subQuery = DB::table($subQuery)
            ->groupBy('customer_id')
            ->selectRaw('customer_id, customer_name, 
                max(created_at) as recentDate,
                datediff(now(), max(created_at)) as recency,
                count(customer_id) as frequency,
                sum(totalPerPurchase) as monetary');

        //4. 会員毎のRFMランクを定義
        $subQuery = DB::table($subQuery)
            ->selectRaw('customer_id, customer_name,
                recentDate, recency, frequency, monetary,
                case
                    when recency < ? then 5
                    when recency < ? then 4
                    when recency < ? then 3
                    when recency < ? then 2
                    else 1 end as r,
                case
                    when ? <= frequency then 5
                    when ? <= frequency then 4
                    when ? <= frequency then 3
                    when ? <= frequency then 2
                    else 1 end as f,
                case
                    when ? <= monetary then 5
                    when ? <= monetary then 4
                    when ? <= monetary then 3
                    when ? <= monetary then 2
                    else 1 end as m
                ', $rfmPrms);

        // Log::debug($subQuery->get());

        // 5. ランク毎の数を計算
        $totals = DB::table($subQuery)->count();

        $rCount = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'r')
            ->groupBy('rank')
            ->selectRaw('rank as r, count(r)')
            ->orderBy('r', 'desc')
            ->pluck('count(r)');

        // Log::debug("rCount: " + $rCount);

        $fCount = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'f')
            ->groupBy('rank')
            ->selectRaw('rank as f, count(f)')
            ->orderBy('f', 'desc')
            ->pluck('count(f)');

        $mCount = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'm')
            ->groupBy('rank')
            ->selectRaw('rank as m, count(m)')
            ->orderBy('m', 'desc')
            ->pluck('count(m)');

        $eachCount = []; //Vue側に渡す用の空の配列
        $rank = 5; //初期値5

        for($i = 0; $i < 5; $i++) {
            array_push($eachCount, [
                'rank' => $rank,
                'r' => $rCount[$i],
                'f' => $fCount[$i],
                'm' => $mCount[$i],
            ]);
            $rank--; //rankを1ずつ減らす
        }

        //6. RとFで2次元で表示
        if($rfmType === 'rf') {
            $data = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'r')
            ->groupBy('rank')
            ->selectRaw('concat("r_",rank) as rRank,
                count(case when f = 5 then 1 end) as f_5,
                count(case when f = 4 then 1 end) as f_4,
                count(case when f = 3 then 1 end) as f_3,
                count(case when f = 2 then 1 end) as f_2,
                count(case when f = 1 then 1 end) as f_1')
            ->orderBy('rRank', 'desc')
            ->get();
        } else if($rfmType === 'rm') {
            $data = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'r')
            ->groupBy('rank')
            ->selectRaw('concat("r_",rank) as rRank,
                count(case when m = 5 then 1 end) as m_5,
                count(case when m = 4 then 1 end) as m_4,
                count(case when m = 3 then 1 end) as m_3,
                count(case when m = 2 then 1 end) as m_2,
                count(case when m = 1 then 1 end) as m_1')
            ->orderBy('rRank', 'desc')
            ->get();
        } else if($rfmType === 'fm') {
            $data = DB::table($subQuery)
            ->rightJoin('ranks', 'ranks.rank', '=', 'f')
            ->groupBy('rank')
            ->selectRaw('concat("f_",rank) as fRank,
                count(case when m = 5 then 1 end) as m_5,
                count(case when m = 4 then 1 end) as m_4,
                count(case when m = 3 then 1 end) as m_3,
                count(case when m = 2 then 1 end) as m_2,
                count(case when m = 1 then 1 end) as m_1')
            ->orderBy('fRank', 'desc')
            ->get();
        }

        return [$data, $totals, $eachCount, $rfmType];
    }
}