<?php
$ENABLE_ADD     = has_permission('Quotation.Add');
$ENABLE_MANAGE  = has_permission('Quotation.Manage');
$ENABLE_VIEW    = has_permission('Quotation.View');
$ENABLE_DELETE  = has_permission('Quotation.Delete');

?>
<style type="text/css">
	thead input {
		width: 100%;
	}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="box">
	<div class="box-header">
		<?php if ($ENABLE_VIEW) : ?>
			<!-- <button class="btn btn-success" type="button" onclick="add_inv()">
				<i class="fa fa-plus"></i>Add Penawaran
			</button> -->
			<!-- <button class="btn btn-success" type="button" onclick="add_quote()">
				<i class="fa fa-plus"></i> Add Penawaran
			</button> -->
			<button class="btn btn-success" type="button" onclick="openAddInvoice()">
				<i class="fa fa-plus"></i> Add Penawaran
			</button>
		<?php endif; ?>

		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center">Date</th>
					<th class="text-center">Customer</th>
					<th class="text-center">Quotation No.</th>
					<th class="text-center">Project</th>
					<th class="text-center">Rev</th>
					<th class="text-center">Status</th>
					<th class="text-center">Reject Reason</th>
					<?php if ($ENABLE_MANAGE) : ?>
						<th>Action</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Rekap Data Customer</h4>
			</div>
			<div class="modal-body" id="MyModalBody">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<span class="glyphicon glyphicon-remove"></span> Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Data Penawaran</h4>
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

<!-- modal -->
<div class="modal modal-default fade" id="ModalViewX" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id='head_title'>Closing Penawaran</h4>
			</div>
			<div class="modal-body" id="viewX">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id='close_penawaran'>Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal modal-default fade" id="ModalPrintQuote" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id='head_title'>Print Quotation</h4>
			</div>
			<form action="" id="print_quotation_form">
				<div class="modal-body" id="viewX">
					<input type="hidden" name="no_penawaran" class="no_penawaran">
					<div class="form-group">
						<label for="">Show PPN / Hide PPN</label>
						<select name="show_hide_ppn" id="" class="form-control form-control-sm show_hide_ppn">
							<option value="1">Show PPN</option>
							<option value="0">Hide PPN</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Show Discount / Hide Discount</label>
						<select name="show_hide_disc" id="" class="form-control form-control-sm show_hide_disc">
							<option value="1">Show Discount</option>
							<option value="0">Hide Discount</option>
						</select>
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal modal-default fade" id="ModalAddQuote" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id='head_title'>Create Quotation</h4>
			</div>
			<form action="" id="add_quotation_form">
				<div class="modal-body" id="viewX">
					<div class="form-group">
						<label for="">Quotation Type</label>
						<select name="currency" id="" class="form-control form-control-sm currency" required>
							<option value="IDR">Lokal</option>
							<option value="USD">Export</option>
						</select>
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- page script -->
<script type="text/javascript">
	$(document).ready(function() {
		DataTables();

		$(".chosen-select").select2({
			width: '100%'
		});
	});

	$(document).on('click', '.edit', function(e) {
		var id = $(this).data('no_penawaran');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Edit Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'wt_penawaran/editPenawaran/' + id,
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});

	$(document).on('click', '.cetak', function(e) {
		var id = $(this).data('no_penawaran');
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Edit Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'xtes/cetak' + id,
			success: function(data) {

			}
		})
	});

	$(document).on('click', '.view', function() {
		var id = $(this).data('no_penawaran');
		// alert(id);
		$("#head_title").html("<i class='fa fa-list-alt'></i><b>Detail Inventory</b>");
		$.ajax({
			type: 'POST',
			url: siteurl + 'penawaran/ViewHeader/' + id,
			data: {
				'id': id
			},
			success: function(data) {
				$("#dialog-popup").modal();
				$("#ModalView").html(data);

			}
		})
	});



	// CLOSE PENAWARAN
	$(document).on('click', '.close_penawaran', function(e) {
		e.preventDefault();
		var id = $(this).data('no_penawaran');

		$("#head_title").html("Closing Penawaran");
		$.ajax({
			type: 'POST',
			url: base_url + active_controller + '/modal_closing_penawaran/' + id,
			success: function(data) {
				$("#ModalViewX").modal();
				$("#viewX").html(data);

			},
			error: function() {
				swal({
					title: "Error Message !",
					text: 'Connection Timed Out ...',
					type: "warning",
					timer: 5000,
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				});
			}
		});
	});

	$(document).on('click', '.ajukan', function() {
		var id = $(this).data('id');
		var status = $(this).data('status');
		var tingkatan = $(this).data('tingkatan');

		msg = 'Anda yakin ingin update penawaran ini ke Waiting Approval ?';

		swal({
				title: "Peringatan !",
				text: msg,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Update!",
				cancelButtonText: "Batal!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: siteurl + active_controller + "update_status",
						dataType: "json",
						type: 'POST',
						data: {
							'id': id,
							'status': status,
							'tingkatan': tingkatan
						},
						success: function(data) {
							var updated_status = 'Waiting Approval';
							if (data.updated_status == '2') {
								updated_status = 'Waiting SO';
							}
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: 'Selamat, status penawaran telah terupdate menjadi ' + updated_status,
									type: "success",
									timer: 15000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: true
								});
								location.reload();
							} else {

								if (data.status == 2) {
									swal({
										title: "Save Failed!",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								} else {
									swal({
										title: "Save Failed!",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}

							}
						},
						error: function() {
							swal({
								title: "Gagal!",
								text: "Batal Proses, Data bisa diproses nanti",
								type: "error",
								timer: 1500,
								showConfirmButton: false
							});
						}
					});
				}
			});
	});

	$(document).on('click', '.approve', function() {
		var id = $(this).data('id');
		// var status = $(this).data('status');

		msg = 'Anda yakin ingin approve penawaran ini ?';


		swal({
				title: "Peringatan !",
				text: msg,
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Approve!",
				cancelButtonText: "Batal!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: siteurl + active_controller + "approve_penawaran",
						dataType: "json",
						type: 'POST',
						data: {
							'id': id
						},
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Save Success!",
									text: 'Selamat, penawaran telah berhasil di approve !',
									type: "success",
									timer: 15000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: true
								});
								location.reload();
							} else {

								if (data.status == 2) {
									swal({
										title: "Maaf, penawaran gagal di approve !",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								} else {
									swal({
										title: "Maaf, penawaran gagal di approve !",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}

							}
						},
						error: function() {
							swal({
								title: "Gagal!",
								text: "Batal Proses, Data bisa diproses nanti",
								type: "error",
								timer: 1500,
								showConfirmButton: false
							});
						}
					});
				}
			});
	});

	$(document).on('click', '.loss', function() {
		var id = $(this).data('id');

		swal({
				title: "Peringatan !",
				text: 'Anda yakin ingin update penawaran ini ke Loss ?',
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Loss!",
				cancelButtonText: "Batal!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: siteurl + active_controller + "loss_penawaran",
						dataType: "json",
						type: 'POST',
						data: {
							'id': id
						},
						success: function(data) {
							if (data.status == 1) {
								swal({
									title: "Update Success!",
									text: 'Selamat, penawaran telah berhasil di loss !',
									type: "success",
									timer: 5000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: true
								});
								location.reload();
							} else {

								if (data.status == 2) {
									swal({
										title: "Maaf !",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								} else {
									swal({
										title: "Maaf !",
										text: data.pesan,
										type: "warning",
										timer: 10000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}

							}
						},
						error: function() {
							swal({
								title: "Gagal!",
								text: "Batal Proses, Data bisa diproses nanti",
								type: "error",
								timer: 1500,
								showConfirmButton: false
							});
						}
					});
				}
			});
	});

	$(document).on('click', '.print_quotation', function() {
		var id_penawaran = $(this).data('id_penawaran');

		$('.no_penawaran').val(id_penawaran);
		$('#ModalPrintQuote').modal('show');
	});

	$(document).on('submit', '#print_quotation_form', function(e) {
		e.preventDefault();
		var no_penawaran = $('.no_penawaran').val();

		var show_hide_ppn = $('.show_hide_ppn').val();
		var show_hide_disc = $('.show_hide_disc').val();
		if (show_hide_ppn > 0) {
			if (show_hide_disc > 0) {
				window.open(siteurl + active_controller + 'print_quotation/' + no_penawaran + '/' + '1');
			} else {
				window.open(siteurl + active_controller + 'print_quotation/' + no_penawaran);
			}
		} else {
			if (show_hide_ppn !== null) {
				if (show_hide_disc > 0) {
					window.open(siteurl + active_controller + 'print_quotation_non_ppn/' + no_penawaran + '/' + '1');
				} else {
					window.open(siteurl + active_controller + 'print_quotation_non_ppn/' + no_penawaran);
				}
			} else {
				swal({
					title: "Error !",
					text: 'Please select show / hide PPn First !!',
					type: "warning"
				});
			}
		}
	});

	$(document).on('submit', '#add_quotation_form', function(e) {
		e.preventDefault();
		var curr = $('.currency').val();

		window.open(siteurl + active_controller + 'modal_add_invoice/' + curr);
	});

	function DataTables() {
		$('#example1').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				type: 'post',
				url: siteurl + active_controller + 'get_quotation'
			},
			columns: [
				{
					data: 'no'
				},
				{
					data: 'tgl'
				},
				{
					data: 'customer'
				},
				{
					data: 'quotation_no'
				},
				{
					data: 'project'
				},
				{
					data: 'rev'
				},
				{
					data: 'status'
				},
				{
					data: 'reject_reason'
				},
				{
					data: 'option'
				}
			]
		});
	}

	function add_inv() {
		window.location.href = base_url + active_controller + 'modal_detail_invoice';
	}

	function add_quote() {
		$("#ModalAddQuote").modal('show');
		$(".currency").val('');
	}

	function openAddInvoice() {
		const siteurl = '<?php echo site_url(); ?>';
		// const active_controller = '<?php echo $this->uri->segment(1); ?>/';
		const curr = 'IDR'; // Ganti ini sesuai nilai dinamis yang kamu butuhkan

		window.open(siteurl + active_controller + 'modal_add_invoice/' + curr, '_blank');
	}

	//Delete

	function PreviewPdf(id) {
		param = id;
		tujuan = 'customer/print_request/' + param;

		$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="570" height="400"></iframe>');
	}

	function PreviewRekap() {
		tujuan = 'customer/rekap_pdf';
		$(".modal-body").html('<iframe src="' + tujuan + '" frameborder="no" width="100%" height="400"></iframe>');
	}
</script>