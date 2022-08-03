@extends('layout.app')

@section('title', 'Pembelian')

@section('content')
<form action="{{ route('purchases.update', $purchase->id) }}" method="POST" class="purchase-form">
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="id_product">Nama Barang</label>
                                        <select name="id_product" id="id_product"
                                            class="select2 form-control id_product">
                                            <option value="">Pilih Barang</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name . ' | ' .
                                                number_format($product->price) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="amount">Jumlah</label>
                                    <input type="number" class="form-control amount" name="amount" placeholder="Jumlah">
                                </div>
                                <div class="col-lg-2 d-flex align-items-center">
                                    <button class="btn btn-primary btn-block mt-3 add-to-table">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga</th>
                                                    <th>Diskon</th>
                                                    <th>Jumlah</th>
                                                    <th>Total Harga</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchase->purchase_detail as $index => $row)
                                                <tr data-id="{{$row->id_product}}">
                                                    <input type="hidden" name="id_product[]"
                                                        value="{{$row->id_product}}">
                                                    <input type="hidden" name="price[]" value="{{$row->price}}">
                                                    <input type="hidden" name="discount_detail[]"
                                                        value="{{$row->product->discount}}">
                                                    <input type="hidden" class="total_detail" name="total_detail[]"
                                                        value="{{$row->total}}">
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{$row->product->product_name}}</td>
                                                    <td class="text-right">{{number_format($row->price)}}</td>
                                                    <td class="text-right">{{ $row->discount }} %</td>
                                                    <td class="text-right">
                                                        <input type="number" class="form-control amount_item"
                                                            style="width:50%" name="amount[]" value="{{$row->amount}}"
                                                            autocomplete="off" />
                                                    </td>
                                                    <td class="text-right">{{number_format($row->total)}}
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-danger btn-sm remove-from-table">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-5 offset-md-7">
                                    <strong>Subtotal</strong>
                                    <input type="hidden" name="subtotal" class="total-input"
                                        value="{{$purchase->subtotal}}">
                                    <span class="float-right mr-1 total">{{ number_format($purchase->subtotal) }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 offset-md-7">
                                    <a href="#paymentModal" data-toggle="modal" class="btn
                                        btn-primary btn-block"><i class="fa fa-credit-card"></i> Bayar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bayar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <select name="discount" id="discount" class="form-control discount" {{$purchase->subtotal <
                                    $min_discount ? 'disabled' : '' }}>
                                    <option {{$purchase->discount == "0" ? 'selected' : ''}} value="0">-- Diskon --
                                    </option>
                                    <option {{$purchase->discount == "5" ? 'selected' : ''}} value="5">5 %</option>
                                    <option {{$purchase->discount == "10" ? 'selected' : ''}} value="10">10 %</option>
                                    <option {{$purchase->discount == "20" ? 'selected' : ''}} value="20">20 %</option>
                                    <option {{$purchase->discount == "30" ? 'selected' : ''}} value="30">30 %</option>
                                    <option {{$purchase->discount == "40" ? 'selected' : ''}} value="40">40 %</option>
                                    <option {{$purchase->discount == "50" ? 'selected' : ''}} value="50">50 %</option>
                                    <option {{$purchase->discount == "60" ? 'selected' : ''}} value="60">60 %</option>
                                    <option {{$purchase->discount == "70" ? 'selected' : ''}} value="70">70 %</option>
                                    <option {{$purchase->discount == "80" ? 'selected' : ''}} value="80">80 %</option>
                                    <option {{$purchase->discount == "90" ? 'selected' : ''}} value="90">90 %</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control rebate" name="rebate" placeholder="Potongan"
                                value="{{$purchase->rebate}}" {{$purchase->subtotal < $min_discount ? 'disabled' : ''
                                }}>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <strong>Grand Total</strong>
                            <input type="hidden" name="total" class="grand-total-input" value="{{$purchase->subtotal}}">
                            <span class="float-right mr-1 grand-total">{{number_format($purchase->subtotal)}}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <input type="text" name="cash" class="form-control cash" placeholder="Cash"
                                value="{{number_format($purchase->cash)}}">
                            <input type="text" name="kembalian" class="form-control mt-2 kembalian"
                                placeholder="Kembalian" readonly
                                value="{{number_format($purchase->cash - $purchase->total)}}">
                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
