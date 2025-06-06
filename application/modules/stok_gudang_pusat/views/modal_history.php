<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title"><b>HISTORY <?= get_name('warehouse', 'nm_gudang', 'id', $gudang); ?></b></h3><br>
    <h3 class="box-title" style="color:#c85b0e;"><b><?= strtoupper(get_name('new_inventory_4', 'nama', 'code_lv4', $material)); ?></b></h3>
    <br>
    <a href="stok_gudang_pusat/export_excel/<?= $material ?>/<?= $gudang ?>" class="btn btn-sm btn-success">
      <i class="fa fa-download"></i> Download Excel
    </a>
  </div>
  <div class="box-body tableFixHead" style="height:500px;">
    <table class="table table-striped table-bordered table-hover table-condensed" width="100%">
      <thead>
        <tr>
          <th class="text-left" width='4%'>#</th>
          <th class="text-center">Hist Date</th>
          <th class="text-left">Hist By</th>
          <th class="text-left">Dari Gudang</th>
          <th class="text-left">Ke Gudang</th>
          <th class="text-right" width='7%'>Qty NG</th>

          <th class="text-right" width='7%'>Qty Pack</th>
          <th class="text-right" width='7%'>Qty Unit</th>
          
          <th class="text-right" width='7%'>Stock Awal</th>
          <th class="text-right" width='7%'>Stock Akhir</th>
          <th class="text-left">No Trans</th>
          <th class="text-left">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 0;
        if (!empty($data)) {
          foreach ($data as $val => $valx) {
            $no++;
            $dari = strtoupper($valx['kd_gudang_dari']);
            $ke   = strtoupper($valx['kd_gudang_ke']);
            $username = get_name('users', 'nm_lengkap', 'id_user', $valx['update_by']);
            $qty_pack = $valx['jumlah_mat'] / $valx['konversi'];
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td align='center'>" . date('d-m-Y H:i:s', strtotime($valx['update_date'])) . "</td>";
            echo "<td>" . strtoupper($username) . "</td>";
            echo "<td>" . $dari . "</td>";
            echo "<td>" . $ke . "</td>";
            echo "<td align='right'>" . number_format($valx['qty_ng'], 2) . "</td>";

            echo "<td align='right'>" . number_format($valx['jumlah_mat'], 2) . "</td>";
            // echo "<td align='right'>" . number_format($valx['jumlah_mat'] / $valx['konversi'], 2) . "</td>";
            // echo "<td align='right'>" . number_format($valx['jumlah_mat'] / $valx['konversi'], 2) . "</td>";
            echo "<td align='right'>" . number_format($valx['jumlah_mat'] * $valx['konversi'], 2) . "</td>";
            // echo "<td align='right'>" . number_format($qty_pack * $valx['konversi'], 2) . "</td>";


            // echo "<td align='right'>" . number_format($valx['qty_stock_awal'], 2) . "</td>";//version old
            // echo "<td align='right'>" . number_format($valx['qty_stock_akhir'], 2) . "</td>";//version old

            //START VERSION OLD
            // echo "<td align='right'>" . number_format($valx['qty_stock_awal'] / $valx['konversi'], 2) . "</td>";
            // echo "<td align='right'>" . number_format($valx['qty_stock_akhir'] / $valx['konversi'], 2) . "</td>";
            //END VERSION OLD
            //START VERSION NEW
            echo "<td align='right'>" . number_format($valx['qty_stock_awal'], 2) . "</td>";
            echo "<td al ign='right'>" . number_format($valx['qty_stock_akhir'], 2) . "</td>";
            //END VERSION NEW
            echo "<td>" . $valx['no_ipp'] . "</td>";
            echo "<td>" . strtolower($valx['ket']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr>";
          echo "<td colspan='10'>Tidak ada data history</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<style media="screen">
  /* JUST COMMON TABLE STYLES... */
  .table {
    border-collapse: collapse;
    width: 100%;
  }

  .td {
    background: #fff;
    padding: 8px 16px;
  }

  .tableFixHead {
    overflow: auto;
    height: 300px;
    position: sticky;
    top: 0;
  }

  .thead .th {
    position: sticky;
    top: 0;
    z-index: 9999;
    background: #a0a0a0;
  }
</style>