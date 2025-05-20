<?php
// print_r($header);
?>
<input type="hidden" name="no_so" class="no_so" value="<?= $results['sales_order']->no_so ?>">
<div class="box box-primary">
    <div class="box-body">
        <table class="table w-100">
            <tr>
                <th>Customer Name</th>
                <th>:</th>
                <td><?= $results['sales_order']->nm_customer ?></td>
                <th>Quote Number</th>
                <th>:</th>
                <td><?= $results['sales_order']->no_penawaran ?></td>
            </tr>
            <tr>
                <th>Customer Address</th>
                <th>:</th>
                <td><?= $results['sales_order']->alamat ?></td>
                <th>Quote Date</th>
                <th>:</th>
                <td><?= date('d F Y', strtotime($results['sales_order']->tgl_so)) ?></td>
            </tr>
            <tr>
                <th>Contact Person</th>
                <th>:</th>
                <td><?= $results['sales_order']->nm_pic ?></td>
                <th>Invoice Address</th>
                <th>:</th>
                <td><?= $results['sales_order']->invoice_address ?></td>
            </tr>
            <tr>
                <th>TOP</th>
                <th>:</th>
                <td><?= $results['top_name'] . ' ' . $results['sales_order']->top_custom ?></td>
                <th>Sales</th>
                <th>:</th>
                <td>
                    <?= $results['sales_order']->nm_lengkap ?>
                </td>
            </tr>
            <tr>
                <th>Delivery Address</th>
                <th>:</th>
                <td><?= $results['sales_order']->delivery_address ?></td>
                <th>Delivery Date</th>
                <th>:</th>
                <td><?= date('d F Y', strtotime($results['sales_order']->delivery_date)) ?></td>
            </tr>
            <tr>
                <th>Upload Dokumen</th>
                <th>:</th>
                <td>
                <?php
					$exp_uppo = explode('|', $results['sales_order']->upload_po);
					foreach ($exp_uppo as $uppo) {
						if (base_url($uppo) && $uppo !== '') {
							echo '<a href="' . base_url($uppo) . '" target="_blank">' . str_replace('uploads/po/', '', $uppo) . '</a> <br>';
						}
					}
					?>
                </td>
                <th colspan="3"></th>
            </tr>
            <tr>
                <th>Approve / Reject</th>
                <th>:</th>
                <td>
                    <select name="action_type" id="" class="form-control form-control-sm action_type" required>
                        <option value="">- Approve / Reject -</option>
                        <option value="1">Approve</option>
                        <option value="0">Reject</option>
                    </select>
                </td>
                <th>Keterangan Approve / Reject</th>
                <th>:</th>
                <td>
                    <input type="text" name="keterangan_approve_reject" id="" class="form-control form-control-sm keterangan_approve_reject" placeholder="Keterangan Approve / Reject" value="<?= ($results['sales_order']->keterangan_approve !== '') ? $results['sales_order']->keterangan_approve : $results['sales_order']->keterangan_loss ?>" required>
                </td>
            </tr>
        </table>
        <div class="form-group row">
            <div class="tableFixHead">
            <table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
					<thead>
						<tr class='bg-blue'>
							<th class='text-center'>No.</th>
							<th class='text-center'>Code</th>
							<th class='text-center' style="width: 250px;">Product Name</th>
							<th class='text-center'>Variant</th>
							<th class='text-center'>Qty</th>
							<th class='text-center'>Price</th>
							<th class='text-center'>Total Price</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$subtotal = 0;
						$discount = 0;
						$ppn = 0;

						$x = 1;
						foreach ($results['data_sales_order_detail'] as $sales_order_detail) :
							$request_production = 0;
							if (($sales_order_detail->actual_stock - $sales_order_detail->booking_stock) < $sales_order_detail->qty) {
								$request_production = ($sales_order_detail->qty - ($sales_order_detail->actual_stock - $sales_order_detail->booking_stock));
							}
							echo '
									<tr>
										<td class="text-center" style="vertical-align: middle;">' . $x . '</td>
										<td class="text-center" style="vertical-align: middle;">' . $sales_order_detail->product_code . '</td>
										<td class="text-center" style="vertical-align: middle;min-width: 250px; max-width: 250px;">' . $sales_order_detail->nama_produk . '</td>
										<td class="text-center" style="vertical-align: middle;">' . $sales_order_detail->variant_product . '</td>
										<td class="text-center" style="vertical-align: middle;">' . number_format($sales_order_detail->qty, 2) . '</td>
										
										<td class="text-left" style="vertical-align: middle;">(' . $results['data_penawaran']->currency . ') ' . number_format(($sales_order_detail->harga_satuan - ($sales_order_detail->harga_satuan * $sales_order_detail->diskon_persen / 100))) . '</td>
                        				<td class="text-left" style="vertical-align: middle;">(' . $results['data_penawaran']->currency . ') ' . number_format((($sales_order_detail->harga_satuan - ($sales_order_detail->harga_satuan * $sales_order_detail->diskon_persen / 100)) * $sales_order_detail->qty), 2) . '</td>
									</tr>
								';


							$subtotal += ((($sales_order_detail->harga_satuan) * $sales_order_detail->qty));
							$discount += (($sales_order_detail->harga_satuan * $sales_order_detail->qty) * $sales_order_detail->diskon_persen / 100);
							$ppn += ((($sales_order_detail->harga_satuan * $sales_order_detail->qty) -  (($sales_order_detail->harga_satuan * $sales_order_detail->qty) * $sales_order_detail->diskon_persen / 100)) * $results['data_penawaran']->ppn / 100);
							$x++;
						endforeach;
						?>
					</tbody>
					<tbody>
						<tr>
							<td colspan="6" class="text-right">Subtotal</td>
							<td>(<?= $results['data_penawaran']->currency ?>) <?= number_format($subtotal, 2) ?></td>
						</tr>
						<tr>
							<td colspan="6" class="text-right">Discount</td>
							<td>(<?= $results['data_penawaran']->currency ?>) <?= number_format($discount, 2) ?></td>
						</tr>
						<tr>
							<td colspan="6" class="text-right">PPn</td>
							<td>(<?= $results['data_penawaran']->currency ?>) <?= number_format($ppn, 2) ?></td>
						</tr>
						<tr>
							<td colspan="6" class="text-right">Grand Total</td>
							<td>(<?= $results['data_penawaran']->currency ?>) <?= number_format($subtotal - ($discount) + $ppn, 2) ?></td>
						</tr>
					</tbody>
				</table>
            </div>
        </div>
    </div>

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
                            <label class="col-sm-4 control-label">Jenis Truck </label>
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

</div>
<style media="screen">
    /* JUST COMMON TABLE STYLES... */
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .td {
        background: #fff;
        padding: 8px 16px;
    }

    .tableFixHead {
        overflow: auto;
        height: 300px;
        position: sticky;
        top: 0;
    }

    .thead .th {
        position: sticky;
        top: 0;
        z-index: 9999;
        background: #0073b7;
    }
</style>