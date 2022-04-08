<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
    <style>
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
    <div class="container">
        <table class="table tableb-bordered" border="0" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <td colspan="4"><strong>Periode</strong> : {{ $start . ' - ' . $end }}</td>
                </tr>
                <tr class="border header">
                    <th class="text-left">No</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Nama Barang</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Jumlah Terbeli</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $index => $report)
                <tr class="border-item">
                    <td>{{$index+1}}</td>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->product_name }}</td>
                    <td class="text-right">{{ number_format($report->price) }}</td>
                    <td class="text-right">{{ $report->amount }}</td>
                    <td class="text-right">{{ number_format($report->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
