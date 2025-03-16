<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Bom extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'BOM.View';
	protected $addPermission  	= 'BOM.Add';
	protected $managePermission = 'BOM.Manage';
	protected $deletePermission = 'BOM.Delete';

	public function __construct()
	{
		parent::__construct();

		// $this->load->library(array('Mpdf'));
		$this->load->model(array(
			'Bom/bom_model'
		));
		$this->template->title('BOM Standard Lainnya');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	//========================================================BOM

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->bom_model->get_data('bom_header', 'deleted', 'N');
		history("View index BOM");
		$this->template->set('results', $data);
		$this->template->title('BOM');
		$this->template->render('index');
	}

	public function data_side_bom()
	{
		$this->bom_model->get_json_bom();
	}

	public function add()
	{
		if ($this->input->post()) {
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			// print_r($data);
			// exit;
			$session 		  = $this->session->userdata('app_session');
			$Detail 	    = $data['detail_material'];
			$detail_material_lain = $data['detail_material_lain'];
			$Ym					  = date('ym');
			$no_bom        = $data['no_bom'];
			$no_bomx        = $data['no_bom'];
			$check_p			  = "SELECT * FROM bom_header WHERE id_product ='" . $data['id_product'] . "' ";
			$num_p		= $this->db->query($check_p)->num_rows();
			// if($num_p < 1){
			$created_by   = 'updated_by';
			$created_date = 'updated_date';
			$tanda        = 'Insert ';
			if (empty($no_bomx)) {
				//pengurutan kode
				$srcMtr			  = "SELECT MAX(no_bom) as maxP FROM bom_header WHERE no_bom LIKE 'BOM" . $Ym . "%' ";
				$numrowMtr		= $this->db->query($srcMtr)->num_rows();
				$resultMtr		= $this->db->query($srcMtr)->result_array();
				$angkaUrut2		= $resultMtr[0]['maxP'];
				$urutan2		  = (int)substr($angkaUrut2, 7, 3);
				$urutan2++;
				$urut2			  = sprintf('%03s', $urutan2);
				$no_bom	      = "BOM" . $Ym . $urut2;

				$created_by   = 'created_by';
				$created_date = 'created_date';
				$tanda        = 'Update ';
			}

			$ArrHeader		= array(
				'no_bom'			    => $no_bom,
				'id_product'	    => $data['id_product'],
				'variant_product'	    => $data['variant_product'],
				'id_variant_product' => $data['id_variant_product'],
				'keterangan'	    => $data['keterangan'],
				'id_jenis_beton' => $data['jenis_beton'],
				'volume_m3' => $data['volume_produk'],
				$created_by	    => $session['id_user'],
				$created_date	  => date('Y-m-d H:i:s')
			);

			$ArrDetail	= array();
			foreach ($Detail as $val => $valx) {
				$urut				= sprintf('%03s', $val);
				$ArrDetail[$val]['no_bom'] 			= $no_bom;
				$ArrDetail[$val]['no_bom_detail'] 	= $no_bom . "-" . $urut;
				$ArrDetail[$val]['code_material'] 	= $valx['id_detail_material'];
				$ArrDetail[$val]['volume_m3'] = $valx['volume_material'];
				$ArrDetail[$val]['satuan_lainnya'] = $valx['satuan_lainnya'];
				$ArrDetail[$val]['satuan'] = $valx['satuan'];
				$ArrDetail[$val]['created_by'] = $this->auth->user_id();
				$ArrDetail[$val]['created_date'] = date('Y-m-d H:i:s');
			}

			$ArrDetail2	= array();
			foreach ($detail_material_lain as $val => $valx) {
				$urut				= sprintf('%03s', $val);
				$ArrDetail2[$val]['no_bom'] = $no_bom;
				$ArrDetail2[$val]['id_material'] = $valx['id_material'];
				$ArrDetail2[$val]['material_name'] = $valx['material_name'];
				$ArrDetail2[$val]['kebutuhan'] = $valx['kebutuhan'];
				$ArrDetail2[$val]['id_satuan'] = $valx['id_satuan'];
				$ArrDetail2[$val]['nm_satuan'] = $valx['satuan'];
				$ArrDetail2[$val]['keterangan'] = $valx['keterangan'];
				$ArrDetail2[$val]['created_by'] = $this->auth->user_id();
				$ArrDetail2[$val]['created_date'] = date('Y-m-d H:i:s');
			}

			$this->db->trans_start();
			if (empty($no_bomx)) {
				$insert_header = $this->db->insert('bom_header', $ArrHeader);
				if (!$insert_header) {
					$this->db->trans_complete();
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
			}
			if (!empty($no_bomx)) {
				$update_header = $this->db->update('bom_header', $ArrHeader, ['no_bom' => $no_bom]);
				if (!$update_header) {
					$this->db->trans_complete();
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
			}

			if (!empty($ArrDetail)) {
				$this->db->delete('bom_detail', array('no_bom' => $no_bom));
				$insert_detail = $this->db->insert_batch('bom_detail', $ArrDetail);
				if (!$insert_detail) {
					$this->db->trans_complete();
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
			}

			if (!empty($ArrDetail2)) {
				$this->db->delete('bom_material_lain', ['no_bom' => $no_bom]);
				$insert_detail2 = $this->db->insert_batch('bom_material_lain', $ArrDetail2);
				if (!$insert_detail2) {
					$this->db->trans_complete();
					$this->db->trans_rollback();

					print_r($this->db->last_query());
					exit;
				}
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
				history($tanda . " BOM " . $no_bom);
			}
			// }
			// else{
			//   $Arr_Data	= array(
			//     'pesan'		=>'Product sudah digunakan .',
			//     'status'	=> 0
			//   );
			// }

			echo json_encode($Arr_Data);
		} else {
			$session  = $this->session->userdata('app_session');
			$no_bom 	  = $this->uri->segment(3);
			$header   = $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();

			$this->db->select('a.*, b.nm_material');
			$this->db->from('bom_detail a');
			$this->db->join('tr_jenis_beton_detail b', 'b.id_detail_material = a.code_material', 'left');
			$this->db->where('a.no_bom', $no_bom);
			$detail = $this->db->get()->result();

			$this->db->select('a.*');
			$this->db->from('bom_material_lain a');
			$this->db->where('a.no_bom', $no_bom);
			$detail_material_lain = $this->db->get()->result();

			$product    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product', 'code_lv1 <>' => 'P123000008'));
			$material    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));

			$variant_product  = $this->bom_model->get_data_where_array('list', array('menu' => 'bom std lainnya', 'category' => 'variant product'));
			$color_product    = $this->bom_model->get_data_where_array('list', array('menu' => 'bom std lainnya', 'category' => 'color'));

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

			$data = [
				'header' => $header,
				'detail' => $detail,
				'product' => $product,
				'bom_standard_list' => $bom_standard_list,
				'list_variant_product' => $variant_product,
				'list_color_product' => $color_product,
				'material' => $material,
				'jenis_beton' => $jenis_beton,
				'list_satuan' => $list_satuan,
				'detail_material_lain' => $detail_material_lain,
				'list_material' => $get_list_material
			];
			$this->template->set('results', $data);
			$this->template->title('Add BOM Standard Lainnya');
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

		$product    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));
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

		$material    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));
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
		$d_Header .= "<input type='text' name='Detail[" . $id . "][weight]' class='form-control input-md autoNumeric4 qty text-right' placeholder='Weight'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add Material'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
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
			history("Delete data BOM Standard Lainnya " . $no_bom);
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
			->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'standard'))
			->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(12);
		$sheet->setCellValue('A' . $Row, 'BOM STANDARD LAINNYA');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;
		$NextRow = $NewRow + 1;

		$sheet->setCellValue('A' . $NewRow, '#');
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

		$sheet->setCellValue('D' . $NewRow, 'Color');
		$sheet->getStyle('D' . $NewRow . ':D' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('D' . $NewRow . ':D' . $NextRow);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$sheet->setCellValue('E' . $NewRow, 'Keterangan');
		$sheet->getStyle('E' . $NewRow . ':E' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('E' . $NewRow . ':E' . $NextRow);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$sheet->setCellValue('F' . $NewRow, 'MOQ');
		$sheet->getStyle('F' . $NewRow . ':F' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('F' . $NewRow . ':F' . $NextRow);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		$sheet->setCellValue('G' . $NewRow, 'Total Berat');
		$sheet->getStyle('G' . $NewRow . ':G' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('G' . $NewRow . ':G' . $NextRow);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		$sheet->setCellValue('H' . $NewRow, 'Waste Product (Kg)');
		$sheet->getStyle('H' . $NewRow . ':H' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('H' . $NewRow . ':H' . $NextRow);
		$sheet->getColumnDimension('H')->setAutoSize(true);

		$sheet->setCellValue('I' . $NewRow, 'Waste Setting & Cleaning Resin (Kg)');
		$sheet->getStyle('I' . $NewRow . ':I' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('I' . $NewRow . ':I' . $NextRow);
		$sheet->getColumnDimension('I')->setAutoSize(true);

		$sheet->setCellValue('J' . $NewRow, 'Waste Setting & Cleaning Glass (Kg)');
		$sheet->getStyle('J' . $NewRow . ':J' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('J' . $NewRow . ':J' . $NextRow);
		$sheet->getColumnDimension('J')->setAutoSize(true);

		$sheet->setCellValue('K' . $NewRow, 'Width (m)');
		$sheet->getStyle('K' . $NewRow . ':K' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('K' . $NewRow . ':K' . $NextRow);
		$sheet->getColumnDimension('K')->setAutoSize(true);

		$sheet->setCellValue('L' . $NewRow, 'Length (m)');
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
				$nomor			= $no;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nomor);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$nm_product		= $row_Cek['nm_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nm_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$variant_product = $row_Cek['variant_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $variant_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$color	= $row_Cek['color'];
				$Cols	= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $color);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$keterangan	= $row_Cek['keterangan'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $keterangan);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$moq	= $row_Cek['moq'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $moq);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

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
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$waste_setting_resin	= $row_Cek['waste_setting_resin'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_setting_resin);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$waste_setting_glass	= $row_Cek['waste_setting_glass'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_setting_glass);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$width	= $row_Cek['width'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $width);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$length	= $row_Cek['length'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $length);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);
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
		header('Content-Disposition: attachment;filename="bom-standard-lainnya.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function excel_report_all_bom_detail($no_bom)
	{
		
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

		$product    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));
		// print_r($header);
		$data = [
			'header' => $header,
			'detail' => $detail,
			'detail_material_lain' => $detail_material_lain,
			'product' => $product,
			'GET_LEVEL4' => get_inventory_lv4(),
		];
		$this->load->view('export_excel_bom', $data);
	}

	public function get_add_copy()
	{
		$no_bom 	= $this->uri->segment(3);
		$no 	= 0;

		$material    = $this->bom_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));
		$detail   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom))->result_array();
		$d_Header = "";
		// $d_Header .= "<tr>";
		$id = 0;
		foreach ($detail as $key => $value) {
			$id++;
			$d_Header .= "<tr class='header_" . $id . "'>";
			$d_Header .= "<td align='center'>" . $id . "</td>";
			$d_Header .= "<td align='left'>";
			$d_Header .= "<select name='Detail[" . $id . "][code_material]' class='chosen_select form-control input-sm inline-blockd material'>";
			$d_Header .= "<option value='0'>Select Material Name</option>";
			foreach ($material as $valx) {
				$sel2 = ($valx->code_lv4 == $value['code_material']) ? 'selected' : '';
				$d_Header .= "<option value='" . $valx->code_lv4 . "' " . $sel2 . ">" . strtoupper($valx->nama) . "</option>";
			}
			$d_Header .= "</select>";
			$d_Header .= "</td>";
			$d_Header .= "<td align='left'>";
			$d_Header .= "<input type='text' name='Detail[" . $id . "][weight]' class='form-control input-md text-right autoNumeric4 qty' placeholder='Weight'  value='" . $value['weight'] . "'>";
			$d_Header .= "</td>";
			$d_Header .= "<td align='left'>";
			$d_Header .= "<input type='text' name='Detail[" . $id . "][ket]' class='form-control input-md' placeholder='Keterangan'  value='" . $value['ket'] . "'>";
			$d_Header .= "</td>";
			$d_Header .= "<td align='left'>";
			$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
			$d_Header .= "</td>";
			$d_Header .= "</tr>";
		}

		//add part
		$d_Header .= "<tr id='add_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add Material'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header' => $d_Header,
		));
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
}
