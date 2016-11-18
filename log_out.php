<?php require_once('includes/functions.php') ?>
<?php
	//Four Steps For Closing a session
	
	//1.Find the session
	session_start();

	//2.Unset all the session variables
	$_SESSION=array();

	//3. Destroy the session cookie
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(),'',time()-42000,'/');
	}

	//4.Destroy de session
	session_destroy();

	//Redirect
	redirect('login.php?logout=1');

?>