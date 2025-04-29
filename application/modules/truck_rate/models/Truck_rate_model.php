<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2019, Ichsan
 *
 * This is model class for table "customer"
 */

class Truck_rate_model extends BF_Model
{

    public function __construct()
    {
        $this->viewPermission = 'Truck_rate.View';
        $this->addPermission = 'Truck_rate.Add';
        $this->deletePermission = 'Truck_rate.Delete';
        $this->managePermission = 'Truck_rate.Manage';
    }

    public function get_data_truck_rate()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');

        $this->db->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created, d.nm_asset');
        $this->db->from('tr_truck_rate a');
        $this->db->join('users b', 'b.id_user = a.updated_by', 'left');
        $this->db->join('users c', 'c.id_user = a.created_by', 'left');
        $this->db->join('asset d', 'd.kd_asset = a.kd_asset', 'left');
        // $this->db->where('a.deleted_by', null);
        $this->db->where('(a.deleted_by IS NULL OR a.deleted_by = "")');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('d.nm_asset', $search['value'], 'both');
            $this->db->or_like('a.maksimal_muatan', $search['value'], 'both');
            $this->db->or_like('a.rate_truck', $search['value'], 'both');
            // $this->db->or_like('c.nm_lengkap', $search['value'], 'both');
            // $this->db->or_Like('a.updated_date', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id_truck_rate');
        $this->db->order_by('a.id_truck_rate', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created, d.nm_asset');
        $this->db->from('tr_truck_rate a');
        $this->db->join('users b', 'b.id_user = a.updated_by', 'left');
        $this->db->join('users c', 'c.id_user = a.created_by', 'left');
        $this->db->join('asset d', 'd.kd_asset = a.kd_asset', 'left');
        // $this->db->where('a.deleted_by', null);
        $this->db->where('(a.deleted_by IS NULL OR a.deleted_by = "")');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('d.nm_asset', $search['value'], 'both');
            $this->db->or_like('a.maksimal_muatan', $search['value'], 'both');
            $this->db->or_like('a.rate_truck', $search['value'], 'both');
            // $this->db->or_like('c.nm_lengkap', $search['value'], 'both');
            // $this->db->or_Like('a.updated_date', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id_truck_rate');
        $this->db->order_by('a.id_truck_rate', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = 1;
        foreach ($get_data->result() as $item) {

            $last_update_by = $item->nama_lengkap;
            $last_update = $item->updated_date;
            if ($last_update_by == '') {
                $last_update_by = '-';
                $last_update = '-';
            }

            $edit = '<button type="button" class="btn btn-sm btn-warning edit" data-id="' . $item->id_truck_rate . '" title="Edit Track Rate"><i class="fa fa-pencil"></i></button>';
            // $edit_v2  = '<a href='" . site_url($this->uri->segment(1)) . "/approval_planning/" . $item->id_truck_rate . "' class="btn btn-sm btn-success" title="Approval PR" data-role="qtip"><i class="fa fa-check"></i></a>';
            $edit_v2  = "<a href='" . site_url($this->uri->segment(1)) . '/add_truck_rate/' . $item->id_truck_rate . "' class='btn btn-sm btn-success' title='Edit Truckc Rate' data-role='qtip'><i class='fa fa-pencil'></i></a>";

            $delete = '<button type="button" class="btn btn-sm btn-danger delete" data-id="' . $item->id_truck_rate . '" title="Delete Track Rate"><i class="fa fa-trash"></i></button>';

            if (!$this->auth->restrict($this->managePermission)) {
                $edit = '';
            }

            if (!$this->auth->restrict($this->deletePermission)) {
                $delete = '';
            }

            // $view = '<button type="button" class="btn btn-sm btn-info view" data-id="' . $item->id_truck_rate . '" title="View Truck Rate"><i class="fa fa-eye"></i></button>';
            // $view_v2  = "<a href='" . site_url($this->uri->segment(1)) . '/add_truck_rate/' . $item->id_truck_rate . '/0' "' class='btn btn-sm btn-info' title='View Truck Rate' data-role='qtip'><i class='fa fa-eye'></i></a>";
            $view_v2  = "<a href='" . site_url($this->uri->segment(1) . '/add_truck_rate/' . $item->id_truck_rate . "/0") . "' class='btn btn-sm btn-info' title='View Truck Rate' data-role='qtip'><i class='fa fa-eye'></i></a>";

            $action = $edit_v2 . ' ' . $view_v2 . ' ' . $delete;



            $hasil[] = [
                'no' => $no,
                'kd_asset' => $item->nm_asset,
                'truck_rate' => number_format($item->rate_truck, 2),
                'last_update_by' => $last_update_by,
                'last_update' => $last_update,
                'action' => $action
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

    public function get_data_rate_borongan()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search');

        $this->db->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created');
        $this->db->from('tr_rate_borongan a');
        $this->db->join('users b', 'b.id_user = a.updated_by', 'left');
        $this->db->join('users c', 'b.id_user = a.created_by', 'left');
        $this->db->where('a.deleted_by', null);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_product', $search['value'], 'both');
            $this->db->or_like('a.rate_borongan', $search['value'], 'both');
            $this->db->or_like('b.nm_lengkap', $search['value'], 'both');
            $this->db->or_like('c.nm_lengkap', $search['value'], 'both');
            $this->db->or_Like('a.updated_date', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.id', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*, IF(b.nm_lengkap IS NULL, "", b.nm_lengkap) as nama_lengkap, c.nm_lengkap as nama_created');
        $this->db->from('tr_rate_borongan a');
        $this->db->join('users b', 'b.id_user = a.updated_by', 'left');
        $this->db->join('users c', 'b.id_user = a.created_by', 'left');
        $this->db->where('a.deleted_by', null);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.nm_product', $search['value'], 'both');
            $this->db->or_like('a.rate_borongan', $search['value'], 'both');
            $this->db->or_like('b.nm_lengkap', $search['value'], 'both');
            $this->db->or_like('c.nm_lengkap', $search['value'], 'both');
            $this->db->or_Like('a.updated_date', $search['value'], 'both');
            $this->db->group_end();
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.id', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = 1;
        foreach ($get_data->result() as $item) {

            $last_update_by = $item->nama_lengkap;
            $last_update = $item->updated_date;
            if ($last_update_by == '') {
                $last_update_by = '-';
                $last_update = '-';
            }

            $edit = '<button type="button" class="btn btn-sm btn-warning edit" data-id="' . $item->id . '" title="Edit Rate Borongan"><i class="fa fa-pencil"></i></button>';

            $delete = '<button type="button" class="btn btn-sm btn-danger delete" data-id="' . $item->id . '" title="Delete Rate Borongan"><i class="fa fa-trash"></i></button>';

            if (!$this->auth->restrict($this->managePermission)) {
                $edit = '';
            }

            if (!$this->auth->restrict($this->deletePermission)) {
                $delete = '';
            }

            $view = '<button type="button" class="btn btn-sm btn-info view" data-id="' . $item->id . '" title="View Rate Borongan"><i class="fa fa-eye"></i></button>';

            $action = $edit . ' ' . $delete . ' ' . $view;



            $hasil[] = [
                'no' => $no,
                'product' => $item->nm_product,
                'rate_borongan' => number_format($item->rate_borongan, 2),
                'last_update_by' => $last_update_by,
                'last_update' => $last_update,
                'action' => $action
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
