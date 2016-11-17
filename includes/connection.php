<?php

	require("constants.php");

	//1.Create Database Connection
	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
	if(!$connection){
		die("Database connection failed");
	}
	//2.Select the Database
	$db_select=mysqli_select_db($connection,DB_NAME);
	if(!$db_select){
		die("Database selection failed");
	}
?>