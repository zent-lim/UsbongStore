<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!-- 
<html lang="en">
<head>
</head>
<body>
-->
	<h2 class="header">Account Settings</h2>
	<br>
	<div>
		<div class="row">
			<?php 
			if ($customer_information_result->is_admin==1) {
			?>
				<div class="col-sm-3 Account-settings">
					<div class="row Account-settings-subject-header">Summary</div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/ordersummaryadmin/')?>">Order Summary (Admin)</a></div>				
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/carthistoryadmin/')?>">Cart History (Admin)</a></div>				
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/requestsummaryadmin/')?>">Requests (Admin)</a></div>				
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/sellsummaryadmin/')?>">Sell (Admin)</a></div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/searchhistoryadmin/')?>">Search (Admin)</a></div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/customersummaryadmin/')?>">Customer List (Admin)</a></div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('contact/contactcasesummaryadmin/')?>">Case Summary (Admin)</a></div>					
					<div class="row Account-settings-subject-header">Settings</div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/settings/')?>">Update Information</a></div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/updatepassword/')?>">Update Password</a></div>
				</div>			
			<?php					
				}
				else {
			?>					
				<div class="col-sm-3 Account-settings">
						<div class="row Account-settings-subject-header">Orders</div>
						<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/ordersummary/')?>">Order Summary</a></div>				
						<div class="row Account-settings-subject-header">Settings</div>
						<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/settings/')?>">Update Information</a></div>
						<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/updatepassword/')?>">Update Password</a></div>
				</div>			
			<?php								
				}			
			?>
		
			<div class="col-sm-9">	
			<div class="Customer-information">
				<div class="Customer-information-text-in-checkout"><b>Customer Information</b></div>
				<?php
					$validation_errors="";
					if ($this->session->flashdata('errors')) {
						$validation_errors = $this->session->flashdata('errors');
					}	
					
					$data=[];
					if ($this->session->flashdata('data')) {
						$data = $this->session->flashdata('data');
					}
			    ?>
				<div class="fields">
						<form method="post" action="<?php echo site_url('account/save')?>">
								<?php 
								//Email Address--------------------------------------------------					
								//Error Message
								if (strpos($validation_errors, "The Email Address field must contain a valid email address.") !== false) {
									echo '<div class="Register-error">Email Address is not a valid email.</div>';
								}		
								echo '<div class="Checkout-div">';
								if (isset($data['emailAddressParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" name="emailAddressParam" value="'.$data['emailAddressParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_email_address)) {
									echo '<input type="text" class="Checkout-input" placeholder="" name="emailAddressParam" value="'.$customer_information_result->customer_email_address.'" required>';
								}
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" name="emailAddressParam" required>';
								}
								echo '<span class="floating-label">Email Address</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								//First Name--------------------------------------------------
								echo '<div class="Checkout-div">';					
								if (isset($data['firstNameParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" name="firstNameParam" value="'.$data['firstNameParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_first_name)) {
									echo '<input type="text" class="Checkout-input" placeholder="" name="firstNameParam" value="'.$customer_information_result->customer_first_name.'" required>';
								}						
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" name="firstNameParam" required>';
								}
								echo '<span class="floating-label">First Name</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								//Last Name--------------------------------------------------
								echo '<div class="Checkout-div">';					
								if (isset($data['lastNameParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="Name" name="lastNameParam" value="'.$data['lastNameParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_last_name)) {
									echo '<input type="text" class="Checkout-input" placeholder="Name" name="lastNameParam" value="'.$customer_information_result->customer_last_name.'" required>';
								}						
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="Name" name="lastNameParam" required>';
								}
								echo '<span class="floating-label">Last Name</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
		
								//Contact Number--------------------------------------------------
								echo '<div class="Checkout-div">';						
								//Error Message
								if (strpos($validation_errors, "The Contact Number field must contain only numbers.") !== false) {
									echo '<div class="Register-error">Contact Number must contain only numbers.</div>';
								}
								if (isset($data['contactNumberParam'])) {
									echo '<input type="tel" class="Checkout-input" placeholder="" name="contactNumberParam" value="'.$data['contactNumberParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_contact_number)) {
									echo '<input type="text" class="Checkout-input" placeholder="" name="contactNumberParam" value="'.$customer_information_result->customer_contact_number.'" required>';
								}
								else { //default
									echo '<input type="tel" class="Checkout-input" placeholder="" name="contactNumberParam" required>';
								}
								echo '<span class="floating-label">Contact Number</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								//Meetup at MOSC--------------------------------------------------
								//added by Mike, 20180321
								if ((!isset($data['shippingAddressParam'])) && (!isset($customer_information_result->customer_shipping_address))) {
									echo '<label class="Checkbox-label-shippingToMOSC"><input type="checkbox" id="shippingToMOSCId" value="0" onClick="clickShipToMOSCFunction(this.value)">&ensp;Meetup at Marikina Orthopedic Specialty Clinic</label>';
								}
								else {
									if (isset($data['shippingAddressParam']) && ($data['shippingAddressParam']=="2 E. Rodriguez Ave. Sto. Niño")) {
										echo '<label class="Checkbox-label-shippingToMOSC"><input type="checkbox" id="shippingToMOSCId" value="1" onClick="clickShipToMOSCFunction(this.value)" checked>&ensp;Meetup at Marikina Orthopedic Specialty Clinic</label>';
									}
									else if (isset($customer_information_result->customer_shipping_address) && ($customer_information_result->customer_shipping_address=="2 E. Rodriguez Ave. Sto. Niño")) {
										echo '<label class="Checkbox-label-shippingToMOSC"><input type="checkbox" id="shippingToMOSCId" value="1" onClick="clickShipToMOSCFunction(this.value)" checked>&ensp;Meetup at Marikina Orthopedic Specialty Clinic</label>';
									}
									else {
										echo '<label class="Checkbox-label-shippingToMOSC"><input type="checkbox" id="shippingToMOSCId" value="0" onClick="clickShipToMOSCFunction(this.value)">&ensp;Meetup at Marikina Orthopedic Specialty Clinic</label>';
									}
								}
								
								echo '<div class="Checkout-div">';
								if (isset($data['shippingAddressParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="shippingAddressId" name="shippingAddressParam" value="'.$data['shippingAddressParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_shipping_address)) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="shippingAddressId" name="shippingAddressParam" value="'.$customer_information_result->customer_shipping_address.'" required>';
								}
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" id="shippingAddressId" name="shippingAddressParam" value="" required>';
								}
								echo '<span class="floating-label">Shipping Address</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								//City--------------------------------------------------
								echo '<div class="Checkout-div">';
								if (isset($data['cityParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="cityId" name="cityParam" value="'.$data['cityParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_city)) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="cityId" name="cityParam" value="'.$customer_information_result->customer_city.'" required>';
								}
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" id="cityId" name="cityParam" required>';
								}
								echo '<span class="floating-label">City</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								
								//Country--------------------------------------------------
								echo '<div class="Checkout-div">';
								if (isset($data['countryParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="countryId" name="countryParam" value="'.$data['countryParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_country)) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="countryId" name="countryParam" value="'.$customer_information_result->customer_country.'" required>';
								}
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" id="countryId" name="countryParam" required>';
								}
								echo '<span class="floating-label">Country</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								//Postal Code--------------------------------------------------
								echo '<div class="Checkout-div">';
								if (strpos($validation_errors, "The Postal Code field must contain only numbers.") !== false) {
									echo '<div class="Register-error">Postal Code must contain only numbers.</div>';
								}
								//Postal Code--------------------------------------------------
								if (isset($data['postalCodeParam'])) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="postalCodeId" name="postalCodeParam" value="'.$data['postalCodeParam'].'" required>';
								}
								else if (isset($customer_information_result->customer_postal_code)) {
									echo '<input type="text" class="Checkout-input" placeholder="" id="postalCodeId" name="postalCodeParam" value="'.$customer_information_result->customer_postal_code.'" required>';
								}
								else { //default
									echo '<input type="text" class="Checkout-input" placeholder="" id="postalCodeId" name="postalCodeParam" required>';
								}
								echo '<span class="floating-label">Postal Code</span>';
								echo '</div>';
								//-----------------------------------------------------------
								
								echo '<label class="Checkout-input-mode-of-payment">-Mode of Payment-</label>';
								
								if (isset($data['modeOfPaymentParam'])) {
									if ($data['modeOfPaymentParam']==0) { //bank deposit
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0" checked>Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1">Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2">Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
									else if ($data['modeOfPaymentParam']==1) { //paypal
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0">Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1" checked>Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2">Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
									else {
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0">Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1">Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2" checked>Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
								}
								else if (isset($customer_information_result->mode_of_payment_id)) {
									if ($customer_information_result->mode_of_payment_id==0) {
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0" checked>Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1">Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2">Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
									else if ($customer_information_result->mode_of_payment_id==1) {
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0">Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1" checked>Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2">Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
									else {
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0">Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1">Paypal</label>';
										echo '</div>';
										echo '<div class="radio Checkout-input-mode-of-payment">';
										echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2" checked>Cash upon Meetup at MOSC</label>';
										echo '</div>';
									}
								}
								else {
									echo '<div class="radio Checkout-input-mode-of-payment">';
									echo '<label><input type="radio" id="modeOfPaymentBankDepositId" name="modeOfPaymentParam" value="0" checked>Bank Deposit (<a href="https://www.bdo.com.ph/send-money" target="_blank"><b>BDO</b></a>/<a href="https://www.bpiexpressonline.com/p/0/6/online-banking" target="_blank"><b>BPI</b></a>)</label>';
									echo '</div>';
									echo '<div class="radio Checkout-input-mode-of-payment">';
									echo '<label><input type="radio" id="modeOfPaymentPaypalId" name="modeOfPaymentParam" value="1">Paypal</label>';
									echo '</div>';
									echo '<div class="radio Checkout-input-mode-of-payment">';
									echo '<label><input type="radio" id="modeOfPaymentMeetupAtMOSCId" name="modeOfPaymentParam" value="2">Cash upon Meetup at MOSC</label>';
									echo '</div>';
								}
								
								echo '<br>';
								
								//reset the session values to null
								$this->session->set_flashdata('errors', null);
								$this->session->set_flashdata('data', null); //added by Mike, 20170619
							?>
							<br>
							<button type="submit" class="Button-login">
			<!-- <img src="<?php echo base_url('assets/images/cart_icon.png'); ?>">	
			 -->					
			 				Save
							</button>
						</form>
					</div>	
				</div>
			</div>
		</div>
	</div>
<!-- 
</body>
</html>
-->