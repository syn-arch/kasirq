@extends('layout.app')

@section('title', 'Laporan Pembelian')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Pembelian</h6>
            </div>
            <div class="card-body">
                @if (Request::has('start') && $end = Request::has('end'))
                <a target="_blank" href="/reports/print/{{Request::get('start')}}/{{Request::get('end')}}" class="btn btn-success float-right"><i class="fa fa-print"></i> Cetak Laporan</a>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <form>
                            <div class="form-group">
                                <label for="start">Dari</label>
                                <input type="date" name="start" id="start" class="form-control" value="{{Request::get('start')}}">
                            </div>
                             <div class="form-group">
                                <label for="end">Sampai</label>
                                <input type="date" name="end" id="end" class="form-control" value="{{Request::get('end')}}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if (Request::has('start') && Request::has('end'))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah Terbeli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $index => $report)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{ $report->date }}</td>
                                <td>{{ $report->product_name }}</td>
                                <td class="text-right">{{ number_format($report->price) }}</td>
                                <td>{{ $report->amount }}</td>
                                <td class="text-right">{{ number_format($report->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>
    @endpush
@endsection