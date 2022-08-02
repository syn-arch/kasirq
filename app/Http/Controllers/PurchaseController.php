<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->role === 'kasir') {
            $purchases = Purchase::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->get();
        } else {
            $start = $request->get('start');
            $end = $request->get('end');

            if ($start) {
                $purchases = Purchase::whereRaw("DATE(created_at) >= '${start}'")
                    ->whereRaw("DATE(created_at) <= '${end}'")
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $purchases = Purchase::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->get();
            }
        }
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $noTitle = true;
        return view('purchase.create', compact('products', 'noTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $puchase = Purchase::create([
                'total' => $request->total,
                'subtotal' => $request->subtotal,
                'id_user' => auth()->user()->id,
                'discount' => $request->discount,
                'rebate' => $request->rebate,
                'cash' => str_replace('.', '', $request->cash),
            ]);

            for ($i = 0; $i < count($request->id_product); $i++) {
                PurchaseDetail::create([
                    'id_purchase' => $puchase->id,
                    'id_product' => $request->id_product[$i],
                    'amount' => $request->amount[$i],
                    'price' => $request->price[$i],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        return redirect('/purchases/create')->with('message', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        return view('purchase.show', compact('purchase'));
    }

    public function cetak(Purchase $purchase)
    {
        setlocale(LC_ALL, 'IND');
        $setting = Setting::first();
        $outlet = Outlet::first();

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
            $printer->text("Tgl    : " . strftime("%A, %d %B %Y %H:%M", strtotime($purchase->created_at)) . "\n");
            $printer->text("Kasir  : " . $purchase->user->name . "\n");

            $printer->text("----------------------------------------\n");
            $printer->text("Barang          Jml    Harga    SubTotal\n");
            $printer->text("----------------------------------------\n");

            // item barang
            foreach ($purchase->purchase_detail as $row) {
                $harga = number_format($row->price);
                $total_harga = number_format($row->price * $row->amount);
                $printer->text($row->product->product_name . "\n");
                $printer->setJustification(2);
                $printer->text("             {$row->amount} X {$harga}   {$total_harga}\n");
                $printer->setJustification();
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("---------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);

            $lineTotal = sprintf('%-5.40s %-1.05s %13.40s', 'Total Belanja', '=', number_format($purchase->subtotal));
            $printer->text("$lineTotal\n");
            $lineDisc = sprintf('%-5.40s %-1.05s %13.40s', 'Diskon', '=', $purchase->discount . ' %');
            $printer->text("$lineDisc\n");
            $linePotongan = sprintf('%-5.40s %-1.05s %13.40s', 'Potongan', '=', number_format($purchase->potongan));
            $printer->text("$linePotongan\n");
            $lineTotal = sprintf('%-5.40s %-1.05s %13.40s', 'Total Bayar', '=', number_format($purchase->total));
            $printer->text("$lineTotal\n");
            $cash = sprintf('%-5.40s %-1.05s %13.40s', 'Cash', '=', number_format($purchase->cash));
            $printer->text("$cash\n");
            $lineKembalian = sprintf('%-5.40s %-1.05s %13.40s', 'Kembalian', '=', number_format($purchase->cash - $purchase->total));
            $printer->text("$lineKembalian\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("---------------------------------------\n");
            $printer->text("***TERIMA KASIH***\n");
            $printer->feed(2);
            $printer->close();

            return redirect('purchases/create');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function print(Purchase $purchase)
    {
        $outlet = Outlet::first();
        $pdf = FacadePdf::loadview('purchase.print', compact('purchase', 'outlet'));
        $pdf->setOptions([
            'dpi' => 50,
        ]);
        $pdf->setPaper('a6', 'potrait');

        return $pdf->stream('purchase.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $products = Product::all();
        $noTitle = true;
        return view('purchase.edit', compact('purchase', 'products', 'noTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        DB::beginTransaction();

        $purchase->purchase_detail()->delete();
        $purchase->delete();

        try {
            $puchase = Purchase::create([
                'total' => $request->total,
                'subtotal' => $request->subtotal,
                'id_user' => auth()->user()->id,
                'discount' => $request->discount,
                'rebate' => $request->rebate,
                'cash' => str_replace(',', '', str_replace('.', '', $request->cash)),
            ]);

            for ($i = 0; $i < count($request->id_product); $i++) {
                PurchaseDetail::create([
                    'id_purchase' => $puchase->id,
                    'id_product' => $request->id_product[$i],
                    'amount' => $request->amount[$i],
                    'price' => $request->price[$i],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        return redirect('/purchases')->with('message', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        PurchaseDetail::where('id_purchase', $purchase->id)->delete();
        $purchase->delete();

        return redirect('/purchases')->with('message', 'Data berhasil dihapus');
    }

    public function get_product($id_product)
    {
        return response()->json([
            'data' => Product::find($id_product)
        ]);
    }
}
