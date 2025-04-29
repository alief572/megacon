<?php
$ENABLE_ADD     = has_permission('Price_Supplier_Raw_Material.Add');
$ENABLE_MANAGE  = has_permission('Price_Supplier_Raw_Material.Manage');
$ENABLE_VIEW    = has_permission('Price_Supplier_Raw_Material.View');
$ENABLE_DELETE  = has_permission('Price_Supplier_Raw_Material.Delete');
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">

<div class="box">
	<div class="box-header">
		<span class="pull-right">
			<a class="btn btn-success btn-sm" href="<?= base_url($this->uri->segment(1) . '/excel_report') ?>" target='_blank' title="Download Excel"> <i class="fa fa-file-excel-o">&nbsp;</i>&nbsp;Download Excel</a>
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="cikarang_tab tab_pin active"><a href="javascript:void();" onclick="change_tab('cikarang')">Cikarang</a></li>
			<li role="presentation" class="palembang_tab tab_pin"><a href="javascript:void();" onclick="change_tab('palembang')">Palembang</a></li>
		</ul>
		<div class="cikarang">
			<table id="example2" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Material Code</th>
						<th>Material Master</th>
						<th>Satuan Beli</th>
						<th>Lower Price<br>Before</th>
						<th>Lower Price<br>After</th>
						<th>Higher Price<br>Before</th>
						<th>Higher Price<br>After</th>
						<th>Expired<br>Before</th>
						<th>Expired<br>After</th>
						<th>Status</th>
						<th>Alasan Reject</th>
						<th width='7%'>Action</th>
					</tr>
				</thead>

				<tbody>

				</tbody>
			</table>
		</div>
		<div class="palembang" style="display: none;">
			<table id="example3" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Material Code</th>
						<th>Material Master</th>
						<th>Satuan Beli</th>
						<th>Lower Price<br>Before</th>
						<th>Lower Price<br>After</th>
						<th>Higher Price<br>Before</th>
						<th>Higher Price<br>After</th>
						<th>Expired<br>Before</th>
						<th>Expired<br>After</th>
						<th>Status</th>
						<th>Alasan Reject</th>
						<th width='7%'>Action</th>
					</tr>
				</thead>

				<tbody>

				</tbody>
			</table>
		</div>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="head_title">Default</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
		</div>
	</div>

	<!-- DataTables -->
	<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
	<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>

	<!-- page script -->
	<script type="text/javascript">
		$(document).on('click', '.edit', function(e) {
			var id = $(this).data('id');
			$("#head_title").html("<b>Material Master</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + active_controller + '/add/' + id,
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

		$(document).on('click', '.add', function() {
			$("#head_title").html("<b>Material Master</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + active_controller + '/add/',
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

		$(document).on('change', '#code_lv1', function() {
			var code_lv1 = $("#code_lv1").val();

			$.ajax({
				url: siteurl + active_controller + '/get_list_level1',
				method: "POST",
				data: {
					code_lv1: code_lv1
				},
				dataType: 'json',
				success: function(data) {
					$('#code_lv2').html(data.option);
					$('#code_lv3').html("<option value='0'>List Empty</option>");
				}
			});
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

		$(document).on('keyup', '.getCub', function() {
			get_cub();
		});


		$(document).on('submit', '#data_form', function(e) {
			e.preventDefault()

			var price_ref_expired = $('#price_ref_expired').val();

			if (price_ref_expired == '0') {
				swal({
					title: "Error Message!",
					text: 'Expired not selected...',
					type: "warning"
				});
				return false;
			}
			// alert(data);

			swal({
					title: "Anda Yakin?",
					text: "Data akan diproses!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-info",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnConfirm: false
				},
				function() {
					// var form_data = $('#data_form').serialize();
					var form_data = new FormData($('#data_form')[0]);
					$.ajax({
						type: 'POST',
						url: siteurl + active_controller + 'add',
						dataType: "json",
						data: form_data,
						processData: false,
						contentType: false,
						success: function(data) {
							if (data.status == '1') {
								swal({
										title: "Sukses",
										text: data.pesan,
										type: "success"
									},
									function() {
										window.location.reload(true);
									})
							} else {
								swal({
									title: "Error",
									text: data.pesan,
									type: "error"
								})

							}
						},
						error: function() {
							swal({
								title: "Error",
								text: "Error proccess !",
								type: "error"
							})
						}
					})
				});

		})


		// DELETE DATA
		$(document).on('click', '.delete', function(e) {
			e.preventDefault()
			var id = $(this).data('id');
			// alert(id);
			swal({
					title: "Anda Yakin?",
					text: "Data akan di hapus!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-info",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnConfirm: false
				},
				function() {
					$.ajax({
						type: 'POST',
						url: siteurl + active_controller + '/delete',
						dataType: "json",
						data: {
							'id': id
						},
						success: function(data) {
							if (data.status == '1') {
								swal({
										title: "Sukses",
										text: data.pesan,
										type: "success"
									},
									function() {
										window.location.reload(true);
									})
							} else {
								swal({
									title: "Error",
									text: data.pesan,
									type: "error"
								})

							}
						},
						error: function() {
							swal({
								title: "Error",
								text: "Error proccess !",
								type: "error"
							})
						}
					})
				});

		})

		$(document).on('click', '#update-kurs', function(e) {
			e.preventDefault();
			var id = $(this).data('id')
			swal({
					title: "Are you sure?",
					text: "Update KURS!",
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
						var baseurl = base_url + active_controller + '/update_kurs'
						$.ajax({
							url: baseurl,
							type: "POST",
							data: {
								'id': id
							},
							cache: false,
							dataType: 'json',
							success: function(data) {
								if (data.status == 1) {
									swal({
										title: "Save Success!",
										text: data.pesan,
										type: "success",
										timer: 3000
									});
									$('#kurs').val(data.kurs)
									swal.close()
								} else {

									if (data.status == 2) {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
										});
									} else {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
										});
									}

								}
							},
							error: function() {

								swal({
									title: "Error Message !",
									text: 'An Error Occured During Process. Please try again..',
									type: "warning",
									timer: 3000,
								});
							}
						});
					} else {
						swal("Cancelled", "Data can be process again :)", "error");
						return false;
					}
				});
		});

		//input idr
		$(document).on('keyup', '#price_ref_new', function() {
			var price_ref = getNum($('#price_ref_new').val().split(",").join(""));
			var kurs = getNum($('#kurs').val().split(",").join(""));
			var price = price_ref / kurs
			$('#price_ref_new_usd').val(price)
		})

		$(document).on('keyup', '#price_ref_high_new', function() {
			var price_ref = getNum($('#price_ref_high_new').val().split(",").join(""));
			var kurs = getNum($('#kurs').val().split(",").join(""));
			var price = price_ref / kurs
			$('#price_ref_high_new_usd').val(price)
		})
		//input usd
		$(document).on('keyup', '#price_ref_new_usd, #kurs', function() {
			var price_ref = getNum($('#price_ref_new_usd').val().split(",").join(""));
			var kurs = getNum($('#kurs').val().split(",").join(""));
			var price = price_ref * kurs
			$('#price_ref_new').val(price)
		})

		$(document).on('keyup', '#price_ref_high_new_usd, #kurs', function() {
			var price_ref = getNum($('#price_ref_high_new_usd').val().split(",").join(""));
			var kurs = getNum($('#kurs').val().split(",").join(""));
			var price = price_ref * kurs
			$('#price_ref_high_new').val(price)
		})

		function DataTables_cikarang() {
			var datatables = $('#example2').dataTable({
				serverSide: true,
				processing: true,
				stateSave: true,
				destroy: true,
				ajax: {
					type: 'post',
					url: siteurl + active_controller + '/get_price_ref',
					dataType: 'json'
				},
				columns: [{
						data: 'no'
					},
					{
						data: 'material_code'
					},
					{
						data: 'material_master'
					},
					{
						data: 'satuan_beli'
					},
					{
						data: 'lower_price_before'
					},
					{
						data: 'lower_price_after'
					},
					{
						data: 'higher_price_before'
					},
					{
						data: 'higher_price_after'
					},
					{
						data: 'expired_before'
					},
					{
						data: 'expired_after'
					},
					{
						data: 'status'
					},
					{
						data: 'alasan_reject'
					},
					{
						data: 'action'
					}
				]
			});
		}

		function DataTables_palembang() {
			var datatables = $('#example3').dataTable({
				serverSide: true,
				processing: true,
				stateSave: true,
				destroy: true,
				ajax: {
					type: 'post',
					url: siteurl + active_controller + '/get_price_ref_2',
					dataType: 'json'
				},
				columns: [{
						data: 'no'
					},
					{
						data: 'material_code'
					},
					{
						data: 'material_master'
					},
					{
						data: 'satuan_beli'
					},
					{
						data: 'lower_price_before'
					},
					{
						data: 'lower_price_after'
					},
					{
						data: 'higher_price_before'
					},
					{
						data: 'higher_price_after'
					},
					{
						data: 'expired_before'
					},
					{
						data: 'expired_after'
					},
					{
						data: 'status'
					},
					{
						data: 'alasan_reject'
					},
					{
						data: 'action'
					}
				]
			});
		}

		$(function() {
			DataTables_cikarang();
			DataTables_palembang();
			$("#form-area").hide();
		});

		function change_tab(tab) {
			if(tab == 'cikarang') {
				$('.cikarang').show();
				$('.cikarang_tab').addClass('active');
				
				$('.palembang').hide();
				$('.palembang_tab').removeClass('active');
			} else {
				$('.palembang').show();
				$('.palembang_tab').addClass('active');
				
				$('.cikarang').hide();
				$('.cikarang_tab').removeClass('active');
			}
		}

		function get_cub() {
			var l = getNum($('#length').val().split(",").join(""));
			var w = getNum($('#wide').val().split(",").join(""));
			var h = getNum($('#high').val().split(",").join(""));
			var cub = (l * w * h) / 1000000000;

			$('#cub').val(cub.toFixed(7));
		}
	</script>