<?php
	require '../connect.php';
	$sql = "SELECT title FROM movies WHERE id='".$_GET['id']."'";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query) > 0){
		$message = '';
		$title = strtolower(mysql_result($query,0));
		for($i=0;$i<strlen($title);$i++){
			if($title[$i] == strtolower($_GET['key']))
				$message = $message.$i.',';
		}
		if($message == "")
			$message = 'no';
		else
			$message = trim($message,',');
		echo $message;
	}
?>