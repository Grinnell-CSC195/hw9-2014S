<?php
require_once 'header.php';
echo "<title>$school DORMSdb Statistics</title>";
if (!$loggedin)
{
	echo "<div id='main'>You must be logged in to access statistics.<br /><a href='logon.php?url=stats.php'>Log On</a>";

}
else
{
	function averagesql($query) {
		$result= mysql_query("SELECT rating FROM rooms NATURAL JOIN halls WHERE raters != 1 and $query;");
		$rows= mysql_num_rows($result);
		for ($i=0; $i < $rows; $i++)
		{
			$row= mysql_fetch_row($result);
			$sum = $sum + $row[0];
		}
		return $sum/$rows;
	}

	$numberofratings = mysql_num_rows(mysql_query("SELECT * FROM reviews"));

	echo "<div id='main'><h1>Averages ($numberofratings ratings)</h1>";
	echo "<h2>By Campus</h2><ul>";
	$result=mysql_query("SELECT DISTINCT campus FROM halls;");
	$rows=mysql_num_rows($result);
	for ($i=0; $i < $rows; $i++)
	{
		$row=mysql_fetch_row($result);
		$avg= averagesql("campus='$row[0]'");
		echo "<li>$row[0]: $avg</li><br />";
	}
	echo "</ul>";

	echo "<br /><h2>By Cluster</h2><ul>";
	$result=mysql_query("SELECT DISTINCT cluster FROM halls;");
	$rows=mysql_num_rows($result);
	for ($i=0; $i < $rows; $i++)
	{
		$row=mysql_fetch_row($result);
		$avg= averagesql("cluster='$row[0]'");
		echo "<li>$row[0]: $avg</li>";
	}
	echo "</ul>";

	echo "<br /><h2>By Hall</h2>";
	$result=mysql_query("SELECT DISTINCT hall FROM halls;");
	$rows=mysql_num_rows($result);
	echo "<ul>";
	for ($i=0; $i < $rows; $i++)
	{
		$row=mysql_fetch_row($result);
		$avg= averagesql("hall='$row[0]'");
		echo "<li>$row[0]: $avg</li>";
	}
	echo "</ul>";


	echo "<br /><h2>Top Five Rooms</h2>";
	$result=mysql_query("SELECT hall, room, rating FROM rooms WHERE raters != 1 ORDER BY rating DESC");
	echo "<ol>";
	for ($i=0; $i < 5; $i++)
	{
		$row=mysql_fetch_row($result);
		echo"<li><a href=room.php?room=$row[1]>$row[0] $row[1]</a> - $row[2]</li>";
	}
	echo "</ol>";


}

require_once 'footer.php';
