<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Syamsudin
 * @copyright Copyright (c) 2022, Syamsudin
 *
 * This is model class for table "ms_diskon"
 */

class Ms_diskon_model extends BF_Model
{
    /**
     * @var string  User Table Name
     */
    protected $table_name = 'ms_diskon';
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

    public function get_data_diskon()
    {
        $this->db->select('a.*, b.nm_lengkap');
        $this->db->from('ms_diskon a');
        $this->db->join('users b', 'b.id_user = a.approved_by', 'left');
        $this->db->where('a.deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function generate_id_diskon($no) {
        $generate_id = $this->db->query("SELECT MAX(id) AS max_id FROM ms_diskon WHERE id LIKE '%MDISC-" . date('m-y') . "%'")->row();
		$kodeBarang = $generate_id->max_id;
		$urutan = (int) substr($kodeBarang, 11, 6);
		$urutan += $no;
		$tahun = date('m-y');
		$huruf = "MDISC-";
		$kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

        return $kodecollect;
    }
}
