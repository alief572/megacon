
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
							<select id="bulan" name="bulan" class="form-control" style="width: 20%;">
								<option value="01">Januari</option>
								<option value="02">Februari</option>
								<option value="03">Maret</option>
								<option value="04">April</option>
								<option value="05">Mei</option>
								<option value="06">Juni</option>
								<option value="07">Juli</option>
								<option value="08">Agustus</option>
								<option value="09">September</option>
								<option value="10">Oktober</option>
								<option value="11">November</option>
								<option value="12">Desember</option>
							</select>
							<select id="tahun" name="tahun" class="form-control" style="width: 20%;">
							<script>
								const tahunSelect = document.getElementById('tahun');
								const tahunSekarang = new Date().getFullYear();
								for (let i = tahunSekarang; i >= tahunSekarang - 10; i--) {
								const option = document.createElement('option');
								option.value = i;
								option.textContent = i;
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
			</div>
        </div>
		<h4>Schedule Detil</h4>
		<div class="form-group row">
        	<div class="col-md-12">
				<table class="table table-bordered table-striped" width='100%' border="1">
					<tr>
						<td colspan="4"></td>
						<td colspan="3" style="text-align: center; background-color: orange;">Schedule</td>
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
						<th class='text-center' width='5%'>Option</th>
					</tr>
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
				</table>
			</div>
        </div>
		<div class="form-group row">
			<div class="col-md-6">
				<!-- <button type="button" class="btn btn-primary" name="save" id="save">Save</button> -->
				<button type="button" class="btn btn-primary" name="save_new" id="save_new">Save</button>
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


<script src="<?= base_url('assets/js/jquery.maskMoney.js')?>"></script>
<script src="<?= base_url('assets/js/autoNumeric.js')?>"></script>
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
  updateGrandTotalKubikasi();
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
						updateGrandTotalKubikasi(); // <-- panggil di sini
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
    let propose = parseFloat($(`input[name="Detail[${rowIndex}][propose]"]`).val()) || 0;

    let total = shift1 + shift2;

    if (total > propose) {
        alert(`Total Shift 1 + Shift 2 (${total}) tidak boleh lebih dari Propose (${propose})`);
        
        // Reset input yang terakhir diubah ke 0
        $(this).val(0);
        updateGrandTotalKubikasi(); // <-- panggil di sini
    }else{
    	$(`input[name="Detail[${rowIndex}][total_kubikasi]"]`).val(total);
    	updateGrandTotalKubikasi(); // <-- panggil di sini
    }
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
			var id 			= parseInt(split_id[1])+1;
			var id_bef 		= split_id[1];
			var due_date	= $('#due_date').val()
			var max_date	= $('#max_date').val()

			$.ajax({
				url: base_url+active_controller+'/get_add_plan/'+id,
				cache: false,
				type: "POST",
				dataType: "json",
				success: function(data){
					$("#add_"+id_bef).before(data.header);
					$("#add_"+id_bef).remove();
					$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false})
					$('.chosen-select').select2();
					// $('.datepicker').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });//version old
					// $('.datepicker2').datepicker({ dateFormat: 'dd-M-yy', maxDate:'+'+max_date+'d' });//version old
					// Ambil bulan dan tahun dari dropdown
					$('.datepicker').datepicker({ dateFormat: 'dd-M-yy' });
					$('.datepicker2').datepicker({ dateFormat: 'dd-M-yy' });
					updateDatepickersByMonthYear();
					$('#rowIndex').val(data.rowIndex);
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

		$(document).on('click', '.delPart', function(){
			var get_id 		= $(this).parent().parent().attr('class');
			$("."+get_id).remove();
		});

		$(document).on('click', '.delPartPlan', function(){
			var get_id 		= $(this).parent().parent().attr('class');
			$("."+get_id).remove();
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


</script>
