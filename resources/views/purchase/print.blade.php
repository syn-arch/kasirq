<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase</title>
    <style>
        @page { margin: 5px; }
        body { margin: 5px; }
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
            <thead>
                <tr>
                    <td colspan="4"><strong>Tanggal</strong> : {{  strftime( "%A, %d %B %Y %H:%M", strtotime($purchase->created_at)) }}</td>
                </tr>
                <tr class="border header">
                    <th class="text-left">Barang</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->purchase_detail as $index => $detail)
                <tr class="border-item">
                    <td>{{$detail->product->product_name}}</td>
                    <td class="text-right">{{ number_format($detail->price) }}</td>
                    <td class="text-right">{{ $detail->amount }}</td>
                    <td class="text-right">{{ number_format($detail->price * $detail->amount) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Grand Total</th>
                    <th class="text-right">{{number_format($purchase->total)}}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
