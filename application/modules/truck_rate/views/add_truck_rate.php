<?php
$id = (!empty($header[0]->id_truck_rate)) ? $header[0]->id_truck_rate : '';
$kendaraan = (!empty($header[0]->kd_asset)) ? $header[0]->kd_asset : '';
$maksimal_muatan = (!empty($header[0]->maksimal_muatan)) ? $header[0]->maksimal_muatan : '';
$bahan_bakar = (!empty($header[0]->bahan_bakar)) ? $header[0]->bahan_bakar : '';
$konsumsi_bahan_bakar = (!empty($header[0]->konsumsi_bahan_bakar)) ? $header[0]->konsumsi_bahan_bakar : '';
$rate_truck = (!empty($header[0]->rate_truck)) ? $header[0]->rate_truck : '';
// $tanda = (!empty($tandas)) ? $tandas : '';
$tandas = $this->uri->segment(4);
// echo $tandas;
// die();
?>
<!-- <?php echo ($tandas == 0) ? 'disabled-div' : ''; ?> -->
<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
			<input type="hidden" name='id_truck' id='id_truck' value='<?= $id; ?>'>
			<input type="hidden" name='tanda' id='tanda' value='<?= @$tandas; ?>'>
			<!-- <input type="hidden" name="tingkat_approval" id="tingkat_approval" value="<?= $tingkat_approval ?>"> -->
			<div class="form-group row">
				<div class="col-md-12">
					<table class='table' width='70%'>
						<tr>
							<td width='10%'>Kendaaraan</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<select name='kd_asset' id='kd_asset' class='form-control input-md'>
									<option value='0'>Select Kendaraan</option>
									<?php
									foreach ($data_asset as $val => $valx) {
										$selected = ($valx['kd_asset'] == $kendaraan) ? 'selected' : '';
										echo "<option value='" . $valx['kd_asset'] . "' " . $selected . ">" . strtoupper($valx['kd_asset']) . " - " . $valx['nm_asset'] . "</option>";
									}
									?>
								</select>
							</td>
							<td width='10%'></td>
							<td width='10%'>Maksimal Muatan</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<?php
								echo form_input(array('id' => 'maksimal_muatan', 'name' => 'maksimal_muatan', 'class' => 'form-control input-md numberOnly', 'placeholder' => 'Maksimal Muatan'), $maksimal_muatan);
								?>
							</td>
						</tr>
						<tr>
							<td width='10%'>Bahan Bakar</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<?php
								echo form_input(array('id' => 'bahan_bakar', 'name' => 'bahan_bakar', 'class' => 'form-control input-md numberOnly', 'placeholder' => 'Bahan Bakar'), $bahan_bakar);
								?>
							</td>
							<td width='10%'></td>
							<td width='10%'>Konsumsi Bahan Bakar</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<?php
								echo form_input(array('id' => 'konsumsi_bahan_bakar', 'name' => 'konsumsi_bahan_bakar', 'class' => 'form-control input-md numberOnly', 'placeholder' => 'Konsumsi Bahan Bakar'), $konsumsi_bahan_bakar);
								?>
							</td>
						</tr>
						<tr>
							<td width='10%'>Rate Truck</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<?php
								echo form_input(array('id' => 'rate_truck', 'name' => 'rate_truck', 'class' => 'form-control input-md numberOnly', 'placeholder' => 'Rate Truck', 'readonly' => 'readonly'), $rate_truck);
								?>
							</td>
							<td width='10%'></td>
							<!-- <td width='10%'>Konsumsi Bahan Bakar</td>
							<td width='1%'>:</td>
							<td width='29%'>
								<?php
								echo form_input(array('id' => 'konsumsi_bahan_bakar', 'name' => 'konsumsi_bahan_bakar', 'class' => 'form-control input-md numberOnly', 'placeholder' => 'Konsumsi Bahan Bakar'), $konsumsi_bahan_bakar);
								?>
							</td> -->
						</tr>
					</table>
				</div>
				<!-- <div class="col-md-12">
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead class='thead'>
							<tr class='bg-blue'>
								<th class='text-center th'><input type="checkbox" name="chk_all" id="chk_all"></th>
								<th class='text-center th'>Material Name</th>
								<th class='text-center th'>Min Stock</th>
								<th class='text-center th'>Max Stock</th>
								<th class='text-center th'>Min Order</th>
								<th class='text-center th'>Qty PR</th>
								<th class='text-center th'>Note</th>
								<th class='text-center th'>Qty Rev</th>
								<th class='text-center th'>#</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($detail as $key => $value) {
								$key++;
								$nm_material 	= $value['nm_material'];
								$stock_free 	= $value['stock_free'];
								$use_stock 		= $value['use_stock'];
								$sisa_free 		= $stock_free - $use_stock;
								$propose 		= $value['propose_purchase'];

								echo "<tr>";
								if ($value['status_app'] == 'N') {
									echo "<td class='text-center'><input type='checkbox' name='check[" . $value['id'] . "]' class='chk_personal' value='" . $value['id'] . "'></td>";
								} else {
									echo "<td></td>";
								}
								echo "<td class='text-left'>" . $nm_material . "
										<input type='hidden' name='detail[" . $key . "][id]' value='" . $value['id'] . "'>
										</td>";
								echo "<td class='text-right min_stok'>" . number_format($value['min_stok'], 2) . "</td>";
								echo "<td class='text-right max_stok'>" . number_format($value['max_stok'], 2) . "</td>";
								echo "<td class='text-right min_order'>" . number_format(0, 2) . "</td>";
								echo "<td class='text-right'>" . number_format($propose, 2) . "</td>";
								echo "<td class='text-left'>" . $value['note'] . "</td>";
								if ($value['status_app'] == 'N') {
									echo "<td align='center'><input type='text' class='form-control input-sm text-center autoNumeric5 propose' style='width: 100px;' id='pr_rev_" . $value['id'] . "' name='pr_rev_" . $value['id'] . "' value='" . $propose . "'></td>";
								} else {
									echo "<td class='text-center'>" . number_format($value['propose_rev'], 2) . "</td>";
								}
								if ($value['status_app'] == 'N') {
									echo "	<td align='center'>
											<button type='button' class='btn btn-sm btn-success processSatuan' data-id=" . $value['id'] . " data-action='approve'><i class='fa fa-check'></i></button>
											<button type='button' class='btn btn-sm btn-danger processSatuan' data-id=" . $value['id'] . " data-action='reject'><i class='fa fa-times'></i></button>
										</td>";
								}
								if ($value['status_app'] == 'Y') {
									echo "<td class='text-center'><span class='badge bg-green text-bold'>Approved</span></td>";
								}
								if ($value['status_app'] == 'D') {
									echo "<td class='text-center'><span class='badge bg-red text-bold'>Rejected</span></td>";
								}
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div> -->
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					<?php
					// $tandas = $this->uri->segment(4);
					if ($tandas !== '0'): // tampilkan tombol jika BUKAN mode VIEW
					?>
						<button type="button" class="btn btn-primary" name="save" id="save">Simpan</button>
					<?php endif; ?>
					<!-- <button type="button" class="btn btn-primary" name="save2" id="save2">Approve 2</button> -->
					<!-- <button type="button" class="btn btn-danger" name="reject" id="reject">Reject</button> -->
					<button type="button" class="btn btn-danger" name="back" id="back">Back</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style='width:70%;'>
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

		textarea {
			resize: none;
		}
	</style>

	<script type="text/javascript">
		//$('#input-kendaraan').hide();
		var base_url = '<?php echo base_url(); ?>';
		var active_controller = '<?php echo ($this->uri->segment(1)); ?>';
		var tingkat_approval = $("#tingkat_approval").val();

		$(document).ready(function() {
			// function updateButtons() {
			// 	if ($('.chk_personal:checked').length === $('.chk_personal').length) {
			// 		$('#chk_all').prop('checked', true);
			// 		$('#save').show();
			// 		$('#save2').hide();
			// 	} else if ($('.chk_personal:checked').length > 0) {
			// 		$('#chk_all').prop('checked', false);
			// 		$('#save').hide();
			// 		$('#save2').show();
			// 	} else {
			// 		$('#chk_all').prop('checked', false);
			// 		$('#save').show(); // Default button 1 muncul
			// 		$('#save2').hide();
			// 	}
			// }

			// $('#chk_all').change(function() {
			// 	$('.chk_personal').prop('checked', this.checked);
			// 	updateButtons();
			// });

			// $('.chk_personal').change(function() {
			// 	updateButtons();
			// });

    		// updateButtons(); // Set default saat halaman dimuat

			$('.datepicker').datepicker({
				dateFormat: 'dd-M-yy'
			});
			$('.autoNumeric5').autoNumeric('init', {
				mDec: '5',
				aPad: false
			})
			$('.chosen-select').select2()

			// $("#chk_all").click(function() {
			// 	$('input:checkbox').not(this).prop('checked', this.checked);
			// });

			//back
			$(document).on('click', '#back', function() {
				window.location.href = base_url + active_controller
			});

			$('#save').click(function(e) {
				e.preventDefault();

				var kendaraan = $("#kd_asset").val();
				var maksimal_muatan = $("#maksimal_muatan").val();
				var bahan_bakar = $("#bahan_bakar").val();
				var konsumsi_bahan_bakar = $("#konsumsi_bahan_bakar").val();

				if (kendaraan == 0) {
					swal({
						title: "Error Message!",
						text: 'Kendaraan is empty, please input first ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}

				if (maksimal_muatan == 0) {
					swal({
						title: "Error Message!",
						text: 'Maksimal muatan is empty, please input first ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}

				if (bahan_bakar == 0) {
					swal({
						title: "Error Message!",
						text: 'Bahan bakar is empty, please input first ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}

				if (konsumsi_bahan_bakar == 0) {
					swal({
						title: "Error Message!",
						text: 'Konsumsi bahan bakar is empty, please input first ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}
				// return false;
				swal({
						title: "Are you sure?",
						text: "You will not be able to process again this datas!",
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
							var baseurl = siteurl + active_controller + '/add_truck_rate';
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
										window.location.href = base_url + active_controller;
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
	</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bahanBakarInput = document.getElementById('bahan_bakar');
    const konsumsiInput = document.getElementById('konsumsi_bahan_bakar');
    const rateTruckInput = document.getElementById('rate_truck');

    function hitungRate() {
        const bahanBakar = parseFloat(bahanBakarInput.value) || 0;
        const konsumsi = parseFloat(konsumsiInput.value) || 0;

        if (konsumsi > 0) {
            const rate = bahanBakar / konsumsi;
            rateTruckInput.value = rate.toFixed(2); // hasil 2 digit desimal
        } else {
            rateTruckInput.value = '';
        }
    }

    bahanBakarInput.addEventListener('input', hitungRate);
    konsumsiInput.addEventListener('input', hitungRate);
});
</script>

<?php
$tandas = $this->uri->segment(4);
$mode_view = ($tandas === '0');
?>
<?php if ($mode_view): ?>
<!-- Script readonly hanya jalan saat mode view -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.box.box-primary');
    if (container) {
        const elements = container.querySelectorAll('input, select, textarea');
        elements.forEach(el => el.disabled = true);
    }
});
</script>
<?php endif; ?>