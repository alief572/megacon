<?php
// fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename=Product Costing (" . date('d F Y') . ").xls");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Excel Product Costing</title>
</head>

<body>
    <table style="width: 100%" border="1">
        <thead>
            <tr>
                <th align="center">No.</th>
                <th align="center">Nama Produk</th>
                <th align="center">Material</th>
                <th align="center">Man Power</th>
                <th align="center">Mesin</th>
                <th align="center">Mold</th>
                <th align="center">Consumable</th>
                <th align="center">Engineering</th>
                <th align="center">FOH</th>
                <th align="center">SDM (HO)</th>
                <th align="center">Marketing</th>
                <th align="center">Interest</th>
                <th align="center">Modal Exclude PPn</th>
                <th align="center">PPn 11% dari harga jual</th>
                <th align="center">Profit 20% dari harga jual</th>
                <th align="center">Harga Jual</th>
                <th align="center">Kompetitif Faktor</th>
                <th align="center">Harga Jual * Kompetitif Faktor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $item) :
                echo '<tr>';
                echo '<td>' . $item['no'] . '</td>';
                echo '<td>' . $item['nama_produk'] . '</td>';
                echo '<td>' . number_format($item['material'], 2) . '</td>';
                echo '<td>' . number_format($item['man_power'], 2) . '</td>';
                echo '<td>' . number_format($item['mesin'], 2) . '</td>';
                echo '<td>' . number_format($item['mold'], 2) . '</td>';
                echo '<td>' . number_format($item['consumable'], 2) . '</td>';
                echo '<td>' . number_format($item['engineering'], 2) . '</td>';
                echo '<td>' . number_format($item['foh'], 2) . '</td>';
                echo '<td>' . number_format($item['sdm_ho'], 2) . '</td>';
                echo '<td>' . number_format($item['marketing'], 2) . '</td>';
                echo '<td>' . number_format($item['interest'], 2) . '</td>';
                echo '<td>' . number_format($item['modal_exc_ppn'], 2) . '</td>';
                echo '<td>' . number_format($item['ppn'], 2) . '</td>';
                echo '<td>' . number_format($item['profit'], 2) . '</td>';
                echo '<td>' . number_format($item['harga_jual'], 2) . '</td>';
                echo '<td>' . number_format($item['kompetitif_faktor'], 2) . '</td>';
                echo '<td>' . number_format($item['harga_jual_komp_faktor'], 2) . '</td>';
                echo '</tr>';
            endforeach;
            ?>
        </tbody>
    </table>
</body>

</html>