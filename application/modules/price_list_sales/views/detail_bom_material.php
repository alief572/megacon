<?php
$id_product 	= (!empty($header[0]->id_product)) ? $header[0]->id_product : '0';
$variant_product 	= (!empty($header[0]->variant_product)) ? $header[0]->variant_product : '0';
$nm_product		= (!empty($GET_LEVEL4[$id_product]['nama'])) ? $GET_LEVEL4[$id_product]['nama'] : '';

$file_upload 	= (!empty($header[0]->file_upload)) ? $header[0]->file_upload : '';

$BERAT_MINUS = 0;
if (!empty($detail_additive)) {
	foreach ($detail_additive as $val => $valx) {
		$val++;
		$detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'additive'))->result();
		$PENGURANGAN_BERAT = 0;
		foreach ($detail_custom as $valx2) {
			$PENGURANGAN_BERAT += $valx2->weight * $valx2->persen / 100;
		}
		$BERAT_MINUS += $PENGURANGAN_BERAT;
	}
}

$TOTAL_PRICE_ALL = 0;
?>
<div class="box box-primary">
	<div class="box-body">
		<br>
		<table width='100%'>
			<tr>
				<th width='20%'>Product Name</th>
				<td><?= $nm_product; ?></td>
			</tr>
			<tr>
				<th>Variant Product</th>
				<td><?= $variant_product; ?></td>
			</tr>
		</table>
		<hr>
		<table class='' width='100%' border="0">
			<thead>
				<tr>
					<th class='text-left' style='width: 3%;'>#</th>
					<th class='text-left'>Material Name</th>
					<th class='text-right' style='width: 8%;'>Volume (m3)</th>
					<th class='text-right' style='width: 8%;'>Price Ref</th>
					<th class='text-right' style='width: 8%;'>Total Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$SUM_TOTAL_BERAT = 0;
				$SUM_TOTAL_PRICE = 0;
				foreach ($detail as $val => $valx) {
					$val++;
					$nm_material		= '';
					$id_material = '';

					$get_nm_material = $this->db->get_where('tr_jenis_beton_detail', ['id_detail_material' => $valx['code_material']])->row();
					if (!empty($get_nm_material)) {
						$nm_material = $get_nm_material->nm_material;
						$id_material = $get_nm_material->id_material;
					}
					$code_lv1		= (!empty($GET_LEVEL4[$valx['code_material']]['code_lv1'])) ? $GET_LEVEL4[$valx['code_material']]['code_lv1'] : '-';
					$code_lv2		= (!empty($GET_LEVEL4[$valx['code_material']]['code_lv2'])) ? $GET_LEVEL4[$valx['code_material']]['code_lv2'] : '-';
					$code_lv3		= (!empty($GET_LEVEL4[$valx['code_material']]['code_lv3'])) ? $GET_LEVEL4[$valx['code_material']]['code_lv3'] : '-';

					$price_ref      = (!empty($GET_PRICE_REF[$id_material]['up_to_value'])) ? $GET_PRICE_REF[$id_material]['up_to_value'] : 0;
					$SUM_TOTAL_BERAT += $valx['volume_m3'];
					$nm_category = strtolower(get_name('new_inventory_2', 'nama', 'code_lv2', $code_lv2));
					echo "<tr>";
					echo "<td align='left'>" . $val . "</td>";
					echo "<td>" . strtoupper($nm_material) . "</td>";
					echo "<td align='right'>" . number_format($valx['volume_m3'], 4) . "</td>";
					echo "<td align='right' class='text-green'>" . number_format($price_ref, 2) . "</td>";
					echo "<td align='right' class='text-blue'>" . number_format($price_ref * $valx['volume_m3'], 2) . "</td>";
					echo "</tr>";

					$SUM_TOTAL_PRICE += ($price_ref * $valx['volume_m3']);
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-right">Total</th>
					<th class="text-right"><?= number_format($SUM_TOTAL_BERAT, 4) ?></th>
					<th class="text-right"></th>
					<th class="text-right"><?= number_format($SUM_TOTAL_PRICE, 2) ?></th>
				</tr>
			</tfoot>
		</table>
		
		<br><br>

		<table class='' width='100%' border="0">
			<thead>
				<tr>
					<th class='text-left' style='width: 3%;'>#</th>
					<th class='text-left'>Material Lain</th>
					<th class='text-right' style='width: 8%;'>Volume (m3)</th>
					<th class='text-right' style='width: 8%;'>Price Ref</th>
					<th class='text-right' style='width: 8%;'>Total Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$SUM_TOTAL_BERAT = 0;
				$SUM_TOTAL_PRICE = 0;

				$no_material_lain = 1;
				foreach ($list_material_lain as $val) {
					$total_price = ($val->kebutuhan * $val->price_ref);
					echo '<tr>';
					echo '<td class="text-center">' . $no_material_lain . '</td>';
					echo '<td>'.$val->nm_material.'</td>';
					echo '<td class="text-right">'.number_format($val->kebutuhan, 4).'</td>';
					echo '<td class="text-right">'.number_format($val->price_ref, 2).'</td>';
					echo '<td class="text-right">'.number_format($total_price, 2).'</td>';
					echo '</tr>';

					$SUM_TOTAL_BERAT += $val->kebutuhan;
					$SUM_TOTAL_PRICE += $total_price;

					$no_material_lain++;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-right">Total</th>
					<th class="text-right"><?= number_format($SUM_TOTAL_BERAT, 4) ?></th>
					<th class="text-right"></th>
					<th class="text-right"><?= number_format($SUM_TOTAL_PRICE, 2) ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>