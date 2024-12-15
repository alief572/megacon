<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Komposisi_beton_model extends BF_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function generate_id_komposisi_beton()
    {

        $query = $this->db->query("SELECT MAX(id_komposisi_beton) as max_id FROM tr_jenis_beton_header WHERE id_komposisi_beton LIKE '%KOMP-" . date('Ym') . "%'");
        $row = $query->row_array();
        $max_id = $row['max_id'];
        $max_id1 = (int) substr($max_id, 12, 5);
        $counter = $max_id1 + 1;
        $idcust = "KOMP-" . date('Ym') . '-' . sprintf('%05s', $counter);
        return $idcust;
    }

    public function get_data_jenis_beton() {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');

        $this->db->select('a.*, SUM(b.volume) as ttl_volume');
        $this->db->from('tr_jenis_beton_header a');
        $this->db->join('tr_jenis_beton_detail b', 'b.id_komposisi_beton = a.id_komposisi_beton', 'left');
        if(!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_jenis_beton', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id_komposisi_beton');
        $this->db->order_by('a.created_date', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*, SUM(b.volume) as ttl_volume');
        $this->db->from('tr_jenis_beton_header a');
        $this->db->join('tr_jenis_beton_detail b', 'b.id_komposisi_beton = a.id_komposisi_beton', 'left');
        if(!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_jenis_beton', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id_komposisi_beton');
        $this->db->order_by('a.created_date', 'desc');
        
        $get_data_all = $this->db->get();

        $hasil = [];

        $no = 0 + $start;
        foreach($get_data->result() as $item) {
            $no++;

            $option = '<a href="'.base_url('komposisi_beton/add/'.$item->id_komposisi_beton).'" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-pencil"></i></a> ';

            $option .= '<button type="button" class="btn btn-sm btn-danger del_komposisi_beton" data-id="'.$item->id_komposisi_beton.'" title="Delete"><i class="fa fa-trash"></i></button> ';

            $option .= '<a href="'.base_url('komposisi_beton/view/'.$item->id_komposisi_beton).'" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>';

            $hasil[] = [
                'no' => $no,
                'jenis_beton' => $item->nm_jenis_beton,
                'volume' => $item->ttl_volume,
                'option' => $option
            ];
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $get_data_all->num_rows(),
            'recordsFiltered' => $get_data_all->num_rows(),
            'data' => $hasil
        ]);
    }
}
