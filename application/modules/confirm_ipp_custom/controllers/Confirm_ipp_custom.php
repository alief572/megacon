<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Confirm_ipp_custom extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Confirm_IPP_Custom.View';
	protected $addPermission  	= 'Confirm_IPP_Custom.Add';
	protected $managePermission = 'Confirm_IPP_Custom.Manage';
	protected $deletePermission = 'Confirm_IPP_Custom.Delete';

	public function __construct()
	{
		parent::__construct();

		// $this->load->library(array('Mpdf'));
		$this->load->model(array(
			'Confirm_ipp_custom/confirm_ipp_custom_model',
			'Bom_hi_grid_custom/bom_hi_grid_custom_model'
		));

		date_default_timezone_set('Asia/Bangkok');
	}

	//========================================================BOM

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		//   $this->template->page_icon('fa fa-users');

		history("View index ipp custom");

		$this->template->title('IPP Custom/Assembly');
		$this->template->render('index');
	}

	public function get_json_ipp()
	{
		$this->confirm_ipp_custom_model->get_json_ipp();
	}

	public function add()
	{
		if ($this->input->post()) {
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			// print_r($data);
			// exit;
			$session 	= $this->session->userdata('app_session');
			$Ym			= date('y');
			$id    				= $data['id'];
			$no_ipp    			= $data['no_ipp'];
			$id_customer    	= $data['id_customer'];
			$project    		= $data['project'];
			$referensi    		= $data['referensi'];
			$id_top    			= $data['id_top'];
			$keterangan    		= $data['keterangan'];
			$delivery_type    	= $data['delivery_type'];
			$id_country    		= $data['id_country'];
			$delivery_category	= $data['delivery_category'];
			$area_destinasi    	= $data['area_destinasi'];
			$delivery_address   = $data['delivery_address'];
			$shipping_method    = $data['shipping_method'];
			$packing    		= $data['packing'];
			$guarantee    		= $data['guarantee'];
			$delivery_date    	= (!empty($data['delivery_date'])) ? date('Y-m-d', strtotime($data['delivery_date'])) : NULL;
			$instalasi_option   = $data['instalasi_option'];

			$created_by   = 'updated_by';
			$created_date = 'updated_date';
			$tanda        = 'Insert ';

			if (empty($id)) {
				//pengurutan kode
				$srcMtr			= "SELECT MAX(no_ipp) as maxP FROM custom_ipp WHERE no_ipp LIKE 'IPP_CA" . $Ym . "%' ";
				$numrowMtr		= $this->db->query($srcMtr)->num_rows();
				$resultMtr		= $this->db->query($srcMtr)->result_array();
				$angkaUrut2		= $resultMtr[0]['maxP'];
				$urutan2		= (int)substr($angkaUrut2, 11, 4);
				$urutan2++;
				$urut2			= sprintf('%04s', $urutan2);
				$no_ipp	      	= "IPP_CA" . $Ym . $urut2;

				$created_by   = 'created_by';
				$created_date = 'created_date';
				$tanda        = 'Update ';

				$rev = 0;
			} else {
				$header   	= $this->db->get_where('custom_ipp', array('id' => $id))->result();
				$rev		= $header[0]->rev + 1;
			}

			$ArrHeader		= array(
				'no_ipp'			=> $no_ipp,
				'id_customer'		=> $id_customer,
				'project'			=> $project,
				'referensi'			=> $referensi,
				'id_top'			=> $id_top,
				'keterangan'		=> $keterangan,
				'delivery_type'		=> $delivery_type,
				'id_country'		=> $id_country,
				'delivery_category'	=> $delivery_category,
				'area_destinasi'	=> $area_destinasi,
				'delivery_address'	=> $delivery_address,
				'shipping_method'	=> $shipping_method,
				'packing'			=> $packing,
				'guarantee'			=> $guarantee,
				'delivery_date'		=> $delivery_date,
				'instalasi_option'	=> $instalasi_option,
				'rev'				=> $rev,
				$created_by	    	=> $session['id_user'],
				$created_date	  	=> date('Y-m-d H:i:s')
			);


			$ArrDetail	= array();
			$ArrDetailProduct	= array();
			$ArrDetailAcc	= array();
			$ArrDetailJadi	= array();
			$ArrDetailSheet	= array();
			$ArrDetailEnd	= array();
			if (!empty($data['Detail'])) {
				$nomor = 0;
				foreach ($data['Detail'] as $val => $valx) {
					$nomor++;
					$ArrDetail[$val]['no_ipp'] 			= $no_ipp;
					$ArrDetail[$val]['no_ipp_code'] 	= $no_ipp . '-' . $nomor;
					$ArrDetail[$val]['platform'] 		= (!empty($valx['platform'])) ? $valx['platform'] : 'N';
					$ArrDetail[$val]['cover_drainage'] 	= (!empty($valx['cover_drainage'])) ? $valx['cover_drainage'] : 'N';
					$ArrDetail[$val]['facade'] 			= (!empty($valx['facade'])) ? $valx['facade'] : 'N';
					$ArrDetail[$val]['ceilling'] 		= (!empty($valx['ceilling'])) ? $valx['ceilling'] : 'N';
					$ArrDetail[$val]['partition'] 		= (!empty($valx['partition'])) ? $valx['partition'] : 'N';
					$ArrDetail[$val]['fence'] 			= (!empty($valx['fence'])) ? $valx['fence'] : 'N';
					$ArrDetail[$val]['app_others'] 		= $valx['app_others'];


					$ArrDetail[$val]['color_dark_green'] 	= (!empty($valx['color_dark_green'])) ? $valx['color_dark_green'] : 'N';
					$ArrDetail[$val]['color_dark_grey'] 	= (!empty($valx['color_dark_grey'])) ? $valx['color_dark_grey'] : 'N';
					$ArrDetail[$val]['color_light_grey'] 	= (!empty($valx['color_light_grey'])) ? $valx['color_light_grey'] : 'N';
					$ArrDetail[$val]['color_yellow'] 		= (!empty($valx['color_yellow'])) ? $valx['color_yellow'] : 'N';
					$ArrDetail[$val]['color'] 				= $valx['color'];

					$ArrDetail[$val]['food_grade'] 			= (!empty($valx['food_grade'])) ? $valx['food_grade'] : 'N';
					$ArrDetail[$val]['uv'] 					= (!empty($valx['uv'])) ? $valx['uv'] : 'N';
					$ArrDetail[$val]['fire_reterdant'] 		= (!empty($valx['fire_reterdant'])) ? $valx['fire_reterdant'] : 'N';
					$ArrDetail[$val]['industrial_type'] 	= (!empty($valx['industrial_type'])) ? $valx['industrial_type'] : 'N';
					$ArrDetail[$val]['commercial_type'] 	= (!empty($valx['commercial_type'])) ? $valx['commercial_type'] : 'N';
					$ArrDetail[$val]['superior_type'] 		= (!empty($valx['superior_type'])) ? $valx['superior_type'] : 'N';

					$ArrDetail[$val]['standard_astm'] 		= (!empty($valx['standard_astm'])) ? $valx['standard_astm'] : 'N';
					$ArrDetail[$val]['standard_bs'] 		= (!empty($valx['standard_bs'])) ? $valx['standard_bs'] : 'N';
					$ArrDetail[$val]['standard_dnv'] 		= (!empty($valx['standard_dnv'])) ? $valx['standard_dnv'] : 'N';
					$ArrDetail[$val]['file_pendukung_1'] 	= $valx['file_pendukung_1'];
					$ArrDetail[$val]['file_pendukung_2'] 	= $valx['file_pendukung_2'];

					$ArrDetail[$val]['surface_concave'] 		= (!empty($valx['surface_concave'])) ? $valx['surface_concave'] : 'N';
					$ArrDetail[$val]['surface_flat'] 			= (!empty($valx['surface_flat'])) ? $valx['surface_flat'] : 'N';
					$ArrDetail[$val]['surface_chequered_plate'] = (!empty($valx['surface_chequered_plate'])) ? $valx['surface_chequered_plate'] : 'N';
					$ArrDetail[$val]['surface_anti_skid'] 		= (!empty($valx['surface_anti_skid'])) ? $valx['surface_anti_skid'] : 'N';
					// $ArrDetail[$val]['surface_custom'] 			= $valx['surface_custom'];

					$ArrDetail[$val]['mesh_open'] 				= (!empty($valx['mesh_open'])) ? $valx['mesh_open'] : 'N';
					$ArrDetail[$val]['mesh_closed'] 			= (!empty($valx['mesh_closed'])) ? $valx['mesh_closed'] : 'N';

					$ArrDetail[$val]['type_product'] 	= $valx['type_product'];
					$ArrDetail[$val]['product_name'] 	= $valx['product_name'];
					$ArrDetail[$val]['accessories'] 	= $valx['accessories'];

					if (!empty($_FILES['photo_' . $val]["tmp_name"])) {
						$target_dir     = "assets/files/";
						$target_dir_u   = get_root3() . "/assets/files/";
						$name_file      = 'ipp-' . $val . "-" . $no_ipp . '-' . $nomor . '-' . date('Ymdhis');
						$target_file    = $target_dir . basename($_FILES['photo_' . $val]["name"]);
						$name_file_ori  = basename($_FILES['photo_' . $val]["name"]);
						$imageFileType  = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
						$nama_upload    = $target_dir_u . $name_file . "." . $imageFileType;

						// if($imageFileType == 'pdf' OR $imageFileType == 'jpeg' OR $imageFileType == 'jpg'){

						$terupload = move_uploaded_file($_FILES['photo_' . $val]["tmp_name"], $nama_upload);
						$link_url    	= $target_dir . $name_file . "." . $imageFileType;

						$ArrDetail[$val]['file_dokumen'] 		= $link_url;
						// }
					}

					if (!empty($valx['ukuran_jadi'])) {
						foreach ($valx['ukuran_jadi'] as $key => $value) {
							$UNIQ = $val . '-' . $key;
							$ArrDetailJadi[$UNIQ]['category'] = 'ukuran jadi';
							$ArrDetailJadi[$UNIQ]['no_ipp'] = $no_ipp;
							$ArrDetailJadi[$UNIQ]['no_ipp_code'] = $no_ipp . '-' . $nomor;
							$ArrDetailJadi[$UNIQ]['length'] 	= str_replace(',', '', $value['length']);
							$ArrDetailJadi[$UNIQ]['width'] 	= str_replace(',', '', $value['width']);
							$ArrDetailJadi[$UNIQ]['order'] 	= str_replace(',', '', $value['order']);
						}
					}
				}
			}

			// print_r($ArrHeader);
			// print_r($ArrDetail);
			// exit;

			$this->db->trans_start();
			if (empty($id)) {
				$this->db->insert('custom_ipp', $ArrHeader);
			}
			if (!empty($id)) {
				$this->db->where('id', $id);
				$this->db->update('custom_ipp', $ArrHeader);
			}

			$this->db->where('no_ipp', $no_ipp);
			$this->db->delete('custom_ipp_detail');

			$this->db->where('no_ipp', $no_ipp);
			$this->db->delete('custom_ipp_detail_lainnya');

			if (!empty($ArrDetail)) {
				$this->db->insert_batch('custom_ipp_detail', $ArrDetail);
			}

			if (!empty($ArrDetailJadi)) {
				$this->db->insert_batch('custom_ipp_detail_lainnya', $ArrDetailJadi);
			}
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=> 'Save gagal disimpan ...',
					'status'	=> 0
				);
			} else {
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=> 'Save berhasil disimpan. Thanks ...',
					'status'	=> 1
				);
				history($tanda . " supplier " . $no_ipp);
			}

			echo json_encode($Arr_Data);
		} else {
			$id 			= $this->uri->segment(3);
			$tanda 			= $this->uri->segment(4);
			$header   		= $this->db->get_where('custom_ipp', array('id' => $id))->result();
			$detail = [];
			if (!empty($header)) {
				$no_ipp 		= (!empty($header[0]->no_ipp)) ? $header[0]->no_ipp : 0;
				$detail   		= $this->db->get_where('custom_ipp_detail', array('no_ipp' => $no_ipp))->result_array();
			}
			$customer   	= $this->db->order_by('nm_customer', 'asc')->get_where('customer', array('deleted_date' => NULL))->result_array();
			$deliv_category = $this->db->order_by('urut', 'asc')->get_where('list', array('menu' => 'delivery rate', 'category' => 'category'))->result_array();
			$top			= $this->db->order_by('id', 'asc')->get_where('list_help', array('group_by' => 'top invoice'))->result_array();
			$shipping		= $this->db->order_by('urut', 'asc')->get_where('list', array('menu' => 'delivery rate', 'category' => 'method'))->result_array();
			$packing		= $this->db->order_by('urut', 'asc')->get_where('list', array('menu' => 'ipp', 'category' => 'packing type'))->result_array();
			$country 		= $this->db->order_by('a.name', 'asc')->get('country_all a')->result_array();

			$list_bom_topping = $this->db
				->select('a.*, b.nama AS nama_lv4, c.nama AS nama_lv3')
				->order_by('a.id_product', 'asc')
				->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')
				->join('new_inventory_3 c', 'a.id_product=c.code_lv3', 'left')
				->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'topping'))->result_array();
			// print_r($detail);
			// exit;


			$data = [
				'header' => $header,
				'detail' => $detail,
				'customer' => $customer,
				'top' => $top,
				'country' => $country,
				'deliv_category' => $deliv_category,
				'shipping' => $shipping,
				'packing_list' => $packing,
				'list_bom_topping' => $list_bom_topping,
				'tanda' => $tanda,
				'product_lv1' => get_list_inventory_lv1('product')
			];

			$explodeURL = explode('/', base_url());

			$this->template->title('Add IPP Custom');
			$this->template->page_icon('fa fa-edit');
			// $this->template->set('results', $data);
			$this->template->render('add', $data);
		}
	}

	public function get_add()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$product_lv1 = get_list_inventory_lv1('product');
		$list_bom_topping = $this->db
			->select('a.*, b.nama AS nama_lv4, c.nama AS nama_lv3')
			->order_by('a.id_product', 'asc')
			->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')
			->join('new_inventory_3 c', 'a.id_product=c.code_lv3', 'left')
			->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'topping'))->result_array();

		$d_Header = "";
		$d_Header .= "<div id='header_" . $id . "'>";
		$d_Header .= "<h4 class='text-bold text-primary'>Permintaan " . $id . "&nbsp;&nbsp;<span class='text-red text-bold delPart' data-id='" . $id . "' style='cursor:pointer;' title='Delete Part'>Delete</span></h4>";
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "<div class='col-md-2'>";
		$d_Header .= "<label>Aplikasi Kebutuhan</label>";
		$d_Header .= "</div>";
		$d_Header .= "<div class='col-md-2'>";
		$d_Header .= "<div class='form-group'>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][platform]' value='Y'>Platform</label></div>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][cover_drainage]' value='Y'>Cover Drainage</label></div>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][facade]' value='Y'>Facade</label></div>";
		$d_Header .= "</div>";
		$d_Header .= "</div>";
		$d_Header .= "<div class='col-md-2'>";
		$d_Header .= "<div class='form-group'>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][ceilling]' value='Y'>Ceilling</label></div>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][partition]' value='Y'>Partition</label></div>";
		$d_Header .= "<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][fence]' value='Y'>Fence</label></div>";
		$d_Header .= "</div>";
		$d_Header .= "</div>";
		$d_Header .= "<div class='col-md-2'>";
		$d_Header .= "<div class='form-group'><label>Other</label>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][app_others]' class='form-control input-md' placeholder='Other' value=''>";
		$d_Header .= "</div>";
		$d_Header .= "</div>";
		$d_Header .= "</div>";

		$d_Header .= "<hr>";
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Type Product</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-3'>";
		$d_Header .= "		<select name='Detail[" . $id . "][type_product]' class='form-control'>";
		foreach ($product_lv1 as $key => $value) {
			$d_Header .= "<option value='" . $value['code_lv1'] . "'>" . $value['nama'] . "</option>";
		}
		$d_Header .= "		</select>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Product Name</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-6'>";
		$d_Header .= "		<input type='text' name='Detail[" . $id . "][product_name]' class='form-control input-md' placeholder='Product Name' value=''>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		$d_Header .= "<hr>";
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Additional Spesification</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label>Additional</label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][food_grade]' value='Y'>Food Grade</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][uv]' value='Y'>UV</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][fire_reterdant]' value='Y'>Fire Reterdant</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label></label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][industrial_type]' value='Y'>Industrial Type</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][commercial_type]' value='Y'>Commercial Type</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][superior_type]' value='Y'>Superior Type</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label>Standard Spec</label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][standard_astm]' value='Y'>ASTM</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][standard_bs]' value='Y'>BS</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][standard_dnv]' value='Y'>GNV-GL</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-4'>";
		$d_Header .= "		<div class='form-group'><label>Dokumen Pendukung</label>";
		$d_Header .= "		<input type='text' class='form-control' name='Detail[" . $id . "][file_pendukung_1]' placeholder='Dokumen Pendukung 1' style='margin-bottom:5px;'>";
		$d_Header .= "		<input type='text' class='form-control' name='Detail[" . $id . "][file_pendukung_2]' placeholder='Dokumen Pendukung 2' style='margin-bottom:5px;'>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label></label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label>Color</label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][color_dark_green]' value='Y'>Dark Green</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][color_dark_grey]' value='Y'>Dark Grey</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label></label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][color_light_grey]' value='Y'>Light Grey</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][color_yellow]' value='Y'>Yellow</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label>Color Other</label>";
		$d_Header .= "		<input type='text' class='form-control' name='Detail[" . $id . "][color]' placeholder='Color Other'>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		$d_Header .= "<div class='form-group row' hidden>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label></label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label>Surface</label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][surface_concave]' value='Y'>Concave</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][surface_flat]' value='Y'>Flat</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'><label></label>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][surface_anti_skid]' value='Y'>Anti Skid</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][surface_chequered_plate]' value='Y'>Chequered Plate</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Drawing Customer</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-5'><input type='file' name='photo_" . $id . "' id='photo_" . $id . "' ></div>";
		$d_Header .= "</div>";

		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Accessories</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-6'>";
		$d_Header .= "		<input type='text' name='Detail[" . $id . "][accessories]' class='form-control input-md' placeholder='Accessories' value=''>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		$d_Header .= "<div class='form-group row' hidden>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Mesh</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<div class='form-group'>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][mesh_open]' value='Y'>Open Mesh</label></div>";
		$d_Header .= "		<div class='checkbox'><label><input type='checkbox' name='Detail[" . $id . "][mesh_closed]' value='Y'>Closed Mesh</label></div>";
		$d_Header .= "		</div>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		//ukuran jadi
		$d_Header .= "<div class='form-group row'>";
		$d_Header .= "	<div class='col-md-2'>";
		$d_Header .= "		<label>Ukuran Jadi</label>";
		$d_Header .= "	</div>";
		$d_Header .= "	<div class='col-md-5'>";
		$d_Header .= "	<table class='table table-striped table-bordered table-hover table-condensed'>";
		$d_Header .= "		<tr class='bg-blue'>";
		$d_Header .= "			<th class='text-center' width='30%'>Length</th>";
		$d_Header .= "			<th class='text-center' width='30%'>Width</th>";
		$d_Header .= "			<th class='text-center' width='30%'>Qty</th>";
		$d_Header .= "			<th class='text-center' width='10%'>#</th>";
		$d_Header .= "		</tr>";
		$new_number = 0;
		$d_Header .= "		<tr id='addjadi_" . $id . "_" . $new_number . "'>";
		$d_Header .= "			<td><button type='button' class='btn btn-sm btn-warning addPartUkj' title='Add Ukuran Jadi'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Ukuran Jadi</button></td>";
		$d_Header .= "			<td></td>";
		$d_Header .= "			<td></td>";
		$d_Header .= "			<td></td>";
		$d_Header .= "		</tr>";
		$d_Header .= "	</table>";
		$d_Header .= "	</div>";
		$d_Header .= "</div>";

		//penutup div delete
		$d_Header .= "<hr>";
		$d_Header .= "</div>";
		//add part
		$d_Header .= "<div id='add_" . $id . "'><button type='button' class='btn btn-sm btn-primary addPart' title='Add Permintaan'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Permintaan</button></td></div>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_ukuran()
	{
		$post 			= $this->input->post();

		$id_head 		= $this->uri->segment(3);
		$id 			= $this->uri->segment(4);
		$NameSave 		= $post['NameSave'];
		$LabelAdd 		= $post['LabelAdd'];
		$LabelClass 	= $post['LabelClass'];
		$idClass 		= $post['idClass'];

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr id='header" . $idClass . "_" . $id_head . "_" . $id . "'>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id_head . "][" . $NameSave . "][" . $id . "][length]' class='form-control input-md text-center autoNumeric4'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id_head . "][" . $NameSave . "][" . $id . "][width]' class='form-control input-md text-center autoNumeric4'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id_head . "][" . $NameSave . "][" . $id . "][order]' class='form-control input-md text-center autoNumeric0'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='center'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart" . $LabelClass . "' title='Delete'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "		<tr id='add" . $idClass . "_" . $id_head . "_" . $id . "'>";
		$d_Header .= "			<td><button type='button' class='btn btn-sm btn-warning addPart" . $LabelClass . "' title='Add " . $LabelAdd . "'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add " . $LabelAdd . "</button></td>";
		$d_Header .= "			<td></td>";
		$d_Header .= "		</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function confirm()
	{
		$data 		= $this->input->post();
		$session 	= $this->session->userdata('app_session');
		$id  				= $data['id'];
		$ajukan_sts  		= $data['ajukan_sts'];
		$ajukan_sts_reason  = $data['ajukan_sts_reason'];

		$ArrHeader		= array(
			'ajukan_sts'	  	=> $ajukan_sts,
			'ajukan_sts_reason'	  	=> $ajukan_sts_reason,
			'ajukan_sts_by'	=> $session['id_user'],
			'ajukan_sts_date'	=> date('Y-m-d H:i:s')
		);

		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->update('custom_ipp', $ArrHeader);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=> 'Save gagal diproses !',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=> 'Save berhasil diproses !',
				'status'	=> 1
			);
			history("Konfirmasi custom ipp: " . $id);
		}

		echo json_encode($Arr_Data);
	}

	//bom
	public function add_bom()
	{
		if ($this->input->post()) {
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			// print_r($data);
			// exit;
			$session 		 = $this->session->userdata('app_session');
			$Ym					  = date('ym');
			$id        = $data['id'];
			$no_bom        = $data['no_bom'];
			$no_bomx        = $data['no_bom'];
			$id_ipp        = $data['id_ipp'];
			$no_bomx        = $data['no_bom'];

			if ($no_bom == '') {
				$no_bom = $this->confirm_ipp_custom_model->generate_no_bom_custom();
			}

			$get_jenis_beton = $this->db->get_where('tr_jenis_beton_header', array('id_komposisi_beton' => $data['jenis_beton']))->row();
			$nm_jenis_beton = (!empty($get_jenis_beton)) ? $get_jenis_beton->nm_jenis_beton : '';

			$this->db->trans_begin();

			if ($no_bomx == '') {
				$data_header = [
					'no_bom' => $no_bom,
					'category' => 'grid custom',
					'id_product' => $data['id_product'],
					'id_variant_product' => $data['id_variant_product'],
					'variant_product' => $data['variant_product'],
					'id_jenis_beton' => $data['jenis_beton'],
					'nm_jenis_beton' => $nm_jenis_beton,
					'keterangan' => $data['keterangan'],
					'volume_m3' => $data['volume_produk'],
					'created_by' => $this->auth->user_id(),
					'created_date' => date('Y-m-d H:i:s')
				];

				$data_detail = [];
				if (isset($data['detail_material'])) {
					foreach ($data['detail_material'] as $item => $itemx) {
						$urut = sprintf('%03s', $item);

						$get_data_beton_detail = $this->db->get_where('tr_jenis_beton_detail', array('id_detail_material' => $itemx['id_detail_material']))->row();

						$id_material = (!empty($get_data_beton_detail)) ? $get_data_beton_detail->id_material : '';

						$volume_material = (isset($itemx['volume_material'])) ? $itemx['volume_material'] : 0;

						$data_detail[] = [
							'category' => 'default',
							'no_bom' => $no_bom,
							'no_bom_detail' => $no_bom . '-' . $urut,
							'code_material' => $itemx['id_detail_material'],
							'volume_m3' => $volume_material,
							'satuan_lainnya' => $itemx['satuan_lainnya'],
							'satuan' => $itemx['satuan'],
							'created_by' => $this->auth->user_id(),
							'created_date' => date('Y-m-d H:i:s')
						];
					}
				}

				$data_detail_material_lain = [];
				if (isset($data['detail_material_lain'])) {
					foreach ($data['detail_material_lain'] as $item => $itemx) {
						$data_detail_material_lain[] = [
							'no_bom' => $no_bom,
							'id_material' => $itemx['id_material'],
							'material_name' => $itemx['material_name'],
							'kebutuhan' => $itemx['kebutuhan'],
							'id_satuan' => $itemx['id_satuan'],
							'nm_satuan' => $itemx['satuan'],
							'keterangan' => $itemx['keterangan'],
							'created_by' => $this->auth->user_id(),
							'created_date' => date('Y-m-d H:i:s')
						];
					}
				}

				$insert_bom = $this->db->insert('bom_header', $data_header);
				if (!$insert_bom) {
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}

				if (!empty($data_detail)) {
					$insert_detail = $this->db->insert_batch('bom_detail', $data_detail);
					if (!$insert_detail) {
						$this->db->trans_rollback();

						print_r($this->db->last_query());
						exit;
					}
				}

				if (!empty($data_detail_material_lain)) {
					$insert_detail_material_lain = $this->db->insert_batch('bom_material_lain', $data_detail_material_lain);
					if (!$insert_detail_material_lain) {
						$this->db->trans_rollback();

						print_r($this->db->last_query());
						exit;
					}
				}

				$update_ipp_custom = $this->db->update('custom_ipp', array('no_bom' => $no_bom), array('id' => $id_ipp));
				if (!$update_ipp_custom) {
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
			} else {
				$data_header = [
					'category' => 'grid custom',
					'id_product' => $data['id_product'],
					'id_variant_product' => $data['id_variant_product'],
					'variant_product' => $data['variant_product'],
					'id_jenis_beton' => $data['jenis_beton'],
					'nm_jenis_beton' => $nm_jenis_beton,
					'keterangan' => $data['keterangan'],
					'volume_m3' => $data['volume_produk'],
					'updated_by' => $this->auth->user_id(),
					'updated_date' => date('Y-m-d H:i:s')
				];

				$data_detail = [];
				if (isset($data['detail_material'])) {
					foreach ($data['detail_material'] as $item => $itemx) {
						$urut = sprintf('%03s', $item);

						$get_data_beton_detail = $this->db->get_where('tr_jenis_beton_detail', array('id_detail_material' => $itemx['id_detail_material']))->row();

						$id_material = (!empty($get_data_beton_detail)) ? $get_data_beton_detail->id_material : '';

						$volume_material = (isset($itemx['volume_material'])) ? $itemx['volume_material'] : 0;

						$data_detail[] = [
							'category' => 'default',
							'no_bom' => $no_bom,
							'no_bom_detail' => $no_bom . '-' . $urut,
							'code_material' => $itemx['id_detail_material'],
							'volume_m3' => $volume_material,
							'satuan_lainnya' => $itemx['satuan_lainnya'],
							'satuan' => $itemx['satuan'],
							'created_by' => $this->auth->user_id(),
							'created_date' => date('Y-m-d H:i:s')
						];
					}
				}

				$data_detail_material_lain = [];
				if (isset($data['detail_material_lain'])) {
					foreach ($data['detail_material_lain'] as $item => $itemx) {
						$data_detail_material_lain[] = [
							'no_bom' => $no_bom,
							'id_material' => $itemx['id_material'],
							'material_name' => $itemx['material_name'],
							'kebutuhan' => $itemx['kebutuhan'],
							'id_satuan' => $itemx['id_satuan'],
							'nm_satuan' => $itemx['satuan'],
							'keterangan' => $itemx['keterangan'],
							'created_by' => $this->auth->user_id(),
							'created_date' => date('Y-m-d H:i:s')
						];
					}
				}

				$update_bom = $this->db->update('bom_header', $data_header, array('no_bom' => $no_bom));
				if (!$update_bom) {
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}

				if (!empty($data_detail)) {
					$refresh_detail = $this->db->delete('bom_detail', array('no_bom' => $no_bom));
					$insert_detail = $this->db->insert_batch('bom_detail', $data_detail);
					if (!$insert_detail) {
						$this->db->trans_rollback();

						print_r($this->db->last_query());
						exit;
					}
				}

				if (!empty($data_detail_material_lain)) {
					$refresh_detail_material_lain = $this->db->delete('bom_material_lain', array('no_bom' => $no_bom));
					$insert_detail_material_lain = $this->db->insert_batch('bom_material_lain', $data_detail_material_lain);
					if (!$insert_detail_material_lain) {
						$this->db->trans_rollback();

						print_r($this->db->last_query());
						exit;
					}
				}

				$update_ipp_custom = $this->db->update('custom_ipp', array('no_bom' => $no_bom), array('id' => $id_ipp));
				if (!$update_ipp_custom) {
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=> 'Save gagal disimpan ...',
					'status'	=> 0
				);
			} else {
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=> 'Save berhasil disimpan. Thanks ...',
					'status'	=> 1
				);
				// history($tanda . " BOM " . $no_bom);
			}
			echo json_encode($Arr_Data);
		} else {
			$session  = $this->session->userdata('app_session');
			$id_ipp 	  			= $this->uri->segment(3);
			$getIppCustom = $this->db->get_where('custom_ipp', array('id' => $id_ipp))->result();

			$no_bom 	  			= $this->uri->segment(4);
			$header   			= $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();
			$detail   			= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'default'))->result_array();
			$detail_hi_grid   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'hi grid std'))->result_array();
			$detail_additive   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'additive'))->result_array();
			$detail_topping   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'topping'))->result_array();
			$detail_accessories 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'accessories'))->result_array();
			$detail_mat_joint 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'mat joint'))->result_array();
			$detail_flat_sheet 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'flat sheet'))->result_array();
			$detail_end_plate 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'end plate'))->result_array();
			$detail_ukuran_jadi = $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'ukuran jadi'))->result_array();
			$detail_others 		= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'others'))->result_array();
			$product			= $this->bom_hi_grid_custom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));
			$material			= $this->bom_hi_grid_custom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));
			$accessories		= $this->bom_hi_grid_custom_model->get_data_where_array('accessories', array('deleted_date' => NULL));
			$bom_additive    	= $this->bom_hi_grid_custom_model->get_data_where_array('bom_header', array('deleted_date' => NULL, 'category' => 'additive'));
			$bom_topping    	= $this->db->select('a.*, b.nama AS nm_jenis')->join('new_inventory_3 b', 'a.id_product=b.code_lv3', 'left')->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'topping'))->result();
			$bom_higridstd1    	= $this->db->select('a.*, b.nama AS nm_jenis')->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'grid standard'))->result();
			$bom_higridstd2    	= $this->db->select('a.*, b.nama AS nm_jenis')->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'standard'))->result();
			$bom_higridstd 		= array_merge($bom_higridstd1, $bom_higridstd2);
			$satuan				= $this->bom_hi_grid_custom_model->get_data_where_array('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'));

			$get_jenis_beton = $this->db->get_where('tr_jenis_beton_header', array('deleted_by' => null))->result();

			$header_bom   = $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();

			$this->db->select('a.*, b.nm_material');
			$this->db->from('bom_detail a');
			$this->db->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.code_material', 'left');
			$this->db->where('a.no_bom', $no_bom);
			$detail_bom = $this->db->get()->result();

			$this->db->select('a.*');
			$this->db->from('bom_material_lain a');
			$this->db->where('a.no_bom', $no_bom);
			$detail_material_lain = $this->db->get()->result();

			$product    = $this->db->get_where('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product', 'code_lv1 <>' => 'P123000008'))->result();
			$material    = $this->db->get_where('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'))->result();

			$variant_product  = $this->db->get_where('list', array('menu' => 'bom std lainnya', 'category' => 'variant product'))->result();
			$color_product    = $this->db->get_where('list', array('menu' => 'bom std lainnya', 'category' => 'color'))->result();

			$jenis_beton = $this->db->get_where('tr_jenis_beton_header', ['deleted_by' => null])->result();

			// print_r($header);
			// exit;

			$bom_standard_list   = $this->db->select('a.*, b.nama')->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')->get_where('bom_header a', array('a.category' => 'standard', 'a.deleted_date' => NULL))->result_array();

			$list_satuan = $this->db->get_where('ms_satuan', ['category' => 'unit', 'deleted_by' => null])->result();

			$this->db->select('a.*');
			$this->db->from('new_inventory_4 a');
			$this->db->where('a.category', 'material');
			$this->db->where('a.deleted_by', null);
			$get_list_material = $this->db->get()->result();

			// print_r($header);
			// exit;
			$data = [
				'headerIPP' => $getIppCustom,
				'id_ipp' => $id_ipp,
				'header' => $header,
				'detail' => $detail,
				'satuan' => $satuan,
				'detail_hi_grid' => $detail_hi_grid,
				'detail_additive' => $detail_additive,
				'detail_topping' => $detail_topping,
				'detail_accessories' => $detail_accessories,
				'detail_mat_joint' => $detail_mat_joint,
				'detail_flat_sheet' => $detail_flat_sheet,
				'detail_end_plate' => $detail_end_plate,
				'detail_ukuran_jadi' => $detail_ukuran_jadi,
				'detail_others' => $detail_others,
				'product' => $product,
				'material' => $material,
				'accessories' => $accessories,
				'bom_additive' => $bom_additive,
				'bom_topping' => $bom_topping,
				'bom_higridstd' => $bom_higridstd,
				'GET_LEVEL4' => get_inventory_lv4(),
				'jenis_beton' => $get_jenis_beton,
				'list_material' => $get_list_material,
				'header_bom' => $header_bom,
				'detail_bom' => $detail_bom,
				'list_variant_product' => $variant_product,
				'list_color_product' => $color_product,
				'material' => $material,
				'jenis_beton' => $jenis_beton,
				'list_satuan' => $list_satuan,
				'detail_material_lain' => $detail_material_lain
			];

			$this->template->set('results', $data);
			$this->template->title('Add BOM Custom');
			$this->template->page_icon('fa fa-edit');
			$this->template->render('add_bom', $data);
		}
	}

	public function get_varian_product()
	{
		$post = $this->input->post();

		$this->db->select('a.code_lv3, a.nama');
		$this->db->from('new_inventory_3 a');
		$this->db->join('new_inventory_4 b', 'b.code_lv3 = a.code_lv3', 'left');
		$this->db->where('b.code_lv4', $post['id_product']);
		$get_variant_product = $this->db->get()->row();

		$hasil = [
			'id_variant_product' => $get_variant_product->code_lv3,
			'nm_variant_product' => $get_variant_product->nama
		];

		echo json_encode($hasil);
	}

	public function get_detail_material()
	{
		$post = $this->input->post();

		$hasil = '';
		$get_jenis_beton_detail = $this->db->get_where('tr_jenis_beton_detail a', ['a.id_komposisi_beton' => $post['jenis_beton']])->result();

		$no = 1;
		foreach ($get_jenis_beton_detail as $item) {

			$volume_m3 = ($post['volume_produk'] * $item->volume);

			$this->db->select('a.*, b.code as satuan');
			$this->db->from('new_inventory_4 a');
			$this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
			$this->db->where('a.code_lv4', $item->id_material);
			$get_material = $this->db->get()->row();

			$satuan_lainnya = (!empty($get_material)) ? ($volume_m3 * $get_material->konversi) : 0;
			$satuan = (!empty($get_material)) ? $get_material->satuan : '';



			$hasil .= '<tr>';

			$hasil .= '<td class="text-center">';
			$hasil .= $no;
			$hasil .= '<input type="hidden" name="detail_material[' . $no . '][id_detail_material]" value="' . $item->id_detail_material . '">';
			$hasil .= '</td>';

			$hasil .= '<td class="text-left">';
			$hasil .= $item->nm_material;
			$hasil .= '</td>';

			$hasil .= '<td class="text-center">';
			$hasil .= number_format($volume_m3, 4);
			$hasil .= '<input type="hidden" name="detail_material[' . $no . '][volume_material]" value="' . $volume_m3 . '">';
			$hasil .= '</td>';

			$hasil .= '<td class="text-center">';
			$hasil .= number_format($satuan_lainnya, 4);
			$hasil .= '<input type="hidden" name="detail_material[' . $no . '][satuan_lainnya]" value="' . $satuan_lainnya . '">';
			$hasil .= '</td>';

			$hasil .= '<td class="text-center">';
			$hasil .= ucfirst($satuan);
			$hasil .= '<input type="hidden" name="detail_material[' . $no . '][satuan]" value="' . $satuan . '">';
			$hasil .= '</td>';

			$hasil .= '</tr>';

			$no++;
		}

		echo json_encode([
			'hasil' => $hasil
		]);
	}

	public function get_list_satuan()
	{
		$hasil = '';

		$get_satuan = $this->db->get_where('ms_satuan a', ['a.category' => 'unit', 'a.deleted_by' => null])->result();

		foreach ($get_satuan as $item) {
			$hasil .= '<option value="' . $item->id . '">' . strtoupper($item->nama) . '</option>';
		}

		echo json_encode([
			'hasil' => $hasil
		]);
	}

	public function get_nm_material_lain()
	{
		$post = $this->input->post();

		$this->db->select('a.nama, a.id_unit, b.code as satuan');
		$this->db->from('new_inventory_4 a');
		$this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
		$this->db->where('a.code_lv4', $post['id_material']);
		$get_material = $this->db->get()->row();

		$nm_material = (!empty($get_material)) ? $get_material->nama : '';
		$satuan = (!empty($get_material)) ? $get_material->satuan : '';
		$id_satuan = (!empty($get_material)) ? $get_material->id_unit : '';

		echo json_encode([
			'nm_material' => $nm_material,
			'satuan' => ucfirst($satuan),
			'id_satuan' => $id_satuan
		]);
	}

	public function add_product_master($id = null)
	{
		$listData = $this->db->get_where('new_inventory_4', array('id' => $id))->result();
		$code_lv1 = (!empty($listData[0]->code_lv1)) ? $listData[0]->code_lv1 : 0;
		$code_lv2 = (!empty($listData[0]->code_lv2)) ? $listData[0]->code_lv2 : 0;

		$satuan     = $this->db->get_where('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'))->result();
		$satuan_packing = $this->db->get_where('ms_satuan', array('deleted_date' => NULL, 'category' => 'packing'))->result();

		$data = [
			'listData' => $listData,
			'listLevel1' => get_list_inventory_lv1('product'),
			'listLevel2' => (!empty(get_list_inventory_lv2('product')[$code_lv1])) ? get_list_inventory_lv2('product')[$code_lv1] : array(),
			'listLevel3' => (!empty(get_list_inventory_lv3('product')[$code_lv1][$code_lv2])) ? get_list_inventory_lv3('product')[$code_lv1][$code_lv2] : array(),
			'satuan' => $satuan,
			'satuan_packing' => $satuan_packing,
		];
		$this->template->set($data);
		$this->template->render('add_product_master');
	}

	public function get_list_product_category()
	{
		$id_product_type = $this->input->post('id_product_type');

		$get_list_product_category = $this->db->get_where('new_inventory_2', array('code_lv1' => $id_product_type, 'deleted_by' => null))->result();

		$list_product_category = array();
		foreach ($get_list_product_category as $item) {
			$list_product_category[] = [
				'code_lv2' => $item->code_lv2,
				'nama' => $item->nama
			];
		}

		echo json_encode([
			'list_product_category' => $list_product_category
		]);
	}

	public function save_new_product_master()
	{
		$post = $this->input->post();

		$data_new_kategori_produk = [];
		$code_lv1 = (isset($post['code_lv1'])) ? $post['code_lv1'] : '';
		if ($code_lv1 == '') {
			$kategori_produk_nm_1 = (isset($post['kategori_produk_nm_1'])) ? $post['kategori_produk_nm_1'] : '';
			$kategori_produk_type_code_1 = (isset($post['kategori_produk_type_code_1'])) ? $post['kategori_produk_type_code_1'] : '';

			if ($kategori_produk_nm_1 !== '' || $kategori_produk_type_code_1 !== '') {
				$kode             = 'P1' . date('y');
				$Query            = "SELECT MAX(" . $this->code . ") as maxP FROM new_inventory_1 WHERE code_lv1 LIKE '" . $kode . "%' ";
				$resultIPP        = $this->db->query($Query)->result_array();
				$angkaUrut2        = $resultIPP[0]['maxP'];
				$urutan2        = (int)substr($angkaUrut2, 4, 6);
				$urutan2++;
				$urut2            = sprintf('%06s', $urutan2);
				$kode_id        = $kode . $urut2;

				$code_lv1 = $kode_id;

				$data_new_kategori_produk = [
					'category_produk' => 'produk',
					'code_lv1' => $code_lv1,
					'nama' => $kategori_produk_nm_1,
					'code' => $kategori_produk_type_code_1,
					'status' => 1,
					'created_by' => $this->auth->user_id(),
					'created_date' => date('Y-m-d H:i:s')
				];
			}
		}

		$data_new_tipe_ukuran = [];
		$code_lv2 = (isset($post['code_lv2'])) ? $post['code_lv2'] : '';
		if ($code_lv2 == '') {
			$kategori_produk2 = (isset($post['kategori_produk2'])) ? $post['kategori_produk2'] : '';
			$tipe_ukuran_nm_2 = (isset($post['tipe_ukuran_nm_2'])) ? $post['tipe_ukuran_nm_2'] : '';
			$tipe_ukuran_category_code_2 = (isset($post['tipe_ukuran_category_code_2'])) ? $post['tipe_ukuran_category_code_2'] : '';

			if (
				$kategori_produk2 !== '' &&
				($tipe_ukuran_nm_2 !== '' || $tipe_ukuran_category_code_2 !== '')
			) {
				$kode             = 'P2' . date('y');
				$Query            = "SELECT MAX(" . $this->code . ") as maxP FROM new_inventory_2 WHERE code_lv2 LIKE '" . $kode . "%' ";
				$resultIPP        = $this->db->query($Query)->result_array();
				$angkaUrut2        = $resultIPP[0]['maxP'];
				$urutan2        = (int)substr($angkaUrut2, 4, 6);
				$urutan2++;
				$urut2            = sprintf('%06s', $urutan2);
				$kode_id        = $kode . $urut2;

				$code_lv2 = $kode_id;

				$data_new_tipe_ukuran = [
					'category' => 'product',
					'code_lv1' => $kategori_produk2,
					'code_lv2' => $code_lv2,
					'nama' => $tipe_ukuran_nm_2,
					'code' => $tipe_ukuran_category_code_2,
					'status' => '1',
					'created_by' => $this->auth->user_id(),
					'created_date' => date('Y-m-d H:i:s')
				];
			}
		}

		$data_new_varian = [];
		$code_lv3 = (isset($post['code_lv3'])) ? $post['code_lv3'] : '';
		if ($code_lv3 == '') {
			$varian_kategori_produk_3 = $post['varian_kategori_produk_3'];
			$tipe_ukuran3 = $post['tipe_ukuran3'];
			$varian_nm_3 = $post['varian_nm_3'];
			$varian_type_code_3 = $post['varian_type_code_3'];

			if (
				$varian_kategori_produk_3 !== '' &&
				(
					$varian_kategori_produk_3 !== '' ||
					$tipe_ukuran3 !== '' ||
					$varian_nm_3 !== '' ||
					$varian_type_code_3 !== ''
				)
			) {
				$kode             = 'P3' . date('y');
				$Query            = "SELECT MAX(" . $this->code . ") as maxP FROM " . $this->table_name . " WHERE " . $this->code . " LIKE '" . $kode . "%' ";
				$resultIPP        = $this->db->query($Query)->result_array();
				$angkaUrut2        = $resultIPP[0]['maxP'];
				$urutan2        = (int)substr($angkaUrut2, 4, 6);
				$urutan2++;
				$urut2            = sprintf('%06s', $urutan2);
				$kode_id        = $kode . $urut2;

				$code_lv3 = $kode_id;

				$data_new_varian = [
					'category' => 'product',
					'code_lv1' => $varian_kategori_produk_3,
					'code_lv2' => $tipe_ukuran3,
					'code_lv3' => $code_lv3,
					'nama' => $varian_nm_3,
					'code' => $varian_type_code_3,
					'status' => '1',
					'created_by' => $this->auth->user_id(),
					'created_date' => date('Y-m-d H:i:s')
				];
			}
		}

		$code_lv4 = $this->confirm_ipp_custom_model->generate_id_code_lv4();

		$this->db->trans_begin();

		$data_product_master = [
			'category' => 'product',
			'code_lv1' => $code_lv1,
			'code_lv2' => $code_lv2,
			'code_lv3' => $code_lv3,
			'code_lv4' => $code_lv4,
			'code' => $post['code'],
			'trade_name' => $post['trade_name'],
			'id_unit' => $post['id_unit'],
			'id_unit_packing' => $post['id_unit_packing'],
			'konversi' => $post['konversi'],
			'max_stok' => $post['max_stok'],
			'min_stok' => $post['min_stok'],
			'status' => 1,
			'created_by' => $this->auth->user_id(),
			'created_date' => date('Y-m-d H:i:s')
		];

		$dataProcess2 = [];
		if (!empty($_FILES['photo']["tmp_name"])) {
			$target_dir     = "assets/files/";
			$target_dir_u   = get_root3() . "/assets/files/";
			$name_file      = 'msds-' . $code_lv4 . "-" . date('Ymdhis');
			$target_file    = $target_dir . basename($_FILES['photo']["name"]);
			$name_file_ori  = basename($_FILES['photo']["name"]);
			$imageFileType  = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$nama_upload    = $target_dir_u . $name_file . "." . $imageFileType;

			$terupload = move_uploaded_file($_FILES['photo']["tmp_name"], $nama_upload);
			$link_url      = $target_dir . $name_file . "." . $imageFileType;

			$dataProcess2  = array('file_msds' => $link_url);
		}

		$data_product_master = array_merge($data_product_master, $dataProcess2);

		if (!empty($data_new_kategori_produk)) {
			$insert_kategori_produk = $this->db->insert('new_inventory_1', $data_new_kategori_produk);

			if (!$insert_kategori_produk) {
				$this->db->trans_rollback();

				print_r($this->db->last_query());
				exit;
			}
		}

		if (!empty($data_new_tipe_ukuran)) {
			$insert_tipe_ukuran = $this->db->insert('new_inventory_2', $data_new_tipe_ukuran);

			if (!$insert_tipe_ukuran) {
				$this->db->trans_rollback();

				print_r($this->db->last_query());
				exit;
			}
		}

		if (!empty($data_new_varian)) {
			$insert_new_varian = $this->db->insert('new_inventory_3', $data_new_varian);

			if (!$insert_new_varian) {
				$this->db->trans_rollback();

				print_r($this->db->last_query());
				exit;
			}
		}

		$insert_product_master = $this->db->insert('new_inventory_4', $data_product_master);
		if (!$insert_product_master) {
			$this->db->trans_rollback();

			print_r($this->db->last_query());
			exit;
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();

			$valid = 0;
			$msg = 'Please try again later !';
		} else {
			$this->db->trans_commit();

			$valid = 1;
			$msg = 'New Product Master has been inserted';
		}

		echo json_encode([
			'status' => $valid,
			'msg' => $msg
		]);
	}
}
