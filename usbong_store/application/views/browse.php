<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!-- 
<html lang="en">
<head>
</head>
<body>
-->
	<h2 class="header">Search</h2>
	<br>
	<?php 
		//added by Mike, 20180117
		$URLFriendlyReformattedProductName = str_replace("(","",
			str_replace(")","",
			str_replace("&","and",
			str_replace(',','',
			str_replace(' ','-',
			str_replace('/','-',
			$param)))))); //replace "&", " ", and "-"
	
	
	
		$resultCount = count($result);
		if ($resultCount==0) {
			echo '<div class="Search-noResult">';
			echo 'Your search <b>- '.$param.' -</b> did not match any of our products.';
			echo '<br><br>Suggestion:';
			echo '<br>&#x25CF; Make sure that all words are spelled correctly.';				
			echo '<br>&#x25CF; You may send us a request for the product item <a class="Request-link" href="'.site_url('request/'.$URLFriendlyReformattedProductName.'/b').'">here</a>.';		
			echo '</div>';
		}
		else {
			if ($resultCount==1) {
				echo '<div class="Search-result"><b>'.count($result).'</b> result found.</div>';
			}
			else {
				echo '<div class="Search-result"><b>'.count($result).'</b> results found.</div>';			
			}
	?>
		<div class="container-search">
			<?php
				$colCounter = 0;
				$itemCounter = 0;
				
				foreach ($result as $value) {
/*					
					$reformattedProductName = str_replace(':','',str_replace('\'','',$value['name'])); //remove ":" and "'"
					$URLFriendlyReformattedProductName = str_replace(',','',str_replace(' ','-',$reformattedProductName)); //replace " " and "-"
					$URLFriendlyReformattedProductAuthor = str_replace(',','',str_replace(' ','-',$value['author'])); //replace " " and "-"
*/
					$reformattedProductName = str_replace(':','',str_replace('\'','',$value['name'])); //remove ":" and "'"
					$URLFriendlyReformattedProductName = str_replace("(","",
														str_replace(")","",
															str_replace("&","and",
															str_replace(',','',
																str_replace(' ','-',
																str_replace('/','-',															
																$reformattedProductName)))))); //replace "&", " ", and "-"
					$URLFriendlyReformattedProductAuthor = str_replace("(","",
														str_replace(")","",
															str_replace("&","and",
																str_replace(',','',
																str_replace(' ','-',
																str_replace('/','-',
																	$value['author'])))))); //replace "&", " ", and "-"
					
					$productType="books"; //default
					switch($value['product_type_id']) {
						case 2:
							$productType = "books";
							break;
						case 10:
							$productType = "childrens";
							break;
						case 9:
							$productType = "textbooks";
							break;
						case 5:
							$productType = "promos";
							break;
						case 11:
							$productType = "food";
							break;
						case 3:
							$productType = "beverages";
							break;
						case 6:
							$productType = "comics";
							break;
						case 7:
							$productType = "manga";
							break;
						case 8:
							$productType = "toys_and_collectibles";
							break;
						case 12:
							$productType = "miscellaneous";
							break;
						case 13:
							$productType = "medical";
							break;						
					}
				?>
					<div class="row">
						<div class="col-sm-3">	
							<img class="Product-image" src="<?php echo base_url('assets/images/'.$productType.'/'.$reformattedProductName.'.jpg');?>">				
							<?php 
								//added by Mike, 20180402
								if (isset($value['is_essential_reading']) && ($value['is_essential_reading'])) {
									echo '<img class="Product-image-essential-reading" src="'.base_url('assets/images/essential_reading.png').'">';
								}
							?>
						</div>
						<div class="col-sm-4">	
							<div class="row Product-name">							
								<?php
									echo '<a class="Product-item" href="'.site_url('w/'.$URLFriendlyReformattedProductName.'-'.$URLFriendlyReformattedProductAuthor.'/'.$value['product_id']).'">';
									echo $value['name'];
									echo '</a>';
								?>
							</div>
							<div class="row Product-author">
								<b>
								<?php
									echo $value['author'];
								?>
								</b>
							</div>				
							<div class="row">	
								<div class="Product-overview-header"><b>Product Overview</b><br></div>
								<div class="Product-overview-content">
								<?php									
//									if (!empty($result->product_overview)) {
//									if (isset($value['product_overview'])) {							
//									if (!empty($value['product_overview'])) {									
									if (!empty($value['product_overview']) && (strcmp($value['product_overview'], "<p>&nbsp;</p>")!=0)) {									
										echo $value['product_overview'];
									}
									else {
										echo '<br><br><i>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;No synopsis available.</i>';
									}
								?>
								</div>
							</div>		
						</div>
						<div class="col-sm-3">	
							<div class="row Product-price">
								<b>
								<?php
								if (trim($value['price'])=='') {
									echo 'out of stock';						
								}
								else {
									echo '<a class="Product-price" href ="'.site_url('help/').'" target="_blank">';
									echo '&#x20B1;'.$value['price'].' [Free Delivery]';
									echo '</a>';
								}
								?>
								</b>					
							</div>					
							<div class="row Product-quantity">
								<?php 
									if ($value['quantity_in_stock']<1) {
								?>
										<label class="Quantity-label">Quantity: <span class="Quantity-out-of-stock">out of stock</span></label>						
								<?php						
									}
									else {
								?>											
								<label class="Quantity-label">Quantity:</label>
								<input type="tel" id="quantityId<?php echo $itemCounter.'~'.$resultCount;?>" class="Quantity-textbox no-spin" 
										name="quantityParam<?php echo $itemCounter;?>"
										value="1" min="1" max="99" onKeyUp="" onKeyPress="if(this.value.length==2) {return false;} if(parseInt(this.value)<1) {this.value='1'; return false;}" required>					    
								
								<input type="hidden" id="productId<?php echo $itemCounter?>" value="<?php echo $value['product_id'];?>">
								<input type="hidden" id="productName<?php echo $itemCounter?>" value="<?php echo $value['name'];?>">
								<input type="hidden" id="productImage<?php echo $itemCounter?>" value="<?php echo base_url('assets/images/'.$productType.'/'.$reformattedProductName.'.jpg');?>">
								<?php 
									}
								?>							
							</div>
							<?php 
								if ($value['quantity_in_stock']>=1) {
							?>														
							<div class="row Product-purchase-button">
								<?php 
//										$quantity = 1;
										//TODO: fix quantity and price
											
// 										echo '<input type="hidden" id="product_idParam" value="'.$value['product_id'].'" required>';
										echo '<input type="hidden" id="customer_idParam" value="'.$this->session->userdata('customer_id').'" required>';										
		// 								echo '<input type="hidden" id="quantityParam" value="'.$quantity.'" required>';
										echo '<input type="hidden" id="priceParam" value="'.$value['price'].'" required>';							
								?>				
								<button id="addToCartId<?php echo $itemCounter.'~'.$resultCount;?>" onclick="myPopupFunctionInSearchPage(this.id)" class="Button-purchase">ADD TO CART</button>
								<div id="myPopup" class="popup-content">
									<div class="row">
										<div class="col-sm-4">									
											<img class="Popup-product-image" id="productImageId" src="">				
										</div>
										<div class="col-sm-8 Popup-product-details">
											<span id="quantityId"></span>
											<?php 
											
		/*									
												$quantity=1;
												if ($quantity>1) {
													echo 'Added<b>'.$quantity.'</b> copies of ';									
												}
												else {
													echo 'Added <b>1</b> copy of ';
												}
		*/										
//												echo '<b>'.$value['name'].'</b>!'
											echo '<b><span id="productNameId"></span></b>!'
											
											?>
											<br><b>Order Total: </b>
											<label class="Popup-product-price">&#x20B1;<?php echo $value['price'];?></label>
											<label class="Popup-product-free-delivery"><br>[Free Delivery]</label> 												
											<form method="post" action="<?php echo site_url('cart/shoppingcart')?>">
												<button type="submit" class="Button-view-cart">
													View Cart 
												</button>
											</form>						
										</div>
									</div>
								</div>					
							</div>		
						<?php 
							}
							else {
							?>
								<br><br>
								<div>
								<!--  $this->uri->segment(2) is a URL friendly product name-->
								<a class="Request-link" href="<?php echo site_url('request/'.$URLFriendlyReformattedProductName.'-'.$URLFriendlyReformattedProductAuthor.'/'.$value['product_id'])?>"><img class="Product-item-page-image-offers-request" src="<?php echo base_url('assets/images/usbongOffersRequest_L.jpg')?>"></a>
								</div>	
							<?php
							}
						?>													
						</div>
					</div>
					<hr class="horizontal-line">
				<?php 
					$itemCounter++;
				  }
				?>					
			</div>		
		<?php 
		}		
		?>
<!-- 
</body>
</html>
-->