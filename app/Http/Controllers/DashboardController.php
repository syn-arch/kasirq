<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $purchase_total = DB::table('purchases')->whereRaw('date(created_at) = CURDATE()')->sum('total');
        $purchase_count = DB::table('purchases')->whereRaw('date(created_at) = CURDATE()')->count('*');
        $product_count = DB::table('products')->count('*');
        $user_count = DB::table('users')->count('*');

        $most_purchased = DB::table('purchase_detail')
            ->selectRaw('product_name, sum(amount) as total')
            ->join('products', 'purchase_detail.id_product', '=', 'products.id')
            ->limit(5)
            ->groupBy('product_name')
            ->get()
            ->toArray();

        $most_purchased_label = array_column($most_purchased, 'product_name');
        $most_purchased_value = array_column($most_purchased, 'total');

        $purchase_chart = DB::table('purchases')
            ->selectRaw('month(created_at) as created_at, sum(total) as total')
            ->groupByRaw('month(created_at)')
            ->get();

        $purchase_chart_value = $purchase_chart->pluck('total')->toArray();
        $purchase_chart_label = $purchase_chart->pluck('created_at')->map(function ($date) {
            return $this->MonthNumberToMonthName($date);
        })->toArray();

        return view('dashboard', compact(
            'purchase_total',
            'purchase_count',
            'product_count',
            'user_count',
            'most_purchased_label',
            'most_purchased_value',
            'purchase_chart_value',
            'purchase_chart_label'
        ));
    }

    private function MonthNumberToMonthName($monthNumber)
    {
        $monthName = '';
        switch ($monthNumber) {
            case 1:
                $monthName = 'Januari';
                break;
            case 2:
                $monthName = 'Februari';
                break;
            case 3:
                $monthName = 'Maret';
                break;
            case 4:
                $monthName = 'April';
                break;
            case 5:
                $monthName = 'Mei';
                break;
            case 6:
                $monthName = 'Juni';
                break;
            case 7:
                $monthName = 'Juli';
                break;
            case 8:
                $monthName = 'Agustus';
                break;
            case 9:
                $monthName = 'September';
                break;
            case 10:
                $monthName = 'Oktober';
                break;
            case 11:
                $monthName = 'November';
                break;
            case 12:
                $monthName = 'Desember';
                break;
        }
        return $monthName;
    }
}
