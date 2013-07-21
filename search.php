<!DOCTYPE html>
<?php
	session_start();
	require 'connect.php';
?>
<html>
	<head>
		<title>Search</title>
		<link type='text/css' href='style.css' rel='stylesheet'/>
		<link rel='icon' href='favicon.ico' type='image/x-icon'/>
	</head>
	<body>
		<div id='header'>
			MOVIES
		</div>
		<div id='nav'>
			| <a href='index.php'>Home</a> | <a href='top.php'>Top Movies</a> | <a href='profile.php?uid=<? echo $_SESSION['uid']; ?>'>Profile</a> | <a href='forum/'>Forum</a> | <a href='hangman/hangman.php'>Fun</a> |
		</div>
		<?php

			if(!isset($_SESSION['uid']))
				header("Location:index.php");

			else{
				$sql4 = "SELECT username,admin FROM users WHERE id='".$_SESSION['uid']."'";
				$query4 = mysql_query($sql4) or die(mysql_error());
				$username = mysql_result($query4,0,'username');
				$admin = mysql_result($query4,0,'admin');
				echo "<div id='userdat'>";
				echo "Welcome <a href='profile.php?uid=".$_SESSION['uid']."'>".$username."</a> | ";
				if($admin == 1)
					echo "<a href='adminpanel.php'>Admin Control Panel</a> | ";
		?>
				<a href='logout.php'>Logout</a>
				</div>
				<div id='searchbox'>
				<form method='GET' action='search.php?query=<? echo $_GET['query']; ?>'>
				<input type='text' name='query' id='search'/>
				<input type='submit' name='search' value='Search'/>
				</form>
				</div>
				<div id='maincontent'>
		<?
				if(!isset($_GET['query'])){
					echo "No results found.<br/>";
				}
				else{
					$query = trim(mysql_real_escape_string($_GET['query']));
					if(empty($query))
						echo "No results found.<br/>";
					else{
						$searchterms = explode(' ',$query);
						$termsno = sizeof($searchterms);
						$k=0;
						foreach($searchterms as $term){
							//echo $term.'<br/>';
							//$termsmatched = array();

							$term = strtolower($term);
							$sql2 = "SELECT id,keywords FROM movies WHERE keywords LIKE '%,".$term.",%' OR keywords LIKE '".$term.",%' OR keywords LIKE '%,".$term."'";
							$query2 = mysql_query($sql2) or die(mysql_error());
							//echo $term.' has '.mysql_num_rows($query2).' occ.<br/>';
							if(mysql_num_rows($query2) > 0){
								while($keyword = mysql_fetch_assoc($query2)){
									$key = $keyword['id'];
									$allkeywords = explode(",",$keyword['keywords']);
									$count = array_count_values($allkeywords);
									//echo $term.' has '.$count[$term].' occ. in '.$keyword['id'].'<br/>';
									if(isset($occuranceno[$key])){ 
										$occuranceno[$key] += $count[$term];
										$termsmatched[$key]++;
										for($j=0;$j<$termsno;$j++)
											if($j==$k && $searchterms[$j] == $term){
												$occuranceno[$key]--;
												if($occuranceno[$key] == 1)
													$termsmatched[$key]--;
											}

									}
									else{
										$occuranceno[$key] = $count[$term];
										$termsmatched[$key] = 1;
									}
								}
							}
							$k++;
						}
						if(isset($occuranceno)){
						//	arsort($occuranceno);
							arsort($termsmatched);
							//print_r($termsmatched);
							$i = current($termsmatched);
							foreach($termsmatched as $prikey=>$timesmatched){
								$maxcount = $occuranceno[$prikey];
								if($timesmatched>=2){
									$occuranceno[$prikey] = max($occuranceno)+1;
								}
							}
							arsort($occuranceno);
							//print_r($termsmatched);
							//print_r($occuranceno);
							if(sizeof($occuranceno) == 0)
								echo "No results found.<br/>";
							else{
								echo "Search Results:<br/>";
								$limit = 0;
								foreach($occuranceno as $mid=>$contains){
									$sql3 = "SELECT * FROM movies WHERE id='".$mid."'";
									$query3 = mysql_query($sql3) or die(mysql_error());
									while($details = mysql_fetch_assoc($query3)){
										echo "Movie Name:<a href='movie.php?mid=".$mid."'>".$details['title'].'</a><br/>';
									}
									$limit++;
									if($limit == 10)
										break;

								}
							}
						}
						else{
							foreach($searchterms as $term){
								$sql5 = "SELECT id,title,keywords FROM movies";
								$query5 = mysql_query($sql5) or die(mysql_error());
								if(mysql_num_rows($query5) >0){
									while($kword = mysql_fetch_assoc($query5)){
										$key = $kword['id'];
										$allkwords = explode(",",$kword['keywords']);
										foreach($allkwords as $fuzzy){
											similar_text($term,$fuzzy,$perc);
											if($perc > 70){
												if(isset($occuranceno[$key])){
													$termsmatched[$key] += 1;
													break;
												}
												else{
													$termsmatched[$key] = 1;
													break;
												}
											}
										}
										//echo $term.' has '.$count[$term].' occ. in '.$keyword['id'].'<br/>';
									//**
									}
								}
							}
							if(isset($termsmatched)){
							echo "No results found for ".$_GET['query'].".Showing results for related searches.<br/>";
							arsort($termsmatched);
							$limit = 0;
							foreach($termsmatched as $fkey=>$fuzzyresult){
								$sql6 = "SELECT * FROM movies WHERE id='".$fkey."'";
								$query6 = mysql_query($sql6) or die(mysql_error());
								if(mysql_num_rows($query6) > 0){
									echo "Movie Name:<a href='movie.php?mid=".$fkey."'>".mysql_result($query6,0,'title').'</a><br/>';
								}
								$limit++;
								if($limit == 10)
									break;
							}
						}
							// Fuzzy Search
						else{
							echo "No results found.<br/>";
						}
						}
					}
				}
				echo "</div>";
				echo "<div id='newmovies'>";
				require 'newmovies.php';
				echo "</div>";

			}
		?>
	</body>
<html>