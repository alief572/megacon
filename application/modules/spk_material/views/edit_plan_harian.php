<?php
// $id = (!empty($listData[0]->id))?$listData[0]->id:'';
// $id_customer = (!empty($listData[0]->id_customer))?$listData[0]->id_customer:'';
// $kurs = (!empty($listData[0]->kurs))?$listData[0]->kurs:'';
// // $credit_limit = (!empty($listData[0]->credit_limit))?$listData[0]->credit_limit:'';
// $credit_limit = isset($listData[0]->credit_limit) && is_numeric($listData[0]->credit_limit) ? $listData[0]->credit_limit : 0;
// // $status1 = (!empty($listData[0]->status) AND $listData[0]->status == '1')?'checked':'';
// // $status2 = (!empty($listData[0]->status) AND $listData[0]->status == '0')?'checked':'';
// print_r($DataPLanHarian);
// die();
?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" autocomplete="off">
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Plan Date <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-9">
					<input type="hidden" class="form-control" id="id" name="id" value='<?= $DataPLanHarian->id_planning_harian_detail ?>'>
					<input type="text" class="form-control" id="plan_date" required name="plan_date" placeholder="Plan Date" value="<?= isset($DataPLanHarian->plan_date) ? $DataPLanHarian->plan_date : '' ?>" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Product <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-9">
				<input type="hidden" class="form-control" id="id_stock" name="id_stock" value='<?= $DataPLanHarian->id_stock_product ?>'>
				<input type="hidden" class="form-control" id="id_product" name="id_product" value='<?= $DataPLanHarian->id_product ?>'>
				<input type="text" class="form-control" id="name_product" name="name_product" value='<?= $DataPLanHarian->name_product ?>' readonly>
				<!-- <select name='id_customer' id='id_customer' class='form-control input-md' required>
					<option value='0'>Select Customers</option>
					<?php
					foreach($data_customer AS $val => $valx){
						$selected = ($valx['id_customer'] == $id_customer)?'selected':'';
						echo "<option value='".$valx['id_customer']."' ".$selected.">".strtoupper($valx['nm_customer'])."</option>";
					}
					?>
				</select> -->
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Propose Production </label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" id="propose" required name="propose" placeholder="Propose Production" value="<?= isset($DataPLanHarian->propose_production) ? $DataPLanHarian->propose_production : '' ?>" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">m3/pcs </label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" id="m3" required name="m3" placeholder="m3/pcs" value="<?= isset($DataPLanHarian->m3_pcs) ? $DataPLanHarian->m3_pcs : '' ?>" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Shift 1 </label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" id="shift1" required name="shift1" placeholder="Shift1" value="<?= isset($DataPLanHarian->shift1) ? $DataPLanHarian->shift1 : '' ?>" >
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Shift 2 </label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" id="shift2" required name="shift2" placeholder="Shift2" value="<?= isset($DataPLanHarian->shift2) ? $DataPLanHarian->shift2 : '' ?>" >
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Total Kubikasi </label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" id="total_kubikasi" required name="total_kubikasi" placeholder="Total kubikasi" value="<?= isset($DataPLanHarian->total_kubikasi) ? $DataPLanHarian->total_kubikasi : '' ?>" readonly>
				</div>
			</div>


			<div class="form-group row">
				<div class="col-md-3"></div>
				<div class="col-md-9">
				<button type="submit" class="btn btn-primary" name="update" id="update"><i class="fa fa-save"></i> Update</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).on('input', '#shift1, #shift2', function () {
    let shift1 = parseFloat($('#shift1').val()) || 0;
    let shift2 = parseFloat($('#shift2').val()) || 0;
    let m3 = parseFloat($('#m3').val()) || 0;

    let total = (shift1 + shift2) * m3;

    $('#total_kubikasi').val(total);
});
</script>
