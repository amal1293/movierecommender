<?php
	session_start();
	if(!isset($_SESSION['uid']))
		echo "You are not logged in.<br/>";
	else{
		echo "You have logged out.<br/>";
		session_unset();
		session_destroy();
	}

?>