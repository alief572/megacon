<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bom_model extends BF_Model
{

	public function __construct()
	{
		parent::__construct();

		$this->ENABLE_ADD     = has_permission('BOM.Add');
		$this->ENABLE_MANAGE  = has_permission('BOM.Manage');
		$this->ENABLE_VIEW    = has_permission('BOM.View');
		$this->ENABLE_DELETE  = has_permission('BOM.Delete');
	}

	public function get_data($table, $where_field = '', $where_value = '')
	{
		if ($where_field != '' && $where_value != '') {
			$query = $this->db->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_data_where_array($table, $where)
	{
		if (!empty($where)) {
			$query = $this->db->get_where($table, $where);
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_data_group($table, $where_field = '', $where_value = '', $where_group = '')
	{
		if ($where_field != '' && $where_value != '') {
			$query = $this->db->group_by($where_group)->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_json_bom()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		// $Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_bom(
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
		);
		$totalData			= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query					= $fetch['query'];

		$data	= array();
		$urut1  = 1;
		$urut2  = 0;
		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if ($asc_desc == 'asc') {
				$nomor = $urut1 + $start_dari;
			}
			if ($asc_desc == 'desc') {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>" . $nomor . "</div>";
			$nestedData[]	= "<div align='center'>" . $row['no_bom'] . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['nama'])) . "</div>";
			$nestedData[]   = "<div align='center'>" . number_format($row['volume_m3'], 2) . "</div>";
			$nestedData[]	= "<div align='left'>" . $row['keterangan'] . "</div>";
			$moq = (!empty($row['moq'])) ? number_format($row['moq'], 4) : '-';

			$last_create = (!empty($row['updated_by'])) ? $row['updated_by'] : $row['created_by'];
			$nestedData[]	= "<div align='left'>" . strtolower(get_name('users', 'username', 'id_user', $last_create)) . "</div>";

			$last_date = (!empty($row['updated_date'])) ? $row['updated_date'] : $row['created_date'];
			$nestedData[]	= "<div align='center'>" . date('d-M-Y H:i', strtotime($last_date)) . "</div>";
			$edit	= "";
			$delete	= "";
			$excel	= "";
			if ($this->ENABLE_MANAGE) {
				$edit	= "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/add/' . $row['no_bom'] . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
			}
			if ($this->ENABLE_DELETE) {
				$delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete work data' data-no_bom='" . $row['no_bom'] . "'><i class='fa fa-trash'></i></button>";
			}
			$excel	= "&nbsp;<a href='" . site_url($this->uri->segment(1) . '/excel_report_all_bom_detail/' . $row['no_bom']) . "' class='btn btn-sm btn-success' target='_blank' title='Excel' data-role='qtip'><i class='fa fa-file-excel-o'></i></a>";

			$nestedData[]	= "	<div align='left'>
								<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-no_bom='" . $row['no_bom'] . "'><i class='fa fa-eye'></i></button>
								" . $edit . "
								" . $delete . "
								" . $excel . "
								</div>";
			$data[] = $nestedData;
			$urut1++;
			$urut2++;
		}

		$json_data = array(
			"draw"            	=> intval($requestData['draw']),
			"recordsTotal"    	=> intval($totalData),
			"recordsFiltered" 	=> intval($totalFiltered),
			"data"            	=> $data
		);

		echo json_encode($json_data);
	}

	public function get_query_json_bom($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{

		$sql = "SELECT
					(@row:=@row+1) AS nomor,
					a.*,
					b.nama
				FROM
					bom_header a LEFT JOIN new_inventory_4 b ON a.id_product=b.code_lv4,
					(SELECT @row:=0) r
				WHERE 1=1 AND a.deleted_date IS NULL AND 
					(
						no_bom LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR b.nama LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR a.variant_product LIKE '%" . $this->db->escape_like_str($like_value) . "%'
					)
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'b.nama',
			2 => 'variant_product'
		);

		$sql .= " ORDER BY a.no_bom DESC,  " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}
}
