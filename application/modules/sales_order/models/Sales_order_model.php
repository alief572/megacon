<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Sales_order_model extends BF_Model
{

	public function __construct()
	{
		parent::__construct();

		$this->ENABLE_ADD     = has_permission('Sales_Order_New.Add');
		$this->ENABLE_MANAGE  = has_permission('Sales_Order_New.Manage');
		$this->ENABLE_VIEW    = has_permission('Sales_Order_New.View');
		$this->ENABLE_DELETE  = has_permission('Sales_Order_New.Delete');
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

	public function get_data_group($table, $where_field = '', $where_value = '', $where_group = '')
	{
		if ($where_field != '' && $where_value != '') {
			$query = $this->db->group_by($where_group)->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_json_sales_order()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		// $Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_sales_order(
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

			if ($row['approve'] == '1') {
				$status = '<div class="badge badge-info">SO</div>';
			} else {
				if ($row['req_app'] == '1' && $row['approve'] == '0') {
					$status = '<div class="badge badge-warning">Waiting Approval SO</div>';
				} else {
					$status = '<div class="badge bg-purple">Waiting SO</div>';
				}
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>" . $nomor . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['no_so'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['nm_customer'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['no_penawaran'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . $row['project'] . "</div>";
			$nestedData[]	= "<div align='center'>" . $row['update_by'] . "</div>";
			$nestedData[]	= "<div align='center'>" . date('d F Y', strtotime($row['tgl_penawaran'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . $row['no_revisi'] . "</div>";
			$nestedData[]	= "<div align='left'>" . $status . "</div>";
			$edit	= "";
			$delete	= "";
			$print	= "";
			$approve = "";
			$download = "";
			$cekSO = $this->db->query("SELECT * FROM delivery_header WHERE no_so='" . $row['no_so'] . "' ")->result_array();

			if (empty($cekSO)) {
				$edit	= "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/deal_so/' . $row['no_penawaran'] . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'>Deal SO</a>";
			}

			// if(empty($cekSO)){
			// 	$delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete' data-no_so='".$row['no_so']."'><i class='fa fa-trash'></i></button>";
			// }

			$print	= "&nbsp;<a href='" . site_url($this->uri->segment(1) . '/print_sales_order/' . $row['no_so']) . "' class='btn btn-sm bg-purple' target='_blank' title='Print Sales Order' data-role='qtip'>Print SO</a>";
			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $row['no_penawaran']])->result();
			if (count($check_so) < 1) {
				$print = '';
			}

			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $row['no_penawaran']])->row();

			$ajukan = '';
			if ($row['status'] == '2' && count($check_so) > 0) {
				$ajukan = '<button type="button" class="btn btn-sm btn-success ajukan" data-id_so="' . $row['no_so'] . '">Ajukan</button>';
			}

			$view = "";
			if (count($check_so) > 0) {
				$view = "<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-no_so='" . $row['no_so'] . "'>View</button>";
			}

			$buttons = $view . ' ' . $edit . ' ' . $print . ' ' . $ajukan;
			if ($row['req_app'] == '1') {
				$buttons = $view . ' ' . $print;
			}

			$nestedData[]	= "<div align='left'>
												" . $buttons . "
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

	public function get_query_json_sales_order($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{

		$sql = "
			SELECT
      			(@row:=@row+1) AS nomor,
				a.*,
        		b.nm_customer,
				IF(a.modified_by != '' OR a.modified_by IS NOT NULL, e.nm_lengkap, d.nm_lengkap) AS update_by,
				c.no_so,
				f.req_app,
				f.approve
			FROM
				tr_penawaran a
				LEFT JOIN customer b ON b.id_customer = a.id_customer
				LEFT JOIN tr_sales_order c ON c.no_penawaran = a.no_penawaran
				LEFT JOIN users d ON d.id_user = a.created_by
				LEFT JOIN users e ON e.id_user = a.modified_by
				LEFT JOIN tr_sales_order f ON f.no_penawaran = a.no_penawaran
		   	WHERE 1=1 AND (a.status = '2' OR a.status = '3') AND a.created_by = '" . $this->auth->user_id() . "' AND (
				c.no_so LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				b.nm_customer LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				a.project LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				IF(a.modified_by != '' OR a.modified_by IS NOT NULL, e.nm_lengkap, d.nm_lengkap) LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				a.tgl_penawaran LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR 
				a.no_revisi LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR 
				IF(f.approve = '1', 'SO', 'Waiting SO') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
	        ) 
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'no_so',
			2 => 'no_so_manual',
			3 => 'name_customer',
			4 => 'delivery_date'
		);

		$sql .= " ORDER BY a.created_on DESC ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function get_json_approval_sales_order()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		// $Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_approval_sales_order(
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

			if ($row['approve'] == '1') {
				$status = '<div class="badge badge-info">SO</div>';
				// print($status);
				// exit;
			} else {
				if ($row['req_app'] == '1' && $row['approve'] == '0') {
					$status = '<div class="badge badge-warning">Waiting Approval SO</div>';
				} else {
					$status = '<div class="badge bg-purple">Waiting SO</div>';
				}
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>" . $nomor . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['no_so'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['nm_customer'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['no_penawaran'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . $row['project'] . "</div>";
			$nestedData[]	= "<div align='center'>" . $row['update_by'] . "</div>";
			$nestedData[]	= "<div align='center'>" . date('d F Y', strtotime($row['tgl_penawaran'])) . "</div>";
			$nestedData[]	= "<div align='left'>" . $row['no_revisi'] . "</div>";
			$nestedData[]	= "<div align='left'>" . $status . "</div>";
			$edit	= "";
			$delete	= "";
			$print	= "";
			$approve = "";
			$download = "";
			$cekSO = $this->db->query("SELECT * FROM delivery_header WHERE no_so='" . $row['no_so'] . "' ")->result_array();

			if (empty($cekSO)) {
				$edit	= "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/deal_so/' . $row['no_penawaran'] . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'>Deal SO</a>";
			}

			// if(empty($cekSO)){
			// 	$delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete' data-no_so='".$row['no_so']."'><i class='fa fa-trash'></i></button>";
			// }

			$print	= "&nbsp;<a href='" . site_url($this->uri->segment(1) . '/print_sales_order/' . $row['no_so']) . "' class='btn btn-sm bg-purple' target='_blank' title='Print Sales Order' data-role='qtip'>Print SO</a>";
			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $row['no_penawaran']])->result();
			if (count($check_so) < 1) {
				$print = '';
			}

			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $row['no_penawaran']])->row();

			$ajukan = '';
			if ($row['status'] == '2' && count($check_so) > 0) {
				$ajukan = '<button type="button" class="btn btn-sm btn-success ajukan" data-id_so="' . $row['no_so'] . '">Ajukan</button>';
			}

			$view = "";
			if (count($check_so) > 0) {
				$view = "<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-no_so='" . $row['no_so'] . "'>View</button>";
			}

			$approval = '';
			if ($row['approve'] == '0') {
				$approval = '<button type="button" class="btn btn-sm btn-primary approval" data-id_so="' . $row['no_so'] . '">Approval</button>';
			}

			$buttons = $view . ' ' . $edit . ' ' . $print . ' ' . $ajukan;
			if ($row['req_app'] == '1') {
				$buttons = $approval . ' ' . $view . ' ' . $print;
			}

			$nestedData[]	= "<div align='left'>
												" . $buttons . "
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

	public function get_query_json_approval_sales_order($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{

		$sql = "
			SELECT
      			(@row:=@row+1) AS nomor,
				a.*,
        		b.nm_customer,
				IF(a.modified_by != '' OR a.modified_by IS NOT NULL, e.nm_lengkap, d.nm_lengkap) AS update_by,
				c.no_so,
				f.req_app,
				f.approve
			FROM
				tr_penawaran a
				LEFT JOIN customer b ON b.id_customer = a.id_customer
				LEFT JOIN tr_sales_order c ON c.no_penawaran = a.no_penawaran
				LEFT JOIN users d ON d.id_user = a.created_by
				LEFT JOIN users e ON e.id_user = a.modified_by
				LEFT JOIN tr_sales_order f ON f.no_penawaran = a.no_penawaran
		   	WHERE 1=1 AND (a.status = '2' OR a.status = '3') AND f.req_app = '1' AND (
				c.no_so LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				b.nm_customer LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				a.project LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				IF(a.modified_by != '' OR a.modified_by IS NOT NULL, e.nm_lengkap, d.nm_lengkap) LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR
				a.tgl_penawaran LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR 
				a.no_revisi LIKE '%" . $this->db->escape_like_str($like_value) . "%' OR 
				IF(f.approve = '1', 'SO', 'Waiting SO') LIKE '%" . $this->db->escape_like_str($like_value) . "%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'no_so',
			2 => 'no_so_manual',
			3 => 'name_customer',
			4 => 'delivery_date'
		);

		$sql .= " ORDER BY  " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function get_data_so()
	{
		$draw = $this->input->post('draw');
		$length = $this->input->post('length');
		$start = $this->input->post('start');
		$search = $this->input->post('search');

		$this->db->select('a.*, b.nm_customer, c.no_so, f.req_app, f.approve');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_customer = a.id_customer', 'left');
		$this->db->join('tr_sales_order c', 'c.no_penawaran = a.no_penawaran', 'left');
		$this->db->join('tr_sales_order f', 'f.no_penawaran = a.no_penawaran', 'left');
		$this->db->group_start();
		$this->db->where('a.status', 2);
		$this->db->or_where('a.status', 3);
		$this->db->group_end();
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d %M %Y")', $search['value'], 'both');
			$this->db->or_like('c.no_so', $search['value'], 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$this->db->limit($length, $start);
		$get_data = $this->db->get();

		$this->db->select('a.*, b.nm_customer, c.no_so, f.req_app, f.approve');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_customer = a.id_customer', 'left');
		$this->db->join('tr_sales_order c', 'c.no_penawaran = a.no_penawaran', 'left');
		$this->db->join('tr_sales_order f', 'f.no_penawaran = a.no_penawaran', 'left');
		$this->db->group_start();
		$this->db->where('a.status', 2);
		$this->db->or_where('a.status', 3);
		$this->db->group_end();
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d %M %Y")', $search['value'], 'both');
			$this->db->or_like('c.no_so', $search['value'], 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$get_data_all = $this->db->get();

		$hasil = array();

		$no = 1;
		foreach ($get_data->result() as $data) {
			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $data->no_penawaran])->result();

			$print = '&nbsp;<a href="sales_order/print_sales_order/' . $data->no_so . '" class="btn btn-sm bg-purple" data-no_so="' . $data->no_so . '">Print SO</a>';
			if (count($check_so) < 1) {
				$print = '';
			}

			$edit    = "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/deal_so/' . $data->no_penawaran . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'>Deal SO</a>";
			if (!$this->ENABLE_MANAGE) {
				$edit = '';
			}

			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $data->no_penawaran])->row();

			$ajukan = '';
			if ($data->status == '2' && count($check_so) > 0 && $this->ENABLE_MANAGE) {
				$ajukan = '<button type="button" class="btn btn-sm btn-success ajukan" data-id_so="' . $data->no_so . '">Ajukan</button>';
			}

			$view = "";
			if (count($check_so) > 0) {
				$view = "<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-no_so='" . $data->no_so . "'>View</button>";
			}

			$approval = '';
			if ($data->req_app > 0 && $this->ENABLE_MANAGE) {
				$approval = '<button type="button" class="btn btn-sm btn-primary approval" data-id_so="' . $data->no_so . '">Approval</button>';
			}

			$buttons = $view . ' ' . $edit . ' ' . $print . ' ' . $ajukan . ' ' . $approval;
			if ($data->req_app == '1') {
				$buttons = $view . ' ' . $print;
				if ($data->approve < 1 && $this->uri->segment(2) == 'approval') {
					$buttons .= ' ' . $approval;
				}
			}

			if ($data->approve == '1') {
				$status = '<div class="badge bg-green">SO</div>';
			} else {
				if ($data->req_app == '1' && $data->approve == '0') {
					$status = '<div class="badge bg-blue">Waiting Approval SO</div>';
				} else {
					$status = '<div class="badge bg-yellow">Waiting SO</div>';
				}
			}

			$hasil[] = [
				'no' => $no,
				'tgl' => date('d F Y', strtotime($data->tgl_penawaran)),
				'so_no' => $data->no_so,
				'customer' => $data->nm_customer,
				'quotation_no' => $data->no_penawaran,
				'project' => $data->project,
				'rev' => $data->no_revisi,
				'status' => $status,
				'option' => $buttons
			];

			$no++;
		}

		echo json_encode([
			'draw' => intval($draw),
			'recordsTotal' => $get_data_all->num_rows(),
			'recordsFiltered' => $get_data_all->num_rows(),
			'data' => $hasil
		]);
	}

	public function get_data_so_app()
	{
		$draw = $this->input->post('draw');
		$length = $this->input->post('length');
		$start = $this->input->post('start');
		$search = $this->input->post('search');
		$approval = $this->input->post('approval');

		$this->db->select('a.*, b.nm_customer, c.no_so, f.req_app as req_app_so, f.approve');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_customer = a.id_customer', 'left');
		$this->db->join('tr_sales_order c', 'c.no_penawaran = a.no_penawaran', 'left');
		$this->db->join('tr_sales_order f', 'f.no_penawaran = a.no_penawaran');
		$this->db->where('f.req_app', 1);
		if($approval == '1') {
			$this->db->group_start();
			$this->db->where('f.approve', 0);
			$this->db->or_where('f.approve', null);
			$this->db->group_end();
		}
		$this->db->group_start();
		$this->db->where('a.status', 2);
		$this->db->or_where('a.status', 3);
		$this->db->group_end();
		if (!empty($search['value'])) {
			$this->db->group_start();
			$this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d %M %Y")', $search['value'], 'both');
			$this->db->or_like('c.no_so', $search['value'], 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$this->db->limit($length, $start);
		$get_data = $this->db->get();

		$this->db->select('a.*, b.nm_customer, c.no_so, f.req_app as req_app_so, f.approve');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_customer = a.id_customer', 'left');
		$this->db->join('tr_sales_order c', 'c.no_penawaran = a.no_penawaran', 'left');
		$this->db->join('tr_sales_order f', 'f.no_penawaran = a.no_penawaran');
		$this->db->where('f.req_app', 1);
		if($approval == '1') {
			$this->db->group_start();
			$this->db->where('f.approve', 0);
			$this->db->or_where('f.approve', null);
			$this->db->group_end();
		}
		$this->db->group_start();
		$this->db->where('a.status', 2);
		$this->db->or_where('a.status', 3);
		$this->db->group_end();
		if (!empty($search['value'])) {
			$this->db->group_start();
			$this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d %M %Y")', $search['value'], 'both');
			$this->db->or_like('c.no_so', $search['value'], 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$get_data_all = $this->db->get();

		$hasil = array();

		$no = 1;
		foreach ($get_data->result() as $data) {
			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $data->no_penawaran])->result();

			$print = '&nbsp;<a href="sales_order/print_sales_order/' . $data->no_so . '" class="btn btn-sm bg-purple" data-no_so="' . $data->no_so . '">Print SO</a>';
			if (count($check_so) < 1) {
				$print = '';
			}

			$edit    = "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/deal_so/' . $data->no_penawaran . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'>Deal SO</a>";
			if (!$this->ENABLE_MANAGE) {
				$edit = '';
			}

			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $data->no_penawaran])->row();

			$ajukan = '';
			if ($data->status == '2' && count($check_so) > 0 && $this->ENABLE_MANAGE) {
				$ajukan = '<button type="button" class="btn btn-sm btn-success ajukan" data-id_so="' . $data->no_so . '">Ajukan</button>';
			}

			$view = "";
			if (count($check_so) > 0) {
				$view = "<button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-no_so='" . $data->no_so . "'>View</button>";
			}

			$approval_btn = '';
			if ($approval == '1' && $data->approve !== '1' && $this->ENABLE_MANAGE) {
				$approval_btn = '<button type="button" class="btn btn-sm btn-primary approval" data-id_so="' . $data->no_so . '">Approval</button>';
			}

			$buttons = $view . ' ' . $print . ' ' . $approval_btn;
			// if ($data->req_app_so == '1') {
			// 	$buttons = $view . ' ' . $print;
			// 	if ($approval == '1' && $data->approve !== '1' && $this->uri->segment(2) == 'approval') {
			// 		$buttons .= ' ' . $approval_btn;
			// 	}
			// }

			if ($data->approve == '1') {
				$status = '<div class="badge bg-green">SO</div>';
			} else {
				if ($data->req_app_so == '1' && $data->approve == '0') {
					$status = '<div class="badge bg-blue">Waiting Approval SO</div>';
				} else {
					$status = '<div class="badge bg-yellow">Waiting SO</div>';
				}
			}

			$hasil[] = [
				'no' => $no,
				'tgl' => date('d F Y', strtotime($data->tgl_penawaran)),
				'so_no' => $data->no_so,
				'customer' => $data->nm_customer,
				'quotation_no' => $data->no_penawaran,
				'project' => $data->project,
				'rev' => $data->no_revisi,
				'status' => $status,
				'option' => $buttons
			];

			$no++;
		}

		echo json_encode([
			'draw' => intval($draw),
			'recordsTotal' => $get_data_all->num_rows(),
			'recordsFiltered' => $get_data_all->num_rows(),
			'data' => $hasil
		]);
	}
}
