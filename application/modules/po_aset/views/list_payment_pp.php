<?php
    $ENABLE_ADD     = has_permission('PembayaranPembelianAset.Add');
    $ENABLE_MANAGE  = has_permission('PembayaranPembelianAset.Manage');
    $ENABLE_VIEW    = has_permission('PembayaranPembelianAset.View');
    $ENABLE_DELETE  = has_permission('PembayaranPembelianAset.Delete');
?>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
	<div class="box-header">
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="mytabledata" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width="35">
			<?php if($ENABLE_MANAGE) : ?>
			Action
			<?php endif; ?>
			</th>
			<th>No Permintaan Pembayaran</th>
			<th>No PR</th>
			<th>Tanggal Permintaan Pembayaran</th>
			<th>Nilai Pengajuan</th>
			<th>Terbayar</th>
			<th>Sisa</th>
		</tr>
		</thead>
		<tbody>
		<?php if(empty($results)){
		}else{
			$numb=0; foreach($results AS $record){ $numb++; ?>
		<tr>
			<td>
			<?php if($ENABLE_MANAGE) : ?>
			<?php if($record->status==2){?>
				<a class="text-blue" href="javascript:void(0)" title="Payment" onclick="edit_data('<?=$record->id?>')"><i class="fa fa-money"></i> Bayar</a>
			<?php } ?>
			<?php endif; ?>
			</td>
			<td><?= $record->no_pp ?></td>
			<td><?= $record->no_pr ?></td>
			<td><?= $record->tgl_pp?></td>
			<td><?= number_format($record->request_payment)?></td>
			<td><?= number_format($record->terbayar)?></td>
			<td><?= number_format($record->request_payment-$record->terbayar)?></td>
		</tr>
		<?php }
		}  ?>
		</tbody>
		</table>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<h3>Export excel</h3>
		<form target="_blank" action="po_nonstock/export_payment" method="post">
			<input type="hidden" name="tipe" value="PP">
			<div class="form-group ">
				<label for="tgl1" class="col-sm-2 control-label">Tgl Permintan Pembayaran 1</label>
				<div class="col-sm-3">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" class="form-control tgl" id="tgl1" name="tgl1" value="<?php echo date("Y-m-d"); ?>" style="background:white" readonly>
					</div>
				</div>
				<label for="tgl2" class="col-sm-2 control-label">Tgl Permintan Pembayaran 2</label>
				<div class="col-sm-3">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" class="form-control tgl" id="tgl2" name="tgl2" value="<?php echo date("Y-m-d"); ?>" style="background:white" readonly>
					</div>
				</div>
				<div class="col-sm-2"><button type="submit">Export</button></div>
			</div>
		</form>
	</div>

</div>
<div id="form-data"></div>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">

  	$(function() {
    	$("#mytabledata").DataTable();
    	$("#form-data").hide();

		// Daterange Picker
		$(".tgl").datepicker({
			todayHighlight: true,
			format : "yyyy-mm-dd",
			showInputs: true,
			autoclose:true
		});
  	});

  	function edit_data(id){
		if(id!=""){
			var url = 'po_aset/payment_pp/'+id;
			$(".box").hide();
			$("#form-data").show();
			$("#form-data").load(siteurl+url);
		    $("#title").focus();
		}
	}
</script>