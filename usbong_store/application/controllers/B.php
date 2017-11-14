<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class B extends MY_Controller {

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
/*		
		$this->load->library('session');
		$this->load->library('form_validation');
*/		
		$fields = array('emailAddressParam', 'passwordParam');
				
		foreach ($fields as $field)
		{
			if (isset($_POST[$field])) {
				$data[$field] = $_POST[$field];		
			}
		}
		
		if (isset($data)) {
			$this->load->model('Account_Model');
// 			$data['does_email_exist'] = $this->Account_Model->doesEmailAccountExist($data);

			$data['customer_data'] = $this->Account_Model->loginAccount($data);

			if (isset($data['customer_data'])) {
				//added by Mike, 20170626
				$newdata = array(
							'customer_first_name'  => $data['customer_data']->customer_first_name,
							'customer_email_address'     => $data['customer_data']->customer_email_address,
							'logged_in' => TRUE,
							'customer_id' => $data['customer_data']->customer_id
				);
					
				$this->session->set_userdata($newdata);
//				$this->books();
				$this->frontPage();
				
// 					$this->home($data);					
			}
			else {
					/*
					 $this->session->set_flashdata('data', $data);
					 redirect('account/login');
					 */
					
				//added by Mike, 20170622
				$data['does_email_exist'] = $this->Account_Model->doesEmailAccountExist($data);
				if (isset($data['does_email_exist'])) { 
						$this->session->set_flashdata('data', $data);
						redirect('account/login');
				}										
				else {					
					echo "<script>
							    alert('Either the email address or password you entered is incorrect. If you pasted your temporary password from an email, please enter it by typing it in instead.');
								window.location.href='/'; ///usbong_store/
						  </script>";
// 					redirect('/'); //return to homepage					
// 					$this->books();
				}
			}				
		}
		else {
//			$this->books();		
			$this->frontPage();			
		}
	}
	
	//---------------------------------------------------------
	// Front Page
	//---------------------------------------------------------
	public function frontPage()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		//		$data['content'] = 'category/Books';
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Books_Model');
		$data['books'] = $this->Books_Model->getBooksOnly(null);

		$this->load->model('Childrens_Model');
		$data['childrens'] = $this->Childrens_Model->getChildrensOnly(null);
		
		$this->load->model('Textbooks_Model');
		$data['textbooks'] = $this->Textbooks_Model->getTextbooksOnly(null);

		$this->load->model('Promos_Model');
		$data['promos'] = $this->Promos_Model->getPromosOnly(null);

		$this->load->model('Food_Model');
		$data['food'] = $this->Food_Model->getFoodOnly(null);
		
		//recommended shops
		
		$this->load->model('Beverages_Model');
		$data['beverages'] = $this->Beverages_Model->getBeveragesOnly(null);
		
		$this->load->model('Comics_Model');
		$data['comics'] = $this->Comics_Model->getComicsOnly(null);

		$this->load->model('Manga_Model');
		$data['manga'] = $this->Manga_Model->getMangaOnly(null);
		
		$this->load->model('Toys_and_Collectibles_Model');
		$data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_CollectiblesOnly(null);
		
		$this->load->model('Miscellaneous_Model');
		$data['miscellaneous'] = $this->Miscellaneous_Model->getMiscellaneousOnly(null);
		
		//edited by Mike, 20170903
		$this->load->model('B_Model');
		$data['merchants'] = $this->B_Model->getMerchants();
				
		$this->B_Model->incrementViewNum();
				
		$this->load->view('b/frontPage',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	
	
	//---------------------------------------------------------
	// Account Creation Successful
	//---------------------------------------------------------
	public function literature_and_fiction()
	{
		$this->form_validation->set_rules('firstNameParam', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastNameParam', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('emailAddressParam', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('confirmEmailAddressParam', 'Confirm Email Address', 'required|matches[emailAddressParam]');		
		$this->form_validation->set_rules('passwordParam', 'Password', 'required');
		$this->form_validation->set_rules('confirmPasswordParam', 'Password Confirmation', 'required|matches[passwordParam]');		
		
		$fields = array('firstNameParam', 'lastNameParam', 'emailAddressParam', 'confirmEmailAddressParam', 'passwordParam', 'confirmPasswordParam');
		
		foreach ($fields as $field)
		{
			$data[$field] = $_POST[$field];
		}
		
		if ($this->form_validation->run() == FALSE)
		{
//			$this->load->view('myform');
//			$this->load->view('account/create');			
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('data', $data);		
// 			redirect('account/create');
			redirect('account/create');			
		}
		else
		{
			$this->load->model('Account_Model');
			$customer_id = $this->Account_Model->registerAccount($data);
			
			//added by Mike, 20170624
			$newdata = array(
					'customer_first_name'  => $data['firstNameParam'],
					'customer_email_address'     => $data['emailAddressParam'],
					'logged_in' => TRUE,
					'customer_id' => $customer_id
			);			
			$this->session->set_userdata($newdata);
/*		
			$this->load->view('templates/style');
			$this->load->view('templates/header');
*/
			//from application/core/MY_Controller
			$this::initStyle();
			$this::initHeader();
			//--------------------------------------------
			$this->load->view('templates/right_side_bar');
			//--------------------------------------------
										
			$this->load->model('Books_Model');
			$this->load->model('W_Model');

			$data['books'] = $this->Books_Model->getBooks(null);

			//added by Mike, 20171114
			$customer_id = $this->session->userdata('customer_id');		
			$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);		

			$this->load->view('b/books',$data);			

			//--------------------------------------------
			$this->load->view('templates/footer');
		}								
	}
/*
	public function home($data) {
//		$data['customer_first_name'] = $param;
		
		$this->load->view('templates/style');
		$this->load->view('templates/header',$data);
		//--------------------------------------------
		
				
		$this->load->model('Books_Model');
		$data['books'] = $this->Books_Model->getBooks();
		$this->load->view('b/books',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');		
	}
*/	
	//---------------------------------------------------------
	// Books Category
	//---------------------------------------------------------
	public function books()
	{					
		//from application/core/MY_Controller
		$this::initStyle();		
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------

//		$data['content'] = 'category/Books';
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Books_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['books'] = $this->Books_Model->getBooks($merchant_id);
			
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['books'] = $this->Books_Model->getBooks(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');		
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);		
		
/*		
		$this->load->model('Books_Model');
		$data['books'] = $this->Books_Model->getBooks();
//		$this->load->view('templates/general_template',$data);
 */
		$this->load->view('b/books',$data);

		//--------------------------------------------
		$this->load->view('templates/footer');
	}	

	//---------------------------------------------------------
	// Textbooks Category
	//---------------------------------------------------------
	public function textbooks()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Textbooks_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['textbooks'] = $this->Textbooks_Model->getTextbooks($merchant_id);
			
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['textbooks'] = $this->Textbooks_Model->getTextbooks(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
/*		
		$this->load->model('Textbooks_Model');
		$data['books'] = $this->Textbooks_Model->getTextbooks();
*/		
		$this->load->view('b/textbooks',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	//---------------------------------------------------------
	// Children's Books Category
	//---------------------------------------------------------
	public function childrens()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		//		$data['content'] = 'category/Books';
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Childrens_Model');		
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['childrens'] = $this->Childrens_Model->getChildrens($merchant_id);

//			$this->load->model('W_Model');		
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);		
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);			
		}
		else {
			$data['childrens'] = $this->Childrens_Model->getChildrens(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
		//		$this->load->view('templates/general_template',$data);
		$this->load->view('b/childrens',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	
	
	//---------------------------------------------------------
	// PROMOS Category
	//---------------------------------------------------------
	public function promos()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Promos_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['promos'] = $this->Promos_Model->getPromos($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['promos'] = $this->Promos_Model->getPromos(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);		
		
//		$data['content'] = 'category/Combos';
/*		$this->load->model('Promos_Model');
		$data['promos'] = $this->Promos_Model->getPromos();
*/		
		//		$this->load->view('templates/general_template',$data);
		$this->load->view('b/promos',$data);

		//--------------------------------------------
		$this->load->view('templates/footer');		
	}
	
	//---------------------------------------------------------
	// BEVERAGES Category
	//---------------------------------------------------------
	public function beverages()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Beverages_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['beverages'] = $this->Beverages_Model->getBeverages($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['beverages'] = $this->Beverages_Model->getBeverages(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
/*		
		$this->load->model('Beverages_Model');
		$data['beverages'] = $this->Beverages_Model->getBeverages();
		//		$this->load->view('templates/general_template',$data);
*/
		$this->load->view('b/beverages',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	//---------------------------------------------------------
	// COMICS Category
	//---------------------------------------------------------
	public function comics()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Comics_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['comics'] = $this->Comics_Model->getComics($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['comics'] = $this->Comics_Model->getComics(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
/*		
		$this->load->model('Comics_Model');
		$data['comics'] = $this->Comics_Model->getComics();
		//		$this->load->view('templates/general_template',$data);
*/
		$this->load->view('b/comics',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	//---------------------------------------------------------
	// MANGA Category
	//---------------------------------------------------------
	public function manga()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Manga_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['manga'] = $this->Manga_Model->getManga($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['manga'] = $this->Manga_Model->getManga(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
/*		
		$this->load->model('Manga_Model');
		$data['manga'] = $this->Manga_Model->getManga();
		//		$this->load->view('templates/general_template',$data);
*/
		$this->load->view('b/manga',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}
	
	//---------------------------------------------------------
	// TOYS & COLLECTIBLES Category
	//---------------------------------------------------------
	public function toys_and_collectibles()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Toys_and_Collectibles_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_Collectibles($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_Collectibles(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
/*		
		$this->load->model('Toys_and_Collectibles_Model');
		$data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_Collectibles();
		//		$this->load->view('templates/general_template',$data);
*/
		$this->load->view('b/toys_and_collectibles',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	
	
	//---------------------------------------------------------
	// FOOD Category
	//---------------------------------------------------------
	public function food()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
		
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Food_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['food'] = $this->Food_Model->getFood($merchant_id);
			
			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['food'] = $this->Food_Model->getFood(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);		
		
		/*
		 $this->load->model('Toys_and_Collectibles_Model');
		 $data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_Collectibles();
		 //		$this->load->view('templates/general_template',$data);
		 */
		$this->load->view('b/food',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	
	
	//---------------------------------------------------------
	// MISCELLANEOUS Category
	//---------------------------------------------------------
	public function miscellaneous()
	{
		//from application/core/MY_Controller
		$this::initStyle();
		$this::initHeader();		
		//--------------------------------------------
		$this->load->view('templates/right_side_bar');
		//--------------------------------------------
				
		$merchant_id = $this->uri->segment(3);
		
		$this->load->model('Miscellaneous_Model');
		$this->load->model('W_Model');
		
		if (isset($merchant_id)) {
			$data['miscellaneous'] = $this->Miscellaneous_Model->getMiscellaneous($merchant_id);
			
//			$this->load->model('W_Model');
			$data['categories'] = $this->W_Model->getMerchantCategories($merchant_id);
			$data['result'] = $this->W_Model->getMerchantName($merchant_id);
		}
		else {
			$data['miscellaneous'] = $this->Miscellaneous_Model->getMiscellaneous(null);
		}
		
		$customer_id = $this->session->userdata('customer_id');
		$data['merchant_customer_categories'] = $this->W_Model->getMerchantCustomerCategories($customer_id);
		
		/*
		 $this->load->model('Toys_and_Collectibles_Model');
		 $data['toys_and_collectibles'] = $this->Toys_and_Collectibles_Model->getToys_and_Collectibles();
		 //		$this->load->view('templates/general_template',$data);
		 */
		$this->load->view('b/miscellaneous',$data);
		
		//--------------------------------------------
		$this->load->view('templates/footer');
	}	
}
