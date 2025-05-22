<?php
// print_r($DataPlan_detail);
// die();
?>
<div class="box box-primary">
    <div class="box-body">
		<form id="data-form" method="post" autocomplete="off"><br>
		<div class="form-group row">
        	<div class="col-md-12">
				<table width='50%'>
					<tr>
						<td width='20%'>Periode Planning</td>
						<td width='1%'>:</td>
						<td>
							<div style="display: flex; gap: 10px;">
							<!-- <input type="month" id="bulanTahun" name="bulanTahun"> -->
							<select id="bulan" name="bulan" class="form-control" style="width: 20%;" <?= (@$DataPlan->periode_bulan != '') ? 'disabled' : '' ?> >
							    <option value="01" <?= (@$DataPlan->periode_bulan == '1' || @$DataPlan->periode_bulan == '01') ? 'selected' : '' ?>>Januari</option>
							    <option value="02" <?= (@$DataPlan->periode_bulan == '2') ? 'selected' : '' ?>>Februari</option>
							    <option value="03" <?= (@$DataPlan->periode_bulan == '3') ? 'selected' : '' ?>>Maret</option>
							    <option value="04" <?= (@$DataPlan->periode_bulan == '4') ? 'selected' : '' ?>>April</option>
							    <option value="05" <?= (@$DataPlan->periode_bulan == '5') ? 'selected' : '' ?>>Mei</option>
							    <option value="06" <?= (@$DataPlan->periode_bulan == '6') ? 'selected' : '' ?>>Juni</option>
							    <option value="07" <?= (@$DataPlan->periode_bulan == '7') ? 'selected' : '' ?>>Juli</option>
							    <option value="08" <?= (@$DataPlan->periode_bulan == '8') ? 'selected' : '' ?>>Agustus</option>
							    <option value="09" <?= (@$DataPlan->periode_bulan == '9') ? 'selected' : '' ?>>September</option>
							    <option value="10" <?= (@$DataPlan->periode_bulan == '10') ? 'selected' : '' ?>>Oktober</option>
							    <option value="11" <?= (@$DataPlan->periode_bulan == '11') ? 'selected' : '' ?>>November</option>
							    <option value="12" <?= (@$DataPlan->periode_bulan == '12') ? 'selected' : '' ?>>Desember</option>
							</select>
							<select id="tahun" name="tahun" class="form-control" style="width: 20%;" <?= (@$DataPlan->periode_tahun != '') ? 'disabled' : '' ?> >
							<script>
							    const tahunSelect = document.getElementById('tahun');
							    const tahunSekarang = new Date().getFullYear();
							    const selectedYear = tahunSelect.getAttribute('data-selected-year');

							    for (let i = tahunSekarang; i >= tahunSekarang - 10; i--) {
							        const option = document.createElement('option');
							        option.value = i;
							        option.textContent = i;

							        if (selectedYear == i) {
							            option.selected = true;
							        }

							        tahunSelect.appendChild(option);
							    }
							</script>
							</select>
							</div>
						</td>
					</tr>
					<!-- <tr>
						<td>Product Name</td>
						<td>:</td>
						<td><?=strtoupper($NamaProduct);?></td>
					</tr>
					<tr>
						<td>Qty</td>
						<td>:</td>
						<td><?=number_format($qty).' dari total propose '.number_format($getData[0]['propose']);?></td>
					</tr>
					<tr>
						<td>Due Date</td>
						<td>:</td>
						<td><?=date('d-M-Y',strtotime($getData[0]['due_date']));?></td>
					</tr>
					<tr>
						<td>Detail BOM</td>
						<td>:</td>
						<td><span class='text-bold text-primary detail' data-id='<?=$getData[0]['no_bom'];?>' data-category='<?=$getDataProduct[0]['category'];?>' style='cursor:pointer;'>Tampilkan BOM</span></td>
					</tr>
					<tr>
						<td class='text-bold'>Tot. Cycletime/Hour</td>
						<td class='text-bold'>:</td>
						<td class='text-bold' id='total_cycletime'></td>
					</tr> -->
				</table>
				<input type="hidden" id='cycletime' name='cycletime' value=''>
				<input type="hidden" id='propose' name='propose' value=''>
				<input type="hidden" id='id' name='id' value=''>
				<input type="hidden" id='so_number' name='so_number' value=''>
				<input type="hidden" id='due_date' name='due_date' value=''>
				<input type="hidden" id='max_date' name='max_date' value=''>
				<input type="hidden" id='rowIndex' name='rowIndex' value=''>
				<input type="hidden" id='id_planning_harian' name='id_planning_harian' value="<?= @$IdPlanningHarian ?>">
				<input type="hidden" id='kode_planning' name='kode_planning' value="<?= @$KodePlan ?>">
				<input type="hidden" id="jumlah_data_detail" name="jumlah_data_detail" value="<?= @$JmlhDataDetail ?>">
			</div>
        </div>
		<h4>Schedule Detil</h4>
		<div class="form-group row">
        	<div class="col-md-12">
				<table class="table table-bordered table-striped" width='100%' border="1"  id="myTable">
					<thead>
					<tr>
						<td colspan="4"></td>
						<td colspan="3" style="text-align: center; background-color: orange;">
							Schedule &nbsp;
							<!-- <button type="button" class="btn btn-primary" name="btn_test" id="btn_test"  onclick="test_klik()">Test Btn</button> -->
						</td>
						<!-- <td>2</td>
						<td>3</td> -->
						<td></td>
					</tr>
					<tr style="background-color: #1E90FF;">
						<th class='text-center' width='10%'>Plan Date</th>
						<th class='text-center' width='15%'>Product</th>
						<th class='text-center' width='15%'>Propose Production</th>
						<th class='text-center' width='15%'>m3/pcs</th>
						<th class='text-center' width='15%'>Shift 1</th>
						<th class='text-center' width='15%'>Shift 2</th>
						<th class='text-center' width='15%'>Total Kubikasi</th>
						<th class='text-center' width='10%'>Option</th>
					</tr>
				</thead>
				<tbody id="list_schedule_detil">
<?php
if (isset($DataPlan_detail)) {
	$index = 0;
	foreach ($DataPlan_detail as $plan_detail) {
?>
					<tr class="header_<?= $index ?>">
						<td class='text-center' width='10%'>
							<!-- <?= $plan_detail->plan_date ?> -->
							<input type="hidden" class="id" value="<?= $plan_detail->id_planning_harian_detail ?>">
							<input type="hidden" name="Detail[<?= $index ?>][tanggal]" class="form-control input-md text-center datepicker" placeholder="Plan Date" value="<?= $plan_detail->plan_date ?>" readonly>
							<input type="text" name="tanggal_view" class="form-control input-md text-center" placeholder="Plan Date" value="<?= $plan_detail->plan_date ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->name_product ?> -->
							<input type="hidden" name="Detail[<?= $index ?>][product]" class="form-control input-md text-center get_data_product product" placeholder="Plan Date" value="<?= $plan_detail->id_stock_product ?>" >
							<input type="text" name="name_product_view" class="form-control input-md text-center" placeholder="" value="<?= $plan_detail->name_product ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->propose_production ?> -->
							<input type="text" name="Detail[<?= $index ?>][propose]" class="form-control input-md text-center propose" placeholder="Plan Date" value="<?= $plan_detail->propose_production ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->m3_pcs ?> -->
							<input type="text" name="Detail[<?= $index ?>][m3]" class="form-control input-md text-center m3" placeholder="Plan Date" value="<?= $plan_detail->m3_pcs ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->shift1 ?> -->
							<input type="text" name="Detail[<?= $index ?>][shift1]" class="form-control input-md text-center shift1" placeholder="Plan Date" value="<?= $plan_detail->shift1 ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->shift2 ?> -->
							<input type="text" name="Detail[<?= $index ?>][shift2]" class="form-control input-md text-center shift2" placeholder="Plan Date" value="<?= $plan_detail->shift2 ?>" readonly>
						</td>
						<td class='text-center' width='15%'>
							<!-- <?= $plan_detail->total_kubikasi ?> -->
							<input type="text" name="Detail[<?= $index ?>][total_kubikasi]" class="form-control input-md text-center total_kubikasi" placeholder="Plan Date" value="<?= $plan_detail->total_kubikasi ?>" readonly>
						</td>
						<td class='text-center' width='10%'>
							<!-- <button type='button' class='btn btn-sm btn-danger delPartPlan' title='Delete Part'><i class='fa fa-close'></i></button> -->
							<button type='button' class='btn btn-sm btn-success editPlan' title='Edit' data-id="<?= $plan_detail->id_planning_harian_detail ?>">Edit</button>
							<button type='button' class="btn btn-sm btn-danger del_product_price_'<?= $plan_detail->id_planning_harian_detail ?>'" onclick="del_planning_harian('<?= $plan_detail->id_planning_harian_detail ?>')" title='Delete'><i class='fa fa-close'></i></button>
							<!-- del_product_price_' . $penawaran_detail->id_penawaran_detail . '" onclick="del_product_price(' . $penawaran_detail->id_penawaran_detail . ')" -->
						</td>
					</tr>
<?php
		$index++;
	}
}
?>
					<tr id='add_0'>
						<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartPlan' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>
						<td align='center'></td>
						<td align='center'></td>
						<td align='center'></td>
						<!-- <td align='center'></td> -->
						<td align='center' colspan="2" style="text-align: right;">Total Kubikasi Tgl :</td>
						<!-- <td align='center'></td> -->
						<!-- <td align='center'>9999</td> -->
						<td align='center' colspan='2'>
              <input type='text' id='grand_total_kubikasi' class='form-control input-md text-center' placeholder='Grand Total Kubikasi' readonly>
            </td>
					</tr>
				</tbody>
				</table>
			</div>
        </div>
		<div class="form-group row">
			<div class="col-md-6">
				<!-- <button type="button" class="btn btn-primary" name="save" id="save">Save</button> -->
				<?php
				if (!empty($DataPlan_detail)) {
				?>
				<button type="button" class="btn btn-primary" name="update_new" id="update_new" onclick="saveScheduleDetail()">Update</button>
				<?php
				}else{
				?>
				<button type="button" class="btn btn-primary" name="save_new" id="save_new">Save</button>
				<?php
				}
				?>
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


<script src="<?= base_url('assets/js/jquery.maskMoney.js')?>"></script>
<script src="<?= base_url('assets/js/autoNumeric.js')?>"></script>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

<style>
    .datepicker, .datepicker2{
        cursor: pointer;
    }
</style>

<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';

	// Fungsi bantu untuk dapatkan tanggal awal dan akhir dari bulan-tahun yang dipilih
function getDateRangeFromMonthYear(bulan, tahun) {
    var firstDay = new Date(tahun, bulan - 1, 1);
    var lastDay = new Date(tahun, bulan, 0);
    return { min: firstDay, max: lastDay };
}

function updateDatepickersByMonthYear() {
	const selectedMonth = parseInt($('#bulan').val());
	const selectedYear = parseInt($('#tahun').val());

	if (!selectedMonth || !selectedYear) return;

	const firstDay = new Date(selectedYear, selectedMonth - 1, 1);
	const lastDay = new Date(selectedYear, selectedMonth, 0);

	$('.datepicker').datepicker('option', {
		minDate: firstDay,
		maxDate: lastDay
	});
	$('.datepicker2').datepicker('option', {
		minDate: firstDay,
		maxDate: lastDay
	});
}


$('#bulan, #tahun').on('change', function () {
	updateDatepickersByMonthYear();
});


//START JAVASCRIPT NEW
function updateGrandTotalKubikasiTerakhirTgl() {
    let tanggalTerakhir = null;
    let totalKubikasi = 0;

    // Step 1: Ambil tanggal terbesar (terakhir)
    $('input[name$="[tanggal]"]').each(function () {
        let tglStr = $(this).val(); // Contoh: 01-Jan-2025
        let dateObj = parseTanggal(tglStr);
        if (dateObj && (!tanggalTerakhir || dateObj > tanggalTerakhir)) {
            tanggalTerakhir = dateObj;
        }
    });

    // Step 2: Hitung total kubikasi hanya untuk tanggal terakhir
    $('tr').each(function () {
        let $row = $(this);
        let tglStr = $row.find('input[name$="[tanggal]"]').val();
        let dateObj = parseTanggal(tglStr);

        if (dateObj && tanggalTerakhir && +dateObj === +tanggalTerakhir) {
            let val = parseFloat($row.find('.total_kubikasi').val()?.replace(',', '.') || 0);
            totalKubikasi += val;
        }
    });

    // Step 3: Tampilkan total kubikasi dari tanggal terakhir
    $('#grand_total_kubikasi').val(totalKubikasi.toFixed(4));
}

// Fungsi bantu untuk parsing tanggal dari format "dd-MMM-yyyy"
function parseTanggal(tglStr) {
    if (!tglStr) return null;
    let parts = tglStr.split('-');
    if (parts.length !== 3) return null;

    let day = parts[0].padStart(2, '0');
    let month = bulanKeAngka(parts[1]);
    let year = parts[2];

    return new Date(`${year}-${month}-${day}`);
}

function bulanKeAngka(bulan) {
    const map = {
        Jan: '01', Feb: '02', Mar: '03', Apr: '04',
        May: '05', Jun: '06', Jul: '07', Aug: '08',
        Sep: '09', Oct: '10', Nov: '11', Dec: '12'
    };
    return map[bulan.substring(0, 3)] || '01';
}

// Jalankan ulang fungsi saat ada perubahan input
$(document).on('input change', '.total_kubikasi, input[name$="[tanggal]"]', function () {
    updateGrandTotalKubikasiTerakhirTgl();
});

function updateGrandTotalKubikasi() {
  let grandTotal = 0;
  $('.total_kubikasi').each(function() {
      let val = parseFloat($(this).val().replace(',', '.')) || 0;
      grandTotal += val;
  });

  $('#grand_total_kubikasi').val(grandTotal.toFixed(2)); // Atur jumlah desimal sesuai kebutuhan
}

// Panggil fungsi setiap kali input total_kubikasi berubah nilainya
$(document).on('input', '.total_kubikasi', function() {
  // updateGrandTotalKubikasi();
  updateGrandTotalKubikasiTerakhirTgl();
});


$(document).on('change', '.get_data_product', function() {//test
    var id_product = $(this).val();
    var rowIndex	= $('#rowIndex').val()
    // var no_penawaran = $('#no_surat').val();
    // var grand_total_input = $('#grand_total').val();
    // var total_all_qty = parseFloat($('#total_all_qty').val().replace(/[^0-9.-]+/g,"")) || 0;
    // var jarak_pengiriman = parseFloat($('#jarak_pengiriman_truck_dc').val().replace(/\./g, '')) || 0;
    // var estimasi_tol = parseFloat($('#estimasi_tol_bt').val().replace(/\./g, '')) || 0;

    // console.log("DEBUG id_product = ", id_product);
    // console.log("DEBUG siteurl = ", siteurl);
    // console.log("DEBUG active_controller = ", active_controller);

    if (!id_product) {
        console.warn("ID product kosong saat pilih pertama!");
        return; // Jangan lanjut Ajax kalau id_truck kosong
    }

    // console.log(id_product);
    // return;

    $.ajax({
        type: 'post',
        url: siteurl + active_controller + '/get_data_product',
        data: {
            'id_product': id_product,
            'rowIndex': rowIndex
        },
        cache: false,
        dataType: 'json',
        success: function(result) {
            // console.log("Success result = ", result);
            // location.reload();
            // $("#Detail[" + rowIndex + "][propose]");
            // $(`input[name="Detail[${rowIndex}][propose]"]`).val(data.propose);
            // Pastikan data.propose ada sebelum diset
						if (result && typeof result.propose !== 'undefined') {
						    const selector = `input[name="Detail[${rowIndex}][propose]"]`;
						    $(selector).val(result.propose);
						} else {
						    console.warn(`Propose data tidak ditemukan untuk rowIndex ${rowIndex}`);
						}
						if (result && typeof result.volumeM3 !== 'undefined') {
						    const selector = `input[name="Detail[${rowIndex}][m3]"]`;
						    $(selector).val(result.volumeM3);
						} else {
						    console.warn(`m3/pcs data tidak ditemukan untuk rowIndex ${rowIndex}`);
						}
						// updateGrandTotalKubikasi(); // <-- panggil di sini
						updateGrandTotalKubikasiTerakhirTgl();
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: ", status, error);
            console.log(xhr.responseText); // ini supaya kita tahu detail error dari server
        }
    });
});

$(document).on('input', 'input[name^="Detail"][name$="[shift1]"], input[name^="Detail"][name$="[shift2]"]', function () {
    let inputName = $(this).attr('name'); // e.g., Detail[3][shift1]
    
    // Ambil row index dari nama input
    let match = inputName.match(/^Detail\[(\d+)\]\[shift[12]\]$/);
    if (!match) return;

    let rowIndex = match[1];

    // Ambil nilai shift1, shift2, dan propose
    let shift1 = parseFloat($(`input[name="Detail[${rowIndex}][shift1]"]`).val()) || 0;
    let shift2 = parseFloat($(`input[name="Detail[${rowIndex}][shift2]"]`).val()) || 0;
    let m3 = parseFloat($(`input[name="Detail[${rowIndex}][m3]"]`).val()) || 0;
    let propose = parseFloat($(`input[name="Detail[${rowIndex}][propose]"]`).val()) || 0;

    // let total = shift1 + shift2;//version old
    let total = (shift1 + shift2) * m3;

    // if (total > propose) {
    //     alert(`Total Shift 1 + Shift 2 (${total}) tidak boleh lebih dari Propose (${propose})`);
        
    //     // Reset input yang terakhir diubah ke 0
    //     $(this).val(0);
    //     // updateGrandTotalKubikasi(); // <-- panggil di sini
    //     updateGrandTotalKubikasiTerakhirTgl();
    // }else{
    	$(`input[name="Detail[${rowIndex}][total_kubikasi]"]`).val(total);
    	// updateGrandTotalKubikasi(); // <-- panggil di sini
    	updateGrandTotalKubikasiTerakhirTgl();
    // }
});

$('#save_new').click(function(e){
	e.preventDefault();
	let propose = $('#propose').val()
	let selectval;
	//plan date
	$('.datepicker').each(function(){
		selectval = $(this).val();
		
		if(selectval == ''){
			return false;
		}
	});
	if(selectval == ''){
		swal({
		title	: "Error Message!",
		text	: 'Plan date belum dipilih...',
		type	: "warning"
		});
		return false;
	}
	//start new
	//product	
	$('.product').each(function(){
		selectval = $(this).val();
		
		if(selectval == '0'){
			return false;
		}
	});
	if(selectval == '0'){
		swal({
		title	: "Error Message!",
		text	: 'Product belum dipilih...',
		type	: "warning"
		});
		return false;
	}
  //propose
	$('.propose').each(function(){
		selectval = $(this).val();
		
		if(selectval == ''){
			return false;
		}
	});
	if(selectval == ''){
		swal({
		title	: "Error Message!",
		text	: 'Propose Production belum terisi...',
		type	: "warning"
		});
		return false;
	}
	//m3.pcs
	$('.m3').each(function(){
		selectval = $(this).val();
		
		if(selectval == ''){
			return false;
		}
	});
	if(selectval == ''){
		swal({
		title	: "Error Message!",
		text	: 'm3/pcs belum terisi...',
		type	: "warning"
		});
		return false;
	}
	//shift 1
	$('.shift1').each(function(){
		selectval = $(this).val();
		
		if(selectval == '' || selectval <= 0){
			return false;
		}
	});
	if(selectval == ''){
		swal({
		title	: "Error Message!",
		text	: 'Shift 1 tidak boleh kosong / Nol...',
		type	: "warning"
		});
		return false;
	}
	//shift 2
	$('.shift2').each(function(){
		selectval = $(this).val();
		
		if(selectval == '' || selectval <= 0){
			return false;
		}
	});
	if(selectval == ''){
		swal({
		title	: "Error Message!",
		text	: 'Shift 2 tidak boleh kosong / Nol...',
		type	: "warning"
		});
		return false;
	}

	//end new
	//qty_spk
	// $('.qty_spk').each(function(){
	// 	selectval = $(this).val();
		
	// 	if(selectval == '' || selectval <= 0){
	// 		return false;
	// 	}
	// });
	// if(selectval == ''){
	// 	swal({
	// 	title	: "Error Message!",
	// 	text	: 'Qty tidak boleh kosong / Nol...',
	// 	type	: "warning"
	// 	});
	// 	return false;
	// }

	//CHECK QTY
	// let SUM = 0
	// $('.qty_spk').each(function(){
	// 	qty = getNum($(this).val().split(",").join(""));
		
	// 	SUM += qty
	// });

	// if(SUM != propose){
	// 	swal({
	// 	title	: "Error Message!",
	// 	text	: 'Jumlah Qty SPK dan Propose Harus Sama !',
	// 	type	: "warning"
	// 	});
	// 	return false;
	// }

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
				var formData 	=new FormData($('#data-form')[0]);
				var baseurl=siteurl+active_controller+'/add_new';
				$.ajax({
					url			: baseurl,
					type		: "POST",
					data		: formData,
					cache		: false,
					dataType	: 'json',
					processData	: false,
					contentType	: false,
					success		: function(data){
						if(data.status == 1){
							swal({
								  title	: "Save Success!",
								  text	: data.pesan,
								  type	: "success",
								  timer	: 7000
								});
								// window.open(base_url + active_controller+'/print_spk/'+data.kode,'_blank');
								window.location.href = base_url + active_controller
						}else{

							if(data.status == 2){
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning",
								  timer	: 7000
								});
							}else{
								swal({
								  title	: "Save Failed!",
								  text	: data.pesan,
								  type	: "warning",
								  timer	: 7000
								});
							}

						}
					},
					error: function() {

						swal({
						  title				: "Error Message !",
						  text				: 'An Error Occured During Process. Please try again..',
						  type				: "warning",
						  timer				: 7000
						});
					}
				});
		  } else {
			swal("Cancelled", "Data can be process again :)", "error");
			return false;
		  }
	});
});



//END JAVASCRIPT NEW



	$(document).ready(function(){
		$('.chosen-select').select2();
    	$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false})

		// $('#myTable').DataTable({});

    	//back
		$(document).on('click', '#back', function(){
		    window.location.href = base_url + active_controller
		});

		$(document).on('click', '.addPart', function(){
			// loading_spinner();
			var get_id 		= $(this).parent().parent().attr('id');
			var split_id	= get_id.split('_');
			var id 			= parseInt(split_id[1])+1;
			var id_bef 		= split_id[1];
			var due_date	= $('#due_date').val()
			var max_date	= $('#max_date').val()

			$.ajax({
				url: base_url+active_controller+'/get_add/'+id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$("#add_"+id_bef).before(data.header);
					$("#add_"+id_bef).remove();
					$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false})
					$('.chosen-select').select2();
					$('.datepicker').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });
					$('.datepicker2').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });
					// $('.datepicker2').datepicker({ dateFormat: 'dd-M-yy', minDate:'+0d'});
					swal.close();
				},
				error: function() {
					swal({
						title				: "Error Message !",
						text				: 'Connection Time Out. Please try again..',
						type				: "warning",
						timer				: 3000,
						showCancelButton	: false,
						showConfirmButton	: false,
						allowOutsideClick	: false
					});
				}
			});
		});

		$(document).on('click', '.addPartPlan', function(){
			// loading_spinner();
			var get_id 		= $(this).parent().parent().attr('id');
			var split_id	= get_id.split('_');
			// var id 			= parseInt(split_id[1])+1;//version old
			// let rowIndexCounter = parseInt(<?= $index+1 ?>); // mulai dari jumlah data lama //version new
			// var id_bef 		= split_id[1];//version old
			// var id_bef 		= <?= $index ?>;//version new

			var id_bef = parseInt($('#jumlah_data_detail').val()) || 0;
			var id     = id_bef + 1;

			var due_date	= $('#due_date').val()
			var max_date	= $('#max_date').val()
			// console.log(id)
			// return;

			$.ajax({
				url: base_url+active_controller+'/get_add_plan/'+id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					console.log('Response:', data);
					console.log("Selector: ", "#add_" + id_bef);
					console.log("Exists in DOM:", $("#add_" + id_bef).length > 0);
					// $("#add_"+id_bef).before(data.header);
					// $("#add_"+id_bef).remove();
					// $("#add_row").before(data.header);
					$("#add_0").before(data.header);
					$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false})
					$('.chosen-select').select2();
					// $('.datepicker').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });//version old
					// $('.datepicker2').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });//version old
					// Ambil bulan dan tahun dari dropdown
					$('.datepicker').datepicker({ dateFormat: 'dd-M-yy' });
					$('.datepicker2').datepicker({ dateFormat: 'dd-M-yy' });
					updateDatepickersByMonthYear();
					$('#rowIndex').val(data.rowIndex);
					$('#jumlah_data_detail').val(id);
					swal.close();
				},
				error: function() {
					swal({
						title				: "Error Message !",
						text				: 'Connection Time Out. Please try again..',
						type				: "warning",
						timer				: 3000,
						showCancelButton	: false,
						showConfirmButton	: false,
						allowOutsideClick	: false
					});
				}
			});
		});

// $(document).on('click', '.addPartPlan', function () {
//   // Ambil ID baris saat ini (format: add_0, add_1, dst.)
//   var get_id     = $(this).closest('tr').attr('id');
//   var split_id   = get_id.split('_');
//   var id         = parseInt(split_id[1]) + 1;

//   // Ambil index terakhir dari PHP
//   var id_bef     = <?= $JmlhDataDetail ?>;

//   // Ambil tanggal untuk batasan datepicker
//   var due_date   = $('#due_date').val();
//   var max_date   = $('#max_date').val();

//   $.ajax({
//       url: base_url + active_controller + '/get_add_plan/' + id,
//       cache: false,
//       type: "POST",
//       dataType: "json",
//       success: function (data) {
//           // Sisipkan row baru sebelum row 'add'
//           $("#add_" + id_bef).before(data.header);

//           // Hapus row add sebelumnya
//           $("#add_" + id_bef).remove();

//           // Inisialisasi ulang plugin yang digunakan
//           $('.autoNumeric0').autoNumeric('init', { mDec: '0', aPad: false });
//           $('.chosen-select').select2();
//           $('.datepicker').datepicker({ dateFormat: 'dd-M-yy' });
//           $('.datepicker2').datepicker({ dateFormat: 'dd-M-yy' });

//           // Update datepicker jika pakai navigasi bulan/tahun custom
//           updateDatepickersByMonthYear();

//           // Update index terakhir yang disimpan di hidden input
//           $('#rowIndex').val(data.rowIndex);

//           // Tutup swal jika sebelumnya ada spinner
//           swal.close();
//       },
//       error: function () {
//           swal({
//               title: "Error Message!",
//               text: 'Connection Time Out. Please try again..',
//               type: "warning",
//               timer: 3000,
//               showCancelButton: false,
//               showConfirmButton: false,
//               allowOutsideClick: false
//           });
//       }
//   });
// });


		$(document).on('click', '.delPart', function(){
			var get_id 		= $(this).parent().parent().attr('class');
			$("."+get_id).remove();
		});

		// $(document).on('click', '.delPartPlan', function(){
		// 	var get_id 		= $(this).parent().parent().attr('class');
		// 	$("."+get_id).remove();
		// 	updateGrandTotalKubikasiTerakhirTgl();
		// });

		$(document).on('click', '.delPartPlan', function(){
	    var get_id = $(this).closest('tr').attr('class');
	    $("." + get_id).remove();
	    updateGrandTotalKubikasiTerakhirTgl(); // panggil fungsi update total jika perlu
	  });

		$(document).on('keyup', '.qty_spk', function(){
			let cycletime = $('#cycletime').val()
			let SUM = 0
			$('.qty_spk').each(function(){
				qty = getNum($(this).val().split(",").join(""));
				
				SUM += qty
			});

			let totalCT = cycletime * SUM

			$('#total_cycletime').text(number_format(totalCT,2))

		});

		$('#save').click(function(e){//version old
			e.preventDefault();
			let propose = $('#propose').val()
			let selectval;
			//plan date
			$('.datepicker').each(function(){
				selectval = $(this).val();
				
				if(selectval == ''){
					return false;
				}
			});
			if(selectval == ''){
				swal({
				title	: "Error Message!",
				text	: 'Plan date belum dipilih...',
				type	: "warning"
				});
				return false;
			}
			//qty_spk
			$('.qty_spk').each(function(){
				selectval = $(this).val();
				
				if(selectval == '' || selectval <= 0){
					return false;
				}
			});
			if(selectval == ''){
				swal({
				title	: "Error Message!",
				text	: 'Qty tidak boleh kosong / Nol...',
				type	: "warning"
				});
				return false;
			}
			//costcenter
			$('.costcenter').each(function(){
				selectval = $(this).val();
				
				if(selectval == '0'){
					return false;
				}
			});
			if(selectval == '0'){
				swal({
				title	: "Error Message!",
				text	: 'Costcenter belum dipilih...',
				type	: "warning"
				});
				return false;
			}

			//CHECK QTY
			let SUM = 0
			$('.qty_spk').each(function(){
				qty = getNum($(this).val().split(",").join(""));
				
				SUM += qty
			});

			if(SUM != propose){
				swal({
				title	: "Error Message!",
				text	: 'Jumlah Qty SPK dan Propose Harus Sama !',
				type	: "warning"
				});
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
						var formData 	=new FormData($('#data-form')[0]);
						var baseurl=siteurl+active_controller+'/add';
						$.ajax({
							url			: baseurl,
							type		: "POST",
							data		: formData,
							cache		: false,
							dataType	: 'json',
							processData	: false,
							contentType	: false,
							success		: function(data){
								if(data.status == 1){
									swal({
										  title	: "Save Success!",
										  text	: data.pesan,
										  type	: "success",
										  timer	: 7000
										});
										window.open(base_url + active_controller+'/print_spk/'+data.kode,'_blank');
										window.location.href = base_url + active_controller
								}else{

									if(data.status == 2){
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000
										});
									}else{
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000
										});
									}

								}
							},
							error: function() {

								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',
								  type				: "warning",
								  timer				: 7000
								});
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					return false;
				  }
			});
		});

		$(document).on('click', '.detail', function(){
			var no_bom = $(this).data('id');
			var category = $(this).data('category');
			// console.log(category)
			let controller_category = '';
			if(category == 'standard'){
				controller_category = 'bom';
			}
			if(category == 'topping'){
				controller_category = 'bom_topping';
			}
			if(category == 'grid standard'){
				controller_category = 'bom_hi_grid_standard';
			}
			if(category == 'grid custom'){
				controller_category = 'bom_hi_grid_custom';
			}
			if(category == 'ftackel'){
				controller_category = 'bom_ftackel';
			}
			// alert(id);
			$("#head_title").html("<b>Detail Bill Of Material</b>");
			$.ajax({
				type:'POST',
				url: base_url + controller_category + '/detail/',
				data:{
					'no_bom':no_bom,
				},
				success:function(data){
					$("#dialog-popup").modal();
					$("#ModalView").html(data);

				}
			})
		});

	});

	function number_format (number, decimals, dec_point, thousands_sep) {
		// Strip all characters but numerical ones.
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function (n, prec) {
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

function del_planning_harian(id) {
	var id_planning_harian	= $('#id_planning_harian').val();
	// var id_header = segments[4]; // index ke-4 = segment ke-5
	// Ambil path dari URL, misalnya: /megacon/spk_material/create_plan/27
	var path = window.location.pathname;
	// Pisah berdasarkan slash
	var segments = path.split('/');
	// Ambil segment terakhir (angka 27)
	var lastSegment = segments.pop() || segments.pop(); // handle jika ada slash di akhir URL
  $.ajax({
      type: 'post',
      url: siteurl + active_controller + '/del_planning_harian',
      data: {
          'id': id
      },
      cache: false,
      success: function(result) {
        // cek_detail_penawaran(no_surat);
        // updateBeratAktualStyle();
        // updateGrandTotalKubikasiTerakhirTgl(); // panggil fungsi update total jika perlu
      	// cek_schedule_detil(id_planning_harian);
      	location.reload(); // atau refresh table
      	// window.location.href = base_url + active_controller + '/create_plan/' + lastSegment;
      	// refreshAjaxTable('#myTable');
      }
  });
}	

function refreshAjaxTable(tableId) {
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().ajax.reload(null, false); // false agar tetap di halaman sekarang
    } else {
        console.warn("DataTable belum diinisialisasi pada:", tableId);
    }
}

function test_klik(){
	var id_planning_harian	= $('#id_planning_harian').val();
	cek_schedule_detil(id_planning_harian);
	updateGrandTotalKubikasiTerakhirTgl(); // panggil fungsi update total jika perlu
}

function cek_schedule_detil(id) {
  // var id = '';
  // var ppn = $('.ppn_check:checked').val();
  // var curr = $('.curr').val();
  // var get_id 		= $(this).parent().parent().attr('id');
	// var split_id	= get_id.split('_');
  // var id_uri		= parseInt(split_id[1])+1;

  $.ajax({
      type: 'post',
      url: siteurl + active_controller + '/cek_schedule_detil',
      data: {
          'id': id
          // 'no_surat': no_surat,
          // 'ppn': ppn,
          // 'curr': curr
      },
      cache: false,
      dataType: 'JSON',
      success: function(result) {
          $('#list_schedule_detil').html(result.hasil);
      }
  });
}

function saveScheduleDetail() {
    let dataDetail = [];

    $('#list_schedule_detil tr').each(function () {
        const row = $(this);
        const id = row.find('.id').val() || '';
        // const id = $('.id').val() || '';
        const tanggal = row.find('.datepicker').val();
        const product = row.find('.product').val();
        const propose = row.find('.propose').val();
        const m3 = row.find('.m3').val();
        const shift1 = row.find('.shift1').val();
        const shift2 = row.find('.shift2').val();
        const total_kubikasi = row.find('.total_kubikasi').val();

        // skip jika semua kosong
        if (!tanggal || !product) return;

        dataDetail.push({
            id: id,
            tanggal: tanggal,
            product: product,
            propose: propose,
            m3: m3,
            shift1: shift1,
            shift2: shift2,
            total_kubikasi: total_kubikasi
        });
    });

    const kode_planning = $('#kode_planning').val(); // pastikan ada input hidden atau select untuk ini

    $.ajax({
        // url: "<?= base_url('spk_material/update_schedule_detail') ?>",
        url: siteurl + active_controller + '/update_schedule_detail',
        type: "POST",
        dataType: "json",
        data: {
            kode_planning: kode_planning,
            Detail: dataDetail
        },
        success: function (res) {
            if (res.status) {
                alert("Data berhasil disimpan!");
                location.reload(); // atau refresh table
            } else {
                alert("Gagal menyimpan data.");
            }
        },
        error: function (xhr, status, error) {
            alert("Terjadi kesalahan koneksi.");
        }
    });
}

//START BAGIAN MODAL EDIT PLAN DETAIL
$(document).on('click', '.editPlan', function() {
    var id = $(this).data('id');
    // alert(id);
    $("#head_title").html("<i class='fa fa-list-alt'></i><b>Detail Planning Harian</b>");
    $.ajax({
        type: 'POST',
        // url: siteurl + active_controller + '/detail_sales_order/' + id,
        url: siteurl + active_controller + '/edit_plan_harian/' + id,
        data: {
            'id': id
        },
        success: function(data) {
            $("#dialog-popup").modal();
            $("#ModalView").html(data);

            $('.modal-footer').html('');
        }
    });
});
//END BAGIAN MODAL EDIT PLAN DETAIL

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
				url: siteurl + active_controller + '/update_plan_detail',
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

</script>
