<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<form action="#" method="POST" id="form_proses_bro" enctype="multipart/form-data">
	<div class="box box-primary">
		<div class="box-header">
			<div class="box-tool pull-right">
				<?php
				echo form_button(array('type' => 'button', 'class' => 'btn btn-sm btn-danger', 'style' => 'min-width:100px; float:right; margin: 5px 0px 5px 5px;', 'content' => 'Clear Propose Request', 'id' => 'autoDelete'));
				echo form_button(array('type' => 'button', 'class' => 'btn btn-sm btn-primary', 'style' => 'min-width:100px; float:right; margin: 5px 0px 5px 5px;', 'content' => 'Set Auto Propose', 'id' => 'autoPropose'));
				?>
			</div>
			<div class="box-tool pull-left">
				<label for="tgl_butuh"><b>Tanggal Dibutuhkan</b></label>
				<?php
				$tgl_now = date('Y-m-d');
				$tgl_next_month = date('Y-m-' . '20', strtotime('+1 month', strtotime($tgl_now)));
				echo form_input(array('id' => 'tgl_butuh', 'name' => 'tgl_butuh', 'class' => 'form-control input-md text-center tgl changeSaveDate', 'readonly' => 'readonly', 'placeholder' => 'Tanggal Dibutuhkan'), $tgl_next_month);
				?>
			</div>
			<div class="box-tool pull-left">
				<label for="tgl_butuh"><b>Tingkat PR</b></label>
				<select name="tingkat_pr" id="" class="form-control input-md tingkat_pr">
					<option value="1">Normal</option>
					<option value="2">Urgent</option>
				</select>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- <table id="example1" class="table table-bordered table-striped" width='100%'>
				<thead>
					<tr>
						<th class="text-center mid" rowspan='2' width='3%'>#</th>
						<th class="text-center mid" rowspan='2'>Id Material</th>
						<th class="text-center mid" rowspan='2'>Material</th>
						<th class="text-center mid" rowspan='2'>Category</th>
						<th class="text-center mid" colspan='4'>Stock Free</th>
						<th class="text-center mid" rowspan='2'>Min Stock</th>
						<th class="text-center mid" rowspan='2'>Max Stock</th>
						<th class="text-center mid" rowspan='2'>PR On Progress</th>
						<th class="text-center mid" colspan='2'>Propose Request</th>
						<th class="text-center mid" rowspan='2'>Packing Unit</th>
						<th class="text-center mid" rowspan='2'>Keterangan</th>
					</tr>
					<tr>
						<th>Qty Pack</th>
						<th>Pack Unit</th>
						<th>Convertion</th>
						<th>Weight (Kg)</th>
						<th width='7%'>Kg</th>
						<th width='7%'>Qty Packing</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			<br> -->
			<?php
			// echo form_button(array('type' => 'button', 'class' => 'btn btn-md btn-success', 'style' => 'min-width:100px; float:right; margin: 5px 0px 5px 0px;', 'content' => 'Purchase Request', 'id' => 'saveRequest')) . ' ';
			?>
			<!-- <br><h2>DATA TABLE BARU</h2><br> -->
			<table id="example2" class="table table-bordered table-striped" width='100%'>
				<thead>
					<tr>
						<th class="text-center mid" >#</th>
						<th class="text-center mid" >Material</th>
						<th class="text-center mid" >Stock (Pack)</th>
						<th class="text-center mid" >Satuan (Pack)</th>
						<th class="text-center mid" >Konversi</th>
						<th class="text-center mid" >Stok Konversi</th>
						<th class="text-center mid" >Unit Measurement</th>
						<th class="text-center mid" >Pemakaian Sehari (Pack)</th>
						<th class="text-center mid" >Sisa Kecukupan (Hari)</th>
						<th class="text-center mid" >Max. Stock (Pack)</th>
						<!-- <th class="text-center mid" >Qty PR</th> -->
						<th class="text-center" >Propose Request</th>
					</tr>
					<!-- <tr>
						<th>Qty Pack</th>
						<th>Pack Unit</th>
						<th>Convertion</th>
						<th>Weight (Kg)</th>
						<th width='7%'>Kg</th>
						<th width='7%'>Qty Packing</th>
					</tr> -->
				</thead>
				<tbody></tbody>
			</table>
			<br>
			<?php
			echo form_button(array('type' => 'button', 'class' => 'btn btn-md btn-success', 'style' => 'min-width:100px; float:right; margin: 5px 0px 5px 0px;', 'content' => 'Purchase Request', 'id' => 'saveRequest')) . ' ';
			?>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->

</form>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<style>
	.tgl {
		cursor: pointer;
	}
</style>
<script>
	$(document).ready(function() {
		// DataTables();//version old
		DataTables_New();
		$('.autoNumeric2').autoNumeric('init', {
			mDec: '2',
			aPad: false
		})
		$('.tgl').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
		});
	});

	$(document).on('click', '#autoDelete', function() {

		swal({
				title: "Are you sure?",
				text: "Clear All Propose Request !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, Process it!",
				cancelButtonText: "No, cancel process!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: base_url + active_controller + '/clear_update_reorder',
						type: "POST",
						cache: false,
						dataType: 'json',
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000
								});
								window.location.href = base_url + active_controller + 'add';
							} else if (data.status == 0) {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000
								});
							}
						},
						error: function() {
							swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 7000
							});
						}
					});
				} else {
					swal("Cancelled", "Data can be process again :)", "error");
					return false;
				}
			});
	});

	$(document).on('change', '.changeSave', function() {
		var nomor = $(this).data('no');
		var id_material = $(this).data('id_material');
		var purchase = $('#purchase_' + nomor).val().split(",").join("");
		// var keterangan = $('#keterangan_' + nomor).val().split(",").join("");//version old
		var tanggal = $('#tgl_butuh').val();

		var HTML = $(this).parent().parent().parent()
		var konversi = getNum(HTML.find('.konversi').text().split(",").join(""));
		var propose_pack = 0;
		if (konversi > 0 && purchase > 0) {
			var propose_pack = purchase / konversi;
		}

		HTML.find('.propose_packing').text(number_format(propose_pack, 2))

		// console.log(konversi)

		$.ajax({
			url: base_url + active_controller + '/save_reorder_change',
			type: "POST",
			data: {
				"id_material": id_material,
				"purchase": purchase,
				// "keterangan": keterangan,
				"tanggal": tanggal
			},
			cache: false,
			dataType: 'json',
			success: function(data) {
				console.log(data.pesan)
			},
			error: function() {
				console.log('error connection serve !')
			}
		});
	});

	$(document).on('click', '#saveRequest', function() {

		var tingkat_pr = $('.tingkat_pr').val();

		swal({
				title: "Are you sure?",
				text: "Membuat semua Propose Material !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, Process it!",
				cancelButtonText: "No, cancel process!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: base_url + active_controller + '/save_reorder_all',
						type: "POST",
						data: {
							'tingkat_pr': tingkat_pr
						},
						cache: false,
						dataType: 'json',
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000
								});
								window.location.href = base_url + active_controller
							} else if (data.status == 0) {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000
								});
							}
						},
						error: function() {
							swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 7000
							});
						}
					});
				} else {
					swal("Cancelled", "Data can be process again :)", "error");
					return false;
				}
			});
	});

	$(document).on('change', '.changeSaveDate', function() {
		var tanggal = $('#tgl_butuh').val();

		$.ajax({
			url: base_url + active_controller + '/save_reorder_change_date',
			type: "POST",
			data: {
				"tanggal": tanggal
			},
			cache: false,
			dataType: 'json',
			success: function(data) {
				console.log(data.pesan)
			},
			error: function() {
				console.log('error connection serve !')
			}
		});
	});

	$(document).on('click', '#autoPropose', function() {

		swal({
				title: "Are you sure?",
				text: "Set Auto Propose !!!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, Process it!",
				cancelButtonText: "No, cancel process!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: base_url + active_controller + '/set_update_propose_reorder',
						type: "POST",
						cache: false,
						dataType: 'json',
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: data.pesan,
									type: "success",
									timer: 7000
								});
								window.location.href = base_url + active_controller + 'add';
							} else if (data.status == 0) {
								swal({
									title: "Save Failed!",
									text: data.pesan,
									type: "warning",
									timer: 7000
								});
							}
						},
						error: function() {
							swal({
								title: "Error Message !",
								text: 'An Error Occured During Process. Please try again..',
								type: "warning",
								timer: 7000
							});
						}
					});
				} else {
					swal("Cancelled", "Data can be process again :)", "error");
					return false;
				}
			});
	});


	function DataTables() {
		var dataTable = $('#example1').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"aaSorting": [
				[2, "asc"]
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [
				[10, 20, 50, 100, 150],
				[10, 20, 50, 100, 150]
			],
			"ajax": {
				url: base_url + active_controller + '/server_side_reorder_point',
				type: "post",
				// data: function(d){
				// d.gudang = $('#gudang').val()
				// },
				cache: false,
				// success: function(){
				// 	$('.maskM').autoNumeric('init', {mDec: '2', aPad: false});
				// },
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}

	function DataTables_New() {
		var dataTable = $('#example2').DataTable({
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"aaSorting": [
				[2, "asc"]
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [
				[10, 20, 50, 100, 150],
				[10, 20, 50, 100, 150]
			],
			"ajax": {
				url: base_url + active_controller + '/server_side_reorder_point_new',
				type: "post",
				// data: function(d){
				// d.gudang = $('#gudang').val()
				// },
				cache: false,
				// success: function(){
				// 	$('.maskM').autoNumeric('init', {mDec: '2', aPad: false});
				// },
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}

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