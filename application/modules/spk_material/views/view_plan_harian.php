<?php
// print_r($DataPlan_detail);
// die();
?>
<div class="box box-primary">
    <div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
		<div class="form-group row">
        	<div class="col-md-12">
				<table width='50%'>
					<tr>
						<td width='20%'>Periode Planning</td>
						<td width='1%'>:</td>
						<td>
							<div style="display: flex; gap: 10px;">
							<!-- <input type="month" id="bulanTahun" name="bulanTahun"> -->
							<select id="bulan" name="bulan" class="form-control" style="width: 20%;" disabled >
							    <option value="01" <?= ($DataPlan->periode_bulan == '1' || $DataPlan->periode_bulan == '01') ? 'selected' : '' ?>>Januari</option>
							    <option value="02" <?= ($DataPlan->periode_bulan == '2') ? 'selected' : '' ?>>Februari</option>
							    <option value="03" <?= ($DataPlan->periode_bulan == '3') ? 'selected' : '' ?>>Maret</option>
							    <option value="04" <?= ($DataPlan->periode_bulan == '4') ? 'selected' : '' ?>>April</option>
							    <option value="05" <?= ($DataPlan->periode_bulan == '5') ? 'selected' : '' ?>>Mei</option>
							    <option value="06" <?= ($DataPlan->periode_bulan == '6') ? 'selected' : '' ?>>Juni</option>
							    <option value="07" <?= ($DataPlan->periode_bulan == '7') ? 'selected' : '' ?>>Juli</option>
							    <option value="08" <?= ($DataPlan->periode_bulan == '8') ? 'selected' : '' ?>>Agustus</option>
							    <option value="09" <?= ($DataPlan->periode_bulan == '9') ? 'selected' : '' ?>>September</option>
							    <option value="10" <?= ($DataPlan->periode_bulan == '10') ? 'selected' : '' ?>>Oktober</option>
							    <option value="11" <?= ($DataPlan->periode_bulan == '11') ? 'selected' : '' ?>>November</option>
							    <option value="12" <?= ($DataPlan->periode_bulan == '12') ? 'selected' : '' ?>>Desember</option>
							</select>
							<select id="tahun" name="tahun" class="form-control" style="width: 20%;" disabled >
							<script>
							    const tahunSelect = document.getElementById('tahun');
							    const tahunSekarang = new Date().getFullYear();
							    const selectedYear = tahunSelect.getAttribute('data-selected-year');

							    for (let i = tahunSekarang; i >= tahunSekarang - 10; i--) {
							        const option = document.createElement('option');
							        option.value = i;
							        option.textContent = i;

							        if (selectedYear == i) {
							            option.selected = true;
							        }

							        tahunSelect.appendChild(option);
							    }
							</script>
							</select>
							</div>
						</td>
					</tr>
					<!-- <tr>
						<td>Product Name</td>
						<td>:</td>
						<td><?=strtoupper($NamaProduct);?></td>
					</tr>
					<tr>
						<td>Qty</td>
						<td>:</td>
						<td><?=number_format($qty).' dari total propose '.number_format($getData[0]['propose']);?></td>
					</tr>
					<tr>
						<td>Due Date</td>
						<td>:</td>
						<td><?=date('d-M-Y',strtotime($getData[0]['due_date']));?></td>
					</tr>
					<tr>
						<td>Detail BOM</td>
						<td>:</td>
						<td><span class='text-bold text-primary detail' data-id='<?=$getData[0]['no_bom'];?>' data-category='<?=$getDataProduct[0]['category'];?>' style='cursor:pointer;'>Tampilkan BOM</span></td>
					</tr>
					<tr>
						<td class='text-bold'>Tot. Cycletime/Hour</td>
						<td class='text-bold'>:</td>
						<td class='text-bold' id='total_cycletime'></td>
					</tr> -->
				</table>
				<input type="hidden" id='cycletime' name='cycletime' value=''>
				<input type="hidden" id='propose' name='propose' value=''>
				<input type="hidden" id='id' name='id' value=''>
				<input type="hidden" id='so_number' name='so_number' value=''>
				<input type="hidden" id='due_date' name='due_date' value=''>
				<input type="hidden" id='max_date' name='max_date' value=''>
				<input type="hidden" id='rowIndex' name='rowIndex' value=''>
				<input type="hidden" id='id_planning_harian' name='id_planning_harian' value="<?= @$IdPlanningHarian ?>">
				<input type="hidden" id='kode_planning' name='kode_planning' value="<?= @$KodePlan ?>">
				<input type="hidden" id="jumlah_data_detail" name="jumlah_data_detail" value="<?= @$JmlhDataDetail ?>">
			</div>
        </div>
		<h4>Schedule Detil</h4>
		<div class="form-group row">
        	<div class="col-md-12">
				<table class="table table-bordered table-striped" width='100%' border="1"  id="myTable">
					<thead>
					<tr>
						<td colspan="4"></td>
						<td colspan="3" style="text-align: center; background-color: orange;">
							Schedule &nbsp;
							<!-- <button type="button" class="btn btn-primary" name="btn_test" id="btn_test"  onclick="test_klik()">Test Btn</button> -->
						</td>
						<!-- <td>2</td>
						<td>3</td> -->
						<!-- <td></td> -->
					</tr>
					<tr style="background-color: #1E90FF;">
						<th class='text-center' width='10%'>Plan Date</th>
						<th class='text-center' width='15%'>Product</th>
						<th class='text-center' width='15%'>Propose Production</th>
						<th class='text-center' width='15%'>m3/pcs</th>
						<th class='text-center' width='15%'>Shift 1</th>
						<th class='text-center' width='15%'>Shift 2</th>
						<th class='text-center' width='15%'>Total Kubikasi</th>
						<th class='text-center' width='10%' hidden>Option</th>
					</tr>
				</thead>
				<tbody id="list_schedule_detil">
<?php
if (isset($DataPlan_detail)) {
	$index = 0;
	foreach ($DataPlan_detail as $plan_detail) {
?>
					<tr class="header_<?= $index ?>">
						<td class='text-center' width='10%'>
							<!-- <?= $plan_detail->plan_date ?> -->
							<input type="hidden" class="id" value="<?= $plan_detail->id_planning_harian_detail ?>">
							<input type="hidden" name="Detail[<?= $index ?>][tanggal]" class="form-control input-md text-center datepicker" placeholder="Plan Date" value="<?= $plan_detail->plan_date ?>" readonly>
							<input type="text" name="tanggal_view" class="form-control input-md text-center" placeholder="Plan Date" value="<?= $plan_detail->plan_date ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->name_product ?> -->
							<input type="hidden" name="Detail[<?= $index ?>][product]" class="form-control input-md text-center get_data_product product" placeholder="Plan Date" value="<?= $plan_detail->id_stock_product ?>" >
							<input type="text" name="name_product_view" class="form-control input-md text-center" placeholder="" value="<?= $plan_detail->name_product ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->propose_production ?> -->
							<input type="text" name="Detail[<?= $index ?>][propose]" class="form-control input-md text-center propose" placeholder="Plan Date" value="<?= $plan_detail->propose_production ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->m3_pcs ?> -->
							<input type="text" name="Detail[<?= $index ?>][m3]" class="form-control input-md text-center m3" placeholder="Plan Date" value="<?= $plan_detail->m3_pcs ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->shift1 ?> -->
							<input type="text" name="Detail[<?= $index ?>][shift1]" class="form-control input-md text-center shift1" placeholder="Plan Date" value="<?= $plan_detail->shift1 ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->shift2 ?> -->
							<input type="text" name="Detail[<?= $index ?>][shift2]" class="form-control input-md text-center shift2" placeholder="Plan Date" value="<?= $plan_detail->shift2 ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->total_kubikasi ?> -->
							<input type="text" name="Detail[<?= $index ?>][total_kubikasi]" class="form-control input-md text-center total_kubikasi" placeholder="Plan Date" value="<?= $plan_detail->total_kubikasi ?>" readonly>
						</td>
						<td class='text-center' width='10%' hidden>
							<!-- <button type='button' class='btn btn-sm btn-danger delPartPlan' title='Delete Part'><i class='fa fa-close'></i></button> -->
							<button type='button' class='btn btn-sm btn-success editPlan' title='Edit' data-id="<?= $plan_detail->id_planning_harian_detail ?>">Edit</button>
							<button type='button' class="btn btn-sm btn-danger del_product_price_'<?= $plan_detail->id_planning_harian_detail ?>'" onclick="del_planning_harian('<?= $plan_detail->id_planning_harian_detail ?>')" title='Delete'><i class='fa fa-close'></i></button>
							<!-- del_product_price_' . $penawaran_detail->id_penawaran_detail . '" onclick="del_product_price(' . $penawaran_detail->id_penawaran_detail . ')" -->
						</td>
					</tr>
<?php
		$index++;
	}
}
?>
					<tr id='add_0' hidden>
						<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartPlan' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>
						<td align='center'></td>
						<td align='center'></td>
						<td align='center'></td>
						<!-- <td align='center'></td> -->
						<td align='center' colspan="2" style="text-align: right;">Total Kubikasi Tgl :</td>
						<!-- <td align='center'></td> -->
						<!-- <td align='center'>9999</td> -->
						<td align='center'>
			              <input type='text' id='grand_total_kubikasi' class='form-control input-md text-center' placeholder='Grand Total Kubikasi' readonly>
			            </td>
					</tr>
				</tbody>
				</table>
			</div>
        </div>
		<div class="form-group row">
			<div class="col-md-6">
				<!-- <button type="button" class="btn btn-primary" name="save" id="save">Save</button> -->
				<button type="button" class="btn btn-danger back" style='margin-left:5px;' name="back" id="back">Back</button>
			</div>
        </div>

      	
		</form>
	</div>
</div>

<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).on('click', '#back', function(){
	    window.location.href = base_url + active_controller
	});
</script>