<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_gudang_produksi extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Gudang_Produksi.View';
  protected $addPermission    = 'Gudang_Produksi.Add';
  protected $managePermission = 'Gudang_Produksi.Manage';
  protected $deletePermission = 'Gudang_Produksi.Delete';

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Stok_gudang_produksi/stok_gudang_produksi_model'
    ));
    $this->template->title('Manage Data Supplier');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  //==========================================================================================================
  //============================================STOCK=========================================================
  //==========================================================================================================

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');

    $listGudang     = $this->db->get_where('warehouse', array('desc' => 'costcenter'))->result_array();
    $data = [
      'listGudang' => $listGudang
    ];

    history("View data gudang produksi");
    $this->template->title('Gudang Material / Gudang Produksi / Stok');
    $this->template->render('index', $data);
  }

  public function data_side_stock()
  {
    $this->stok_gudang_produksi_model->get_json_stock();
  }

  public function modal_history()
  {
    $data     = $this->input->post();
    $gudang   = $data['gudang'];
    $material = $data['material'];

    $sql = "SELECT a.* FROM warehouse_history a WHERE a.id_gudang='" . $gudang . "' AND a.id_material='" . $material . "' ORDER BY a.id ASC ";
    $data = $this->db->query($sql)->result_array();

    $dataArr = array(
      'gudang' => $gudang,
      'material' => $material,
      'data'  => $data
    );
    $this->load->view('modal_history', $dataArr);
  }

  public function download_excel()
  {
    $tanggal = $this->uri->segment(3);
    $id_gudang = $this->uri->segment(4);

    $nm_gudang = '';
    if ($id_gudang !== '0') {
      $get_gudang = $this->db->get_where('warehouse', ['id' => $id_gudang])->row_array();
      if (!empty($get_gudang)) {
        $nm_gudang = $get_gudang['nm_gudang'];
      }
    } else {
      $nm_gudang = 'All Costcenter';
    }

    $get_material = $this->db->get_where('new_inventory_4', ['deleted_by' => null, 'category' => 'material'])->result_array();
    $get_satuan = $this->db->get_where('ms_satuan', ['deleted' => 'N'])->result_array();

    $list_packing = [];
    $list_unit = [];

    foreach ($get_satuan as $item_satuan) {
      if ($item_satuan['category'] == 'unit') {
        $list_unit[$item_satuan['id']] = $item_satuan['code'];
      } else {
        $list_packing[$item_satuan['id']] = $item_satuan['code'];
      }
    }

    $getIdCost = $this->db->get_where('warehouse', ['desc' => 'costcenter'])->result_array();
    $arrIdCost = [];
    foreach ($getIdCost as $idCost) {
      $arrIdCost[] = $idCost['id'];
    }


    if (date('Y-m-d', strtotime($tanggal)) == date('Y-m-d')) {
      $this->db->select('a.id_material, a.nm_material, a.qty_stock as stok');
      $this->db->from('warehouse_stock a');
      $this->db->join('new_inventory_4 b', 'b.code_lv4 = a.id_material');
      if ($id_gudang !== '0') {
        $this->db->where('a.id_gudang', $id_gudang);
      } else {
        $this->db->where_in('a.id_gudang', $arrIdCost);
      }
      $this->db->group_by('a.id_material');
      $get_stok_material = $this->db->get()->result_array();
    } else {
      $this->db->select('a.id_material, a.nm_material, max(a.qty_stock) as stok');
      $this->db->from('warehouse_stock_per_day a');
      $this->db->where('DATE_FORMAT(a.hist_date, "%Y-%m-%d") = ', $tanggal);
      if ($id_gudang !== '0') {
        $this->db->where('a.id_gudang', $id_gudang);
      } else {
        $this->db->where_in('a.id_gudang', $arrIdCost);
      }
      $this->db->group_by('a.id_material');
      $get_stok_material = $this->db->get()->result_array();
    }

    $list_stok = [];
    foreach ($get_stok_material as $item_stok) {
      $list_stok[$item_stok['id_material']] = $item_stok['stok'];
    }

    $data = [
      'list_material' => $get_material,
      'list_unit' => $list_unit,
      'list_packing' => $list_packing,
      'list_stok' => $list_stok,
      'tanggal' => $tanggal,
      'nm_gudang' => $nm_gudang
    ];

    // $this->load->set('results', $data);
    $this->load->view('excel_stok_produksi', ['results' => $data]);
  }
}
