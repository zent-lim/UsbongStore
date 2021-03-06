<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends MY_Controller {

    //------------------------//
    // Private Functions
    //------------------------//

    // throws error if user has no access right
    private function can_user_access() {
        // load dependencies
        $this->load->library('session');

        // access session fields
        $customer_id = $this->session->userdata('customer_id');
        $is_admin    = $this->session->userdata('is_admin');

        // check if user is signed
        if (!isset($customer_id)) {
            redirect('account/login');
        }

        // check if user is admin
        if ($is_admin != 1) {
            show_error('Admin access is required.', 403, 'Authentication Failed');
        }
    }

    //------------------------//
    // Private Functions: Product
    //------------------------//

    private function render_product_form($product_model = NULL) {

        //------------------------//
        // Init
        //------------------------//

        // check authentication
        self::can_user_access();

        // load dependencies: helpers
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('url');
        // load dependencies: libraries
        $this->load->library('form_validation');
        // load dependencies: models
        $this->load->model('Merchant_Model');
        $this->load->model('Product_Model');
        $this->load->model('Product_Type_Model');

        $data['mode'] = 'add';

        //------------------------//
        // Access Database
        //------------------------//

        $data['merchant_model']     = $this->Merchant_Model->getAll();
        $data['product_type_model'] = $this->Product_Type_Model->getAll();

        //------------------------//
        // Switch to Edit Mode
        //------------------------//

        if (isset($product_model)) {
            $data['product_model'] = $product_model;
            $data['mode']          = 'edit';
        }

        //------------------------//
        // Process Post Request
        //------------------------//

        // required fields
        $this->form_validation->set_rules('image'            , 'Image'       , 'required');
        if ($data['mode'] == 'edit') {
            $this->form_validation->set_rules('name'             , 'Name'        , 'trim|required|max_length[100]');
        } else {
            $this->form_validation->set_rules('name'             , 'Name'        , 'trim|required|max_length[100]|is_unique[product.name]');
        }
        $this->form_validation->set_rules('merchant_id'      , 'Merchant'    , 'required|integer');
        $this->form_validation->set_rules('product_type_id'  , 'Product Type', 'required|integer');
        $this->form_validation->set_rules('quantity_in_stock', 'Quantity'    , 'required|integer');
        $this->form_validation->set_rules('price'            , 'Price'       , 'required|integer');
        $this->form_validation->set_rules('show'             , 'Show'        , 'required|integer|less_than_equal_to[1]');

        // optional fields
        $this->form_validation->set_rules('previous_price', 'Previous Price', 'max_length[30]');
        $this->form_validation->set_rules('language'      , 'Language'      , 'max_length[20]');
        $this->form_validation->set_rules('author'        , 'Author'        , 'max_length[50]');
        $this->form_validation->set_rules('supplier'      , 'Supplier'      , 'max_length[30]');
        $this->form_validation->set_rules('description'   , 'Description'   , 'max_length[100]');
        $this->form_validation->set_rules('format'        , 'Format'        , 'max_length[10]');
        $this->form_validation->set_rules('translator'    , 'Translator'    , 'max_length[50]');
        $this->form_validation->set_rules('pages'         , 'Pages'         , 'integer|greater_than[0]');
        $this->form_validation->set_rules('external_url'  , 'External Url'  , 'trim|prep_url|valid_url');
        $this->form_validation->set_rules('publisher'  , 'Publisher'  , 'max_length[50]'); //added by Mike, 20180331
        
        if ($this->form_validation->run()) {
            // save to database
            $save_data = [
                // required
                'name'              => $this->input->post('name'),
                'merchant_id'       => $this->input->post('merchant_id'),
                'product_type_id'   => $this->input->post('product_type_id'),
                'quantity_in_stock' => $this->input->post('quantity_in_stock'),
                'price'             => $this->input->post('price'),
                'image'             => $this->input->post('image'),
                'show'              => $this->input->post('show'),
                // optional
                'previous_price'    => $this->input->post('previous_price'),
                'language'          => $this->input->post('language'),
                'author'            => $this->input->post('author'),
                'supplier'          => $this->input->post('supplier'),
                'description'       => $this->input->post('description'),
                'format'            => $this->input->post('format'),
                'translator'        => $this->input->post('translator'),
                'product_overview'  => $this->input->post('product_overview'),
                'pages'             => $this->input->post('pages'),
                'external_url'      => $this->input->post('external_url'),
            	'publisher'      	=> $this->input->post('publisher'),            	            		
            	'released_date'      	=> $this->input->post('released_date'),            		            		
            ];

            if ($data['mode'] == 'edit') {
                $save_result = $this->Product_Model->editRow($data['product_model']['product_id'], $save_data);
            } else {
                $save_result = $this->Product_Model->insertRow($save_data);
            }

            if ($save_result['success']) {
                // redirect to new product page
                redirect('manage/index');
            } else {
                // report error
                $this->session->set_flashdata('manage-add-error', $save_result['error']);
            }
        }

        //------------------------//
        // Render Form
        //------------------------//

        // render view
        $this->load->view('manage/product_form', $data);
    }

    //------------------------//
    // Private Functions: Merchant
    //------------------------//

    private function render_merchant_form($merchant_model = NULL) {

        //------------------------//
        // Init
        //------------------------//

        // check authentication
        self::can_user_access();

        // load dependencies: helpers
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('url');
        // load dependencies: libraries
        $this->load->library('form_validation');
        // load dependencies: models
        $this->load->model('Merchant_Model');

        $data['mode'] = 'add';

        //------------------------//
        // Switch to Edit Mode
        //------------------------//

        if (isset($merchant_model)) {
            $data['merchant_model'] = $merchant_model;
            $data['mode']          = 'edit';
        }

        //------------------------//
        // Process Post Request
        //------------------------//

        // required fields
        if ($data['mode'] == 'edit') {
            $this->form_validation->set_rules('merchant_name', 'Merchant Name', 'trim|required|max_length[50]');
        } else {
            $this->form_validation->set_rules('merchant_name', 'Merchant Name', 'trim|required|max_length[50]|is_unique[merchant.merchant_name]');
        }
        $this->form_validation->set_rules('image'                    , 'Image'                    , 'required');
        $this->form_validation->set_rules('merchant_motto'           , 'Merchant Motto'           , 'trim|required|max_length[50]');
        $this->form_validation->set_rules('merchant_motto_font_color', 'Merchant Motto Font Color', 'required|regex_match[/^#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9]$/i]');

        if ($this->form_validation->run()) {
            // save to database
            $save_data = [
                // required
                'merchant_name'             => $this->input->post('merchant_name'),
                'merchant_motto'            => $this->input->post('merchant_motto'),
                'merchant_motto_font_color' => $this->input->post('merchant_motto_font_color'),
                'image'                     => $this->input->post('image'),
            ];

            if ($data['mode'] == 'edit') {
                $save_result = $this->Merchant_Model->editRow($data['merchant_model']['merchant_id'], $save_data);
            } else {
                $save_result = $this->Merchant_Model->insertRow($save_data);
            }

            if ($save_result['success']) {
                // redirect to merchant page
                redirect('manage/merchants');
            } else {
                // report error
                $this->session->set_flashdata('manage-merchant-add-error', $save_result['error']);
            }
        }

        //------------------------//
        // Render Form
        //------------------------//

        // render view
        $this->load->view('manage/merchant_form', $data);
    }

    //------------------------//
    // Public Functions: Products
    //------------------------//

    public function index($page = NULL) {

        //------------------------//
        // Init
        //------------------------//

        // check authentication
        self::can_user_access();

        // load header
        $this::initStyle();
        $this::initHeader();

        // load dependencies: helpers
        $this->load->helper('html');
        $this->load->helper('url');
        // load dependencies
        $this->load->model('Product_Model');

        // prepare data
        if (!isset($page)) {
            $page = 1;
        }

        //------------------------//
        // Init filters
        //------------------------//

        $data['page']['filters']['name']           = $this->input->get('name_filter');
        $data['page']['filters']['author']         = $this->input->get('author_filter');
        $data['page']['options']['quantity_order'] = $this->input->get('quantity_order');
        $data['page']['options']['search']         = $this->input->get('search');

        //------------------------//
        // Render
        //------------------------//

        $data['page']['filters']['name']           = (isset($data['page']['filters']['name']          ) AND !empty(trim($data['page']['filters']['name']  ))  ) ? trim($data['page']['filters']['name']  )   : NULL;
        $data['page']['filters']['author']         = (isset($data['page']['filters']['author']        ) AND !empty(trim($data['page']['filters']['author']))  ) ? trim($data['page']['filters']['author'])   : NULL;
        $data['page']['options']['quantity_order'] = (isset($data['page']['options']['quantity_order']) AND !empty($data['page']['options']['quantity_order'])) ? $data['page']['options']['quantity_order'] : NULL;
        $data['page']['options']['search']         = (isset($data['page']['options']['search']        ) AND !empty($data['page']['options']['search'])        ) ? $data['page']['options']['search']         : 'and';

        $data['page']['page']          = $page;
        $data['page']['max_page']      = $this->Product_Model->getMaxPage($data['page']['filters'], $data['page']['options']);
        $data['page']['prev_page']     = ($page > 1                        ) ? ($page - 1) : NULL;
        $data['page']['next_page']     = ($page < $data['page']['max_page']) ? ($page + 1) : NULL;
        $data['products']              = $this->Product_Model->getPage($page, $data['page']['filters'], $data['page']['options']);

        // render view
        $this->load->view('manage/index', $data);

        // load footer
        $this->load->view('templates/footer');
    }

    public function add() {
        // check authentication
        self::can_user_access();

        // load header
        $this::initStyle();
        $this::initHeader();

        // render form
        self::render_product_form();

        // load footer
        $this->load->view('templates/footer');
    }

    public function edit($product_id = NULL) {
        // check authentication
        self::can_user_access();

        // load dependencies: models
        $this->load->model('Product_Model');

        // load header
        $this::initStyle();
        $this::initHeader();

        // check if Entry Exists
        $data['product_model'] = $this->Product_Model->get($product_id);
        if (!isset($data['product_model'])) {
            show_error('Product ID is not valid.', 404, 'Item Not Found');
        }

        // render form
        self::render_product_form($data['product_model']);

        // load footer
        $this->load->view('templates/footer');
    }

    //------------------------//
    // Public Functions: Merchants
    //------------------------//

    public function merchants($page = NULL) {

        //------------------------//
        // Init
        //------------------------//

        // check authentication
        self::can_user_access();

        // load header
        $this::initStyle();
        $this::initHeader();

        // load dependencies: helpers
        $this->load->helper('html');
        $this->load->helper('url');
        // load dependencies
        $this->load->model('Merchant_Model');

        // prepare data
        if (!isset($page)) {
            $page = 1;
        }

        //------------------------//
        // Init filters
        //------------------------//

        $data['page']['filters']['merchant_name']  = $this->input->get('merchant_name_filter');
        $data['page']['filters']['merchant_motto'] = $this->input->get('merchant_motto_filter');
        $data['page']['options']['search']         = $this->input->get('search');

        //------------------------//
        // Render
        //------------------------//

        $data['page']['filters']['merchant_name']  = (isset($data['page']['filters']['merchant_name'] ) AND !empty(trim($data['page']['filters']['merchant_name'] ))) ? trim($data['page']['filters']['merchant_name'] ) : NULL;
        $data['page']['filters']['merchant_motto'] = (isset($data['page']['filters']['merchant_motto']) AND !empty(trim($data['page']['filters']['merchant_motto']))) ? trim($data['page']['filters']['merchant_motto']) : NULL;
        $data['page']['options']['search']         = (isset($data['page']['options']['search']        ) AND !empty($data['page']['options']['search']              )) ? $data['page']['options']['search']               : 'and';

        $data['page']['page']          = $page;
        $data['page']['max_page']      = $this->Merchant_Model->getMaxPage($data['page']['filters'], $data['page']['options']);
        $data['page']['prev_page']     = ($page > 1                        ) ? ($page - 1) : NULL;
        $data['page']['next_page']     = ($page < $data['page']['max_page']) ? ($page + 1) : NULL;
        $data['merchants']             = $this->Merchant_Model->getPage($page, $data['page']['filters'], $data['page']['options']);

        // render view
        $this->load->view('manage/merchants', $data);

        // load footer
        $this->load->view('templates/footer');
    }

    public function merchant_add() {
        // check authentication
        self::can_user_access();

        // load header
        $this::initStyle();
        $this::initHeader();

        // render form
        self::render_merchant_form();

        // load footer
        $this->load->view('templates/footer');
    }

    public function merchant_edit($merchant_id = NULL) {
        // check authentication
        self::can_user_access();

        // load dependencies: models
        $this->load->model('Merchant_Model');

        // load header
        $this::initStyle();
        $this::initHeader();

        // check if Entry Exists
        $data['merchant_model'] = $this->Merchant_Model->get($merchant_id);
        if (!isset($data['merchant_model'])) {
            show_error('Merchant ID is not valid.', 404, 'Merchant Not Found');
        }

        // render form
        self::render_merchant_form($data['merchant_model']);

        // load footer
        $this->load->view('templates/footer');
    }

}
