<?php 
	// import paytabs class 

	require 'PayTabs.php';

	//make sure to set the right value in paytabs_config.php file
	//create new paytabs object 
	$paytabs= new PayTabs();
	/*
		-title: payment title
		-ref_number: number from your system to track the order
		-currency: 3 character for currency 
		-customer_ip: customer ip address
		-page_language: the language of the payment page
	
	*/
	$paytabs->set_page_setting('title','ref number','BHD','127.0.0.1','English');

	/*
		-customer first name
		-customer last name
		-customer international phone number
		-customer phone number
		-customer email

	*/
	$paytabs->set_customer('Muhsan','Taher','00973','12345678','customer@email.com');
	/*
		-Item name
		-item price in the same currency set in paytabs_config.php file
		-item quantity 
	*/
	$paytabs->add_item('New item','10','2');
	$paytabs->add_item('New item2','20','2');

	/*
		add extra charges
	*/

	$paytabs->set_other_charges(3);

	/*
		add discount 
	*/
	$paytabs->set_discount(1);

	/*
		-customer address
		-customer state, required for USA and Canada
		-customer city 
		-customer postal code
		-customer country 
	*/
	$paytabs->set_address("Flat 3021 Manama Bahrain","Manama","Manama","12345","BHR");
	

	/*

		note: only set shipping address if it is different than billing address.
		-customer address
		-customer state, required for USA and Canada
		-customer city 
		-customer postal code
		-customer country 
	*/
	$paytabs->set_shipping_address("Flat 01 Manama Bahrain","Manama","ABC","4321","BHR");

	/*
		return value:
			-result
			-response_code
			-payment_url
			-p_id
	*/
	print_r($paytabs->create_pay_page());
?>
