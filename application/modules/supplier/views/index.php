<?php
$ENABLE_ADD     = has_permission('Master_Supplier.Add');
$ENABLE_MANAGE  = has_permission('Master_Supplier.Manage');
$ENABLE_VIEW    = has_permission('Master_Supplier.View');
$ENABLE_DELETE  = has_permission('Master_Supplier.Delete');
?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box">
	<div class="box-header">
		<span class="pull-left">
			<?php if ($ENABLE_ADD) : ?>
				<!-- <a class="btn btn-success btn-sm" href="<?= base_url('supplier/add') ?>" title="Add"> <i class="fa fa-plus">&nbsp;</i>Add</a> -->
				<a class="btn btn-success btn-sm add_supplier" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Tambah Data</a>
			<?php endif; ?>
			<a class="btn btn-warning btn-sm" href="<?= base_url('supplier/excel_report_all') ?>" target='_blank' title="Download Excel"> <i class="fa fa-file-excel-o">&nbsp;</i>&nbsp;Download Excel</a>

		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Supplier Name</th>
					<th>Country</th>
					<th>Telp</th>
					<th>Fax</th>
					<th>Email</th>
					<th>Last By</th>
					<th class='text-center'>Last Date</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody></tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Data Supplier</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

<!-- page script -->
<script type="text/javascript">
	$(document).on('click', '.detail', function() {
		var no_bom = $(this).data('no_bom');
		// alert(id);
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Detail Bill Of Material</b>");
		$.ajax({
			type: 'POST',
			url: base_url + active_controller + 'detail/' + no_bom,
			data: {
				'no_bom': no_bom
			},
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});
	$(document).on('click', '.add', function() {
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Tambah Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'inventory_1/addInventory',
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});

	// FORM MODAL ADD SUPPLIER
	$(document).on('click', '.add_supplier', function() {
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Tambah Data</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'supplier/add',
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);
			}
		})
	});

	//FORM MODAL EDIT SUPPLIER
	$(document).on('click', '.edit_supplier', function() {
		const id = $(this).data('id');

		$("#head_title").html("<i class='fa fa-edit'></i><b>Edit Data</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'supplier/edit',
			data: {
				id: id
			}, // Kirim sebagai POST
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);
			}
		});
	});



	// DELETE DATA
	$(document).on('click', '.delete', function(e) {
		e.preventDefault()
		var id = $(this).data('id');
		// alert(id);
		swal({
				title: "Anda Yakin?",
				text: "Data BOM akan di hapus !",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: "Ya, Hapus!",
				cancelButtonText: "Batal",
				closeOnConfirm: false
			},
			function() {
				$.ajax({
					type: 'POST',
					url: base_url + active_controller + '/hapus',
					dataType: "json",
					data: {
						'id': id
					},
					success: function(result) {
						if (result.status == '1') {
							swal({
									title: "Sukses",
									text: "Data berhasil dihapus.",
									type: "success"
								},
								function() {
									window.location.reload(true);
								})
						} else {
							swal({
								title: "Error",
								text: "Data error. Gagal hapus data",
								type: "error"
							})

						}
					},
					error: function() {
						swal({
							title: "Error",
							text: "Data error. Gagal request Ajax",
							type: "error"
						})
					}
				})
			});

	});

	$(function() {
		DataTables();
	});


	function DataTables() {
		var dataTable = $('#example1').DataTable({
			// "scrollX": true,
			"scrollY": "500",
			"scrollCollapse": true,
			"processing": true,
			"serverSide": true,
			"stateSave": true,
			"bAutoWidth": true,
			"destroy": true,
			"responsive": true,
			"aaSorting": [
				[1, "asc"]
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
				url: base_url + active_controller + '/get_json_supplier',
				type: "post",
				data: function(d) {
					// d.kode_partner = $('#kode_partner').val()
				},
				cache: false,
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display", "none");
				}
			}
		});
	}
</script>