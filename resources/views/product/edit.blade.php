@extends('layout.app')

@section('title', 'Data Barang')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Barang</h6>
            </div>
            <div class="card-body">
                <a href="/products" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="product_name">Nama Barang</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                    name="product_name" id="product_name" placeholder="Nama Barang"
                                    value="{{ old('product_name', $product->product_name) }}">
                                @error('product_name')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror"
                                    name="price" id="price" placeholder="Harga"
                                    value="{{ old('price', $product->price) }}">
                                @error('price')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="discount">Diskon (%)</label>
                                <input type="text" class="form-control @error('discount') is-invalid @enderror"
                                    name="discount" id="discount" placeholder="Diskon"
                                    value="{{ old('discount', $product->discount) }}">
                                @error('discount')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
