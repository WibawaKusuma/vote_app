<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Vote_Detail extends CI_Controller
{
    protected $_translation = 'management_alkes';
    protected $_model = 'vote_model';

    public function __construct()
    {
        parent::__construct();

        $this->load->model("vote_model");

        $this->page = "inquiry";
    }

    public function index($item = NULL)
    {
        $data = array(
            "collection" => $this->vote_model->get_alkes_detail(@$item->No_Bukti),
            "form" => TRUE,
            'datatables' => TRUE,
            // 'create_detail' => base_url("inquiry/inquiries/details/item_create"),
            // 'delete_detail' => base_url("inquiry/inquiries/details/item_delete"),
            // 'view_detail' => base_url("inquiry/inquiries/details/item_view"),
        );

        $this->load->view('admin/details', $data);
    }

    public function lookup_detail($is_ajax_request = false)
    {
        if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
            $this->load->view('inquiries/lookup/details');
        }
    }

    public function lookup_supplier($is_ajax_request = false)
    {
        if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
            $this->load->view('inquiries/details/lookup/suppliers');
        }
    }

    public function lookup_product($is_ajax_request = false)
    {
        if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
            $this->load->view('management_alkes/lookup/products');
        }
    }

    public function lookup($is_ajax_request = false)
    {
        if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
            $this->load->view('icd/lookup/datatable');
        } else {
            $data = array(
                'page' => $this->page,
                'datatables' => TRUE,
                'form' => TRUE,
            );

            $this->template
                ->set("heading", "Lookup Box")
                ->set_breadcrumb(lang("common:page"), base_url("common"))
                ->set_breadcrumb("Lookup Box")
                ->build('icd/lookup', (isset($data) ? $data : NULL));
        }
    }

    public function lookup_collection()
    {
        $this->datatable_collection(1);
    }

    public function datatable_collection($state = false)
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);

        $db_where = array();
        $db_like = array();

        // prepare defautl flter
        $db_where['deleted_at'] = NULL;
        if ($state !== false) {
            $db_where['state'] = 1;
        }

        // preparing default
        if (isset($search['value']) && ! empty($search['value'])) {
            for ($i = 0; $i < count($columns); $i++) {
                if (isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true') {
                    $db_like[$columns[$i]['data']] = $search['value'];
                }
            }
        }

        // get total records
        $this->db->from("common_icd");
        if (!empty($db_where)) {
            $this->db->where($db_where);
        }
        $records_total = $this->db->count_all_results();

        // get total filtered
        $this->db->from("common_icd");
        if (!empty($db_where)) {
            $this->db->where($db_where);
        }
        if (!empty($db_like)) {
            $this->db->group_start()->or_like($db_like)->group_end();
        }
        $records_filtered = $this->db->count_all_results();

        // get result filtered
        $this->db->from("common_icd");
        if (!empty($db_where)) {
            $this->db->where($db_where);
        }
        if (!empty($db_like)) {
            $this->db->group_start()->or_like($db_like)->group_end();
        }

        // ordering
        if (isset($order)) {
            $sort_column = $order[0]['column'];
            $sort_dir = $order[0]['dir'];

            if ($columns[$sort_column]['orderable'] == 'true') {
                $this->db
                    ->order_by($columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir));
            }
        }

        // paging
        if (isset($start) && $length != '-1') {
            $this->db
                ->limit($length, $start);
        }

        // get
        $result = $this->db
            ->get()
            ->result();

        // Output
        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_filtered,
            'data' => array()
        );

        foreach ($result as $row) {
            $row->created_at = strftime(config_item('date_format'), @$row->created_at);
            $row->updated_at = strftime(config_item('date_format'), @$row->updated_at);

            $output['data'][] = $row;
        }

        $this->template
            ->build_json($output);
    }
}
