<?php
$id_rate_borongan = (isset($id)) ? $id : '';

$edit = 0;

?>
<input type="hidden" name="id_rate_borongan" value="<?= $id_rate_borongan ?>">
<table class="table table-striped">
    <thead>
        <tr>
            <th class="text-center">Nama Produk</th>
            <th class="text-center">Rate Borongan / Produk</th>
        </tr>
    </thead>
    <tbody id="list_barang_rate">
        <?php
        // if (isset($header)) {
            $no_list = 1;

            echo '<tr class="row_barang_' . $no_list . '">';

            echo '
                    <td>
                        <select name="barang_input" id="barang_input" class="form-control form-control-sm" disabled>
                            ';

            foreach ($list_product as $item) {
                if ($item->code_lv4 == $header->id_product) {
                    $selected = 'selected';
                    echo '<option value="' . $item->code_lv4 . '" ' . $selected . '>' . $item->nama . '</option>';
                }
            }

            echo ';
                        </select>
                        <input type="hidden" name="nm_barang_input" value="' . $header->nm_product . '">
                    </td>
                    <td>
                        <input type="text" name="rate_produk" id="" class="form-control form-control-sm text-right auto_num" value="' . $header->rate_borongan . '" readonly>
                    </td>
                ';

            echo '</tr>';
        // }
        ?>
    </tbody>
   
</table>
<script>
    var no_list = 1;

    $(document).ready(function() {
        $('.auto_num').autoNumeric();
        $('.chosen_select').chosen({
            width: '100%'
        });
    });

    $(document).on('change', '#barang_input', function() {
        var barang_input = $(this).val();

        $.ajax({
            type: 'post',
            url: siteurl + active_controller + '/detail_barang_input',
            data: {
                'id_barang': barang_input
            },
            dataType: 'json',
            cache: false,
            success: function(result) {
                $('input[name="nm_barang_input"]').val(result.nm_product);
            },
            error: function(result) {
                swal({
                    type: 'error',
                    title: 'Error !',
                    text: 'Please try again later !'
                });
            }
        });
    });

    $(document).on('click', '.add_barang', function() {
        var barang_input = $('#barang_input').val();
        var nm_barang_input = $('input[name="nm_barang_input"]').val();
        var rate_produk = $('input[name="rate_produk"]').val();

        if (nm_barang_input == '' || (rate_produk > 0 || rate_produk == '')) {
            swal({
                type: 'warning',
                title: 'Warning !',
                text: 'Please make sure all input are filled already !'
            });

            return false;
        } else {
            var hasil = '<tr class="row_barang_' + no_list + '">';

            hasil += '<td class="text-center">';
            hasil += nm_barang_input;
            hasil += '<input type="hidden" name="detail_barang[' + no_list + '][id_barang]" value="' + barang_input + '">';
            hasil += '<input type="hidden" name="detail_barang[' + no_list + '][nm_barang]" value="' + nm_barang_input + '">';
            hasil += '</td>';

            hasil += '<td class="text-center">';
            hasil += 'Rp. ' + number_format(rate_produk) + '/' + 'pcs';
            hasil += '<input type="hidden" name="detail_barang[' + no_list + '][rate_borongan]" value="' + rate_produk + '">';
            hasil += '</td>';

            hasil += '<td class="text-center">';
            hasil += '<button type="button" class="btn btn-sm btn-danger del_barang" data-no="' + no_list + '"><i class="fa fa-trash"></i></button>';
            hasil += '</td>';

            hasil += '</tr>';

            $('#list_barang_rate').append(hasil);

            $('.form-control').val('');

            no_list++;

        }
    });

    $(document).on('click', '.del_barang', function() {
        var no = $(this).data('no');

        $('.row_barang_' + no).remove();
    });
</script>