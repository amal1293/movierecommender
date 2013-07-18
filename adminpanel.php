<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>Admin Control Panel</title>
		<script type='text/javascript'>
			function helpdate(id){
				var el = document.getElementById(id);
				if(id=='date')
					val = 'dd';
				else if(id=='month')
					val = 'mm';
				else if(id=='year')
					val = 'yyyy';

				if(el.value==val)
					el.value = "";
			}
			function nohelp(id){
				var el = document.getElementById(id);
				if(id=='date')
					val = 'dd';
				else if(id=='month')
					val = 'mm';
				else if(id=='year')
					val = 'yyyy';

				if(el.value=="")
					el.value = val;
			}
			function addgenre(){
				var genre = document.getElementById('genre');
				var newgenre = document.getElementById('newgenre');
				if(genre.value=='other')
					newgenre.style.display = 'table-row';
				else
					newgenre.style.display = 'none';

			}
		</script>
	</head>
	<body>
		<div id='header'>
			MOVIES
		</div>
		<div id='nav'>
			| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | <a href='profile.php?uid=<? echo $_SESSION['uid'];?>' >Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
		</div>
			<?php
				if(!isset($_SESSION['uid']))
					echo "You are not logged in.Click <a href='index.php'>here</a> to login.<br/>";
				else{
					$sql = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
					$query = mysql_query($sql) or die(mysql_error());
					if(mysql_result($query,0,'admin') == '0'){
						echo "You are not authorized to be here.<br/>";
					}
					else{
						$username = mysql_result($query,0,'username');
						echo "<div id='userdat'>";
						echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".mysql_result($query,0,'username')."</a> | <a href='adminpanel.php'>Admin Control Panel</a> | <a href='logout.php'>Logout</a> | ";
						echo "</div>";
						echo "<div id='admincontrols'>";
						echo "<a href='adminpanel.php?act=addmovie'>Add New Movie</a><br/>";

						if(isset($_GET['act'])){
							if($_GET['act'] == 'addmovie'){
								if(isset($_POST['submit'])){
									$title = trim(mysql_real_escape_string($_POST['title']));
									$director = trim(mysql_real_escape_string($_POST['director']));
									$producer = trim(mysql_real_escape_string($_POST['producer']));
									$story = trim(mysql_real_escape_string($_POST['story']));
									$starring = trim(mysql_real_escape_string($_POST['starring']));
									$genre = trim(mysql_real_escape_string($_POST['genre']));
									$date = trim(mysql_real_escape_string($_POST['date']));
									$month = trim(mysql_real_escape_string($_POST['month']));
									$year = trim(mysql_real_escape_string($_POST['year']));
									$synopsis = trim(mysql_real_escape_string($_POST['plot']));
									$storemovie = 1;
									if(empty($title) || empty($director) || empty($producer) || empty($story) || empty($starring) || empty($synopsis) || !isset($_FILES['poster'])){
										echo "Enter all the fields.<br/>";
										$storemovie = 0;
									}

									if($genre == '0'){
										echo "Select the movie's genre.<br/>";
										$storemovie = 0;
									}
									else if($genre == 'other'){
										$newgenre = trim(mysql_real_escape_string($_POST['newgenre']));
										if(empty($newgenre)){
											echo "Enter the movie's genre.<br/>";
											$storemovie = 0;
										}
										else{
											$sql5 = "INSERT INTO genre VALUES('".$newgenre."')";
											$query5 = mysql_query($sql5) or die(mysql_error());
											$genre = $newgenre;
										}
									}

									if(!($year <= 2013 && $year >= 1870)){
										echo "Enter a valid release date.<br/>";
										$date = "";
										$month = "";
										$year = "";
										$storemovie = 0;
									}
									
									else{
										switch($month){
											case '01':
											case '03':
											case '05':
											case '07':
											case '08':
											case '10':
											case '12':if(!($date > 0 && $date <= 31) || strlen($date) != 2){
														$storemovie = 0;
														$date = "";
														$month = "";
														$year = "";
														echo "Enter a valid release date.<br/>";
													}
													break;
											case '04':
											case '06':
											case '09':
											case '11':if(!($date > 0 && $date <= 31) || strlen($date) != 2){
														$storemovie = 0;
														$date = "";
														$month = "";
														$year = "";
														echo "Enter a valid release date.<br/>";
													}
													break;
											case '02':if($year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0)){
														if(!($date > 0 && $date <= 29)){
															$storemovie = 0;
															$date = "";
															$month = "";
															$year = "";
															echo "Enter a valid release date.<br/>";
														}
													}
													else{
														if(!($date > 0 && $date <= 28) || strlen($date) != 2){
															$storemovie = 0;
															$date = "";
															$month = "";
															$year = "";
															echo "Enter a valid release date.<br/>";
														}
													}
													break;
											default:$storemovie = 0;
													$date = "";
													$month = "";
													$year = "";
													echo "Enter a valid release date.<br/>";
										}
									}
									if(!isset($_FILES['poster']) || empty($_FILES['poster']['name'])){
										echo "Upload the movie poster.<br/>";
										$storemovie = 0;
									}
									else if(!empty($_FILES['poster']['name'])){
										$postertype = $_FILES['poster']['type'];
										$postersize = $_FILES['poster']['size'];
										$tempname = $_FILES['poster']['tmp_name'];
										if($postertype != 'image/jpeg' && $postertype != 'image/png'){
											echo "Poster must be a jpeg or png file.<br/>";
											$storemovie = 0;
										}
										if($postersize > 1*1024*1024){
											echo "Poster size must be less than 1 MB.<br/>";
											$storemovie = 0;
										}
									}
									if($storemovie == 1){

										$releasedate=$date.'-'.$month.'-'.$year;
										if($postertype == 'image/jpeg')
											$ext = ".jpg";
										else if($postertype == 'image/png')
											$ext = '.png';
										$sql2 = "INSERT INTO movies(title,director,producer,story,starring,genre,releasedate,synopsis) VALUES('".$title.
											"','".$director."','".$producer."','".$story."','".$starring."','".$genre."',STR_TO_DATE('$releasedate','%d-%m-%Y'),'".$synopsis."')";
										$query2 = mysql_query($sql2) or die(mysql_error());
										$storedphotoname = mysql_insert_id().$ext;
										move_uploaded_file($tempname, 'posters/'.$storedphotoname);
										$id = mysql_insert_id();
										
										
										function omitwords($array){
											$omitwords = array('and','the','but','are');
											foreach($array as $index=>$word){
												if(strlen($word) <= 3 || in_array($word, $omitwords)){
													unset($array[$index]);
												}
											}
											return $array;
										}
										$titlekeywords = preg_split('/(\s|,)/',strtolower($title));
										$titlekeywords = omitwords($titlekeywords);
										$directorkeywords = preg_split('/(\s|,)/',strtolower($director));
										$directorkeywords = omitwords($directorkeywords);
										$producerkeywords = preg_split('/(\s|,)/',strtolower($producer));
										$producerkeywords = omitwords($producerkeywords);
										$storykeywords = preg_split('/(\s|,)/',strtolower($story));
										$storykeywords = omitwords($storykeywords);
										$starringkeywords = preg_split('/(\s|,)/',strtolower($starring));
										$starringkeywords = omitwords($starringkeywords);
										$plotkeywords = preg_split('/(\s|,)/',strtolower($synopsis));
										$plotkeywords = omitwords($plotkeywords);
										$keywords = array_merge($titlekeywords,$directorkeywords,$producerkeywords,$storykeywords,$starringkeywords,$plotkeywords);
										$sql3 = "UPDATE movies SET poster='".$storedphotoname."',keywords='".implode(',',$keywords)."' WHERE id='".$id."'";
										$query3 = mysql_query($sql3) or die(mysql_error());
			
										echo "Movie added to database.<br/>";
									}
								}
				?>
								<table border='0'>
								<form method='POST' action='adminpanel.php?act=addmovie' enctype='multipart/form-data'>
								<tr><td>Movie Title:</td><td><input type='text' name='title' value='<? if(isset($title)) echo $title; ?>'></td></tr>
								<tr><td>Director:</td><td><input type='text' name='director' value ='<? if(isset($director)) echo $director; ?>' ></td></tr>
								<tr><td>Producer:</td><td><input type='text' name='producer' value='<? if(isset($producer)) echo $producer; ?>'></td></tr>
								<tr><td>Screenplay:</td><td><input type='text' name='story' value='<? if(isset($story)) echo $story; ?>'></td></tr>
								<tr><td>Starring:</td><td><input type='text' name='starring' value='<? if(isset($starring)) echo $starring; ?>'></td></tr>
				<?php
								echo "<tr><td>Genre:</td><td><select id='genre' name='genre' onchange='addgenre()'>";
								echo "<option value='0'>Choose Genre</option>";
								$sql4 = "SELECT * FROM genre ORDER BY genre";
								$query4 = mysql_query($sql4) or die(mysql_error());
								while($genre = mysql_fetch_assoc($query4)){
									echo "<option value='".$genre['genre']."'>".$genre['genre']."</option>";
								}
								echo "<option value='other'>Other</option>";
								echo "</select></td></tr>";
								echo "<tr id='newgenre' style='display:none'><td>Add Genre:</td><td><input type='text' name='newgenre'></td></tr>";
				?>
								<tr><td>Release Date:</td><td><input type='text' name='date' maxlength='2' size='4' value='<?if(isset($date) && !empty($date)) echo $date; else echo 'dd'; ?>' id='date' onfocus="helpdate('date')" onblur="nohelp('date')">  
																<input type='text' name='month' maxlength='2' size='4' value='<?if(isset($month) && !empty($month)) echo $month; else echo 'mm'; ?>' id='month' onfocus="helpdate('month')" onblur="nohelp('month')"> 
																<input type='text' name='year' size='8' maxlength='4' value='<?if(isset($year) && !empty($year)) echo $year; else echo 'yyyy'; ?>' id='year' onfocus="helpdate('year')" onblur="nohelp('year')"></td></tr>
								<tr><td>Plot Synopsis:</td><td><textarea name='plot'><? if(isset($synopsis)) echo $synopsis; ?></textarea></td></tr>
								<tr><td>Movie Poster:</td><td><input type='file' name='poster'/></td></tr>
								<tr><td colspan='2'><input type='submit' name='submit' value='Add Movie'></td></tr>
								</form>
								</table>

				<?
							}
							echo "</div>";
						}
					}
				}
			?>
	</body>
</html>