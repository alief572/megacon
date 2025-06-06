<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Costcenter_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'ms_costcenter';
    protected $key        = 'id';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_on';

    /**
     * @var bool Set the created time automatically on a new record (if true)
     */
    protected $set_created = true;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = true;
    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = true;

    protected $date_format = 'datetime';

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = true;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }


    function generate_id($kode='') {

        $today=date("ymd");
		$year=date("y");
		$month=date("m");
		$day=date("d");

        $cek = date('y').$month;
        $query = "SELECT MAX(RIGHT(id_costcenter,5)) as max_id from ms_costcenter ";
        $q = $this->db->query($query);
		$r = $q->row();
        $query_cek = $q->num_rows();
		$kode2 = $r->max_id;
		$kd_noreg = "";

        if ($query_cek == 0) {
          $kd_noreg = 1;
          $reg = sprintf("%02d%05s", $year,$kode_noreg);

        }else {

        // jk sudah ada maka
			$kd_new = $kode2+1; // kode sebelumnya ditambah 1.
			$reg = sprintf("%02d%05s", $year,$kd_new);

        }

		$tr ="CC$reg";


          // print_r($tr);
		  // exit();

      return $tr;
	}

 	public function get_data($table,$where_field='',$where_value=''){
		if($where_field !='' && $where_value!=''){
			$query = $this->db->get_where($table, array($where_field=>$where_value));
		}else{
			$query = $this->db->get($table);
		}

		return $query->result();
	}

    function getById($id)
    {
       return $this->db->get_where('ms_inventory_type',array('id_type' => $id))->row_array();
    }

	public function getArray($table,$WHERE=array(),$keyArr='',$valArr=''){
		if($WHERE){
			$query = $this->db->get_where($table, $WHERE);
		}else{
			$query = $this->db->get($table);
		}
		$dataArr	= $query->result_array();

		if(!empty($keyArr)){
			$Arr_Data	= array();
			foreach($dataArr as $key=>$val){
				$nilai_id					= $val[$keyArr];
				if(!empty($valArr)){
					$nilai_val				= $val[$valArr];
					$Arr_Data[$nilai_id]	= $nilai_val;
				}else{
					$Arr_Data[$nilai_id]	= $val;
				}

			}

			return $Arr_Data;
		}else{
			return $dataArr;
		}

	}



}
