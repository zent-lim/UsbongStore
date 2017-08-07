<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{		
		$customer_id = $this->session->userdata('customer_id');
				
		if (!isset($customer_id)) {
			redirect('account/login'); //home page
		}
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->view('request');
		
		//--------------------------------------------
		$this->load->view('templates/footer');	
	}
	
	public function confirm()
	{				
		$customer_id = $this->session->userdata('customer_id');

		if (!isset($customer_id)) {
			redirect('account/login'); //home page
		}
	
		$fields = array('productNameParam', 'productLinkParam', 'productTypeParam', 'quantityParam', 'totalBudgetParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
		
		$this->load->model('Request_Model');
		$data["is_success"] = $this->Request_Model->insertRequest($data, $customer_id);
				
		$this->session->set_flashdata('data', $data);
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->library('session');
		$this->load->library('form_validation');
								
		$this->load->view('request');
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
}