<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase</title>
    <style>
        @page {
            margin: 5px;
        }

        body {
            margin: 5px;
        }

        * {
            font-family: sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            width: 100%
        }

        table {
            width: 100%;
            margin: auto;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .border {
            border-top: 1px solid rgb(211, 211, 211);
        }

        .border-item {
            border-bottom: 1px solid rgb(211, 211, 211);
        }

        .header {
            background: rgb(223, 223, 223);
        }
    </style>
</head>

<body>
    @php
    setlocale(LC_ALL, 'IND');
    @endphp
    <div class="container">
        <table class="table tableb-bordered" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td colspan="5" align="center">
                    <span>
                        <strong>{{$outlet->name}} </strong> <br>
                        {{$outlet->address}} <br>
                        {{$outlet->phone}}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <strong>Tanggal</strong> : {{ strftime( "%A, %d %B %Y %H:%M",
                    strtotime($purchase->created_at)) }} <br>
                    <strong>Kasir</strong> : {{ $purchase->user->name }} <br>
                </td>
            </tr>
            <tr class="border header">
                <th class="text-left">Barang</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Disc</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
            @foreach ($purchase->purchase_detail as $index => $detail)
            <tr class="border-item">
                <td>{{$detail->product->product_name}}</td>
                <td class="text-right">{{ number_format($detail->price) }}</td>
                <td class="text-right">{{ $detail->discount }}%</td>
                <td class="text-right">{{ $detail->amount }}</td>
                <td class="text-right">{{ number_format($detail->total) }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="4" class="text-right">Subtotal</th>
                <th class="text-right">{{number_format($purchase->subtotal)}}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Discount</th>
                <th class="text-right">{{ $purchase->discount }} %</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Potongan</th>
                <th class="text-right">{{number_format($purchase->rebate)}}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Grand Total</th>
                <th class="text-right">{{number_format($purchase->total)}}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Cash</th>
                <th class="text-right">{{number_format($purchase->cash)}}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Kembalian</th>
                <th class="text-right">{{number_format($purchase->cash - $purchase->total)}}</th>
            </tr>
        </table>
    </div>
</body>

</html>
