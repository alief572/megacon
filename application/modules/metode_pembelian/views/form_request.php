<?php
$this->load->view('include/side_menu');
?>
<?=form_open('purchase/request_payment_save',array('id'=>'frm_data','name'=>'frm_data','role'=>'form','class'=>'form-horizontal'));?>
<input type="hidden" name="id_req" id="id_req" value="" />
<input type="hidden" name="id_top" id="id_top" value="<?php echo (isset($info_payterm->id) ? $info_payterm->id: ''); ?>" />
<div class="tab-content">
	<div class="tab-pane active">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"><?php echo $title;?></h3>
			</div>
			<div class="box-body">
				<div class="row">
				  <div class="col-md-6">
					<label class="control-label">Request Date</label>
					<input type="text" id="request_date" name="request_date" value="<?php echo date("Y-m-d"); ?>" class="form-control tanggal" required>
					<label class="control-label">No Request</label>
					<input type="text" id="no_request" name="no_request" value="" class="form-control" placeholder="Auto" readonly>
					<label class="control-label">PO Number</label>
					<input type="text" class="form-control" id="no_po" name="no_po" value="<?php echo $datapo->no_po; ?>" readonly tabindex="-1">
					<label class="control-label">Supplier</label>
					<p><?=$datapo->nm_supplier?></p><input type="hidden" id="id_supplier" name="id_supplier" value="<?php echo$datapo->id_supplier; ?>">
					<label class="control-label">Tipe Payment</label>
					<p><?=strtoupper($payterm->name) ?><input type="hidden" name="tipe" id="tipe" value="<?=$payterm->data2?>" /></p>
					<label class="control-label">Currency</label>
					<p><?=$datapo->mata_uang?><input type="hidden" name="curs_header" id="curs_header" value="<?=$datapo->mata_uang?>" /></p>
					<label class="control-label">PO</label>
					<input type="text" class="form-control divide" id="nilai_po" name="nilai_po" value="<?php echo $datapo->nilai_total; ?>" readonly tabindex="-1">
					<label class="control-label">PPN</label>
					<input type="text" class="form-control divide" id="nilai_ppn" name="nilai_ppn" value="<?php echo $datapo->nilai_ppn; ?>" readonly tabindex="-1">
					<label class="control-label">PO+PPN</label>
					<input type="text" class="form-control divide" id="nilai_total" name="nilai_total" value="<?php echo $datapo->nilai_plus_ppn; ?>" readonly tabindex="-1">
				</div>
				<div class="col-md-6">
					<input type="hidden" class="form-control divide" id="total_bayar" name="total_bayar" value="<?php echo $datapo->total_bayar; ?>" readonly tabindex="-1">
					<input type="hidden" class="form-control divide" id="po_belum_dibayar" name="po_belum_dibayar" value="<?php echo ($datapo->nilai_total-$datapo->total_bayar) ?>" readonly tabindex="-1">
					<input type="hidden" class="form-control divide" id="sisa_dp" name="sisa_dp" value="<?php echo $datapo->sisa_dp; ?>" readonly tabindex="-1">
					<label class="control-label">Bank Account</label>
					<input type="text" id="bank_transfer" name="bank_transfer" value="<?=$datapo->data_bank?>" class="form-control">
					<label class="control-label">Payment Date</label>
					<input type="text" id="req_payment_date" name="req_payment_date" value="<?php echo date("Y-m-d"); ?>" class="form-control tanggal" required>
					<label class="control-label">Nomor Invoice</label>
					<input type="text" class="form-control" id="no_invoice" name="no_invoice" value="<?=$info_payterm->invoice_no?>">
					<label class="control-label">Keterangan Invoice</label>
					<input type="text" class="form-control" id="keterangan" name="keterangan" value="">
					<label class="control-label">PO yang akan dibayar</label>
					<?php
					$nilai_po_invoice=0;
					if($datapo->mata_uang=='IDR'){
						$nilai_po_invoice=$info_payterm->value_idr;
					}else{
						$nilai_po_invoice=$info_payterm->value_usd;						
					}
					?>
					<input type="text" class="form-control divide" id="nilai_po_invoice" name="nilai_po_invoice" value="<?=$nilai_po_invoice?>" placeholder=0 required onchange="calculate_invoice()">
					<label class="control-label">Nilai Potongan DP</label>
					<input type="text" class="form-control divide" id="potongan_dp" name="potongan_dp" placeholder=0 value=0 onblur="calculate_invoice()">
					<label class="control-label">PPN</label>
					<div class="input-group">
					<div class="input-group-addon"><input type="checkbox" value="1" onclick="calculate_invoice()" name="ch_ppn" id="ch_ppn" <?php
					if($datapoh->tax > 0) echo "checked";
					?> style="pointer-events: none;" tabindex="-1"></div>
					<input type="text" class="form-control divide" readonly id="invoice_ppn" name="invoice_ppn" value="0" placeholder=0 required tabindex="-1">
					</div>
					<label class="control-label">PPH</label>
					<?php
					$coa_pph='';
					echo form_dropdown('coa_pph',$combo_coa_pph,$coa_pph,array('id'=>'coa_pph','class'=>'form-control'));
					?>
					<input type="text" class="form-control divide" onblur="calculate_invoice()" id="nilai_pph_invoice" name="nilai_pph_invoice" value="0" >
					<label class="control-label">PO+PPN-PPH</label>
					<input type="text" class="form-control divide" id="nilai_invoice" name="nilai_invoice" value="<?=$nilai_po_invoice?>" placeholder=0 required readonly tabindex="-1">
					<label class="control-label">Nilai Potongan Claim</label>
					<input type="text" class="form-control divide" id="potongan_claim" name="potongan_claim" placeholder=0 value=0 onblur="calculate_invoice()">
					<label class="control-label">Keterangan Potongan</label>
					<input type="text" class="form-control" id="keterangan_potongan" name="keterangan_potongan" value="">
					<label class="control-label">Request Payment</label>
					<input type="text" class="form-control divide" id="request_payment" name="request_payment" value="0" placeholder=0 required readonly tabindex="-1">
				</div>
			</div>

				<div class="table-responsive">
					<h4>Detail PO</h4>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Nama Barang</th>
								<th>Qty</th>
								<th>Price/Unit</th>
								<th>Total Price</th>
								<th>Terkirim</th>
								<th>Payment For</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($datapod)){
							foreach($datapod AS $record){ ?>
							<tr>
							<td><?=$record->nm_barang?></td>
							<td><?=number_format($record->qty_purchase,2)?></td>
							<td><?=number_format($record->net_price,2)?></td>
							<td><?=number_format($record->total_price,2)?></td>
							<td><?=number_format($record->qty_in)?></td>
							<td align=center><?=($record->status_pay==""?'<input type="checkbox" name="payfor[]" value="'.$record->id.'">':'')?></td>
							</tr>
							<?php
							}
						}
						?>
						</tbody>
					</table>
					<h4>TOP</h4>
					<table class="table table-bordered table-striped">
						<thead>
						<tr>
							<th class="text-center" width='5%'>Group TOP</th>
							<th class="text-center" width='8%'>Progress (%)</th>
							<th class="text-center" width='11%'>Value (USD)</th>
							<th class="text-center" width='11%'>Value (IDR)</th>
							<th class="text-center" width='25%'>Keterangan</th>
							<th class="text-center" width='10%'>Est Jatuh Tempo</th>
							<th class="text-center" width='25%'>Persyaratan</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($data_payterm)){
							foreach($data_payterm AS $valx){
								echo "<tr class='header'>";
									echo "<td align='left'>".$valx->group_top."</td>";
									echo "<td align='left'>".$valx->progress."</td>";
									echo "<td align='left'>".number_format($valx->value_usd,2)."</td>";
									echo "<td align='left'>".number_format($valx->value_idr,2)."</td>";
									echo "<td align='left'>".$valx->keterangan."</td>";
									echo "<td align='left'>".$valx->jatuh_tempo."</td>";
									echo "<td align='left'>".$valx->syarat."</td>";
								echo "</tr>";
							}
						}
						?>
						</tbody>
					</table>
				</div>

			</div>
			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="simpan-com" class="btn btn-success btn-sm stsview" id="simpan-com"><i class="fa fa-save">&nbsp;</i>Submit & Print</button>
						<a href="<?=base_url()?>pembelian/purchase_order" class="btn btn-warning btn-sm"><i class="fa fa-reply">&nbsp;</i>Kembali</a>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<?= form_close() ?>
<?php $this->load->view('include/footer'); ?>
<script src="<?= base_url('assets/js/number-divider.min.js') ?>"></script>
<script type="text/javascript">
function calculate_invoice(){
	var inv_po=$("#nilai_po_invoice").val();
	var po_ppn=$('#nilai_ppn').val();
	var ch_ppn=$('#ch_ppn').is(":checked");
//	var ch_pph=$('#ch_pph').is(":checked");
	var inv_dp=$("#potongan_dp").val();
	var inv_claim=$("#potongan_claim").val();
	var potongan_dp=$("#potongan_dp").val();
	var potongan_claim=$("#potongan_claim").val();
	inv_po=(parseFloat(inv_po)-parseFloat(potongan_dp));
	var nilai_invoice=inv_po;
	var inv_ppn=0;
	var inv_pph=0;
	if(ch_ppn){
		inv_ppn=(parseFloat(inv_po)*<?=$def_ppn->info/100?>);
	}
	inv_pph=$("#nilai_pph_invoice").val();
/*
	if(ch_pph){
		inv_pph=(parseFloat(inv_po)*<?=$def_pph->info/100?>);
	}
*/
	nilai_invoice=(parseFloat(inv_ppn)-parseFloat(inv_pph)+parseFloat(inv_po));		
	$("#invoice_ppn").val(inv_ppn);
	$("#nilai_pph_invoice").val(inv_pph);
	$("#nilai_invoice").val(nilai_invoice);
	var req_payment=(parseFloat(nilai_invoice)-parseFloat(potongan_claim));
	$("#request_payment").val(req_payment);	
}
	$(".divide").divide();
	$(".tanggal").datepicker({
		todayHighlight: true,
		dateFormat : "yy-mm-dd",
		showInputs: true,
		autoclose:true
	});
	$('#simpan-com').click(function(e){
		$("#simpan-com").addClass("hidden");
		d_error='';
		e.preventDefault();
   		if($("#request_date").val()==""){
   			d_error='Request Date Error';
   			alert(d_error);
   		}
		var request_payment=$("#request_payment").val();
		var total_bayar=$("#total_bayar").val();
		var nilai_total=$("#nilai_total").val();
		var nilai_invoice=$("#nilai_invoice").val();
		
   		if(request_payment==""){
   			d_error='Request Payment Error';
   			alert(d_error);
   		}
/*
   		if(parseFloat(nilai_total)<(parseFloat(total_bayar)+parseFloat(request_payment))){
   			d_error='Total Payment Error';
   			alert(d_error);
   		}
*/
   		if(nilai_invoice==""){
   			d_error='Nilai Invoice Error';
   			alert(d_error);
   		}
		
		if(d_error==''){
			swal({
				  title: "Save Data?",type: "warning",showCancelButton: true,confirmButtonClass: "btn-danger",confirmButtonText: "Yes",cancelButtonText: "No",closeOnConfirm: true,closeOnCancel: true
				},
				function(isConfirm) {
				  if (isConfirm) {
					  var formData 	=new FormData($('#frm_data')[0]);
					  $.ajax({
							url         : base_url + active_controller+"/request_payment_save",
							type		: "POST",
							data		: formData,
							cache		: false,
							dataType	: 'json',
							processData	: false,
							contentType	: false,
						success: function(data){
							if(data.status == 1){
								swal({
									title: "Success!", text: "Data saved", type: "success", timer: 1500, showConfirmButton: false
								});
//								window.open(base_url + active_controller+"/print_request/"+data.id_request);
//								window.location.href = base_url + active_controller+"/purchase_order";
								window.open = base_url + active_controller+"/purchase_order";
								window.location.href(base_url + active_controller+"/print_request/"+data.id_request);

							} else {
								swal({
									title: "Failed!", text: "Save Error", type: "error", timer: 1500, showConfirmButton: false
								});
							};
							console.log(msg);
						},
						error: function(msg){
						$("#simpan-com").removeClass("hidden");
						  swal({
							  title: "Error!",text: "Ajax Error",type: "error",timer: 1500, showConfirmButton: false
						  });
						  console.log(msg.responseText);
						}
					  });
			     }
				 else{
					$("#simpan-com").removeClass("hidden");
				 }
		  });
		}else{
			$("#simpan-com").removeClass("hidden");
		}
   	});


<?php
if(isset($status)){
	if($status=='view'){
		echo '$("#frm_data :input").prop("disabled", true);
		$(".stsview").addClass("hidden");';
	}
}
?>
</script>