<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {

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
	public function login()//$param)
	{
//		$data['param'] = $this->input->get('param'); //added by Mike, 20170616
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
/*		
		$this->load->library('session');
		$this->load->library('form_validation');
*/		
/*				
		$fields = array('emailAddressParam', 'passwordParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
		
		$this->load->model('Account_Model');
		$data['is_login_success'] = $this->Account_Model->loginAccount($data);
*/				
		$this->load->view('account/login');
		
		//--------------------------------------------
		$this->load->view('templates/footer');	
	}
	
	public function ordersummary() {
		$customer_id = $this->session->userdata('customer_id');
		
		if (!isset($customer_id)) {
			redirect('account/login'); //home page
		}
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		
		$this->load->model('Account_Model');
		$data['order_summary'] = $this->Account_Model->getCustomerOrders($customer_id);

		$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;
		
		$this->load->view('account/ordersummary', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');	
	}
	
	public function ordersummaryadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');		
		
		if ((!isset($customer_id)) ||
//			($customer_id!="12")) {
			($is_admin!="1")) {			
				redirect('account/login'); //home page
		}
				
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->model('Account_Model');
		$fulfilled_status = $this->uri->segment(3);
		if ($fulfilled_status!==null) {			
			date_default_timezone_set('Asia/Hong_Kong');			
			$addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(4));			
			$productCustomerId = $this->uri->segment(5);
			
			$this->Account_Model->updateCustomerOrderAdmin($fulfilled_status, $addedDateTimeStamp, $productCustomerId);			
		}
						
		$data['order_summary'] = $this->Account_Model->getCustomerOrdersAdmin();
		
		$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;
		
		$this->load->view('account/ordersummaryadmin', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	public function ordersummarymerchant() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		$merchant_id = $this->session->userdata('merchant_id');
		
		if ((!isset($customer_id)) ||
		//			($customer_id!="12")) {
				($is_admin!="1") ||
				($merchant_id=="0")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				$this->load->model('Account_Model');
				$fulfilled_status = $this->uri->segment(3);
				if ($fulfilled_status!==null) {
					date_default_timezone_set('Asia/Hong_Kong');
					$addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(4));
					$productId = $this->uri->segment(5);
					
					$this->Account_Model->updateCustomerOrderMerchant($fulfilled_status, $addedDateTimeStamp, $productId);
				}
								
				$data['order_summary'] = $this->Account_Model->getCustomerOrdersMerchant($merchant_id);				
/*
				foreach ($data['order_summary'] as $value) {
					foreach ($value as $v) {
						echo 'hey '.$v.'<br>';
					}
					
					echo 'space <br>';				
				}
*/				
				/*				
				//added by Mike, 20171001
				$data['order_details'] = [];
				foreach ($data['order_summary'] as $value) {
//					$result = $this->Account_Model->getOrderDetailsMerchant($merchant_id, $value['purchased_datetime_stamp']);
//					echo 'hey '.count($result);					
					$data['order_details'] += $this->Account_Model->getOrderDetailsMerchant($merchant_id, $value['purchased_datetime_stamp']);
				}
*/				
/*
				foreach ($data['order_details'] as $value) {
					echo 'hello '.$value['quantity'];
				}
*/				
				$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;

				$data['merchant_name'] = $this->Account_Model->getCustomerName($customer_id)->customer_first_name." ".$this->Account_Model->getCustomerName($customer_id)->customer_last_name;
				
				$this->load->view('account/ordersummarymerchant', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}
	
	//added by Mike, 20171009
	public function carthistoryadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		
		if ((!isset($customer_id)) ||
		//			($customer_id!="12")) {
				($is_admin!="1")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				$this->load->model('Account_Model');
/*				
				$fulfilled_status = $this->uri->segment(3);
				if ($fulfilled_status!==null) {
					date_default_timezone_set('Asia/Hong_Kong');
					$addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(4));
					$productCustomerId = $this->uri->segment(5);
					
					$this->Account_Model->updateCustomerOrderAdmin($fulfilled_status, $addedDateTimeStamp, $productCustomerId);
				}
*/				
				$data['cart_history'] = $this->Account_Model->getCartHistoryAdmin();
				
				$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;
				
				$this->load->view('account/carthistoryadmin', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}
	
	//added by Mike, 20171010
	public function customerdetailsadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		
		if ((!isset($customer_id)) ||
				//			($customer_id!="12")) {
				($is_admin!="1")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				$this->load->model('Account_Model');
				/*
				 $fulfilled_status = $this->uri->segment(3);
				 if ($fulfilled_status!==null) {
				 date_default_timezone_set('Asia/Hong_Kong');
				 $addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(4));
				 $productCustomerId = $this->uri->segment(5);
				 
				 $this->Account_Model->updateCustomerOrderAdmin($fulfilled_status, $addedDateTimeStamp, $productCustomerId);
				 }
				 */

				$customerBuyerId= $this->uri->segment(3);								
				
				$data['cart_history'] = $this->Account_Model->getCustomerCartHistoryAdmin($customerBuyerId);
				
				$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customerBuyerId)->customer_email_address;
				
				$data['result'] = $this->Account_Model->getCustomerInformation($customerBuyerId);
				
				$this->load->view('account/customerdetailsadmin', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}
		
	public function orderdetails() {
		$customer_id = $this->session->userdata('customer_id');
		
		if (!isset($customer_id)) {
			redirect('account/login'); //home page
		}
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------

		date_default_timezone_set('Asia/Hong_Kong');
		$addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(3));		
//		echo 'hello '.$addedDateTimeStamp.'<br>';

		$this->load->model('Account_Model');
		$data['order_details'] = $this->Account_Model->getOrderDetails($customer_id, $addedDateTimeStamp);				
				
		$data['result'] = $this->Account_Model->getCustomerInformation($customer_id);

		//added by Mike, 20170922
		$first_row;
		foreach($data['order_details'] as $row)
		{
			$first_row = $row;
			break;
		}

		$data['customer_address_at_the_time_of_purchase'] = $this->Account_Model->getCustomerAddressFromCustomerOrder($first_row['customer_order_id']);
		
		$this->load->view('account/orderdetails', $data);

		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	public function orderdetailsadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		
		if ((!isset($customer_id)) ||
//			($customer_id!="12")) {
			($is_admin!="1")) {
				redirect('account/login'); //home page
		}
				
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		date_default_timezone_set('Asia/Hong_Kong');
		$addedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(3));
//		echo 'hello '.$addedDateTimeStamp.'<br>';
		
		$product_customer_id = $this->uri->segment(4);
		
		$this->load->model('Account_Model');
		$data['order_details'] = $this->Account_Model->getOrderDetailsAdmin($product_customer_id, $addedDateTimeStamp);
		
		$data['result'] = $this->Account_Model->getCustomerInformation($product_customer_id);
		
		//added by Mike, 20170922
		$first_row;
		foreach($data['order_details'] as $row)
		{
			$first_row = $row;
			break;
		}
		
		$data['customer_address_at_the_time_of_purchase'] = $this->Account_Model->getCustomerAddressFromCustomerOrder($first_row['customer_order_id']);		
		
		$this->load->view('account/orderdetailsadmin', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	public function orderdetailsmerchant() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		$merchant_id = $this->session->userdata('merchant_id');
		
		if ((!isset($customer_id)) ||
		//			($customer_id!="12")) {
				($is_admin!="1") ||
				($merchant_id=="0")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				date_default_timezone_set('Asia/Hong_Kong');
				$purchasedDateTimeStamp = date('Y-m-d H:i:s', $this->uri->segment(3));
				//		echo 'hello '.$addedDateTimeStamp.'<br>';
				
				$product_id = $this->uri->segment(4);
				
				$this->load->model('Account_Model');
				$data['order_details'] = $this->Account_Model->getOrderDetailsMerchant($merchant_id, $purchasedDateTimeStamp);
				
				//added by Mike, 20170922
				$first_row;
				foreach($data['order_details'] as $row)
				{
					$first_row = $row;
					break;
				}
		
				$data['result'] = $this->Account_Model->getCustomerInformation($first_row['customer_id']); //$data['order_details']
				
				$data['customer_address_at_the_time_of_purchase'] = $this->Account_Model->getCustomerAddressFromCustomerOrder($first_row['customer_order_id']); //data['order_details']
								
				$data['merchant_name'] = $this->Account_Model->getCustomerMerchantName($merchant_id)->customer_first_name." ".$this->Account_Model->getCustomerMerchantName($merchant_id)->customer_last_name;
								
				$this->load->view('account/orderdetailsmerchant', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}
	
	public function requestsummaryadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		
		if ((!isset($customer_id)) ||
		//			($customer_id!="12")) {
				($is_admin!="1")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				$this->load->model('Account_Model');
				$fulfilled_status = $this->uri->segment(3);
				$customer_request_id = $this->uri->segment(4);
				
				if ($fulfilled_status!==null) {					
					$this->Account_Model->updateCustomerRequestAdmin($fulfilled_status, $customer_request_id);
				}
				
				$data['request_summary'] = $this->Account_Model->getCustomerRequestAdmin();

//				$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;
				
				$this->load->view('account/requestsummaryadmin', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}

	public function sellsummaryadmin() {
		$customer_id = $this->session->userdata('customer_id');
		$is_admin = $this->session->userdata('is_admin');
		
		if ((!isset($customer_id)) ||
				//			($customer_id!="12")) {
				($is_admin!="1")) {
					redirect('account/login'); //home page
				}
				
				//from application/core/MY_Controller
				$this::initStyle();
				$this::initHeader();
				//--------------------------------------------
				
				$this->load->model('Account_Model');
				$fulfilled_status = $this->uri->segment(3);
				$customer_sell_id = $this->uri->segment(4);
				
				if ($fulfilled_status!==null) {
					$this->Account_Model->updateCustomerSellAdmin($fulfilled_status, $customer_sell_id);
				}
				
				$data['sell_summary'] = $this->Account_Model->getCustomerSellAdmin();
				
//				$data['customer_email_address'] = $this->Account_Model->getCustomerEmailAddress($customer_id)->customer_email_address;
				
				$this->load->view('account/sellsummaryadmin', $data);
				
				//--------------------------------------------
				$this->load->view('templates/footer');
	}
	
	public function logout() {
		session_destroy();
		
		redirect(''); //home page		
	}
	
	public function create()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->library('session');
		$this->load->library('form_validation');
		
		/*
		 $this->load->model('Cart_Model');
		 $data['result'] = $this->Cart_Model->getCart();//$this->input->post('customer'));//$param);
		 */
		$this->load->view('account/create');
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	

	public function settings()
	{
		$customer_id = $this->session->userdata('customer_id');
		
		if (!isset($customer_id)) {
			redirect('account/login'); //home page			
		}
		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('Account_Model');
		$data['result'] = $this->Account_Model->getCustomerInformation($customer_id);
				
		$this->load->view('account/settings', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}

	public function settingsmerchant()
	{
		//TODO: fix this
		$this->settings();
	}
	
	public function save()
	{				
		$customer_id = $this->session->userdata('customer_id');

		$this->form_validation->set_rules('emailAddressParam', 'Email Address', 'valid_email|trim|required');
		$this->form_validation->set_rules('firstNameParam', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastNameParam', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('contactNumberParam', 'Contact Number', 'trim|required|numeric');
		$this->form_validation->set_rules('shippingAddressParam', 'Shipping Address', 'trim|required');
		$this->form_validation->set_rules('cityParam', 'City', 'trim|required');
		$this->form_validation->set_rules('countryParam', 'Country', 'trim|required');
		$this->form_validation->set_rules('postalCodeParam', 'Postal Code', 'trim|required|numeric');
		
		$fields = array('emailAddressParam', 'firstNameParam', 'lastNameParam', 'contactNumberParam', 'shippingAddressParam', 'cityParam', 'countryParam', 'postalCodeParam', 'modeOfPaymentParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('data', $data);
			
			//from application/core/MY_Controller
			$this::initStyle();
			$this::initHeader();
			//--------------------------------------------
			
			$this->load->library('session');
			$this->load->library('form_validation');
									
			$this->load->view('account/settings');
			
			//--------------------------------------------
			$this->load->view('templates/footer');
		}
		else
		{
			/*
			 $this->load->model('Account_Model');
			 $this->Account_Model->registerAccount($data);
			 
			 //added by Mike, 20170624
			 $newdata = array(
			 'customer_first_name'  => $data['firstNameParam'],
			 'customer_email_address'     => $data['emailAddressParam'],
			 'logged_in' => TRUE
			 );
			 $this->session->set_userdata($newdata);
			 
			 $this::initStyle();
			 $this::initHeader();
			 
			 //--------------------------------------------
			 
			 $this->load->model('Books_Model');
			 $data['books'] = $this->Books_Model->getBooks();
			 $this->load->view('b/books',$data);
			 
			 //--------------------------------------------
			 $this->load->view('templates/footer');
			 */
//			echo "OK! Success!";
/*			
			$this->session->set_flashdata('data', $data);
			
			$newdata = array(
					'customer_id'  => $customer_id
			);
			$this->session->set_userdata($newdata);
*/			
/*			
//			$customer_id = $this->session->userdata('customer_id');
			
			//from application/core/MY_Controller
			$this::initStyle();
			$this::initHeader();
			//--------------------------------------------
			
			$this->load->library('session');
			$this->load->library('form_validation');
			
			$this->load->model('Account_Model');
			$this->Account_Model->updateAccount($customer_id, $data);
			
			$this->load->view('account/settings');
			
			//--------------------------------------------
			$this->load->view('templates/footer');			
*/
			$this->load->model('Account_Model');
			$this->Account_Model->updateAccount($customer_id, $data);
			
			$this->settings();
		}
	}	

	public function updatepassword() {
		$this->updatepasswordWith(null);
	}
	
	public function updatepasswordWith($param)
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		
		 $this->load->library('session');
		 $this->load->library('form_validation');
		
		/*
		 $fields = array('emailAddressParam', 'passwordParam');
		 
		 foreach ($fields as $field)
		 {
		 $data[$field] = $_POST[$field];
		 }
		 
		 $this->load->model('Account_Model');
		 $data['is_login_success'] = $this->Account_Model->loginAccount($data);
		 */
		 		 		 
		$data['is_update_password_successful'] = $param;
		$this->load->view('account/updatepassword', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	public function savepassword()
	{
		$customer_id = $this->session->userdata('customer_id');
		
		$this->form_validation->set_rules('currentPasswordParam', 'Current Password', 'trim|required');
		$this->form_validation->set_rules('newPasswordParam', 'New Password', 'trim|required');
		$this->form_validation->set_rules('confirmNewPasswordParam', 'Confirm New Password', 'trim|required|matches[newPasswordParam]');
		
		$fields = array('currentPasswordParam', 'newPasswordParam', 'confirmNewPasswordParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
		
		$data['customerId'] = $customer_id;
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('data', $data);
			
			$this->updatepassword();			
		}
		else
		{			
			$this->load->model('Account_Model');
			$data['password_is_incorrect'] = $this->Account_Model->isCurrentPasswordCorrect($data);
			
			if (isset($data['password_is_incorrect'])) {
				$this->session->set_flashdata('data', $data);				
				$this->updatepassword();				
			}
			else {
				$this->Account_Model->updateAccountPassword($customer_id, $data);				
				$this->updatepasswordWith("success");				
			}			
		}
	}	
}
