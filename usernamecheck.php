<?php
	require 'connect.php';
	$query = "SELECT id FROM users WHERE username = '".trim(mysql_real_escape_string($_GET['username']))."'";
	$query_res = mysql_query($query);
	if(mysql_num_rows($query_res) > 0){
		echo "Username already taken";
	}else
		echo "";
?>