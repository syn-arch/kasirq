<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $id_user = $request->get('id_user');

        if ($id_user) {
            $reports = DB::table('purchase_detail')
                ->selectRaw('
                DATE(purchase_detail.created_at) as date,
                product_name,
                purchase_detail.amount,
                purchase_detail.price,
                purchase_detail.discount,
                purchase_detail.total
                ')
                ->where('purchases.id_user', $id_user)
                ->join('purchases', 'purchase_detail.id_purchase', '=', 'purchases.id')
                ->join('products', 'purchase_detail.id_product', '=', 'products.id')
                ->orderBy('purchase_detail.created_at', 'ASC')
                ->get();
            $total = DB::table('purchase_detail')
                ->where('purchases.id_user', $id_user)
                ->join('purchases', 'purchase_detail.id_purchase', '=', 'purchases.id')
                ->selectRaw('SUM(purchase_detail.total) as total')->first()->total;
        } else {
            $reports = DB::table('purchase_detail')
                ->selectRaw('
                DATE(purchase_detail.created_at) as date,
                product_name,
                purchase_detail.amount,
                purchase_detail.price,
                purchase_detail.discount,
                purchase_detail.total
                ')
                ->join('products', 'purchase_detail.id_product', '=', 'products.id')
                ->orderBy('purchase_detail.created_at', 'ASC')
                ->get();
            $total = DB::table('purchase_detail')
                ->selectRaw('SUM(total) as total')->first()->total;
        }

        $users = User::all();
        return view('report.index', compact('reports', 'users', 'total'));
    }

    public function harian()
    {
        setlocale(LC_ALL, 'IND');
        $setting = Setting::first();
        $outlet = Outlet::first();

        $report = DB::table('purchases')
            ->selectRaw('SUM(discount) as diskon, SUM(rebate) as potongan, SUM(subtotal) AS pendapatan, SUM(total) as total_akhir')
            ->where('id_user', auth()->user()->id)
            ->first();

        try {
            // windows
            $connector = new WindowsPrintConnector($setting->printer);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($outlet->name . "\n");
            $printer->text($outlet->address . "\n");
            $printer->text($outlet->phone . "\n");
            $printer->text($outlet->email . "\n");
            $printer->text("---------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Tgl    : " . strftime("%A, %d %B %Y %H:%M", strtotime(date('Y-m-d H:i:s'))) . "\n");
            $printer->text("Kasir  : " . auth()->user()->name . "\n");
            $printer->text("---------------------------------------\n");
            $printer->text("Pendapatan  : " . number_format($report->pendapatan) . "\n");
            $printer->text("Diskon      : " . number_format($report->diskon) . "%\n");
            $printer->text("Potongan    : " . number_format($report->potongan) . "\n");
            $printer->text("Total Akhir : " . number_format($report->total_akhir) . "\n");
            $printer->text("---------------------------------------\n");
            $printer->text("***TERIMA KASIH***\n");
            $printer->feed(2);
            $printer->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
