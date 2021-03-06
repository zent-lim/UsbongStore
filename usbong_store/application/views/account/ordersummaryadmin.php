<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!-- 
<html lang="en">
<head>
</head>
<body>
-->
	<h2 class="header">Order Summary (Admin)</h2>
	<br>
	<div>
		<div class="row">
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
			<div class="col-sm-9">		
				<?php 
					if (count($order_summary)==0) {
						echo '<div class="Order-Summary-noResult">';
						echo 'No customer has made any orders yet.';
						echo '</div>';
					}
					else {
				?>
					<div class="row">
						<div class="col-sm-1 Order-summary">		
						</div>
						<div class="col-sm-10 Order-summary">		
							<h3>Orders</h3>
							<div>
								<div class="row">
									<div class="col-sm-2 Order-summary">		
										<b>Date</b>
									</div>
									<div class="col-sm-2 Order-summary">		
										<b>Order #</b>
									</div>
									<div class="col-sm-2 Order-summary">		
										<b># of Items</b>
									</div>
									<div class="col-sm-2 Order-summary">		
										<b>Status</b>
									</div>
									<div class="col-sm-2 Order-summary">		
										<b>Sale Price</b>
									</div>
									<div class="col-sm-2 Order-summary">		
										<b>Fulfilled?</b>
									</div>
								</div>
								<?php 
									$counter=0;
									foreach ($order_summary as $value) {
										echo '<div class="row">';
											if ($counter!=0) {
												echo '<div class="col-sm-2 Order-summary-alternate">';
												echo date_format(date_create($value['added_datetime_stamp']),'m/d/Y');
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary-alternate">';
			//									echo strtotime($value['added_datetime_stamp']);
												$date = new DateTime($value['added_datetime_stamp'], new DateTimeZone("Asia/Hong_Kong"));
												$timestamp = $date->format('U');
//												echo $timestamp;
												echo '<b><a class="Order-details-order-number-link" href="'.site_url('account/orderdetailsadmin/'.$timestamp).'/'.$value['customer_id'].'">'.$timestamp.'</a></b>';
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary-alternate">';
												echo $value['quantity'];
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary-alternate">';
												echo "Accepted";
												echo '</div>';
												
												$orderTotal = ($value['order_total_price']-$value['order_total_discount']);
												
												echo '<div class="col-sm-2 Order-summary-alternate offset-col-sm-2">';
												echo '<span class="Order-summary-order-total">&#x20B1;'.$orderTotal.'</span>';
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary-alternate offset-col-sm-2">';
												if ($value['fulfilled_status']==0) {
													echo '<span class="Fulfilled-Status-Not-OK">&ensp;Not Yet&ensp;</span>';
												}
												else if ($value['fulfilled_status']==1) {
													echo '<span class="Fulfilled-Status-OK">&ensp;OK&ensp;</span>';												
												}												
												else {
													echo '<span class="Fulfilled-Status-Cancelled">&ensp;Cancelled&ensp;</span>';
												}
												
												echo '</div>';
												
											}
											else {
												echo '<div class="col-sm-2 Order-summary">';
												echo date_format(date_create($value['added_datetime_stamp']),'m/d/Y');
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary">';
			//									echo strtotime($value['added_datetime_stamp']);
												$date = new DateTime($value['added_datetime_stamp'], new DateTimeZone("Asia/Hong_Kong"));
												$timestamp = $date->format('U');
//												echo $timestamp;
												echo '<b><a class="Order-details-order-number-link" href="'.site_url('account/orderdetailsadmin/'.$timestamp).'/'.$value['customer_id'].'">'.$timestamp.'</a></b>';											
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary">';
												echo $value['quantity'];
												echo '</div>';
												
												echo '<div class="col-sm-2 Order-summary">';
												echo "Accepted";
												echo '</div>';
												
												$orderTotal = ($value['order_total_price']-$value['order_total_discount']);
												
												echo '<div class="col-sm-2 Order-summary">';											
												echo '<span class="Order-summary-order-total">&#x20B1;'.$orderTotal.'</span>';
												echo '</div>';			
												
												echo '<div class="col-sm-2 Order-summary">';
												if ($value['fulfilled_status']==0) {
													echo '<span class="Fulfilled-Status-Not-OK">&ensp;Not Yet&ensp;</span>';
												}
												else if ($value['fulfilled_status']==1) {
													echo '<span class="Fulfilled-Status-OK">&ensp;OK&ensp;</span>';
												}
												else {
													echo '<span class="Fulfilled-Status-Cancelled">&ensp;Cancelled&ensp;</span>';
												}
												echo '</div>';												
											}
											
											$counter=($counter+1)%2;
											
										echo '</div>';		
									}
								?>					
							</div>
						</div>
					</div>
				<?php		
					}
				?>		
			</div>
		</div>
	</div>
	<br>
<!-- 
</body>
</html>
-->