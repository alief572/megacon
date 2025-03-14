<?php
    $ENABLE_ADD     = has_permission('Inventory_2.Add');
    $ENABLE_MANAGE  = has_permission('Inventory_2.Manage');
    $ENABLE_VIEW    = has_permission('Inventory_2.View');
    $ENABLE_DELETE  = has_permission('Inventory_2.Delete');

foreach ($results['inven'] as $inven){
}	

?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box box-primary">
	<!-- /.box-header -->
	<div class="box-body">
		<form id="data_form">
		<input type="hidden" name="id_inventory" id="id_inventory" value='<?= $inven->id_category3 ?>'>
		<div class="row">
			<div class="col-md-12"><legend>Data Category</legend></div>
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-3">
					 <label for="inventory_1">Inventory Type</label>
					</div>
					<div class="col-md-9">
					  <select id="inventory_1"  name="inventory_1"  class="form-control select" required>
						<option value="">-- Pilih Type --</option>
						<?php foreach ($results['lvl1'] as $lvl1){
						$select = $inven->id_type == $lvl1->id_type ? 'selected' : '';
						?>
						<option value="<?= $lvl1->id_type?>" <?= $select ?>><?= $lvl1->nama?></option>
						<?php } ?>
					  </select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3">
					 <label for="inventory_2" >Nama Category I</label>
					</div>
					<div class="col-md-9">
					  <select id="inventory_2" name="inventory_2" class="form-control select" required>
						<option value="">-- Pilih Kategori --</option>
						<?php foreach ($results['lvl2'] as $lvl2){
						$select = $inven->id_category1 == $lvl2->id_category1 ? 'selected' : '';
						?>
						<option value="<?= $lvl2->id_category1?>" <?= $select ?>><?= $lvl2->nama?></option>
						<?php } ?>
					  </select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3">
					 <label for="inventory_2" >Nama Category II</label>
					</div>
					<div class="col-md-9">
					  <select id="inventory_3" name="inventory_3" class="form-control select" required>
						<option value="">-- Pilih Kategori --</option>
						<?php foreach ($results['lvl3'] as $lvl3){
						$select = $inven->id_category2 == $lvl3->id_category2 ? 'selected' : '';
						?>
						<option value="<?= $lvl3->id_category2?>" <?= $select ?>><?= $lvl3->nama?></option>
						<?php } ?>
					  </select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3">
					  <label for="">Nama Category III</label>
					</div>
					 <div class="col-md-9">
					  <input type="text" class="form-control" id="nm_inventory" required name="nm_inventory" placeholder="Inventory Name" value="<?= $inven->nama ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3">
					  <label for="">Status</label>
					</div>
					<div class="col-md-4">
					<?php if ($inven->aktif == 'aktif'){?>
					  <label>
						<input type="radio" class="radio-control" id="" name="status" value="aktif" checked required> Aktif
					  </label>
						&nbsp &nbsp &nbsp
					  <label>
						<input type="radio" class="radio-control" id="" name="status" value="nonaktif" required> Non Aktif
					  </label>
					<?php } else { ?>
					  <label>
						<input type="radio" class="radio-control" id="" name="status" value="aktif" required> Aktif
					  </label>
						&nbsp &nbsp &nbsp
					  <label>
						<input type="radio" class="radio-control" id="" name="status" value="nonaktif" checked required> Non Aktif
					  </label>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<hr>
	<button type="submit" class="btn btn-primary" name="save" id="save"><i class="fa fa-save"></i> Save</button>
	</form>
	</div>
	<!-- /.box-body -->
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">

  	$(function() {
		$('.select2').select2();
  	});
	$("#inventory_1").change(function(){

            // variabel dari nilai combo box kendaraan
            var inventory_1 = $("#inventory_1").val();

            // Menggunakan ajax untuk mengirim dan dan menerima data dari server
            $.ajax({
                url:siteurl+'inventory_4/get_inven2',
                method : "POST",
                data : {inventory_1:inventory_1},
                async : false,
                dataType : 'json',
                success: function(data){
                    var html = '';
                    var i;

                    for(i=0; i<data.length; i++){
                        html += '<option value='+data[i].id_category1+'>'+data[i].nama+'</option>';
                    }
                    $('#inventory_2').html(html);

                }
            });
        });
	
	$("#inventory_2").change(function(){

            // variabel dari nilai combo box kendaraan
            var inventory_2 = $("#inventory_2").val();

            // Menggunakan ajax untuk mengirim dan dan menerima data dari server
            $.ajax({
                url:siteurl+'inventory_4/get_inven3',
                method : "POST",
                data : {inventory_2:inventory_2},
                async : false,
                dataType : 'json',
                success: function(data){
                    var html = '';
                    var i;

                    for(i=0; i<data.length; i++){
                        html += '<option value='+data[i].id_category2+'>'+data[i].nama+'</option>';
                    }
                    $('#inventory_3').html(html);

                }
            });
        });
	
	// ADD CUSTOMER 
	
	$(document).on('submit', '#data_form', function(e){
		e.preventDefault()
		var data = $('#data_form').serialize();
		var id = $('#id_inventory').val();
		// alert(id);
		swal({
		  title: "Anda Yakin?",
		  text: "Data Inventory akan di simpan.",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-info",
		  confirmButtonText: "Ya, Simpan!",
		  cancelButtonText: "Batal",
		  closeOnConfirm: false
		},
		function(){
		  $.ajax({
			  type:'POST',
			  url:siteurl+'inventory_4/saveEditInventory',
			  dataType : "json",
			  data:data,
			  success:function(result){
				  if(result.status == '1'){
					 swal({
						  title: "Sukses",
						  text : "Data Inventory berhasil disimpan.",
						  type : "success"
						},
						function (){
							window.location.reload(true);
						})
				  } else {
					swal({
					  title : "Error",
					  text  : "Data error. Gagal insert data",
					  type  : "error"
					})
					
				  }
			  },
			  error : function(){
				swal({
					  title : "Error",
					  text  : "Data error. Gagal request Ajax",
					  type  : "error"
					})
			  }
		  })
		});
		
	})
	
	
	

	function PreviewPdf(id)
	{
		param=id;
		tujuan = 'customer/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

	function PreviewRekap()
	{
		tujuan = 'customer/rekap_pdf';
	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	}
</script>
