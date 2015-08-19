<?php 
	// import paytabs class 
	require('PayTabs.php');

	//make sure to set the right value in paytabs_config.php file
	//create new paytabs object 
	$paytabs= new PayTabs();
	print($paytabs->validate());

?>