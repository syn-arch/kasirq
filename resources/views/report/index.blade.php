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
                                <td class="text-right subtotal">{{ number_format($report->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td class="text-right total"></td>
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

        function formatRupiah(number){
            var number_string = number.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function get_total(){
            const Total = $('.table tbody tr').toArray().reduce((a, b) => {
                const total = parseInt($(b).find('td:eq(5)').text().replace(',', '').replace(',', '').replace(',', ''));
                return a + total;
            }, 0);

            $('.total').text(formatRupiah(Total));
        }

        get_total();
    });
</script>
@endpush
@endsection
