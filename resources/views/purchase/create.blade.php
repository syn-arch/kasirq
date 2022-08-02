@extends('layout.app')

@section('title', 'Pembelian')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" class="purchase-form">
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
                                        <select name="id_product" id="id_product" class="select2 form-control"
                                            autofocus>
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
                                <div class="col-md-5 offset-md-7">
                                    <strong>Subtotal</strong>
                                    <input type="hidden" name="subtotal" class="total-input">
                                    <span class="float-right mr-1 total"></span>
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
                            <select name="discount" id="discount" class="form-control discount">
                                <option value="0">-- Diskon --</option>
                                <option value="5">5 %</option>
                                <option value="10">10 %</option>
                                <option value="20">20 %</option>
                                <option value="30">30 %</option>
                                <option value="40">40 %</option>
                                <option value="50">50 %</option>
                                <option value="60">60 %</option>
                                <option value="70">70 %</option>
                                <option value="80">80 %</option>
                                <option value="90">90 %</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control rebate" name="rebate" placeholder="Rebate">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <strong>Grand Total</strong>
                            <input type="hidden" name="total" class="grand-total-input">
                            <span class="float-right mr-1 grand-total"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <input type="text" name="cash" class="form-control cash" placeholder="Cash">
                            <input type="text" name="kembalian" class="form-control mt-2 kembalian"
                                placeholder="Kembalian" readonly>
                        </div>
                    </div>
                    <div class="row">
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

                $.get(`/purchases/get_product/${id_product}`, function(res){
                    const amount = $('.amount').val() || 1;
                    const total_price = parseInt(amount) * parseInt(res.data.price);

                    const row = $(document).find(`tr[data-id="${res.data.id}"]`);

                    const rowLength = $('table').find('tr').length

                    if (row.length === 0) {
                        const row = `
                            <tr data-id="${res.data.id}">
                                <input type="hidden" name="id_product[]" value="${res.data.id}">
                                <input type="hidden" name="price[]" value="${res.data.price}">
                                <td>${rowLength}</td>
                                <td>${res.data.product_name}</td>
                                <td class="text-right">${formatRupiah(res.data.price)}</td>
                                <td class="text-right">
                                    <input type="number" class="form-control amount_item" style="width:50%" name="amount[]" value="${amount}" autocomplete="off" />
                                </td>
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

                        sumTotal();
                    }else{
                        alert('Produk sudah ditambahkan!');
                    }

                })
            }

            function sumTotal(){
                const Total = $('.table tbody tr').toArray().reduce((a, b) => {
                    const total = parseInt($(b).find('td:eq(4)').text().replace('.', ''));
                    return a + total;
                }, 0);

                $('.total').text(formatRupiah(Total));
                $('.total-input').val(Total);

                $('.grand-total').text(formatRupiah(Total));
                $('.grand-total-input').val(Total);
            }

            $(document).on('click', '.remove-from-table', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
                sumTotal();
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
            });

            $(document).on('keyup', '.amount_item', function(){
                const amount = $(this).val();
                const price = $(this).closest('tr').find('td:eq(2)').text().replace('.', '').replace('.', '').replace('.', '');
                const total_price = parseInt(amount) * parseInt(price);

                $(this).closest('tr').find('td:eq(4)').text(formatRupiah(total_price));

                sumTotal();
            });

            $('.discount').change(function(){
                const discount = $(this).val();
                const total = $('.total-input').val();

                const rebate = $('.rebate').val() || 0;
                const after_rebate = parseInt(total) - parseInt(rebate);

                const grandTotal = parseInt(after_rebate) - (parseInt(after_rebate) * parseInt(discount) / 100);

                $('.grand-total').text(formatRupiah(grandTotal));
                $('.grand-total-input').val(grandTotal);
            });

            $('.rebate').keyup(function(){
                const rebate = $(this).val() || 0;
                const total = $('.total-input').val();

                const discount = $('.discount').val() || 0;
                const after_diskon = parseInt(total) - (parseInt(total) * parseInt(discount) / 100);

                const grandTotal = parseInt(after_diskon) - parseInt(rebate);

                $('.grand-total').text(formatRupiah(grandTotal));
                $('.grand-total-input').val(grandTotal);
            });

            $('.cash').keyup(function(){
                $(this).val(formatRupiah($(this).val()));
                const cash = $(this).val().replace('.', '').replace('.', '').replace('.', '').replace('.', '')
                const grandTotal = $('.grand-total-input').val();

                const kembalian = parseInt(cash) - parseInt(grandTotal);

                $('.kembalian').val(formatRupiah(kembalian));
            });

            // shortcut
            function shortcut(e) {
                if (e.keyCode == 112) {
                    e.preventDefault();
                    $("#paymentModal").modal('show');
                    setTimeout(() => {
                        $('.cash').focus();
                    }, 500);
                }
            }

            // shortcut
            $(document).on("keyup keydown", "input", function (e) {
            shortcut(e);
            });

            $(document).on("keyup keydown", function (e) {
            shortcut(e);
            });

            $('.cash').keydown(function(e){
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $('.purchase-form').submit();
                }
            });

        });
</script>
@endpush
