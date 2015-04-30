<?php
	//there would be four params in the following serial--- ip address, username, password, name of the database
	$db=new mysqli('127.0.0.1','root','','app');


	// this block was for testing purposes, not erased due to superstision :P :P :P 
	if ($db->connect_errno){
		//echo $db->connect_error;
		//die("we are having problems");
		
	}
?>