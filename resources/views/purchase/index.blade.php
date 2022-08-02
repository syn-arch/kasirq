@extends('layout.app')

@section('title', 'Data Pembelian')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Data Pembelian</h6>
            </div>
            <div class="card-body">
                @if ($message = Session::get('message'))
                <div class="alert alert-success mt-4">
                    <strong>Berhasil</strong>
                    <p>{{$message}}</p>
                </div>
                @endif

                @if (auth()->user()->role === 'admin_kasir')
                <div class="row">
                    <div class="col-md-6">
                        <form>
                            <div class="form-group">
                                <label for="start">Dari</label>
                                <input type="date" name="start" id="start" class="form-control"
                                    value="{{Request::get('start')}}">
                            </div>
                            <div class="form-group">
                                <label for="end">Sampai</label>
                                <input type="date" name="end" id="end" class="form-control"
                                    value="{{Request::get('end')}}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Cash</th>
                                <th>Diskon</th>
                                <th>Potongan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $index => $purchase)
                            @php
                            setlocale(LC_ALL, 'IND');
                            @endphp
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{
                                    strftime( "%A, %d %B %Y %H:%M", strtotime($purchase->created_at))
                                    }}</td>
                                <td class="text-right">{{ $purchase->user->name }}</td>
                                <td class="text-right">{{ number_format($purchase->total) }}</td>
                                <td class="text-right">{{ number_format($purchase->cash) }}</td>
                                <td class="text-right">{{ $purchase->discount }} %</td>
                                <td class="text-right">{{ number_format($purchase->rebate) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-success"><i
                                            class="fa fa-eye"></i> Detail</a>
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-dark"><i
                                            class="fa fa-edit"></i> Edit</a>
                                    <a href="/purchases/cetak/{{$purchase->id}}" class="btn btn-primary"><i
                                            class="fa fa-print"></i> Print</a>
                                    <a href="/purchases/print/{{$purchase->id}}" class="btn btn-warning"><i
                                            class="fa fa-print"></i> Cetak</a>
                                    <a href="#deleteModal" data-id={{$purchase->id}} data-toggle="modal" class="btn
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
                $('#dataTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });
</script>
@endpush

@push('js')
<script>
    const delete_button = document.querySelectorAll('.delete-button');

            delete_button.forEach(element => {
                element.addEventListener('click', function(){
                    const id_purchase = this.getAttribute('data-id')
                    const form_delete = document.querySelector('.form-delete');
                    form_delete.action = `/purchases/${id_purchase}`
                })
            });

</script>
@endpush
@endsection
