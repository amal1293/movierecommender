<?php
	$newarray = array('Amal'=>12,'Qwerty'=>9);
	asort($newarray);
	print_r($newarray);
	foreach($newarray as $num=>$value){
		echo $num.' ';
	}
?>