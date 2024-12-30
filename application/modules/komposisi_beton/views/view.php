<?php
$jenis_beton = (isset($header)) ? $header->nm_jenis_beton : '';
$keterangan = (isset($header)) ? $header->keterangan : '';
?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<div class="box">
    <div class="box-body">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Jenis Beton</label>
                <input type="text" name="jenis_beton" id="" class="form-control form-control-sm" value="<?= $jenis_beton ?>" readonly>
            </div>
        </div>
        <div class="col-md-12">

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Keterangan</label>
                <textarea name="keterangan" id="" class="form-control form-control-sm" readonly><?= $keterangan ?></textarea>
            </div>
        </div>

        <br><br>

        <div class="col-md-12">
            <h4>Detail Material</h4>
            <table class="table table-striped" style="margin-top: 1rem;">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Material Name</th>
                        <th class="text-center">Volume (m3)</th>
                        <th class="text-center">Satuan Lainnya</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="list_detail_material">
                    <?php
                    $no = 1;
                    if (!empty($detail)) {
                        foreach ($detail as $item) {
                            echo '<tr class="row_detail_material_' . $no . '">';

                            echo '<td class="text-center">';
                            echo $no;
                            echo '</td>';

                            echo '<td class="text-left">';
                            echo '<input type="text" class="form-control form-control-sm" name="detail_material[' . $no . '][material_name]" value="' . $item->nm_material . '" readonly>';
                            echo '</td>';

                            echo '<td class="text-left">';
                            echo '<input type="number" class="form-control form-control-sm" name="detail_material[' . $no . '][volume]" value="' . $item->volume . '" step="0.01" readonly>';
                            echo '</td>';

                            echo '<td class="text-left">';
                            echo '<input type="number" class="form-control form-control-sm" name="detail_material[' . $no . '][satuan_lainnya]" value="' . $item->satuan_lainnya . '" step="0.0001" readonly>';
                            echo '</td>';

                            echo '<td class="text-left">';
                            echo '<input type="text" class="form-control form-control-sm" name="detail_material[' . $no . '][satuan]" value="' . $item->satuan . '" readonly>';
                            echo '</td>';

                            echo '<td class="text-left">';
                            echo '<textarea name="detail_material[' . $no . '][keterangan]" class="form-control form-control-sm" readonly>' . $item->keterangan . '</textarea>';
                            echo '</td>';

                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>

            <a href="<?= base_url('komposisi_beton') ?>" class="btn btn-sm btn-danger">
                <i class="fa fa-arrow-left"></i> Back
            </a>

        </div>
    </div>

</div>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>