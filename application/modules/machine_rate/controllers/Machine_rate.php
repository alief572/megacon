<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Machine_rate extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Machine_Rate.View';
  protected $addPermission    = 'Machine_Rate.Add';
  protected $managePermission = 'Machine_Rate.Manage';
  protected $deletePermission = 'Machine_Rate.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Machine_rate/Machine_rate_model'
    ));
    $this->template->title('Manage Product Jenis');
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
    ];
    $listData = $this->Machine_rate_model->get_data($where);

    $data = [
      'result' =>  $listData,
      'list_asset' =>  get_list_machine(),
      'list_satuan' =>  get_list_satuan()
    ];

    history("View index machine rate");
    $this->template->set($data);
    $this->template->title('Rate Machine');
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

      $id         = $post['id'];
      $kd_mesin   = $post['kd_mesin'];

      $label      = (!empty($id)) ? 'Edit' : 'Add';

      $get_asset = $this->db->get_where('asset', ['kd_asset' => $kd_mesin])->row();



      // print_r($dataProcess);
      // exit;

      $this->db->trans_start();
      if (empty($id)) {
        $dataProcess = [
          'kd_mesin'  => $kd_mesin,
          'nm_mesin' => $get_asset->nm_asset,
          'harga_mesin' => str_replace(',', '', $post['harga']),
          'depresiasi' => str_replace(',', '', $post['depresiasi']),
          'depresiasi_per_tahun' => str_replace(',', '', $post['depresiasi_per_tahun']),
          'utilisasi_hari' => str_replace(',', '', $post['utilisasi_hari']),
          'utilisasi_m3_per_hari' => str_replace(',', '', $post['utilisasi_m3_per_hari']),
          'cost_m3' => str_replace(',', '', $post['cost_m3']),
          'created_by' => $this->auth->user_id(),
          'created_date' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('rate_machine', $dataProcess);
      } else {
        $dataProcess = [
          'kd_mesin'  => $kd_mesin,
          'nm_mesin' => $get_asset->nm_asset,
          'depresiasi' => str_replace(',', '', $post['depresiasi']),
          'depresiasi_per_tahun' => str_replace(',', '', $post['depresiasi_per_tahun']),
          'utilisasi_hari' => str_replace(',', '', $post['utilisasi_hari']),
          'utilisasi_m3_per_hari' => str_replace(',', '', $post['utilisasi_m3_per_hari']),
          'cost_m3' => str_replace(',', '', $post['cost_m3']),
          'updated_by' => $this->auth->user_id(),
          'updated_date' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        $this->db->update('rate_machine', $dataProcess);
      }
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
        history($label . " rate machine: " . $kd_mesin);
      }
      echo json_encode($status);
    } else {
      $listData   = $this->db->get_where('rate_machine', array('id' => $id))->result();
      $satuan     = $this->db->get_where('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'))->result();
      $list_asset = $this->db->group_by('SUBSTR(kd_asset, 1, 20)')->get_where('asset', array('deleted_date' => NULL, 'category' => '2'))->result_array();

      $ArrlistCT = $this->db->get_where('rate_machine', array('deleted_date' => NULL))->result_array();
      $ArrProductCT = [];
      foreach ($ArrlistCT as $key => $value) {
        $ArrProductCT[] = $value['kd_mesin'];
      }

      $data = [
        'ArrProductCT' => $ArrProductCT,
        'listData' => $listData,
        'list_asset' =>  $list_asset,
        'satuan' => $satuan
      ];
      $this->template->set($data);
      $this->template->render('add');
    }
  }

  public function view($id)
  {
    $listData   = $this->db->get_where('rate_machine', array('id' => $id))->result();
    $satuan     = $this->db->get_where('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'))->result();
    $list_asset = $this->db->group_by('SUBSTR(kd_asset, 1, 20)')->get_where('asset', array('deleted_date' => NULL, 'category' => '2'))->result_array();

    $ArrlistCT = $this->db->get_where('rate_machine', array('deleted_date' => NULL))->result_array();
    $ArrProductCT = [];
    foreach ($ArrlistCT as $key => $value) {
      $ArrProductCT[] = $value['kd_mesin'];
    }

    $data = [
      'ArrProductCT' => $ArrProductCT,
      'listData' => $listData,
      'list_asset' =>  $list_asset,
      'satuan' => $satuan
    ];
    $this->template->set($data);
    $this->template->render('view');
  }

  public function delete()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');
    $data = [
      'deleted_by'     => $this->id_user,
      'deleted_date'   => $this->datetime
    ];

    $this->db->trans_begin();
    $this->db->where('id', $id)->update("rate_machine", $data);

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
      history("Delete rate machine : " . $id);
    }
    echo json_encode($status);
  }

  public function get_list_level1($id = null)
  {
    $code_lv1 = $this->input->post('code_lv1');
    $result  = get_list_inventory_lv2('product');

    if (!empty($result[$code_lv1])) {
      $option  = "<option value='0'>Select Product Category</option>";
      foreach ($result[$code_lv1] as $val => $valx) {
        $sel = ($id == $valx['code_lv2']) ? 'selected' : '';
        $option .= "<option value='" . $valx['code_lv2'] . "' " . $sel . ">" . strtoupper($valx['nama']) . "</option>";
      }
    } else {
      $option  = "<option value='0'>List Not Found</option>";
    }

    $ArrJson  = array(
      'option' => $option
    );
    // exit;
    echo json_encode($ArrJson);
  }

  public function get_list_level3($id = null)
  {
    $code_lv1 = $this->input->post('code_lv1');
    $code_lv2 = $this->input->post('code_lv2');
    $result  = get_list_inventory_lv3('product');

    if (!empty($result[$code_lv1][$code_lv2])) {
      $option  = "<option value='0'>Select Product Jenis</option>";
      foreach ($result[$code_lv1][$code_lv2] as $val => $valx) {
        $sel = ($id == $valx['code_lv3']) ? 'selected' : '';
        $option .= "<option value='" . $valx['code_lv3'] . "' " . $sel . ">" . strtoupper($valx['nama']) . "</option>";
      }
    } else {
      $option  = "<option value='0'>List Not Found</option>";
    }

    $ArrJson  = array(
      'option' => $option
    );
    // exit;
    echo json_encode($ArrJson);
  }

  public function get_list_level4_name()
  {
    $code_lv1 = $this->input->post('code_lv1');
    $code_lv2 = $this->input->post('code_lv2');
    $code_lv3 = $this->input->post('code_lv3');

    $get_level_1 =  get_list_inventory_lv1('product');
    $get_level_2 =  get_list_inventory_lv2('product');
    $get_level_3 =  get_list_inventory_lv3('product');

    $product_type     = (!empty($get_level_1[$code_lv1]['nama'])) ? $get_level_1[$code_lv1]['nama'] : '';
    $product_category = (!empty($get_level_2[$code_lv1][$code_lv2]['nama'])) ? $get_level_2[$code_lv1][$code_lv2]['nama'] : '';
    $product_jenis     = (!empty($get_level_3[$code_lv1][$code_lv2][$code_lv3]['nama'])) ? $get_level_3[$code_lv1][$code_lv2][$code_lv3]['nama'] : '';


    $ArrJson  = array(
      'nama' => strtoupper($product_type . " " . $product_category . "; " . $product_jenis)
    );
    // exit;
    echo json_encode($ArrJson);
  }

  public function insert_select_data()
  {

    $ArrlistCT = $this->db->get_where('rate_machine', array('deleted_date' => NULL))->result_array();
    $ArrProductCT = [];
    foreach ($ArrlistCT as $key => $value) {
      $ArrProductCT[] = substr($value['kd_mesin'], 0, 20);
    }

    // echo '<pre>';
    // print_r($ArrProductCT);
    // exit;
    if (!empty($ArrProductCT)) {
      $list_asset = $this->db->select('kd_asset, nilai_asset, depresiasi, value')->group_by('SUBSTR(kd_asset, 1, 20)')->where_not_in('SUBSTR(kd_asset, 1, 20)', $ArrProductCT)->get_where('asset', array('deleted_date' => NULL, 'category' => '2'))->result_array();
    } else {
      $list_asset = $this->db->select('kd_asset, nilai_asset, depresiasi, value')->group_by('SUBSTR(kd_asset, 1, 20)')->get_where('asset', array('deleted_date' => NULL, 'category' => '2'))->result_array();
    }
    $ArrInsert = [];
    foreach ($list_asset as $key => $value) {
      $ArrInsert[$key]['kd_mesin'] = $value['kd_asset'];
      $ArrInsert[$key]['harga_mesin'] = $value['nilai_asset'];
      $ArrInsert[$key]['est_manfaat'] = $value['depresiasi'];
      $ArrInsert[$key]['depresiasi_bulan'] = $value['value'];
    }
    // print_r($list_asset);
    // exit;

    $this->db->trans_start();
    if (!empty($ArrInsert)) {
      $this->db->insert_batch('rate_machine', $ArrInsert);
    }
    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Update Failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Update Success. Thanks ...',
        'status'  => 1
      );
      history('Success insert select rate machine');
    }
    echo json_encode($Arr_Data);
  }

  public function update_kurs()
  {
    $data     = $this->input->post();
    $session   = $this->session->userdata('app_session');
    $id        = $data['id'];
    $kurs     = $this->db->order_by('id', 'desc')->limit(1)->get_where('master_kurs', array('deleted_date' => NULL))->result();

    $ArrHeader = array(
      'id_kurs'    => $kurs[0]->id,
      'kurs'  => $kurs[0]->kurs,
      'kurs_tanggal' => $kurs[0]->tanggal,
      'kurs_by'      => $session['id_user'],
      'kurs_date'  => date('Y-m-d H:i:s')
    );

    $this->db->trans_start();
    $this->db->where('id', $id);
    $this->db->update('rate_machine', $ArrHeader);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save gagal disimpan ...',
        'status'  => 0,
        'kurs' => $kurs[0]->kurs,
        'label_kurs' => number_format($kurs[0]->kurs),
        'label_kurs_date' => date('d-M-Y', strtotime($kurs[0]->tanggal)),
        'label_kurs_last' => date('d-M-Y H:i:s'),
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Kurs berhasil di update, klik Save',
        'status'  => 1,
        'kurs' => $kurs[0]->kurs,
        'label_kurs' => number_format($kurs[0]->kurs),
        'label_kurs_date' => date('d-M-Y', strtotime($kurs[0]->tanggal)),
        'label_kurs_last' => date('d-M-Y H:i:s'),
      );
      history("Update Kurs di machine rate");
    }

    echo json_encode($Arr_Data);
  }

  public function get_asset_mesin()
  {
    $post = $this->input->post();

    $get_asset = $this->db->get_where('asset', ['kd_asset' => $post['kd_mesin']])->row();

    $hasil = '<tr>';

    $hasil .= '<td>' . $get_asset->nm_asset . '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="text" name="harga" class="form-control input-md text-right" value="' . number_format($get_asset->nilai_asset, 2) . '" readonly>';
    $hasil .= '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="text" name="depresiasi" class="form-control input-md text-right" value="' . number_format($get_asset->depresiasi) . '" readonly>';
    $hasil .= '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="text" name="depresiasi_per_tahun" class="form-control input-md text-right" value="' . number_format(($get_asset->nilai_asset / $get_asset->depresiasi), 2) . '" readonly>';
    $hasil .= '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="number" class="form-control input-md text-right" name="utilisasi_hari" min="1" onkeyup="hitung_cost_m3();">';
    $hasil .= '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="text" class="form-control input-md text-right maskM" name="utilisasi_m3_per_hari" onkeyup="hitung_cost_m3();">';
    $hasil .= '</td>';

    $hasil .= '<td>';
    $hasil .= '<input type="text" class="form-control input-md text-right" name="cost_m3" readonly>';
    $hasil .= '</td>';

    $hasil .= '</tr>';

    echo json_encode([
      'hasil' => $hasil
    ]);
  }

  public function get_data_rate_machine()
  {
    $this->Machine_rate_model->get_data_rate_machine();
  }

  public function update_rate()
  {
    $data_header = [];
    $get_rate = $this->db->get_where('rate_machine', ['deleted_by' => null])->result_array();

    foreach($get_rate as $item) {
      $get_asset = $this->db->get_where('asset', ['kd_asset' => $item['kd_mesin']])->row();

      $harga_mesin = (!empty($get_asset)) ? $get_asset->nilai_asset : 0;
      $depresiasi = (!empty($get_asset)) ? $get_asset->depresiasi : 0;
      $depresiasi_per_tahun = ($harga_mesin / $depresiasi);

      $utilisasi_hari = $item['utilisasi_hari'];
      $utilisasi_m3_per_hari = $item['utilisasi_m3_per_hari'];

      $cost_m3 = ($depresiasi_per_tahun / ($utilisasi_hari * $utilisasi_m3_per_hari));

      $data_header[] = [
        'id' => $item['id'],
        'harga_mesin' => $harga_mesin,
        'depresiasi' => $depresiasi,
        'depresiasi_per_tahun' => $depresiasi_per_tahun,
        'cost_m3' => $cost_m3
      ];
    }

    $this->db->update_batch('rate_machine', $data_header, 'id');
    if($this->db->trans_status() === false) {
      $this->db->trans_rollback();

      $valid = 0;
      $msg = 'Please try again later !';
    } else {
      $this->db->trans_commit();

      $valid = 1;
      $msg = 'Update rate success !';
    }

    echo json_encode([
      'status' => $valid,
      'msg' => $msg
    ]);
  }
}
