<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Customer
 */

class Truck_rate extends Admin_Controller
{

    //Permission
    protected $viewPermission   = "Truck_rate.View";
    protected $addPermission    = "Truck_rate.Add";
    protected $managePermission = "Truck_rate.Manage";
    protected $deletePermission = "Truck_rate.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib'));
        $this->load->model(array(
            'Truck_rate/Truck_rate_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->template->title('Truck Rate');
        $this->template->page_icon('fa fa-table');

        $this->id_user  = $this->auth->user_id();
		$this->datetime = date('Y-m-d H:i:s');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Truck Rate');
        $this->template->render('index');
    }

    public function add()
    {
        $post = $this->input->post();
        $get_product = $this->db->get_where('new_inventory_4', ['deleted_by' => null, 'category' => 'product'])->result();

        if (isset($post['id'])) {
            $get_rate_borongan = $this->db->get_where('tr_rate_borongan', ['id' => $post['id']])->row();

            $data = [
                'header' => $get_rate_borongan,
                'id' => $post['id']
            ];
            $this->template->set($data);
        }

        $this->template->set('list_product', $get_product);
        $this->template->render('add');
    }

    public function add_truck_rate_old($id = null){
        $id_truck = $id;
        $get_asset = $this->db->get_where('asset', ['deleted_by' => null, 'category' => 3])->result();//hanya untuk kategori kendaraan
        if($id_truck == NULL || $id_truck == ''){
            // print_r('1');
            // die();
            $header = null;
            $detail = null;
        }else{
            // print_r('2');
            // die();
            $header     = $this->db
                    ->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created')
                    ->join('users b', 'a.updated_by=b.id_user', 'left')
                    ->join('users c', 'a.created_by=c.id_user', 'left')
                    ->get_where(
                        'tr_truck_rate a',
                        array(
                            'a.id_truck_rate' => $id_truck
                        )
                    )
                    ->result_array();
                    // echo $this->db->last_query();
                    // die();
            $detail     = $this->db
                // ->select('a.*, b.max_stok, b.min_stok, b.nama AS nm_material')
                // ->join('new_inventory_4 b', 'a.id_material=b.code_lv4', 'left')
                ->select('a.*')
                // ->join('tr_jenis_beton_detail b', 'b.id_material = a.id_material', 'left')
                // ->join('new_inventory_4 c', 'a.id_material=c.code_lv4', 'inner')
                ->get_where(
                    'tr_truck_rate_detail a',
                    array(
                    'a.id_truck_rate' => $id_truck
                    )
                )
                ->result_array();
        }
        // die();

        $data = [
            'data_asset' => $get_asset,
            'id_truck_rate' => $id_truck,
            'header' => $header,
            'detail' => $detail
        ];

        $this->template->title('Truck Rate');
        $this->template->render('add_truck_rate', $data);
    }

    public function add_truck_rate($id = null, $tanda = null)
	{
		if (empty($id)) {
			$this->auth->restrict($this->addPermission);
		} else {
			$this->auth->restrict($this->managePermission);
		}
        $tandas = $this->uri->segment(4);
        // $tanda = '00';
        // print_r($tanda);
        // die();
		if ($this->input->post()) {
			$Arr_Kembali			= array();
			$data					= $this->input->post();
			$data_session			= $this->session->userdata;
            $tandas = $this->uri->segment(4);

			$id					= $data['id_truck'];
			$kd_asset           = $data['kd_asset'];
            $maksimal_muatan    = $data['maksimal_muatan'];
            $bahan_bakar        = $data['bahan_bakar'];
            $konsumsi_bahan_bakar = $data['konsumsi_bahan_bakar'];
            $rate_truck = $data['rate_truck'];
			// $nm_karyawan		= strtolower($data['nm_karyawan']);
			// $no_ktp				= strtolower($data['no_ktp']);
			// $tmp_lahir			= strtolower($data['tmp_lahir']);
			// $tgl_lahir			= (!empty($data['tgl_lahir'])) ? date('Y-m-d', strtotime($data['tgl_lahir'])) : NULL;
			// $gender				= $data['gender'];
			// $agama				= $data['agama'];
			// $department			= $data['department'];
			// $no_ponsel			= strtolower($data['no_ponsel']);
			// $email				= strtolower($data['email']);
			// $pendidikan			= $data['pendidikan'];
			// $ktp_kode_pos		= $data['ktp_kode_pos'];
			// $domisili_kode_pos	= $data['domisili_kode_pos'];
			// $ktp_alamat			= $data['ktp_alamat'];
			// $domisili_alamat	= $data['domisili_alamat'];
			// $npwp				= $data['npwp'];
			// $bpjs				= $data['bpjs'];
			// $tgl_join			= (!empty($data['tgl_join'])) ? date('Y-m-d', strtotime($data['tgl_join'])) : NULL;
			// $tgl_end			= (!empty($data['tgl_end'])) ? date('Y-m-d', strtotime($data['tgl_end'])) : NULL;
			// $rek_number			= $data['rek_number'];
			// $bank_account		= $data['bank_account'];
			// $sts_karyawan		= $data['sts_karyawan'];
			// $status				= $data['status'];

			$created_by 		= 'updated_by';
			$created_date 		= 'updated_date';
			$tandax 			= 'Update';

			if (empty($id)) {
				$Y = date('y');
				$created_by 		= 'created_by';
				$created_date 		= 'created_date';
				$tandax 				= 'Insert';
				//kode group
				// $q_group		= "SELECT max(nik) as maxP FROM employee WHERE nik LIKE 'ID" . $Y . "%' ";
				// $rest_group		= $this->db->query($q_group)->result_array();
				// $angka_group	= $rest_group[0]['maxP'];
				// $urut_g			= (int)substr($angka_group, 4, 5);
				// $urut_g++;
				// $urut			= sprintf('%05s', $urut_g);
				// $nik			= "ID" . $Y . $urut;
			}

			$ArrHeader1 = array(
				'kd_asset' 	=> $kd_asset,
				'maksimal_muatan' => $maksimal_muatan,
				'bahan_bakar' => $bahan_bakar,
				'konsumsi_bahan_bakar' => $konsumsi_bahan_bakar,
                'id_category_asset' => 3,//khusus kendaaraan tipenya 3 dari master kategori kendaraan id nya
				'rate_truck' => $rate_truck,
                $created_by => $this->id_user,
				$created_date => $this->datetime
			);

			//UPLOAD DOCUMENT
			// $target_dir     = "assets/files/";
			// $target_dir_u   = get_root3() . "/assets/files/";
			// $name_file      = 'ttd-' . uniqid() . "-" . date('Ymdhis');
			// $target_file    = $target_dir . basename($_FILES['tanda_tangan']["name"]);
			// $name_file_ori  = basename($_FILES['tanda_tangan']["name"]);
			// $imageFileType  = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			// $nama_upload    = $target_dir_u . $name_file . "." . $imageFileType;
			// $ArrHeader2 = [];
			// if ($imageFileType == 'jpeg' or $imageFileType == 'jpg' or $imageFileType == 'png') {
			// 	$terupload = move_uploaded_file($_FILES['tanda_tangan']["tmp_name"], $nama_upload);
			// 	$link_url    	= $target_dir . $name_file . "." . $imageFileType;

			// 	$ArrHeader2	= array('tanda_tangan' => $link_url);
			// }

			// $ArrHeader = array_merge($ArrHeader1, $ArrHeader2);
            $ArrHeader = array_merge($ArrHeader1);

			$this->db->trans_start();
			if (empty($id)) {
				$this->db->insert('tr_truck_rate', $ArrHeader);
			}
			if (!empty($id)) {
				$this->db->where('id_truck_rate', $id);
				$this->db->update('tr_truck_rate', $ArrHeader);
			}
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Process data failed. Please try again later ...',
					'status'	=> 2
				);
			} else {
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Process data Success. Thank you & have a nice day ...',
					'status'	=> 1
				);
				history($tandax . ' Truck Rate ');
			}
			echo json_encode($Arr_Kembali);
		} else {

			$restHeader 	= $this->db->get_where('tr_truck_rate', array('id_truck_rate' => $id))->result();
			// $bank 			= $this->db->get('bank')->result_array();
			// $department		= $this->db->order_by('nama')->get_where('ms_department', array('status' => '1', 'deleted_date' => NULL))->result_array();
			// $pendidikan		= $this->db->order_by('id')->get_where('list_help', array('group_by' => 'pendidikan', 'sts' => 'Y'))->result_array();
			// $agama			= $this->db->order_by('id')->get_where('list_help', array('group_by' => 'agama', 'sts' => 'Y'))->result_array();
			// $gender			= $this->db->order_by('id')->get_where('list_help', array('group_by' => 'gender', 'sts' => 'Y'))->result_array();
			// $sts_karyawan	= $this->db->order_by('id')->get_where('list_help', array('group_by' => 'status karyawan', 'sts' => 'Y'))->result_array();
			// $status			= $this->db->order_by('id')->get_where('list_help', array('group_by' => 'status aktif', 'sts' => 'Y'))->result_array();
            // $get_asset = $this->db->get_where('asset', ['deleted_by' => null, 'category' => 3])->result();//hanya untuk kategori kendaraan
            // $get_asset = $this->db->order_by('id')->get_where('asset', array('deleted_by' => null, 'category' => 3))->result_array();
            $get_asset = $this->db
                        ->select('a.*')
                        ->from('asset a')
                        ->where('a.category', 3)
                        ->where('a.deleted', 'N')
                        // ->where('(a.deleted_by IS NULL OR a.deleted_by = "")', null, false)
                        ->order_by('a.id')
                        ->get()
                        ->result_array();
            $tandas = $this->uri->segment(4);
            // echo $this->db->last_query();
            // echo $tandas;
            // die();
            $header     = $this->db
                            ->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created')
                            ->join('users b', 'a.updated_by=b.id_user', 'left')
                            ->join('users c', 'a.created_by=c.id_user', 'left')
                            ->get_where(
                                'tr_truck_rate a',
                                array(
                                    'a.id_truck_rate' => $id
                                )
                            )
                            ->result_array();
                            // echo $this->db->last_query();
                            // die();
            $detail     = $this->db
                        // ->select('a.*, b.max_stok, b.min_stok, b.nama AS nm_material')
                        // ->join('new_inventory_4 b', 'a.id_material=b.code_lv4', 'left')
                        ->select('a.*')
                        // ->join('tr_jenis_beton_detail b', 'b.id_material = a.id_material', 'left')
                        // ->join('new_inventory_4 c', 'a.id_material=c.code_lv4', 'inner')
                        ->get_where(
                            'tr_truck_rate_detail a',
                            array(
                            'a.id_truck_rate' => $id
                            )
                        )
                        ->result_array();

			// $data = [
			// 	'departmentx'	=> $department,
			// 	'pendidikanx'	=> $pendidikan,
			// 	'bankx'			=> $bank,
			// 	'agamax'		=> $agama,
			// 	'genderx'		=> $gender,
			// 	'sts_karyawanx'	=> $sts_karyawan,
			// 	'statusx'		=> $status,
			// 	'header' 		=> $restHeader,
			// 	'tanda' 		=> $tanda,
			// ];
            // echo $tandas;
            // die();
            $data = [
                'data_asset' => $get_asset,
                'id_truck_rate' => $id,
                'header' => $restHeader,
                'tandas' => $tandas
                // 'detail' => $detail
            ];
            

			$this->template->set($data);
			$this->template->title('Add Truck Rate');
			$this->template->render('add_truck_rate');
		}
	}

    public function view()
    {
        $get_product = $this->db->get_where('new_inventory_4', ['deleted_by' => null, 'category' => 'product'])->result();

        $post = $this->input->post();
        $get_rate_borongan = $this->db->get_where('tr_rate_borongan', ['id' => $post['id']])->row();

        $data = [
            'header' => $get_rate_borongan,
            'id' => $post['id']
        ];
        $this->template->set($data);
        $this->template->set('list_product', $get_product);
        $this->template->render('view');
    }

    public function detail_barang_input()
    {
        $post = $this->input->post();

        $get_product = $this->db->get_where('new_inventory_4', ['code_lv4' => $post['id_barang']])->row();

        echo json_encode([
            'nm_product' => $get_product->nama
        ]);
    }

    public function save_rate_borongan()
    {
        $post = $this->input->post();

        $this->db->trans_begin();

        if ($post['id_rate_borongan'] !== '') {
            $data_update = [
                'id_product' => $post['barang_input'],
                'nm_product' => $post['nm_barang_input'],
                'rate_borongan' => str_replace(',', '', $post['rate_produk']),
                'updated_by' => $this->auth->user_id(),
                'updated_date' => date('Y-m-d H:i:s')
            ];

            $this->db->update('tr_rate_borongan', $data_update, ['id' => $post['id_rate_borongan']]);
        } else {
            $data_insert = [];

            if (isset($post['detail_barang'])) {
                foreach ($post['detail_barang'] as $item) {
                    $data_insert[] = [
                        'id_product' => $item['id_barang'],
                        'nm_product' => $item['nm_barang'],
                        'rate_borongan' => str_replace(',', '', $item['rate_borongan']),
                        'created_by' => $this->auth->user_id(),
                        'created_date' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (!empty($data_insert)) {
                $insert_data = $this->db->insert_batch('tr_rate_borongan', $data_insert);
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Please try again later !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Save Data Success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function delete_rate_borongan()
    {
        $post = $this->input->post();

        $this->db->update('tr_rate_borongan', [
            'deleted_by' => $this->auth->user_id(),
            'deleted_date' => date('Y-m-d H:i:s')
        ], [
            'id' => $post['id']
        ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Please try again later !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Delete Data Success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function get_data_rate_borongan()
    {
        $this->Truck_rate_model->get_data_rate_borongan();
    }

    public function get_data_truck_rate()
    {
        $this->Truck_rate_model->get_data_truck_rate();
    }

    public function delete_truck_rate()
    {
        $post = $this->input->post();

        $this->db->update('tr_truck_rate', [
            'deleted_by' => $this->auth->user_id(),
            'deleted_date' => date('Y-m-d H:i:s')
        ], [
            'id_truck_rate' => $post['id']
        ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Please try again later !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Delete Data Success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }
}
