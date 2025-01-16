<?php
$BERAT_MINUS = 0;
if (!empty($detail_additive)) {
    foreach ($detail_additive as $val => $valx) {
        $val++;
        $detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'additive'))->result();
        $PENGURANGAN_BERAT = 0;
        foreach ($detail_custom as $valx2) {
            $PENGURANGAN_BERAT += $valx2->weight * $valx2->persen / 100;
        }
        $BERAT_MINUS += $PENGURANGAN_BERAT;
    }
}

$TOTAL_PRICE_ALL = 0;

//default
foreach ($detail as $val => $valx) {
    $val++;
    $code_lv2        = (!empty($GET_LEVEL4[$valx['code_material']]['code_lv2'])) ? $GET_LEVEL4[$valx['code_material']]['code_lv2'] : '-';
    $price_ref      = (!empty($GET_PRICE_REF[$valx['code_material']]['price_ref'])) ? $GET_PRICE_REF[$valx['code_material']]['price_ref'] : 0;
    $nm_category = strtolower(get_name('new_inventory_2', 'nama', 'code_lv2', $code_lv2));
    $berat_pengurang_additive = ($nm_category == 'resin') ? $BERAT_MINUS : 0;

    $berat_bersih = $valx['volume_m3'] - $berat_pengurang_additive;
    $total_price = $berat_bersih * $price_ref;
    $TOTAL_PRICE_ALL += $total_price;
}

//additive
foreach ($detail_additive as $val => $valx) {
    $val++;
    $detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'additive'))->result();
    foreach ($detail_custom as $valx2) {
        $price_ref      = (!empty($GET_PRICE_REF[$valx2->code_material]['price_ref'])) ? $GET_PRICE_REF[$valx2->code_material]['price_ref'] : 0;
        $total_price    = $valx2->weight * $price_ref;
        $TOTAL_PRICE_ALL += $total_price;
    }
}

//topping
foreach ($detail_topping as $val => $valx) {
    $detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'topping'))->result();
    foreach ($detail_custom as $valx2) {
        $price_ref      = (!empty($GET_PRICE_REF[$valx2->code_material]['price_ref'])) ? $GET_PRICE_REF[$valx2->code_material]['price_ref'] : 0;
        $total_price    = $valx2->weight * $price_ref;
        $TOTAL_PRICE_ALL += $total_price;
    }
}

?>

<div class="box">
    <div class="box-body">
        <form id="data-form" method="post">
            <input type="hidden" id='id' name='id' value="<?= $product_price[0]['id']; ?>">
            <input type="hidden" id='no_bom' name='no_bom' value="<?= $no_bom; ?>">
            <input type="hidden" id='kode' name='kode' value="<?= $product_price[0]['kode']; ?>">
            <table id="example1" class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class='text-center' width="3%">#</th>
                        <!-- <th class='text-center' width="5%">Code</th> -->
                        <th class='text-center' width="25%">Element Costing</th>
                        <th class='text-center' width="17%">Rate</th>
                        <th class='text-right' width="12%">Price</th>
                        <th class='text-center'>Keterangan</th>
                        <th class='text-center' width="10%">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $harga_modal = 0;

                    foreach ($dataList as $key => $value) {
                        if ($value['judul'] == 'Material') {
                            echo "<tr>";
                            echo "<td class='text-center'>1</td>";
                            echo "<td>" . $value['element_costing'] . "</td>";
                            echo "<td></td>";
                            echo "<td class='text-right'>" . number_format($product_price[0]['price_material'], 2) . "</td>";
                            echo "<td>" . $value['keterangan'] . "</td>";
                            echo "<td class='text-center'><span class='text-primary btncursor' id='btnShowMaterial' data-bom='" . $no_bom . "' >Detail</span></td>";
                            echo "</tr>";

                            $harga_modal += $product_price[0]['price_material'];
                        }
                    }
                    //===============NEW=====================


                    //===============END NEW=================
                    foreach ($dataList as $key => $value) {
                        if ($value['judul'] == 'Manpower') {
                            echo "<tr>";
                            echo "<td class='text-center'>2</td>";
                            // echo "<td class='text-center text-bold text-primary'>".$value['code']."</td>";
                            echo "<td>" . $value['element_costing'] . "</td>";
                            echo "<td class='text-right'>";
                            echo number_format($product_price[0]['price_man_power'], 2);
                            echo "</td>";
                            echo "<td class='text-right'>";
                            echo number_format($product_price[0]['price_man_power'], 2);
                            echo "</td>";
                            echo "<td>" . $value['keterangan'] . "</td>";
                            echo "<td></td>";
                            echo "</tr>";

                            $harga_modal += $product_price[0]['price_man_power'];
                        }
                    }
                    echo "<tr>";
                    echo "<td class='text-center' rowspan='3'>3</td>";
                    // echo "<td class='text-center'></td>";
                    echo "<td class='text-left text-bold' colspan='4'>Depresiasi / Penyusutan</td>";
                    echo "</tr>";
                    foreach ($dataList as $key => $value) {
                        if ($value['judul'] == 'Depresiasi / Penyusutan') {
                            echo "<tr>";
                            if ($value['code'] == '3') {
                                $rate         = number_format($product_price[0]['rate_depresiasi'], 2);
                                $cost_machine    = ($product_price[0]['cost_machine'] * $ttl_volume);
                                $detRate = "<span class='text-primary btncursor detailRate' id='btnShowMachine' data-tanda='machine' data-cost='" . $product_price[0]['rate_depresiasi'] . "' data-id_product='" . $header[0]->id_product . "' >Detail</span>";
                            }
                            if ($value['code'] == '4') {
                                $rate         = number_format($product_price[0]['rate_mould'], 2);
                                $cost_machine     = ($product_price[0]['cost_mould']);
                                $detRate = "<span class='text-primary btncursor detailRate' id='btnShowMold' data-tanda='mold' data-cost='" . $product_price[0]['rate_mould'] . "' data-id_product='" . $header[0]->id_product . "' >Detail</span>";
                            }
                            echo "<td>" . $value['element_costing'] . "</td>";
                            echo "<td class='text-center'>" . $rate . "</td>";
                            echo "<td class='text-right'>" . number_format($cost_machine, 2) . "</td>";
                            echo "<td>" . $value['keterangan'] . "</td>";
                            echo "<td class='text-center'>" . $detRate . "</td>";
                            echo "</tr>";

                            $harga_modal += $cost_machine;
                        }
                    }

                    $nomor = 3;
                    foreach ($dataList as $key => $value) {
                        if ($value['judul'] == 'Lainnya') {
                            $nomor++;

                            if ($value['code'] == '5') {
                                $rate         = number_format($product_price[0]['cost_persen_consumable'], 2) . "";
                                $cost     = ($product_price[0]['cost_consumable'] * $ttl_volume);

                                $harga_modal += $cost;
                            }

                            if ($value['code'] == '6') {
                                $rate         = number_format($product_price[0]['cost_persen_enginnering'], 2);
                                $cost       = ($product_price[0]['cost_enginnering'] * $ttl_volume);

                                $harga_modal += $cost;
                            }

                            if ($value['code'] == '7') {
                                $rate = number_format($product_price[0]['cost_foh'], 2);
                                $cost = ($product_price[0]['cost_foh'] * $ttl_volume);

                                $harga_modal += $cost;
                            }

                            if ($value['code'] == '8') {
                                $rate         = number_format($product_price[0]['cost_persen_fin_adm'], 2);
                                $cost       = ($product_price[0]['cost_fin_adm'] * $ttl_volume);

                                $harga_modal += $cost;
                            }
                            if ($value['code'] == '9') {
                                $rate         = number_format($product_price[0]['cost_persen_mkt_sales'], 2);
                                $cost        = ($product_price[0]['cost_mkt_sales'] * $ttl_volume);

                                $harga_modal += $cost;
                            }
                            if ($value['code'] == '10') {
                                $rate         = number_format($product_price[0]['cost_persen_interest'], 2);
                                $cost       = ($product_price[0]['cost_interest'] * $ttl_volume);

                                $harga_modal += $cost;
                            }
                            if ($value['code'] == '11') {
                                $rate         = number_format($product_price[0]['ppn'], 2);
                                $cost        = ($product_price[0]['ppn'] * $harga_modal / 100);

                                $harga_modal += $cost;
                            }
                            if ($value['code'] == '12') {
                                $rate         = '';
                                $cost        = $harga_modal;
                            }
                            if ($value['code'] == '13') {
                                $rate         = number_format($product_price[0]['cost_persen_profit'], 2);
                                $cost       = ($harga_modal * $product_price[0]['cost_persen_profit'] / 100);
                            }
                            if ($value['code'] == '14') {
                                $rate         = '';
                                $cost        = ($harga_modal + ($harga_modal * $product_price[0]['cost_persen_profit'] / 100));
                            }
                            if ($value['code'] == '15') {
                                $rate         = number_format($product_price[0]['cost_factor_kompetitif'], 2);
                                $cost       = '';
                            }
                            if ($value['code'] == '16') {
                                $rate = '';
                                $cost = ((($harga_modal + ($harga_modal * $product_price[0]['cost_persen_profit'] / 100)) * $product_price[0]['cost_factor_kompetitif']));
                            }
                            echo "<tr>";
                            echo "<td class='text-center'>" . $nomor . "</td>";
                            echo "<td>" . nl2br($value['element_costing']) . "</td>";
                            echo "<td class='text-center'>" . $rate . "</td>";
                            if ($value['code'] == '15') {
                                echo "<td class='text-right'></td>";
                            } else {
                                echo "<td class='text-right'>" . number_format($cost, 2) . "</td>";
                            }
                            echo "<td>" . $value['keterangan'] . "</td>";
                            echo "<td></td>";
                            echo "</tr>";
                        }
                    }



                    $cost_pengajuan = ($product_price[0]['pengajuan_price_list'] > 0) ? $product_price[0]['pengajuan_price_list'] : $cost;
                    $kurs = ($product_price[0]['kurs'] > 0) ? $product_price[0]['kurs'] : '';
                    $price_idr = ($product_price[0]['price_idr'] > 0) ? $product_price[0]['price_idr'] : '';
                    ?>
                    <tr>
                        <td></td>
                        <td colspan='2' class='text-bold'>Pengajuan Price List Costing</td>
                        <td class='text-right text-bold'>IDR <?= number_format($cost_pengajuan, 2); ?></td>
                        <td colspan='2'></td>
                    </tr>
                    <!-- <tr>
                    <td></td>
                    <td colspan='2' class='text-bold'>Kurs</td>
                    <td class='text-right text-bold'>Rp. <?= number_format($kurs, 2); ?></td>
                    <td colspan='2'></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan='2' class='text-bold'>Price IDR</td>
                    <td class='text-right text-bold'>Rp. <?= number_format($price_idr, 2); ?></td>
                    <td colspan='2'></td>
                </tr> -->
                </tbody>
            </table>
            <button type="button" class="btn btn-danger" name="back" id="back">Back</button>
        </form>
    </div>
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width:80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Detail Data</h4>
            </div>
            <div class="modal-body" id="ModalView">
                ...
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.maskMoney.js') ?>"></script>
    <script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

    <style>
        .btncursor {
            cursor: pointer;
        }
    </style>
    <!-- page script -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.autoNumeric2').autoNumeric('init', {
                mDec: '2',
                aPad: false
            })
            $('#rate_1,#rate_2,#rate_4,#rate_5,#rate_8,#rate_15,#rate_16,#rate_19,#coa_14,#coa_15,#coa_16,#coa_17,#coa_18,#coa_19').prop('readonly', true);

            $(document).on('click', '#btnShowMaterial', function() {
                var no_bom = $(this).data('bom');
                // alert(id);
                $("#myModalLabel").html("<b>Detail Price</b>");
                $.ajax({
                    type: 'POST',
                    url: base_url + active_controller + 'detail_material',
                    data: {
                        'no_bom': no_bom
                    },
                    success: function(data) {
                        $("#dialog-popup").modal();
                        $("#ModalView").html(data);

                    }
                })
            });

            $(document).on('keyup', '#pengajuan_price_list, #kurs', function() {
                var pengajuan_price_list = getNum($('#pengajuan_price_list').val().split(",").join(""))
                var kurs = getNum($('#kurs').val().split(",").join(""))
                var price_idr = pengajuan_price_list * kurs;
                // console.log('masuk')
                $('#price_idr').val(number_format(price_idr, 2))
            });

            $(document).on('click', '.detailRate', function() {
                var id_product = $(this).data('id_product');
                var cost = $(this).data('cost');
                var tanda = $(this).data('tanda');
                var no_bom = $('#no_bom').val()
                // alert(id);
                $("#myModalLabel").html("<b>Detail Price</b>");
                $.ajax({
                    type: 'POST',
                    url: base_url + active_controller + 'detail_machine_mold',
                    data: {
                        'id_product': id_product,
                        'tanda': tanda,
                        'cost': cost,
                        'no_bom': no_bom
                    },
                    success: function(data) {
                        $("#dialog-popup").modal();
                        $("#ModalView").html(data);

                    }
                })
            });

            $(document).on('click', '#back', function() {
                window.location.href = base_url + active_controller;
            });
        })

        $(document).on('click', '#btnAjukan', function(e) {
            e.preventDefault()
            let id = $('#id').val()
            let pengajuan_price_list = $('#pengajuan_price_list').val()
            let kurs = $('#kurs').val()
            let price_idr = $('#price_idr').val()
            swal({
                    title: "Anda Yakin?",
                    text: "Mengajukan Price Costing !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-info",
                    confirmButtonText: "Ya, Update!",
                    cancelButtonText: "Batal",
                    closeOnConfirm: false
                },
                function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + active_controller + '/ajukan_product_price',
                        dataType: "json",
                        data: {
                            'id': id,
                            'pengajuan_price_list': pengajuan_price_list,
                            'kurs': kurs,
                            'price_idr': price_idr,
                        },
                        success: function(result) {
                            if (result.status == '1') {
                                swal({
                                        title: "Sukses",
                                        text: "Data berhasil diajuakan",
                                        type: "success"
                                    },
                                    function() {
                                        window.location.href = base_url + active_controller;
                                    })
                            } else {
                                swal({
                                    title: "Error",
                                    text: "Data error. Gagal diajuakan",
                                    type: "error"
                                })
                            }
                        },
                        error: function() {
                            swal({
                                title: "Error",
                                text: "Data error. Gagal request Ajax",
                                type: "error"
                            })
                        }
                    })
                });

        });

        function number_format(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>