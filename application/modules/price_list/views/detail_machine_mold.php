<div class="box box-primary">
    <div class="box-body">
        <div class="form-group row">
            <div class="col-md-12">
                <table width='100%' class="table table-sm table-bordered">
                    <thead class="thead">
                        <tr>
                            <td colspan='7'><b>PRODUCT NAME :</b> <?= strtoupper(get_name('new_inventory_4', 'nama', 'code_lv4', $id_product)); ?></td>
                        </tr>
                        <tr class='bg-blue'>
                            <th class='text-center th' width='3%'>#</th>
                            <th class='text-center th' width='25%'>Area</th>
                            <th class='text-center th' width='25%'>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // if (!empty($header[0]->id_time)) {
                            foreach ($header as $item_header) {
                                $q_header_test = $this->db->query("SELECT * FROM cycletime_detail_header WHERE id_time='" . $item_header->id_time . "'")->result_array();
                                $nox = 0;
                                $ttl_rate = 0;
                                foreach ($q_header_test as $val2 => $val2x) {
                                    $nox++;

                                    echo "<tr>";
                                    echo "<td align='center'>" . $nox . "</td>";
                                    echo "<td align='left'><b>" . strtoupper(get_name('ms_costcenter', 'nama_costcenter', 'id_costcenter', $val2x['costcenter'])) . "</b></td>";
                                    echo "<td></td>";
                                    echo "</tr>";

                                    if($title == 'Machine') {

                                        $this->db->select('a.*, b.cost_m3');
                                        $this->db->from('cycletime_detail_machine a');
                                        $this->db->join('rate_machine b', 'b.kd_mesin = a.id_machine', 'left');
                                        $this->db->where('a.id_time', $item_header->id_time);
                                        $this->db->where('a.id_costcenter', $val2x['id_costcenter']);
                                        $this->db->group_by('a.id');
                                        $get_list_machine = $this->db->get()->result();

                                        foreach($get_list_machine as $item_machine) {
                                            echo '<tr>';
                                            echo '<td></td>';
                                            echo '<td align="left">Machine : '.$item_machine->nm_machine.'</td>';
                                            echo '<td align="right">'.number_format($item_machine->cost_m3, 2).'</td>';
                                            echo '</tr>';

                                            $ttl_rate += $item_machine->cost_m3;
                                        }
                                    } else {
                                        $this->db->select('b.cost_m3, c.nm_asset');
                                        $this->db->from('cycletime_detail_detail a');
                                        $this->db->join('rate_mold b', 'b.kd_mesin = a.mould', 'left');
                                        $this->db->join('asset c', 'c.kd_asset = b.kd_mesin', 'left');
                                        $this->db->where('a.id_time', $item_header->id_time);
                                        $this->db->where('a.id_costcenter', $val2x['id_costcenter']);
                                        $this->db->group_by('a.id');
                                        $get_list_mold = $this->db->get()->result();

                                        foreach($get_list_mold as $item_mold) {
                                            echo '<tr>';
                                            echo '<td></td>';
                                            echo '<td align="left">Mold : '.$item_mold->nm_asset.'</td>';
                                            echo '<td align="right">'.number_format($item_mold->cost_m3, 2).'</td>';
                                            echo '</tr>';

                                            $ttl_rate += $item_mold->cost_m3;
                                        }
                                    }
                                }
                            }
                        // }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right">
                                TOTAL RATE
                            </th>
                            <th class="text-right">
                                <?= number_format($ttl_rate, 2) ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>