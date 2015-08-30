###PHP API Documentation 

First download the API and extra the zip file, you will get the following files:
- [Curl.php](https://github.com/php-curl-class/php-curl-class): class to make connection with PayTabs server.
- PayTabs.php: the class main API class.
- paytabs_config.php: this will contain all the API configuration.
- ex_1_verify_info.php: example for verifying the email and secret key.
- ex_2_create_pay_page.php:An example of how to create payment page.

#####Setup
Start with paytabs_config.php where you should set API options.

```php
return array(
    'merchant_email' => 'YOUR PAYTABS EMAIL',
	'secret_key' => 'YOUR SECRET KEY',
	'site_url' => "YOUR WEBSITE URL",
	'return_url' => "COMPLETE PAYMENT URL",
	'ip_merchant' => "WEBSITE IP ADDRESS",
	'cms_with_version' => "VT_PayTabs 0.1.0"
);

```

- merchant_email: The email you use in PayTabs.
- secret_key: The secret key from PayTabs E-commerce plugins & API tab.
- site_url: Your website URL, this link most be same as the one in your PayTabs profile.
- return_url: The page where the user will return after completing his/her translation.
- ip_merchant: Your server IP address, you can get it using:
```php
	$_SERVER['SERVER_ADDR'];
```

#####Verify setting

After setting the value in paytabs_config.php file, you can try the first example to check if the email and the secret key are valid, also to check if you can make connection with PayTabs server.

```php
	// import paytabs class 
	require('PayTabs.php');

	//make sure to set the right value in paytabs_config.php file
	//create new paytabs object 
	$paytabs= new PayTabs();
	print($paytabs->validate());
```
First, you have to include PayTabs class, then create a new PHP object of the class, while creating new object, the class constructor will initialize the class properties with the setting from paytabs_config.php file.
Validate function will take the email and the secret key from the configuration file and try to test the data from PayTabs server, if the return value is "Valid" everything should be OK otherwise you need to check the email and the secret key in paytabs_config.php file.


#####Create pay page
```php

	require 'PayTabs.php';

	//make sure to set the right values in paytabs_config.php file
	//create new paytabs object 
	$paytabs= new PayTabs();
	/*
		-title: payment title
		-ref_number: number from your system to track the order
		-currency: 3 characters for currency 
		-customer_ip: customer IP address
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
		set extra charges
	*/

	$paytabs->set_other_charges(3);

	/*
		set discount 
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
```
To create the payment page you have to set the order details.
```php
$paytabs->set_page_setting('title','ref number','BHD','127.0.0.1','English');
```
This will set the page setting, the parameters are:
- Payment title 
- Reference number from your system to track the order
- The currency
- Customer IP address 
- The payment page language

```php
$paytabs->set_customer('Muhsan','Taher','00973','12345678','customer@email.com');
```
set_customer function will set the customer information as the following:
- First name
- Last name
- International phone codes for the customer e.g. 00973
- Customer phone number 
- Customer email

```php
$paytabs->add_item('New item','10','2');
```
add_item function will add the item to the order, this function can be called multiple time to add a new item, the parameters are:
- Item name
- Item Price (Number)
- Item quantity (Number)

```php
$paytabs->set_other_charges(3);
```
set_other_charges will set extra charges to the order.

```php
$paytabs->set_discount(1);
```
set_discount will set discount to the order, the value of the discount is a positive number.

```php 
$paytabs->set_address("Flat 3021 Manama Bahrain","Manama","Manama","12345","BHR");
```
set_address function will set the customer address, the address will be verified with the card number for fraud detection, the parameters are:
- Customer address
- Customer state, required for USA and Canada
>When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. For other countries, the state can be a string of up to 32 characters.

- Customer city
- Customer postal code
- Customer country 

```php
$paytabs->set_shipping_address("Flat 01 Manama Bahrain","Manama","ABC","4321","BHR");
```
set_shipping_address is an optional function in case if the customer address is different than the shipping address, it takes the same parameters like set_address function.

```php
$paytabs->create_pay_page();
```
Finally, calling create_pay_page will return an array with the following values:
- result: request result
- response_code: response code
- payment_url: payment page URL
- p_id: payment reference from PayTabs system
>When you create a PayPage, you will receive p_id in the response. When the customer completes a payment and is referred back to your website, there is a payment_reference that is sent with a POST method. The payment_reference is used to verify the status of the payment whether it is a successful transaction or a failed transaction. Additionally, you can compare the payment_reference and the p_id, in order to match the payment with its respective PayPage.

#####Verify Payment
```php
$paytabs->verify_payment($payment_reference);
```
verify_payment function will return the result of specific payment, it sent the p_id from create_pay_page and return the following:
- result : transaction response
- response_code
- pt_invoice_id
- amount
- currency
- transaction_id


```php
$paytabs->get_transactions_reports("14-08-2015","22-08-2015");
```
>This API call will post all transactions that have taken place within the specified time interval to a URL. Before calling this API, you will need to set the listener URL for the reports. Login to your merchant dashboard and edit your profile. In the ‘IPN Listener for Transactions Reports Enter the URL where you would like PayTabs to post your transactions and click on Save. Once you call the transaction_reports API, all the transactions will be posted to the listener URL and the response will be contain the number of transactions that will be posted.

The return of this call will be:
- transaction_result: number of transaction
- response_code
Note that the date should be in the following format: dd-mm-YYYY

Other improvements to the class will be added soon, to stay updated with the project you can watch it on [GitHub](https://github.com/plusmnt/PayTabs).
You can find more information about the size of each variable and dummy credit card number in the [original documentation](https://www.paytabs.com/PayTabs-API-Documentation-V-2.1.pdf).
