<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<script type='text/javascript' src='rating.js'></script>
		<title>Movie</title>
	</head>
	<body>
		<div id='header'>
			MOVIES
		</div>
		<div id='nav'>
			| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | <a href='profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
		</div>

		<div>
			<?php
			if(isset($_SESSION['uid'])){
				if(!isset($_GET['mid'])){
					echo "Movie Info Not Found.<br/>";
				}
				else{
					$sql3 = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
					$query3 = mysql_query($sql3) or die(mysql_error());
					$username = mysql_result($query3,0,'username');
					$admin = mysql_result($query3,0,'admin');
					echo "<div id='userdat'>";
					echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | ";
					if($admin == 1){
						echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
					}
					echo "<a href='logout.php'>Logout</a> | ";
					echo "</div>";
					$sql = "SELECT * FROM movies WHERE id='".$_GET['mid']."'";
					$query = mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($query) == 0)
							echo "Movie Info Not Found.<br/>";
					else{
						while($movie = mysql_fetch_assoc($query)){
							echo "<img src='posters/".$movie['poster']."' height='300' width='300'/><br/>";
							echo "Movie Title:".$movie['title'].'<br/>';
							echo "Director:".$movie['director'].'<br/>';
							echo "Producer:".$movie['producer'].'<br/>';
							echo "Screenplay:".$movie['story'].'<br/>';
							echo "Starring:".$movie['starring'].'<br/>';
							echo "Release Date:".date('d M ,Y',strtotime($movie['releasedate'])).'<br/>';
							echo "Plot Synopsis:".$movie['synopsis'].'<br/>';
						}
						$sql2 = "SELECT userid,rating FROM ratings WHERE movieid='".$_GET['mid']."'";
						$query2 = mysql_query($sql2) or die(mysql_error());
						$rated = 0;
						while($ratedmovies = mysql_fetch_assoc($query2)){
							if($ratedmovies['userid'] == $_SESSION['uid']){
								$rating = $ratedmovies['rating'];
								$rated = 1;
							}
						}
						if($rated == 1){
							echo "Your Rating: ";
							for($i=1;$i<=$rating;$i++){
								echo "<img src='rating/color.jpg' height='20' width='20'>";
							}
							for($i=10-$rating;$i>=0;$i--){
								echo "<img src='rating/white.jpg' height='20' width='20'>";
							}
							echo $rating.'.0/10.0<br/>';

						}
						else if($rated == 0){
							
					?>
							<span id='message'>Rate This Movie: </span>
							<img src='rating/white.jpg' height='20' width='20' id='star1' onmouseover='changeColor(1)' onmouseout='revertColor(1)' onclick='fixcolor(1); storerating(<?php echo $_GET['mid']; ?>,1);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star2' onmouseover='changeColor(2)' onmouseout='revertColor(2)' onclick='fixcolor(2); storerating(<?php echo $_GET['mid']; ?>,2);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star3' onmouseover='changeColor(3)' onmouseout='revertColor(3)' onclick='fixcolor(3); storerating(<?php echo $_GET['mid']; ?>,3);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star4' onmouseover='changeColor(4)' onmouseout='revertColor(4)' onclick='fixcolor(4); storerating(<?php echo $_GET['mid']; ?>,4);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star5' onmouseover='changeColor(5)' onmouseout='revertColor(5)' onclick='fixcolor(5); storerating(<?php echo $_GET['mid']; ?>,5);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star6' onmouseover='changeColor(6)' onmouseout='revertColor(6)' onclick='fixcolor(6); storerating(<?php echo $_GET['mid']; ?>,6);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star7' onmouseover='changeColor(7)' onmouseout='revertColor(7)' onclick='fixcolor(7); storerating(<?php echo $_GET['mid']; ?>,7);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star8' onmouseover='changeColor(8)' onmouseout='revertColor(8)' onclick='fixcolor(8); storerating(<?php echo $_GET['mid']; ?>,8);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star9' onmouseover='changeColor(9)' onmouseout='revertColor(9)' onclick='fixcolor(9); storerating(<?php echo $_GET['mid']; ?>,9);'/>
							<img src='rating/white.jpg' height='20' width='20' id='star10' onmouseover='changeColor(10)' onmouseout='revertColor(10)' onclick='fixcolor(10); storerating(<?php echo $_GET['mid']; ?>,10);'/>
							<span id='rating'>0.0</span>/10.0<br/>
					<?php
						}
						
					}
				}
			}
			else{
				header("Location:index.php");
			}
			?>
		</div>
	</body>
</html>