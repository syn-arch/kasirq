@extends('layout.app')

@section('title', 'Data Barang')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
            </div>
            <div class="card-body">
                <a href="/products/create" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
                @if ($message = Session::get('message'))
                <div class="alert alert-success mt-4">
                    <strong>Berhasil</strong>
                    <p>{{$message}}</p>
                </div>
                @endif
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$product->product_name}}</td>
                                <td class="text-right">{{ number_format($product->price) }}</td>
                                <td class="text-right">{{ $product->discount }} %</td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning"><i
                                            class="fa fa-edit"></i> Edit</a>
                                    <a href="#deleteModal" data-id={{$product->id}} data-toggle="modal" class="btn
                                        btn-danger delete-button"><i class="fa fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Data yang telah dihapus tidak dapat dikembalikan.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form method="POST" class="d-inline form-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                </form>
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

@push('js')
<script>
    const delete_button = document.querySelectorAll('.delete-button');

            delete_button.forEach(element => {
                element.addEventListener('click', function(){
                    const id_product = this.getAttribute('data-id')
                    const form_delete = document.querySelector('.form-delete');
                    form_delete.action = `/products/${id_product}`
                })
            });

</script>
@endpush
@endsection
