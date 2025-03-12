<?php
$id = (!empty($listData[0]->id)) ? $listData[0]->id : '';
$kd_mesin = (!empty($listData[0]->kd_mesin)) ? $listData[0]->kd_mesin : '';
$nm_mesin = (!empty($listData[0]->nm_mesin)) ? $listData[0]->nm_mesin : '';
$harga_mesin = (!empty($listData[0]->harga_mesin)) ? $listData[0]->harga_mesin : 0;
$depresiasi = (!empty($listData[0]->depresiasi)) ? $listData[0]->depresiasi : 0;
$depresiasi_per_tahun = (!empty($listData[0]->depresiasi_per_tahun)) ? $listData[0]->depresiasi_per_tahun : 0;
$utilisasi_hari = (!empty($listData[0]->utilisasi_hari)) ? $listData[0]->utilisasi_hari : 0;
$utilisasi_m3_per_hari = (!empty($listData[0]->utilisasi_m3_per_hari)) ? $listData[0]->utilisasi_m3_per_hari : 0;
$cost_m3 = (!empty($listData[0]->cost_m3)) ? $listData[0]->cost_m3 : 0;
?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" method="post" autocomplete="off" enctype='multiple/form-data'>
			<input type="hidden" name="id" value="<?= $id ?>">
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Machine Name <span class='text-danger'>*</span></label>
				</div>
				<div class="col-md-10">
					<select name="kd_mesin" id="kd_mesin" class='chosen-select'>
						<option value="">- Select Machine -</option>
						<?php
						foreach ($list_asset as $item) {
							$selected = '';
							if ($item['kd_asset'] == $kd_mesin) {
								$selected = 'selected';
							}
							echo '<option value="' . $item['kd_asset'] . '" ' . $selected . '>' . $item['nm_asset'] . '</option>';
						}
						?>
					</select>
				</div>

				<div class="col-md-12">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Asset Mesin</th>
								<th class="text-center">Harga</th>
								<th class="text-center">Depresiasi (Tahun)</th>
								<th class="text-center">Depresiasi Per Tahun</th>
								<th class="text-center">Utilisasi (Hari)</th>
								<th class="text-center">Utilisasi (m3 / hari)</th>
								<th class="text-center">Cost / m3</th>
							</tr>
						</thead>
						<tbody id="list_mesin">
							<?php
							if (isset($listData) && !empty($listData)) {
								echo '<tr>';

								echo '<td>' . $nm_mesin . '</td>';

								echo '<td>';
								echo '<input type="text" class="form-control input-md text-right" name="harga" value="' . number_format($harga_mesin) . '" readonly>';
								echo '</td>';

								echo '<td>';
								echo '<input type="text" name="depresiasi" class="form-control input-md text-right" value="' . number_format($depresiasi) . '" readonly>';
								echo '</td>';

								echo '<td>';
								echo '<input type="text" name="depresiasi_per_tahun" class="form-control input-md text-right" value="' . number_format($depresiasi_per_tahun) . '" readonly>';
								echo '</td>';

								echo '<td>';
								echo '<input type="number" class="form-control input-md text-right" name="utilisasi_hari" min="1" value="' . $utilisasi_hari . '" onkeyup="hitung_cost_m3();">';
								echo '</td>';

								echo '<td>';
								echo '<input type="text" class="form-control input-md text-right maskM" name="utilisasi_m3_per_hari" value="' . $utilisasi_m3_per_hari . '" onkeyup="hitung_cost_m3();">';
								echo '</td>';

								echo '<td>';
								echo '<input type="text" class="form-control input-md text-right maskM" name="cost_m3" value="' . $cost_m3 . '" readonly>';
								echo '</td>';

								echo '</tr>';
							}
							?>
						</tbody>
					</table>

					<br><br>

					<div class="form-group row">
						<div class="col-md-2"></div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.chosen-select').select2({
			width: '100%'
		});
		$('.maskM').autoNumeric();

		$(document).on('change', '#kd_mesin', function() {
			var kd_mesin = $(this).val();

			$.ajax({
				type: 'post',
				url: siteurl + active_controller + 'get_asset_mesin',
				data: {
					'kd_mesin': kd_mesin
				},
				dataType: 'json',
				cache: false,
				success: function(result) {
					$('#list_mesin').html(result.hasil);

					$('.maskM').autoNumeric();
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


	});
</script>