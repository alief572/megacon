<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Price_ref_raw_material extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Price_Ref_Raw_Material.View';
  protected $addPermission    = 'Price_Ref_Raw_Material.Add';
  protected $managePermission = 'Price_Ref_Raw_Material.Manage';
  protected $deletePermission = 'Price_Ref_Raw_Material.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Price_ref_raw_material/Price_ref_raw_material_model'
    ));
    $this->template->title('Manage Material Jenis');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $where = [
      'deleted_date' => NULL,
      'category' => 'material'
    ];
    $listData = $this->Price_ref_raw_material_model->get_data($where);

    $get_satuan = $this->db->get_where('ms_satuan', array('deleted' => 'N'))->result();
    
    $list_satuan = array();

    foreach($get_satuan as $item_satuan) {
      $list_satuan[$item_satuan->id] = $item_satuan->code;
    }

    $data = [
      'result' =>  $listData,
      'list_satuan' => $list_satuan
    ];

    history("View index price reference raw materials");
    $this->template->set($data);
    $this->template->title('Price Reference >> Raw Materials');
    $this->template->render('index');
  }

  public function add($id = null)
  {
    if (empty($id)) {
      $this->auth->restrict($this->addPermission);
    } else {
      $this->auth->restrict($this->managePermission);
    }
    if ($this->input->post()) {
      $post = $this->input->post();
      $generate_id = $this->Price_ref_raw_material_model->generate_id();

      $id                   = $post['id'];
      $code_lv4             = $post['code_lv4'];
      $action_app           = $post['action_app'];
      $status_reject        = $post['status_reject'];


      // $price_ref_use_after          = str_replace(',','',$post['price_ref_use_after']);
      // $price_ref_use_after_usd          = str_replace(',','',$post['price_ref_use_after_usd']);
      $price_ref_expired_use_after  = $post['price_ref_expired_use_after'];

      $getPurchase = $this->db->get_where('new_inventory_4', array('id' => $id))->result_array();

      if ($action_app == '1') {
        $dataProcess = [
          'price_ref'             => $getPurchase[0]['price_ref_new'],
          'price_ref_high'        => $getPurchase[0]['price_ref_high_new'],
          'price_ref_usd'         => $getPurchase[0]['price_ref_new_usd'],
          'price_ref_high_usd'    => $getPurchase[0]['price_ref_high_new_usd'],
          'price_ref_date'        => $getPurchase[0]['price_ref_new_date'],
          'price_ref_expired'     => $getPurchase[0]['price_ref_new_expired'],

          'price_ref_new'             => NULL,
          'price_ref_high_new'        => NULL,
          'price_ref_new_usd'         => NULL,
          'price_ref_high_new_usd'    => NULL,
          'price_ref_new_date'        => NULL,
          'price_ref_new_expired'     => NULL,

          'price_ref_use'             => str_replace(',', '', $post['up_to_value']),
          'price_ref_use_usd'         => str_replace(',', '', $post['up_to_value_usd']),
          'price_ref_date_use'        => date('Y-m-d'),
          'price_ref_expired_use'     => $price_ref_expired_use_after,

          'decision' => $post['decision'],
          'up_to_persen' => $post['up_to'],
          'up_to_value' => str_replace(',', '', $post['up_to_value']),
          'up_to_value_usd' => str_replace(',', '', $post['up_to_value_usd']),

          'status_reject'   => $status_reject,
          'status_app'      => 'N',
          'app_by'          => $this->id_user,
          'app_date'        => $this->datetime
        ];
      } else {
        $dataProcess = [
          'status_reject'   => $status_reject,
          'status_app'      => 'N',
          'app_by'          => $this->id_user,
          'app_date'        => $this->datetime
        ];
      }

      $this->db->trans_start();
      $this->db->where('id', $id);
      $this->db->update('new_inventory_4', $dataProcess);
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $status  = array(
          'pesan'    => 'Failed process data!',
          'status'  => 0
        );
      } else {
        $this->db->trans_commit();
        $status  = array(
          'pesan'    => 'Success process data!',
          'status'  => 1
        );
        history("Update price supplier raw material: " . $code_lv4);
      }
      echo json_encode($status);
    } else {
      $listData = $this->db->get_where('new_inventory_4', array('id' => $id))->result();

      $get_satuan_beli = $this->db->get_where('ms_satuan', ['id' => $listData[0]->satuan_beli])->row();
      $satuan_beli = (!empty($get_satuan_beli)) ? $get_satuan_beli->code : '';

      $data = [
        'listData' => $listData,
        'satuan_beli' => $satuan_beli
      ];
      $this->template->set($data);
      $this->template->render('add');
    }
  }
}
