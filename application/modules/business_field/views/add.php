<?php 
    $id_bidang_usaha = (isset($list_bidang_usaha)) ? $list_bidang_usaha->id_bidang_usaha : '';
    $bidang_usaha = (isset($list_bidang_usaha)) ? $list_bidang_usaha->bidang_usaha : '';
    $keterangan = (isset($list_bidang_usaha)) ? $list_bidang_usaha->keterangan : '';
?>
<input type="hidden" name="id_bidang_usaha" value="<?= $id_bidang_usaha ?>">
<div class="form-group">
    <label for="">Business Field Name</label>
    <input type="text" name="business_field" id="" class="form-control form-control-sm" value="<?= $bidang_usaha ?>" placeholder="Business Field Name">
</div>
<div class="form-group">
    <label for="">Keterangan</label>
    <textarea name="keterangan" id="" class="form-control form-control-sm"><?= $keterangan ?></textarea>
</div>