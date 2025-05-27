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
      // print_r($data);
      // die();
      $so_number        = $data['so_number'];
      $tgl_dibutuhkan    = (!empty($data['tgl_dibutuhkan'])) ? date('Y-m-d', strtotime($data['tgl_dibutuhkan'])) : NULL;
      $bulan = (!empty($data['bulan']) && !empty($data['tahun'])) ? date('m', strtotime($data['tahun'] . '-' . $data['bulan'] . '-01')) : NULL;
      $tahun = (!empty($data['bulan']) && !empty($data['tahun'])) ? date('Y', strtotime($data['tahun'] . '-' . $data['bulan'] . '-01')) : NULL;
      $detail = $data['detail'];
      // print_r($bulan.'||'.$tahun);
      // die();


      $ArrPlanningDetail = [];
      $SUM_USE = 0;
      $SUM_PROPOSE = 0;
      $ArrStock = [];
      if (!empty($detail)) {
        foreach ($detail as $key => $value) {
          //Planning
          // $use_stock = str_replace(',', '', $value['use_stock']);//version old
          // $propose = str_replace(',', '', $value['propose']);//version old
          $total_estimasi_material = str_replace(',', '', $value['total_estimasi_material']);

          $ArrPlanningDetail[$key]['id'] = $value['id'];
          $ArrPlanningDetail[$key]['stock_free'] = $value['stock_free'];
          $ArrPlanningDetail[$key]['min_stock'] = $value['min_stok'];
          $ArrPlanningDetail[$key]['max_stock'] = $value['max_stok'];
          // $ArrPlanningDetail[$key]['use_stock'] = $use_stock;//version old
          // $ArrPlanningDetail[$key]['propose_purchase'] = $propose;//version old
          // $ArrPlanningDetail[$key]['note'] = $value['note'];//version old
          $ArrPlanningDetail[$key]['total_estimasi_material'] = $total_estimasi_material;
          $ArrPlanningDetail[$key]['daily_use_qty'] = $value['daily_use_qty'];
          $ArrPlanningDetail[$key]['sisa_kecukupan'] = $value['sisa_kecukupan'];
          $ArrPlanningDetail[$key]['estimasi_sekali_kirim'] = $value['estimasi_sekali_kirim'];
          $ArrPlanningDetail[$key]['cycle_order'] = $value['cycle_order'];
          $ArrPlanningDetail[$key]['updated_by'] = $this->id_user;
          $ArrPlanningDetail[$key]['updated_date'] = $this->datetime;

          $ArrStock[$key]['id'] = $value['code_material'];
          // $ArrStock[$key]['qty'] = $use_stock;

          // $SUM_USE += $use_stock;
          // $SUM_PROPOSE += $propose;
        }
      }

      //start version old
      // $ArrHeader_old = array(
      //   'tgl_dibutuhkan'  => $tgl_dibutuhkan,
      //   'qty_use_stok'  => $SUM_USE,
      //   'qty_propose'  => $SUM_PROPOSE,
      //   'updated_by'      => $this->id_user,
      //   'updated_date'    => $this->datetime
      // );
      //end version old
      $ArrHeader = array(
        'periode_bulan'  => $bulan,
        'periode_tahun' => $tahun,
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

  public function plan_detail_tgl($id = null,$so = null,$type=null)
  {
      // print_r($type);
      // die();
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
        // $detail_tgl   = $this->db
        // ->select('a.*, b.satuan_lainnya as nominal_kg, c.nama as nm_material, c.max_stok, c.min_stok, c.daily_usage_qty')
        // ->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material', 'left')
        // ->join('new_inventory_4 c', 'b.id_material = c.code_lv4', 'left')
        // ->get_where(
        //   'material_planning_base_on_produksi_detail_tgl a',
        //   array(
        //     'a.so_number' => $so,
        //     'a.deleted_by' => NULL
        //   )
        // )
        // ->result_array();
        $detail_tgl = $this->db
        ->select('a.*, b.satuan_lainnya as nominal_kg, c.nama as nm_material, c.max_stok, c.min_stok, c.daily_usage_qty')
        ->from('material_planning_base_on_produksi_detail_tgl a')
        ->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.id_material', 'left')
        ->join('new_inventory_4 c', 'b.id_material = c.code_lv4', 'left')
        ->where('a.so_number', $so)
        ->where('a.id_material_planning_base_on_produksi_detail', $id)
        ->where('a.deleted_by IS NULL', null, false) // penting: false untuk raw query
        ->get()
        ->result_array();

        // echo $this->db->last_query();
        // die();

      $data = [
        'id_detail' => $id,
        'so_number' => $so,
        'header' => $header,
        'detail' => $detail,
        'detail_tgl' => $detail_tgl,
        'type' => $type
        // 'GET_LEVEL4'   => get_inventory_lv4(),
        // 'GET_STOK_PUSAT' => getStokMaterial(1)
      ];

      $this->template->title('Set Detail Plan Tanggal');
      $this->template->render('material_planning_detail_tgl', $data);
  }

  public function add_plan_date($id=null,$so=null,$id_plan_detail=null,$id_material=null){  
      // print_r($id.'||'.$so);
      // die();
      if(empty($id)){
        $this->auth->restrict($this->addPermission);
      }
      else{
        $this->auth->restrict($this->managePermission);
      }
      if($this->input->post()){
        $post = $this->input->post();
        // print_r($post);
        // echo "<br>";
        // print_r($id_plan_detail);
        // die();
        $id_plan_detail   = $post['id_detail'];
        $id   = $post['id'];
        $so_number   = $post['so_number'];
        $id_material = $post['id_material'];
        $tgl_rencana = $post['tgl_rencana'];
        $qty_kedatangan = $post['qty_kedatangan'];
        $getDataMaterial = $this->db->get_where('tr_jenis_beton_detail',array('id_detail_material' => $id_material))->row();
        $MaterialName = $getDataMaterial->nm_material;
        // $id_plan_detail = (!empty($id_plan_detail))?$id_plan_detail : NULL;
        // $nama = $post['nama'];
        // print_r($getDataMaterial->nm_material);
        // die();

        $last_by    = (!empty($id))?'updated_by':'created_by';
        $last_date  = (!empty($id))?'updated_date':'created_date';
        $label      = (!empty($id))?'Edit':'Add';

        $dataHeader = $this->db->get_where('material_planning_base_on_produksi',array('so_number' => $so))->row();
        $dataDetail = $this->db->get_where('material_planning_base_on_produksi_detail',array('id' => $id))->row();
        // $listData_tgl = null;

        $dataProcess = [
          'id_material_planning_base_on_produksi_detail' => $id_plan_detail,
          'so_number' => $so_number,
          'id_material' => $id_material,
          'name_material' => $MaterialName,
          'tgl_rencana_kedatangan' => $tgl_rencana,
          'qty_kedatangan' => $qty_kedatangan,
          $last_by    => $this->id_user,
          $last_date  => $this->datetime
        ];

        $dataProcessUpdate = [
          'tgl_rencana_kedatangan' => $tgl_rencana,
          'qty_kedatangan' => $qty_kedatangan,
          $last_by    => $this->id_user,
          $last_date  => $this->datetime
        ];

        // print_r($dataProcess);
        // echo "<br>";
        // print_r($id);
        // die();

        $this->db->trans_start();
          if(empty($id)){
            $this->db->insert('material_planning_base_on_produksi_detail_tgl',$dataProcess);
          }
          else{
            $this->db->where('id',$id);
            $this->db->update('material_planning_base_on_produksi_detail_tgl',$dataProcessUpdate);
          }
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
          history($label." Material Planning Base On produksi Detail Tanggal : ".$id);
        }
        echo json_encode($status);
      }
      else{
        // $listData = $this->db->get_where('ms_department',array('id' => $id))->result();
        // print_r($id.'||no save||'.$so.'||'.$id_plan_detail);
        // die();
        $dataHeader = $this->db->get_where('material_planning_base_on_produksi',array('so_number' => $so))->row();
        $dataDetail = $this->db->get_where('material_planning_base_on_produksi_detail',array('id' => $id))->row();
        $listData_tgl = $this->db->get_where('material_planning_base_on_produksi_detail_tgl',array('id' => $id_plan_detail))->row();
        // echo $this->db->last_query($listData_tgl);
        // die();
        // print_r($listData_tgl->tgl_rencana_kedatangan);
        // die();
        $data = [
          'dataHeader' => $dataHeader,
          'dataDetail' => $dataDetail,
          'listDataTgl' => $listData_tgl,
          'id_detail' => $id,
          'so_number' => $so
        ];
        $this->template->set($data);
        $this->template->render('modal_material_planning_detail_tgl');
      }
    }

    public function delete_date_plan(){
      $this->auth->restrict($this->deletePermission);
      
      $id = $this->input->post('id');
      $data = [
        'deleted_by'    => $this->id_user,
        'deleted_date'  => $this->datetime
      ];
      // print_r($data);
      // echo "<br>";
      // print_r($id);
      // die();

      $this->db->trans_begin();
      $this->db->where('id',$id)->update("material_planning_base_on_produksi_detail_tgl",$data);

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
        history("Delete department : ".$id);
      }
      echo json_encode($status);
    }

  public function download_excel_material_plan_old($so=null) {
    $so_number = $so;

    if (!$so_number) {
        show_error("SO Number Error!");
        return;
    }
    // print_r($so_number);
    // die();

    //START AMBIL DATA HEADER Material Planning
    $HeaderMaterialPlan = $this->db->get_where('material_planning_base_on_produksi', [
        'so_number' => $so_number
    ])->row();

    if (!$HeaderMaterialPlan) {
        show_error("Data Header Material tidak ditemukan.");
        return;
    }
    //END AMBIL DATA HEADER Material PLANNING

    //START AMBIL DATA DETAIL Material Planning
    $this->db->order_by('id', 'ASC');
    $DetailMaterialPlan = $this->db->get_where('material_planning_base_on_produksi_detail', [
        'so_number' => $so_number
    ])->result();

    if (!$DetailMaterialPlan) {
        show_error("Data Detail Material tidak ditemukan.");
        return;
    }
    //END AMBIL DATA DETAIL Material PLANNING

    //START AMBIL DATA DETAIL Material Planning TANGGAL
    $this->db->order_by('id', 'ASC');
    $DetailMaterialPlanTgl = $this->db->get_where('material_planning_base_on_produksi_detail_tgl', [
        'so_number' => $so_number
    ])->result();

    if (!$DetailMaterialPlanTgl) {
        show_error("Data Detail Material Tanggal tidak ditemukan.");
        return;
    }
    //END AMBIL DATA DETAIL Material PLANNING TANGGAL

    // Buat Excel
    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle("PURCHASE ORDER");

    // Judul
    $sheet->setCellValue('A1', 'Nama Vendor :');
    $sheet->setCellValue('B1', 'PT. test vendor');

    // Header
    $headers = [
        'A3' => 'No',
        'B3' => 'Deskripsi Permintaan Material'
    ];

    foreach ($headers as $cell => $label) {
        $sheet->setCellValue($cell, $label);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true);
    }

    // Isi Data
    $row = 4;
    $currentDate = '';
    $subtotal = 0;

    // Buat grouping berdasarkan tanggal (Y-m-d)
    // $grouped = [];
    // foreach ($details as $d) {
    //     $date_key = date('Y-m-d', strtotime($d->plan_date)); // normalize format tanggal
    //     $grouped[$date_key][] = $d;
    // }

    $row = 4;
    $no = 0;
    foreach ($DetailMaterialPlan as $value) {
        $no++;
        $dataMaterial = $this->db->get_where('tr_jenis_beton_detail', array('id_detail_material' => $value->id_material))->row();
        $sheet->setCellValue('A' . $row, $no);
        $sheet->setCellValue('B' . $row, $dataMaterial->nm_material);
        $row++; // Tambahkan ini agar pindah ke baris berikutnya setiap loop
    }

    // Nama file
    $filename = 'Material_Planning_' . $so_number . '.xls';

    ob_clean();
    header_remove();

    // Output Excel
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
  }

  public function download_excel_material_plan($so = null){
    $so_number = $so;

    if (!$so_number) {
        show_error("SO Number Error!");
        return;
    }

    // Ambil Header
    $HeaderMaterialPlan = $this->db->get_where('material_planning_base_on_produksi', [
        'so_number' => $so_number
    ])->row();

    if (!$HeaderMaterialPlan) {
        show_error("Data Header Material tidak ditemukan.");
        return;
    }

    // Ambil Detail Material
    $this->db->order_by('id', 'ASC');
    $DetailMaterialPlan = $this->db->get_where('material_planning_base_on_produksi_detail', [
        'so_number' => $so_number
    ])->result();

    // Ambil Detail Tanggal
    $this->db->order_by('tgl_rencana_kedatangan', 'ASC');
    $DetailMaterialPlanTgl = $this->db->get_where('material_planning_base_on_produksi_detail_tgl', [
        'so_number' => $so_number
    ])->result();

    // Index berdasarkan id_material
    $groupedByMaterial = [];
    foreach ($DetailMaterialPlanTgl as $row) {
        $groupedByMaterial[$row->id_material][] = $row;
    }

    // Load PHPExcel
    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet();
    $sheet->setTitle("Material Planning");

    // Informasi Vendor dan Dokumen
    $sheet->setCellValue('A1', 'Nama Vendor :');
    $sheet->setCellValue('B1', '');
    $sheet->setCellValue('D1', 'No. Dokumen :');
    $sheet->setCellValue('E1', $so_number);

    $sheet->setCellValue('A2', 'Alamat :');
    $sheet->setCellValue('D2', 'Alamat Pengiriman :');

    $sheet->setCellValue('A3', 'No. Telp :');

    // Tabel Utama (Deskripsi Permintaan Material)
    $startRow = 5;
    $sheet->setCellValue("A{$startRow}", 'No');
    $sheet->setCellValue("B{$startRow}", 'Deskripsi Permintaan Material');
    $sheet->getStyle("A{$startRow}:B{$startRow}")->getFont()->setBold(true);

    $no = 1;
    $row = $startRow + 1;
    foreach ($DetailMaterialPlan as $value) {
        $dataMaterial = $this->db->get_where('tr_jenis_beton_detail', [
            'id_detail_material' => $value->id_material
        ])->row();

        if (!$dataMaterial) continue;

        $sheet->setCellValue("A{$row}", $no++);
        $sheet->setCellValue("B{$row}", $dataMaterial->nm_material);
        $row++;
    }

    // Border untuk tabel utama
    $sheet->getStyle("A{$startRow}:B" . ($row - 1))->applyFromArray([
        'borders' => [
            'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
        ]
    ]);

    // Spasi
    $row += 2;

    // Detail Tanggal per Material
    $no = 1;
    foreach ($DetailMaterialPlan as $value) {
        $dataMaterial = $this->db->get_where('tr_jenis_beton_detail', [
            'id_detail_material' => $value->id_material
        ])->row();

        if (!$dataMaterial) continue;

        // Judul merah
        $sheet->setCellValue("A{$row}", "{$no}. {$dataMaterial->nm_material}");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('FF0000');
        $row++;

        // Header tabel
        $sheet->setCellValue("A{$row}", 'No');
        $sheet->setCellValue("B{$row}", 'Tgl Dibutuhkan');
        $sheet->setCellValue("C{$row}", 'Qty Dibutuhkan');
        $sheet->setCellValue("D{$row}", 'Satuan');
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:D{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $headerRow = $row;
        $row++;

        $i = 1;
        if (!empty($groupedByMaterial[$value->id_material])) {
            foreach ($groupedByMaterial[$value->id_material] as $dt) {
                $sheet->setCellValue("A{$row}", $i++);
                $sheet->setCellValue("B{$row}", PHPExcel_Shared_Date::PHPToExcel(strtotime($dt->tgl_rencana_kedatangan)));
                $sheet->setCellValue("C{$row}", $dt->qty_kedatangan);
                // $sheet->setCellValue("D{$row}", $dataMaterial->satuan ?? '-');
                $sheet->setCellValue("D{$row}", isset($dataMaterial->satuan) ? $dataMaterial->satuan : '-');
                $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('d-mmm-yy');
                $row++;
            }

            // Border untuk tabel ini
            $sheet->getStyle("A{$headerRow}:D" . ($row - 1))->applyFromArray([
                'borders' => [
                    'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
                ]
            ]);
        } else {
            $sheet->setCellValue("A{$row}", 'Tidak ada data tanggal');
            $row++;
        }

        $row += 2;
        $no++;
    }

    // Autosize kolom
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Nama file
    $filename = 'Material_Planning_' . $so_number . '.xls';

    ob_clean();
    header_remove();
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $writer->save('php://output');
    exit;
}



  // Fungsi helper untuk format nama bulan
  private function bulan_indo($bulan) {
      $bulanList = [
          1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
          4 => 'April',   5 => 'Mei',      6 => 'Juni',
          7 => 'Juli',    8 => 'Agustus',  9 => 'September',
          10 => 'Oktober',11 => 'November',12 => 'Desember'
      ];
      return isset($bulanList[$bulan]) ? $bulanList[$bulan] : $bulan;
  }


}
