<?php
//ipp
$id              = (!empty($header[0]->id)) ? $header[0]->id : '';
$no_ipp         = (!empty($header[0]->no_ipp)) ? $header[0]->no_ipp : '';
$id_customer      = (!empty($header[0]->id_customer)) ? $header[0]->id_customer : '';
$project           = (!empty($header[0]->project)) ? $header[0]->project : '';
$referensi       = (!empty($header[0]->referensi)) ? $header[0]->referensi : '';
$id_top           = (!empty($header[0]->id_top)) ? $header[0]->id_top : '';
$keterangan       = (!empty($header[0]->keterangan)) ? $header[0]->keterangan : '';
//delivery
$delivery_type       = (!empty($header[0]->delivery_type)) ? $header[0]->delivery_type : '';
$id_country           = (!empty($header[0]->id_country)) ? $header[0]->id_country : 'IDN';
$delivery_category  = (!empty($header[0]->delivery_category)) ? $header[0]->delivery_category : '';
$area_destinasi       = (!empty($header[0]->area_destinasi)) ? $header[0]->area_destinasi : '';
$delivery_address   = (!empty($header[0]->delivery_address)) ? $header[0]->delivery_address : '';
$shipping_method       = (!empty($header[0]->shipping_method)) ? $header[0]->shipping_method : '';
$packing               = (!empty($header[0]->packing)) ? $header[0]->packing : '';
$guarantee           = (!empty($header[0]->guarantee)) ? $header[0]->guarantee : '';
$delivery_date               = (!empty($header[0]->delivery_date)) ? $header[0]->delivery_date : '';
$instalasi_option    = (!empty($header[0]->instalasi_option)) ? $header[0]->instalasi_option : '';

$delivery_type1    = (!empty($header[0]->delivery_type) and $header[0]->delivery_type == 'local') ? 'selected' : '';
$delivery_type2 = (!empty($header[0]->delivery_type) and $header[0]->delivery_type == 'export') ? 'selected' : '';

$instalasi1    = (!empty($header[0]->instalasi_option) and $header[0]->instalasi_option == 'N') ? 'selected' : '';
$instalasi2 = (!empty($header[0]->instalasi_option) and $header[0]->instalasi_option == 'Y') ? 'selected' : '';
// print_r($header);
?>

<div class="box box-primary">
    <div class="box-body">
        <form id="data-form" method="post" enctype="multipart/form-data"><br>
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="customer">Customer Name <span class='text-red'>*</span></label>
                </div>
                <div class="col-md-4">
                    <select id="id_customer" name="id_customer" class="form-control input-md chosen-select" disabled>
                        <option value="0">Select An Customer</option>
                        <?php foreach ($customer as $val => $value) {
                            $sel = ($value['id_customer'] == $id_customer) ? 'selected' : '';
                        ?>
                            <option value="<?= $value['id_customer']; ?>" <?= $sel; ?>><?= strtoupper($value['nm_customer']) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="customer">Project Name <span class='text-red'>*</span></label>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                    <input type="hidden" name="no_ipp" id="no_ipp" value="<?= $no_ipp; ?>">
                    <input type="text" name="project" id="project" class='form-control input-md' required placeholder='Project Name' value="<?= $project; ?>" readonly>
                </div>
            </div>



            <div>
                <?php
                $val = 0;
                if (!empty($detail)) {
                    foreach ($detail as $val => $valx) {
                        $val++;

                        $platform           = (!empty($valx['platform']) and $valx['platform'] == 'Y') ? 'checked' : '';
                        $cover_drainage       = (!empty($valx['cover_drainage']) and $valx['cover_drainage'] == 'Y') ? 'checked' : '';
                        $facade               = (!empty($valx['facade']) and $valx['facade'] == 'Y') ? 'checked' : '';
                        $ceilling           = (!empty($valx['ceilling']) and $valx['ceilling'] == 'Y') ? 'checked' : '';
                        $partition           = (!empty($valx['partition']) and $valx['partition'] == 'Y') ? 'checked' : '';
                        $fence               = (!empty($valx['fence']) and $valx['fence'] == 'Y') ? 'checked' : '';
                        $app_indoor           = (!empty($valx['app_indoor']) and $valx['app_indoor'] == 'Y') ? 'checked' : '';
                        $app_outdoor           = (!empty($valx['app_outdoor']) and $valx['app_outdoor'] == 'Y') ? 'checked' : '';
                        $max_load           = (!empty($valx['max_load'])) ? $valx['max_load'] : '';
                        $min_load           = (!empty($valx['min_load'])) ? $valx['min_load'] : '';
                        $type_product       = (!empty($valx['type_product'])) ? $valx['type_product'] : '';

                        $file_pendukung_1   = (!empty($valx['file_pendukung_1'])) ? $valx['file_pendukung_1'] : '';
                        $file_pendukung_2   = (!empty($valx['file_pendukung_2'])) ? $valx['file_pendukung_2'] : '';
                        $color               = (!empty($valx['color'])) ? $valx['color'] : '';
                        $other_test           = (!empty($valx['other_test'])) ? $valx['other_test'] : '';

                        $food_grade           = (!empty($valx['food_grade']) and $valx['food_grade'] == 'Y') ? 'checked' : '';
                        $uv                   = (!empty($valx['uv']) and $valx['uv'] == 'Y') ? 'checked' : '';
                        $fire_reterdant_1   = (!empty($valx['fire_reterdant_1']) and $valx['fire_reterdant_1'] == 'Y') ? 'checked' : '';
                        $fire_reterdant_2   = (!empty($valx['fire_reterdant_2']) and $valx['fire_reterdant_2'] == 'Y') ? 'checked' : '';
                        $fire_reterdant_3   = (!empty($valx['fire_reterdant_3']) and $valx['fire_reterdant_3'] == 'Y') ? 'checked' : '';
                        $standard_astm       = (!empty($valx['standard_astm']) and $valx['standard_astm'] == 'Y') ? 'checked' : '';
                        $standard_bs           = (!empty($valx['standard_bs']) and $valx['standard_bs'] == 'Y') ? 'checked' : '';
                        $standard_dnv       = (!empty($valx['standard_dnv']) and $valx['standard_dnv'] == 'Y') ? 'checked' : '';

                        $surface_concave       = (!empty($valx['surface_concave']) and $valx['surface_concave'] == 'Y') ? 'checked' : '';
                        $surface_flat       = (!empty($valx['surface_flat']) and $valx['surface_flat'] == 'Y') ? 'checked' : '';
                        $surface_chequered_flat       = (!empty($valx['surface_chequered_flat']) and $valx['surface_chequered_flat'] == 'Y') ? 'checked' : '';
                        $surface_anti_skid       = (!empty($valx['surface_anti_skid']) and $valx['surface_anti_skid'] == 'Y') ? 'checked' : '';
                        $surface_custom = $valx['surface_custom'];
                        $id_bom_topping           = (!empty($valx['id_bom_topping'])) ? $valx['id_bom_topping'] : '';
                        $file_dokumen           = (!empty($valx['drawing_customer'])) ? $valx['drawing_customer'] : '';

                        echo "<div id='header_" . $val . "'>";
                        echo "<h4 class='text-bold text-primary'>Permintaan " . $val . "</h4>";
                        echo "<div class='form-group row'>";
                        echo "<div class='col-md-2'>";
                        echo "<label>Aplikasi Kebutuhan</label>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                        echo "<div class='form-group'>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][platform]' value='Y' " . $platform . ">Platform</label></div>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][cover_drainage]' value='Y' " . $cover_drainage . ">Cover Drainage</label></div>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][facade]' value='Y' " . $facade . ">Facade</label></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                        echo "<div class='form-group'>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][ceilling]' value='Y' " . $ceilling . ">Ceilling</label></div>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][partition]' value='Y' " . $partition . ">Partition</label></div>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][fence]' value='Y' " . $fence . ">Fence</label></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                        echo "<div class='form-group'><label>Aplikasi Pemasangan</label>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][app_indoor]' value='Y' " . $app_indoor . ">Indoor</label></div>";
                        echo "<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][app_outdoor]' value='Y' " . $app_outdoor . ">Outdoor</label></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'><label>Max Load</label>";
                        echo "	<input type='text' readonly name='Detail[" . $val . "][max_load]' class='form-control input-md autoNumeric0' placeholder='Max Load' value='" . $max_load . "'>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                        echo "	<div class='form-group'><label>Min Load</label>";
                        echo "		<input type='text' readonly name='Detail[" . $val . "][min_load]' class='form-control input-md autoNumeric0' placeholder='Min Load' value='" . $min_load . "'>";
                        echo "	</div>";
                        echo "</div>";
                        echo "</div>";

                        echo "<hr>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Type Product</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-4'>";
                        echo "	<select disabled name='Detail[" . $val . "][type_product]' id='type_product_" . $val . "' class='form-control chosen-select'>";
                        echo "		<option value=''>All Type Product</option>";
                        foreach ($product_lv1 as $valz => $valxz) {
                            $selected = ($type_product == $valxz['code_lv1']) ? 'selected' : '';
                            echo "<option value='" . $valxz['code_lv1'] . "' " . $selected . ">" . strtoupper($valxz['nama']) . "</option>";
                        }
                        echo     "</select>";
                        echo "	</div>";
                        echo "</div>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Product Name</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-8'>";
                        echo " <input readonly type='text' value='" . $detail[0]['product_name'] . "' class='form-control form-control-sm'>";
                        echo "	</div>";


                        echo "</div>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Additional Spesification</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'><label>Additional</label>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][food_grade]' value='Y' " . $food_grade . ">Food Grade</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][uv]' value='Y' " . $uv . ">UV</label></div>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'><label>Fire Retardant</label>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][fire_reterdant_1]' value='Y' " . $fire_reterdant_1 . ">Level 1</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][fire_reterdant_2]' value='Y' " . $fire_reterdant_2 . ">Level 2</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][fire_reterdant_3]' value='Y' " . $fire_reterdant_3 . ">Level 3</label></div>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'><label>Standard Spec</label>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][standard_astm]' value='Y' " . $standard_astm . ">ASTM</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][standard_bs]' value='Y' " . $standard_bs . ">BS</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][standard_dnv]' value='Y' " . $standard_dnv . ">GNV-GL</label></div>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "	<div class='col-md-4'>";
                        echo "		<div class='form-group'><label>Dokumen Pendukung</label>";
                        echo "		<input type='text' readonly class='form-control' name='Detail[" . $val . "][file_pendukung_1]' placeholder='Dokumen Pendukung 1' style='margin-bottom:5px;' value='" . $file_pendukung_1 . "'>";
                        echo "		<input type='text' readonly class='form-control' name='Detail[" . $val . "][file_pendukung_2]' placeholder='Dokumen Pendukung 2' style='margin-bottom:5px;' value='" . $file_pendukung_2 . "'>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "</div>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label></label>";
                        echo "	</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'><label>Color</label>";
                        echo "		<input type='text' readonly class='form-control' name='Detail[" . $val . "][color]' placeholder='Color' value='" . $color . "'>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "	<div class='col-md-4'>";
                        echo "		<div class='form-group'><label>Other Testing Requirement</label>";
                        echo "		<textarea class='form-control' name='Detail[" . $val . "][other_test]' rows='2' placeholder='Other Testing Requirement' readonly>" . $other_test . "</textarea>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "</div>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Surface</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-2'>";
                        echo "		<div class='form-group'>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][surface_concave]' value='Y' " . $surface_concave . ">Concave</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][surface_flat]' value='Y' " . $surface_flat . ">Flat</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][surface_chequered_flat]' value='Y' " . $surface_chequered_flat . ">Chequered Plate</label></div>";
                        echo "		<div class='checkbox'><label><input type='checkbox' disabled name='Detail[" . $val . "][surface_anti_skid]' value='Y' " . $surface_anti_skid . ">Anti Skid</label></div>";
                        echo "		<textarea class='form-control' readonly>" . $surface_custom . "</textarea>";
                        echo "		</div>";
                        echo "	</div>";
                        echo "</div>";
                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Topping</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-4'>";
                        echo "	<input type='text' readonly name='Detail[" . $val . "][id_bom_topping]' class='form-control' value='" . $detail[0]['nm_bom_topping'] . "'>";

                        echo "	</div>";
                        echo "</div>";

                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Ukuran Jadi</label>";
                        echo "	</div>";
                        echo "	<div class='col-md-5'>";
                        echo "	<table class='table table-striped table-bordered table-hover table-condensed'>";
                        echo "		<tr class='bg-blue'>";
                        echo "			<th class='text-center' width='30%'>Length</th>";
                        echo "			<th class='text-center' width='30%'>Width</th>";
                        echo "			<th class='text-center' width='30%'>Qty</th>";
                        echo "			<th class='text-center' width='10%'>#</th>";
                        echo "		</tr>";

                        $getdetailProduct4 = $this->db->get_where('ipp_detail_lainnya', array('category' => 'ukuran jadi', 'no_ipp' => $valx['no_ipp'], 'no_ipp_code' => $valx['no_ipp_code']))->result_array();
                        $new_number = 0;
                        foreach ($getdetailProduct4 as $key => $value) {
                            $new_number++;

                            echo "<tr id='headerjadi_" . $val . "_" . $new_number . "'>";
                            echo "<td align='left'>";
                            echo "<input type='text' readonly name='Detail[" . $val . "][ukuran_jadi][" . $new_number . "][length]' class='form-control input-md text-center autoNumeric4' value='" . $value['length'] . "'>";
                            echo "</td>";
                            echo "<td align='left'>";
                            echo "<input type='text' readonly name='Detail[" . $val . "][ukuran_jadi][" . $new_number . "][width]' class='form-control input-md text-center autoNumeric4' value='" . $value['width'] . "'>";
                            echo "</td>";
                            echo "<td align='left'>";
                            echo "<input type='text' readonly name='Detail[" . $val . "][ukuran_jadi][" . $new_number . "][order]' class='form-control input-md text-center autoNumeric0' value='" . $value['order'] . "'>";
                            echo "</td>";
                            echo "<td align='center'>";

                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "		<tr id='addjadi_" . $val . "_" . $new_number . "'>";

                        echo "			<td></td>";
                        echo "			<td></td>";
                        echo "		</tr>";
                        echo "	</table>";
                        echo "	</div>";
                        echo "</div>";



                        echo "<div class='form-group row'>";
                        echo "	<div class='col-md-2'>";
                        echo "		<label>Drawing Customer</label>";
                        echo "	</div>";
                        if (!empty($file_dokumen)) {
                            echo "<a href='" . base_url() . $file_dokumen . "' target='_blank' class='help-block' title='Download'>Download File</a>";
                        }
                        echo "	</div>";
                        echo "</div>";

                        //penutup div delete
                        echo "</div>";
                    }
                }
                ?>
                <div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Approve / Reject</label>
                        </div>
                        <div class="col-md-4">
                            <select name="action" id="" class="form-control action" required>
                                <option value="">- Approve / Reject -</option>
                                <option value="1">Approve</option>
                                <option value="2">Reject</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- <div id='add_<?= $val ?>'><button type='button' class='btn btn-sm btn-primary addPart' title='Add Permintaan'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Permintaan</button></td> -->
                <div class="input_product_master_section" style="margin-top: 40px;" hidden>
                    <input type="hidden" name="id_type" value="<?= $detail[0]['type_product'] ?>">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="">Product Type <span class='text-danger'>*</span></label>
                        </div>
                        <div class="col-md-10">
                            <select name="code_lv1" id="code_lv1" class='chosen-select2 w-100 form-control' disabled>
                                <option value="0">Select Product Type</option>
                                <?php
                                foreach ($listLevel1 as $key => $value) {
                                    $selected = ($detail[0]['type_product'] == $value['code_lv1']) ? 'selected' : '';
                                    echo "<option value='" . $value['code_lv1'] . "' " . $selected . ">" . strtoupper($value['nama']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="">Product Category <span class='text-danger'>*</span></label>
                        </div>
                        <div class="col-md-10">
                            <select name="code_lv2" id="code_lv2" class='chosen-select2 form-control'>
                                <?php
                                if (!empty($id) and !empty($listLevel2)) {
                                    echo "<option value=''>Select Product Category</option>";
                                    foreach ($listLevel2 as $key => $value) {
                                        echo '<option value="' . $value->code_lv2 . '">' . strtoupper($value->nama) . '</option>';
                                    }
                                } else {
                                    echo "<option value=''>List Empty</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="">Product Jenis <span class='text-danger'>*</span></label>
                        </div>
                        <div class="col-md-10">
                            <select name="code_lv3" id="code_lv3" class='chosen-select2 form-control'>
                                <?php
                                if (!empty($id) and !empty($listLevel3)) {
                                    echo "<option value=''>Select Product Jenis</option>";
                                    foreach ($listLevel3 as $key => $value) {
                                        $selected = ($code_lv3 == $value['code_lv3']) ? 'selected' : '';
                                        echo "<option value='" . $value['code_lv3'] . "' " . $selected . ">" . strtoupper($value['nama']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>List Empty</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="">Product Master <span class='text-danger'>*</span></label>
                        </div>
                        <div class="col-md-10">
                            <!-- <input type="hidden" class="form-control" id="id" name="id" value='<?= $id; ?>'> -->
                            <input type="hidden" class="form-control" id="code_lv4" name="code_lv4" value='<?= $code_lv4; ?>'>
                            <input type="text" class="form-control" id="nama" required name="nama" placeholder="Product Type" value='<?= $nama; ?>'>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>Product Code</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="code" name="code" value='<?= $code; ?>' placeholder="Product Code">
                        </div>
                        <div class="col-md-2">
                            <label>Trade Name</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="trade_name" name="trade_name" value='<?= $trade_name; ?>' placeholder="Trade Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>Packing Unit / Conversion</label>
                        </div>
                        <div class="col-md-2">
                            <select id="id_unit_packing" name="id_unit_packing" class="form-control input-md chosen-select">
                                <option value="0">Select An Option</option>
                                <?php foreach ($satuan_packing as $value) {
                                    $sel = ($value->id == $id_unit_packing) ? 'selected' : '';
                                ?>
                                    <option value="<?= $value->id; ?>" <?= $sel; ?>><?= strtoupper(strtolower($value->code)) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="konversi" name="konversi" class="form-control input-md maskM" placeholder="Conversion" value='<?= $konversi; ?>'>
                        </div>
                        <div class="col-md-2">
                            <label>Unit Measurement</label>
                        </div>
                        <div class="col-md-4">
                            <select id="id_unit" name="id_unit" class="form-control input-md chosen-select2">
                                <option value="0">Select An Option</option>
                                <?php foreach ($satuan as $value) {
                                    $sel = ($value->id == $id_unit) ? 'selected' : '';
                                ?>
                                    <option value="<?= $value->id; ?>" <?= $sel; ?>><?= strtoupper($value->code) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>MOQ</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control maskM" id="max_stok" name="max_stok" value='<?= $max_stok; ?>' placeholder="MOQ">
                        </div>
                        <div class="col-md-2">
                            <label>Minimum Stok</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control maskM" id="min_stok" name="min_stok" value='<?= $min_stok; ?>' placeholder="Minimum Stok">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>Upload MSDS</label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="file" name='photo' id="photo">
                            </div>
                            <?php if (!empty($file_msds)) { ?>
                                <a href='<?= base_url() . $file_msds; ?>' target='_blank' class="help-block" title='Download'>Download File</a>
                            <?php } ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>Dimensi (L,W,H)</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control maskM getCub" id="length" name="length" value='<?= $length; ?>' placeholder="Length">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control maskM getCub" id="wide" name="wide" value='<?= $wide; ?>' placeholder="Wide">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control maskM getCub" id="high" name="high" value='<?= $high; ?>' placeholder="High">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>CBM</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="cub" name="cub" placeholder="CBM" readonly value='<?= $cub; ?>'>
                        </div>
                    </div>
                    <?php if (!empty($id)) { ?>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="">Status</label>
                            </div>
                            <div class="col-md-4">
                                <label>
                                    <input type="radio" class="radio-control" name="status" value="1" checked> Aktif
                                </label>
                                &nbsp &nbsp &nbsp
                                <label>
                                    <input type="radio" class="radio-control" name="status" value="0"> Non-Aktif
                                </label>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="keterangan_reject_section" style="margin-top: 40px;" hidden>
                    <div class="form-group row mt-15">
                        <div class="col-md-2">
                            <label for="">Reject Remarks</label>
                        </div>
                        <div class="col-md-4">
                            <textarea name="keterangan_reject" id="" cols="30" rows="10" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-success">Update</button>
                <a href="<?= base_url('new_product_request') ?>" class="btn btn-sm btn-danger">Back</a>
            </div>
            <!-- <div class="row"> -->
            <!-- </div> -->
    </div>


    </form>
</div>
</div>

<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<style media="screen">
    .datepicker {
        cursor: pointer;
        padding-left: 12px;
    }
</style>
<script type="text/javascript">
    //$('#input-kendaraan').hide();
    var base_url = '<?php echo base_url(); ?>';
    var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

    $(document).ready(function() {
        $('.chosen-select').select2({
            width: '100%'
        });
        $('.chosen-select2').select2({
            width: '100%',
            dropdownParent: $('.input_product_master_section')
        });
        $(".datepicker").datepicker();
        $(".autoNumeric4").autoNumeric('init', {
            mDec: '4',
            aPad: false
        });
        $(".autoNumeric0").autoNumeric('init', {
            mDec: '0',
            aPad: false
        });

        //add part
        $(document).on('click', '.addPart', function() {
            // loading_spinner();
            var get_id = $(this).parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id = parseInt(split_id[1]) + 1;
            var id_bef = split_id[1];

            $.ajax({
                url: base_url + active_controller + '/get_add/' + id,
                cache: false,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#add_" + id_bef).before(data.header);
                    $("#add_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });



        $(document).on('click', '.delPart', function() {
            var get_id = $(this).data('id');
            $("#header_" + get_id).remove();
        });

        //add product level 4
        $(document).on('click', '.addPartProduct4', function() {
            // loading_spinner();
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];

            var id = parseInt(split_id[2]) + 1;
            var id_bef = split_id[2];

            var type_product = $('#type_product_' + id_head).val()

            $.ajax({
                url: base_url + active_controller + '/get_add_product_lv4/' + id_head + '/' + id,
                cache: false,
                type: "POST",
                data: {
                    'type_product': type_product
                },
                dataType: "json",
                success: function(data) {
                    $("#addproduct4_" + id_head + "_" + id_bef).before(data.header);
                    $("#addproduct4_" + id_head + "_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.delPartProduct4', function() {
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];
            var id_child = split_id[2];
            $("#header_" + id_head + "_" + id_child).remove();
        });

        //add accessories
        $(document).on('click', '.addPartAcc', function() {
            // loading_spinner();
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];

            var id = parseInt(split_id[2]) + 1;
            var id_bef = split_id[2];

            $.ajax({
                url: base_url + active_controller + '/get_add_accessories/' + id_head + '/' + id,
                cache: false,
                type: "POST",
                data: {
                    'type_product': '5'
                },
                dataType: "json",
                success: function(data) {
                    $("#addacc_" + id_head + "_" + id_bef).before(data.header);
                    $("#addacc_" + id_head + "_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.delPartAcc', function() {
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];
            var id_child = split_id[2];
            $("#headeracc_" + id_head + "_" + id_child).remove();
        });

        //ukuran jadi
        $(document).on('click', '.addPartUkj', function() {
            // loading_spinner();
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];

            var id = parseInt(split_id[2]) + 1;
            var id_bef = split_id[2];

            $.ajax({
                url: base_url + active_controller + '/get_add_ukuran/' + id_head + '/' + id,
                cache: false,
                type: "POST",
                data: {
                    'NameSave': 'ukuran_jadi',
                    'LabelAdd': 'Ukuran Jadi',
                    'LabelClass': 'Ukj',
                    'idClass': 'jadi',
                },
                dataType: "json",
                success: function(data) {
                    $("#addjadi_" + id_head + "_" + id_bef).before(data.header);
                    $("#addjadi_" + id_head + "_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.delPartUkj', function() {
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];
            var id_child = split_id[2];
            $("#headerjadi_" + id_head + "_" + id_child).remove();
        });

        //ukuran jadi
        $(document).on('click', '.addPartSheet', function() {
            // loading_spinner();
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];

            var id = parseInt(split_id[2]) + 1;
            var id_bef = split_id[2];

            $.ajax({
                url: base_url + active_controller + '/get_add_ukuran/' + id_head + '/' + id,
                cache: false,
                type: "POST",
                data: {
                    'NameSave': 'flat_sheet',
                    'LabelAdd': 'Flat Sheet',
                    'LabelClass': 'Sheet',
                    'idClass': 'sheet',
                },
                dataType: "json",
                success: function(data) {
                    $("#addsheet_" + id_head + "_" + id_bef).before(data.header);
                    $("#addsheet_" + id_head + "_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.delPartSheet', function() {
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];
            var id_child = split_id[2];
            $("#headersheet_" + id_head + "_" + id_child).remove();
        });

        //ukuran jadi
        $(document).on('click', '.addPartEnd', function() {
            // loading_spinner();
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];

            var id = parseInt(split_id[2]) + 1;
            var id_bef = split_id[2];

            $.ajax({
                url: base_url + active_controller + '/get_add_ukuran/' + id_head + '/' + id,
                cache: false,
                type: "POST",
                data: {
                    'NameSave': 'end_plate',
                    'LabelAdd': 'End/Kick Plate',
                    'LabelClass': 'End',
                    'idClass': 'end',
                },
                dataType: "json",
                success: function(data) {
                    $("#addend_" + id_head + "_" + id_bef).before(data.header);
                    $("#addend_" + id_head + "_" + id_bef).remove();
                    $('.chosen-select').select2({
                        width: '100%'
                    });
                    $('.autoNumeric4').autoNumeric('init', {
                        mDec: '4',
                        aPad: false
                    });
                    $(".autoNumeric0").autoNumeric('init', {
                        mDec: '0',
                        aPad: false
                    });
                    swal.close();
                },
                error: function() {
                    swal({
                        title: "Error Message !",
                        text: 'Connection Time Out. Please try again..',
                        type: "warning",
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.delPartEnd', function() {
            var get_id = $(this).parent().parent().attr('id');
            // console.log(get_id);
            var split_id = get_id.split('_');
            var id_head = split_id[1];
            var id_child = split_id[2];
            $("#headerend_" + id_head + "_" + id_child).remove();
        });


        //add part
        $(document).on('click', '#back', function() {
            window.location.href = base_url + active_controller;
        });

        $('#save').click(function(e) {
            e.preventDefault();
            var id_customer = $('#id_customer').val();
            var project = $('#project').val();

            if (id_customer == '0') {
                swal({
                    title: "Error Message!",
                    text: 'Customer name empty, select first ...',
                    type: "warning"
                });
                return false;
            }
            if (project == '') {
                swal({
                    title: "Error Message!",
                    text: 'Project name empty, select first ...',
                    type: "warning"
                });
                return false;
            }

            swal({
                    title: "Are you sure?",
                    text: "You will not be able to process again this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Process it!",
                    cancelButtonText: "No, cancel process!",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        // var formData = $('#data-form').serialize();
                        var formData = new FormData($('#data-form')[0]);
                        var baseurl = base_url + active_controller + '/add'
                        $.ajax({
                            url: baseurl,
                            type: "POST",
                            data: formData,
                            cache: false,
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                if (data.status == 1) {
                                    swal({
                                        title: "Save Success!",
                                        text: data.pesan,
                                        type: "success",
                                        timer: 3000,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        allowOutsideClick: false
                                    });
                                    window.location.href = siteurl + active_controller;
                                } else {

                                    if (data.status == 2) {
                                        swal({
                                            title: "Save Failed!",
                                            text: data.pesan,
                                            type: "warning",
                                            timer: 3000,
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            allowOutsideClick: false
                                        });
                                        window.location.href = siteurl + active_controller;
                                    } else {
                                        swal({
                                            title: "Save Failed!",
                                            text: data.pesan,
                                            type: "warning",
                                            timer: 3000,
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            allowOutsideClick: false
                                        });
                                        window.location.href = siteurl + active_controller;
                                    }

                                }
                            },
                            error: function() {

                                swal({
                                    title: "Error Message !",
                                    text: 'An Error Occured During Process. Please try again..',
                                    type: "warning",
                                    timer: 7000,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            }
                        });
                    } else {
                        swal("Cancelled", "Data can be process again :)", "error");
                        return false;
                    }
                });
        });

        $(document).on('submit', '#data-form', function(e) {
            e.preventDefault();

            var id = $('#id').val();
            var no_ipp = $('#no_ipp').val();

            swal({
                    title: "Are you sure?",
                    text: "You will not be able to process again this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Process it!",
                    cancelButtonText: "No, cancel process!",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var formData = $('#data-form').serialize();
                        var baseurl = base_url + active_controller + '/accept_ipp';
                        $.ajax({
                            url: baseurl,
                            type: "POST",
                            data: formData,
                            cache: false,
                            dataType: 'json',
                            success: function(data) {
                                if (data.status == 1) {
                                    swal({
                                        title: "Update IPP Success!",
                                        text: data.pesan,
                                        type: "success",
                                        timer: 3000,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        allowOutsideClick: false
                                    });
                                    window.location.href = siteurl + active_controller;
                                } else {

                                    if (data.status == 2) {
                                        swal({
                                            title: "Update IPP Failed!",
                                            text: data.pesan,
                                            type: "warning",
                                            timer: 3000,
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            allowOutsideClick: false
                                        });

                                    } else {
                                        swal({
                                            title: "Update IPP Failed!",
                                            text: data.pesan,
                                            type: "warning",
                                            timer: 3000,
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            allowOutsideClick: false
                                        });

                                    }

                                }
                            },
                            error: function() {

                                swal({
                                    title: "Error Message !",
                                    text: 'An Error Occured During Process. Please try again..',
                                    type: "warning",
                                    timer: 7000,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            }
                        });
                    } else {
                        swal("Cancelled", "Data can be process again :)", "error");
                        return false;
                    }
                });
        });

        $(document).on('change', '.action', function() {
            var action_val = $('.action').val();

            if (action_val == '1') {
                $('.input_product_master_section').show();
                $('.keterangan_reject_section').hide();

                $('#code_lv1').attr('required', true);
                $('#code_lv2').attr('required', true);
                $('#code_lv3').attr('required', true);
                $('#nama').attr('required', true);
            } else {
                $('.input_product_master_section').hide();
                $('.keterangan_reject_section').show();

                $('#code_lv1').attr('required', false);
                $('#code_lv2').attr('required', false);
                $('#code_lv3').attr('required', false);
                $('#nama').attr('required', false);
            }
        });

        $(document).on('change', '#code_lv2', function() {
            var code_lv1 = $("#code_lv1").val();
            var code_lv2 = $("#code_lv2").val();

            $.ajax({
                url: siteurl + active_controller + '/get_list_level3',
                method: "POST",
                data: {
                    code_lv1: code_lv1,
                    code_lv2: code_lv2
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#code_lv3').html(data.option);
                }
            });
        });

        $(document).on('change', '#code_lv3', function() {
            var code_lv1 = $("#code_lv1").val();
            var code_lv2 = $("#code_lv2").val();
            var code_lv3 = $("#code_lv3").val();

            $.ajax({
                url: siteurl + active_controller + '/get_list_level4_name',
                method: "POST",
                data: {
                    code_lv1: code_lv1,
                    code_lv2: code_lv2,
                    code_lv3: code_lv3
                },
                dataType: 'json',
                success: function(data) {
                    $('#nama').val(data.nama);
                }
            });
        });

        function get_cub() {
            var l = $('#length').val().split(",").join("");
            var w = $('#wide').val().split(",").join("");
            var h = $('#high').val().split(",").join("");
            var cub = (l * w * h) / 1000000000;

            $('#cub').val(cub.toFixed(7));
        }

        $(document).on('keyup', '.getCub', function() {
            get_cub();
        });

    });
</script>