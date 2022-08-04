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
                                <label for="id_user">Kasir</label>
                                <select name="id_user" id="id_user" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}" {{Request::get('id_user')==$user->id ? 'selected' :
                                        ''}}>
                                        {{$user->name}}</option>
                                    @endforeach
                                </select>
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
                                <th>Diskon</th>
                                <th>Terjual</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $index => $report)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{ date('d-m-Y', strtotime($report->date)) }}</td>
                                <td>{{ $report->product_name }}</td>
                                <td class="text-right">{{ number_format($report->price) }}</td>
                                <td class="text-right">{{ $report->discount }} %</td>
                                <td>{{ $report->amount }}</td>
                                <td class="text-right subtotal">{{ number_format($report->total) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Pendapatan</td>
                                <td class="text-right total">{{ number_format($total) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Diskon</td>
                                <td class="text-right total">{{ ($diskon) }} %</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Potongan</td>
                                <td class="text-right total">{{ number_format($potongan) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Akhir</td>
                                <td class="text-right total">{{ number_format($akhir) }}</td>
                            </tr>
                        </tfoot>
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
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush
@endsection
