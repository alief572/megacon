<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mat_plan_base_on_produksi extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Material_Planning_Base_On_Produksi.View';
  protected $addPermission    = 'Material_Planning_Base_On_Produksi.Add';
  protected $managePermission = 'Material_Planning_Base_On_Produksi.Manage';
  protected $deletePermission = 'Material_Planning_Base_On_Produksi.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array('Mat_plan_base_on_produksi/mat_plan_base_on_produksi_model'));
    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');

    history("View index material planning base on production");
    $this->template->title('Material Planning / Based On Production');
    $this->template->render('index');
  }

  public function data_side_material_planning()
  {
    $this->mat_plan_base_on_produksi_model->data_side_material_planning();
  }

  public function detail()
  {
    $so_number   = $this->input->post('so_number');

    // $detail   = $this->db->get_where('material_planning_base_on_produksi_detail', array('so_number' => $so_number))->result_array();

    $this->db->select('a.*, b.satuan_lainnya as nominal_kg, c.nama as nm_material');
    $this->db->from('material_planning_base_on_produksi_detail a');
    $this->db->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material');
    $this->db->join('new_inventory_4 c', 'c.code_lv4 = b.id_material');
    $this->db->group_by('a.id');
    $this->db->where('a.so_number', $so_number);
    $detail = $this->db->get()->result_array();

    $data = [
      'so_number' => $so_number,
      'detail' => $detail,
      'GET_LEVEL4'   => get_inventory_lv4()
    ];
    $this->template->set('results', $data);
    $this->template->render('detail', $data);
  }

  public function material_planning($so_number = null)
  {
    if ($this->input->post()) {
      $data         = $this->input->post();
      $session      = $this->session->userdata('app_session');

      $so_number        = $data['so_number'];
      $tgl_dibutuhkan    = (!empty($data['tgl_dibutuhkan'])) ? date('Y-m-d', strtotime($data['tgl_dibutuhkan'])) : NULL;
      $detail            = $data['detail'];


      $ArrPlanningDetail = [];
      $SUM_USE = 0;
      $SUM_PROPOSE = 0;
      $ArrStock = [];
      if (!empty($detail)) {
        foreach ($detail as $key => $value) {
          //Planning
          $use_stock = str_replace(',', '', $value['use_stock']);
          $propose = str_replace(',', '', $value['propose']);

          $ArrPlanningDetail[$key]['id'] = $value['id'];
          $ArrPlanningDetail[$key]['stock_free'] = $value['stock_free'];
          $ArrPlanningDetail[$key]['min_stock'] = $value['min_stok'];
          $ArrPlanningDetail[$key]['max_stock'] = $value['max_stok'];
          $ArrPlanningDetail[$key]['use_stock'] = $use_stock;
          $ArrPlanningDetail[$key]['propose_purchase'] = $propose;
          $ArrPlanningDetail[$key]['note'] = $value['note'];

          $ArrStock[$key]['id'] = $value['code_material'];
          $ArrStock[$key]['qty'] = $use_stock;

          $SUM_USE += $use_stock;
          $SUM_PROPOSE += $propose;
        }
      }

      $ArrHeader = array(
        'tgl_dibutuhkan'  => $tgl_dibutuhkan,
        'qty_use_stok'  => $SUM_USE,
        'qty_propose'  => $SUM_PROPOSE,
        'updated_by'      => $this->id_user,
        'updated_date'    => $this->datetime
      );

      // print_r($ArrBOMDetail);
      // exit;

      $this->db->trans_start();
      $this->db->where('so_number', $so_number);
      $this->db->update('material_planning_base_on_produksi', $ArrHeader);

      if (!empty($ArrPlanningDetail)) {
        $this->db->update_batch('material_planning_base_on_produksi_detail', $ArrPlanningDetail, 'id');
      }
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $Arr_Data  = array(
          'pesan'    => 'Save gagal disimpan ...',
          'status'  => 0
        );
      } else {
        $this->db->trans_commit();
        $Arr_Data  = array(
          'pesan'    => 'Save berhasil disimpan. Thanks ...',
          'status'  => 1
        );
        // booking_warehouse($ArrStock, 1, 1, $so_number, null);
        history("Create material planning  : " . $so_number);
      }
      echo json_encode($Arr_Data);
    } else {
      $header     = $this->db
        ->select('a.*, b.due_date, c.nm_customer')
        ->join('so_internal b', 'a.so_number=b.so_number', 'left')
        ->join('customer c', 'a.id_customer=c.id_customer', 'left')
        ->get_where(
          'material_planning_base_on_produksi a',
          array(
            'a.so_number' => $so_number
          )
        )
        ->result_array();
      $detail     = $this->db
        ->select('a.*, b.satuan_lainnya as nominal_kg, c.nama as nm_material, c.max_stok, c.min_stok, c.daily_usage_qty')
        ->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material', 'left')
        ->join('new_inventory_4 c', 'b.id_material = c.code_lv4', 'left')
        ->get_where(
          'material_planning_base_on_produksi_detail a',
          array(
            'a.so_number' => $so_number
          )
        )
        ->result_array();
        // echo $this->db->last_query();
        // die();

      $data = [
        'so_number' => $so_number,
        'header' => $header,
        'detail' => $detail,
        'GET_LEVEL4'   => get_inventory_lv4(),
        'GET_STOK_PUSAT' => getStokMaterial(1)
      ];

      $this->template->title('Set Material Planning');
      $this->template->render('material_planning', $data);
    }
  }

  public function process_booking()
  {
    $data       = $this->input->post();
    $so_number  = $data['so_number'];

    $ArrHeader = array(
      'booking_by'      => $this->id_user,
      'booking_date'    => $this->datetime
    );

    // $detail = $this->db->get_where('material_planning_base_on_produksi_detail', array('so_number' => $so_number))->result_array();

    $this->db->select('a.use_stock, b.id_material');
    $this->db->from('material_planning_base_on_produksi_detail a');
    $this->db->from('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material');
    $this->db->where('a.so_number', $so_number);
    $detail = $this->db->get()->result_array();

    $ArrStock = [];
    if (!empty($detail)) {
      foreach ($detail as $key => $value) {
        $ArrStock[$key]['id'] = $value['id_material'];
        $ArrStock[$key]['qty'] = $value['use_stock'];
      }
    }
    // print_r($ArrBOMDetail);
    // exit;

    $this->db->trans_start();
    $this->db->where('so_number', $so_number);
    $this->db->update('material_planning_base_on_produksi', $ArrHeader);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Process Failed !',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Process Success !',
        'status'  => 1
      );

      if (!empty($ArrStock)) {
        booking_warehouse($ArrStock, 1, 1, $so_number, null);
      }
      history("booking material planning  : " . $so_number);
    }
    echo json_encode($Arr_Data);
  }

  public function add_tgl($id=null,$so=null){ 
      if(empty($id)){
        $this->auth->restrict($this->addPermission);
      }
      else{
        $this->auth->restrict($this->managePermission);
      }   
      if($this->input->post()){
        $post = $this->input->post();

        $id   = $post['id'];
        // $status = (!empty($id))?$post['status']:1;
        $id_cust = $post['id_customer'];
        //
        // $get_nm_cust = $this->db->get_where('master_customer',array('id_customer' => $id_cust))->result();
        // $nm_customer = $this->Kredit_limit_model->get_nm_customer($id_cust);
        // $get_rev = $this->Kredit_limit_model->get_rev($id);
        // if($get_rev == Null || $get_rev == '' || $get_rev == '0'){
        //   $get_revisi = 0;
        // }else{
        //   $get_revisi = $get_rev;
        // }
        // // print_r($get_nm_cust);
        // // die();
        // $kurs = $post['kurs'];
        // $credit_limit = $post['credit_limit'];

        $last_by    = (!empty($id))?'updated_by':'created_by';
        $last_date  = (!empty($id))?'updated_date':'created_date';
        $label      = (!empty($id))?'Edit':'Add';

        if(empty($id)){
        $dataProcess = [
          'id_customer'     => $id_cust,
          'nm_customer'     =>  $nm_customer,
          'kurs'    => $kurs,
          // 'credit_limit'   => $credit_limit,
          'credit_limit'    => str_replace('.', '', $credit_limit),
          'rev' => 0,
          'status_approval' => 0,
          $last_by    => $this->id_user,
          $last_date  => $this->datetime
        ];
        }else{
          $dataProcess = [
            'id_customer'     => $id_cust,
            'nm_customer'     =>  $nm_customer,
            'kurs'    => $kurs,
            // 'credit_limit'   => $credit_limit,
            'credit_limit'    => str_replace('.', '', $credit_limit),
            'rev' => @$get_revisi,
            'status_approval' => 0,
            $last_by    => $this->id_user,
            $last_date  => $this->datetime
          ];
        }

        $this->db->trans_start();
          if(empty($id)){
            $this->db->insert('ms_credit_limit',$dataProcess);
          }
          else{
            $this->db->where('id',$id);
            $this->db->update('ms_credit_limit',$dataProcess);
          }
        // echo $this->db->last_query();
        // die();
        $this->db->trans_complete();  

        if($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          $status = array(
            'pesan'   =>'Failed process data!',
            'status'  => 0
          );
        } else {
          $this->db->trans_commit();
          $status = array(
            'pesan'   =>'Success process data!',
            'status'  => 1
          );
          history($label." credit limit: ".$id);
        }
        echo json_encode($status);
      }
      else{
        $listDataHeader           = $this->db->get_where('material_planning_base_on_produksi',array('so_number' => $so))->result();
        $listDataDetail           = $this->db->get_where('material_planning_base_on_produksi_detail',array('id' => $id))->result();
        // $data_customer      = $this->db->get_where('master_customers',array('sts_aktif'=>'Y'))->result_array(); 
        // $data_kurs          = $this->db->get('ms_kurs')->result_array();

        $data = [
          'listDataHeader' => $listDataHeader,
          'listDataDetail' => $listDataDetail
          // 'data_customer' => $data_customer,
          // 'data_kurs' => $data_kurs
        ];
        $this->template->set($data);
        $this->template->render('material_planning');
      }
    }

  public function plan_detail_tgl($id = null,$so = null)
  {
      $header   = $this->db
        ->select('a.*, b.due_date, c.nm_customer')
        ->join('so_internal b', 'a.so_number=b.so_number', 'left')
        ->join('customer c', 'a.id_customer=c.id_customer', 'left')
        ->get_where(
          'material_planning_base_on_produksi a',
          array(
            'a.so_number' => $so
          )
        )
        ->result_array();
      $detail   = $this->db
        ->select('a.*, b.satuan_lainnya as nominal_kg, c.nama as nm_material, c.max_stok, c.min_stok, c.daily_usage_qty')
        ->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material', 'left')
        ->join('new_inventory_4 c', 'b.id_material = c.code_lv4', 'left')
        ->get_where(
          'material_planning_base_on_produksi_detail a',
          array(
            'a.so_number' => $so
          )
        )
        ->result_array();
        // echo $this->db->last_query();
        // die();

      $data = [
        'so_number' => $so,
        'header' => $header,
        'detail' => $detail
        // 'GET_LEVEL4'   => get_inventory_lv4(),
        // 'GET_STOK_PUSAT' => getStokMaterial(1)
      ];

      $this->template->title('Set Detail Plan Tanggal');
      $this->template->render('material_planning_detail_tgl', $data);
    
  }

}
