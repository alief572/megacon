<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Price_sup_raw_material_model extends CI_Model
{
    protected $viewPermission   = 'Price_Supplier_Raw_Material.View';
    protected $addPermission    = 'Price_Supplier_Raw_Material.Add';
    protected $managePermission = 'Price_Supplier_Raw_Material.Manage';
    protected $deletePermission = 'Price_Supplier_Raw_Material.Delete';
  
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'new_inventory_4';
        $this->key        = 'id';
        $this->code       = 'code_lv4';
    }

    function generate_id()
    {
        $kode             = 'M4' . date('y');
        $Query            = "SELECT MAX(" . $this->code . ") as maxP FROM " . $this->table_name . " WHERE " . $this->code . " LIKE '" . $kode . "%' ";
        $resultIPP        = $this->db->query($Query)->result_array();
        $angkaUrut2        = $resultIPP[0]['maxP'];
        $urutan2        = (int)substr($angkaUrut2, 4, 6);
        $urutan2++;
        $urut2            = sprintf('%06s', $urutan2);
        $kode_id        = $kode . $urut2;
        return $kode_id;
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
        return $this->db->get_where($this->table_name, array($code => $id))->row_array();
    }

    public function get_price_ref()
    {
        $draw = $this->input->post('draw');
        $length = $this->input->post('length');
        $start = $this->input->post('start');
        $search = $this->input->post('search');

        $this->db->select('a.*, b.code as satuan_beli');
        $this->db->from('new_inventory_4 a');
        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
        $this->db->where('a.category', 'material');
        $this->db->where('a.deleted_by', null);
        if (!empty($search['value'])) {
            $this->db->like('a.code', $search['value'], 'both');
            $this->db->or_like('a.nama', $search['value'], 'both');
            $this->db->or_like('b.code', $search['value'], 'both');
            $this->db->or_like('a.price_ref', $search['value'], 'both');
            $this->db->or_like('a.price_ref_new', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high_new', $search['value'], 'both');
        }
        $this->db->order_by('a.code_lv4', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*, b.code as satuan_beli');
        $this->db->from('new_inventory_4 a');
        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
        $this->db->where('a.category', 'material');
        $this->db->where('a.deleted_by', null);
        if (!empty($search['value'])) {
            $this->db->like('a.code', $search['value'], 'both');
            $this->db->or_like('a.nama', $search['value'], 'both');
            $this->db->or_like('b.code', $search['value'], 'both');
            $this->db->or_like('a.price_ref', $search['value'], 'both');
            $this->db->or_like('a.price_ref_new', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high_new', $search['value'], 'both');
        }
        $this->db->order_by('a.code_lv4', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = (0 + $start);
        foreach ($get_data->result_array() as $item) :
            $no++;

            $tgl_create     = $item['price_ref_new_date'];
            $max_exp         = $item['price_ref_new_expired'];
            $tgl_expired     = date('Y-m-d', strtotime('+' . $max_exp . ' month', strtotime($tgl_create)));
            $date_now        = date('Y-m-d');

            $status = 'Not Set';
            $status_ = 'yellow';
            $status2 = '';
            $status2_ = '';

            $expired = '-';
            $expired_new = '-';

            $satuan_beli = (isset($list_satuan[$item['satuan_beli']])) ? $list_satuan[$item['satuan_beli']] : '';

            if (!empty($item['price_ref_date'])) {
                $price_ref_date     = date('Y-m-d', strtotime('+' . $item['price_ref_expired'] . ' month', strtotime($item['price_ref_date'])));
                $expired = date('d-M-Y', strtotime($price_ref_date));
                if ($date_now > $price_ref_date) {
                    $status = 'Expired';
                    $status_ = 'red';
                } else {
                    $status = 'Oke';
                    $status_ = 'green';
                }
            }

            if ($item['status_app'] == 'Y') {
                $expired_new = date('d-M-Y', strtotime($tgl_expired));
                $status2 = 'Waiting Approve';
                $status2_ = 'purple';
            }

            $action = '';

            if(has_permission($this->managePermission)) {
                $action .= ' <a class="btn btn-primary btn-sm edit" href="javascript:void(0)" title="Edit" data-id="'. $item['id'] .'" data-tipe_gudang="1"><i class="fa fa-edit"></i></a>';
            }

            if(!empty($item['upload_file'])) {
                $action .= ' <a class="btn btn-success btn-sm" href="'. base_url($item['upload_file']) .'" target="_blank" title="Download"><i class="fa fa-download"></i></a>';
            }

            $hasil[] = [
                'no' => $no,
                'material_code' => $item['code_lv4'],
                'material_master' => $item['nama'],
                'satuan_beli' => ucfirst($item['satuan_beli']),
                'lower_price_before' => number_format($item['price_ref'], 2),
                'lower_price_after' => number_format($item['price_ref_new'], 2),
                'higher_price_before' => number_format($item['price_ref_high'], 2),
                'higher_price_after' => number_format($item['price_ref_high_new'], 2),
                'expired_before' => $expired,
                'expired_after' => $expired_new,
                'status' => '<span class="badge bg-' . $status_ . '">' . $status . '</span><br><span class="badge bg-' . $status2_ . '">' . $status2 . '</span>',
                'alasan_reject' => strtoupper($item['status_reject']),
                'action' => $action
            ];
        endforeach;

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $get_data_all->num_rows(),
            'recordsFiltered' => $get_data_all->num_rows(),
            'data' => $hasil,
        ]);
    }

    public function get_price_ref_2()
    {
        $draw = $this->input->post('draw');
        $length = $this->input->post('length');
        $start = $this->input->post('start');
        $search = $this->input->post('search');

        $this->db->select('a.*, b.code as satuan_beli');
        $this->db->from('new_inventory_4 a');
        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
        $this->db->where('a.category', 'material');
        $this->db->where('a.deleted_by', null);
        if (!empty($search['value'])) {
            $this->db->like('a.code', $search['value'], 'both');
            $this->db->or_like('a.nama', $search['value'], 'both');
            $this->db->or_like('b.code', $search['value'], 'both');
            $this->db->or_like('a.price_ref', $search['value'], 'both');
            $this->db->or_like('a.price_ref_new', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high_new', $search['value'], 'both');
        }
        $this->db->order_by('a.code_lv4', 'desc');
        $this->db->limit($length, $start);

        $get_data = $this->db->get();

        $this->db->select('a.*, b.code as satuan_beli');
        $this->db->from('new_inventory_4 a');
        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
        $this->db->where('a.category', 'material');
        $this->db->where('a.deleted_by', null);
        if (!empty($search['value'])) {
            $this->db->like('a.code', $search['value'], 'both');
            $this->db->or_like('a.nama', $search['value'], 'both');
            $this->db->or_like('b.code', $search['value'], 'both');
            $this->db->or_like('a.price_ref', $search['value'], 'both');
            $this->db->or_like('a.price_ref_new', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high', $search['value'], 'both');
            $this->db->or_like('a.price_ref_high_new', $search['value'], 'both');
        }
        $this->db->order_by('a.code_lv4', 'desc');

        $get_data_all = $this->db->get();

        $hasil = [];

        $no = (0 + $start);
        foreach ($get_data->result_array() as $item) :
            $no++;

            $tgl_create     = $item['price_ref_new_date'];
            $max_exp         = $item['price_ref_new_expired'];
            $tgl_expired     = date('Y-m-d', strtotime('+' . $max_exp . ' month', strtotime($tgl_create)));
            $date_now        = date('Y-m-d');

            $status = 'Not Set';
            $status_ = 'yellow';
            $status2 = '';
            $status2_ = '';

            $expired = '-';
            $expired_new = '-';

            $satuan_beli = (isset($list_satuan[$item['satuan_beli']])) ? $list_satuan[$item['satuan_beli']] : '';

            if (!empty($item['price_ref_date_2'])) {
                $price_ref_date     = date('Y-m-d', strtotime('+' . $item['price_ref_expired_2'] . ' month', strtotime($item['price_ref_date_2'])));
                $expired = date('d-M-Y', strtotime($price_ref_date));
                if ($date_now > $price_ref_date) {
                    $status = 'Expired';
                    $status_ = 'red';
                } else {
                    $status = 'Oke';
                    $status_ = 'green';
                }
            }

            if ($item['status_app'] == 'Y') {
                $expired_new = date('d-M-Y', strtotime($tgl_expired));
                $status2 = 'Waiting Approve';
                $status2_ = 'purple';
            }

            $action = '';

            if(has_permission($this->managePermission)) {
                $action .= ' <a class="btn btn-primary btn-sm edit" href="javascript:void(0)" title="Edit" data-id="'. $item['id'] .'" data-tipe_gudang="2"><i class="fa fa-edit"></i></a>';
            }

            if(!empty($item['upload_file_2'])) {
                $action .= ' <a class="btn btn-success btn-sm" href="'. base_url($item['upload_file_2']) .'" target="_blank" title="Download"><i class="fa fa-download"></i></a>';
            }

            $hasil[] = [
                'no' => $no,
                'material_code' => $item['code_lv4'],
                'material_master' => $item['nama'],
                'satuan_beli' => ucfirst($item['satuan_beli']),
                'lower_price_before' => number_format($item['price_ref_2'], 2),
                'lower_price_after' => number_format($item['price_ref_new_2'], 2),
                'higher_price_before' => number_format($item['price_ref_high_2'], 2),
                'higher_price_after' => number_format($item['price_ref_high_new_2'], 2),
                'expired_before' => $expired,
                'expired_after' => $expired_new,
                'status' => '<span class="badge bg-' . $status_ . '">' . $status . '</span><br><span class="badge bg-' . $status2_ . '">' . $status2 . '</span>',
                'alasan_reject' => strtoupper($item['status_reject_2']),
                'action' => $action
            ];
        endforeach;

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $get_data_all->num_rows(),
            'recordsFiltered' => $get_data_all->num_rows(),
            'data' => $hasil,
        ]);
    }
}
