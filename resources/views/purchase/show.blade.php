@extends('layout.app')

@section('title', 'Data Pembelian')

@section('content')

@php
    setlocale(LC_ALL, 'IND');
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Data Pembelian</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="/purchases" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <div class="d-block mt-4">
                            <strong>Tanggal : </strong>
                            <span>{{ strftime( "%A, %d %B %Y %H:%M", strtotime($purchase->created_at))}}</span>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table tableb-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Jumlah</th>
                                        <th class="text-right">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchase_detail as $index => $detail)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$detail->product->product_name}}</td>
                                        <td class="text-right">{{ number_format($detail->price) }}</td>
                                        <td class="text-right">{{ $detail->amount }}</td>
                                        <td class="text-right">{{ number_format($detail->price * $detail->amount) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">Grand Total</th>
                                        <th class="text-right">Rp . {{number_format($purchase->total)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection