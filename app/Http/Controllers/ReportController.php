<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $id_user = $request->get('id_user');

        if ($id_user) {
            $reports = DB::table('purchases as A')
                ->select(DB::raw('date(A.created_at) as date,
                            C.price * sum(B.amount) as total,
                            sum(B.amount) as amount,
                            C.product_name,
                            C.price
                            '))
                ->join('purchase_detail as B', 'A.id', '=', 'B.id_purchase')
                ->join('products as C', 'C.id', '=', 'B.id_product')
                ->whereBetween(DB::raw("date(A.created_at)"), [$start, $end])
                ->whereRaw(DB::raw('A.id_user = ' . $id_user))
                ->groupByRaw('date(A.created_at), C.id, C.product_name, C.price')
                ->get();
        } else {
            $reports = DB::table('purchases as A')
                ->select(DB::raw('date(A.created_at) as date,
                            C.price * sum(B.amount) as total,
                            sum(B.amount) as amount,
                            C.product_name,
                            C.price
                            '))
                ->join('purchase_detail as B', 'A.id', '=', 'B.id_purchase')
                ->join('products as C', 'C.id', '=', 'B.id_product')
                ->whereBetween(DB::raw("date(A.created_at)"), [$start, $end])
                ->groupByRaw('date(A.created_at), C.id, C.product_name, C.price')
                ->get();
        }

        $users = User::all();
        return view('report.index', compact('reports', 'users'));
    }
}
