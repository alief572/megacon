<?php
$ENABLE_ADD     = has_permission('Product_Jenis.Add');
$ENABLE_MANAGE  = has_permission('Product_Jenis.Manage');
$ENABLE_VIEW    = has_permission('Product_Jenis.View');
$ENABLE_DELETE  = has_permission('Product_Jenis.Delete');
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
		<?php if ($ENABLE_ADD) : ?>
			<a class="btn btn-success btn-sm add" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Add</a>
		<?php endif; ?>

		<span class="pull-right">
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Kategori Produk</th>
					<th>Tipe Ukuran</th>
					<th>Varian</th>
					<th>Jenis Code</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($result)) {
				} else {
					$numb = 0;
					foreach ($result as $record) {
						$numb++;
						$product_type 		= (!empty($get_level_1[$record->code_lv1]['nama'])) ? $get_level_1[$record->code_lv1]['nama'] : '';
						$product_category 	= (!empty($get_level_2[$record->code_lv1][$record->code_lv2]['nama'])) ? $get_level_2[$record->code_lv1][$record->code_lv2]['nama'] : '';

				?>
						<tr>
							<td><?= $numb; ?></td>
							<td><?= strtoupper($product_type) ?></td>
							<td><?= strtoupper($product_category) ?></td>
							<td><?= strtoupper($record->nama) ?></td>
							<td><?= strtoupper($record->code) ?></td>

							<td>
								<?php if ($record->status == '1') { ?>
									<label class="label label-success">Aktif</label>
								<?php } else { ?>
									<label class="label label-danger">Non Aktif</label>
								<?php } ?>
							</td>
							<td style="padding-left:20px">

								<?php if ($ENABLE_MANAGE) : ?>
									<a class="btn btn-primary btn-sm edit" href="javascript:void(0)" title="Edit" data-id="<?= $record->id ?>"><i class="fa fa-edit"></i>
									</a>
								<?php endif; ?>

								<?php if ($ENABLE_DELETE) : ?>
									<a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Delete" data-id="<?= $record->id ?>"><i class="fa fa-trash"></i>
									</a>
								<?php endif; ?>
							</td>

						</tr>
				<?php }
				}  ?>
			</tbody>
		</table>
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
	<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

	<!-- page script -->
	<script type="text/javascript">
		$(document).on('click', '.edit', function(e) {
			var id = $(this).data('id');
			$("#head_title").html("<b>Varian</b>");
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
			$("#head_title").html("<b>Varian</b>");
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
				}
			});
		});

		$(document).on('submit', '#data_form', function(e) {
			e.preventDefault()
			var data = $('#data_form').serialize();
			var code_lv1 = $('#code_lv1').val();
			var code_lv2 = $('#code_lv2').val();

			if (code_lv1 == '0') {
				swal({
					title: "Error Message!",
					text: 'Product type not selected...',
					type: "warning"
				});
				return false;
			}
			if (code_lv2 == '0') {
				swal({
					title: "Error Message!",
					text: 'Product category not selected...',
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
					$.ajax({
						type: 'POST',
						url: siteurl + active_controller + 'add',
						dataType: "json",
						data: data,
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

		$(function() {
			var table = $('#example1').DataTable({
				orderCellsTop: true,
				fixedHeader: true
			});
			$("#form-area").hide();
		});
	</script>