<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class W extends MY_Controller {

	public function index($productDetail, $productId)
	{
//		$data['param'] = $this->input->get('param'); //added by Mike, 20170616
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
//		$productName = str_replace('-',' ',$param); 
			
		$this->load->model('W_Model');
		$data['result'] = $this->W_Model->getProduct($productId);
		$data['merchant_categories'] = $this->W_Model->getMerchantCategories($data['result']->merchant_id);
		

		//added by Mike, 20170824
		$this->W_Model->incrementViewNum($productId);
		
		
		
//		$data['result'] = $this->W_Model->getProduct($param);
		
		$mobileResponsiveSetting = $this->session->userdata('is_mobile_responsive');
		if (isset($mobileResponsiveSetting) && ($mobileResponsiveSetting)) {
			//--------------------------------------------
			$this->load->view('w_v2', $data);
			//--------------------------------------------
			$this->load->view('templates/footer_v2');
		} else {
			//--------------------------------------------
			$this->load->view('w', $data);
			//--------------------------------------------
			$this->load->view('templates/footer');
		}
	}
}