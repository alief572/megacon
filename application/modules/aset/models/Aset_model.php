<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Harboens
 * @copyright Copyright (c) 2019, Harboens
 *
 * This is model class for table "Budget"
 */

class Aset_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'ms_coa_aset';
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
    protected $soft_deletes = false;

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

	function GetAset(){
		$this->db->select('a.*, b.nama, c.nm_dept, d.nm_costcenter');
		$this->db->from('ms_coa_aset a'); 
		$this->db->join(DBACC.'.coa_master b','a.coa=b.no_perkiraan');
		$this->db->join('ori_dev.department c','a.divisi=c.id');
		$this->db->join('ori_dev.costcenter d','a.costcenter=d.id');
		$query = $this->db->get(); 
		if($query->num_rows() != 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	function SearchBudget($tahun,$bulan,$id=''){
		$this->db->select('a.*, b.nm_dept');
		$this->db->from('ms_coa_aset a');
			$this->db->join('ori_dev.department b','a.divisi=b.id');
		if($id!=''){
			$this->db->where('a.id', $id);
		}
		$this->db->where('a.tahun', $tahun);
		$this->db->order_by('a.nama_aset', 'desc'); 
		$query = $this->db->get();
		if($query->num_rows() != 0) {
			if($id!=''){
				return $query->row();
			}else{ 
				return $query->result();
			} 
		} else {
			return false;
		}
	}
	
	function get_aset_divisi($divisi,$tahun)
    {
        $this->db->where('divisi', $divisi);
		$this->db->where('tahun', $tahun);
        $this->db->order_by('id', 'ASC');
        return $this->db->from('ms_coa_aset')
            ->get()
            ->result();
    }
}