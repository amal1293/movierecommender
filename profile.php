<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>User Profile</title>
	</head>
	<body>
			<div id='header'>
				MOVIES
			</div>
			<div id='nav'>
				| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | Profile | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
			</div>
			<?php
				if(!isset($_SESSION['uid'])){
					header("Location:index.php");
				}
				else if(!isset($_GET['uid'])){
					echo "Unknown Username. <a href='index.php'>Home</a><br/>";
				}
				else{
					$sql = "SELECT * FROM users WHERE id='".$_GET['uid']."'";
					$query = mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($query) == 0){
						echo "Unknown Username. <a href='index.php'>Home</a><br/>"; 
					}
					else{
						$userexists = 1;
						$username = mysql_result($query,0,'username');
						if(mysql_result($query,0,'admin') == 1)
							$admin = 1;
						else
							$admin = 0;
					}
				}

				if(isset($userexists) && $userexists == 1){
					echo "<div id='userdat'>";
					echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | ";
					if($admin == 1){
						echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
					}
					echo "<a href='logout.php'>Logout</a> | ";
					echo "</div>";
					echo "<div id='leftnav'>";
					$sql2 = "SELECT username,photo FROM users WHERE id='".$_GET['uid']."'";
					$query2 = mysql_query($sql2) or die(mysql_error());
					$profile = mysql_fetch_assoc($query2);
					echo "<img src='users/".$profile['photo']."' title='".$profile['username']."' height='300' width='300'/><br/>";
					if($_GET['uid'] == $_SESSION['uid']){
			?>
						<a href='settings.php?act=changephoto'>Change Photo</a><br/>
						<a href='settings.php?act=editacc'>Edit Account Details</a><br/>
						<a href='settings.php?act=changepassword'>Change Password</a><br/>
			<?php
					}
					echo "</div>";
				}
			?>
		</div>
		<div id='contentbottom'>
			<?php
				if(isset($userexists) && $userexists == 1){
					echo "Recent Activities:<br/>";
					$sql3 = "SELECT * FROM ratings WHERE userid='".$_GET['uid']."' ORDER BY time DESC LIMIT 10";
					$query3 = mysql_query($sql3) or die(mysql_error());
					if(mysql_num_rows($query3) > 0){
						while($activity = mysql_fetch_assoc($query3)){
							$sql4 = "SELECT title FROM movies WHERE id='".$activity['movieid']."'";
							$query4 = mysql_query($sql4) or die(mysql_error());
							$moviename = mysql_result($query4,0);
							$sql5 = "SELECT username FROM users WHERE id='".$_GET['uid']."'";
							$query5 = mysql_query($sql5) or die(mysql_error());
							$username = mysql_result($query5,0);
							echo $username." gave <a href='movie.php?mid=".$activity['movieid']."'>".$moviename.'</a> '.$activity['rating'].' stars.<br/>';
						}
					}
					else{
						echo "No Activities.<br/>";
					}
				}
			?>
		</div>
	</body>
</html>