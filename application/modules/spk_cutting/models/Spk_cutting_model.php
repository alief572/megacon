<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Spk_cutting_model extends BF_Model{

  public function __construct(){
    parent::__construct();

    $this->ENABLE_ADD     = has_permission('SPK_Cutting.Add');
		$this->ENABLE_MANAGE  = has_permission('SPK_Cutting.Manage');
		$this->ENABLE_VIEW    = has_permission('SPK_Cutting.View');
		$this->ENABLE_DELETE  = has_permission('SPK_Cutting.Delete');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function get_data($table,$where_field='',$where_value=''){
    if($where_field !='' && $where_value!=''){
      $query = $this->db->get_where($table, array($where_field=>$where_value));
    }else{
      $query = $this->db->get($table);
    }

    return $query->result();
  }

  public function get_data_group($table,$where_field='',$where_value='',$where_group=''){
    if($where_field !='' && $where_value!=''){
      $query = $this->db->group_by($where_group)->get_where($table, array($where_field=>$where_value));
    }else{
      $query = $this->db->get($table);
    }

    return $query->result();
  }

  public function data_side_request_produksi(){
    $controller			= ucfirst(strtolower($this->uri->segment(1)));
    // $Arr_Akses			= getAcccesmenu($controller);
    $requestData		= $_REQUEST;
    $fetch					= $this->get_query_request_produksi(
      $requestData['product'],
      $requestData['costcenter'],
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData			= $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query					= $fetch['query'];

    $data	= array();
    $urut1  = 1;
    $urut2  = 0;
    $GET_USER = get_list_user();
    foreach($query->result_array() as $row){
      $total_data     = $totalData;
      $start_dari     = $requestData['start'];
      $asc_desc       = $requestData['order'][0]['dir'];
      if($asc_desc == 'asc'){
      $nomor = ($total_data - $start_dari) - $urut2;
      }
      if($asc_desc == 'desc'){
      $nomor = $urut1 + $start_dari;
      }

      $nestedData 	= array();
      $nestedData[]	= "<div align='center'>".$nomor."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper($row['so_customer'])."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper($row['nm_customer'])."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper($row['nm_product_custom'])."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper($row['nm_product'])."</div>";
      $nestedData[]	= "<div align='center'>".date('d-M-Y',strtotime($row['due_date']))."</div>";
      $username = (!empty($GET_USER[$row['created_by']]['username']))?$GET_USER[$row['created_by']]['username']:'-';
      $nestedData[]	= "<div align='left'>".strtolower($username)."</div>";
      $nestedData[]	= "<div align='center'>".number_format($row['propose'])."</div>";
      $nestedData[]	= "<div align='center'>".number_format($row['qty_request'])."</div>";
      $qty_sisa = $row['propose']-$row['qty_request'];
      $nestedData[]	= "<div align='center'>".number_format($qty_sisa)."</div>";

      $edit	= "";
      if($this->ENABLE_MANAGE AND $qty_sisa > 0){
        $edit	= "<a href='".site_url($this->uri->segment(1)).'/add/'.$row['id']."' class='btn btn-sm btn-primary' title='SPK' data-role='qtip'><i class='fa fa-hand-pointer-o'></i></a>";
      }
      $nestedData[]	= "<div align='center'>".$edit."</div>";
      $data[] = $nestedData;
      $urut1++;
      $urut2++;
    }

    $json_data = array(
      "draw"            	=> intval( $requestData['draw'] ),
      "recordsTotal"    	=> intval( $totalData ),
      "recordsFiltered" 	=> intval( $totalFiltered ),
      "data"            	=> $data
    );

    echo json_encode($json_data);
  }

  public function get_query_request_produksi($product, $costcenter, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $costcenter_where = "";

    $product_where = "";
    if($product != '0'){
    $product_where = " AND b.code_lv1 = '".$product."'";
    }

    $sql = "SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              b.nama AS nm_product,
              y.nama_product AS nm_product_custom,
              c.nm_customer
            FROM
                so_spk_cutting a
                LEFT JOIN so_internal_request y ON a.id_request = y.id
                LEFT JOIN new_inventory_4 b ON a.code_lv4=b.code_lv4
                LEFT JOIN customer c ON a.id_customer=c.id_customer,
                (SELECT @row:=0) r
            WHERE 1=1 ".$costcenter_where." ".$product_where." AND (
              b.nama LIKE '%".$this->db->escape_like_str($like_value)."%'
              OR a.code_lv4 LIKE '%".$this->db->escape_like_str($like_value)."%'
              OR c.nm_customer LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;

    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'so_customer',
      2 => 'c.nm_customer',
      3 => 'b.nama',
      4 => 'due_date',
      5 => 'propose'
    );

    $sql .= " ORDER BY a.id desc,  ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }
}