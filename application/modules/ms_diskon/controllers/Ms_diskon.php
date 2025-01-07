<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2022, Syamsudin
 *
 * This is controller for Master diskon
 */

class Ms_diskon extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Master_Discount.View';
	protected $addPermission  	= 'Master_Discount.Add';
	protected $managePermission = 'Master_Discount.Manage';
	protected $deletePermission = 'Master_Discount.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib'));
		$this->load->model(array(
			'ms_diskon/Ms_diskon_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Manage Data Supplier');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Ms_diskon_model->get_data_diskon();
		$this->template->set('results', $data);
		$this->template->title('Discount');
		$this->template->render('index');
	}


	public function AddDiskon($id = null)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');

		if ($id !== null) {
			$get_data = $this->db->query("SELECT a.*, b.nm_lengkap FROM ms_diskon a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.id = '" . $id . "'")->row();
			$get_data_approve_by = $this->db->get_where('ms_diskon_approve_by', ['id_diskon' => $id])->result();
			$get_user = $this->db->query('SELECT id_user, nm_lengkap FROM users')->result();

			$this->template->set('results', [
				'data_diskon' => $get_data,
				'data_diskon_approve_by' => $get_data_approve_by,
				'list_user' => $get_user
			]);

			$this->template->page_icon('fa fa-pencil');
			$this->template->title('Edit Discount');
		} else {
			$this->template->page_icon('fa fa-plus');
			$this->template->title('Add Discount');
		}
		$this->template->render('adddiskon');
	}

	function GetProduk()
	{
		$loop = $_GET['jumlah'];

		$user = $this->db->query("SELECT a.* FROM users as a ")->result();

		echo "
		<tr id='tr_$loop'>
			<td>$loop</td>";
		echo	"
			<td id='tingkatan_$loop'><input type='text' align='right' class='form-control input-sm' id='used_tingkatan_$loop' required name='dt[$loop][tingkatan]'></td>
            <td id='keterangan_$loop'><input type='text' align='right' class='form-control input-sm' id='used_keterangan_$loop' required name='dt[$loop][keterangan]'></td>
            <td id='diskon_awal_$loop'><input type='text' align='right' class='form-control input-sm' id='used_diskon_awal_$loop' required name='dt[$loop][diskon_awal]' value='0'></td>
            <td id='diskon_akhir_$loop'><input type='text' align='right' class='form-control input-sm' id='used_diskon_akhir_$loop' required name='dt[$loop][diskon_akhir]' value='0'></td>
            <td>
				<table class='w-100 list_approve_by_".$loop."'>

				</table>
				<select id='used_user_$loop' name='dt[$loop][user]' data-no='$loop' class='form-control select' required>
					<option value=''>-Pilih-</option>";
		foreach ($user as $user) {
			echo "<option value='$user->id_user'>$user->nm_lengkap</option>";
		}
		echo	"</select>
			<button type='button' class='btn btn-sm btn-success add_approve_by' data-no='" . $loop . "'>
				<i class='fa fa-plus'></i> Add Approve By
			</button>
			</td>
			<td align='center'>
                <button type='button' class='btn btn-sm btn-danger' title='Hapus Data' data-role='qtip' onClick='return HapusItem($loop);'><i class='fa fa-close'></i></button>
             </td>
			
		</tr>
		";
	}


	public function SaveNewDiskon()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();


		$this->db->trans_begin();

		$numb1 = 1;
		$dt = array();
		$dt_approve_by = array();
		if (isset($post['id_diskon'])) {
			$this->db->delete('ms_diskon_approve_by', ['id_diskon' => $post['id_diskon']]);
			if(isset($post['dta_'.$numb1.'_id'])) {
				foreach($post['dta_'.$numb1.'_id'] as $item_approve_by) {
					$get_nm_karyawan = $this->db->get_where('users', ['id_user' => $item_approve_by])->row();
					$nm_karyawan = (!empty($get_nm_karyawan)) ? $get_nm_karyawan->nm_lengkap : '';
					$dt_approve_by[] = [
						'id_diskon' => $post['id_diskon'],
						'id_karyawan' => $item_approve_by,
						'nm_karyawan' => $nm_karyawan,
						'created_by' => $this->auth->user_id(),
						'created_date' => date('Y-m-d H:i:s')
					];
					$numb1++;
				}
			}
			$this->db->insert_batch('ms_diskon_approve_by', $dt_approve_by);
			$this->db->update('ms_diskon', [
				'tingkatan' => $post['tingkatan'],
				'keterangan' => $post['keterangan'],
				'diskon_awal' => $post['diskon_awal'],
				'diskon_akhir' => $post['diskon_akhir'],
				'modified_on' => date('Y-m-d H:i:s'),
				'modified_by' => $this->auth->user_id()
			], ['id' => $post['id_diskon']]);
		} else {
			foreach ($_POST['dt'] as $used) {
				if (!empty($used['tingkatan'])) {
					
					$id_diskon = $this->Ms_diskon_model->generate_id_diskon($numb1);
					if(isset($post['dta_'.$numb1.'_id'])) {
						foreach($post['dta_'.$numb1.'_id'] as $item_approve_by) {
							$get_nm_karyawan = $this->db->get_where('users', ['id_user' => $item_approve_by])->row();
							$nm_karyawan = (!empty($get_nm_karyawan)) ? $get_nm_karyawan->nm_lengkap : '';
							$dt_approve_by[] = [
								'id_diskon' => $id_diskon,
								'id_karyawan' => $item_approve_by,
								'nm_karyawan' => $nm_karyawan,
								'created_by' => $this->auth->user_id(),
								'created_date' => date('Y-m-d H:i:s')
							];
						}
					}
					$dt[] =  array(
						'id' => $id_diskon,
						'tingkatan'		    => $used['tingkatan'],
						'keterangan'		    => $used['keterangan'],
						'diskon_awal'	    => $used['diskon_awal'],
						'diskon_akhir'	    => $used['diskon_akhir'],
						'approved_by'	    => $used['user'],
						'created_on'			=> date('Y-m-d H:i:s'),
						'created_by'			=> $this->auth->user_id()
					);

					$numb1++;
				}
			}

			// print_r($dt_approve_by);
			// exit;

			$this->db->insert_batch('ms_diskon', $dt);
			$this->db->insert_batch('ms_diskon_approve_by', $dt_approve_by);
		}


		


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Gagal Save Item. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Item. invenThanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}


	public function editDiskon($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$diskon = $this->db->get_where('ms_diskon', array('id' => $id))->result();
		$lvl1 = $this->db->get('ms_inventory_type');
		$lvl2 = $this->db->get('ms_top');
		$data = [
			'diskon' => $diskon,
			'lvl1' => $lvl1,
			'lvl2' => $lvl2
		];
		$this->template->set('results', $data);
		$this->template->title('Diskon');
		$this->template->render('editdiskon');
	}

	public function saveEditDiskon()
	{
		$this->auth->restrict($this->editPermission);
		$post = $this->input->post();
		// print_r($post);
		// exit();
		$this->db->trans_begin();
		$data = [
			'id_type'		    => $post['level1'],
			'id_top'		    => $post['top'],
			'nilai_diskon'      => $post['nilai'],
			'modified_on'		=> date('Y-m-d H:i:s'),
			'modified_by'		=> $this->auth->user_id()
		];

		$this->db->where('id', $post['id_diskon'])->update("ms_diskon", $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Gagal Save Data. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Data. Thanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}

	public function deleteDiskon()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');
		$data = [
			'deleted' 		=> '1',
			'deleted_by' 	=> $this->auth->user_id()
		];

		$this->db->trans_begin();

		$del_header = $this->db->where('id', $id)->update("ms_diskon", $data);

		$data_approve_by = [
			'deleted_by' => $this->auth->user_id(),
			'deleted_date' => date('Y-m-d H:i:s')
		];
		$del_approve_by = $this->db->update('ms_diskon_approve_by', $data_approve_by, ['id_diskon' => $id]);
		

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Gagal Save Item. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Item. Thanks ...',
				'status'	=> 1
			);
		}
		echo json_encode($status);
	}

	public function get_karyawan_name() {
		$id_karyawan = $this->input->post('id_karyawan');

		$get_karyawan = $this->db->get_where('users', ['id_user' => $id_karyawan])->row();

		$nm_karyawan = (!empty($get_karyawan)) ? $get_karyawan->nm_lengkap : '';

		echo json_encode([
			'nm_karyawan' => $nm_karyawan
		]);
	}
}
