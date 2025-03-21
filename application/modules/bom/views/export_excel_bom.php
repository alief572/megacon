<?php
// fungsi header dengan mengirimkan raw data excel
$id_product         = (!empty($header[0]->id_product)) ? $header[0]->id_product : '-';
$variant_product     = (!empty($header[0]->variant_product)) ? $header[0]->variant_product : '-';
$color_product         = (!empty($header[0]->color)) ? $header[0]->color : '-';
$keterangan         = (!empty($header[0]->keterangan)) ? $header[0]->keterangan : '-';
$moq                 = (!empty($header[0]->moq)) ? number_format($header[0]->moq, 4) : '-';
$nm_product            = (!empty($GET_LEVEL4[$id_product]['nama'])) ? $GET_LEVEL4[$id_product]['nama'] : '';
$nm_jenis_beton = (!empty($header[0]->nm_jenis_beton)) ? $header[0]->nm_jenis_beton : '';

$volume_m3 = (!empty($header[0]->volume_m3)) ? $header[0]->volume_m3 : 0;
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename=BOM (" . $nm_product . ").xls");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Excel Bom</title>
</head>

<body>
    <table width='100%' border="1">
        <tr>
            <th>Product Name</th>
            <td><?= $nm_product; ?></td>
            <th>Jenis Beton</th>
            <td><?= $nm_jenis_beton; ?></td>
        </tr>
        <tr>
            <th>Variant Product</th>
            <td colspan="3"><?= $variant_product; ?></td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td colspan="3"><?= $keterangan; ?></td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1">
        <tr>
            <th width="20%">Volume (m3)</th>
            <td width="20%"><?= number_format($volume_m3, 4) ?></td>
        </tr>
    </table>
    <br><br>

    <h4>Detail Material</h4>
    <table width='100%' border="1">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Material Name</th>
                <th class="text-center">Volume (m3)</th>
                <th class="text-center">Satuan Lainnya</th>
                <th class="text-center">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($detail as $item) {
                echo '<tr>';

                echo '<td class="text-center">' . $no . '</td>';
                echo '<td class="text-left">' . $item->nm_material . '</td>';
                echo '<td class="text-center">' . number_format($item->volume_m3, 4) . '</td>';
                echo '<td class="text-center">' . number_format($item->satuan_lainnya, 4) . '</td>';
                echo '<td class="text-center">' . ucfirst($item->satuan) . '</td>';

                echo '</tr>';

                $no++;
            }
            ?>
        </tbody>
    </table>

    <br><br>

    <h4>Detail Material Lain</h4>
    <table border="1" width='100%'>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Material Name</th>
                <th class="text-center">Kebutuhan</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($detail_material_lain as $item) {
                echo '<tr>';

                echo '<td class="text-center">' . $no . '</td>';
                echo '<td class="text-left">' . $item->nm_material . '</td>';
                echo '<td class="text-center">' . number_format($item->kebutuhan, 4) . '</td>';
                echo '<td class="text-center">' . ucfirst($item->satuan) . '</td>';
                echo '<td class="text-left">' . $item->keterangan . '</td>';

                echo '</tr>';

                $no++;
            }
            ?>
        </tbody>
    </table>
</body>

</html>