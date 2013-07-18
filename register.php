<?php 
	require 'connect.php'; 
	session_start();	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
		<script type='text/javascript' src='registerval.js'></script>
		<link type='text/css' rel='stylesheet' href='style.css'/>
	</head>
	<body>
		<div id='registration'>
			<h2>Registration Form</h2>
			<div id='validation'>
				<?php 
					require 'registerval.php'; 
					if(isset($_SESSION['id'])){
						echo "You are already registered and logged in.<br/>";
					}
				?>
			</div>
			<div id='form'>
			<table border='0'>
				<form method='POST' action='register.php' onsubmit='return validate()'>
					<tr>
						<td>First Name:</td>
						<td><input type="text" name="firstname" id="firstname" maxlength='30' value='<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>'/></td>
					</tr>
					<tr>
						<td>Last Name:</td>
						<td><input type='text' name='lastname' id='lastname' maxlength='30' value='<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>'/></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><input type='text' name='username' id='username' maxlength='30' onkeyup='usernamecheck()' value='<?php if(isset($_POST['username'])) echo $_POST['username']; ?>'/></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type='password' name='password' id='password'/></td>
					</tr>
					<tr>
						<td>Confirm Password:</td>
						<td><input type='password' name='repassword' id='repassword'/></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><input type='text' name='email' id='email' maxlength='30' value='<?php if(isset($_POST['email'])) echo $_POST['email']; ?>'/></td>
					</tr>
					<tr>
						<td>Enter the text shown:</td>
						<td>
							<img src = 'captcha.php'><br/>
							<input type='text' name='captchatext' maxlength='6' id='captchatext'/><br/>
						</td>
					</tr>
					<tr>
						<td colspan='2'><input type='submit' name='register' id='register' value='Register'/></td>
					</tr>
				</form>
			</table>
			</div>
		</div>
	</body>
</html>


<!--
		VALIDATION
	All fields mandatory
	FIRST NAME : Starts with Capital Letter. No numbers or special characters
	LAST NAME : ''	
	USERNAME : Letters, numbers , underscore, .		min  = 4 max = 10
	PASSWORD : letters,numbers,underscore,atleast one letter one number  min = 6 letters max = 20
	Email  = Normal Validation



-->
