<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>Settings</title>
	</head>
	<body>
		<div id='header'>
			MOVIES
		</div>
		<div id='nav'>
			| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | <a href='profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
		</div>
			<?php
				if(isset($_GET['act']) && isset($_SESSION['uid'])){
					$sql9 = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
					$query9 = mysql_query($sql9) or die(mysql_error());
					$username = mysql_result($query9,0,'username');
					$admin = mysql_result($query9,0,'admin');
					echo "<div id='userdat'>";
					echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | ";
					if($admin == 1)
						echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
					echo "<a href='logout.php'>Logout</a>";
					echo "</div>";
					echo "<div id='admincontrols'>";
					echo "</div>";
					echo "<div id='setting'>";
					if($_GET['act'] == 'changepassword'){
						if(isset($_POST['submitpass'])){
							$canchange = 1;
							$oldpass = trim(mysql_real_escape_string($_POST['oldpassword']));
							$newpass = trim(mysql_real_escape_string($_POST['newpassword']));
							$confpass = trim(mysql_real_escape_string($_POST['confpassword']));
							$sql = "SELECT username,password FROM users WHERE id='".$_SESSION['uid']."'";
							$query = mysql_query($sql) or die(mysql_error());
							$result = mysql_fetch_assoc($query);
							if(md5($result['username'].md5($oldpass)) != $result['password']){
								echo "Wrong Password Entered.<br/>";
								$canchange = 0;
							}
							if(strlen($newpass) < 6){
								echo "New Password must be atleast 6 characters long.<br/>";
								$canchange = 0;
							}
							if($newpass != $confpass){
								echo "Passwords do not match.<br/>";
								$canchange = 0;
							}
							if($canchange == 1){
								$sql2 = "UPDATE users SET password='".md5($result['username'].md5($newpass))."' WHERE id='".$_SESSION['uid']."'";
								$query2 = mysql_query($sql2) or die(mysql_error());
								echo "Password Successfully Changed.<br/>";
							}
						}
						echo "<table border='0'>";
						echo "<form action='settings.php?act=changepassword' method='POST'>";
						echo "<tr><td>Old Password:</td><td><input type='password' name='oldpassword'/></td></tr>";
						echo "<tr><td>New Password:</td><td><input type='password' name='newpassword'/></td></tr>";
						echo "<tr><td>Confirm New Password:</td><td><input type='password' name='confpassword'/></td></tr>";
						echo "<tr><td colspan='2'><input type='submit' name='submitpass' value='Change Password'/></td></tr>";
						echo "</form>";
						echo "</table>";
					}

					else if($_GET['act'] == 'editacc'){
						if(isset($_POST['submit'])){
							$updated = 0;
							if(!empty($_POST['firstname'])){
								$firstname = trim(mysql_real_escape_string($_POST['firstname']));
								if(!($firstname[0] >= 'A' && $firstname[0] <= 'Z'))
									echo "Firstname must start with a capital letter.<br/>";
								else{
									$sql6 = "UPDATE users SET firstname='".$firstname."' WHERE id='".$_SESSION['uid']."'";
									$query = mysql_query($sql6) or die(mysql_error());
									$updated = 1;
								}
							}

							if(!empty($_POST['lastname'])){
								$lastname = trim(mysql_real_escape_string($_POST['lastname']));
								if(!($lastname[0] >= 'A' && $lastname[0] <= 'Z'))
									echo "Lastname must start with a capital letter.<br/>";
								else{
									$sql7 = "UPDATE users SET lastname='".$lastname."' WHERE id='".$_SESSION['uid']."'";
									$query7 = mysql_query($sql7) or die(mysql_error());
									$updated = 1;
								}
							}

							if(isset($_POST['gender'])){
								$gender = trim(mysql_real_escape_string($_POST['gender']));
								$sql8 = "UPDATE users SET gender='".$gender."' WHERE id='".$_SESSION['uid']."'";
								$query8 = mysql_query($sql8) or die(mysql_error());
								$updated = 1;
							}

							if($updated == 1)
								echo "Successfully Updated.<br/>";
						}

						$sql5 = "SELECT * FROM users WHERE id='".$_SESSION['uid']."'";
						$query5 = mysql_query($sql5) or die(mysql_error());
						$details = mysql_fetch_assoc($query5);
		?>
						<table border='0'>
						<form method='POST' action='settings.php?act=editacc' enctype='multipart-formdata'>
						<tr><td>First Name:</td><td><input type='text' name='firstname' value='<? echo $details['firstname']; ?>'></td></tr>
						<tr><td>Last Name:</td><td><input type='text' name='lastname' value='<? echo $details['lastname']; ?>'></td></tr>
						<tr><td>Username:</td><td><? echo $details['username']; ?></td></tr>
						<tr><td>Gender:</td><td><input type='radio' name='gender' value='male' <? if(isset($details['gender']) && $details['gender'] == 'male') echo "checked='checked'"; ?>>Male <input type='radio' name='gender' value='female' <? if(isset($details['gender']) && $details['gender'] == 'female') echo "checked= 'checked'"; ?> >Female</td></tr>
						<tr><td>Email:</td><td><? echo $details['email']; ?></td></tr>
						<tr><td colspan='2'><input type='submit' name='submit' value='Update'></td></tr>
						</form>
						</table>
		<?php
					}

					else if($_GET['act'] == 'changephoto'){
						if(isset($_POST['submitphoto'])){
							$phototype = $_FILES['photo']['type'];
							$photosize = $_FILES['photo']['size'];
							$tempname = $_FILES['photo']['tmp_name'];
							$changephoto = 1;
							if(!empty($_FILES['photo']['name'])){
								if($phototype != 'image/jpeg' && $phototype != 'image/png'){
									echo "Photo should be of jpeg or png formats.<br/>";
									$changephoto = 0;
								}
								if($photosize > 1*1024*1024){
									echo "Photo should be less than 1 MB in size.<br/>";
									$changephoto = 0;
								}
								if($changephoto == 1){
									if($phototype == 'image/jpeg')
										$ext = '.jpg';
									else if($phototype == 'image/png')
										$ext = '.png';
									$photoname = $_SESSION['uid'].$ext;
									move_uploaded_file($tempname, 'users/'.$photoname);
									$sql4 = "UPDATE users SET photo='".$photoname."' WHERE id='".$_SESSION['uid']."'";
									$query4 = mysql_query($sql4) or die(mysql_error());
									echo "Successfully Updated Photo.<br/>";
								}
							}
						}
						$sql3 = "SELECT photo FROM users WHERE id='".$_SESSION['uid']."'";
						$query3 = mysql_query($sql3) or die(mysql_error());	
						echo "<img src='users/".mysql_result($query3,0)."' title='Profile Photo' width='300' height='300'/>";
						echo "<table border='0'>";
						echo "<form action='settings.php?act=changephoto' method='POST' enctype='multipart/form-data'>";
						echo "<tr><td>Upload New Photo:</td><td><input type='file' name='photo'/></td></tr>";
						echo "<tr><td colspan='2'><input type='submit' value='Change Photo' name='submitphoto'/></td></tr>";
						echo "</form>";
						echo "</table>";
					}
					echo "</div>";
				}
			?>
	</body>
</html>