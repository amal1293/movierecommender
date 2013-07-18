<?php
	session_start();
	header("Content-Type:image/jpeg");

	$captcha_image = imagecreate(250, 60);
	imagecolorallocate($captcha_image, 255, 255, 255);
	$pos_x  = 15;
	$pos_y = 50;
	$string = "";
	for($i = 0; $i<=5; $i++){
		$r = rand(0,126);
		$g = rand(0,126);
		$b = rand(0,126);
		$small = 'abcdefghijklmnopqrstuvwxyz';
		$caps = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$captcha_text = substr(str_shuffle($small.$caps), 0, 1);
		$string = $string.$captcha_text;
		$font_color = imagecolorallocate($captcha_image,$r,$g,$b);
		imagettftext($captcha_image, 40,-10,$pos_x,$pos_y,$font_color,'./asman.ttf',$captcha_text);
		$pos_x += 35;

	}
	$_SESSION['captcha'] = $string;

	imagejpeg($captcha_image);

?>