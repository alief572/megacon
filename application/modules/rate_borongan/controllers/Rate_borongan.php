<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Customer
 */

class Rate_borongan extends Admin_Controller
{

    //Permission
    protected $viewPermission   = "Rate_Borongan.View";
    protected $addPermission    = "Rate_Borongan.Add";
    protected $managePermission = "Rate_Borongan.Manage";
    protected $deletePermission = "Rate_Borongan.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib'));
        $this->load->model(array(
            'Rate_borongan/Rate_borongan_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->template->title('Rate Borongan');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Rate Borongan');
        $this->template->render('index');
    }

    public function add()
    {
        $post = $this->input->post();
        $get_product = $this->db->get_where('new_inventory_4', ['deleted_by' => null, 'category' => 'product'])->result();

        if (isset($post['id'])) {
            $get_rate_borongan = $this->db->get_where('tr_rate_borongan', ['id' => $post['id']])->row();

            $data = [
                'header' => $get_rate_borongan,
                'id' => $post['id']
            ];
            $this->template->set($data);
        }

        $this->template->set('list_product', $get_product);
        $this->template->render('add');
    }

    public function view()
    {
        $get_product = $this->db->get_where('new_inventory_4', ['deleted_by' => null, 'category' => 'product'])->result();

        $post = $this->input->post();
        $get_rate_borongan = $this->db->get_where('tr_rate_borongan', ['id' => $post['id']])->row();

        $data = [
            'header' => $get_rate_borongan,
            'id' => $post['id']
        ];
        $this->template->set($data);
        $this->template->set('list_product', $get_product);
        $this->template->render('view');
    }

    public function detail_barang_input()
    {
        $post = $this->input->post();

        $get_product = $this->db->get_where('new_inventory_4', ['code_lv4' => $post['id_barang']])->row();

        echo json_encode([
            'nm_product' => $get_product->nama
        ]);
    }

    public function save_rate_borongan()
    {
        $post = $this->input->post();

        $this->db->trans_begin();

        if ($post['id_rate_borongan'] !== '') {
            $data_update = [
                'id_product' => $post['barang_input'],
                'nm_product' => $post['nm_barang_input'],
                'rate_borongan' => str_replace(',', '', $post['rate_produk']),
                'updated_by' => $this->auth->user_id(),
                'updated_date' => date('Y-m-d H:i:s')
            ];

            $this->db->update('tr_rate_borongan', $data_update, ['id' => $post['id_rate_borongan']]);
        } else {
            $data_insert = [];

            if (isset($post['detail_barang'])) {
                foreach ($post['detail_barang'] as $item) {
                    $data_insert[] = [
                        'id_product' => $item['id_barang'],
                        'nm_product' => $item['nm_barang'],
                        'rate_borongan' => str_replace(',', '', $item['rate_borongan']),
                        'created_by' => $this->auth->user_id(),
                        'created_date' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (!empty($data_insert)) {
                $insert_data = $this->db->insert_batch('tr_rate_borongan', $data_insert);
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Please try again later !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Save Data Success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function delete_rate_borongan()
    {
        $post = $this->input->post();

        $this->db->update('tr_rate_borongan', [
            'deleted_by' => $this->auth->user_id(),
            'deleted_date' => date('Y-m-d H:i:s')
        ], [
            'id' => $post['id']
        ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Please try again later !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Delete Data Success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function get_data_rate_borongan()
    {
        $this->Rate_borongan_model->get_data_rate_borongan();
    }
}
