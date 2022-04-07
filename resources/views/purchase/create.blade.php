@extends('layout.app')

@section('title', 'Data Pembelian')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Pembelian</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('purchases.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="id_product">Nama Barang</label>
                                        <select name="id_product" id="id_product" class="form-control select2">
                                            <option value="">Pilih Barang</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
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
                                                    <th>Jumlah</th>
                                                    <th>Total Harga</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 offset-md-9">
                                    <strong>Grand Total</strong>
                                    <input type="hidden" name="total" class="grand-total-input">
                                    <span class="float-right mr-1 grand-total"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-9">
                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(function(){

            $('.select2').select2();

            function formatRupiah(number){
                var number_string = number.toString().replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa  = split[0].length % 3,
                    rupiah  = split[0].substr(0, sisa),
                    ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            function addToTable(){
                const id_product = $('#id_product').val();

                $.get(`/products/get_product/${id_product}`, function(res){
                    const amount = $('.amount').val() || 1;
                    const total_price = parseInt(amount) * parseInt(res.data.price);

                    const row = $(document).find(`tr[data-id="${res.data.id}"]`);

                    const rowLength = $('table').find('tr').length

                    if (row.length === 0) {
                        const row = `
                            <tr data-id="${res.data.id}">
                                <input type="hidden" name="id_product[]" value="${res.data.id}">
                                <input type="hidden" name="amount[]" value="${amount}">
                                <input type="hidden" name="price[]" value="${res.data.price}">
                                <td>${rowLength}</td>
                                <td>${res.data.product_name}</td>
                                <td class="text-right">${formatRupiah(res.data.price)}</td>
                                <td class="text-right">${amount}</td>
                                <td class="text-right">${formatRupiah(total_price)}</td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm remove-from-table">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('.table tbody').append(row);
                        $('.amount').val('');

                        sumGrandTotal();
                    }

                })
            }

            function sumGrandTotal(){
                const grandTotal = $('.table tbody tr').toArray().reduce((a, b) => {
                    const total = parseInt($(b).find('td:eq(4)').text().replace('.', ''));
                    return a + total;
                }, 0);

                $('.grand-total').text(formatRupiah(grandTotal));
                $('.grand-total-input').val(grandTotal);
            }

            $(document).on('click', '.remove-from-table', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
                sumGrandTotal();
            })

            $('.amount').on('keydown', function(e){
                if (e.keyCode === 13) {
                    e.preventDefault();
                    addToTable();
                }
            });

            $('.add-to-table').click(function(e){
                e.preventDefault();
                addToTable();
            })

        })
    </script>
@endpush
