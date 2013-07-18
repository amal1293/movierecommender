<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
<head>
	<title>
		Top Movies
	</title>
</head>
<body>
	<div id='header'>
		MOVIES
	</div>
	<div id='nav'>
		| <a href='index.php'>Home</a> | Top Movies | <a href='profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
	</div>
	<?php
		if(!isset($_SESSION['uid']))
			header("Location:index.php");
		else{
			$sql = "SELECT id,username,admin FROM users WHERE id='".$_SESSION['uid']."'";
			$query = mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($query) == 0)
				header("Location:index.php");
			else{
				$username=mysql_result($query,0,'username');
				$admin = mysql_result($query,0,'admin');
				echo "<div id='userdat'>";
				echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | ";
				if($admin == 1){
						echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
				}
				echo "<a href='logout.php'>Logout</a> | ";
				echo "</div>";
				echo "<div id='content'>";
				$sql2 = "SELECT * FROM movies WHERE avgrating != 0.00 ORDER BY avgrating DESC,no_voters DESC";
				$query2 = mysql_query($sql2) or die(mysql_error());
				if(mysql_num_rows($query2) > 0){
					$sno = 1;
					echo "<table border='0'>";
					echo "<tr><th>S NO.</th><th>Movie</th><th>Rating</th><th>Votes</th></tr>";
					while($movie = mysql_fetch_assoc($query2)){
						echo "<tr><td>".$sno."</td><td><a href='movie.php?mid=".$movie['id']."'</a>".$movie['title']."</td><td>".$movie['avgrating']."</td><td>".$movie['no_voters']."</td></tr>";
						$sno++;
					}
					echo "</table>";		
				}
				echo "</div>";
			}
		}	
	?>
</body>
</html>