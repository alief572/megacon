<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Machine_rate_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'rate_machine';
        $this->key        = 'id';
        $this->code       = 'code_lv4';

        $this->viewPermission   = 'Machine_Rate.View';
        $this->addPermission    = 'Machine_Rate.Add';
        $this->managePermission = 'Machine_Rate.Manage';
        $this->deletePermission = 'Machine_Rate.Delete';
    }

    public function get_data($array_where)
    {
        if (!empty($array_where)) {
            $query = $this->db->get_where($this->table_name, $array_where);
        } else {
            $query = $this->db->get($this->table_name);
        }

        return $query->result();
    }

    function getById($id)
    {
        return $this->db->get_where($this->table_name, array($this->key => $id))->row_array();
    }

    public function get_data_rate_machine()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');

        $this->db->select('a.*');
        $this->db->from('rate_machine a');
        $this->db->where('a.deleted_by', null);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_mesin', $search['value'], 'both');
            $this->db->or_like('a.harga_mesin', $search['value'], 'both');
            $this->db->or_like('a.depresiasi', $search['value'], 'both');
            $this->db->or_like('a.depresiasi_per_tahun', $search['value'], 'both');
            $this->db->or_like('a.utilisasi_hari', $search['value'], 'both');
            $this->db->or_like('a.utilisasi_m3_per_hari', $search['value'], 'both');
            $this->db->or_like('a.cost_m3', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->order_by('a.created_date', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*');
        $this->db->from('rate_machine a');
        $this->db->where('a.deleted_by', null);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_mesin', $search['value'], 'both');
            $this->db->or_like('a.harga_mesin', $search['value'], 'both');
            $this->db->or_like('a.depresiasi', $search['value'], 'both');
            $this->db->or_like('a.depresiasi_per_tahun', $search['value'], 'both');
            $this->db->or_like('a.utilisasi_hari', $search['value'], 'both');
            $this->db->or_like('a.utilisasi_m3_per_hari', $search['value'], 'both');
            $this->db->or_like('a.cost_m3', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->order_by('a.created_date', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = 1;
        foreach ($get_data->result() as $item) {

            $edit = '<button type="button" class="btn btn-sm btn-warning edit" data-id="' . $item->id . '" title="Edit Machine Rate"><i class="fa fa-pencil"></i></button>';

            $delete = '<button type="button" class="btn btn-sm btn-danger delete" data-id="' . $item->id . '" title="Delete Machine Rate"><i class="fa fa-trash"></i></button>';

            $view = '<button type="button" class="btn btn-sm btn-info view" data-id="'.$item->id.'" title="View Rate Machine"><i class="fa fa-eye"></i></button>';

            if (!$this->auth->restrict($this->managePermission)) {
                $edit = '';
                $delete = '';
            }

            $option = $edit . ' ' . $delete . ' ' . $view;

            $hasil[] = [
                'no' => $no,
                'machine_name' => $item->nm_mesin,
                'harga' => number_format($item->harga_mesin),
                'depresiasi' => number_format($item->depresiasi),
                'depresiasi_per_tahun' => number_format($item->depresiasi_per_tahun),
                'utilisasi' => number_format($item->utilisasi_hari),
                'utilisasi_m3_per_hari' => number_format($item->utilisasi_m3_per_hari),
                'cost_m3' => number_format($item->cost_m3, 2),
                'action' => $option
            ];

            $no++;
        }

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $get_data_all->num_rows(),
            'recordsFiltered' => $get_data_all->num_rows(),
            'data' => $hasil
        ]);
    }
}
