<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spk_material extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'SPK_Material.View';
  protected $addPermission    = 'SPK_Material.Add';
  protected $managePermission = 'SPK_Material.Manage';
  protected $deletePermission = 'SPK_Material.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->library(array('upload', 'Image_lib','PHPExcel'));
    $this->load->model(array(
      'Spk_material/spk_material_model'
    ));

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');
    // $this->template->page_icon('fa fa-users');

    $listSO = $this->db->get_where('so_internal', array('deleted_date' => NULL))->result_array();
    $listPlanning = $this->db->get_where('planning_harian', array('deleted_date' => NULL))->result_array();
    $listType = $this->db->get_where('new_inventory_1', array('deleted_date' => NULL, 'category' => 'product', 'code_lv1 <>' => 'P123000009'))->result_array();
    $listCust = $this->db->query("SELECT a.* FROM master_customers a")->result_array();
    $data = [
      'listSO' => $listSO,
      'listType' => $listType,
      'listPlan' => $listPlanning,
      'listCust' => $listCust
    ];

    history("View data spk material");
    // $this->template->title('SPK Produksi');
    $this->template->title('Planning Harian');
    $this->template->render('index', $data);
  }

  public function data_side_spk_material()
  {
    $this->spk_material_model->data_side_spk_material();
  }

  public function data_side_planning_harian()
  {
    $this->spk_material_model->data_side_planning_harian();
  }

  public function release_spk()
  {
    $data         = $this->input->post();
    $session      = $this->session->userdata('app_session');

    $id        = $data['id'];
    $qty      = str_replace(',', '', $data['qty']);

    $Arr_Data  = array(
      'id'    => $id,
      'qty'    => $qty
    );
    echo json_encode($Arr_Data);
  }

  public function delete_plan()
  {
    $data         = $this->input->post();
    $session      = $this->session->userdata('app_session');

    $id         = $data['id'];
    // $qty        = str_replace(',', '', $data['qty']);

    $ArrHeaderPlan = array(
      'deleted_by'      => $this->id_user,
      'deleted_date'    => $this->datetime
    );

    $this->db->trans_start();
    $this->db->where('id_planning_harian', $id);
    $this->db->update('planning_harian', $ArrHeaderPlan);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Delete gagal ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Delete berhasil. Thanks ...',
        'status'  => 1
      );
      history("Delete Planning Harian : " . $id);
    }
    echo json_encode($Arr_Data);
  }

  public function add($id = null, $qty = null)
  {
    if ($this->input->post()) {
      $data         = $this->input->post();
      $session      = $this->session->userdata('app_session');

      $id        = $data['id'];
      $so_number = $data['so_number'];
      $Detail    = $data['Detail'];

      $Ym = date('ym');
      $SQL        = "SELECT MAX(kode) as maxP FROM so_internal_spk WHERE kode LIKE 'SPK" . $Ym . "%' ";
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $kode          = "SPK" . $Ym . $urut2;

      $Y          = date('y');
      $SQL        = "SELECT MAX(no_spk) as maxP FROM so_internal_spk WHERE no_spk LIKE 'INT." . $Y . ".%' ";
      // echo $SQL; exit;
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $no_spk          = "INT." . $Y . '.' . $urut2;

      $ArrInsert = [];
      $ArrInsertMaterial = [];
      foreach ($Detail as $key => $value) {
        $qty = str_replace(',', '', $value['qty']);
        if ($qty > 0) {
          $ArrInsert[$key]['id_so'] = $id;
          $ArrInsert[$key]['kode'] = $kode;
          $ArrInsert[$key]['kode_det'] = $kode . '-' . $key;
          $ArrInsert[$key]['no_spk'] = $no_spk;
          $ArrInsert[$key]['tanggal'] = date('Y-m-d', strtotime($value['tanggal']));
          $ArrInsert[$key]['tanggal_est_finish'] = date('Y-m-d', strtotime($value['tanggal_est_finish']));
          $ArrInsert[$key]['qty'] = $qty;
          $ArrInsert[$key]['id_costcenter'] = $value['costcenter'];
          $ArrInsert[$key]['created_by'] = $this->id_user;
          $ArrInsert[$key]['created_date'] = $this->datetime;

          $dataBOM = $this->db->get_where('so_internal_material', array('so_number' => $so_number))->result_array();
          if (!empty($dataBOM)) {
            foreach ($dataBOM as $key2 => $value2) {
              $UNIQ = $key . '-' . $key2;
              $ArrInsertMaterial[$UNIQ]['kode_det'] = $kode . '-' . $key;
              $ArrInsertMaterial[$UNIQ]['code_material'] = $value2['code_material'];
              $ArrInsertMaterial[$UNIQ]['weight'] = $value2['weight'];
              $ArrInsertMaterial[$UNIQ]['code_lv1'] = $value2['code_lv1'];
              $ArrInsertMaterial[$UNIQ]['type_name'] = $value2['type_name'];
            }
          }
        }
      }

      $this->db->trans_start();
      if (!empty($ArrInsert)) {
        $this->db->insert_batch('so_internal_spk', $ArrInsert);
      }
      if (!empty($ArrInsertMaterial)) {
        $this->db->insert_batch('so_internal_spk_material', $ArrInsertMaterial);
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
          'status'  => 1,
          'kode' => $kode
        );
        history("Create spk planning : " . $so_number);
      }
      echo json_encode($Arr_Data);
    } else {

      $getData = $this->db->get_where('so_internal', array('id' => $id))->result_array();

      $tgl1 = date_create();
      $tgl2 = date_create($getData[0]['due_date']);
      $jarak = date_diff($tgl1, $tgl2);

      $maxDate = $jarak->days + 1;

      $GET_CYCLETIME = get_total_time_cycletime();
      $code_lv4 = $getData[0]['code_lv4'] . '-' . $getData[0]['no_bom'];
      $no_bom = $getData[0]['no_bom'];

      $getDataProduct = $this->db->get_where('bom_header', array('no_bom' => $getData[0]['no_bom']))->result_array();

      $cycletimeMesin   = (!empty($GET_CYCLETIME[$code_lv4]['ct_machine'])) ? $GET_CYCLETIME[$code_lv4]['ct_machine'] : 0;

      $GetNamaBOMProduct  = get_name_product_by_bom($no_bom);
      $NamaProduct         = (!empty($GetNamaBOMProduct[$no_bom])) ? $GetNamaBOMProduct[$no_bom] : 0;

      $data = [
        'getData' => $getData,
        'getDataProduct' => $getDataProduct,
        'maxDate' => $maxDate,
        'NamaProduct' => $NamaProduct,
        'qty' => $qty,
        'cycletime' => ($cycletimeMesin > 0) ? $cycletimeMesin / 60 : 0,
      ];

      $this->template->title('Add Schedule Detil');
      $this->template->render('add', $data);
    }
  }

  public function add_new($id = null, $qty = null)
  {
    $x = $this->input->post();
    // print_r($x);
    // die();
    if ($this->input->post()) {
      $data         = $this->input->post();
      $session      = $this->session->userdata('app_session');
      echo "<pre>";
      print_r($data);
      echo "<pre>";
      die();

      // $id        = $data['id'];
      // $so_number = $data['so_number'];
      $bulan        = $data['bulan'];
      $tahun        = $data['tahun'];
      $Detail       = $data['Detail'];

      $Ym = date('ym');
      // $SQL        = "SELECT MAX(kode) as maxP FROM so_internal_spk WHERE kode LIKE 'SPK" . $Ym . "%' ";
      $SQL        = "SELECT MAX(kode_planning) as maxP FROM planning_harian WHERE kode_planning LIKE 'PL" . $Ym . "%' ";
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      // $kodeSPK          = "SPK" . $Ym . $urut2;
      $kode          = "PL" . $Ym . $urut2;

      //generate no spk
      $SQL        = "SELECT MAX(kode) as maxP FROM so_internal_spk WHERE kode LIKE 'SPK" . $Ym . "%' ";
      // $SQL        = "SELECT MAX(kode_planning) as maxP FROM planning_harian WHERE kode_planning LIKE 'PL" . $Ym . "%' ";
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $kodeSPK          = "SPK" . $Ym . $urut2;
      // $kode          = "PL" . $Ym . $urut2;

      $Y          = date('y');
      $SQL        = "SELECT MAX(no_spk) as maxP FROM so_internal_spk WHERE no_spk LIKE 'INT." . $Y . ".%' ";
      // echo $SQL; exit;
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $no_spk          = "INT." . $Y . '.' . $urut2;

      $ArrInsert = [];
      $ArrInsertDetail = [];
      $ArrInsertMaterial = [];
      $ArrInsert['periode_bulan'] = $bulan;
      $ArrInsert['periode_tahun'] = $tahun;
      $ArrInsert['kode_planning'] = $kode;
      // $ArrInsert[$key]['kode_det'] = $kodeSPK . '-' . $key;
      // $ArrInsert[$key]['no_spk'] = $no_spk;
      // $ArrInsert[$key]['tanggal'] = date('Y-m-d', strtotime($value['tanggal']));
      // $ArrInsert[$key]['tanggal_est_finish'] = date('Y-m-d', strtotime($value['tanggal_est_finish']));
      // $ArrInsert[$key]['qty'] = $qty;
      // $ArrInsert[$key]['id_costcenter'] = $value['costcenter'];
      $ArrInsert['created_by'] = $this->id_user;
      $ArrInsert['created_date'] = $this->datetime;
      foreach ($Detail as $key => $value) {
        //start insert so_internal_spk
        $ArrInsert[$key]['kode_det'] = $kodeSPK . '-' . $key;
        $ArrInsert[$key]['no_spk'] = $no_spk;
        $ArrInsert[$key]['tanggal'] = date('Y-m-d');
        // $ArrInsert[$key]['tanggal_est_finish'] = date('Y-m-d', strtotime($value['tanggal_est_finish']));
        // $ArrInsert[$key]['qty'] = $qty;
        // $ArrInsert[$key]['id_costcenter'] = $value['costcenter'];
        $ArrInsert['created_by'] = $this->id_user;
        $ArrInsert['created_date'] = $this->datetime;
        //end insert so_internal_spk

        // $ArrInsertDetail = $value;
        // print_r($value);
        // die();
        // $qty = str_replace(',', '', $value['qty']);
        // if ($qty > 0) {
          

          //start bagian planning detail
          $tanggal = $value['tanggal'];//'01-Jan-2025';
          $formatted = date('Y-m-d', strtotime($tanggal));
          $get_data_stock_product = $this->db->query('SELECT a.code_lv4 as product_id, a.product_name FROM stock_product a where a.id = "' . $value['product'] . '" ')->row();
          $ArrInsertDetail[$key]['kode_planning'] = $kode;
          $ArrInsertDetail[$key]['plan_date'] = $formatted;
          $ArrInsertDetail[$key]['id_stock_product'] = $value['product'];
          $ArrInsertDetail[$key]['id_product'] = $get_data_stock_product->product_id;
          $ArrInsertDetail[$key]['name_product'] = $get_data_stock_product->product_name;
          $ArrInsertDetail[$key]['propose_production'] = $value['propose'];
          $ArrInsertDetail[$key]['m3_pcs'] = $value['m3'];
          $ArrInsertDetail[$key]['shift1'] = $value['shift1'];
          $ArrInsertDetail[$key]['shift2'] = $value['shift2'];
          $ArrInsertDetail[$key]['total_kubikasi'] = $value['total_kubikasi'];
          $ArrInsertDetail[$key]['created_by'] = $this->id_user;
          $ArrInsertDetail[$key]['created_date'] = $this->datetime;
          //end bagian planning detail

          // $dataBOM = $this->db->get_where('so_internal_material', array('so_number' => $so_number))->result_array();
          // if (!empty($dataBOM)) {
          //   foreach ($dataBOM as $key2 => $value2) {
              // $UNIQ = $key . '-' . $key2;
              // $ArrInsertMaterial[$UNIQ]['kode_det'] = $kode . '-' . $key;
              // $ArrInsertMaterial[$UNIQ]['code_material'] = $value2['code_material'];
              // $ArrInsertMaterial[$UNIQ]['weight'] = $value2['weight'];
              // $ArrInsertMaterial[$UNIQ]['code_lv1'] = $value2['code_lv1'];
              // $ArrInsertMaterial[$UNIQ]['type_name'] = $value2['type_name'];
          //   }
          // }
        // }//end qty
      }
      // echo "<pre>";
      // print_r($ArrInsertDetail);
      // echo "</pre>";
      // echo "<hr>";
      // echo "<pre>";
      // print_r($ArrInsert);
      // echo "</pre>";
      // die();

      $this->db->trans_start();
      if (!empty($ArrInsert)) {
        // $this->db->insert_batch('so_internal_spk', $ArrInsert);
        // $this->db->insert_batch('planning_harian', $ArrInsert);
        $this->db->insert('planning_harian', $ArrInsert);
      }
      if (!empty($ArrInsertDetail)) {
        // $this->db->insert_batch('so_internal_spk_material', $ArrInsertMaterial);
        $this->db->insert_batch('planning_harian_detail', $ArrInsertDetail);
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
          'status'  => 1,
          'kode' => $kode
        );
        // history("Create Planning Harian : " . $so_number);
        history("Create Planning Harian");
      }
      echo json_encode($Arr_Data);
    } else {

      $getData = $this->db->get_where('so_internal', array('id' => $id))->result_array();

      $tgl1 = date_create();
      $tgl2 = date_create($getData[0]['due_date']);
      $jarak = date_diff($tgl1, $tgl2);

      $maxDate = $jarak->days + 1;

      $GET_CYCLETIME = get_total_time_cycletime();
      $code_lv4 = $getData[0]['code_lv4'] . '-' . $getData[0]['no_bom'];
      $no_bom = $getData[0]['no_bom'];

      $getDataProduct = $this->db->get_where('bom_header', array('no_bom' => $getData[0]['no_bom']))->result_array();

      $cycletimeMesin   = (!empty($GET_CYCLETIME[$code_lv4]['ct_machine'])) ? $GET_CYCLETIME[$code_lv4]['ct_machine'] : 0;

      $GetNamaBOMProduct  = get_name_product_by_bom($no_bom);
      $NamaProduct         = (!empty($GetNamaBOMProduct[$no_bom])) ? $GetNamaBOMProduct[$no_bom] : 0;

      $data = [
        'getData' => $getData,
        'getDataProduct' => $getDataProduct,
        'maxDate' => $maxDate,
        'NamaProduct' => $NamaProduct,
        'qty' => $qty,
        'cycletime' => ($cycletimeMesin > 0) ? $cycletimeMesin / 60 : 0,
      ];

      $this->template->title('Add Schedule Detil');
      $this->template->render('add', $data);
    }
  }

  public function get_add()
  {
    $id   = $this->uri->segment(3);
    $no   = 0;

    $costcenter  = $this->db->query("SELECT * FROM ms_costcenter WHERE deleted='0' ORDER BY nama_costcenter ASC ")->result_array();
    $d_Header = "";
    // $d_Header .= "<tr>";
    $d_Header .= "<tr class='header_" . $id . "'>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][tanggal]' class='form-control input-md text-center datepicker' placeholder='Plan Date' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][tanggal_est_finish]' class='form-control input-md text-center datepicker2' placeholder='Est Finish' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][qty]' class='form-control input-md text-center autoNumeric0 qty_spk' placeholder='Qty SPK'>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<select name='Detail[" . $id . "][costcenter]' class='chosen-select form-control input-sm inline-blockd costcenter'>";
    $d_Header .= "<option value='0'>Select Costcenter</option>";
    foreach ($costcenter as $val => $valx) {
      $d_Header .= "<option value='" . $valx['id_costcenter'] . "'>" . strtoupper($valx['nama_costcenter']) . "</option>";
    }
    $d_Header .=     "</select>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='center'>";
    $d_Header .= "<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
    $d_Header .= "</td>";
    $d_Header .= "</tr>";

    //add part
    $d_Header .= "<tr id='add_" . $id . "'>";
    $d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "</tr>";

    echo json_encode(array(
      'header'      => $d_Header,
    ));
  }

  public function get_add_plan($id = 1)
  {
    // $id   = $this->uri->segment(3);
    $no   = 0;

    $costcenter  = $this->db->query("SELECT * FROM ms_costcenter WHERE deleted='0' ORDER BY nama_costcenter ASC ")->result_array();
    $sql_product = "
          SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              (SELECT ng_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_ng,
              (SELECT actual_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_akhir,
              (SELECT booking_stock FROM stock_product WHERE id = MAX(a.id)) AS booking_akhir,
              b.nama AS nama_level4,
              b.min_stok,
              b.max_stok,
              c.category AS category_bom,
              c.no_bom
          FROM
              stock_product a
              JOIN bom_header c ON a.no_bom = c.no_bom
              LEFT JOIN new_inventory_4 b ON a.code_lv4 = b.code_lv4,
              (SELECT @row := 0) r
          WHERE
              1=1
              AND c.category IN ('grid standard','standard','ftackel','custom')
              AND a.deleted_date IS NULL
          GROUP BY
              a.no_bom, a.code_lv4
    ";
    $sql_product = $this->db->query($sql_product)->result_array();
    $propose = 0;
    // if ($row['stock_akhir'] - $row['booking_akhir'] < $row['min_stok']) {
    //   // $propose = $row['max_stok'];//version old
    //   $propose = $row['max_stok'] - ($row['stock_ng'] + $row['stock_akhir']);
    // }
    // print_r($id);
    // die();

    $d_Header = "";
    // $d_Header .= "<tr>";
    $d_Header .= "<tr class='header_" . $id . "'>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='hidden' class='id' value=''>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][tanggal]' class='form-control input-md text-center datepicker' placeholder='Plan Date' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<select name='Detail[" . $id . "][product]' class='chosen-select form-control input-sm inline-blockd get_data_product product'>";
    $d_Header .= "<option value='0'>Select Product</option>";
    foreach ($sql_product as $val => $valx) {
      // $d_Header .= "<option value='" . $valx['code_lv4'] . "'>" . strtoupper($valx['product_name']) . "</option>";
      // $d_Header .= "<option value='" . $valx['id'] . "'>" . strtoupper($valx['product_name']) . "</option>";
      $d_Header .= "<option value='" . $valx['id'] . "'>" . strtoupper($valx['nama_level4']) . "</option>";
    }
    $d_Header .=     "</select>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][propose]' class='form-control input-md text-center propose' placeholder='Propose' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][m3]' class='form-control input-md text-center m3' placeholder='m3/pcs' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][shift1]' class='form-control input-md text-center shift1' placeholder='Shift 1'>";
    $d_Header .= "</td>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][shift2]' class='form-control input-md text-center shift2' placeholder='shift2'>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[" . $id . "][total_kubikasi]' class='form-control input-md text-center total_kubikasi' placeholder='Total Kubikasi' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='center'>";
    $d_Header .= "<button type='button' class='btn btn-sm btn-danger delPartPlan' title='Delete'><i class='fa fa-close'></i></button>";
    $d_Header .= "</td>";
    $d_Header .= "</tr>";

    //add part
    // $d_Header .= "<tr id='add_" . $id . "'>";
    // $d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartPlan' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
    // $d_Header .= "<td align='center'></td>";
    // $d_Header .= "<td align='center'></td>";
    // $d_Header .= "<td align='center'></td>";
    // // $d_Header .= "<td align='center'></td>";
    // $d_Header .= "<td align='center' colspan='2' style='text-align: right;'>Total Kubikasi Tgl : </td>";
    // // $d_Header .= "<td align='center'></td>";
    // $d_Header .= "<td align='center' colspan='2'>
    //               <input type='text' id='grand_total_kubikasi' class='form-control input-md text-center' placeholder='Grand Total Kubikasi' readonly>
    //               </td>";
    // $d_Header .= "</tr>";

    echo json_encode(array(
      'header'      => $d_Header,
      'rowIndex'    => $id,
    ));
  }

  public function get_data_product()
  {
    $id_product = $this->input->post('id_product');
    $id   = $this->uri->segment(3);
    $rowIndex = $this->input->post('rowIndex');
    // print_r($no_penawarans);
    // die();
    $sql_product = '
          SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              (SELECT ng_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_ng,
              (SELECT actual_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_akhir,
              (SELECT booking_stock FROM stock_product WHERE id = MAX(a.id)) AS booking_akhir,
              b.nama AS nama_level4,
              b.min_stok,
              b.max_stok,
              c.category AS category_bom,
              c.no_bom
          FROM
              stock_product a
              JOIN bom_header c ON a.no_bom = c.no_bom
              LEFT JOIN new_inventory_4 b ON a.code_lv4 = b.code_lv4,
              (SELECT @row := 0) r
          WHERE
              a.id = "' . $id_product . '"
          GROUP BY
              a.no_bom, a.code_lv4
    ';
    $get_data_product = $this->db->query($sql_product)->row();
    $propose = 0;
    //version old
    // if ($get_data_product->stock_akhir - $get_data_product->booking_akhir < $get_data_product->min_stok) {
    //   // $propose = $row['max_stok'];//version old
    //   // $propose = $row['max_stok'] - ($row['stock_ng'] + $row['stock_akhir']);
    //   $propose = $get_data_product->max_stok - ($get_data_product->stock_ng  + $get_data_product->stock_akhir);
    // }
    //version old
    //start version new
    // if($get_data_product->stock_ng > $get_data_product->min_stok){
    //   $propose = 0;
    // }elseif ($get_data_product->stock_ng < $get_data_product->min_stok) {
      // $propose = $get_data_product->min_stok - ($get_data_product->stock_ng + $get_data_product->stock_akhir);
    $propose = $get_data_product->max_stok - ($get_data_product->stock_akhir + $get_data_product->stock_ng);
    // }else{
    //   $propose = 0;
    // }
    //end version new
    $get_data_bom = $this->db->query('SELECT no_bom, volume_m3 FROM bom_header WHERE no_bom = "' . $get_data_product->no_bom . '" ')->row();
    $volumeM3 = $get_data_bom->volume_m3;
    // echo $this->db->last_query();
    // die();
    // echo $get_data_truck->maksimal_muatan;
    $list_product = '<option value="' . $get_data_product->id . '">' . $get_data_product->product_name . '</option>';
    echo json_encode([
      'list_product' => $list_product,
      'propose' => $propose,
      'volumeM3' => $volumeM3,
      'rowIndex' => $rowIndex
      // 'kapasitas' => $get_data_truck->maksimal_muatan,
      // 'rate_truck' => $get_data_truck->rate_truck,
      // 'all_qty_penawaran' => $get_data_detail_penawaran->sum_qty,
      // 'grand_total' => $grand_total
    ]);
  }

  public function print_spk()
  {
    $kode  = $this->uri->segment(3);
    $data_session  = $this->session->userdata;
    $session        = $this->session->userdata('app_session');
    $printby    = $session['id_user'];

    $data_url    = base_url();
    $Split_Beda    = explode('/', $data_url);
    $Jum_Beda    = count($Split_Beda);
    $Nama_Beda    = $Split_Beda[$Jum_Beda - 2];

    $getMaterialMixing    = $this->db->select('code_material, weight AS berat')->group_by('code_material')->like('kode_det', $kode)->get_where('so_internal_spk_material', array('type_name' => 'mixing'))->result_array();
    $getMaterialProduksi  = $this->db->select('code_material, weight AS berat')->group_by('code_material')->like('kode_det', $kode)->get_where('so_internal_spk_material', array('type_name <>' => 'mixing'))->result_array();

    $getData = $this->db
      ->select('
                          b.nama_product,
                          SUM(a.qty) AS qty_produksi,
                          b.so_number AS nomor_so,
                          a.no_spk,
                          a.tanggal AS tanggal,
                          MAX(a.tanggal_est_finish) AS tanggal_est_finish,
                          b.due_date AS due_date,
                          b.no_bom
                      ')
      ->group_by('a.kode')
      ->join('so_internal b', 'a.id_so=b.id', 'left')
      ->get_where('so_internal_spk a', array(
        'a.kode' => $kode
      ))
      ->result_array();

    $getHeader = $this->db->get_where('so_internal_spk', array('kode' => $kode))->result_array();

    $no_bom = $getData[0]['no_bom'];
    $GetNamaBOMProduct  = get_name_product_by_bom($no_bom);
    $NamaProduct         = (!empty($GetNamaBOMProduct[$no_bom])) ? $GetNamaBOMProduct[$no_bom] : 0;

    $data = array(
      'Nama_Beda' => $Nama_Beda,
      'printby' => $printby,
      'getHeader' => $getHeader,
      'getData' => $getData,
      'NamaProduct' => $NamaProduct,
      'getMaterialMixing' => $getMaterialMixing,
      'getMaterialProduksi' => $getMaterialProduksi,
      'GET_DET_Lv4' => get_inventory_lv4(),
      'kode' => $kode
    );

    history('Print spk material ' . $kode);
    $this->load->view('print_spk3', $data);
  }

  //Re-Print SPK
  public function reprint_spk()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');
    $this->template->page_icon('fa fa-users');

    $this->template->title('SPK Re-Print');
    $this->template->render('reprint_spk');
  }

  public function data_side_spk_reprint()
  {
    $this->spk_material_model->data_side_spk_reprint();
  }

  public function create_plan($id = null, $qty = null)
  {
    if ($this->input->post()) {
      $data         = $this->input->post();
      $session      = $this->session->userdata('app_session');

      $id        = $data['id'];
      $so_number = $data['so_number'];
      $Detail    = $data['Detail'];

      $Ym = date('ym');
      $SQL        = "SELECT MAX(kode) as maxP FROM so_internal_spk WHERE kode LIKE 'SPK" . $Ym . "%' ";
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $kode          = "SPK" . $Ym . $urut2;

      $Y          = date('y');
      $SQL        = "SELECT MAX(no_spk) as maxP FROM so_internal_spk WHERE no_spk LIKE 'INT." . $Y . ".%' ";
      // echo $SQL; exit;
      $result      = $this->db->query($SQL)->result_array();
      $angkaUrut2    = $result[0]['maxP'];
      $urutan2      = (int)substr($angkaUrut2, 7, 4);
      $urutan2++;
      $urut2        = sprintf('%04s', $urutan2);
      $no_spk          = "INT." . $Y . '.' . $urut2;

      $ArrInsert = [];
      $ArrInsertMaterial = [];
      foreach ($Detail as $key => $value) {
        $qty = str_replace(',', '', $value['qty']);
        if ($qty > 0) {
          $ArrInsert[$key]['id_so'] = $id;
          $ArrInsert[$key]['kode'] = $kode;
          $ArrInsert[$key]['kode_det'] = $kode . '-' . $key;
          $ArrInsert[$key]['no_spk'] = $no_spk;
          $ArrInsert[$key]['tanggal'] = date('Y-m-d', strtotime($value['tanggal']));
          $ArrInsert[$key]['tanggal_est_finish'] = date('Y-m-d', strtotime($value['tanggal_est_finish']));
          $ArrInsert[$key]['qty'] = $qty;
          $ArrInsert[$key]['id_costcenter'] = $value['costcenter'];
          $ArrInsert[$key]['created_by'] = $this->id_user;
          $ArrInsert[$key]['created_date'] = $this->datetime;

          $dataBOM = $this->db->get_where('so_internal_material', array('so_number' => $so_number))->result_array();
          if (!empty($dataBOM)) {
            foreach ($dataBOM as $key2 => $value2) {
              $UNIQ = $key . '-' . $key2;
              $ArrInsertMaterial[$UNIQ]['kode_det'] = $kode . '-' . $key;
              $ArrInsertMaterial[$UNIQ]['code_material'] = $value2['code_material'];
              $ArrInsertMaterial[$UNIQ]['weight'] = $value2['weight'];
              $ArrInsertMaterial[$UNIQ]['code_lv1'] = $value2['code_lv1'];
              $ArrInsertMaterial[$UNIQ]['type_name'] = $value2['type_name'];
            }
          }
        }
      }

      $this->db->trans_start();
      if (!empty($ArrInsert)) {
        $this->db->insert_batch('so_internal_spk', $ArrInsert);
      }
      if (!empty($ArrInsertMaterial)) {
        $this->db->insert_batch('so_internal_spk_material', $ArrInsertMaterial);
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
          'status'  => 1,
          'kode' => $kode
        );
        history("Create spk planning : " . $so_number);
      }
      echo json_encode($Arr_Data);
    } else {
      $getDataPlan = $this->db->get_where('planning_harian', array('id_planning_harian' => $id))->row();
      $getDataPlan_detail = $this->db->get_where('planning_harian_detail', ['kode_planning' => @$getDataPlan->kode_planning])->result();
      $KodePlanning = @$getDataPlan->kode_planning;
      $getSumPlanDetail = $this->db->query("SELECT COUNT(*) AS jmlh_data FROM planning_harian_detail WHERE kode_planning = '" . @$getDataPlan->kode_planning . "'")->row();
      $JmlhDataDetail = @$getSumPlanDetail->jmlh_data;
      // print_r($getDataPlan_detail);
      // die();

      $tgl1 = date_create();
      $GET_CYCLETIME = get_total_time_cycletime();

      $data = [
        // 'dataProduct' => $sql_product,
        'DataPlan' => $getDataPlan,
        'DataPlan_detail' => $getDataPlan_detail,
        'IdPlanningHarian' => $id,
        'KodePlan' => $KodePlanning,
        'JmlhDataDetail' => $JmlhDataDetail
      ];

      $this->template->title('Add Schedule Detil');
      $this->template->render('create_plan', $data);
    }
  }

  public function view_plan($id = null)
  {
      // $id_plan_detail   = $this->input->post('id');
      $id  = $this->uri->segment(3);
      $getDataPlan = $this->db->get_where('planning_harian', array('id_planning_harian' => $id))->row();
      $getDataPlan_detail = $this->db->get_where('planning_harian_detail', ['kode_planning' => @$getDataPlan->kode_planning])->result();
      $KodePlanning = @$getDataPlan->kode_planning;
      $getSumPlanDetail = $this->db->query("SELECT COUNT(*) AS jmlh_data FROM planning_harian_detail WHERE kode_planning = '" . @$getDataPlan->kode_planning . "'")->row();
      $JmlhDataDetail = @$getSumPlanDetail->jmlh_data;
      // print_r($getDataPlan_detail);
      // die();

      $tgl1 = date_create();
      $GET_CYCLETIME = get_total_time_cycletime();

      $data = [
        // 'dataProduct' => $sql_product,
        'DataPlan' => $getDataPlan,
        'DataPlan_detail' => $getDataPlan_detail,
        'IdPlanningHarian' => $id,
        'KodePlan' => $KodePlanning,
        'JmlhDataDetail' => $JmlhDataDetail
      ];

      $this->template->title('View Schedule Detil');
      $this->template->render('view_plan_harian', $data);
  }


  public function del_planning_harian()
  {
    $id = $this->input->post('id');

    $this->db->trans_begin();

    $this->db->delete('planning_harian_detail', ['id_planning_harian_detail' => $id]);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }
  }

  public function cek_schedule_detil()
  {
    $session = $this->session->userdata('app_session');
    // $id   = $this->uri->segment(3);
    $ids = $this->input->post('id');
    if ($ids == '') {
      // $id = $session['id_user'];
      $ids = '0';
    }
    // print_r($id);
    // die();

    $post = $this->input->post();

    $hasil = '';
    $d_Header = '';
    $hasil_delivery_cost = '';
    $total_berat_all = 0;

    $getDataPlan = $this->db->get_where('planning_harian', array('id_planning_harian' => $ids))->row();
    // print_r($getDataPlan->kode_planning);
    // die();

    $sql_product = "
          SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              (SELECT ng_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_ng,
              (SELECT actual_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_akhir,
              (SELECT booking_stock FROM stock_product WHERE id = MAX(a.id)) AS booking_akhir,
              b.nama AS nama_level4,
              b.min_stok,
              b.max_stok,
              c.category AS category_bom,
              c.no_bom
          FROM
              stock_product a
              JOIN bom_header c ON a.no_bom = c.no_bom
              LEFT JOIN new_inventory_4 b ON a.code_lv4 = b.code_lv4,
              (SELECT @row := 0) r
          WHERE
              1=1
              AND c.category IN ('grid standard','standard','ftackel','custom')
              AND a.deleted_date IS NULL
          GROUP BY
              a.no_bom, a.code_lv4
    ";
    $sql_product = $this->db->query($sql_product)->result_array();

    // $get_penawaran_detail = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $id, 'curr' => $curr])->result();//version old
    $get_planning_detail = $this->db->get_where('planning_harian_detail', ['kode_planning' => @$getDataPlan->kode_planning])->result();
    // print_r($get_planning_detail);
    // die();
    foreach ($get_planning_detail as $plan_detail) {

    // $d_Header = "";
    $d_Header .= "<tr>";
    // $d_Header .= "<tr class='header_" . $id . "'>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[tanggal]' class='form-control input-md text-center datepicker' placeholder='Plan Date' value='".$plan_detail->plan_date."'>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<select name='Detail[product]' class='chosen-select form-control input-sm inline-blockd get_data_product product'>";
    $d_Header .= "<option value='0'>Select Product</option>";
    foreach ($sql_product as $val => $valx) {
      // $d_Header .= "<option value='" . $valx['code_lv4'] . "'>" . strtoupper($valx['product_name']) . "</option>";
      if($valx['code_lv4'] == $plan_detail->id_product){
        $d_Header .= "<option value='" . $valx['id'] . "' selected>" . strtoupper($valx['product_name']) . "</option>";
      }else{
        $d_Header .= "<option value='" . $valx['id'] . "' >" . strtoupper($valx['product_name']) . "</option>";
      }
    }
    $d_Header .=     "</select>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[propose]' class='form-control input-md text-center propose' placeholder='Propose' readonly value='".$plan_detail->propose_production."'>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[m3]' class='form-control input-md text-center m3' placeholder='m3/pcs' readonly value='".$plan_detail->m3_pcs."'>";
    $d_Header .= "</td>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[shift1]' class='form-control input-md text-center shift1' placeholder='Shift 1' value='".$plan_detail->shift1."'>";
    $d_Header .= "</td>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[shift2]' class='form-control input-md text-center shift2' placeholder='shift2' value='".$plan_detail->shift2."'>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='left'>";
    $d_Header .= "<input type='text' name='Detail[total_kubikasi]' class='form-control input-md text-center total_kubikasi' placeholder='Total Kubikasi' value='".$plan_detail->total_kubikasi."' readonly>";
    $d_Header .= "</td>";
    $d_Header .= "<td align='center'>";
    // $d_Header .= "<button type='button' class='btn btn-sm btn-danger delPartPlan' title='Delete Part' onclick='del_planning_harian('".$plan_detail->id_planning_harian_detail."')'><i class='fa fa-close'></i></button>";
    $d_Header .= "<button type=\"button\" class=\"btn btn-sm btn-danger delPartPlan\" title=\"Delete Part\" onclick=\"del_planning_harian('{$plan_detail->id_planning_harian_detail}')\"><i class=\"fa fa-close\"></i></button>";
    $d_Header .= "</td>";
    $d_Header .= "</tr>";

    }

    //add part
    $d_Header .= "<tr>";
    // $d_Header .= "<tr id='add_" . $id . "'>";
    $d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartPlan' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center'></td>";
    // $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center' colspan='2' style='text-align: right;'>Total Kubikasi Tgl : </td>";
    // $d_Header .= "<td align='center'></td>";
    $d_Header .= "<td align='center' colspan='2'>
                  <input type='text' id='grand_total_kubikasi' class='form-control input-md text-center' placeholder='Grand Total Kubikasi' readonly>
                  </td>";
    $d_Header .= "</tr>";

    echo json_encode([
      'hasil' => $d_Header
    ]);
  }


  public function update_schedule_detail()
  {
      if (!$this->input->is_ajax_request()) {
          show_404();
      }

      $post = $this->input->post('Detail');
      $kode_planning = $this->input->post('kode_planning');
      // echo "<pre>";
      // // foreach ($post as $row) {
      //     // echo "ID: " . $row['id'] . "\n";
      //   print_r($post);
      // // }
      // echo "</pre>";
      // die();

      foreach ($post as $row) {
        $tanggal = $row['tanggal'];//'01-Jan-2025';
        $formatted = date('Y-m-d', strtotime($tanggal));
        $get_data_stock_product = $this->db->query('SELECT a.code_lv4 as product_id, a.product_name FROM stock_product a where a.id = "' . $row['product'] . '" ')->row();
          $data = [
              'kode_planning'      => $kode_planning,
              'plan_date'          => $formatted,
              'id_stock_product'   => $row['product'],
              'id_product'         => $get_data_stock_product->product_id,
              'name_product'       => $get_data_stock_product->product_name,
              'propose_production' => $row['propose'],
              'm3_pcs'             => $row['m3'],
              'shift1'             => $row['shift1'],
              'shift2'             => $row['shift2'],
              'total_kubikasi'     => $row['total_kubikasi'],
              'created_by'         => $this->id_user,
              'created_date'       => $this->datetime,
          ];

          // $ArrInsertDetail[$key]['kode_planning'] = $kode;
          // $ArrInsertDetail[$key]['plan_date'] = $formatted;
          // $ArrInsertDetail[$key]['id_stock_product'] = $value['product'];
          // $ArrInsertDetail[$key]['id_product'] = $get_data_stock_product->product_id;
          // $ArrInsertDetail[$key]['name_product'] = $get_data_stock_product->product_name;
          // $ArrInsertDetail[$key]['propose_production'] = $value['propose'];
          // $ArrInsertDetail[$key]['m3_pcs'] = $value['m3'];
          // $ArrInsertDetail[$key]['shift1'] = $value['shift1'];
          // $ArrInsertDetail[$key]['shift2'] = $value['shift2'];
          // $ArrInsertDetail[$key]['total_kubikasi'] = $value['total_kubikasi'];
          // $ArrInsertDetail[$key]['created_by'] = $this->id_user;
          // $ArrInsertDetail[$key]['created_date'] = $this->datetime;

          if (!empty($row['id'])) {
              $this->db->where('id_planning_harian_detail', $row['id']);
              $this->db->update('planning_harian_detail', $data);
            // echo "Update: ID = " . $row['id'] . "<br>";
            // echo "Data: <pre>" . print_r($data, true) . "</pre><br>";
            //   $this->db->where('id_planning_harian_detail', $row['id']);
            //   $success = $this->db->update('planning_harian_detail', $data);

            //   if (!$success) {
            //       log_message('error', 'Gagal update id ' . $row['id'] . ': ' . $this->db->last_query());
            //   }
          } else {
              $this->db->insert('planning_harian_detail', $data);
          }
      }

      echo json_encode(['status' => true, 'message' => 'Berhasil disimpan!']);
  }

  public function edit_plan_harian(){
      $this->auth->restrict($this->viewPermission);
      $session = $this->session->userdata('app_session');

      $this->template->page_icon('fa fa-users');
      $id_plan_detail   = $this->input->post('id');
      $get_plan_harian_detail = $this->db->get_where('planning_harian_detail', ['id_planning_harian_detail' => $id_plan_detail])->row();
      $sql_product = "
          SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              (SELECT ng_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_ng,
              (SELECT actual_stock FROM stock_product WHERE id = MAX(a.id)) AS stock_akhir,
              (SELECT booking_stock FROM stock_product WHERE id = MAX(a.id)) AS booking_akhir,
              b.nama AS nama_level4,
              b.min_stok,
              b.max_stok,
              c.category AS category_bom,
              c.no_bom
          FROM
              stock_product a
              JOIN bom_header c ON a.no_bom = c.no_bom
              LEFT JOIN new_inventory_4 b ON a.code_lv4 = b.code_lv4,
              (SELECT @row := 0) r
          WHERE
              1=1
              AND c.category IN ('grid standard','standard','ftackel','custom')
              AND a.deleted_date IS NULL
          GROUP BY
              a.no_bom, a.code_lv4
    ";
    $sql_product = $this->db->query($sql_product)->result_array();
      // print_r($get_plan_harian_detail->kode_planning);
      // die();
      // $get_plan_harian_detail = $this->db->get_where('delivery_cost_detail', ['no_penawaran' => $sales_order->no_penawaran])->result();
      // $get_penawaran_dc = $this->db->query('SELECT a.*, b.nm_lengkap FROM tr_penawaran a LEFT JOIN users b ON b.id_user = a.created_by WHERE a.no_penawaran = "' . $sales_order->no_penawaran . '"')->row();
      // $get_penawaran_detail_dc = $this->db->get_where('tr_penawaran_detail', ['no_penawaran' => $sales_order->no_penawaran])->result();

      $data = [
        'id_plan_detail' => $id_plan_detail,
        'DataPLanHarian' => $get_plan_harian_detail,
        'DataProduct' => $sql_product
      ];
      
      history("Editing planning harian");
      $this->template->set($data);
      $this->template->title('Edit Planning Harian');
      $this->template->render('edit_plan_harian');
  }

  public function update_plan_detail($id=null){//ini hanya khusus update plan detail
      if(empty($id)){
        $this->auth->restrict($this->addPermission);
      }
      else{
        $this->auth->restrict($this->managePermission);
      }   
      if($this->input->post()){
        $post = $this->input->post();
        // print_r($post);
        // die();

        $id   = $post['id'];
        $plan_date = $post['plan_date'];
        $id_stock = $post['id_stock'];
        $id_product = $post['id_product'];
        $name_product = $post['name_product'];
        $propose = $post['propose'];
        $m3 = $post['m3'];
        $shift1 = $post['shift1'];
        $shift2 = $post['shift2'];
        $total_kubikasi = $post['total_kubikasi'];

        $get_data_stock = $this->db->get_where('stock_product',array('id' => $id_stock))->row();
        // $StockID = $get_data_stock->id_stock;
        // $nm_customer = $this->Kredit_limit_model->get_nm_customer($id_cust);
        // $get_rev = $this->Kredit_limit_model->get_rev($id);
        // if($get_rev == Null || $get_rev == '' || $get_rev == '0'){
        //   $get_revisi = 0;
        // }else{
        //   $get_revisi = $get_rev;
        // }
        // print_r($get_data_stock);
        // die();

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
          // $dataProcess = [
          //   'id_customer'     => $id_cust,
          //   'nm_customer'     =>  $nm_customer,
          //   'kurs'    => $kurs,
          //   // 'credit_limit'   => $credit_limit,
          //   'credit_limit'    => str_replace('.', '', $credit_limit),
          //   'rev' => @$get_revisi,
          //   'status_approval' => 0,
          //   $last_by    => $this->id_user,
          //   $last_date  => $this->datetime
          // ];
          $dataProcess = [
            'plan_date'     => $plan_date,
            'id_stock_product'    =>  $get_data_stock->id,
            'id_product'     =>  $get_data_stock->code_lv4,
            'name_product'    => $get_data_stock->product_name,
            'propose_production'    => $propose,
            'm3_pcs' => @$m3,
            'shift1' => @$shift1,
            'shift2' => @$shift2,
            'total_kubikasi' => @$total_kubikasi,
            $last_by    => $this->id_user,
            $last_date  => $this->datetime
          ];
        }

        $this->db->trans_start();
          if(empty($id)){
            $this->db->insert('planning_harian_detail',$dataProcess);
          }
          else{
            $this->db->where('id_planning_harian_detail',$id);
            $this->db->update('planning_harian_detail',$dataProcess);
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
          history($label." Planning Harian: ".$id);
        }
        echo json_encode($status);
      }
      else{
        echo "salah if";
        die();
      }
      // else{
      //   $listData           = $this->db->get_where('ms_credit_limit',array('id' => $id))->result();
      //   $data_customer      = $this->db->get_where('master_customers',array('sts_aktif'=>'Y'))->result_array(); 
      //   $data_kurs          = $this->db->get('ms_kurs')->result_array();

      //   $data = [
      //     'listData' => $listData,
      //     'data_customer' => $data_customer,
      //     'data_kurs' => $data_kurs
      //   ];
      //   $this->template->set($data);
      //   $this->template->render('add_kredit_limit_request');
      // }
    }

  public function download_excel_plan_harian($id=null) {
    // Ambil input dari form GET atau POST
    // $start_date = $this->input->get('start_date');
    // $end_date   = $this->input->get('end_date');
    $id_plan = $id;

    if (!$id_plan) {
        show_error("ID Planning Harian Error!");
        return;
    }
    // print_r($id_plan);
    // die();

    //START AMBIL DATA HEADER PLANNING HARIAN
    $HeaderPlanHarian = $this->db->get_where('planning_harian', [
        'id_planning_harian' => $id_plan
    ])->row();

    if (!$HeaderPlanHarian) {
        show_error("Data planning tidak ditemukan.");
        return;
    }
    //END AMBIL DATA HEADER PLANNING HARIAN

    // AMBIL DATA KODE PLANNING
    $getDataPlanningHeader = $this->db->get_where('planning_harian',array('id_planning_harian' => $id_plan))->row();
    $KodePlaning = $getDataPlanningHeader->kode_planning;
    $bulan = $getDataPlanningHeader->periode_bulan;
    $tahun = $getDataPlanningHeader->periode_tahun;
    // print_r($this->bulan_indo($bulan));
    // die();

    // Ambil data detail
    // $details = $this->db->get_where('planning_harian_detail', [
    //     'kode_planning' => $KodePlaning
    // ])->result_array();
    $this->db->order_by('plan_date', 'ASC');
    $details = $this->db->get_where('planning_harian_detail', [
        'kode_planning' => $KodePlaning
    ])->result();
    // print_r($KodePlaning);
    // die();

    // Buat Excel
      $excel = new PHPExcel();
      $sheet = $excel->getActiveSheet();
      $sheet->setTitle("Schedule Detail");

      // Judul
      $sheet->setCellValue('A1', 'Periode Planning :');
      $sheet->setCellValue('B1', $this->bulan_indo($bulan) . ' ' . $tahun);

      // Header
      $headers = [
          'A3' => 'Plan Date',
          'B3' => 'Product',
          'C3' => 'Propose Production',
          'D3' => 'm3/pcs',
          'E3' => 'Shift 1',
          'F3' => 'Shift 2',
          'G3' => 'Total Kubikasi'
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
    $grouped = [];
    foreach ($details as $d) {
        $date_key = date('Y-m-d', strtotime($d->plan_date)); // normalize format tanggal
        $grouped[$date_key][] = $d;
    }

    $row = 4;
    foreach ($grouped as $date => $entries) {
        $subtotal = 0;
        foreach ($entries as $d) {
            $total_kubikasi = ($d->shift1 + $d->shift2) * $d->m3_pcs;
            $subtotal += $total_kubikasi;

            $sheet->setCellValue('A' . $row, $d->plan_date);
            $sheet->setCellValue('B' . $row, $d->name_product);
            $sheet->setCellValue('C' . $row, $d->propose_production);
            $sheet->setCellValue('D' . $row, $d->m3_pcs);
            $sheet->setCellValue('E' . $row, $d->shift1);
            $sheet->setCellValue('F' . $row, $d->shift2);
            $sheet->setCellValue('G' . $row, number_format($total_kubikasi, 2));
            $row++;
        }

        // Baris subtotal setelah tiap tanggal
        $sheet->setCellValue('F' . $row, 'Total Kubikasi Tgl ' . $date);
        $sheet->setCellValue('G' . $row, number_format($subtotal, 2));
        $sheet->getStyle('F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('G' . $row)->getFont()->setBold(true);
        $row++;
    }

    // Nama file
    $filename = 'Planning_' . $bulan . '_' . $tahun . '.xls';

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
