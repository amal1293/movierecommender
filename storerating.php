<?php
	session_start();
	require 'connect.php';
	if(isset($_GET['rating']) && isset($_GET['mid'])){
		$sql = "SELECT * FROM ratings WHERE userid='".$_SESSION['uid']."' AND movieid='".$_GET['mid']."'";
		$query = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($query) == 0){
			$sql2 = "INSERT INTO ratings(userid,movieid,rating) VALUES('".$_SESSION['uid']."','".$_GET['mid']."','".$_GET['rating']."')";
			$query2 = mysql_query($sql2) or die(mysql_error());
			$sql10 = "SELECT avgrating,no_voters FROM movies WHERE id='".$_GET['mid']."'";
			$query10 = mysql_query($sql10) or die(mysql_error());
			$no_voters = mysql_result($query10,0,'no_voters');
			$avgrating = mysql_result($query10,0,'avgrating');
			$newrating = (($avgrating*$no_voters)+$_GET['rating'])/($no_voters+1);
			$voters = $no_voters+1;
			$sql11 = "UPDATE movies SET avgrating=".$newrating." , no_voters=".$voters." WHERE id='".$_GET['mid']."'";
			$query11 = mysql_query($sql11) or die(mysql_error());
		}
		//a -> Rated Movie
		//b -> All other Movies rated by same user
		$sql3 = "SELECT DISTINCT b.movieid as othermovieid,a.rating-b.rating as diff FROM ratings a,ratings b WHERE b.userid='".$_SESSION['uid']."' AND a.userid='".$_SESSION['uid']."' AND a.movieid='".$_GET['mid']."' AND b.movieid!='".$_GET['mid']."'";
		$query3 = mysql_query($sql3) or die(mysql_error());
		if(mysql_num_rows($query3) > 0){
			while($result = mysql_fetch_assoc($query3)){
				$diff = $result['diff'];
				$sql4 = "SELECT movie1 FROM compare WHERE movie1='".$_GET['mid']."' AND movie2='".$result['othermovieid']."'";
				$query4 = mysql_query($sql4) or die(mysql_error());
				if(mysql_num_rows($query4) > 0){
					$sql5 = "UPDATE compare SET users_rated_both=users_rated_both+1, sum=sum+$diff WHERE movie1 = '".$_GET['mid']."' AND movie2 = '".$result['othermovieid']."'";
					$query5 = mysql_query($sql5) or die(mysql_error());
				}
				else{
					$sql6 = "INSERT INTO compare(movie1,movie2,users_rated_both,sum) VALUES('".$_GET['mid']."','".$result['othermovieid']."',1,'".$diff."')";
					$query6 = mysql_query($sql6) or die(mysql_error());
				}
				$sql7 = "SELECT movie1 FROM compare WHERE movie1='".$result['othermovieid']."' AND movie2='".$_GET['mid']."'";
				$query7 = mysql_query($sql7) or die(mysql_error());
				if(mysql_num_rows($query7) > 0){
					$sql8 = "UPDATE compare SET users_rated_both=users_rated_both+1,sum=sum-$diff WHERE movie1='".$result['othermovieid']."' AND movie2 = '".$_GET['mid']."'";
					$query8 = mysql_query($sql8) or die(mysql_error());
				}
				else{
					$sql9 = "INSERT INTO compare(movie1,movie2,users_rated_both,sum) VALUES('".$result['othermovieid']."','".$_GET['mid']."',1,'".-1*$diff."')";
					$query9 = mysql_query($sql9) or die(mysql_error());
				}
			}
		}
	}
?>