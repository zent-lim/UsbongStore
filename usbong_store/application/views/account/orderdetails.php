<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!-- 
<html lang="en">
<head>
</head>
<body>
-->
	<h2 class="header">Order #<?php echo $this->uri->segment(3);?></h2>
	<br>
	<div>
		<div class="row">
			<div class="col-sm-3 Account-settings">
					<div class="row Account-settings-subject-header">Orders</div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/ordersummary/')?>">Order Summary</a></div>				
					<div class="row Account-settings-subject-header">Settings</div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/settings/')?>">Update Information</a></div>
					<div class="row Account-settings-subject-content"><a class="Account-settings-subject-content-link" href="<?php echo site_url('account/updatepassword/')?>">Update Password</a></div>
			</div>
			<div class="col-sm-9">			
				<div class="row">
					<div class="col-sm-7 Order-details">				
						<?php 				
						$addedDateTimeStamp = date('F j, Y H:i A', $this->uri->segment(3));
						echo '<div class="Order-details-purchased-datetime-stamp">'.$addedDateTimeStamp.'</div>';
						
							//					echo count($order_details).'items';
								$count=0;
								foreach ($order_details as $value) {
									$count+=$value['quantity'];
								}
								
								if ($count>1) {
									echo $count." items";						
								}
								else {
									echo $count." item";
								}
								
								if (count($order_details)==0) {
									echo '<div class="Order-Details-noResult">';
									echo 'You have not made any orders yet.';
									echo '</div>';
								}
								else {
									foreach ($order_details as $value) {
										$counter = 0;
										while ($counter<$value['quantity']) {
											$reformattedBookName = str_replace(':','',str_replace('\'','',$value['name'])); //remove ":" and "'"
											$URLFriendlyReformattedBookName = str_replace("(","",
											str_replace(")","",
											str_replace("&","and",
											str_replace(',','',
											str_replace(' ','-',
											$reformattedBookName))))); //replace "&", " ", and "-"
											$URLFriendlyReformattedBookAuthor = str_replace("(","",
											str_replace(")","",
											str_replace("&","and",
											str_replace(',','',
											str_replace(' ','-',
											$value['author']))))); //replace "&", " ", and "-"
											
											echo '<div class="row Order-details-product">';
											echo	'<div class="col-sm-8 Order-details">';
											echo 	'<a class="Product-item" href="'.site_url('w/'.$URLFriendlyReformattedBookName.'-'.$URLFriendlyReformattedBookAuthor.'/'.$value['product_id']).'">';
											echo	'<b>'.$value['name'].'</b>';
											echo	'</a>';
											echo 	'</div>';
											echo	'<div class="col-sm-3 Order-details">';
											echo	'<div class="Order-details-align-right Order-details-amount">&#x20B1;'.$value['price'].'</div>';
											echo	'</div>';
											echo '</div>';
											
											$counter++;
										}
									}
									echo '<div class="row">';
									echo	'<div class="col-sm-6 Order-details">';
									echo	'<div class="Order-details-align-right">Order Subtotal</div>';
									echo 	'</div>';
									echo	'<div class="col-sm-5 Order-details">';
									echo	'<div class="Order-details-align-right Order-details-amount">&#x20B1;'.$value['order_total_price'].'</div>';
									echo	'</div>';
									echo '</div>';
									
									//edited by Mike, 20170918
									$totalQuantity = $count-1;
									$totalBuyMoreSaveMoreDiscount = $totalQuantity*70;
									
									echo '<div class="row">';
									echo	'<div class="col-sm-6 Order-details">';
									echo	'<div class="Order-details-align-right">Less &#x20B1;70 promo</div>';
									echo 	'</div>';
									echo	'<div class="col-sm-5 Order-details">';
									echo	'<div class="Order-details-align-right Order-details-amount">-&#x20B1;'.$totalBuyMoreSaveMoreDiscount.'</div>';
									echo	'</div>';
									echo '</div>';

									//added by Mike, 20170918
									$totalMeetupAtMOSCPromoDiscount = $value['order_total_discount'] - $totalBuyMoreSaveMoreDiscount;
									
									echo '<div class="row">';
									echo	'<div class="col-sm-6 Order-details">';
									echo	'<div class="Order-details-align-right">Meetup at MOSC</div>';
									echo 	'</div>';
									echo	'<div class="col-sm-5 Order-details">';
									echo	'<div class="Order-details-align-right Order-details-amount">-&#x20B1;'.$totalMeetupAtMOSCPromoDiscount.'</div>';
									echo	'</div>';
									echo '</div>';																		
									
									echo '<div class="row">';
									echo	'<div class="col-sm-6 Order-details">';
									echo	'<div class="Order-details-align-right">Shipping (PH)</div>';
									echo 	'</div>';
									echo	'<div class="col-sm-5 Order-details">';
									echo	'<div class="Order-details-align-right"><b>FREE</b></div>';
									echo	'</div>';
									echo '</div>';
									
									$orderTotal = ($value['order_total_price']-$value['order_total_discount']);
																		
									echo '<div class="row Order-details-product">';
									echo	'<div class="col-sm-6 Order-details">';
									echo    '<div class="Order-details-align-right">Order Total</div>';
									echo 	'</div>';
									echo	'<div class="col-sm-5 Order-details">';
									echo	'<div class="Order-details-align-right-order-total">&#x20B1;'.$orderTotal.'</div>';
									echo	'</div>';
									echo '</div>';
								}
						?>
					</div>
					<div class="col-sm-4 Order-details">		
						<div class="Order-details-shipping-address">
							<h3><b>Contact Number:</b></h3>
							<?php 
								echo $result->customer_contact_number.'<br>';				
							?>
						</div>
					
						<div class="Order-details-shipping-address">
							<h3><b>Payment Method:</b></h3>
							<?php 
								if ($result->mode_of_payment_id==0) {
									echo 'Bank Deposit<br>';
								}
								else if ($result->mode_of_payment_id==1) {
									echo 'Paypal<br>';
								}
								else {
									echo 'Cash upon Meetup at MOSC<br>(Marikina Orthopedic Specialty Clinic)<br>';
								}
							?>
						</div>
			
						<div class="Order-details-shipping-address">
							<h3><b>Shipped To:</b></h3>
							<?php 
								echo $result->customer_first_name.' '.$result->customer_last_name.'<br>';				
								
								if ($totalMeetupAtMOSCPromoDiscount==0) {
/*									
									echo $result->customer_shipping_address.'<br>';
									echo $result->customer_city.', '.$result->customer_postal_code.',<br>';
									echo $result->customer_country.'<br>';									
*/
									echo $customer_address_at_the_time_of_purchase->customer_shipping_address.'<br>';
									echo $customer_address_at_the_time_of_purchase->customer_city.', '.$customer_address_at_the_time_of_purchase->customer_postal_code.',<br>';
									echo $customer_address_at_the_time_of_purchase->customer_country.'<br>';
								}
								else {
									echo '2 E. Rodriguez Ave. Sto. Niño<br>';
									echo 'Marikina City, 1800,<br>';
									echo 'Philippines<br>';											
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
<!-- 
</body>
</html>
-->