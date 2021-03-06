<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>Movie Recommendation Engine</title>
		<link href='style.css' rel='stylesheet' type='text/css'/>
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
					echo "<div id='login'>";
					if(isset($_POST['login'])){
						$username = trim(mysql_real_escape_string($_POST['username']));
						$password = trim($_POST['password']);
						$sql="SELECT id FROM users WHERE username='".$_POST['username']."' AND password='".md5($username.md5($password))."'";
						$query=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($query) == 0){
							echo "Incorrect Username/Password<br/>";
						}
						else{
							$_SESSION['uid'] = mysql_result($query,0);
							header("Location:index.php");
						}
					}
			?>
					<p id='logintitle'>LOGIN</p>
					<table border='0' id='logintable'>
					<form method='POST' action='index.php'>
					<tr><td class='loginlabel'>Username:</td><td class='logininput'><input type='text' name='username' id='username'></td></tr>
					<tr><td class='loginlabel'>Password:</td><td class='logininput'><input type='password' name='password' id='password'></td></tr>
					<tr><td colspan='2' align='center'><input type='submit' value='Login' name='login'></td></tr>
					</form>
					</table>
					<p id='toregister'>Not a registered user? <a href='register.php'>Register Now</a></p>
			<?php
					echo "</div>";
				}

				else{
					$sql2="SELECT username FROM users WHERE id='".$_SESSION['uid']."'";
					$query2 = mysql_query($sql2) or die(mysql_error());
					echo "<div id='userdat'>";
					echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".mysql_result($query2,0)."</a> | ";
					$sql3 = "SELECT admin FROM users WHERE id = '".$_SESSION['uid']."'";
					$query3 = mysql_query($sql3) or die(mysql_error());
					if(mysql_result($query3,0) == '1'){
						echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
					}

					echo "<a href='logout.php'>Logout</a> | <br/>";
					echo "</div>";
					echo "<div id='searchbox'>";
					if(!isset($_GET['query']))
						$_GET['query'] = '';
					echo "<form method='GET' action='search.php?query=".$_GET['query']."'>";
					echo "<input type='text' name='query' id='search'/>";
					echo "<input type='submit' name='search' value='Search'/>";
					echo "</form>";
					echo "</div>";
					echo "<hr/>";

				echo "<div id='maincontent'>";
				

				echo "<span id='foryou'>Your Recommendations:</span><br/>";
				//		Recommendation Engine

				//1.Select Possible Movies

				$sql4 = "SELECT DISTINCT movieid FROM ratings WHERE userid='".$_SESSION['uid']."'";
				$query4 = mysql_query($sql4) or die(mysql_error());
				if(mysql_num_rows($query4) < 1){
				}
				else{
					while($ratedmovie = mysql_fetch_assoc($query4)){
						$sql5 = "SELECT userid FROM ratings WHERE movieid='".$ratedmovie['movieid']."' AND userid!='".$_SESSION['uid']."'";
						$query5 = mysql_query($sql5) or die(mysql_error());
						if(mysql_num_rows($query5) > 0){
							while($otherusers = mysql_fetch_assoc($query5)){
								$sql6 = "SELECT movieid FROM ratings WHERE userid='".$otherusers['userid']."'";
								$query6 = mysql_query($sql6) or die(mysql_error());
								while($othermovies = mysql_fetch_assoc($query6)){
								$possiblemovies[] = $othermovies['movieid'];
								}
							}
						}	
					}

					//2.Exclude Already Rated Movies

					if(isset($possiblemovies)){
						$possiblemovies = array_unique($possiblemovies);
						$sql7 = "SELECT movieid FROM ratings WHERE userid='".$_SESSION['uid']."'";
						$query7 = mysql_query($sql7) or die(mysql_error());
						while($excludemovies = mysql_fetch_assoc($query7)){
							if(($key = array_search($excludemovies['movieid'], $possiblemovies)) !== false){
								unset($possiblemovies[$key]);
							}
						}

						//3.Find Possible Rating

						if(sizeof($possiblemovies) > 0){
							foreach($possiblemovies as $checkmovie){
								$top = 0;
								$bottom = 0;
								$sql8 = "SELECT movieid,rating FROM ratings WHERE userid='".$_SESSION['uid']."'";
								$query8 = mysql_query($sql8) or die(mysql_error());
								while($result = mysql_fetch_assoc($query8)){
									$movie2 = $result['movieid'];
									$rating2 = $result['rating'];
									$sql9 = "SELECT users_rated_both,sum FROM compare WHERE movie1='".$checkmovie."' AND movie2='".$movie2."'";
									//echo $sql9.'<br/>';
									$query9 = mysql_query($sql9) or die(mysql_error());
									if(mysql_num_rows($query9) > 0){
										$userno = mysql_result($query9,0,'users_rated_both');
										$sum = mysql_result($query9,0,'sum');
										$avgrating = $sum/$userno;
										$bottom += $userno;
										//echo 'Movie1'.$checkmovie.'<br/>Movieid:'.$movie2.'<br/> Rate diff:'.$avgrating.'<br/>Users Rated Both'.$userno.'<br/>Rating For 1st Movie'.$rating2.'<br/><br/>';
										$top += ($userno*($rating2-$avgrating));
									}
									else{
										$sql11 = "SELECT users_rated_both,sum FROM compare WHERE movie1='".$movie2."' AND movie2 = '".$checkmovie."'";
										$query11 = mysql_query($sql11) or die(mysql_error());
										if(mysql_num_rows($query11) > 0){
											$userno = mysql_result($query11,0,'users_rated_both');
											$sum = mysql_result($query11,0,'sum');
											$avgrating = $sum/$userno;
											$bottom += $userno;
											$top += ($userno*($rating2+$avgrating));
										}
									}
								}
								if($bottom == 0)
									$possiblerating[$checkmovie] = 0;
								else{
									//echo 'Movieid:'.$checkmovie.' Top='.$top.'and bottom='.$bottom.'<br/>';
									$possiblerating[$checkmovie] = ($top/$bottom);
								}
							}

							//4.Echo Movies Based On Possible Rating

							if(isset($possiblerating)){
								echo "<form method='POST' action='index.php'>";
								echo "<span id='genre'>";
								echo "Genre:";
								echo "<select name='genre' onchange='this.form.submit()'>";
								echo "<option value='All'>All</option>";
								$sql12 = "SELECT genre FROM genre";
								$query12 = mysql_query($sql12) or die(mysql_error());
								while($genres = mysql_fetch_assoc($query12)){
									if(isset($_POST['genre']) && $_POST['genre'] == $genres['genre'])
										$select = 'selected="selected"';
									else 
										$select = "";
									echo "<option value='".$genres['genre']."' ".$select.">".$genres['genre']."</option>";
								}
								echo "</select>";
								echo "</span>";
								echo "</form><br/>";
								arsort($possiblerating);
								foreach($possiblerating as $recommend=>$predictrating){
									if(!isset($_POST['genre']) || $_POST['genre'] == 'All'){
										$sql10 = "SELECT * FROM movies WHERE id='".$recommend."' AND '".$predictrating."' > 5";
										$query10 = mysql_query($sql10) or die(mysql_error());
									}
									else{
										$sql10 =  "SELECT * FROM movies WHERE id='".$recommend."' AND '".$predictrating."'>5 AND genre='".$_POST['genre']."'";
										$query10 = mysql_query($sql10) or die(mysql_error());
									}
									if(mysql_num_rows($query10) > 0){
										echo "<div class='recimg'>";
										while($printmovie = mysql_fetch_assoc($query10)){
											//echo $predictrating;	
											echo "<a href='movie.php?mid=".$printmovie['id']."'><img class='recimg' src='posters/".$printmovie['poster']."' height='250' width='250'/></a><br/>";
											echo "<span class='rectitle'>".$printmovie['title']."</span>";
										}
										echo "</div>";
									}
								}
							}
						}
					}
				}
				echo "</div>";
			?>
			<div id='newmovies'>
				<?
					require 'newmovies.php';
			}
				?>
			</div>
	</body>
</html>