<!DOCTYPE html>
<?php
	session_start();
	require '../connect.php';
?>
<html>
	<head>
		<title>Hangman</title>
		<script type='text/javascript' src='hangman.js'></script>
	</head>
	<body>
		<div id='header'>
			MOVIES
		</div>
		<div id='nav'>
			| <a href='../index.php'>Home</a> | <a href='../top.php'>Top Movies</a> | <a href='../profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='../forum/'>Forum</a> | <a href='../hangman/hangman.php'>Fun</a> |
		</div>

		<?php
			if(!isset($_SESSION['uid']))
				header("Location:../index.php");
			else{
				$sql4 = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
				$query4 = mysql_query($sql4) or die(mysql_error());
				$username = mysql_result($query4,0,'username');
				$admin = mysql_result($query4,0,'admin');
				echo "<div id='userdat'>";
				echo "Welcome <a href='../profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | <a href='../adminpanel.php'>Admin Control Panel</a> | <a href='../logout.php'>Logout</a>";
				echo "</div>";
				echo "<div id='admincontrols'>";
				echo "</div>";
				function generatemovie(){
					$sql = "SELECT id FROM movies ORDER BY id DESC LIMIT 1";
					$query = mysql_query($sql) or die(mysql_error());
					$sql2 = "SELECT id FROM movies ORDER BY id LIMIT 1";
					$query2 = mysql_query($sql2) or die(mysql_error());
					if(mysql_num_rows($query) > 0 && mysql_num_rows($query2) > 0){
						$lastid = mysql_result($query,0);
						$firstid = mysql_result($query2,0);
						do{
						$id = rand($firstid,$lastid);
						$sql3 = "SELECT id,title FROM movies WHERE id='".$id."'";
						$query3 = mysql_query($sql3) or die(mysql_error());
						$movieid = mysql_result($query3,0,'id');
						$movietitle = mysql_result($query3,0,'title');
						}while((mysql_num_rows($query3) == 0) || preg_match('/^[a-zA-Z\s]+$/', $movietitle) == 0);
						return array('id'=>$movieid,'title'=>$movietitle);
					}
					else{
						return false;
					}
				}

				$movie = generatemovie();
				echo "<div id='hangmancontent'>";
				for($i=0;$i<strlen($movie['title']);$i++){
					if($movie['title'][$i] != " "){
						echo "<span class='letters' id='letter".$i."'>";
						echo "___</span> &nbsp;";
					}
					else
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
	?>
				<input type='button' value='A' class='keys' id='A' onclick="checkvalue(<? echo $movie['id']; ?>,'A')">
				<input type='button' value='B' class='keys' id='B' onclick="checkvalue(<? echo $movie['id']; ?>,'B')">
				<input type='button' value='C' class='keys' id='C' onclick="checkvalue(<? echo $movie['id']; ?>,'C')">
				<input type='button' value='D' class='keys' id='D' onclick="checkvalue(<? echo $movie['id']; ?>,'D')">
				<input type='button' value='E' class='keys' id='E' onclick="checkvalue(<? echo $movie['id']; ?>,'E')">
				<input type='button' value='F' class='keys' id='F' onclick="checkvalue(<? echo $movie['id']; ?>,'F')">
				<input type='button' value='G' class='keys' id='G' onclick="checkvalue(<? echo $movie['id']; ?>,'G')">
				<input type='button' value='H' class='keys' id='H' onclick="checkvalue(<? echo $movie['id']; ?>,'H')">
				<input type='button' value='I' class='keys' id='I' onclick="checkvalue(<? echo $movie['id']; ?>,'I')">
				<input type='button' value='J' class='keys' id='J' onclick="checkvalue(<? echo $movie['id']; ?>,'J')">
				<input type='button' value='K' class='keys' id='K' onclick="checkvalue(<? echo $movie['id']; ?>,'K')">
				<input type='button' value='L' class='keys' id='L' onclick="checkvalue(<? echo $movie['id']; ?>,'L')">
				<input type='button' value='M' class='keys' id='M' onclick="checkvalue(<? echo $movie['id']; ?>,'M')">
				<input type='button' value='N' class='keys' id='N' onclick="checkvalue(<? echo $movie['id']; ?>,'N')">
				<input type='button' value='O' class='keys' id='O' onclick="checkvalue(<? echo $movie['id']; ?>,'O')">
				<input type='button' value='P' class='keys' id='P' onclick="checkvalue(<? echo $movie['id']; ?>,'P')">
				<input type='button' value='Q' class='keys' id='Q' onclick="checkvalue(<? echo $movie['id']; ?>,'Q')">
				<input type='button' value='R' class='keys' id='R' onclick="checkvalue(<? echo $movie['id']; ?>,'R')">
				<input type='button' value='S' class='keys' id='S' onclick="checkvalue(<? echo $movie['id']; ?>,'S')">
				<input type='button' value='T' class='keys' id='T' onclick="checkvalue(<? echo $movie['id']; ?>,'T')">
				<input type='button' value='U' class='keys' id='U' onclick="checkvalue(<? echo $movie['id']; ?>,'U')">
				<input type='button' value='V' class='keys' id='V' onclick="checkvalue(<? echo $movie['id']; ?>,'V')">
				<input type='button' value='W' class='keys' id='W' onclick="checkvalue(<? echo $movie['id']; ?>,'W')">
				<input type='button' value='X' class='keys' id='X' onclick="checkvalue(<? echo $movie['id']; ?>,'X')">
				<input type='button' value='Y' class='keys' id='Y' onclick="checkvalue(<? echo $movie['id']; ?>,'Y')">
				<input type='button' value='Z' class='keys' id='Z' onclick="checkvalue(<? echo $movie['id']; ?>,'Z')">

				<img src='images/1.png' id='hangman' width='500' height='500'/>
							
	<?php
		echo "</div>";
				}
		?>
	</body>
</html>