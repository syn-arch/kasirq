$(function () {
    $(document).on("select2:open", () => {
        document.querySelector(".select2-search__field").focus();
    });

    $(".select2").select2({
        theme: "bootstrap4",
    });

    function formatRupiah(number) {
        var number_string = number
                .toString()
                .replace(/[^,\d]/g, "")
                .toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah;
    }

    function addToTable() {
        const id_product = $("#id_product").val();

        $.get(`/purchases/get_product/${id_product}`, function (res) {
            const amount = $(".amount").val() || 1;
            const total_price = parseInt(amount) * parseInt(res.data.price);

            const row = $(document).find(`tr[data-id="${res.data.id}"]`);

            const rowLength = $("table").find("tr").length;

            if (row.length === 0) {
                const row = `
                            <tr data-id="${res.data.id}">
                                <input type="hidden" name="id_product[]" value="${
                                    res.data.id
                                }">
                                <input type="hidden" name="price[]" value="${
                                    res.data.price
                                }">
                                <td>${rowLength}</td>
                                <td>${res.data.product_name}</td>
                                <td class="text-right">${formatRupiah(
                                    res.data.price
                                )}</td>
                                <td class="text-right">
                                    <input type="number" class="form-control amount_item" style="width:50%" name="amount[]" value="${amount}" autocomplete="off" />
                                </td>
                                <td class="text-right">${formatRupiah(
                                    total_price
                                )}</td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm remove-from-table">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                $(".table tbody").append(row);
                $(".amount").val("");

                sumTotal();
            } else {
                alert("Produk sudah ditambahkan!");
            }
        });
    }

    function sumTotal() {
        const Total = $(".table tbody tr")
            .toArray()
            .reduce((a, b) => {
                const total = parseInt(
                    $(b)
                        .find("td:eq(4)")
                        .text()
                        .replace(".", "")
                        .replace(".", "")
                        .replace(",", "")
                        .replace(",", "")
                );
                return a + total;
            }, 0);

        $(".total").text(formatRupiah(Total));
        $(".total-input").val(Total);

        $(".grand-total").text(formatRupiah(Total));
        $(".grand-total-input").val(Total);
    }

    $(document).on("click", ".remove-from-table", function (e) {
        e.preventDefault();
        $(this).closest("tr").remove();
        sumTotal();
    });

    $(".amount").on("keydown", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            addToTable();
        }
    });

    $(".add-to-table").click(function (e) {
        e.preventDefault();
        addToTable();
    });

    $(document).on("keyup change", ".amount_item", function () {
        const amount = $(this).val();
        const price = $(this)
            .closest("tr")
            .find("td:eq(2)")
            .text()
            .replace(".", "")
            .replace(".", "")
            .replace(".", "")
            .replace(",", "")
            .replace(",", "")
            .replace(",", "");
        const total_price = parseInt(amount) * parseInt(price);

        $(this).closest("tr").find("td:eq(4)").text(formatRupiah(total_price));

        sumTotal();
    });

    $(".discount").change(function () {
        const discount = $(this).val();
        const total = $(".total-input").val();

        const rebate = $(".rebate").val() || 0;
        const after_rebate = parseInt(total) - parseInt(rebate);

        const grandTotal =
            parseInt(after_rebate) -
            (parseInt(after_rebate) * parseInt(discount)) / 100;

        $(".grand-total").text(formatRupiah(grandTotal));
        $(".grand-total-input").val(grandTotal);
    });

    $(".rebate").on("keyup change", function () {
        const rebate = $(this).val() || 0;
        const total = $(".total-input").val();

        const discount = $(".discount").val() || 0;
        const after_diskon =
            parseInt(total) - (parseInt(total) * parseInt(discount)) / 100;

        const grandTotal = parseInt(after_diskon) - parseInt(rebate);

        $(".grand-total").text(formatRupiah(grandTotal));
        $(".grand-total-input").val(grandTotal);
    });

    $(".cash").on("keyup change", function () {
        $(this).val(formatRupiah($(this).val()));
        const cash = $(this)
            .val()
            .replace(".", "")
            .replace(".", "")
            .replace(".", "")
            .replace(".", "")
            .replace(",", "")
            .replace(",", "")
            .replace(",", "");
        const grandTotal = $(".grand-total-input").val();

        const kembalian = parseInt(cash) - parseInt(grandTotal);

        $(".kembalian").val(formatRupiah(kembalian));
    });

    // shortcut
    function shortcut(e) {
        if (e.keyCode == 112) {
            e.preventDefault();
            $("#paymentModal").modal("show");
            setTimeout(() => {
                $(".cash").focus();
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

    $(".cash").keydown(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $(".purchase-form").submit();
        }
    });
});
