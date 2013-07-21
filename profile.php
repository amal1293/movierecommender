<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>User Profile</title>
		<link type='text/css' href='style.css' rel='stylesheet'/>
		<link rel='icon' href='favicon.ico' type='image/x-icon'/>
	</head>
	<body>
			<div id='header'>
				MOVIES
			</div>
			<div id='nav'>
				| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | <a href='profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
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
						$profilename = mysql_result($query,0,'username');
						$sql3 = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
						$query3 = mysql_query($sql3) or die(mysql_error());
						$username = mysql_result($query3,0,'username');
						if(mysql_result($query3,0,'admin') == 1)
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
					echo "<span id='profilename'>".$profilename.'</span><br/>';
					echo "<img src='users/".$profile['photo']."' title='".$profile['username']."' height='300' width='300'/><br/>";
					if($_GET['uid'] == $_SESSION['uid']){
			?>
						<span class='setting'><a href='settings.php?act=changephoto'>Change Photo</a></span><br/>
						<span class='setting'><a href='settings.php?act=editacc'>Edit Account Details</a></span><br/>
						<span class='setting'><a href='settings.php?act=changepassword'>Change Password</a></span><br/>
			<?php
						$name = "Your";
						$name2 = "You";
					}
					else{
						$name = $profilename."'s";
						$name2 = $profilename;
					}
					echo "</div>";
				}
			?>

		<div id='contentbottom'>
			<?php
				if(isset($userexists) && $userexists == 1){
					echo $name." Recent Activities:<br/>";
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
							echo $name2." gave <a href='movie.php?mid=".$activity['movieid']."'>".$moviename.'</a> '.$activity['rating'].' stars.<br/>';
						}
					}
					else{
						echo "No Activities.<br/>";
					}

					// Watchlist
					echo $name."  Watchlist:<br/>";
					$sql6 = "SELECT watchlist FROM users WHERE id='".$_SESSION['uid']."'";
					$query6 = mysql_query($sql6) or die(mysql_error());
					if(mysql_num_rows($query6) > 0 && mysql_result($query6,0,'watchlist') != ""){
						$wlistid = explode(",", mysql_result($query6,0,'watchlist'));
						foreach($wlistid as $wlistmovie){
							$sql7 = "SELECT * FROM movies WHERE id='".$wlistmovie."'";
							$query7 = mysql_query($sql7) or die(mysql_error());
							echo "<a href='movie.php?mid=".$wlistmovie."'><img src='posters/".$wlistmovie.".jpg' width = '200' height = '200'></a> ";
						}
					}
					//End of Watchlist
					//User Reviews
					echo "<br/>Reviews:<br/>";
					$sql8 = "SELECT *,movies.title FROM reviews  INNER JOIN movies ON reviews.mid=movies.id WHERE uid='".$_GET['uid']."' ORDER BY date DESC";
					$query8 = mysql_query($sql8) or die(mysql_error());
					if(mysql_num_rows($query8) > 0){
						while($reviews = mysql_fetch_assoc($query8)){
							echo $name." review on <a href='movie.php?mid=".$reviews['mid']."'>".$reviews['title'].'</a>:<br/>';
							echo $reviews['reviews'].'<br/><br/>';
						}
					}

					//End of User Reviews
				}
			?>
	</div>
	</body>
</html>