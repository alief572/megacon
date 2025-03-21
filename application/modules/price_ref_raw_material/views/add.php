<?php
$id 		= (!empty($listData[0]->id)) ? $listData[0]->id : '';
$code_lv4 	= (!empty($listData[0]->code_lv4)) ? $listData[0]->code_lv4 : '';
$nama 		= (!empty($listData[0]->nama)) ? $listData[0]->nama : '';
$code 		= (!empty($listData[0]->code)) ? $listData[0]->code : '';
$status_app = (!empty($listData[0]->status_app)) ? $listData[0]->status_app : '';

$price_ref 				= (!empty($listData[0]->price_ref)) ? $listData[0]->price_ref : 0;
$price_ref_high 		= (!empty($listData[0]->price_ref_high)) ? $listData[0]->price_ref_high : 0;
$price_ref_new 			= (!empty($listData[0]->price_ref_new)) ? $listData[0]->price_ref_new : 0;
$price_ref_high_new 	= (!empty($listData[0]->price_ref_high_new)) ? $listData[0]->price_ref_high_new : 0;
$price_ref_use 			= (!empty($listData[0]->price_ref_use)) ? $listData[0]->price_ref_use : 0;

$price_ref_usd 				= (!empty($listData[0]->price_ref_usd)) ? $listData[0]->price_ref_usd : 0;
$price_ref_high_usd 		= (!empty($listData[0]->price_ref_high_usd)) ? $listData[0]->price_ref_high_usd : 0;
$price_ref_new_usd 			= (!empty($listData[0]->price_ref_new_usd)) ? $listData[0]->price_ref_new_usd : 0;
$price_ref_high_new_usd 	= (!empty($listData[0]->price_ref_high_new_usd)) ? $listData[0]->price_ref_high_new_usd : 0;
$price_ref_use_usd 			= (!empty($listData[0]->price_ref_use_usd)) ? $listData[0]->price_ref_use_usd : 0;

$price_ref_date 		= (!empty($listData[0]->price_ref_date)) ? $listData[0]->price_ref_date : 0;
$price_ref_new_date 	= (!empty($listData[0]->price_ref_new_date)) ? $listData[0]->price_ref_new_date : 0;
$price_ref_date_use 	= (!empty($listData[0]->price_ref_date_use)) ? $listData[0]->price_ref_date_use : 0;

$price_ref_expired 		= (!empty($listData[0]->price_ref_expired)) ? $listData[0]->price_ref_expired : 0;
$price_ref_new_expired 	= (!empty($listData[0]->price_ref_new_expired)) ? $listData[0]->price_ref_new_expired : 0;
$price_ref_expired_use 	= (!empty($listData[0]->price_ref_expired_use)) ? $listData[0]->price_ref_expired_use : 0;

$decision = (!empty($listData[0]->decision)) ? $listData[0]->decision : '';
$up_to_persen = (!empty($listData[0]->up_to_persen)) ? $listData[0]->up_to_persen : 0;
$up_to_value = (!empty($listData[0]->up_to_value)) ? $listData[0]->up_to_value : 0;
$up_to_value_usd = (!empty($listData[0]->up_to_value_usd)) ? $listData[0]->up_to_value_usd : 0;

$kurs 	= (!empty($listData[0]->kurs)) ? $listData[0]->kurs : 0;

$tgl_expired 		= '';
$tgl_expired_new 	= '';
$tgl_expired_use 	= '';

if ($price_ref_date != 0) {
	$tgl_expired 		= date('d-M-Y', strtotime('+' . $price_ref_expired . ' month', strtotime($price_ref_date)));
}
if ($price_ref_new_date != 0) {
	$tgl_expired_new 	= date('d-M-Y', strtotime('+' . $price_ref_new_expired . ' month', strtotime($price_ref_new_date)));
}
if ($price_ref_date_use != 0) {
	$tgl_expired_use 	= date('d-M-Y', strtotime('+' . $price_ref_expired_use . ' month', strtotime($price_ref_date_use)));
}

$note 			= (!empty($listData[0]->note)) ? $listData[0]->note : '';

$expired1 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '1') ? 'selected' : '';
$expired3 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '3') ? 'selected' : '';
$expired6 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '6') ? 'selected' : '';
$expired12 = (!empty($listData[0]->price_ref_new_expired) and $listData[0]->price_ref_new_expired == '12') ? 'selected' : '';

?>
<div class="box box-primary">
	<div class="box-body">
		<form id="data_form" method="post" autocomplete="off" enctype='multiple/form-data'>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Material ID</label>
				</div>
				<div class="col-md-10">
					<input type="text" class="form-control" id="code" required name="code" placeholder="Material ID" value='<?= $code; ?>' readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label for="">Material Master</label>
				</div>
				<div class="col-md-10">
					<input type="hidden" class="form-control" id="id" name="id" value='<?= $id; ?>'>
					<input type="hidden" class="form-control" id="code_lv4" name="code_lv4" value='<?= $code_lv4; ?>'>
					<input type="text" class="form-control" id="nama" required name="nama" placeholder="Material Type" value='<?= $nama; ?>' readonly>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">

				</div>
				<div class="col-md-5">
					<span class='text-red text-bold'>Lower Price</span>
				</div>
				<div class="col-md-5">
					<span class='text-green text-bold'>Higher Price</span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Before</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref" name="price_ref" value='<?= $price_ref; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_usd" name="price_ref_usd" value='<?= $price_ref_usd; ?>' readonly>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_high" name="price_ref_high" value='<?= $price_ref_high; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_usd" name="price_ref_high_usd" value='<?= $price_ref_high_usd; ?>' readonly>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>After</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_new" name="price_ref_new" value='<?= $price_ref_new; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_new_usd" name="price_ref_new_usd" value='<?= $price_ref_new_usd; ?>' readonly>
					</div>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_high_new" name="price_ref_high_new" value='<?= $price_ref_high_new; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_high_new_usd" name="price_ref_high_new_usd" value='<?= $price_ref_high_new_usd; ?>' readonly>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">

				</div>
				<div class="col-md-5">
					<span class='text-red text-bold'>Before</span>
				</div>
				<div class="col-md-5">
					<span class='text-green text-bold'>After</span>
				</div>
			</div>
			<div class="form-group row" hidden>
				<div class="col-md-2">
					<label>Expired Purchase</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired" name="tgl_expired" value='<?= $tgl_expired; ?>' placeholder="Expired Purchase Before" readonly>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired_new" name="tgl_expired_new" value='<?= $tgl_expired_new; ?>' placeholder="Expired Purchase After" readonly>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Price Reference</label>
				</div>
				<div class="col-md-5">
					<div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_use" name="price_ref_use" value='<?= $price_ref_use; ?>' readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_usd" name="price_ref_use_usd" value='<?= $price_ref_use_usd; ?>' readonly>
					</div>
				</div>
				<div class="col-md-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">Decision</button>
						</span>
						<select name="decision" id="decision" class="form-control form-control-sm" required>
							<option value="">- Select Decision -</option>
							<option value="1">Lower Price</option>
							<option value="2">Higher Price</option>
						</select>

					</div>
					<!-- <div class='input-group'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">IDR</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric" id="price_ref_use_after" required name="price_ref_use_after" value='<?= $price_ref_high_new; ?>'>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">USD</button>
						</span>
						<input type="text" class="form-control text-center autoNumeric6" id="price_ref_use_after_usd" required name="price_ref_use_after_usd" value='<?= $price_ref_high_new_usd; ?>'>
					</div> -->
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Expired Reference</label>
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control" id="tgl_expired_use" name="tgl_expired_use" value='<?= $tgl_expired_use; ?>' placeholder="Expired Price Reference Before" readonly>
				</div>
				<div class="col-md-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">Up to (%)</button>
						</span>
						<input type="number" name="up_to" id="up_to" class="form-control form-control-sm text-right" step="0.01" >
					</div>
				</div>
				<br><br>
				<div class="col-md-2"></div>
				<div class="col-md-5">
					<select id="price_ref_expired_use_after" name="price_ref_expired_use_after" class="form-control input-md chosen-select" required>
						<option value="0">Select An Expired</option>
						<option value="1" <?= $expired1; ?>>1 Bulan</option>
						<option value="3" <?= $expired3; ?>>3 Bulan</option>
						<option value="6" <?= $expired6; ?>>Semester</option>
						<option value="12" <?= $expired12; ?>>Tahunan</option>
					</select>
				</div>
				<div class="col-md-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">Up to (IDR)</button>
						</span>
						<input type="text" name="up_to_value" id="up_to_value" class="form-control form-control-sm text-right autoNumeric" readonly>
					</div>
				</div>
				<br><br>
				<div class="col-md-2"></div>
				<div class="col-md-5"></div>
				<div class="col-md-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-flat">Up to (USD)</button>
						</span>
						<input type="text" name="up_to_value_usd" id="up_to_value_usd" class="form-control form-control-sm text-right autoNumeric" readonly>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Note</label>
				</div>
				<div class="col-md-5">
					<textarea class="form-control" id="note" name="note" row='3' placeholder="Note" readonly><?= $note; ?></textarea>
				</div>
				<div class="col-md-2">
					<label>Kurs</label>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control text-center autoNumeric" id="kurs" required name="kurs" value='<?= $kurs; ?>' readonly>
				</div>
				<div class="col-md-12"></div>
				<div class="col-md-2" style="margin-top: 1rem;">
					<label>Satuan Beli</label>
				</div>
				<div class="col-md-5" style="margin-top: 1rem;">
					<input type="text" name="" id="" class="form-control text-center" value="<?= ucfirst($satuan_beli) ?>" readonly>
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Action</label>
				</div>
				<div class="col-md-10">
					<select id="action_app" name="action_app" class="form-control input-md chosen-select" required>
						<option value="1">Approve</option>
						<option value="0">Reject</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2">
					<label>Reason</label>
				</div>
				<div class="col-md-10">
					<textarea class="form-control" id="status_reject" name="status_reject" row='3' placeholder="Reason"></textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-2"></div>
				<div class="col-md-10">
					<button type="submit" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
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
		$('.autoNumeric').autoNumeric('init', {
			mDec: '0',
			aPad: false
		});
		$(".autoNumeric6").autoNumeric('init', {
			mDec: '6',
			aPad: false
		});

		$(document).on('change', '#decision, #up_to', function() {
			var decision = $('#decision').val();
			var up_to = $('#up_to').val();
			if (up_to == '') {
				up_to = 0;
			} else {
				up_to = parseFloat(up_to);
			}

			var base_price = 0;
			var base_price_usd = 0;

			if (decision == '1') {
				var base_price = $('#price_ref_new').val();
				var base_price_usd = $('#price_ref_new_usd').val();
			} else {
				var base_price = $('#price_ref_high_new').val();
				var base_price_usd = $('#price_ref_high_new_usd').val();
			}

			if (base_price !== '') {
				base_price = base_price.split(',').join('');
				base_price = parseFloat(base_price);
			} else {
				base_price = 0;
			}

			if (base_price_usd !== '') {
				base_price_usd = base_price_usd.split(',').join('');
				base_price_usd = parseFloat(base_price_usd);
			} else {
				base_price_usd = 0;
			}

			var up_to_val = parseFloat((base_price * up_to / 100) + base_price);
			var up_to_val_usd = parseFloat((base_price_usd * up_to / 100) + base_price_usd);

			$('#up_to_value').val(up_to_val.toLocaleString('en-US'));
			$('#up_to_value_usd').val(up_to_val_usd.toLocaleString('en-US'));
		})
	});
</script>