<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval_quotation extends Admin_Controller
{

	//Permissionaa

	protected $viewPermission   = "Approval_Quotation.View";
	protected $addPermission    = "Approval_Quotation.Add";
	protected $managePermission = "Approval_Quotation.Manage";
	protected $deletePermission = "Approval_Quotation.Delete";

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('Mpdf', 'upload', 'Image_lib'));
		$this->load->model('Approval_quotation/Approval_quotation_model');
		$this->template->title('Approval Quotation');
		$this->template->page_icon('fa fa-building-o');
		date_default_timezone_set('Asia/Bangkok');
	}


	public function index()
	{
		$this->template->page_icon('fa fa-list');
		$get_curr = $this->db->get_where('mata_uang', ['deleted' => null])->result();
		$this->template->set('list_curr', $get_curr);

		$this->template->title('Approval Quotation');
		$this->template->render('index');
	}

	public function approval_quotation_1($no_penawaran)
	{
		$this->Approval_quotation_model->approval_quotation_1($no_penawaran);
	}
	public function approval_quotation_2($no_penawaran)
	{
		$this->Approval_quotation_model->approval_quotation_2($no_penawaran);
	}
	public function approval_quotation_3($no_penawaran)
	{
		$this->Approval_quotation_model->approval_quotation_3($no_penawaran);
	}

	public function view_quotation($no_penawaran)
	{
		$this->Approval_quotation_model->view_quotation($no_penawaran);
	}

	public function save_approval()
	{
		$session = $this->session->userdata('app_session');

		$post = $this->input->post();

		$get_penawaran = $this->db->get_where('tr_penawaran', ['no_penawaran' => $post['no_surat']])->row_array();

		$this->db->trans_begin();

		$action = 'Approve';
		if ($post['pilih_action'] == '0') {
			$action = 'Reject';
		}

		$app_1 = '';
		$app_2 = '';
		$app_3 = '';
		$status = $get_penawaran->status;
		$keterangan_approve_reject = '';
		$keterangan_approve = '';
		if ($action == 'Reject') {
			$keterangan_approve = $post['keterangan_approve'];
		}

		$app_quote = 1;

		$next_approval = ($post['approval_num'] + 1);

		$next_approval_num = $get_penawaran['req_app' . $next_approval];

		if ($action == 'Approve') {
			$this->db->select('a.*');
			$this->db->from('tr_req_quot a');
			$this->db->where('a.id_quotation', $post['no_surat']);
			$this->db->where('a.approved', 'N');
			$this->db->order_by('a.level', 'asc');
			$get_req_quot_num = $this->db->get()->num_rows();

			if ($get_req_quot_num < 2) {
				$this->db->select('a.*');
				$this->db->from('tr_req_quot a');
				$this->db->where('a.id_quotation', $post['no_surat']);
				$this->db->where('a.approved', 'N');
				$this->db->order_by('a.level', 'asc');
				$this->db->limit(1);
				$get_req_quot = $this->db->get()->row();

				$this->db->update('tr_req_quot', array('approved' => 'Y', 'approved_by' => $this->auth->user_id(), 'approved_date' => date('Y-m-d H:i:s')), array('id' => $get_req_quot->id));

				$this->db->update('tr_penawaran', array('status' => 2), array('no_penawaran' => $post['no_surat']));
			} else {
				$this->db->select('a.*');
				$this->db->from('tr_req_quot a');
				$this->db->where('a.id_quotation', $post['no_surat']);
				$this->db->where('a.approved', 'N');
				$this->db->order_by('a.level', 'asc');
				$this->db->limit(1);
				$get_req_quot = $this->db->get()->row();

				$this->db->update('tr_req_quot', array('approved' => 'Y', 'approved_by' => $this->auth->user_id(), 'approved_date' => date('Y-m-d H:i:s')), array('id' => $get_req_quot->id));
			}
		} else {
			$this->db->update('tr_penawaran', array('status' => 0), array('no_penawaran' => $post['no_surat']));
			$this->db->delete('tr_req_quot', array('id_penawaran' => $post['no_surat']));
		}


		if ($this->db->trans_status() === FALSE) {
			$valid = 0;
			$msg = 'Maaf, penawaran gagal di ' . $action;
			$this->db->trans_rollback();
		} else {
			$valid = 1;
			$msg = 'Selamat, penawaran telah berhasil di ' . $action;
			$this->db->trans_commit();
		}

		echo json_encode([
			'status' => $valid,
			'pesan' => $msg,
			'app_quote' => $app_quote
		]);
	}

	public function get_quotation()
	{
		$this->Approval_quotation_model->get_quotation();
	}
}
