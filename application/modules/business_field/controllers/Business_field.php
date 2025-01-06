<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Business_field extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Master_Business_Field.View';
  protected $addPermission    = 'Master_Business_Field.Add';
  protected $managePermission = 'Master_Business_Field.Manage';
  protected $deletePermission = 'Master_Business_Field.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Business_field/Business_field_model'
    ));
    $this->template->title('Manage Material Type');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $this->template->title('Master Business Field');
    $this->template->render('index');
  }

  public function add_business_field()
  {
    $this->template->render('add');
  }

  public function edit_business_field()
  {
    $id_bidang_usaha = $this->input->post('id_bidang_usaha');

    $get_bidang_usaha = $this->db->get_where('bidang_usaha', ['id_bidang_usaha' => $id_bidang_usaha])->row();

    $this->template->set('list_bidang_usaha', $get_bidang_usaha);
    $this->template->render('add');
  }

  public function save_business_field()
  {
    $post = $this->input->post();

    $this->db->trans_begin();

    if ($post['id_bidang_usaha'] !== '') {

      $data_update = [
        'bidang_usaha' => $post['business_field'],
        'keterangan' => $post['keterangan'],
        'modified_on' => date('Y-m-d H:i:s'),
        'modified_by' => $this->auth->user_id()
      ];

      $update_business_field = $this->db->update('bidang_usaha', $data_update, ['id_bidang_usaha' => $post['id_bidang_usaha']]);
      if (!$update_business_field) {
        $this->db->trans_rollback();

        print_r($this->db->error($update_business_field));
        exit;
      }
    } else {
      $data_insert = [
        'bidang_usaha' => $post['business_field'],
        'keterangan' => $post['keterangan'],
        'created_on' => date('Y-m-d H:i:s'),
        'created_by' => $this->auth->user_id()
      ];

      $insert_business_field = $this->db->insert('bidang_usaha', $data_insert);
      if (!$insert_business_field) {
        $this->db->trans_rollback();

        print_r($this->db->error($insert_business_field));
        exit;
      }
    }

    if ($this->db->trans_status() === false) {
      $this->db->trans_rollback();
      $valid = 0;
      $msg = 'Save data failed !';
    } else {
      $this->db->trans_commit();
      $valid = 1;
      $msg = 'Save data Success !';
    }

    echo json_encode([
      'status' => $valid,
      'msg' => $msg
    ]);
  }

  public function delete_business_field()
  {
    $id_bidang_usaha = $this->input->post('id_bidang_usaha');

    $this->db->trans_begin();

    $delete_bidang_usaha = $this->db->delete('bidang_usaha', ['id_bidang_usaha' => $id_bidang_usaha]);

    if ($this->db->trans_status() === false) {
      $this->db->trans_rollback();
      $valid = 0;
      $msg = 'Delete data failed !';
    } else {
      $this->db->trans_commit();
      $valid = 1;
      $msg = 'Delete data Success !';
    }

    echo json_encode([
      'status' => $valid,
      'msg' => $msg
    ]);
  }

  public function get_data_bf()
  {
    $this->Business_field_model->get_data_bf();
  }
}
