<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bom_hi_grid_custom extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'BOM_HI-Grid_Custom.View';
	protected $addPermission  	= 'BOM_HI-Grid_Custom.Add';
	protected $managePermission = 'BOM_HI-Grid_Custom.Manage';
	protected $deletePermission = 'BOM_HI-Grid_Custom.Delete';

	public function __construct()
	{
		parent::__construct();

		// $this->load->library(array('Mpdf'));
		$this->load->model(array(
			'Bom_hi_grid_custom/bom_hi_grid_custom_model'
		));

		date_default_timezone_set('Asia/Bangkok');
	}

	//========================================================BOM

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->bom_hi_grid_custom_model->get_data('bom_header', 'deleted', 'N');
		history("View index BOM Assembly");
		$this->template->set('results', $data);
		$this->template->title('BOM Assembly');
		$this->template->render('index');
	}

	public function data_side_bom()
	{
		$this->bom_hi_grid_custom_model->get_json_bom();
	}

	public function add()
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
			
			$no_bomx        = $data['no_bom'];

			$get_ipp_custom = $this->db->get_where('custom_ipp', array('no_bom' => $no_bom))->row();

			$id_ipp        = (!empty($get_ipp_custom)) ? $get_ipp_custom->id : '';

			$get_jenis_beton = $this->db->get_where('tr_jenis_beton_header', array('id_komposisi_beton' => $data['jenis_beton']))->row();
			$nm_jenis_beton = (!empty($get_jenis_beton)) ? $get_jenis_beton->nm_jenis_beton : '';

			$this->db->trans_begin();

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
			

			$no_bom 	  			= $this->uri->segment(3);

			$get_ipp_custom = $this->db->get_where('custom_ipp', array('no_bom' => $no_bom))->row();
			$id_ipp = (!empty($get_ipp_custom)) ? $get_ipp_custom->id : '';

			$getIppCustom = $this->db->get_where('custom_ipp', array('id' => $id_ipp))->result();

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
				'detail_material_lain' => $detail_material_lain,
				'id_ipp' => ''
			];

			$this->template->set('results', $data);
			$this->template->title('Edit BOM Custom');
			$this->template->page_icon('fa fa-edit');
			$this->template->render('add', $data);
		}
	}

	public function detail()
	{
		// $this->auth->restrict($this->viewPermission);
		$no_bom 	= $this->input->post('no_bom');

		$this->db->select('a.*, b.nm_jenis_beton');
		$this->db->from('bom_header a');
		$this->db->join('tr_jenis_beton_header b', 'b.id_komposisi_beton = a.id_jenis_beton', 'left');
		$this->db->where('a.no_bom', $no_bom);
		$header = $this->db->get()->result();

		$this->db->select('a.*, b.nm_material');
		$this->db->from('bom_detail a');
		$this->db->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.code_material', 'left');
		$this->db->where('a.no_bom', $no_bom);
		$detail = $this->db->get()->result();

		$this->db->select('a.*, b.code as satuan, c.nama as nm_material');
		$this->db->from('bom_material_lain a');
		$this->db->join('ms_satuan b', 'b.id = a.id_satuan', 'left');
		$this->db->join('new_inventory_4 c', 'c.code_lv4 = a.id_material', 'left');
		$this->db->where('a.no_bom', $no_bom);
		$detail_material_lain = $this->db->get()->result();

		$product    = $this->db->get_where('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'))->result();
		// print_r($header);
		$data = [
			'header' => $header,
			'detail' => $detail,
			'detail_material_lain' => $detail_material_lain,
			'product' => $product,
			'GET_LEVEL4' => get_inventory_lv4(),
		];
		$this->template->set('results', $data);
		$this->template->render('detail', $data);
	}

	public function get_add()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='header_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='Detail[" . $id . "][code_material]' class='chosen_select form-control input-sm inline-blockd material'>";
		$d_Header .= "<option value='0'>Select Material Name</option>";
		foreach ($material as $valx) {
			$d_Header .= "<option value='" . $valx->code_lv4 . "'>" . strtoupper($valx->nama) . "</option>";
		}
		$d_Header .= 		"</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][weight]' class='form-control input-md autoNumeric4 qty' placeholder='Weight'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addPart' title='Add Material'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function hapus()
	{
		$data = $this->input->post();
		$session 		= $this->session->userdata('app_session');
		$no_bom  = $data['id'];

		$ArrHeader		= array(
			'deleted'			  => "Y",
			'deleted_by'	  => $session['id_user'],
			'deleted_date'	=> date('Y-m-d H:i:s')
		);

		$this->db->trans_start();
		$this->db->where('no_bom', $no_bom);
		$this->db->update('bom_header', $ArrHeader);
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
			history("Delete data BOM " . $no_bom);
		}

		echo json_encode($Arr_Data);
	}

	public function excel_report_all_bom()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();

		$tableHeader 	= tableHeader();
		$mainTitle 		= mainTitle();
		$tableBodyCenter = tableBodyCenter();
		$tableBodyLeft 	= tableBodyLeft();
		$tableBodyRight = tableBodyRight();

		$sheet 		= $objPHPExcel->getActiveSheet();

		$product    = $this->db
			->select('a.*, b.nama AS nm_product')
			->order_by('a.no_bom', 'desc')
			->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')
			->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'grid custom'))
			->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(12);
		$sheet->setCellValue('A' . $Row, 'BOM HI GRID STANDARD');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;
		$NextRow = $NewRow + 1;

		$sheet->setCellValue('A' . $NewRow, 'No');
		$sheet->getStyle('A' . $NewRow . ':A' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('A' . $NewRow . ':A' . $NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B' . $NewRow, 'Product Name');
		$sheet->getStyle('B' . $NewRow . ':B' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('B' . $NewRow . ':B' . $NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C' . $NewRow, 'Variant');
		$sheet->getStyle('C' . $NewRow . ':C' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('C' . $NewRow . ':C' . $NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$sheet->setCellValue('D' . $NewRow, 'Total Weight');
		$sheet->getStyle('D' . $NewRow . ':D' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('D' . $NewRow . ':D' . $NextRow);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$sheet->setCellValue('E' . $NewRow, 'Waste Product (%)');
		$sheet->getStyle('E' . $NewRow . ':E' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('E' . $NewRow . ':E' . $NextRow);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$sheet->setCellValue('F' . $NewRow, 'Waste Setting/Cleaning (%)');
		$sheet->getStyle('F' . $NewRow . ':F' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('F' . $NewRow . ':F' . $NextRow);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		$sheet->setCellValue('G' . $NewRow, 'Fire Reterdant');
		$sheet->getStyle('G' . $NewRow . ':G' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('G' . $NewRow . ':G' . $NextRow);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		$sheet->setCellValue('H' . $NewRow, 'Anti UV');
		$sheet->getStyle('H' . $NewRow . ':H' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('H' . $NewRow . ':H' . $NextRow);
		$sheet->getColumnDimension('H')->setAutoSize(true);

		$sheet->setCellValue('I' . $NewRow, 'Tixotropic');
		$sheet->getStyle('I' . $NewRow . ':I' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('I' . $NewRow . ':I' . $NextRow);
		$sheet->getColumnDimension('I')->setAutoSize(true);

		$sheet->setCellValue('J' . $NewRow, 'Food Grade');
		$sheet->getStyle('J' . $NewRow . ':J' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('J' . $NewRow . ':J' . $NextRow);
		$sheet->getColumnDimension('J')->setAutoSize(true);

		$sheet->setCellValue('K' . $NewRow, 'Wax');
		$sheet->getStyle('K' . $NewRow . ':K' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('K' . $NewRow . ':K' . $NextRow);
		$sheet->getColumnDimension('K')->setAutoSize(true);

		$sheet->setCellValue('L' . $NewRow, 'Corrosion');
		$sheet->getStyle('L' . $NewRow . ':L' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('L' . $NewRow . ':L' . $NextRow);
		$sheet->getColumnDimension('L')->setAutoSize(true);

		if ($product) {
			$awal_row	= $NextRow;
			$no = 0;
			foreach ($product as $key => $row_Cek) {
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomor	= $no;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nomor);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$nm_product	= $row_Cek['nm_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nm_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$variant_product	= $row_Cek['variant_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $variant_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$SUM_WEIGHT = $this->db->query("SELECT SUM(weight) AS berat FROM bom_detail WHERE no_bom = '" . $row_Cek['no_bom'] . "' ")->result();
				$awal_col++;
				$status_date	= number_format($SUM_WEIGHT[0]->berat, 4);
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$waste_product	= $row_Cek['waste_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$waste_setting	= $row_Cek['waste_setting'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_setting);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$fire_retardant	= ($row_Cek['fire_retardant'] == 1) ? 'Yes' : 'No';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $fire_retardant);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$anti_uv	= ($row_Cek['anti_uv'] == 1) ? 'Yes' : 'No';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $anti_uv);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$tixotropic	= ($row_Cek['tixotropic'] == 1) ? 'Yes' : 'No';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $tixotropic);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$food_grade	= ($row_Cek['food_grade'] == 1) ? 'Yes' : 'No';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $food_grade);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$wax	= ($row_Cek['wax'] == 1) ? 'Yes' : 'No';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $wax);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$corrosion	= ($row_Cek['corrosion'] != 0 or $row_Cek['corrosion'] != NULL) ? $row_Cek['corrosion'] : '-';
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $corrosion);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);
			}
		}

		$sheet->setTitle('BOM');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="bom-hi-grid-custom.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function excel_report_all_bom_detail()
	{
		$kode_bom = $this->uri->segment(3);
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();

		$tableHeader 	= tableHeader();
		$mainTitle 		= mainTitle();
		$tableBodyCenter = tableBodyCenter();
		$tableBodyLeft 	= tableBodyLeft();
		$tableBodyRight = tableBodyRight();

		$sheet 		= $objPHPExcel->getActiveSheet();

		$sql = "
  			SELECT
  				a.id_product,
				a.variant_product,
          		b.code_material,
          		b.weight,
				c.nama AS nm_product
  			FROM
  				bom_header a 
				LEFT JOIN bom_detail b ON a.no_bom = b.no_bom
				LEFT JOIN new_inventory_4 c ON a.id_product = c.code_lv4
  		    WHERE 
				a.no_bom = '" . $kode_bom . "' 
				AND b.no_bom = '" . $kode_bom . "'
				AND a.category = 'grid custom'
  			ORDER BY
  				b.id ASC
  		";
		$product    = $this->db->query($sql)->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(3);
		$sheet->setCellValue('A' . $Row, 'BOM HI GRID STANDARD DETAIL');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;

		$sheet->setCellValue('A' . $NewRow, $product[0]['nm_product']);
		$sheet->getStyle('A' . $NewRow . ':C' . $NewRow)->applyFromArray($tableBodyLeft);
		$sheet->mergeCells('A' . $NewRow . ':C' . $NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$NewRow	 = $NewRow + 1;
		$NextRow = $NewRow;

		$sheet->setCellValue('A' . $NewRow, $product[0]['variant_product']);
		$sheet->getStyle('A' . $NewRow . ':C' . $NewRow)->applyFromArray($tableBodyLeft);
		$sheet->mergeCells('A' . $NewRow . ':C' . $NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$NewRow	 = $NewRow + 2;
		$NextRow = $NewRow;

		$sheet->setCellValue('A' . $NewRow, 'No');
		$sheet->getStyle('A' . $NewRow . ':A' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('A' . $NewRow . ':A' . $NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B' . $NewRow, 'Material Name');
		$sheet->getStyle('B' . $NewRow . ':B' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('B' . $NewRow . ':B' . $NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C' . $NewRow, 'Total Weight');
		$sheet->getStyle('C' . $NewRow . ':C' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('C' . $NewRow . ':C' . $NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		if ($product) {
			$awal_row	= $NextRow;
			$no = 0;
			foreach ($product as $key => $row_Cek) {
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $no);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$status_date	= strtoupper(get_name('new_inventory_4', 'nama', 'code_lv4', $row_Cek['code_material']));
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$status_date	= number_format($row_Cek['weight'], 4);
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);
			}
		}


		$sheet->setTitle('List BOM DETAIL');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="bom-hi-grid-custom-detail-' . $kode_bom . '.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function get_add_additive()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('bom_header', array('deleted_date' => NULL, 'category' => 'additive'));
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headeradditive_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailAdt[" . $id . "][code_material]' data-id='" . $id . "' class='chosen_select form-control input-sm inline-blockd changeFungsiAdditive'>";
		$d_Header .= "<option value='0'>Select Additive</option>";
		foreach ($material as $valx) {
			$d_Header .= "<option value='" . $valx->no_bom . "'>" . strtoupper($valx->additive_name) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<table class='table table-bordered additiveMat" . $id . "'></table>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='addadditive_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-info addPartAdditive' title='Add Additive'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Additive</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_additive_breakdown()
	{
		$id 		= $this->uri->segment(3);
		$id_row 	= $this->uri->segment(4);


		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('bom_detail', array('no_bom' => $id));
		$GET_LEVEL4 = get_inventory_lv4();

		$d_Header = "";
		$nomor = 0;
		foreach ($material as $valx) {
			$nomor++;
			$nm_material = (!empty($GET_LEVEL4[$valx->code_material]['nama'])) ? $GET_LEVEL4[$valx->code_material]['nama'] : '';
			$datetime 	= $id_row . '-' . $nomor;

			$d_Header .= "<tr>";
			$d_Header .= "<td width='70%'>";
			$d_Header .= "<input type='hidden' name='DetailAdt[" . $id_row . "][detail][" . $datetime . "][code_material]' class='form-control input-md' value='" . $valx->code_material . "'>";
			$d_Header .= "<input type='text' name='DetailAdt[" . $id_row . "][detail][" . $datetime . "][nm_material]' class='form-control input-md' value='" . $nm_material . "' readonly>";
			$d_Header .= "</td>";
			$d_Header .= "<td>";
			$d_Header .= "<input type='text' name='DetailAdt[" . $id_row . "][detail][" . $datetime . "][berat]' class='form-control input-md autoNumeric4 qty' placeholder='Weight'>";
			$d_Header .= "</td>";
			$d_Header .= "</tr>";
		}

		echo json_encode(array(
			'material'	=> $d_Header
		));
	}

	public function get_add_topping()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$GET_LEVEL3 = get_inventory_lv3();

		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('bom_header', array('deleted_date' => NULL, 'category' => 'topping'));
		$satuan		= $this->bom_hi_grid_custom_model->get_data_where_array('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'));
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headertopping_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailTop[" . $id . "][code_material]' data-id='" . $id . "' class='chosen_select form-control input-sm inline-blockd changeFungsiTopping'>";
		$d_Header .= "<option value='0'>Select Topping</option>";
		foreach ($material as $valx) {
			$nm_jenis = (!empty($GET_LEVEL3[$valx->id_product]['nama'])) ? $GET_LEVEL3[$valx->id_product]['nama'] : '';
			$d_Header .= "<option value='" . $valx->no_bom . "'>" . strtoupper($nm_jenis . ' | ' . $valx->variant_product) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<table class='table table-bordered toppingMat" . $id . "'></table>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailTop[" . $id . "][qty]' class='form-control input-md autoNumeric0 text-center' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailTop[" . $id . "][unit]' data-id='" . $id . "' class='chosen_select form-control input-sm inline-blockd'>";
		$d_Header .= "<option value='0'>Select Unit</option>";
		foreach ($satuan as $valx) {
			$d_Header .= "<option value='" . $valx->id . "'>" . strtoupper($valx->code) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailTop[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='addtopping_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-success addPartTopping' title='Add Topping'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Topping</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_topping_breakdown()
	{
		$id 		= $this->uri->segment(3);
		$id_row 	= $this->uri->segment(4);


		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('bom_detail', array('no_bom' => $id));
		$GET_LEVEL4 = get_inventory_lv4();

		$d_Header = "";
		$nomor = 0;
		foreach ($material as $valx) {
			$nomor++;
			$nm_material = (!empty($GET_LEVEL4[$valx->code_material]['nama'])) ? $GET_LEVEL4[$valx->code_material]['nama'] : '';
			$datetime 	= $id_row . '-' . $nomor;

			$d_Header .= "<tr>";
			$d_Header .= "<td width='70%'>";
			$d_Header .= "<input type='hidden' name='DetailTop[" . $id_row . "][detail][" . $datetime . "][code_material]' class='form-control input-md' value='" . $valx->code_material . "'>";
			$d_Header .= "<input type='text' name='DetailTop[" . $id_row . "][detail][" . $datetime . "][nm_material]' class='form-control input-md' value='" . $nm_material . "' readonly>";
			$d_Header .= "</td>";
			$d_Header .= "<td>";
			$d_Header .= "<input type='text' name='DetailTop[" . $id_row . "][detail][" . $datetime . "][berat]' class='form-control input-md autoNumeric4 qty' placeholder='Weight'  readonly value='" . $valx->weight . "'>";
			$d_Header .= "</td>";
			$d_Header .= "</tr>";
		}

		echo json_encode(array(
			'material'	=> $d_Header
		));
	}

	public function get_add_accessories()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$accessories    = $this->bom_hi_grid_custom_model->get_data_where_array('accessories', array('deleted_date' => NULL));
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headeraccessories_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailAcc[" . $id . "][code_material]' class='chosen_select form-control input-sm inline-blockd material'>";
		$d_Header .= "<option value='0'>Select Accessories</option>";
		foreach ($accessories as $valx) {
			$d_Header .= "<option value='" . $valx->id . "'>" . strtoupper($valx->stock_name . ' ' . $valx->brand . ' ' . $valx->spec) . "</option>";
		}
		$d_Header .= 		"</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailAcc[" . $id . "][weight]' class='form-control input-md autoNumeric4 qty' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailAcc[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='addaccessories_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartAcc' title='Add Accessories'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Accessories</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_mat_joint()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$material    = get_list_inventory_lv4('material');
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headermatjoint_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailMatJoint[" . $id . "][layer]' class='form-control input-md' placeholder='Layer'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailMatJoint[" . $id . "][code_material]' class='chosen_select form-control input-sm inline-blockd material'>";
		$d_Header .= "<option value='0'>Select Material</option>";
		foreach ($material as $valx => $value) {
			$d_Header .= "<option value='" . $value['code_lv4'] . "'>" . strtoupper($value['nama']) . "</option>";
		}
		$d_Header .= 		"</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailMatJoint[" . $id . "][weight]' class='form-control input-md autoNumeric4 qty' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailMatJoint[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='addmatjoint_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPartMatJoint' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material Joint</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_flat_sheet()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerflatsheet_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailFlat[" . $id . "][length]' class='form-control input-md text-center autoNumeric4 length changeFlat' placeholder='Length'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailFlat[" . $id . "][width]' class='form-control input-md text-center autoNumeric4 width changeFlat' placeholder='Width'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailFlat[" . $id . "][qty]' class='form-control input-md text-center autoNumeric4' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailFlat[" . $id . "][m2]' class='form-control input-md text-center autoNumeric4 resultM2' placeholder='M2' readonly>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part

		$d_Header .= "<tr id='headerflatsheet_" . $id . "_" . $no . "' class='headerflatsheet_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMat' data-label_class='headerflatsheet' data-label_name='DetailFlat' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		$d_Header .= "<tr id='addflatsheet_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-danger addPartFlat' title='Add Flat Sheet'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Flat Sheet</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_end_plate()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerendplate_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailEnd[" . $id . "][length]' class='form-control input-md text-center autoNumeric4 length changeEnd' placeholder='Length'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailEnd[" . $id . "][width]' class='form-control input-md text-center autoNumeric4 width changeEnd' placeholder='Height'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailEnd[" . $id . "][qty]' class='form-control input-md text-center autoNumeric4' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailEnd[" . $id . "][m2]' class='form-control input-md text-center autoNumeric4 resultM2' placeholder='M2' readonly>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part

		$d_Header .= "<tr id='headerendplate_" . $id . "_" . $no . "' class='headerendplate_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMat' data-label_class='headerendplate' data-label_name='DetailEnd' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		$d_Header .= "<tr id='addendplate_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addPartEnd' title='Add End Plate'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add End Plate</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_ukuran_jadi()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerukuranjadi_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailJadi[" . $id . "][length]' class='form-control input-md text-center autoNumeric4 length changeEnd' placeholder='Length'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailJadi[" . $id . "][width]' class='form-control input-md text-center autoNumeric4 width changeEnd' placeholder='Width'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailJadi[" . $id . "][qty]' class='form-control input-md text-center autoNumeric4' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailJadi[" . $id . "][m2]' class='form-control input-md text-center autoNumeric4 resultM2' placeholder='M2' readonly>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part

		$d_Header .= "<tr id='headerukuranjadi_" . $id . "_" . $no . "' class='headerukuranjadi_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMat' data-label_class='headerukuranjadi' data-label_name='DetailJadi' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		$d_Header .= "<tr id='addukuranjadi_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-success addPartJadi' title='Add Ukuran Jadi'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Chequered Plate</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_others()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerothers_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailOthers[" . $id . "][length]' class='form-control input-md text-center autoNumeric4 length changeEnd' placeholder='Length'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailOthers[" . $id . "][width]' class='form-control input-md text-center autoNumeric4 width changeEnd' placeholder='Width'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailOthers[" . $id . "][qty]' class='form-control input-md text-center autoNumeric4' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailOthers[" . $id . "][m2]' class='form-control input-md text-center autoNumeric4 resultM2' placeholder='M2' readonly>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part

		$d_Header .= "<tr id='headerothers_" . $id . "_" . $no . "' class='headerothers_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMat' data-label_class='headerothers' data-label_name='DetailOthers' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		$d_Header .= "<tr id='addothers_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-success addPartOthers' title='Add Others'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Others</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_hi_grid_std()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$GET_LEVEL3 = get_inventory_lv4();

		$material1    	= $this->bom_hi_grid_custom_model->get_data_where_array('bom_header', array('deleted_date' => NULL, 'category' => 'grid standard'));
		$material2    	= $this->bom_hi_grid_custom_model->get_data_where_array('bom_header', array('deleted_date' => NULL, 'category' => 'standard'));
		$satuan			= $this->bom_hi_grid_custom_model->get_data_where_array('ms_satuan', array('deleted_date' => NULL, 'category' => 'unit'));

		$material = array_merge($material1, $material2);
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailHiGrid[" . $id . "][code_material]' data-id='" . $id . "' class='chosen_select form-control input-sm inline-blockd changeFungsiHiGrid'>";
		$d_Header .= "<option value='0'>Select BOM Standard</option>";
		foreach ($material as $valx) {
			$nm_jenis = (!empty($GET_LEVEL3[$valx->id_product]['nama'])) ? $GET_LEVEL3[$valx->id_product]['nama'] : '';
			$d_Header .= "<option value='" . $valx->no_bom . "'>" . strtoupper($nm_jenis . ' | ' . $valx->variant_product) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left' hidden>";
		$d_Header .= "<table class='table table-bordered higridMat" . $id . "'></table>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][qty]' class='form-control input-md autoNumeric0 text-center' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailHiGrid[" . $id . "][unit]' data-id='" . $id . "' class='chosen_select form-control input-sm inline-blockd'>";
		$d_Header .= "<option value='0'>Select Unit</option>";
		foreach ($satuan as $valx) {
			$d_Header .= "<option value='" . $valx->id . "'>" . strtoupper($valx->code) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='addhigrid_" . $id . "_" . $no . "' class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPart' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Ukuran Jadi</button></td>";
		$d_Header .= "<td align='center' hidden></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		$d_Header .= "<tr id='addhigridcutting_" . $id . "_" . $no . "' class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMatCut' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material Cutting</button></td>";
		$d_Header .= "<td align='center' hidden></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='addhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addPartHiGrid' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Standard & HI GRID Standard</button></td>";
		$d_Header .= "<td align='center' hidden></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_sub_ukuran_jadi()
	{
		$id 	= $this->uri->segment(3);
		$no 	= $this->uri->segment(4);

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<div class='input-group'>";
		$d_Header .= "<span class='input-group-addon' style='background: bisque;'>Length :</span>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][ukuran_jadi][" . $no . "][length]' class='form-control input-md autoNumeric'>";
		$d_Header .= "<span class='input-group-addon' style='background: bisque;'>Width :</span>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][ukuran_jadi][" . $no . "][width]' class='form-control input-md autoNumeric'>";
		$d_Header .= "<span class='input-group-addon' style='background: bisque;'>Qty :</span>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][ukuran_jadi][" . $no . "][qty]' class='form-control input-md autoNumeric'>";
		$d_Header .= "<span class='input-group-addon' style='background: bisque;'>Meter Lari :</span>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][ukuran_jadi][" . $no . "][lari]' class='form-control input-md autoNumeric'>";
		$d_Header .= "</div>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left' hidden></td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delSubPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='addhigrid_" . $id . "_" . $no . "' class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPart' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Ukuran Jadi</button></td>";
		$d_Header .= "<td align='center' hidden></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_sub_cutting_material()
	{
		$id 	= $this->uri->segment(3);
		$no 	= $this->uri->segment(4);

		$material    = get_list_inventory_lv4('material');

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='DetailHiGrid[" . $id . "][cutting][" . $no . "][id_material]' data-id='" . $id . "' class='chosen-select form-control input-sm inline-blockd'>";
		$d_Header .= "<option value='0'>Select Material</option>";
		foreach ($material as $valx => $value) {
			$d_Header .= "<option value='" . $value['code_lv4'] . "'>" . strtoupper($value['nama']) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left' hidden></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='DetailHiGrid[" . $id . "][cutting][" . $no . "][weight]' class='form-control input-md text-center autoNumeric4 qty' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delSubPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='addhigridcutting_" . $id . "_" . $no . "' class='headerhigrid_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMatCut' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material Cutting</button></td>";
		$d_Header .= "<td align='center' hidden></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add_hi_grid_std_breakdown()
	{
		$id 		= $this->uri->segment(3);
		$id_row 	= $this->uri->segment(4);


		$material    = $this->bom_hi_grid_custom_model->get_data_where_array('bom_detail', array('no_bom' => $id));
		$GET_LEVEL4 = get_inventory_lv4();

		$d_Header = "";
		$nomor = 0;
		foreach ($material as $valx) {
			$nomor++;
			$nm_material = (!empty($GET_LEVEL4[$valx->code_material]['nama'])) ? $GET_LEVEL4[$valx->code_material]['nama'] : '';
			$datetime 	= $id_row . '-' . $nomor;

			$d_Header .= "<tr>";
			$d_Header .= "<td width='70%'>";
			$d_Header .= "<input type='hidden' name='DetailHiGrid[" . $id_row . "][detail][" . $datetime . "][code_material]' class='form-control input-md' value='" . $valx->code_material . "'>";
			$d_Header .= "<input type='text' name='DetailHiGrid[" . $id_row . "][detail][" . $datetime . "][nm_material]' class='form-control input-md' value='" . $nm_material . "' readonly>";
			$d_Header .= "</td>";
			$d_Header .= "<td>";
			$d_Header .= "<input type='text' name='DetailHiGrid[" . $id_row . "][detail][" . $datetime . "][berat]' class='form-control input-md autoNumeric4 qty' placeholder='Weight'  readonly value='" . $valx->weight . "'>";
			$d_Header .= "</td>";
			$d_Header .= "</tr>";
		}

		echo json_encode(array(
			'material'	=> $d_Header
		));
	}

	public function get_add_sub_material()
	{
		$data = $this->input->post();

		$id 		= $data['id'];
		$no 		= $data['no'];
		$labelName 	= $data['label_name'];
		$labelClass = $data['label_class'];

		$material    = get_list_inventory_lv4('material');

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='" . $labelClass . "_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left' colspan='2'>";
		$d_Header .= "<select name='" . $labelName . "[" . $id . "][material][" . $no . "][id_material]' data-id='" . $id . "' class='chosen-select form-control input-sm inline-blockd'>";
		$d_Header .= "<option value='0'>Select Material</option>";
		foreach ($material as $valx => $value) {
			$d_Header .= "<option value='" . $value['code_lv4'] . "'>" . strtoupper($value['nama']) . "</option>";
		}
		$d_Header .= "</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='" . $labelName . "[" . $id . "][material][" . $no . "][weight]' class='form-control input-md text-center autoNumeric4 qty' placeholder='Qty'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'></td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delSubPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='" . $labelClass . "_" . $id . "_" . $no . "' class='" . $labelClass . "_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-default addSubPartMat' data-label_class='" . $labelClass . "' data-label_name='" . $labelName . "' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}
}
