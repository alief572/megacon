<?php

$no_so          = (!empty($header)) ? $header[0]->no_so : '';
$code_cust      = (!empty($header)) ? $header[0]->code_cust : '';
$delivery_date  = (!empty($header)) ? date('d-m-Y', strtotime($header[0]->delivery_date)) : '';
$shippingx      = (!empty($header)) ? $header[0]->shipping : '';
$no_so_manual   = (!empty($header)) ? $header[0]->no_so_manual : '';
$shipment       = (!empty($header)) ? $header[0]->shipment : '';
$no_po          = (!empty($header)) ? $header[0]->no_po : '';

// print_r($header);
?>

<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" class="dropzone" method="post" autocomplete="off" enctype="multipart/form-data">
			<input type="hidden" name="no_penawaran" value="<?= $data_penawaran->no_penawaran ?>">
			<table class="table w-100">
				<tr>
					<th>Customer Name</th>
					<th>:</th>
					<td><?= $data_penawaran->nm_customer ?></td>
					<th>Quote Number</th>
					<th>:</th>
					<td><?= $data_penawaran->no_penawaran ?></td>
				</tr>
				<tr>
					<th>Customer Address</th>
					<th>:</th>
					<td><?= $data_penawaran->alamat ?></td>
					<th>Quote Date</th>
					<th>:</th>
					<td><?= date('d F Y', strtotime($data_penawaran->tgl_penawaran)) ?></td>
				</tr>
				<tr>
					<th>Contact Person</th>
					<th>:</th>
					<td><?= $data_penawaran->nm_pic ?></td>
					<th>Invoice Address</th>
					<th>:</th>
					<td>
						<input type="text" name="invoice_address" id="" class="form-control form-control-sm" value="<?= $data_penawaran->alamat ?>" required>
					</td>
				</tr>
				<tr>
					<th>TOP</th>
					<th>:</th>
					<td>
						<select name="top" id="" class="form-control form-control-sm">
							<option value="">- Pilih TOP -</option>
							<?php
							foreach ($list_top as $top) {
								$selected = '';
								if ($data_penawaran->top == $top->id) {
									$selected = 'selected';
								}
								echo '<option value="' . $top->id . '" ' . $selected . '>' . $top->name . '</option>';
							}
							?>
						</select>
					</td>
					<th>Sales</th>
					<th>:</th>
					<td>
						<?= $data_penawaran->nm_lengkap ?>
					</td>
				</tr>
				<tr>
					<th>Notes</th>
					<th>:</th>
					<td style="vertical-align: top;">
						<textarea name="notes" id="" cols="30" rows="5" class="form-control form-control-sm"><?= $data_penawaran->notes ?></textarea>
					</td>
					<th>PO Date</th>
					<th>:</th>
					<td style="vertical-align: top;">
						<input type="date" name="po_date" id="" class="form-control form-control-sm" value="">
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<th>PO Number</th>
					<th>:</th>
					<td style="vertical-align: top;">
						<input type="text" name="po_no" id="" class="form-control form-control-sm" value="">
					</td>
				</tr>
				<tr>
					<th>Delivery Address</th>
					<th>:</th>
					<td>
						<input type="text" name="delivery_address" id="" class="form-control form-control-sm" value="<?= $data_penawaran->alamat ?>" required>
					</td>
					<th>Delivery Date</th>
					<th>:</th>
					<td>
						<input type="date" name="delivery_date" id="" class="form-control form-control-sm" required>
					</td>
				</tr>
				<tr>
					<th>Upload Document</th>
					<th>:</th>
					<td>
						<div id="fileInputs">
							<input type="file" name="upload_po[]" id="" class="form-control form-control-sm upload_po" value="" multiple required>
						</div>
						<!-- <button type="submit">Upload</button>
						<button type="button" id="addFileInput">Add More Files</button> -->
					</td>
					<th>
						<!-- Delivery -->
					</th>
					<th>
					<!-- : -->
					</th>
					<td style="display: none;">
						<select name="pengiriman" id="" class="form-control form-control-sm">
							<option value="Franco">Franco</option>
							<option value="Ex Work">Ex Work</option>
							<option value="CIF">CIF</option>
							<option value="CNF">CNF</option>
							<option value="FOB Jakarta">FOB Jakarta</option>
							<option value="FOB Destination">FOB Destination</option>
						</select>
					</td>
				</tr>
			</table>


			<br>
			<div class='box box-info'>
				<div class='box-header'>
					<h3 class='box-title'>Detail Product</h3>
					<div class='box-tool pull-right'>
						<!--<button type='button' data-id='frp_".$a."' class='btn btn-md btn-info panelSH'>SHOW</button>-->
					</div>
				</div>
				<div class='box-body hide_header'>
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead>
							<tr class='bg-blue'>
								<th class='text-center'>No.</th>
								<th class='text-center'>Code</th>
								<th class='text-center' style="width: 350px;">Product Name</th>
								<th class='text-center'>Variant</th>
								<th class='text-center'>Qty</th>
								<th class='text-center'>Price</th>
								<th class='text-center'>Total Price</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$subtotal = 0;
							$subtotal_other = 0;
							$discount = 0;
							$ppn = 0;
							$persen_ppn = 0;

							$x = 1;
							foreach ($data_penawaran_detail as $penawaran_detail) :
								$request_production = 0;
								if ($penawaran_detail->stok_tersedia < $penawaran_detail->qty) {
									$request_production = ($penawaran_detail->qty - $penawaran_detail->stok_tersedia);
								}
								echo '
									<tr>
										<td class="text-center" style="vertical-align: middle;">' . $x . '</td>
										<td class="text-center" style="vertical-align: middle;">' . $penawaran_detail->product_code . '</td>
										<td class="text-center" style="vertical-align: middle;">' . $penawaran_detail->nama_produk . '</td>
										<td class="text-center" style="vertical-align: middle;">' . $penawaran_detail->variant_product . '</td>
										<td class="text-center" style="vertical-align: middle;">' . number_format($penawaran_detail->qty, 2) . '</td>
										<td class="text-right" style="vertical-align: middle;">(' . $data_penawaran->currency . ') ' . number_format(($penawaran_detail->harga_satuan - ($penawaran_detail->harga_satuan * $penawaran_detail->diskon_persen / 100)), 2) . '</td>
                        				<td class="text-right" style="vertical-align: middle;">(' . $data_penawaran->currency . ') ' . number_format((($penawaran_detail->harga_satuan - ($penawaran_detail->harga_satuan * $penawaran_detail->diskon_persen / 100)) * $penawaran_detail->qty), 2) . '</td>
									</tr>
								';

								if ($penawaran_detail->tipe_data == "non other") {
									$subtotal += ((($penawaran_detail->harga_satuan) * $penawaran_detail->qty));
									$discount += (($penawaran_detail->harga_satuan * $penawaran_detail->qty) * $penawaran_detail->diskon_persen / 100);
									$ppn += ((($penawaran_detail->harga_satuan * $penawaran_detail->qty) - ((($penawaran_detail->harga_satuan * $penawaran_detail->qty) * $penawaran_detail->diskon_persen / 100))) * $data_penawaran->ppn / 100);
								} else {
									$subtotal_other += ((($penawaran_detail->harga_satuan) * $penawaran_detail->qty));
									$ppn += ((($penawaran_detail->harga_satuan * $penawaran_detail->qty) - ((($penawaran_detail->harga_satuan * $penawaran_detail->qty) * $penawaran_detail->diskon_persen / 100))) * $data_penawaran->ppn / 100);
								}

								$persen_ppn = $data_penawaran->ppn;

								$x++;
							endforeach;
							?>
						</tbody>
						<tbody>
							<tr>
								<td colspan="6" class="text-right">Subtotal</td>
								<td class="text-right">(<?= $data_penawaran->currency ?>) <?= number_format($subtotal, 2) ?></td>
							</tr>
							<tr>
								<td colspan="6" class="text-right">Discount</td>
								<td class="text-right">(<?= $data_penawaran->currency ?>) <?= number_format($discount, 2) ?></td>
							</tr>
							<tr>
								<td colspan="6" class="text-right">PPn (<?= $persen_ppn ?>%)</td>
								<td class="text-right">(<?= $data_penawaran->currency ?>) <?= number_format($ppn, 2) ?></td>
							</tr>
							<tr>
								<td colspan="6" class="text-right">Grand Total</td>
								<td class="text-right">(<?= $data_penawaran->currency ?>) <?= number_format($subtotal + $subtotal_other - ($discount) + $ppn, 2) ?></td>
							</tr>
						</tbody>
					</table>

					<!-- START BAGIAN DELIVERY COST -->
            <div class="box box-default ">
                <div class="box-header">
                    <h3>Biaya Pengiriman</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered" width="100%" id="tabel-detail-mutasi-delivery-cost">
                        <thead>
                            <tr class="bg-blue">
                                <th class="text-center">Product</th>
                                <th class="text-center">Berat</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Total Berat</th>
                                <!-- <th class="text-center">Discount (%)</th>
                                <th class="text-center">Price Unit After Discount</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Action</th> -->
                            </tr>
                        </thead>
                        <tbody id="list_item_mutasi_delivery_cost">
                            <?php
                            $total_all = 0;
                            $total_price_before_discount = 0;
                            $total_nilai_discount = 0;
                            $total_berat_all = 0;
                            $total_all_qty = 0;
                            if (isset($results['data_penawaran_detail_dc'])) {
                                foreach ($results['data_penawaran_detail_dc'] as $penawaran_detail) {

                                    //start get stok
                                    $id_category3 = $penawaran_detail->id_category3;
                                    $no_penawaran = $penawaran_detail->no_penawaran;
                                    $sql = "
                                            SELECT
                                                a.code_lv4,
                                                MAX(a.actual_stock) AS stock_akhir,
                                                b.berat_produk,
                                                c.qty,
                                                (IFNULL(b.berat_produk,0) * c.qty) total_berat
                                            FROM
                                                stock_product a
                                            LEFT JOIN
                                                new_inventory_4 b ON a.code_lv4 = b.code_lv4
                                            LEFT JOIN tr_penawaran_detail as c 
                                            ON c.id_category3 = a.code_lv4
                                            WHERE
                                                b.code_lv4 = ?
                                                AND c.no_penawaran = ?
                                                AND a.deleted_date IS NULL
                                            GROUP BY
                                                a.code_lv4
                                        ";

                                    // Eksekusi query dengan parameter binding
                                    @$query = $this->db->query(@$sql, array($id_category3, $no_penawaran));
                                    @$result_stok = $query->row(); // ambil satu baris hasil
                                    //end get stok

//START GET DATA INVENTORY
$sql_berat = "
SELECT
berat_produk
FROM
new_inventory_4
WHERE
code_lv4 = ?
";
$query_berat = $this->db->query($sql_berat, array($id_category3));
$result_berat = $query_berat->row();
// $berat_produk = $result_berat ? $result_berat->berat_produk : 0;
$berat_produk = ($result_berat && !empty($result_berat->berat_produk)) ? $result_berat->berat_produk : 0;

$sql_qty = "
    SELECT
        qty
    FROM
        tr_penawaran_detail
    WHERE
        id_category3 = ?
        AND no_penawaran = ?
";
$query_qty = $this->db->query($sql_qty, array($id_category3, $no_penawaran));
$result_qty = $query_qty->row();
// print_r($this->db->last_query());
// exit;
$qty = $result_qty ? $result_qty->qty : 0;
// print_r($qty);exit;
$total_berat = $berat_produk * $qty;
$total_berat_all += $total_berat;
$total_all_qty += $qty;
// print_r($total_all_qty);exit;
//END GET DATA INVENTORY

                                    echo '
                                            <tr>
                                            <td style="display:none">' . $no_penawaran . '</td>
                                            <td style="display:none">' . $id_category3 . '</td>
                                            <td>
                                            <span>' . htmlspecialchars($penawaran_detail->nama_produk) . '</span><br><br>
                                            </td>
                                            <td>' . $berat_produk . '</td>
                                            <td>' . $qty . '</td>
                                            <td>' . $total_berat . '</td>
                                            </tr>
                                        ';
                                }
                            } else {
                                $total_berat_all = 0;
                                foreach ($results['list_penawaran_detail'] as $penawaran_detail) {

                                //start get stok
                                $id_category3 = $penawaran_detail->id_category3;
                                $no_penawaran = $penawaran_detail->no_penawaran;
                                $sql = "
                                        SELECT
                                            a.code_lv4,
                                            MAX(a.actual_stock) AS stock_akhir,
                                            b.berat_produk,
                                            c.qty,
                                            (IFNULL(b.berat_produk,0) * c.qty) total_berat
                                        FROM
                                            stock_product a
                                        LEFT JOIN
                                            new_inventory_4 b ON a.code_lv4 = b.code_lv4
                                        LEFT JOIN tr_penawaran_detail as c 
                                        ON c.id_category3 = a.code_lv4
                                        WHERE
                                            b.code_lv4 = ?
                                            AND c.no_penawaran = ?
                                            AND a.deleted_date IS NULL
                                        GROUP BY
                                            a.code_lv4
                                    ";
                                // echo $this->db->last_query();die();
                                // Eksekusi query dengan parameter binding
                                @$query = $this->db->query(@$sql, array($id_category3, $no_penawaran));
                                @$result_stok = $query->row(); // ambil satu baris hasil
                                //end get stok

//START GET DATA INVENTORY
$sql_berat = "
SELECT
berat_produk
FROM
new_inventory_4
WHERE
code_lv4 = ?
";
$query_berat = $this->db->query($sql_berat, array($id_category3));
$result_berat = $query_berat->row();
// $berat_produk = $result_berat ? $result_berat->berat_produk : 0;
$berat_produk = ($result_berat && !empty($result_berat->berat_produk)) ? $result_berat->berat_produk : 0;

$sql_qty = "
    SELECT
        qty
    FROM
        tr_penawaran_detail
    WHERE
        id_category3 = ?
        AND no_penawaran = ?
";
$query_qty = $this->db->query($sql_qty, array($id_category3, $no_penawaran));
$result_qty = $query_qty->row();
$qty = $result_qty ? $result_qty->qty : 0;

$total_berat = $berat_produk * $qty;
$total_berat_all += $total_berat;
$total_all_qty += $qty;
//END GET DATA INVENTORY
                                echo '
                                    <tr>
                                        <td style="display:none">' . $no_penawaran . '</td>
                                        <td style="display:none">' . $id_category3 . '</td>
                                        <td>
                                        <span>' . htmlspecialchars($penawaran_detail->nama_produk) . '</span><br><br>
                                        </td>
                                        <td>' . $berat_produk . '</td>
                                        <td>' . $qty . '</td>
                                        <td>' . $total_berat . '</td>
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Total Berat</label>
                            <div class="col-sm-6">
                            <!-- number_format($total_berat_all, 2) -->
                                <input type="text" name="total_berat_all" class="form-control input-sm text-right" id="total_berat_all" value="<?= number_format($total_berat_all, 2) ?>" readonly>
                                <input type="hidden" name="total_berat_all_new" class="form-control input-sm text-right" id="total_berat_all_new" value="<?= $total_berat_all ?>" readonly>
                                <input type="hidden" name="total_all_qty" class="form-control input-sm text-right" id="total_all_qty" value="<?= $total_all_qty ?>" readonly>

                            </div>
                        </div>
                    </div>
                    <br>
<?php
$get_data_cust = $this->db->get_where('master_customers', ['id_customer' => $results['data_penawaran']->id_customer])->row();
$ViewCustName = $get_data_cust->name_customer;
?>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Customer</label>
                            <div class="col-sm-6">
                            <!-- number_format($total_berat_all, 2) -->
                                <input type="text" name="customer_dc" class="form-control input-sm" id="customer_dc" readonly value="<?= @$ViewCustName ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group " style="padding-top:15px;">&nbsp;</div>
                    </div>
                    <br>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Jenis Truck</label>
                            <div class="col-sm-6">
                                <select id="jenis_truck" name="jenis_truck" class="form-control select2 get_data_truck" readonly>
                                    <!-- <option value="">-- Choose Option --</option> -->
                                    <?php foreach ($results['jenis_truck'] as $jenis_trucks) { ?>
                                        <option value="<?= $jenis_trucks->id_truck_rate ?>" <?php if($jenis_trucks->id_truck_rate == $results['get_delivery_cost_header']->id_truck_rate){ 'selected'; } ?>><?= ucfirst($jenis_trucks->nm_asset) ?></option>
                                        <!-- <option value="<?= $jenis_trucks->id_truck_rate ?>" ><?= ucfirst($jenis_trucks->nm_asset) ?></option> -->
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Kapasitas</label>
                            <div class="col-sm-6">
                            <input type="text" name="kapasitas_truck_dc" class="form-control text-right" id="kapasitas_truck_dc" readonly value="<?= number_format(@$results['get_delivery_cost_header']->kapasitas) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Perbandingan berat produk Vs Kapasitas</label>
                            <div class="col-sm-6">
                                <input type="text" name="berat_aktual_truck_dc" class="form-control input-sm text-right" id="berat_aktual_truck_dc" readonly value="<?= number_format(@$results['get_delivery_cost_header']->berat_vs_kapasitas) ?>">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <!-- <div class="form-group " style="padding-top:15px;"> -->
                            <!-- <label class="col-sm-4 control-label">&nbsp;</label> -->
                            <!-- <div class="col-sm-6"> -->
                            <!-- number_format($total_berat_all, 2) -->
                                <!-- <input type="text" name="total_berat_all" class="form-control input-sm text-right grand_total" id="total_berat_all" value="<?= number_format($total_berat_all, 2) ?>" readonly> -->
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                    <br>
                    <!-- <div class="col-lg-7"></div> -->
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Jarak Pengiriman (PP) (Km)</label>
                            <div class="col-sm-6">
                            <input type="text" name="jarak_pengiriman_truck_dc" class="form-control text-right" id="jarak_pengiriman_truck_dc" value="<?= number_format(@$results['get_delivery_cost_header']->jarak_pengiriman) ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-8">
                        <div class="form-group " style="padding-top:15px;">&nbsp;</div>
                    </div> -->
                    <br>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <!-- <div class="form-group " style="padding-top:15px;"> -->
                            <!-- <label class="col-sm-4 control-label">&nbsp;</label> -->
                            <!-- <div class="col-sm-6"> -->
                            <!-- number_format($total_berat_all, 2) -->
                                <!-- <input type="text" name="total_berat_all" class="form-control input-sm text-right grand_total" id="total_berat_all" value="<?= number_format($total_berat_all, 2) ?>" readonly> -->
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                    <br>
                    <!-- <hr> -->
                    <div class="col-lg-12"></div>
                    <div class="col-lg-12">
                        <h3>Biaya Angkut</h3>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Rate Truck</label>
                            <div class="col-sm-6">
                            <input type="text" name="rate_truck_ba" class="form-control text-right" id="rate_truck_ba" readonly value="<?= number_format(@$results['get_delivery_cost_header']->rate_truck) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Total Pengiriman (Qty)</label>
                            <div class="col-sm-6">
                            <input type="text" name="total_pengiriman_ba" class="form-control text-right" id="total_pengiriman_ba" readonly value="<?= @$results['get_delivery_cost_header']->total_pengiriman ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Rate Biaya Angkut (Rp/Km)</label>
                            <div class="col-sm-6">
                                <input type="text" name="rate_biaya_angkut_ba" class="form-control input-sm text-right" id="rate_biaya_angkut_ba" readonly value="<?= @$results['get_delivery_cost_header']->rate_biaya_angkut ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Biaya Angkut (Rp)</label>
                            <div class="col-sm-6">
                                <input type="text" name="biaya_angkut_ba" class="form-control input-sm text-right" id="biaya_angkut_ba" readonly value="<?= number_format(@$results['get_delivery_cost_header']->biaya_angkut) ?>">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-12">
                        <h3>Biaya Tol (PP)</h3>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Estimasi Tol (PP) (Rp)</label>
                            <div class="col-sm-6">
                            <input type="text" name="estimasi_tol_bt" class="form-control text-right" id="estimasi_tol_bt" value="<?= number_format(@$results['get_delivery_cost_header']->estimasi_tol) ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-12">
                        <h3>Charger Biaya Lain-Lain (Supir, Kenek, Uang Makan, Maintenance)</h3>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Charger Biaya Lain-Lain</label>
                            <div class="col-sm-6">
                            <input type="hidden" name="charger_biaya_cbl" class="form-control text-right" id="charger_biaya_cbl"  value="<?= @$results['get_delivery_cost_header']->charger_biaya_lain_lain ?>" >
                            <input type="text" name="biaya_cbl" class="form-control text-right" id="biaya_cbl" value="<?= number_format
                            (@$results['get_delivery_cost_header']->total_charger_biaya_lain_lain) ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-12"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">&nbsp;</label>
                            <div class="col-sm-6">
                            <input type="text" name="biaya_cbl" class="form-control text-right" id="biaya_cbl" readonly value="<?= @$results['get_delivery_cost_header']->total_charger_biaya_lain_lain ?>" >
                            </div>
                        </div>
                    </div> -->
                    <div class="col-lg-12"></div>
                    <div class="col-lg-12">
                        <h3>Total Biaya Pengiriman</h3>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Total Biaya Delivery (Rp)</label>
                            <div class="col-sm-6">
                            <input type="text" name="total_biaya_delivery_tbp" class="form-control text-right" id="total_biaya_delivery_tbp" readonly value="<?= number_format(@$results['get_delivery_cost_header']->total_biaya_delivery) ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">Total Biaya Produk + Delivery Cost</label>
                            <div class="col-sm-6">
                            <input type="text" name="grand_total_tbp" class="form-control text-right" id="grand_total_tbp" readonly value="<?= number_format(@$results['get_delivery_cost_header']->biaya_pengiriman) ?>" >
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <div class="form-group" style="">
                            <label class="col-sm-4 control-label">PPN (%)</label>
                            <div class="col-sm-6">
                            <input type="text" name="ppn_check" id="ppn_check" class="form-control text-right" 
                            value="<?= (isset($results['data_penawaran']) && $results['data_penawaran']->ppn == '11') ? '11' : '0' ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <div class="form-group " style="padding-top:15px;">
                            <label class="col-sm-4 control-label">&nbsp;</label>
                            <div class="col-sm-6 text-center">
                                <div class="form-group">
                                    <!-- <span style="padding-right: 40px;"> -->
                                        <input type="text" name="ppn_final" id="ppn_final" class="form-control text-right" readonly value="<?= number_format(@$results['get_delivery_cost_header']->biaya_ppn) ?>" >
                                    <!-- </span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <div class="form-group" style="">
                            <label class="col-sm-4 control-label">Grand Total (Rp)</label>
                            <div class="col-sm-6">
                            <input type="text" name="grand_total_final" class="form-control text-right" id="grand_total_final" readonly value="<?= number_format(@$results['get_delivery_cost_header']->grand_total) ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"></div>
                    <div class="col-lg-11"></div>
                    <div class="col-lg-1" style="padding-top:15px;">
                        <!-- <button id="simpanpenerimaan" class="btn btn-primary" type="button" onclick="savemutasi()">
                            <i class="fa fa-save"></i><b> Save Quotation</b>
                        </button> -->

                        <!-- <a href="<?= base_url() ?>quotation" class="btn btn-danger">
                            <i class="fa fa-refresh"></i><b> Back</b>
                        </a> -->
                    </div>


                    </div>
                </div>
            </div>
            <!-- END BAGIAN DELIVERY COST -->


					<br>
					<button type="button" class="btn btn-danger" style='float:right; margin-left:5px;' name="back" id="back"><i class="fa fa-reply"></i> Back</button>
					<button type="submit" class="btn btn-primary" style='float:right;' name="save" id="save"><i class="fa fa-save"></i> Create SO</button>

				</div>
			</div>
		</form>
	</div>
</div>

<!-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.37.0/js/jquery.fileupload.min.js"></script> -->
<script src="<?= base_url('assets/js/jquery.maskMoney.js') ?>"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

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

		// $('#addFileInput').click(function() {
		// 	$('<input type="file" name="files[]">').appendTo('#fileupload');
		// });

		$('.maskM').maskMoney();
		$('.chosen-select').select2();
		$(".datepicker").datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			minDate: 0
		});

		//add part
		$(document).on('click', '.addPart', function() {
			// loading_spinner();
			var get_id = $(this).parent().parent().attr('id');
			// console.log(get_id);
			var split_id = get_id.split('_');
			var id = parseInt(split_id[1]) + 1;
			var id_bef = split_id[1];

			$.ajax({
				url: base_url + 'index.php/' + active_controller + '/get_add/' + id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#add_" + id_bef).before(data.header);
					$("#add_" + id_bef).remove();
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.maskM').maskMoney();
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
				}
			});
		});

		$(document).on('change', '.product', function() {
			// loading_spinner();
			var nomor = $(this).data('no');
			var product = $(this).val();
			var code_cust = $('#code_cust').val();

			if (code_cust == '0') {
				swal({
					title: "Error Message!",
					text: 'Customer name empty, select first ...',
					type: "warning"
				});
				return false;
			}

			$.ajax({
				url: base_url + active_controller + '/get_balance/' + product + '/' + code_cust,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#balance_" + nomor).val(data.balance);
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
				}
			});
		});

		//delete part
		$(document).on('click', '.delPart', function() {
			var get_id = $(this).parent().parent().attr('class');
			$("." + get_id).remove();
		});

		//add part
		$(document).on('click', '#back', function() {
			window.location.href = base_url + active_controller;
		});

		$('#data-form').submit(function(e) {
			e.preventDefault();
			var code_cust = $('#code_cust').val();
			var delivery_date = $('#delivery_date').val();
			var shipping = $('#shipping').val();
			var product = $('.product').val();
			var qty = $('.qty').val();

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
						var formData = new FormData($('#data-form')[0]);
						var baseurl = siteurl + active_controller + '/save_so';
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
										timer: 7000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
									window.location.href = base_url + active_controller;
								} else {

									if (data.status == 2) {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 7000,
											showCancelButton: false,
											showConfirmButton: false,
											allowOutsideClick: false
										});
									} else {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 7000,
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

	});

	$(document).on('click', '#addFileInput', function() {
		$('.fileInputs').append('<input type="file" name="upload_po[]" id="" class="form-control form-control-sm upload_po" value="" multiple required>');
	});

	// document.addEventListener("DOMContentLoaded", function() {
	// 	// Select the file input container and the button to add file inputs
	// 	var fileInputsContainer = document.getElementById("fileInputs");
	// 	var addFileInputButton = document.getElementById("addFileInput");

	// 	// Add event listener to the button to add file inputs
	// 	addFileInputButton.addEventListener("click", function() {
	// 		// Create a new file input element

	// 		var newFileInput = document.createElement("input");
	// 		newFileInput.type = "file";
	// 		newFileInput.name = "files[]";

	// 		// Append the new file input to the container
	// 		fileInputsContainer.appendChild(newFileInput);
	// 	});
	// });
</script>