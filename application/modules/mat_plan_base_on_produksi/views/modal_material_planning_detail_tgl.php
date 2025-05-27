<?php
// print_r($dataDetail);
// die();
$id_data_details = (!empty($dataDetail->id))?$dataDetail->id:'';
$so_number_detail = (!empty($dataDetail->so_number))?$dataDetail->so_number:'';
$id_material = (!empty($dataDetail->id_material))?$dataDetail->id_material:'';
$tgl_rencana_kedatangan = (!empty($listDataTgl->tgl_rencana_kedatangan))?$listDataTgl->tgl_rencana_kedatangan:'';
$qty_kedatangan = (!empty($listDataTgl->qty_kedatangan))?$listDataTgl->qty_kedatangan:'';
$id_detail_tgl = (!empty($listDataTgl->id))?$listDataTgl->id:'';
// $tgl_rencana_kedatangan = (!empty($TglRencana))?$TglRencana:'';
// print_r($tgl_rencana_kedatangan);
// die();
?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" autocomplete="off">
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Tanggal Rencana Kedatangan <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-5">
				<input type="hidden" class="form-control" id="id_detail" name="id_detail" value='<?= $id_data_details ?>'>
				<input type="hidden" class="form-control" id="so_number" name="so_number" value='<?= $so_number ?>'>
				<input type="hidden" class="form-control" id="id" name="id" value="<?= @$id_detail_tgl ?>">
				<input type="hidden" class="form-control" id="id_material" name="id_material" value="<?= @$id_material ?>">
				<input type="hidden" class="form-control" id="tgl_rencana" name="tgl_rencana" value="<?= @$tgl_rencana_kedatangan ?>">
				<input type="date" class="form-control datepicker" id="tgl_rencana" required name="tgl_rencana" placeholder="Tanggal Rencana Kedatangan" value="<?=@$tgl_rencana_kedatangan;?>" >
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3">
				<label for="">Qty Kedatangan <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-5">
				<input type="text" class="form-control" id="qty_kedatangan" required name="qty_kedatangan" placeholder="Qty Kedatangan" value="<?=@$qty_kedatangan;?>" >
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-3"></div>
				<div class="col-md-9">
				<button type="submit" class="btn btn-primary" name="update_tgl" id="update_tgl"><i class="fa fa-save"></i> Save</button>
				</div>
			</div>
		</form>
	</div>
</div>
