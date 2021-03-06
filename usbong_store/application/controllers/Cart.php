<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

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
	public function shoppingcart()//$param)
	{
//		$data['param'] = $this->input->get('param'); //added by Mike, 20170616
		
		$customer_id = $this->session->userdata('customer_id');
		$data['result'] = '';

		$product_id = $this->uri->segment(3);
//		$customer_id = $this->uri->segment(4);
		$quantity = $this->uri->segment(4);
		
		if ($product_id!="") {			
			//edited by Mike, 20170704
			if (($customer_id!="") && ($quantity!="")) { //do this if product_id and quantity are all uri segments				
				$this->load->model('Cart_Model');
				$this->Cart_Model->updateQuantityOfProductInCart($customer_id, $product_id, $quantity);				
			}
			else {	//do this if product_id is the only uri segment
				$this->load->model('Cart_Model');
				$this->Cart_Model->removeItemInCart($customer_id, $product_id);
			}
		}		
		
		if ($customer_id!="") {					
			$this->load->model('Cart_Model');
			$data['result'] = $this->Cart_Model->getCart($customer_id);
			$data['result'] = $this->mergeOutput($data['result']);
		}
/*		
		//added by Mike, 20170916
		$data['totalItemsInCart'] = count($data['result']);
*/		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		
		$this->load->view('shoppingcart', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');	
	}

	//added by Mike, 20170711
	public function mergeOutput($d) {		
		//merge all product items that are the same
		//increment quantity field accordingly
		$mergeOutput = array(); //$data['result'];//
		
		foreach ($d as $value) {
			if ($this->in_array_r($value['name'], $mergeOutput, false)) {
				$mergeOutput[$value['name']]['quantity'] += $value['quantity'];
				//					echo "in array".$mergeOutput[$value['name']]['quantity']."<br>";
			}
			else {
				$mergeOutput[$value['name']] = $value;
				//					echo "new ".$value['name']."<br>";
			}
			
		}
		//		$data['result'] = $finalOutput;//$mergeOutput;
		return $mergeOutput;		
	}
	
	//Reference: https://stackoverflow.com/questions/4128323/in-array-and-multidimensional-array;
	//last accessed: 20170702
	//answer by: jwueller
	public function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		
		return false;
	}
	
	public function addToCart() {//$product_idParam, $customer_idParam, $quantityParam, $priceParam) {//($productId, $customerId, $quantity, $price) {	
/*		$fields = array('product_idParam', 'customer_idParam', 'quantityParam', 'priceParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
*/		
/*
		$data = array(
				'product_id' => $product_idParam,
				'customer_id' => $customer_idParam,
				'quantity' => $quantityParam,
				'price' => $priceParam
		);
*/		
		$data = array(
				'product_id' => $this->uri->segment(3),
				'customer_id' => $this->uri->segment(4),
				'quantity' => $this->uri->segment(5),
				'price' => $this->uri->segment(6)
		);
				
		$this->load->model('Cart_Model');
		$this->Cart_Model->addToCart($data);
	}	
	
	public function checkout() {		
		$customer_id = $this->session->userdata('customer_id');

		//added by Mike, 20170710
		if ($customer_id=="") {
			redirect(site_url('account/login/'));
		}
/*  //commented out by Mike, 20180830
 		if (!($this->session->flashdata('errors')) && //or coming from field validation and there are errors
			((!isset($_POST['quantityParam0'])))) {//if POST variable is empty
			
		//edited by Mike, 20180830
		if (!($this->session->flashdata('errors'))) { //or coming from field validation and there are errors
			redirect(site_url(''));	//redirect to home page
		}		
*/		
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
	
		//added by Mike, 20170711
		if ($customer_id!="") {
			$this->load->model('Cart_Model');
			$data['result'] = $this->Cart_Model->getCart($customer_id);			
			$data['result'] = $this->mergeOutput($data['result']);
			
//			$data = $this->processCustomerOrder($data['result'], $customer_id);	
			$data = array_merge($data, $this->processCustomerOrder($data['result'], $customer_id));
		}
/*		
		$orderTotalPrice = 0;
		$totalQuantity = 0;
		
		foreach ($data['result'] as $value) {			
			$orderTotalPrice+=$value['quantity']*$value['price'];
			$totalQuantity+=$value['quantity'];			
		}

		$data = array_merge($data, array(
				'customer_id' => $customer_id,
				'quantity' => $totalQuantity,
				'status_accepted' => 1,
				'order_total_price' => $orderTotalPrice
		));
*/
		
		//added by Mike, 20170710
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('Account_Model');
		$data['customer_information_result'] = $this->Account_Model->getCustomerInformation($customer_id);
				
		
		$this->load->view('checkout', $data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
		

	}

	public function processCustomerOrder($result, $customer_id) {
/*
		$this->load->model('Cart_Model');
		$data['result'] = $this->Cart_Model->getCart($customer_id);
		
		$data['result'] = $this->mergeOutput($data['result']);
*/		
		$orderTotalPrice = 0;
		$totalQuantity = 0;

		//added by Mike, 20171117
		$this->load->model('Cart_Model');		

		foreach ($result as $value) {
			$orderTotalPrice+=$value['quantity']*$value['price'];
			$totalQuantity+=$value['quantity'];

			//added by Mike, 20171117			
			$this->Cart_Model->updateProductQuantity($value);			
		}
		
		//edited by Mike, 20170915
		$orderTotalDiscount=0;
		if ($totalQuantity>1) {
			$orderTotalDiscount = ($totalQuantity-1)*70;
		}		
		
		//added by Mike, 20170912
		$this->load->model('Account_Model');
		$data['customer_information'] = $this->Account_Model->getCustomerInformation($customer_id);
		
		if (( strpos( $data['customer_information']->customer_shipping_address, '2 E. Rodriguez Ave. Sto. Niño') !== false ) || 
			( strpos( $data['customer_information']->customer_shipping_address, '2 E. Rodriguez Ave. Sto. Nino') !== false )) {					

			$orderTotalDiscount+=70;
			
		}
		
		date_default_timezone_set('Asia/Hong_Kong');
		$dateTimeStamp = date('Y/m/d H:i:s');				
		
		return array(
						'customer_id' => $customer_id,
						'quantity' => $totalQuantity,
						'status_accepted' => 1,
						'order_total_price' => $orderTotalPrice,
						'order_total_discount' => $orderTotalDiscount,
						'added_datetime_stamp' => $dateTimeStamp,
						'customer_shipping_address' => $data['customer_information']->customer_shipping_address,
						'customer_city' => $data['customer_information']->customer_city,
						'customer_country' => $data['customer_information']->customer_country,
						'customer_postal_code' => $data['customer_information']->customer_postal_code
		);		

/*		
		$data = array_merge($data, array(
				'customer_id' => $customer_id,
				'quantity' => $totalQuantity,
				'status_accepted' => 1,
				'order_total_price' => $orderTotalPrice
		));
*/
/*		
		$data = array(
					'customer_id' => $customer_id,
					'quantity' => $totalQuantity,
					'status_accepted' => 1,
					'order_total_price' => $orderTotalPrice
				);
		
		return $data;
*/		
	}
	
	//---------------------------------------------------------
	// Cart Checkout Confirm
	//---------------------------------------------------------	
	public function confirm() {
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
			//added by Mike, 20170806
			if (!isset($_POST[$field])) {
				redirect(site_url(''));	//redirect to home page
			}

			$data[$field] = $_POST[$field];
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('data', $data);

			$this->checkout();
			//redirect('cart/checkout');
		}
		else
		{
			//added by Mike, 20170713
			$this->session->set_flashdata('data', $data);
			
			$this->load->model('Account_Model');
			$this->Account_Model->updateAccount($customer_id, $data);
			
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
*/
			$this->load->model('Cart_Model');
			$data['result'] = $this->Cart_Model->getCart($customer_id);
			$data['result'] = $this->mergeOutput($data['result']);

//			$data = $this->processCustomerOrder($customer_id);
//			$data = $this->processCustomerOrder($data['result'], $customer_id);
			$data = $this->processCustomerOrder($data['result'], $customer_id);
			
			$this->load->model('Cart_Model');			
			$this->Cart_Model->checkoutCustomerOrder($data);

//			echo "hey ".$data['added_datetime_stamp'];
			
			$data['order_number'] = strtotime($data['added_datetime_stamp']);	
			$date = date_create($data['added_datetime_stamp']);
			$data['date'] = date_format($date, 'F j, Y');
			
			$this::initStyle();
			$this::initHeader();			
			//--------------------------------------------
			
			$this->load->view('thankyou',$data);
			
			//--------------------------------------------
			$this->load->view('templates/footer');

//			echo "OK! Success!";
			//send the data to DB
		}
	}
}
