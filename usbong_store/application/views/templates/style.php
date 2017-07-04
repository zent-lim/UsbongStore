<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script>
		//Reference: https://www.w3schools.com/howto/howto_js_dropdown.asp;
		//last accessed: 20170622
		/* When the user clicks on the button, 
		toggle between hiding and showing the dropdown content */
		function myFunction(id) {
		    document.getElementById("myDropdown"+id).classList.toggle("show");
		}

//		window.onclick = function(event) {
		$(document).click(function(){
// 			$("#dropbtn").hide();
//			if (!event.target.matches('.dropbtn')) {				
//			if (event.target.matches('.window')) {
/*
				if (!event) { 
					var event = window.event; 
				}
*/	
/*
				var S = event.srcElement ? event.srcElement : event.target;
				if(($(S).attr('id')!='myDropDown')||$(S).hasClass('option')==false)
				{ 
					alert("hello");
					$('.dropdown-content').hide();
				}
*/
//				$("div").is(".dropdown-content").hide();
				$(".dropdown-content").hide();
//			}
		});
	</script>

	<script>
		//added by Mike, 20170626
		function myPopupFunction() {				
			var product_id = document.getElementById("product_idParam").value;
			var customer_id = document.getElementById("customer_idParam").value;
			var quantity = document.getElementById("quantityParam").value;
			var price = document.getElementById("priceParam").value;
			
			var textCart = document.getElementById("Text-cartId");
			var textCart2Digits = document.getElementById("Text-cart-2digitsId");
			var textCart3Digits = document.getElementById("Text-cart-3digitsId");
	
			var totalItemsInCart = parseInt(document.getElementById("totalItemsInCartId").value);

			//do the following only if quantity is a Number, i.e. not NaN
			if (!isNaN(quantity)) {								
				//added by Mike, 20170701
				var quantityField = document.getElementById("quantityId");

				if (quantity>1) {
					quantityField.innerHTML = "Added <b>" +quantity +"</b> copies of ";
				}
				else {
					quantityField.innerHTML = "Added <b>1</b> copy of ";
				}

				//-----------------------------------------------------------
				
				totalItemsInCart+=parseInt(quantity);

				if (totalItemsInCart>99) {
					totalItemsInCart=99;
				}
	
				document.getElementById("totalItemsInCartId").value = totalItemsInCart;
						
				//added by Mike, 20170627
				if (customer_id=="") {
					window.location.href = "<?php echo site_url('account/login/');?>";
				}
				else {				
		//			var base_url = window.location.origin;
					var site_url = "<?php echo site_url('cart/addToCart/');?>";
					var my_url = site_url.concat(product_id,'/',customer_id,'/',quantity,'/',price);
					
					$.ajax({
				        type:"POST",
				        url:my_url,
		
				        success:function() {			        	
				        	if (totalItemsInCart<10) {
					        	textCart.innerHTML=totalItemsInCart;
								textCart2Digits.innerHTML="";
								textCart3Digits.innerHTML="";
				        	}
							else if (totalItemsInCart<100) {
					        	textCart.innerHTML="";
								textCart2Digits.innerHTML=totalItemsInCart;
								textCart3Digits.innerHTML="";
							}
							else {
					        	textCart.innerHTML="";
								textCart2Digits.innerHTML="";
								textCart3Digits.innerHTML=totalItemsInCart;
							}
							
							document.getElementById("myPopup").classList.toggle("show");			        
				        }
		
				    });
				    event.preventDefault();
				}
			}
		}	

		// Close the dropdown menu if the user clicks outside of it
		window.onclick = function(event) {
		  if (!event.target.matches('.Button-purchase')) {
		
		    var dropdowns = document.getElementsByClassName("popup-content");
		    var i;
		    for (i = 0; i < dropdowns.length; i++) {
		      var openDropdown = dropdowns[i];
		      if (openDropdown.classList.contains('show')) {
		        openDropdown.classList.remove('show');
		      }
		    }
		  }
		}		
	</script>
	
	<script>
		//added by Mike, 20170626
		function myQuantityFunction(quantity, id) {			
			var trimmedId = id.split("~")[0].substring("quantityId".length, id.length);
			var totalItemsInCart = id.split("~")[1];	

//			alert("hello"+totalItemsInCart);			
//			alert("hello"+id.substring("quantityParam".length, id.length));
					
			var subTotalField = document.getElementById("subtotalId"+trimmedId);
			var priceField = document.getElementById("priceId"+trimmedId);

			if (Number.isNaN(quantity)) {
				quantity = 0;
			}
			
			var subTotal = quantity * parseInt(priceField.innerHTML);
			subTotalField.innerHTML = "&#x20B1;" + subTotal;

			//-----------------------------------------------------------------------
			//update Order Total
			//-----------------------------------------------------------------------
			var orderTotalField1 = document.getElementById("orderTotalId1");
			var orderTotalField2 = document.getElementById("orderTotalId2");

			var orderTotal=0;
			
			for (i=0; i<totalItemsInCart; i++) {
				var sField = document.getElementById("subtotalId"+i);
				orderTotal += parseInt(sField.innerHTML.substring(1, sField.innerHTML.length));
			}

			orderTotalField1.innerHTML = orderTotal;
			orderTotalField2.innerHTML = orderTotal;			
		}
	</script>

	<script>
		//added by Mike, 20170626
		function removeProductItemFunction(id) {			
//			alert("hello"+id);

			var productId = id;
			var totalItemsInCart = id.split("~")[1];	

//			alert("hello "+productId);						
//			alert("hello"+totalItemsInCart);			
//			alert("hello"+id.substring("quantityParam".length, id.length));

			var site_url = "<?php echo site_url('cart/shoppingcart/');?>";
			var my_url = site_url.concat(productId);

//			alert("hey! "+my_url);
			
			$.ajax({
		        type:"POST",
		        url:my_url,

		        success:function() {		
					window.location.href = "<?php echo site_url('cart/shoppingcart/');?>";			        	        			        				        
		       	}
		    });
			event.preventDefault();

/*					
			var subTotalField = document.getElementById("subtotalId"+trimmedId);
			var priceField = document.getElementById("priceId"+trimmedId);

			if (Number.isNaN(quantity)) {
				quantity = 0;
			}
			
			var subTotal = quantity * parseInt(priceField.innerHTML);
			subTotalField.innerHTML = "&#x20B1;" + subTotal;

			//-----------------------------------------------------------------------
			//update Order Total
			//-----------------------------------------------------------------------
			var orderTotalField1 = document.getElementById("orderTotalId1");
			var orderTotalField2 = document.getElementById("orderTotalId2");

			var orderTotal=0;
			
			for (i=0; i<totalItemsInCart; i++) {
				var sField = document.getElementById("subtotalId"+i);
				orderTotal += parseInt(sField.innerHTML.substring(1, sField.innerHTML.length));
			}

			orderTotalField1.innerHTML = orderTotal;
			orderTotalField2.innerHTML = orderTotal;			
*/			
		}
	</script>
	
	<title>Usbong Store</title>
	<style type="text/css">

	::selection { background-color: #f07746; color: #fff; }
	::-moz-selection { background-color: #f07746; color: #fff; }

	body {
		background-color:  #f6f6f6;
		margin: 0px 0px 0px 0px;
		max-width: 100%;
		font: 16px/24px normal "Helvetica Neue",Helvetica,Arial,sans-serif;
		color: #808080;
	}
	
	.navbar {
		background-color: #1a0d00;
	}
	
	a {
		color: #dec32e;
	}

	a:hover {
		color: #dec32e;
	}
	
	hr {
		margin-right: 142px;
		border: 1px solid #6d8f48;
	}

	hr.Cart-hr {
		border: 1px solid #6d8f48;
		margin: 10px 10px 10px 10px;
	}

	
	p {
		 padding:0;
	}

	p.footer {		
		text-align: right;
		font-size: 12px;
		border-top: 1px solid #d0d0d0;
		line-height: 32px;
		background: #52493f;
		color:#fff;
		margin: 20px 0 0 0; 
		padding: 0 10px 0 10px;
	}
	
	.Search-container {
		float: left;
		margin-top: 6px;	
		margin-left: 16px;	
	}

	.Search-input {
		float: left;
		font-size: 18px;
		padding: 4px;
		border: #ffffff;		
		border-radius: 3px;
	}
	
	.Button-container {
	}
	
	.Button {		
		padding: 5.5px;
		border-top: 1px solid #d0d0d0;
		border-right: 1px solid #d0d0d0;
		border-bottom: 1px solid #d0d0d0;
		border-left: #ffffff;		
		background:#ffffff;
	}

	.Remove-button {
		background-color: Transparent;
	    border: none;
	}
	
	Remove-button-image-text {
		pointer-events: none;
	}

	.Remove-button:hover {
		border: 1px solid #969696;
		border-radius: 4px;	
	}
	
	.Image-item {
	    max-width: 75%;
    	height: auto;
	}
	
	.Product-item {
		border-radius: 4px;	
		color: #222222;
	}

	.Product-item:hover {
		text-decoration: underline;
		color: #222222;
	}

	.Product-item-titleOnly {
		color: #222222;
		font-weight: bold;
	}

	.Product-item-details {
		color: #4b4b4b;	
		font-weight: normal;
	}

	.Product-item-price {
		color: #b88a1b;
	}

			
	.Product-image {
	    max-width: 75%;
    	height: auto;
	}
		
	.Cart-product-image {
	    max-width: 160%;
    	height: auto;
	}

	.Popup-product-image {
	    max-width: 120%;
    	height: auto;
    	margin-left: 6px;
	}

	.Product-name {
		color: #222222;
		font-size: 30px;
		font-weight: bold;	
	}

	.Cart-product-name {
		color: #222222;
		font-size: 18px;
		font-weight: bold;	
		margin-top: 4px;			
		margin-left: 10px;    			
	}

	.Cart-product-price {
		color: #b88a1b;
		font-size: 20px;
		margin-top: 4px;		
	}

	.Cart-order-price {
		color: #b88a1b;
	}

	.Cart-product-price-each {
		color: #4b4b4b;
		font-size: 20px;
	}

	label.Cart-product-price {
		color: #4b4b4b;
	}

	label.Cart-product-item-subtotal {
		color: #4b4b4b;
	}

	.Cart-product-subtotal {
		color: #b88a1b;
		font-size: 20px;
		margin-top: 4px;		
		margin-right: 30px;
		text-align: right;
	}

	.Cart-order-list {
	}

	.Cart-order-total {
		border: 1px solid;		
		border-radius: 2px;	
		padding: 10px 22px 10px 7px;
		text-align: right;
		color: #77b043;
		font-weight: bold;
	}

	.Cart-order-total-row {
		color: #4b4b4b;
	}

	.Cart-order-total-with-checkout-row {
		border-top: 1px solid;				
		color: #4b4b4b;
		margin-left: 0px;
	}


	.Cart-product-author {
		color: #4b4b4b;
		font-size: 18px;
		margin-left: 6px;
	}

	.Product-author {
		color: #4b4b4b;
		font-size: 18px;
		margin-left: 6px;
	}

	.Cart-product-price {
		color: #b88a1b;
		font-size: 20px;
		margin-top: 4px;		
		margin-left: 6px;
	}

	.Product-price {
		color: #b88a1b;
		font-size: 24px;
		margin-top: 24px;		
		margin-left: 6px;
	}
	
	.Popup-product-details {
		font-size: 15px;
	}

	.Popup-product-currency-symbol {
		color: #b88a1b;
		font-size: 18px;
		margin-left: 6px;
	}

	.Popup-product-price {
		color: #b88a1b;
		font-size: 24px;
		margin-right: 2px;
	}

	.Popup-product-free-delivery {
		color: #b88a1b;
		font-size: 18px;
		margin-top: -30px;
		margin-right: 2px;
	}

	.Product-overview-header {
		color: #8bbf4f;
		margin-top: 12px;
		font-size: 18px;
	}

	.Product-overview-content {
		font-size: 16px;
	}

	.Cart-product-quantity {
		color: #4b4b4b;
		font-size: 16px;
		margin-top: 4px;
	}

	.Product-quantity {
		color: #4b4b4b;
		font-size: 16px;
		margin-top: 24px;
	}

	.Quantity-label {
		padding-right: 8px;
	}

	.Product-purchase-button {
		color: #4b4b4b;
		font-size: 16px;
		margin-top: 12px;
	}

	.Button-purchase {
		padding: 8px 42px 8px 42px;
		background-color: #ffe400;
		color: #222222;
		font-weight: bold;
		border: 0px solid;		
		border-radius: 4px;
		margin-left: 16px;
	}

	.Button-purchase:hover {
		background-color: #d4be00;
	}

	.Button-continue-to-checkout {
		padding: 8px 24px 8px 24px;
		background-color: #ffe400;
		color: #222222;
		font-weight: bold;
		border: 0px solid;		
		border-radius: 4px;
		margin-left: 16px;
		font-size: 14px;
	}

	.Button-continue-to-checkout:hover {
		background-color: #d4be00;
	}

	.Button-view-cart {
		padding: 8px 42px 8px 42px;
		background-color: #84c44b;
		color: #222222;
		font-weight: bold;
		border: 0px solid;		
		border-radius: 4px;
	}

	.Button-view-cart:hover {
		background-color: #77b043;
	}
		
	.col-sm-3 {
		text-align:center
	}

	.col-sm-2 {
		text-align:center
	}
	
	.container {
		margin-left: 142px;
		margin-bottom: 48px;
	}	
	
	
	.Cart-container {
		margin-right: 24px;
		margin-left: 24px;
	}	
	
	.Topbar-container {
		width: 100%;
		overflow: hidden;
		float: right;
	}
	
	.Login-container {
		float: left;
		margin-top: 16px;	
		margin-left: 36px;		
	}
	
	.Button-cart {
		background: #0000000;
		border: 0px solid;		
		padding: 0px;
		margin-top: 4px;
		margin-left: 6px;
	}	

	.Text-cart {
		pointer-events: none;
		position: absolute;
		color: white;
		padding-left: 15px;		
	    padding-top: 14px;
		font-size: 14px;
	}	

	.Text-cart-2digits {
		pointer-events: none;
		position: absolute;
		color: white;
		padding-left: 10px;		
	    padding-top: 14px;
		font-size: 14px;
	}	

	.Text-cart-3digits {
		pointer-events: none;
		position: absolute;
		color: white;
		padding-left: 8px;		
	    padding-top: 14px;
		font-size: 14px;
	}	
	
	.Customer-dropdown {
		margin-top: 16px;
	}
	
	.dropdown {
	    position: relative;
    	display: inline-block;
	}
	
	/* Dropdown Content (Hidden by Default) */
	.dropdown-menu {
	    display: none;
	    position: absolute;
	    background-color: #f9f9f9;
	    min-width: 160px;
	    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	    z-index: 1;
	}
	
	/* Show the dropdown menu on hover */
	.dropdown:hover .dropdown-menu {
	    display: block;
	    margin-right:-80px;
	}
			
	.dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {
         background-color:#f1f1f1;
         font-weight: bold;
 	}
 	
	.nav {
	}
	
	.header {
		margin: 0px 10px 0px 10px;	

	}

	.Search-result {
		margin-left: 100px;
		margin-bottom: 32px;	
		font-size: 18px;
		color: #1a0d00;
	}
	
	.Search-noResult {
		margin-left: 100px;
		margin-bottom: 32px;	
		font-size: 18px;
		color: #1a0d00;
	}

	.Cart-noResult {
		margin-top: 56px;
		margin-left: 560px;
		margin-bottom: 56px;	
		font-size: 18px;
		color: #1a0d00;
	}

	.usbongLogo {
		margin-top: 3px;
	}
	
	.login-and-create {
		max-width: 30%;		
		float: none;
    	display: block;
    	margin: 0 auto;
    	font-size: 24px;    	
    	padding: 16px;
    	border: 1px solid #4b3b2c;
    	border-radius: 4px;
	}
	
	.login-text {
		border-right: 1px solid;	
		display: inline-block; 
		padding-right: 12px;	
	}
	
	.register-text {
    	font-size: 16px;    		
		display: inline-block; 
		padding-left: 12px;		
		padding-bottom: 12px;	
	}

	.login-text-in-create {
    	font-size: 16px;    		
		display: inline-block; 
		padding-left: 12px;		
		padding-bottom: 12px;	
	}
	
	.register-text-in-create {
		border-right: 1px solid;	
		display: inline-block; 
		padding-right: 12px;	
	}

	.Login-input {
	    font-size: 16px;    			
	    padding: 6px;
	    width: 100%;
	    margin-bottom: 12px;		    
	}
	
	.Button-login {
	    font-size: 20px;    			
	    display: inline-block; 	    
		background-color: #ffe400;
		padding: 8px 30px 8px 30px;
		background-color: #ffe400;
		color: #222222;
		border: 0px solid;		
		border-radius: 4px;
	}
	
	.Button-login:hover {
		background-color: #d4be00;
	}

	.forgotPassword-text {
	    font-size: 14px;    			
	    padding: 10px;		
	    display: inline-block; 	    
	}	
	
	.Register-input {
	    font-size: 16px;    			
	    padding: 6px;
	    width: 100%;
	    margin-bottom: 12px;		    
	}
	
	.Register-error {		
		font-size: 16px;
		color: #c14646;		
		background-color: #f6aaaa;
		border-top: 1px solid #c98989;	
		border-right: 1px solid #c98989;	
		border-left: 1px solid #c98989;		

		border-bottom: 1px solid #f6aaaa;					
		margin-bottom: -1px;
	}		
	
	.Quantity-textbox { 
		background-color: #fCfCfC;
	    color: #68502b;
	    padding: 12px;
	    font-size: 16px;
	    border: 1px solid #68502b;
	    width: 20%;
	    border-radius: 3px;	    	    
	}
	
	.no-spin::-webkit-inner-spin-button, .no-spin::-webkit-outer-spin-button {
	    -webkit-appearance: none !important;
	    margin: 0px !important;
	    -moz-appearance:textfield !important;
	}
	
	/* 
	 * ------------------------------------------------------------------
	 * DROPDOWN
	 * Reference: https://www.w3schools.com/howto/howto_js_dropdown.asp;
	 * last accessed: 20170622	 
	 * ------------------------------------------------------------------
	 */

	/* Dropdown Button */
	.dropbtn {
	    background-color: #68502b;
	    color: white;
	    padding: 16px;
	    font-size: 16px;
	    border: none;
	    cursor: pointer;
	}
	
	/* Dropdown button on hover & focus */
	.dropbtn:hover, .dropbtn:focus {
	    background-color: #9f7b42;
	}
	
	/* The container <div> - needed to position the dropdown content */
	.dropdown {
	    position: relative;
	    display: inline-block;
	}
	
	/* Dropdown Content (Hidden by Default) */
	.dropdown-content {
	    display: none;
	    position: absolute;
	    background-color: #f9f9f9;
	    min-width: 40px;
	    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	    z-index: 1;
	}
	
	/* Links inside the dropdown */
	.dropdown-content a {
	    color: black;
	    padding: 12px 16px;
	    text-decoration: none;
	    display: block;
	}
	
	/* Change color of dropdown links on hover */
	.dropdown-content a:hover {background-color: #f1f1f1}
	
	/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
	.show {display:block;}
	
	/* added by Mike, 20170626 */		
	/* Popup container */
	.popup {
	    position: relative;
	    display: inline-block;
	}
	
	/* The actual popup (appears on top) */
	.popup-content {
	    display: none;
    	position: fixed;
    	top: 50px;
    	right: 10px;	    
    	width: 300px;
    	background-color: #f9f9f9;
	    padding: 16px;
	    min-width: 40px;
	    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	    z-index: 1;
	    border-radius: 3px;	    
	}

</style>
</head>
<body>
</body>
</html>
