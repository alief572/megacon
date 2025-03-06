<?php
class Approval_quotation_model extends BF_Model
{
// aa
	public function __construct()
	{
		parent::__construct();
	}

	public function approval_quotation_1($no_penawaran)
	{
		$session = $this->session->userdata('app_session');

		$Cust = $this->db->query("SELECT a.* FROM customer a")->result();
		$User = $this->db->query("SELECT a.* FROM users a")->result();
		$pic_cust = $this->db->query("SELECT a.* FROM customer_pic a WHERE a.nm_pic <> ''")->result();

		$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $session['id_user']])->result();

		if ($no_penawaran !== null) {
			$get_penawaran = $this->db->query('SELECT a.*, b.nm_lengkap FROM tr_penawaran a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.no_penawaran = "' . $no_penawaran . '"')->row();
			$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $no_penawaran])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$action_app = 'Approve';
			if ($get_penawaran->req_app2 == '1' || $get_penawaran->req_app3 == '1') {
				$this->db->select('a.tingkatan');
				$this->db->from('ms_diskon a');
				$this->db->where('a.deleted', 0);
				$this->db->order_by('a.id', 'asc');
				$this->db->limit(1, 1);
				$get_next_approval = $this->db->get()->row();

				$action_app = 'Request Approval ' . $get_next_approval->tingkatan;
			}

			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$get_other_item = $this->db->query("
				SELECT
					a.code_lv4 as id_product,
					a.nama as nm_product,
					a.code as product_code
				FROM
					new_inventory_4 a
				WHERE
					a.category = 'material' AND
					a.deleted_by IS NULL
				
				UNION ALL
		
				SELECT
					a.id as id_product,
					a.stock_name as nm_product,
					a.id_stock as product_code
				FROM
					accessories a 
				WHERE
					a.deleted_by IS NULL
			")->result();

			$get_list_item_others = $this->db->get_where('tr_penawaran_other_item', ['id_penawaran' => $no_penawaran])->result();

			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'nm_sales' => $session['nm_lengkap'],
				'data_penawaran' => $get_penawaran,
				'data_penawaran_detail' => $get_penawaran_detail,
				'app_quote' => 1,
				'action_app' => $action_app,
				'list_top' => $get_top,
				'curr' => $get_penawaran->currency,
				'list_other_cost' => $get_other_cost,
				'list_other_item' => $get_other_item,
				'list_another_item' => $get_list_item_others,
				'approval_num' => 1
			]);
		} else {
			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();
			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'list_penawaran_detail' => $get_penawaran_detail,
				'nm_sales' => $session['nm_lengkap'],
				'list_top' => $get_top
			]);
		}
		// $this->template->set('results', $get_penawaran);
		$this->template->title('Approval Quotation');
		$this->template->render('approval_quotation');
	}

	public function approval_quotation_2($no_penawaran)
	{
		$session = $this->session->userdata('app_session');

		$Cust = $this->db->query("SELECT a.* FROM customer a")->result();
		$User = $this->db->query("SELECT a.* FROM users a")->result();
		$pic_cust = $this->db->query("SELECT a.* FROM customer_pic a WHERE a.nm_pic <> ''")->result();

		$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $session['id_user']])->result();

		if ($no_penawaran !== null) {
			$get_penawaran = $this->db->query('SELECT a.*, b.nm_lengkap FROM tr_penawaran a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.no_penawaran = "' . $no_penawaran . '"')->row();
			$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $no_penawaran])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$action_app = 'Approve';
			if ($get_penawaran->req_app3 == '1') {
				$this->db->select('a.tingkatan');
				$this->db->from('ms_diskon a');
				$this->db->where('a.deleted', 0);
				$this->db->order_by('a.id', 'asc');
				$this->db->limit(1, 2);
				$get_next_approval = $this->db->get()->row();

				$action_app = 'Request Approval ' . $get_next_approval->tingkatan;
			}

			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$get_other_item = $this->db->query("
				SELECT
					a.code_lv4 as id_product,
					a.nama as nm_product,
					a.code as product_code
				FROM
					new_inventory_4 a
				WHERE
					a.category = 'material' AND
					a.deleted_by IS NULL
				
				UNION ALL
		
				SELECT
					a.id as id_product,
					a.stock_name as nm_product,
					a.id_stock as product_code
				FROM
					accessories a 
				WHERE
					a.deleted_by IS NULL
			")->result();

			$get_list_item_others = $this->db->get_where('tr_penawaran_other_item', ['id_penawaran' => $no_penawaran])->result();

			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'nm_sales' => $session['nm_lengkap'],
				'data_penawaran' => $get_penawaran,
				'data_penawaran_detail' => $get_penawaran_detail,
				'app_quote' => 1,
				'action_app' => $action_app,
				'list_top' => $get_top,
				'curr' => $get_penawaran->currency,
				'list_other_cost' => $get_other_cost,
				'list_other_item' => $get_other_item,
				'list_another_item' => $get_list_item_others,
				'approval_num' => 2
			]);
		} else {
			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();
			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'list_penawaran_detail' => $get_penawaran_detail,
				'nm_sales' => $session['nm_lengkap'],
				'list_top' => $get_top
			]);
		}
		// $this->template->set('results', $get_penawaran);
		$this->template->title('Approval Quotation');
		$this->template->render('approval_quotation');
	}

	public function approval_quotation_3($no_penawaran)
	{
		$session = $this->session->userdata('app_session');

		$Cust = $this->db->query("SELECT a.* FROM customer a")->result();
		$User = $this->db->query("SELECT a.* FROM users a")->result();
		$pic_cust = $this->db->query("SELECT a.* FROM customer_pic a WHERE a.nm_pic <> ''")->result();

		$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $session['id_user']])->result();

		if ($no_penawaran !== null) {
			$get_penawaran = $this->db->query('SELECT a.*, b.nm_lengkap FROM tr_penawaran a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.no_penawaran = "' . $no_penawaran . '"')->row();
			$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $no_penawaran])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$action_app = 'Approve';
			// if ($get_penawaran->req_app2 == '1' || $get_penawaran->req_app3 == '1') {
			// 	$this->db->select('a.tingkatan');
			// 	$this->db->from('ms_diskon a');
			// 	$this->db->where('a.deleted', 0);
			// 	$this->db->order_by('a.id', 'asc');
			// 	$this->db->limit(1, 3);
			// 	$get_next_approval = $this->db->get()->row();

			// 	$action_app = 'Request Approval '.$get_next_approval->tingkatan;
			// }

			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$get_other_item = $this->db->query("
				SELECT
					a.code_lv4 as id_product,
					a.nama as nm_product,
					a.code as product_code
				FROM
					new_inventory_4 a
				WHERE
					a.category = 'material' AND
					a.deleted_by IS NULL
				
				UNION ALL
		
				SELECT
					a.id as id_product,
					a.stock_name as nm_product,
					a.id_stock as product_code
				FROM
					accessories a 
				WHERE
					a.deleted_by IS NULL
			")->result();

			$get_list_item_others = $this->db->get_where('tr_penawaran_other_item', ['id_penawaran' => $no_penawaran])->result();

			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'nm_sales' => $session['nm_lengkap'],
				'data_penawaran' => $get_penawaran,
				'data_penawaran_detail' => $get_penawaran_detail,
				'app_quote' => 1,
				'action_app' => $action_app,
				'list_top' => $get_top,
				'curr' => $get_penawaran->currency,
				'list_other_cost' => $get_other_cost,
				'list_other_item' => $get_other_item,
				'list_another_item' => $get_list_item_others,
				'approval_num' => 3
			]);
		} else {
			$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();
			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'list_penawaran_detail' => $get_penawaran_detail,
				'nm_sales' => $session['nm_lengkap'],
				'list_top' => $get_top
			]);
		}
		// $this->template->set('results', $get_penawaran);
		$this->template->title('Approval Quotation');
		$this->template->render('approval_quotation');
	}

	public function view_quotation($no_penawaran)
	{
		$session = $this->session->userdata('app_session');

		$Cust = $this->db->query("SELECT a.* FROM customer a")->result();
		$User = $this->db->query("SELECT a.* FROM users a")->result();
		$pic_cust = $this->db->query("SELECT a.* FROM customer_pic a WHERE a.nm_pic <> ''")->result();

		$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $session['id_user']])->result();
		$get_top = $this->db->get_where('list_help', ['group_by' => 'top invoice'])->result();

		if ($no_penawaran !== null) {
			$get_penawaran = $this->db->query('SELECT a.*, b.nm_lengkap FROM tr_penawaran a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.no_penawaran = "' . $no_penawaran . '"')->row();
			$get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $no_penawaran])->result();

			$get_other_cost = $this->db->get_where('tr_penawaran_other_cost', ['id_penawaran' => $no_penawaran])->result();

			$get_other_item = $this->db->query("
			SELECT
				a.code_lv4 as id_product,
				a.nama as nm_product,
				a.code as product_code
			FROM
				new_inventory_4 a
			WHERE
				a.category = 'material' AND
				a.deleted_by IS NULL
			
			UNION ALL

			SELECT
				a.id as id_product,
				a.stock_name as nm_product,
				a.id_stock as product_code
			FROM
				accessories a 
			WHERE
				a.deleted_by IS NULL
		")->result();

			$get_list_item_others = $this->db->get_where('tr_penawaran_other_item', ['id_penawaran' => $no_penawaran])->result();

			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'nm_sales' => $session['nm_lengkap'],
				'data_penawaran' => $get_penawaran,
				'data_penawaran_detail' => $get_penawaran_detail,
				'list_top' => $get_top,
				'curr' => $get_penawaran->currency,
				'list_other_cost' => $get_other_cost,
				'list_other_item' => $get_other_item,
				'list_another_item' => $get_list_item_others
			]);
		} else {
			$this->template->set('results', [
				'customers' => $Cust,
				'user' => $User,
				'pic_cust' => $pic_cust,
				'list_penawaran_detail' => $get_penawaran_detail,
				'nm_sales' => $session['nm_lengkap'],
				'list_top' => $get_top
			]);
		}
		$this->template->render('view_quotation');
	}

	public function get_quotation()
	{
		$draw = $this->input->post('draw');
		$length = $this->input->post('length');
		$start = $this->input->post('start');
		$search = $this->input->post('search');

		$get_user = $this->db->get_where('users', $this->auth->user_id())->row();



		$this->db->select('a.id, a.id_diskon');
		$this->db->from('ms_diskon_approve_by a');
		$this->db->where('a.id_karyawan', $this->auth->user_id());
		$this->db->where('a.deleted_by', null);
		$this->db->order_by('a.id_diskon', 'asc');
		$get_approve_step = $this->db->get()->result();
		// echo $this->db->last_query();
		$ids_diskon = array_column($get_approve_step, 'id_diskon');
		$no_step = [];
		$no = 1;
		// foreach ($get_approve_step as $item_step) {
		// 	$get_diskon = $this->db->get_where('ms_diskon', array('id' => $item_step->id_diskon))->row();
		// 	if (!empty($get_diskon)) {
		// 		$no_step[] = $no;
		// 		// print_r($no_step);
		// 	}

		// 	$no++;
		// }
		if (!empty($ids_diskon)) {
			$this->db->where_in('id', $ids_diskon);
			$get_diskon = $this->db->get('ms_diskon')->result_array();
		
			if (!empty($get_diskon)) {
				$no_step = range(1, count($get_diskon)); // Membuat array [1, 2, 3, ...]
			} else {
				$no_step = [];
			}
		} else {
			$no_step = [];
		}

		// $tingkatan = COUNT($get_approve_step);
		$tingkatan = $no_step;
		// echo "<br>";
		// echo $no_step;
		// echo "<br>";
		$this->db->select('a.no_penawaran, a.tgl_penawaran, a.project, a.status, a.req_app1, a.app_1, a.req_app2, a.app_2, a.req_app3, a.app_3, b.nm_customer');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_customer = a.id_customer', 'left');
		$this->db->where('a.status', 1);
		if (!empty($no_step)) {
			$noo = 1;
			$this->db->group_start();
			foreach ($no_step as $step) {
				if ($noo == 1) {
					// $this->db->where('a.req_app' . $step, 1);
					$this->db->or_where("a.req_app{$step}", 1);
				} else {
					// $this->db->or_where('a.req_app' . $step, 1);
					$this->db->or_where("a.req_app{$step}", 1);
				}

				$noo++;
			}

			$this->db->group_end();
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d-%M-%Y")', $search['value'], 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->or_like('a.no_revisi', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$this->db->limit($length, $start);
		$get_data = $this->db->get();
		// echo $this->db->last_query();

		$this->db->select('a.no_penawaran, a.tgl_penawaran, a.status, a.project, a.req_app1, a.app_1, a.req_app2, a.app_2, a.req_app3, a.app_3, b.nm_customer');
		$this->db->from('tr_penawaran a');
		$this->db->join('customer b', 'b.id_Customer = a.id_customer', 'left');
		$this->db->where('a.status', 1);
		if (!empty($no_step)) {
			$noo = 1;
			$this->db->group_start();
			foreach ($no_step as $step) {
				if ($noo == 1) {
					$this->db->where('a.req_app' . $step, 1);
				} else {
					$this->db->or_where('a.req_app' . $step, 1);
				}

				$noo++;
			}

			$this->db->group_end();
		}
		if (!empty($search)) {
			$this->db->group_start();
			// $this->db->like('DATE_FORMAT(a.tgl_penawaran, "%d-%M-%Y")', $search['value'], 'both');
			// $this->db->where("DATE_FORMAT(a.tgl_penawaran, '%d-%M-%Y') LIKE", "%" . $search['value'] . "%", false);
			$this->db->like('a.tgl_penawaran', date('Y-m-d', strtotime($search['value'])), 'both');
			$this->db->or_like('b.nm_customer', $search['value'], 'both');
			$this->db->or_like('a.no_penawaran', $search['value'], 'both');
			$this->db->or_like('a.project', $search['value'], 'both');
			$this->db->or_like('a.no_revisi', $search['value'], 'both');
			$this->db->group_end();
		}
		$this->db->order_by('a.created_on', 'desc');
		$get_data_all = $this->db->get();

		$hasil = [];

		$no = 0;
		// echo $this->db->last_query();
		// if ($get_data->num_rows() > 0) {
		// 	foreach ($get_data->result_array() as $item) {
		// 		print_r($item);
		// 	}
		// } else {
		// 	echo "Data tidak ditemukan";
		// }
		// print_r($get_data->result_array());
		// die();
		foreach ($get_data->result_array() as $item) {
			$no++;

			$Status = '';

			if ($item['status'] == 0) {
				$Status = "<span class='badge bg-yellow'>Draft</span>";
			} elseif ($item['status'] == 1) {



				$num_approval = 'Staff Sales';
				if ($item['req_app2'] == '1' && $item['app_1'] == '1') {
					$num_approval = 'Manager Sales';
				}
				if ($item['req_app3'] == '1' && $item['app_2'] == '1') {
					$num_approval = 'Direktur';
				}

				$Status = "<span class='badge bg-blue'>Waiting Approval " . $num_approval . "</span>";
			} elseif ($item['status'] == '2') {
				$Status = "<span class='badge bg-green'>Waiting SO</span>";
			} elseif ($item['status'] == '3') {
				$Status = "<span class='badge bg-purple'>SO Approved</span>";
			} elseif ($item['status'] == '4') {
				$Status = "<span class='badge bg-red'>Loss</span>";
			}

			$btn_edit = '<a href="quotation/modal_detail_invoice/' . $item['no_penawaran'] . '" class="btn btn-sm btn-success">Edit</a>';
			$check_so = $this->db->get_where('tr_sales_order', ['no_penawaran' => $item['no_penawaran']])->num_rows();
			if ($check_so > 0) {
				$btn_edit = '';
			}

			$btn_view = '<a href="approval_quotation/view_quotation/' . $item['no_penawaran'] . '" class="btn btn-sm btn-info">View</a>';



			$check_disc_penawaran = $this->db->query('SELECT MAX(diskon_persen) AS max_disc_persen FROM tr_penawaran_detail WHERE no_penawaran = "' . $item['no_penawaran'] . '"')->row();

			$get_disc = $this->db->query('SELECT * FROM ms_diskon WHERE deleted = 0 ORDER BY diskon_awal ASC')->result();

			$tingkatan = 0;

			$no_awd = 0;
			foreach ($get_disc as $list_disc) {
				$no_awd++;
				// if ($tingkatan == '') {
				// 	if ($check_disc_penawaran->max_disc_persen >= $list_disc->diskon_awal && $check_disc_penawaran->max_disc_persen <= $list_disc->diskon_akhir) {
				// 		$tingkatan = $list_disc->tingkatan;
				// 	} else {
				// 		if ($check_disc_penawaran->max_disc_persen >= $list_disc->diskon_awal && $list_disc->diskon_akhir == 0) {
				// 			$tingkatan = $list_disc->tingkatan;
				// 		}
				// 	}
				// }

				if ($check_disc_penawaran->max_disc_persen >= $list_disc->diskon_awal && $check_disc_penawaran->max_disc_persen <= $list_disc->diskon_akhir) {
					$tingkatan = $no_awd;
				}
			}

			$btn_ajukan = '<a href="javascript:void(0);" class="btn btn-sm btn-success ajukan" data-id="' . $item['no_penawaran'] . '" data-status="' . $item['status'] . '" data-tingkatan="' . $tingkatan . '">Ajukan</a>';

			if ($tingkatan == 1) {
				$btn_ajukan = '';
			}

			$btn_approve = '<a href="javascript:void(0);" class="btn btn-sm btn-success approve" data-id="' . $item['no_penawaran'] . '">Approve</a>';

			if ($btn_ajukan !== '') {
				$btn_approve = '';
			}

			$btn_approve2 = '';
			// if ($item['req_app1'] == 1 && $item['app_1'] == null) {
			// 	$check_disc_approval = $this->db->get_where('ms_diskon_approve_by', array('id_diskon' => 'MDISC-01-25000001', 'id_karyawan' => $this->auth->user_id()))->num_rows();

			// 	if ($check_disc_approval > 0) {
			// 		$btn_approve2 = '<button type="button" class="btn btn-sm btn-primary approve_sales" data-id="' . $item['no_penawaran'] . '">Approve</button>';
			// 	}
			// }

			// $btn_print = '<a href="' . base_url() . 'quotation/print_quotation/' . $item['no_penawaran'] . '" class="btn btn-sm bg-purple" target="_blank">Print</a>';

			$btn_print = '<a href="javascript:void(0);" class="btn btn-sm bg-purple print_quotation" data-id_penawaran="' . $item['no_penawaran'] . '">Print</a>';

			// $btn_print = '<a href="javascript:"></a>';

			if ($item['status'] == '1' || $item['status'] == '0') {
				$btn_print = '';
			}

			// if ($item['req_app1'] == '1') {
			// 	if ($item['app_1'] !== '1') {
			// 		$btn_print = '';
			// 	}
			// }
			// if ($item['req_app2'] == '1') {
			// 	if ($item['app_2'] !== '1') {
			// 		$btn_print = '';
			// 	}
			// }
			// if ($item['req_app3'] == '1') {
			// 	if ($item['app_3'] !== '1') {
			// 		$btn_print = '';
			// 	}
			// }

			$btn_loss = '<a href="javascript:void(0);" class="btn btn-sm btn-danger loss" data-id="' . $item['no_penawaran'] . '">Loss</a>';

			$buttons = $btn_edit . ' ' . $btn_view . ' ' . $btn_ajukan . ' ' . $btn_approve . ' ' . $btn_print . ' ' . $btn_loss;
			if ($item['status'] == '1') {
				$buttons = $btn_view . ' ' . $btn_print . ' ' . $btn_approve2;
			}
			if ($item['status'] == '2') {
				$buttons = $btn_edit . ' ' . $btn_view . ' ' . $btn_print;
			}
			if ($item['status'] == '3') {
				$buttons = $btn_view . ' ' . $btn_print;
			}

			$link_approval = base_url('approval_quotation/approval_quotation_1/' . $item['no_penawaran']);
			if ($item['app_1'] !== null) {
				$link_approval = base_url('approval_quotation/approval_quotation_2/' . $item['no_penawaran']);
			}
			if ($item['app_2'] !== null) {
				$link_approval = base_url('approval_quotation/approval_quotation_3/' . $item['no_penawaran']);
			}

			$buttons .= '<a class="btn btn-sm btn-primary" href="' . $link_approval . '">Approval</a>';

			$hasil[] = [
				'no' => $no,
				'tgl' => date('d F Y', strtotime($item['tgl_penawaran'])),
				'customer' => strtoupper($item['nm_customer']),
				'quotation_no' => $item['no_penawaran'],
				'project' => $item['project'],
				'rev' => $item['revisi'],
				'status' => $Status,
				'option' => $buttons
			];
		}

		echo json_encode([
			'draw' => intval($draw),
			'recordsTotal' => $get_data_all->num_rows(),
			'recordsFiltered' => $get_data_all->num_rows(),
			'data' => $hasil
		]);
	}
}
