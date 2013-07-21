<?
require 'connect.php';

echo "<span id='newtitle'>New Releases:</span><br/>";
$sql1 = "SELECT id,title,avgrating,no_voters FROM movies WHERE releasedate >= CURDATE() - INTERVAL 1 WEEK ORDER BY releasedate DESC,avgrating DESC";
$query1 = mysql_query($sql1) or die(mysql_error());
if(mysql_num_rows($query1) > 0){
	echo "<table border='0' id='newmtable'>";
	while($newmovies = mysql_fetch_assoc($query1)){
		if($newmovies['no_voters'] > 0){
			echo "<tr><td id='newmname'><a href='movie.php?mid=".$newmovies['id']."'>".$newmovies['title'].'</a></td><td>'.$newmovies['avgrating'].' ('.$newmovies['no_voters'].' votes)</td></tr>';
		}
		else{
			echo "<tr><td id='newmname'><a href='movie.php?mid=".$newmovies['id']."'>".$newmovies['title'].'</a></td><td>No votes Yet.</td></tr>';
		}
	}
}
else{
	echo "No New Releases.<br/>";	
}

?>