<div class="box box-primary">
	<div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
			<input type="hidden" name='so_number' value='<?= $header[0]['so_number']; ?>'>
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
								<!-- SELECT BULAN -->
								<!-- <?= (!empty($header[0]['periode_bulan'])) ? 'disabled' : '' ?> -->
								<select id="bulan" name="bulan" class="form-control" style="width: 20%;"  >
								    <?php
								    $namaBulan = [
								        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
								        4 => 'April', 5 => 'Mei', 6 => 'Juni',
								        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
								        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
								    ];
								    for ($i = 1; $i <= 12; $i++) {
								        $selected = ($header[0]['periode_bulan'] == $i || $header[0]['periode_bulan'] == sprintf('%02d', $i)) ? 'selected' : '';
								        echo "<option value=\"$i\" $selected>{$namaBulan[$i]}</option>";
								    }
								    ?>
								</select>
								<!-- SELECT TAHUN -->
								<!-- <?= (!empty($header[0]['periode_tahun'])) ? 'disabled' : '' ?> -->
								<select id="tahun" name="tahun" class="form-control" style="width: 20%;"  >
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
							<td><?= @$header[0]['nm_customer']; ?></td>
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
					<table class='table table-striped table-bordered table-hover table-condensed' width='100%' id="planningTable">
						<thead class='thead'>
							<!-- <tr class='bg-blue'>
								<th class='text-center th'>#</th>
								<th class='text-center th'>Material Name</th>
								<th class='text-center th'>Estimasi (Kg)</th>
								<th class='text-center th'>Stock Free (Kg)</th>
								<th class='text-center th'>Use Stock (Kg)</th>
								<th class='text-center th'>Sisa Stock Free (Kg)</th>
								<th class='text-center th'>Min Stock</th>
								<th class='text-center th'>Max Stock</th>
								<th class='text-center th'>PR On Progress</th>
								<th class='text-center th'>Propose Purchase</th>
								<th class='text-center th'>Keterangan</th>
							</tr> -->
							<tr class='bg-blue' id="mainHeader">
								<th class='text-center th'>#</th>
								<th class='text-center th'>Material Name</th>
								<th class='text-center th'>Estimasi (1 Bulan)</th>
								<th class='text-center th'>Stock Saat ini</th>
								<th class='text-center th'>Pemakaian Sehari</th>
								<th class='text-center th'>Sisa Kecukupan</th>
								<th class='text-center th'>Jumlah Sekali Pengiriman</th>
								<th class='text-center th'>Cycle Order</th>
								<th class='text-center th'>Option</th>
								<!-- <th class='text-center th'>Propose Purchase</th>
								<th class='text-center th'>Keterangan</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$GET_OUTSTANDING_PR = get_pr_on_progress();

							foreach ($detail as $key => $value) {
							    $key++;

							    // Ambil data material
							    $nm_material = $value['nm_material'];
							    $stock_free = !empty($GET_STOK_PUSAT[$value['id_material']]['stok']) 
							        ? (float)$GET_STOK_PUSAT[$value['id_material']]['stok'] 
							        : 0;

							    // Gunakan stok atau qty_order
							    $use_stock = !empty($value['use_stock']) 
							        ? $value['use_stock'] 
							        : $value['qty_order'];

							    // Sesuaikan use_stock jika stok kurang
							    if ($stock_free < $use_stock) {
							        $use_stock = $stock_free;
							    }

							    $use_stock_new = ($use_stock > 0) ? $use_stock : 0;
							    $sisa_free = $stock_free - $use_stock;

							    // Hitung kebutuhan pengadaan (propose)
							    $propose = 0;
							    if (empty($value['propose_purchase'])) {
							        if ($stock_free < $value['min_stok']) {
							            $propose = ($value['min_stok'] - $sisa_free) + ($value['max_stok'] - $value['min_stok']);
							        }
							    } else {
							        $propose = $value['propose_purchase'];
							    }

							    // Outstanding PR
							    $outstanding_pr = (!empty($GET_OUTSTANDING_PR[$value['id_material']]) && $GET_OUTSTANDING_PR[$value['id_material']] > 0) 
							        ? $GET_OUTSTANDING_PR[$value['id_material']] 
							        : 0;

							    // Pemakaian per hari
							    $pemakaian_sehari = !empty($value['daily_use_qty']) ? $value['daily_use_qty'] : 0;
							    $sisa_kecukupan = ($pemakaian_sehari > 0) ? $stock_free / $pemakaian_sehari : 0;
							    $sisa_kecukupan_text = !empty($value['sisa_kecukupan']) ? $value['sisa_kecukupan'] : $sisa_kecukupan;
							    $estimasi_sekali_kirim = !empty($value['estimasi_sekali_kirim']) ? $value['estimasi_sekali_kirim'] : 0;
							    $cycle_order = !empty($value['cycle_order']) ? $value['cycle_order'] : 0;

							    // Mulai generate row
							    echo "<tr data-key='{$key}' class='row-detail'>";

							    echo "<td class='text-center'>{$i}</td>";

							    echo "<td class='text-left'>{$nm_material}
							        <input type='hidden' name='detail[{$key}][id]' value='{$value['id']}'>
							        <input type='hidden' name='detail[{$key}][code_material]' value='{$value['id_material']}'>
							        <input type='hidden' name='detail[{$key}][stock_free]' value='{$stock_free}'>
							        <input type='hidden' name='detail[{$key}][min_stok]' value='{$value['min_stok']}'>
							        <input type='hidden' name='detail[{$key}][max_stok]' value='{$value['max_stok']}'>
							    </td>";

							    echo "<td class='text-right qty_order'>" . number_format($value['nominal_kg'], 5) . "
							        <input type='hidden' name='detail[{$key}][total_estimasi_material]' value='{$value['nominal_kg']}'>
							    </td>";

							    echo "<td class='text-right stock_free'>" . number_format($stock_free, 5) . "</td>";

							    echo "<td align='center'>
							        <input type='text' class='form-control input-sm text-right daily_use_qty' 
							               style='width: 120px;' 
							               name='detail[{$key}][daily_use_qty]' 
							               value='{$pemakaian_sehari}'>
							    </td>";

							    echo "<td class='text-right sisa_free'>" . number_format($sisa_kecukupan_text) . "
							        <input type='hidden' class='form-control input-sm text-right sisa_kecukupan' name='detail[{$key}][sisa_kecukupan]' value='{$sisa_kecukupan_text}'>
							    </td>";

							    echo "<td align='center'>
							        <input type='text' class='form-control input-sm text-right estimasi_sekali_kirim' 
							               style='width: 120px;' 
							               name='detail[{$key}][estimasi_sekali_kirim]' 
							               value='{$estimasi_sekali_kirim}'>
							    </td>";

							    echo "<td align='center'>
							        <input type='text' class='form-control input-sm text-right cycle_order' 
							               style='width: 120px;' 
							               name='detail[{$key}][cycle_order]' 
							               value='{$cycle_order}' 
							               readonly>
							    </td>";

							    // Link add tanggal
							    $url1 = 'mat_plan_base_on_produksi/plan_detail_tgl/' . $value['id'] . '/' . $value['so_number'];
							    $link_add_tgl = base_url($url1);
							    $type = 'view';
							    $url2 = 'mat_plan_base_on_produksi/plan_detail_tgl/' . $value['id'] . '/' . $value['so_number'].'/'.$type;
							    $link_view_tgl = base_url($url2);

							    echo "<td class='text-right'>
							    	<a class='btn btn-warning btn-sm' style='' href='{$link_view_tgl}' title='View Tanggal'>View Tanggal</a>
							        &nbsp;&nbsp;<a class='btn btn-success btn-sm' style='float:right;' href='{$link_add_tgl}' title='Add Tanggal'>Add Tanggal</a>
							    	</td>";
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
					<button type="button" class="btn btn-primary" name="save" id="save">Process</button>
					<button type="button" class="btn btn-danger" style='margin-left:5px;' name="back" id="back">Back</button>
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
	</style>


<script>
class CycleOrderRow {
    constructor(row) {
        this.row = row;
        this.pemakaianInput = row.querySelector('.daily_use_qty');
        this.jumlahInput = row.querySelector('.estimasi_sekali_kirim');
        this.cycleOutput = row.querySelector('.cycle_order');

        this.init();
    }

    init() {
        if (!this.pemakaianInput || !this.jumlahInput || !this.cycleOutput) return;

        this.jumlahInput.addEventListener('input', () => this.hitung());
        this.pemakaianInput.addEventListener('input', () => this.hitung());

        // Hitung awal jika ada nilai default
        this.hitung();
    }

    parseNumber(str) {
        if (!str) return 0;
        return parseFloat(str.replace(/[^0-9.,]/g, '').replace(/\./g, '').replace(',', '.'));
    }

    hitung() {
        const pemakaian = this.parseNumber(this.pemakaianInput.value);
        const jumlah = this.parseNumber(this.jumlahInput.value);

        // if (!isNaN(pemakaian) && pemakaian > 0 && !isNaN(jumlah)) {
        //     const cycle = jumlah / pemakaian;
        //     this.cycleOutput.value = cycle.toFixed(2);
        // } else {
        //     this.cycleOutput.value = '';
        // }
        if (!isNaN(pemakaian) && !isNaN(jumlah)) {
		    const cycle = pemakaian === 0 ? 0 : jumlah / pemakaian;
		    this.cycleOutput.value = cycle.toFixed(2);
		} else {
		    this.cycleOutput.value = '';
		}
    }
}

// Jalankan saat halaman siap
			document.addEventListener('DOMContentLoaded', function () {
			    document.querySelectorAll('.row-detail').forEach(row => {
			        new CycleOrderRow(row);
			    });
			});

</script>

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
				window.location.href = base_url + active_controller
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
				// var tgl_dibutuhkan = $("#tgl_dibutuhkan").val();
				var tgl_dibutuhkan = $("#bulan").val();
				var tgl_dibutuhkan = $("#tahun").val();

				// if (tgl_dibutuhkan == '') {
				// 	swal({
				// 		title: "Error Message!",
				// 		text: 'Tanggal Dibutuhkan masih kosong ...',
				// 		type: "warning"
				// 	});
				// 	$('#save').prop('disabled', false);
				// 	return false;
				// }

				if (bulan == '') {
					swal({
						title: "Error Message!",
						text: 'Bulan masih kosong ...',
						type: "warning"
					});
					$('#save').prop('disabled', false);
					return false;
				}

				if (tahun == '') {
					swal({
						title: "Error Message!",
						text: 'Tahun masih kosong ...',
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

			// Jalankan saat halaman siap
			document.addEventListener('DOMContentLoaded', function () {
			    document.querySelectorAll('.row-detail').forEach(row => {
			        new CycleOrderRow(row);
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

		$(document).on('click', '.edit_tgl', function(e) {
			var id = $(this).data('id');
			var so = $(this).data('so');
			// console.log(id + '||' + so);
			// return;
			$("#head_title").html("<b>Detail Plan Tanggal</b>");
			$.ajax({
				type: 'POST',
				url: siteurl + active_controller + '/plan_detail_tgl/' + id + '/' + so,
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

</script>

