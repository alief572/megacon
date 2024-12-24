<?php
$ENABLE_ADD     = has_permission('Komposisi_Beton.Add');
$ENABLE_MANAGE  = has_permission('Komposisi_Beton.Manage');
$ENABLE_VIEW    = has_permission('Komposisi_Beton.View');
$ENABLE_DELETE  = has_permission('Komposisi_Beton.Delete');
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">

<div class="box">
	<div class="box-header">
		<span class="pull-right">
			<?php if ($ENABLE_ADD) : ?>
				<a class="btn btn-success btn-sm" href="<?= base_url('komposisi_beton/add') ?>" title="Add"> <i class="fa fa-plus">&nbsp;</i>Add</a>
			<?php endif; ?>
			<!-- <a class="btn btn-warning btn-sm" href="<?= base_url('komposisi_beton/excel_download') ?>" target='_blank' title="Download Excel"> <i class="fa fa-file-excel-o">&nbsp;</i>&nbsp;Download Excel</a> -->

		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<th class="text-center">#</th>
				<th class="text-center">Jenis Beton</th>
				<th class="text-center">Total Volume (m3)</th>
				<th class="text-center">Keterangan</th>
				<th class="text-center">Action</th>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<style>
	.box-primary {

		border: 1px solid #ddd;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		DataTables();
	});

	function DataTables() {
		var DataTables = $('#example1').dataTable({
			ajax: {
				url: siteurl + active_controller + 'get_data_jenis_beton',
				type: "POST",
				dataType: "JSON",
				data: function(d) {

				}
			},
			columns: [{
					data: 'no'
				},
				{
					data: 'jenis_beton'
				},
				{
					data: 'volume'
				},
				{
					data: 'keterangan'
				},
				{
					data: 'option'
				}
			],
			responsive: true,
			processing: true,
			serverSide: true,
			stateSave: true,
			destroy: true,
			paging: true
		});
	}

	$(document).on('click', '.del_komposisi_beton', function() {
		var id = $(this).data('id');

		swal({
			type: 'warning',
			title: 'Are you sure ?',
			text: 'This data will be deleted !',
			showCancelButton: true
		}, function(next) {
			if (next) {
				$.ajax({
					type: 'post',
					url: siteurl + active_controller + 'del_komposisi_beton',
					data: {
						'id': id
					},
					dataType: 'json',
					cache: false,
					success: function(result) {
						if (result.status == 1) {
							swal({
								type: 'success',
								title: 'Success !',
								text: result.pesan
							}, function(lanjut) {
								window.location.href = siteurl + active_controller;
							});
						} else {
							swal({
								type: 'warning',
								title: 'Failed !',
								text: result.pesan
							});
						}
					},
					error: function(result) {
						swal({
							type: 'error',
							title: 'Error !',
							text: 'Please try again later !'
						});
					}
				});
			}
		});
	});
</script>