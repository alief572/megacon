<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
			<input type="hidden" name='so_number' id='so_number' value='<?= $header[0]['so_number']; ?>'>
			<input type="hidden" name='id_material_plan_detail' id='id_material_plan_detail' value='<?= $id_detail; ?>'>
			<div class="form-group row">
				<div class="col-md-12">
					<table class='table' width='70%'>
						<tr>
							<td width='20%'>No. SO</td>
							<td width='1%'>:</td>
							<td width='29%'><?= $header[0]['so_number']; ?></td>
							<!-- <td width='20%'>Due Date SO</td>
							<td width='1%'>:</td>
							<td width='29%'><?= date('d F Y', strtotime($header[0]['due_date'])); ?></td> -->
							<td width='20%'>Periode Planning</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<div style="display: flex; gap: 10px;">
								<!-- <input type="month" id="bulanTahun" name="bulanTahun"> -->
								<select id="bulan" name="bulan" class="form-control" style="width: 20%;" <?= (!empty($header[0]['periode_bulan'])) ? 'disabled' : '' ?> >
								    <option value="01" <?= (@$DataPlan->periode_bulan == '1' || @$DataPlan->periode_bulan == '01') ? 'selected' : '' ?>>Januari</option>
								    <option value="02" <?= (@$header[0]['periode_bulan'] == '2') ? 'selected' : '' ?>>Februari</option>
								    <option value="03" <?= (@$header[0]['periode_bulan'] == '3') ? 'selected' : '' ?>>Maret</option>
								    <option value="04" <?= (@$header[0]['periode_bulan'] == '4') ? 'selected' : '' ?>>April</option>
								    <option value="05" <?= (@$header[0]['periode_bulan'] == '5') ? 'selected' : '' ?>>Mei</option>
								    <option value="06" <?= (@$header[0]['periode_bulan'] == '6') ? 'selected' : '' ?>>Juni</option>
								    <option value="07" <?= (@$header[0]['periode_bulan'] == '7') ? 'selected' : '' ?>>Juli</option>
								    <option value="08" <?= (@$header[0]['periode_bulan'] == '8') ? 'selected' : '' ?>>Agustus</option>
								    <option value="09" <?= (@$header[0]['periode_bulan'] == '9') ? 'selected' : '' ?>>September</option>
								    <option value="10" <?= (@$header[0]['periode_bulan'] == '10') ? 'selected' : '' ?>>Oktober</option>
								    <option value="11" <?= (@$header[0]['periode_bulan'] == '11') ? 'selected' : '' ?>>November</option>
								    <option value="12" <?= (@$header[0]['periode_bulan'] == '12') ? 'selected' : '' ?>>Desember</option>
								</select>
								<select id="tahun" name="tahun" class="form-control" style="width: 20%;" <?= (!empty($header[0]['periode_tahun'])) ? 'disabled' : '' ?> >
								    <?php
								    $tahunSekarang = date('Y');
								    for ($i = $tahunSekarang; $i >= $tahunSekarang - 10; $i--) {
								        $selected = ($header[0]['periode_tahun'] == $i) ? 'selected' : '';
								        echo "<option value=\"$i\" $selected>$i</option>";
								    }
								    ?>
								</select>
								</div>
							</td>
						</tr>
						<tr hidden>
							<td>Customer</td>
							<td>:</td>
							<td>
								&nbsp;
							</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php
						$tgl_dibutuhkan = (!empty($header[0]['tgl_dibutuhkan'])) ? date('d-M-Y', strtotime($header[0]['tgl_dibutuhkan'])) : '';
						?>
						<tr hidden>
							<td>Tgl Dibutuhkan <span class='text-red'>*</span></td>
							<td>:</td>
							<td><input type="text" name='tgl_dibutuhkan' id='tgl_dibutuhkan' class='form-control input-sm datepicker' value='<?= $tgl_dibutuhkan; ?>' readonly style='width: 200px;'></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
				<div class="col-md-12">
					<div style="align-items: left;">
							<!-- <a class="btn btn-success btn-sm add_tgl_plan" style='float:left;' href="<?= base_url('Mat_plan_base_on_produksi/create_add_detail_tgl') ?>" title="Create Plan">Create Tanggal</a> -->
							<?php
							if($type != 'view'){
							?>
							<a class="btn btn-success btn-sm add_tgl_plan" style='float:left;' title="Create Plan" data-id="<?= $id_detail ?>" data-so="<?= $so_number ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Tanggal</a>
							<?php
							}
							?>
					</div>
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%' id="planningTable">
						<thead class='thead'>
							<tr class='bg-blue' id="mainHeader">
								<th class='text-center th'>#</th>
								<th class='text-center th'>Material Name</th>
								<th class='text-center th'>Tanggal Rencana Kedatangan</th>
								<th class='text-center th'>Qty Kedatangan</th>
								<!-- <th class='text-center th'>Stock Saat ini</th>
								<th class='text-center th'>Pemakaian Sehari</th>
								<th class='text-center th'>Sisa Kecukupan</th>
								<th class='text-center th'>Jumlah Sekali Pengiriman</th>
								<th class='text-center th'>Cycle Order</th> -->
								<?php
								if($type != 'view'){
								?>
								<th class='text-center th'>Option</th>
								<?php
								}
								?>
								<!-- <th class='text-center th'>Propose Purchase</th>
								<th class='text-center th'>Keterangan</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							 $i = 1;
							$GET_OUTANDING_PR = get_pr_on_progress();
							foreach ($detail_tgl as $key => $value) {
								$key++;
								$nm_material = $value['name_material'];

								// echo "<tr data-key='". $key. "' >";
								echo "<tr >";
								echo "<td class='text-center'>" . $i . "</td>";
								echo "<td class='text-left'>" . $nm_material . "
									<input type='hidden' name='detail[" . $key . "][id]' value='" . $value['id'] . "'>
									<input type='hidden' name='detail[" . $key . "][id_material_planning_base_on_produksi_detail]' value='" . $value['id_material_planning_base_on_produksi_detail'] . "'>
									</td>";
								// echo "<td align='center'><input type='date' class='form-control input-sm text-right datepicker' style='width: 120px;' name='detail[" . $key . "][tgl_rencana]' value=''></td>";
								echo "<td class='text-left'>" . $value['tgl_rencana_kedatangan'] . "</td>";
								echo "<td class='text-left'>" . $value['qty_kedatangan'] . "</td>";
								if($type != 'view'){
								echo "<td class='text-right'>";
								?>
								<a class="btn btn-success btn-sm edit_tgl_plan" title="Edit Tanggal" data-id="<?= $id_detail ?>"  data-so="<?= $value['so_number'] ?>" data-id_plan_detail="<?= $value['id'] ?>" data-id_material="<?= $value['id_material'] ?>"><i class="fa fa-edit"></i></a>
								<a class="btn btn-danger btn-sm delete_tgl_plan" href="javascript:void(0)" title="Delete" data-id="<?= $value['id'] ?>"><i class="fa fa-trash"></i>
									</a>
								<?php
								echo "</td>";
								}
								echo "</tr>";
								$i++;
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					<!-- <button type="button" class="btn btn-primary" name="save" id="save">Process</button> -->
					<button type="button" class="btn btn-danger" style='margin-left:5px;' name="back" id="back">Back</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style='width:40%;'>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="fa fa-users"></span>&nbsp;Detail Data</h4>
			</div>
			<div class="modal-body" id="ModalView">
				...
			</div>
		</div>
	</div>


	<script src="<?= base_url('assets/js/jquery.maskMoney.js') ?>"></script>
	<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
	<style>
		.datepicker {
			cursor: pointer;
		}
	</style>

	<script type="text/javascript">
		//$('#input-kendaraan').hide();
		var base_url = '<?php echo base_url(); ?>';
		var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

		$(document).ready(function() {
			$('.datepicker').datepicker({
				dateFormat: 'dd-M-yy'
			});
			$('.autoNumeric5').autoNumeric('init', {
				mDec: '5',
				aPad: false
			})
			$('.chosen-select').select2()

			//back
			$(document).on('click', '#back', function() {
				var so_number = $("#so_number").val();
				window.location.href = base_url + active_controller + '/material_planning/' + so_number
			});

			$(document).on('keyup', '.use_stock', function() {
				let getHTML = $(this).parent().parent()

				let qty_order = getNum(getHTML.find('.qty_order').text().split(",").join(""))
				let stock_free = getNum(getHTML.find('.stock_free').text().split(",").join(""))
				let use_stock = getNum($(this).val().split(",").join(""))

				if (use_stock > qty_order) {
					use_stock = qty_order
					$(this).val(use_stock)
				}

				if (use_stock > stock_free) {
					use_stock = stock_free
					$(this).val(use_stock)
				}

				let sisa_free = stock_free - use_stock
				let min_stok = getNum(getHTML.find('.min_stok').text().split(",").join(""))
				let max_stok = getNum(getHTML.find('.max_stok').text().split(",").join(""))

				getHTML.find('.sisa_free').text(number_format(sisa_free, 5))

				let propose = 0
				if (stock_free < min_stok) {
					propose = (min_stok - sisa_free) + (max_stok - min_stok);
				}

				getHTML.find('.propose').val(number_format(propose, 2))
			});

			$('#save').click(function(e) {
				e.preventDefault();
				var tgl_dibutuhkan = $("#tgl_dibutuhkan").val();

				if (tgl_dibutuhkan == '') {
					swal({
						title: "Error Message!",
						text: 'Tanggal Dibutuhkan masih kosong ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}

				swal({
						title: "Are you sure?",
						text: "You will not be able to process again this data!",
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
							var formData = new FormData($('#data-form')[0]);
							var baseurl = siteurl + active_controller + '/material_planning';
							$.ajax({
								url: baseurl,
								type: "POST",
								data: formData,
								cache: false,
								dataType: 'json',
								processData: false,
								contentType: false,
								success: function(data) {
									if (data.status == 1) {
										swal({
											title: "Save Success!",
											text: data.pesan,
											type: "success",
											timer: 7000
										});
										window.location.href = base_url + active_controller
									} else {
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

		});

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

		$(document).on('click', '.add_tgl', function(e) {
			var id = $(this).data('id');
			var so = $(this).data('so');
			// console.log(id);
			// return;
			$("#head_title").html("<b>Add Tanggal</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + active_controller + '/add_tgl/' + id + '/' + so,
				success: function(data) {
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});
	</script>

<script>
let tglIndex = 0;

function addTgl() {
  tglIndex++;
  const table = document.getElementById("planningTable");
  const headerRow = document.getElementById("mainHeader");
  const bodyRows = table.getElementsByTagName("tbody")[0].rows;

  // Tambahkan header kolom baru dengan tombol delete
  const th = document.createElement("th");
  th.className = "tgl-group";
  th.innerHTML = 'Tgl ' + tglIndex + '<br>' +
    '<button type="button" class="del-btn" onclick="deleteTgl(this)">x</button>';
  headerRow.appendChild(th);

  // Tambahkan kolom untuk tiap baris
  for (let rowIndex = 0; rowIndex < bodyRows.length; rowIndex++) {
    const key = bodyRows[rowIndex].getAttribute('data-key');
    const td = document.createElement("td");
    td.setAttribute("align", "center");
    td.innerHTML = "<input type='text' class='form-control input-sm text-right autoNumeric5' " +
      "style='width: 120px;' name='detail[" + key + "][tgl_" + tglIndex + "][]' value=''>";
    bodyRows[rowIndex].appendChild(td);
  }
}

function deleteTgl(btn) {
  const th = btn.parentNode;
  const colIndex = Array.prototype.indexOf.call(th.parentNode.children, th);

  const table = document.getElementById("planningTable");
  const rows = table.rows;

  // Hapus kolom dari semua baris
  for (let i = 0; i < rows.length; i++) {
    if (rows[i].cells.length > colIndex) {
      rows[i].deleteCell(colIndex);
    }
  }
}

$(document).on('click', '.add_tgl_plan', function(e) {
	var id = $(this).data('id');
	var so = $(this).data('so');
	// console.log(id + '||' + so);
	// return;
	$("#head_title").html("<b>Detail Plan Tanggal</b>");
	$.ajax({
		type: 'POST',
		url: siteurl + active_controller + '/add_plan_date/' + id + '/' + so,
		success: function(data) {
			$("#dialog-popup").modal();
			$("#ModalView").html(data);

		}
	})
});

$(document).on('click', '.edit_tgl_plan', function(e) {
	var id = $(this).data('id');
	var so = $(this).data('so');
	var id_plan_detail = $(this).data('id_plan_detail');
	var id_material = $(this).data('id_material');
	// console.log(id + '||' + so);
	// return;
	$("#head_title").html("<b>Detail Plan Tanggal</b>");
	$.ajax({
		type: 'POST',
		url: siteurl + active_controller + '/add_plan_date/' + id + '/' + so + '/' + id_plan_detail + '/' + id_material,
		success: function(data) {
			$("#dialog-popup").modal();
			$("#ModalView").html(data);

		}
	})
});

$(document).on('submit', '#data_form', function(e) {
	e.preventDefault()
	var data = $('#data_form').serialize();
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
				url: siteurl + active_controller + '/add_plan_date',
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
		$(document).on('click', '.delete_tgl_plan', function(e) {
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
						url: siteurl + active_controller + '/delete_date_plan',
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

</script>
