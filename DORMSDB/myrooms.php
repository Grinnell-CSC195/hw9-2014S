<?php
require_once 'header.php';
echo "<title>$user's DORMSdb Rooms</title>";

if (!$loggedin)
{
$path .= "?room=$room";
echo <<<_END

<div id="main">
<h2>You must be logged on to view details on this room.</h2><br />
<h4><a href='logon.php?refer=/myrooms.php'>Log in</a> or <a href='register.php'>Sign Up</a></h4>


_END;
}



else
{
$roomblob=mysql_fetch_row(mysql_query("SELECT roompreferences FROM users WHERE email='$user'"));
$roomarray= unserialize($roomblob[0]);
if (isset($_POST['remove']))
{

	unset($roomarray[$_POST['remove']]);
	$roomarray=array_values($roomarray);
	$roomblob= serialize($roomarray);
	mysql_query("UPDATE users SET roompreferences='$roomblob' WHERE email='$user'");

}

echo "<div id='main'>";

$roomblob=mysql_fetch_row(mysql_query("SELECT roompreferences FROM users WHERE email='$user'"));
$roomarray= unserialize($roomblob[0]);



$roomarraylength=count($roomarray);

if ($roomarraylength !==0){

echo "<ol>";
    for ($j = 0 ; $j < $roomarraylength ; ++$j)
        {
        	$roomnum=substr($roomarray[$j], 0, 4);
        	$hall=mysql_fetch_row(mysql_query("SELECT hall FROM rooms WHERE room='$roomarray[$j]'"));
        	echo "<li><a href='room.php?room=$roomarray[$j]'>$hall[0] $roomarray[$j]</a><form method='POST' action='myrooms.php'><input type='hidden' name='remove' value='$j'><input type='submit'value='Remove'></li></form>";

		}

echo "</ol>";
echo "<br /><br />";

}

else echo "You haven't added any rooms to your preferred rooms. <a href='index.php'>Search for rooms now!</a>";


}
require_once 'footer.php';
