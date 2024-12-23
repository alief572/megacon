<?php

$no_bom          = (!empty($header)) ? $header[0]->no_bom : '';
$id_product      = (!empty($header)) ? $header[0]->id_product : '';
$variant_product   	= (!empty($header)) ? $header[0]->variant_product : '';
$id_variant_product   	= (!empty($header)) ? $header[0]->id_variant_product : '';
$keterangan   		= (!empty($header)) ? $header[0]->keterangan : '';
$id_jenis_beton = (!empty($header)) ? $header[0]->id_jenis_beton : '';
$volume_m3 = (!empty($header)) ? $header[0]->volume_m3 : 0;

// print_r($header);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post"><br>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="customer">Product Master <span class='text-red'>*</span></label>
				</div>
				<div class="col-md-4">
					<input type="hidden" name="no_bom" value="<?= $no_bom; ?>">
					<select id="id_product" name="id_product" class="form-control input-md chosen_select" required>
						<option value="0">Select An Option</option>
						<?php foreach ($results['product'] as $product) {
							$sel = ($product->code_lv4 == $id_product) ? 'selected' : '';
						?>
							<option value="<?= $product->code_lv4; ?>" <?= $sel; ?> data-kode='<?= $product->code ?>'><?= strtoupper(strtolower($product->nama)) ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					<label for="">Jenis Beton</label>
				</div>
				<div class="col-md-4">
					<select name="jenis_beton" id="jenis_beton" class="form-control input-md chosen_select">
						<option value="">- Pilih Jenis Beton -</option>
						<?php
						foreach ($results['jenis_beton'] as $item) {
							$selected = '';
							if ($id_jenis_beton == $item->id_komposisi_beton) {
								$selected = 'selected';
							}

							echo '<option value="' . $item->id_komposisi_beton . '" ' . $selected . '>' . $item->nm_jenis_beton . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="customer">Varian Product</label>
				</div>
				<div class="col-md-4">
					<input type="text" name="variant_product" id="" class="form-control input-md" value="<?= $variant_product ?>" readonly>
					<input type="hidden" name="id_variant_product" value="<?= $id_variant_product ?>">
				</div>

			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="customer">Keterangan</label>
				</div>
				<div class="col-md-4">
					<textarea name="keterangan" class='form-control input-md' placeholder='Keterangan' rows='2'><?= $keterangan; ?></textarea>
				</div>
			</div>
			<br>
			<div class='box box-info'>
				<div class='box-header'>
					<h3 class='box-title'>Detail Material</h3>
					<div class='box-tool pull-right'>
						<!--<button type='button' data-id='frp_".$a."' class='btn btn-md btn-info panelSH'>SHOW</button>-->
					</div><br>
				</div>
				<div class='box-body hide_header'>
					<div class="col-md-6">
						<table class="table w-100">
							<tr>
								<th>Volume Produk (m3)</th>
								<th>:</th>
								<th>
									<input type="number" name="volume_produk" id="" class="form-control input-md text-right" step="0.01" min="0" value="<?= $volume_m3 ?>">
								</th>
							</tr>
						</table>
					</div>
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
						<thead>
							<tr class='bg-blue'>
								<th class='text-center' style='width: 4%;'>#</th>
								<th class='text-center' style='width: 40%;'>Material Name</th>
								<th class="text-center">Volume (m3)</th>
							</tr>
						</thead>
						<tbody id='body_table'>
							<?php
							$no = 1;
							if (!empty($detail)) {
								foreach ($detail as $item) {
									echo '<tr>';

									echo '<td class="text-center">';
									echo $no;
									echo '<input type="hidden" name="detail_material[' . $no . '][id_detail_material]" value="' . $item->code_material . '">';
									echo '</td>';

									echo '<td class="text-left">' . $item->nm_material . '</td>';
									echo '<td class="text-center">';
									echo number_format($item->volume_m3, 4);
									echo '<input type="hidden" name="detail_material[' . $no . '][volume_material]" value="' . $item->volume_m3 . '">';
									echo '</td>';

									echo '</tr>';

									$no++;
								}
							}
							?>
						</tbody>
					</table>
					<br>
				</div>
			</div>

			<div class="box box-info">
				<div class='box-header'>
					<h3 class='box-title'>Material Lainnya</h3>
					<div class='box-tool pull-right'>
						<!--<button type='button' data-id='frp_".$a."' class='btn btn-md btn-info panelSH'>SHOW</button>-->
					</div><br>
				</div>
				<div class="box-body hide_header">
					<div class="form-group row">
						<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>
							<thead>
								<tr class='bg-blue'>
									<th class='text-center' style='width: 4%;'>#</th>
									<th class='text-center' style='width: 40%;'>Material Name</th>
									<th class="text-center">Kebutuhan</th>
									<th class="text-center">Satuan</th>
									<th class="text-center">Keterangan</th>
									<th class="text-center">#</th>
								</tr>
							</thead>
							<tbody id='body_table_material_lain'>
								<?php
								$no_material_lain = 1;
								foreach ($detail_material_lain as $item) {
									echo '<tr class="row_material_lain_' . $no_material_lain . '">';

									echo '<td class="text-center">' . $no_material_lain . '</td>';

									echo '<td class="text-left">';
									echo '<select class="form-control form-control-sm chosen_select id_material" name="detail_material_lain[' . $no_material_lain . '][id_material]" data-no="' . $no_material_lain . '">';
									echo '<option value="">- Select Material -</option>';

									foreach ($list_material as $item_material) {
										$selected = '';
										if ($item_material->code_lv4 == $item->id_material) {
											$selected = 'selected';
										}
										echo '<option value="' . $item_material->code_lv4 . '" ' . $selected . '>' . $item_material->nama . '</option>';
									}
									echo '</select>';
									echo '<input type="hidden" class="form-control input-md" name="detail_material_lain[' . $no_material_lain . '][material_name]">';
									echo '</td>';

									echo '<td class="text-left">';
									echo '<input type="number" class="form-control input-md" name="detail_material_lain[' . $no_material_lain . '][kebutuhan]" step="0.0001" value="' . $item->kebutuhan . '">';
									echo '</td>';

									echo '<td>';
									echo '<input type="text" class="form-control form-control-sm" name="detail_material_lain[' . $no_material_lain . '][satuan]" value="' . ucfirst($item->nm_satuan) . '" readonly>';
									echo '<input type="hidden" name="detail_material_lain[' . $no_material_lain . '][id_satuan]" value="' . $item->id_satuan . '">';
									echo '</td>';

									echo '<td>';
									echo '<textarea class="form-control form-control-sm" name="detail_material_lain[' . $no_material_lain . '][keterangan]">' . $item->keterangan . '</textarea>';
									echo '</td>';

									echo '<td class="text-center">';
									echo '<button type="button" class="btn btn-sm btn-danger del_material_lain" data-no="' . $no_material_lain . '" title="Delete"><i class="fa fa-trash"></i></button>';
									echo '</td>';

									echo '</tr>';

									$no_material_lain++;
								}
								?>
							</tbody>
							<tbody>
								<tr>
									<td colspan="6">
										<button type="button" class="btn btn-sm btn-warning add_material_lain">
											<i class="fa fa-plus"></i> Add Material
										</button>
									</td>
								</tr>
							</tbody>
						</table>

						<button type="button" class="btn btn-danger" style='float:right; margin-left:5px;' name="back" id="back"><i class="fa fa-reply"></i> Back</button>
						<button type="submit" class="btn btn-primary" style='float:right;' name="save" id="save"><i class="fa fa-save"></i> Save</button>
					</div>
				</div>
		</form>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<style media="screen">
	.datepicker {
		cursor: pointer;
		padding-left: 12px;
	}

	.font20 {
		font-size: 16px;
	}
</style>
<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url = '<?php echo base_url(); ?>';
	var active_controller = '<?php echo ($this->uri->segment(1)); ?>';

	var no_material_lain = <?= $no_material_lain ?>;

	$(document).ready(function() {
		chosen_select();
		$(".datepicker").datepicker();
		$(".autoNumeric4").autoNumeric('init', {
			mDec: '4',
			aPad: false
		});

		//add part
		$(document).on('click', '.addPart', function() {
			// loading_spinner();
			var get_id = $(this).parent().parent().attr('id');
			// console.log(get_id);
			var split_id = get_id.split('_');
			var id = parseInt(split_id[1]) + 1;
			var id_bef = split_id[1];

			$.ajax({
				url: base_url + active_controller + '/get_add/' + id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#add_" + id_bef).before(data.header);
					$("#add_" + id_bef).remove();
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.autoNumeric4').autoNumeric('init', {
						mDec: '4',
						aPad: false
					});
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
				}
			});
		});

		$(document).on('change', '#id_product', function() {
			var id_product = $(this).val();

			$.ajax({
				type: 'post',
				url: siteurl + active_controller + '/get_varian_product',
				data: {
					'id_product': id_product
				},
				dataType: 'json',
				cache: false,
				success: function(result) {
					$('input[name="variant_product"]').val(result.nm_variant_product);
					$('input[name="id_variant_product"]').val(result.id_variant_product);
				},
				error: function(result) {
					swal({
						type: 'error',
						title: 'Error !',
						text: 'Please try again later !'
					});
				}
			});
		});

		$(document).on('click', '#updateManualCode', function() {
			var id_product = ($("#id_product").val() != '0') ? $("#id_product").find(':selected').data('kode') : '';
			var variant_product = ($("#variant_product").val() != '0') ? $("#variant_product").find(':selected').data('kode') : '';
			var color_product = ($("#color_product").val() != '0') ? $("#color_product").find(':selected').data('kode') : '';

			var newKode = id_product + '-' + variant_product + '-' + color_product;
			$('#kode').val(newKode)
		});

		//delete part
		$(document).on('click', '.delPart', function() {
			var get_id = $(this).parent().parent().attr('class');
			$("." + get_id).remove();
			sumMaterial()
		});

		//add part
		$(document).on('click', '#back', function() {
			window.location.href = base_url + active_controller;
		});

		$(document).on('keyup', '.qty, #waste_setting_resin, #waste_setting_glass, #moq, #waste_product', function() {
			let waste_product = getNum($('#waste_product').val().split(',').join(''))
			let waste_resin = getNum($('#waste_setting_resin').val().split(',').join(''))
			let waste_glass = getNum($('#waste_setting_glass').val().split(',').join(''))
			let moq = getNum($('#moq').val().split(',').join(''))

			let SumTotal = 0
			let qty
			$('.qty').each(function() {
				qty = getNum($(this).val().split(',').join(''))
				SumTotal += qty
			})

			// console.log(SumTotal)
			$('#total_material').text(number_format(SumTotal, 4))
			$('#tot_material').val(SumTotal)

			let waste_total = (((waste_resin + waste_glass + (waste_product * moq))) / ((SumTotal + waste_product) * moq + (waste_resin + waste_glass))) * 100
			$('#waste_total').val(number_format(waste_total, 4))

		});

		$(document).on('click', '#copyBOM', function() {
			var id = $('#bom_standard_list').val();

			$.ajax({
				url: base_url + active_controller + '/get_add_copy/' + id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#body_table").html(data.header);
					$('.chosen_select').select2({
						width: '100%'
					});
					$('.autoNumeric4').autoNumeric('init', {
						mDec: '4',
						aPad: false
					});

					sumMaterial()
					swal.close();
				},
				error: function() {
					swal({
						title: "Error Message !",
						text: 'Connection Time Out. Please try again..',
						type: "warning",
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
				}
			});
		});

		$('#save').click(function(e) {
			e.preventDefault();
			var id_product = $('#id_product').val();
			var material = $('.material').val();
			var qty = $('.qty').val();

			if (id_product == '0') {
				swal({
					title: "Error Message!",
					text: 'Product name empty, select first ...',
					type: "warning"
				});

				$('#save').prop('disabled', false);
				return false;
			}
			if (material == '0') {
				swal({
					title: "Error Message!",
					text: 'Material name empty, select first ...',
					type: "warning"
				});

				$('#save').prop('disabled', false);
				return false;
			}
			if (qty == '') {
				swal({
					title: "Error Message!",
					text: 'Weight empty, select first ...',
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
						var baseurl = base_url + active_controller + '/add'
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
										timer: 3000,
										showCancelButton: false,
										showConfirmButton: false,
										allowOutsideClick: false
									});
									window.location.href = base_url + active_controller;
								} else {

									if (data.status == 2) {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
											showCancelButton: false,
											showConfirmButton: false,
											allowOutsideClick: false
										});
									} else {
										swal({
											title: "Save Failed!",
											text: data.pesan,
											type: "warning",
											timer: 3000,
											showCancelButton: false,
											showConfirmButton: false,
											allowOutsideClick: false
										});
									}

								}
							},
							error: function() {

								swal({
									title: "Error Message !",
									text: 'An Error Occured During Process. Please try again..',
									type: "warning",
									timer: 7000,
									showCancelButton: false,
									showConfirmButton: false,
									allowOutsideClick: false
								});
							}
						});
					} else {
						swal("Cancelled", "Data can be process again :)", "error");
						return false;
					}
				});
		});

		$(document).on('change', '#jenis_beton, input[name="volume_produk"]', function() {
			var jenis_beton = $('#jenis_beton').val();
			var volume_produk = $('input[name="volume_produk"]').val();
			if (volume_produk == '') {
				volume_produk = 0;
			}

			$.ajax({
				type: 'post',
				url: siteurl + active_controller + '/get_detail_material',
				data: {
					'jenis_beton': jenis_beton,
					'volume_produk': volume_produk
				},
				dataType: 'json',
				cache: false,
				success: function(result) {
					$('#body_table').html(result.hasil);
				},
				error: function(result) {
					swal({
						type: 'error',
						title: 'Error !',
						text: 'Please try again later !'
					});
				}
			});
		});

		$(document).on('click', '.add_material_lain', function() {
			var hasil = '<tr class="row_material_lain_' + no_material_lain + '">';

			hasil += '<td class="text-center">';
			hasil += no_material_lain;
			hasil += '</td>';

			hasil += '<td class="text-left">';
			hasil += '<select class="form-control form-control-sm chosen_select id_material" name="detail_material_lain[' + no_material_lain + '][id_material]" data-no="' + no_material_lain + '">';
			hasil += '<option value="">- Select Material -</option>';
			<?php
			foreach ($list_material as $item_material) {
			?>

				hasil += '<option value="<?= $item_material->code_lv4 ?>"><?= $item_material->nama ?></option>';

			<?php
			}
			?>
			hasil += '</select>';
			hasil += '<input type="hidden" class="form-control input-md" name="detail_material_lain[' + no_material_lain + '][material_name]">';
			hasil += '</td>';

			hasil += '<td class="text-left">';
			hasil += '<input type="number" class="form-control input-md" name="detail_material_lain[' + no_material_lain + '][kebutuhan]" step="0.0001">'
			hasil += '</td>';

			hasil += '<td>';
			hasil += '<input type="text" class="form-control form-control-sm" name="detail_material_lain[' + no_material_lain + '][satuan]" readonly>';
			hasil += '<input type="hidden" name="detail_material_lain[' + no_material_lain + '][id_satuan]">';
			hasil += '</td>';

			hasil += '<td class="text-left">';
			hasil += '<textarea name="detail_material_lain[' + no_material_lain + '][keterangan]"></textarea>'
			hasil += '</td>';

			hasil += '<td class="text-center">';
			hasil += '<button type="button" class="btn btn-sm btn-danger del_material_lain" data-no="' + no_material_lain + '" title="Delete"><i class="fa fa-trash"></i></button>';
			hasil += '</td>';

			hasil += '</tr>';

			no_material_lain++;

			$('#body_table_material_lain').append(hasil);

			$('.chosen_select').chosen();
		});

		$(document).on('change', '.id_material', function() {
			var id_material = $(this).val();
			var no = $(this).data('no');

			$.ajax({
				type: 'post',
				url: siteurl + active_controller + '/get_nm_material_lain',
				data: {
					'id_material': id_material
				},
				dataType: 'json',
				cache: false,
				success: function(result) {
					$('input[name="detail_material_lain[' + no + '][material_name]"]').val(result.nm_material);
					$('input[name="detail_material_lain[' + no + '][satuan]"]').val(result.satuan);
					$('input[name="detail_material_lain[' + no + '][id_satuan]"]').val(result.id_satuan);
				},
				error: function(result) {

				}
			});
		});

		function sumMaterial() {
			let SumTotal = 0
			let qty
			$('.qty').each(function() {
				qty = getNum($(this).val().split(',').join(''))
				SumTotal += qty
			})

			// console.log(SumTotal)
			$('#total_material').text(number_format(SumTotal, 4))
			$('#tot_material').val(SumTotal)
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

		function chosen_select() {
			$('.chosen_select').chosen({
				width: '100%'
			});
		};

	});
</script>