<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Business_field_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Master_Business_Field.Add');
        $this->ENABLE_MANAGE  = has_permission('Master_Business_Field.Manage');
        $this->ENABLE_VIEW    = has_permission('Master_Business_Field.View');
        $this->ENABLE_DELETE  = has_permission('Master_Business_Field.Delete');
    }

    public function get_data_bf()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');

        $this->db->select('a.*');
        $this->db->from('bidang_usaha a');
        $this->db->where('a.deleted', 'N');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.bidang_usaha', $search['value'], 'both');
            $this->db->or_like('a.keterangan', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->order_by('a.id_bidang_usaha', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*');
        $this->db->from('bidang_usaha a');
        $this->db->where('a.deleted', 'N');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.bidang_usaha', $search['value'], 'both');
            $this->db->or_like('a.keterangan', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->order_by('a.id_bidang_usaha', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = 0 + $start;
        foreach ($get_data->result() as $item) {
            $no++;

            $btn_edit = '<button type="button" class="btn btn-sm btn-success edit" data-id_bidang_usaha="' . $item->id_bidang_usaha . '" title="Edit Business Field"><i class="fa fa-pencil"></i></button>';
            if (!has_permission($this->ENABLE_MANAGE)) {
                $btn_edit = '';
            }

            $btn_delete = '<button type="button" class="btn btn-sm btn-danger delete" data-id_bidang_usaha="' . $item->id_bidang_usaha . '" title="Delete Business Field"><i class="fa fa-trash"></i></button>';
            if (!has_permission($this->ENABLE_DELETE)) {
                $btn_delete = '';
            }

            $hasil[] = [
                'no' => $no,
                'bidang_usaha' => $item->bidang_usaha,
                'keterangan' => $item->keterangan,
                'action' => $btn_edit . ' ' . $btn_delete
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
