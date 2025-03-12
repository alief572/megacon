<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Komposisi_beton extends Admin_Controller
{
    //Permission
    protected $viewPermission   = "Komposisi_Beton.View";
    protected $addPermission    = "Komposisi_Beton.Add";
    protected $managePermission = "Komposisi_Beton.Manage";
    protected $deletePermission = "Komposisi_Beton.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'Komposisi_beton/Komposisi_beton_model'
        ));
        $this->template->title('Komposisi Beton');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Komposisi Beton');
        $this->template->render('index');
    }

    public function add($id_komposisi_beton = null)
    {
        $this->auth->restrict($this->viewPermission);

        if ($id_komposisi_beton !== '') {
            $get_header = $this->db->get_where('tr_jenis_beton_header', ['id_komposisi_beton' => $id_komposisi_beton])->row();
            $get_detail = $this->db->get_where('tr_jenis_beton_detail', ['id_komposisi_beton' => $id_komposisi_beton])->result();

            $this->template->set('header', $get_header);
            $this->template->set('detail', $get_detail);

            $this->template->title('Edit Komposisi Beton');
        } else {
            $this->template->title('Add Komposisi Beton');
        }

        $this->db->select('a.*');
        $this->db->from('new_inventory_4 a');
        $this->db->where('a.category', 'material');
        $this->db->where('a.deleted_by', null);
        $get_list_material = $this->db->get()->result();

        $this->template->set('id_komposisi_beton', $id_komposisi_beton);
        $this->template->set('list_material', $get_list_material);
        $this->template->render('add');
    }

    public function view($id_komposisi_beton)
    {
        $this->auth->restrict($this->viewPermission);
        $get_header = $this->db->get_where('tr_jenis_beton_header', ['id_komposisi_beton' => $id_komposisi_beton])->row();
        $get_detail = $this->db->get_where('tr_jenis_beton_detail', ['id_komposisi_beton' => $id_komposisi_beton])->result();

        $this->template->set('header', $get_header);
        $this->template->set('detail', $get_detail);
        $this->template->title('View Komposisi Beton');
        $this->template->render('view');
    }

    public function save_komposisi_beton()
    {
        $post = $this->input->post();

        $this->db->trans_begin();

        $id_komposisi_beton = $this->Komposisi_beton_model->generate_id_komposisi_beton();

        if ($post['id_komposisi_beton'] == '') {
            $arr_header = [
                'id_komposisi_beton' => $id_komposisi_beton,
                'nm_jenis_beton' => $post['jenis_beton'],
                'keterangan' => $post['keterangan'],
                'created_by' => $this->auth->user_id(),
                'created_date' => date('Y-m-d H:i:s')
            ];

            $arr_detail = [];

            if (isset($post['detail_material'])) {
                foreach ($post['detail_material'] as $item) {

                    $arr_detail[] = [
                        'id_komposisi_beton' => $id_komposisi_beton,
                        'id_material' => $item['id_material'],
                        'nm_material' => $item['material_name'],
                        'volume' => $item['volume'],
                        'satuan_lainnya' => $item['satuan_lainnya'],
                        'satuan' => $item['satuan'],
                        'keterangan' => $item['keterangan'],
                        'created_by' => $this->auth->user_id(),
                        'created_date' => date('Y-m-d H:i:s')
                    ];
                }
            }

            $insert_header = $this->db->insert('tr_jenis_beton_header', $arr_header);
            if (!$insert_header) {
                $this->db->trans_rollback();

                print_r($this->db->error($insert_header));
                exit;
            }

            if (!empty($arr_detail)) {
                $insert_detail = $this->db->insert_batch('tr_jenis_beton_detail', $arr_detail);
                if (!$insert_detail) {
                    $this->db->trans_rollback();

                    print_r($this->db->error($insert_detail));
                    exit;
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $valid = 0;
                $pesan = 'Save data failed !';
            } else {
                $this->db->trans_commit();

                $valid = 1;
                $pesan = 'Save data success !';
            }
        } else {
            $id_komposisi_beton = $post['id_komposisi_beton'];

            $this->db->delete('tr_jenis_beton_detail', ['id_komposisi_beton' => $id_komposisi_beton]);

            $arr_header = [
                'id_komposisi_beton' => $id_komposisi_beton,
                'nm_jenis_beton' => $post['jenis_beton'],
                'keterangan' => $post['keterangan'],
                'updated_by' => $this->auth->user_id(),
                'updated_date' => date('Y-m-d H:i:s')
            ];

            $arr_detail = [];
            if (isset($post['detail_material'])) {
                foreach ($post['detail_material'] as $item) {
                    $arr_detail[] = [
                        'id_komposisi_beton' => $id_komposisi_beton,
                        'id_material' => $item['id_material'],
                        'nm_material' => $item['material_name'],
                        'volume' => $item['volume'],
                        'satuan_lainnya' => $item['satuan_lainnya'],
                        'satuan' => $item['satuan'],
                        'keterangan' => $item['keterangan'],
                        'created_by' => $this->auth->user_id(),
                        'created_date' => date('Y-m-d H:i:s')
                    ];
                }
            }

            $update_header = $this->db->update('tr_jenis_beton_header', $arr_header, ['id_komposisi_beton' => $id_komposisi_beton]);
            if (!$update_header) {
                $this->db->trans_rollback();

                print_r('error 1 ' . $this->db->error($update_header));
                exit;
            }

            if (!empty($arr_detail)) {
                $update_detail = $this->db->insert_batch('tr_jenis_beton_detail', $arr_detail);
                if (!$update_detail) {
                    $this->db->trans_rollback();

                    print_r($this->db->last_query());
                    exit;
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $valid = 0;
                $pesan = 'Update data failed !';
            } else {
                $this->db->trans_commit();

                $valid = 1;
                $pesan = 'Update data success !';
            }
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function del_komposisi_beton()
    {
        $id = $this->input->post('id');

        $this->db->trans_begin();

        $this->db->delete('tr_jenis_beton_header', ['id_komposisi_beton' => $id]);
        $this->db->delete('tr_jenis_beton_detail', ['id_komposisi_beton' => $id]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            $valid = 0;
            $pesan = 'Delete data failed !';
        } else {
            $this->db->trans_commit();

            $valid = 1;
            $pesan = 'Delete data success !';
        }

        echo json_encode([
            'status' => $valid,
            'pesan' => $pesan
        ]);
    }

    public function get_nm_material() {
        $post = $this->input->post();

        $nm_material = '';
        $get_nm_material = $this->db->get_where('new_inventory_4', ['code_lv4' => $post['id_material']])->row();

        if(!empty($get_nm_material)) {
            $nm_material = $get_nm_material->nama;
        }

        echo json_encode([
            'nm_material' => $nm_material
        ]);
        
    }

    public function hitung_volume_lain() {
        $post = $this->input->post();

        $satuan_lainnya = 0;
        $satuan = '';

        $this->db->select('a.*, b.code as satuan');
        $this->db->from('new_inventory_4 a');
        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
        $this->db->where('a.code_lv4', $post['id_material']);
        $get_material = $this->db->get()->row();

        if(!empty($get_material)) {
            $satuan_lainnya = ($post['input_volume'] * $get_material->konversi);
            $satuan = $get_material->satuan;
        }

        echo json_encode([
            'satuan_lainnya' => ucfirst($satuan_lainnya),
            'satuan' => $satuan
        ]);
    }

    public function get_data_jenis_beton()
    {
        $this->Komposisi_beton_model->get_data_jenis_beton();
    }
}
