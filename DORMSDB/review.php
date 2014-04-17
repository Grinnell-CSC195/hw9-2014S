<?php
$room=$_GET['room'];
if ($room == '')
{
	header( 'Location: /index.php');
}

else
{
require_once 'header.php';

if (!$loggedin)
{
echo <<<_END
<div id="main">
<h2>You must be logged on to review this room.</h2><br />
<h4><a href='logon.php?refer=/review.php?room=$room'>Log in</a> or <a href='register.php'>Sign Up</a></h4>
_END;

}

elseif ($_SESSION['usertype'] == "Unverified")
{

	echo <<<_END
<div id="main">
<h2>You must validate your account before you can review rooms. </h2><br />
Didn't get a validation email? <a href='logon.php?validate=1'>Click here to resend it!</a>
_END;

}


elseif ($previousreview=mysql_fetch_row(mysql_query("SELECT * FROM reviews WHERE user='$user' AND room='$room'")))
{
	$when= date("F jS, Y",$previousreview[8]);
	echo <<<_END
<div id="main">
<h3>You reviewed this room on $when.</h3><br />
<ul><li>Community: $previousreview[2].</li><li> Size: $previousreview[3].</li><li> View from Window: $previousreview[4].</li><li> Condition of Room: $previousreview[5].</li><li> Recommendation: $previousreview[6].</li></ul> <br /> <p>$previousreview[9]</p><br /><br /><center><a href="room.php?room=$room">Back to Room</a></center>
_END;
}



else
{
if ((isset ($_POST['community'])) && (isset ($_POST['size'])) && (isset ($_POST['window'])) && (isset ($_POST['good'])) && (isset ($_POST['recommend'])))
{
	$community=$_POST['community'];
	$size=$_POST['size'];
	$window=$_POST['window'];
	$good=$_POST['good'];
	$recommend=$_POST['recommend'];
	$comment= sanitizeString ($_POST['comment']);
	$submittedaverage = ($community + $size + $window + $good + $recommend) / 5;
	$date= time();
	$row=mysql_fetch_row(mysql_query("SELECT * FROM rooms WHERE room='$room'"));

	$newreviews= $row[4]+1;
	$newavg= (($row[3]*$row[4]) + $submittedaverage)/$newreviews;
	$lived=$_POST['lived'];
	mysql_query("INSERT INTO reviews(user, room, community, size, window, good, recommend, average, comment, date, lived) VALUES('$user', '$room','$community', '$size', '$window', '$good', '$recommend', '$submittedaverage','$comment', '$date', '$lived');");
	mysql_query("UPDATE rooms SET rating='$newavg', raters='$newreviews' WHERE room='$room';");
	if ($comment) mysql_query("INSERT INTO contentreview (type, user, content, roomid) VALUES ('review', '$user', '$comment', '$room');");

	echo "<div id='main'><center><h2>Thanks for your feedback. <a href='room.php?room=$room'>Back to room</a></h2></center>";


}
else
{


$row= mysql_fetch_row(mysql_query("SELECT * FROM rooms NATURAL JOIN halls WHERE room='$room'"));
$ac= booltoYN ($row[5]);
$laun= booltoYN ($row[6]);
$print= booltoYN ($row[7]);
$type= uncontract ($row[2], $row[1]);
$rating= ratingstar($row[3]);
$roomnum= substr($row[1], 0, 4);
$mainpicture= $room . "main.jpg";
$mainpicturethumb= $room . "mainthumb.jpg";
$user= $_SESSION['user'];

echo <<<_END

<div id='main'>
<div id='review-form'>
<form method="POST" action="review.php?room=$room">
<h1>Room Review for: $row[0] $roomnum</h1><br />
Please rate this room based on the following criteria.<br /><br />
<table border="1">
<tr>
<th></td><th> Strongly Disagree </th><th> Disagree </th><th> No opinion </th><th> Agree </th><th> Strongly Agree </th></tr>

<tr><td>This room's floor community is enjoyable.</td><td>
<input type="radio" name="community" value="1" />
</td><td>
<input type="radio" name="community" value="2" />
</td><td>
<input type="radio" name="community" value="3" />
</td><td>
<input type="radio" name="community" value="4" />
</td><td>
<input type="radio" name="community" value="5" />
</td></tr>

<tr><td>The size of this room is satisfactory.</td><td>
<input type="radio" name="size" value="1" />
</td><td>
<input type="radio" name="size" value="2" />
</td><td>
<input type="radio" name="size" value="3" />
</td><td>
<input type="radio" name="size" value="4" />
</td><td>
<input type="radio" name="size" value="5" />
</td></tr>

<tr><td>The view from this room's window was pleasurable.</td><td>
<input type="radio" name="window" value="1" />
</td><td>
<input type="radio" name="window" value="2" />
</td><td>
<input type="radio" name="window" value="3" />
</td><td>
<input type="radio" name="window" value="4" />
</td><td>
<input type="radio" name="window" value="5" />
</td></tr>

<tr><td>This room is in good shape overall.</td><td>
<input type="radio" name="good" value="1" />
</td><td>
<input type="radio" name="good" value="2" />
</td><td>
<input type="radio" name="good" value="3" />
</td><td>
<input type="radio" name="good" value="4" />
</td><td>
<input type="radio" name="good" value="5" />
</td></tr>

<tr><td>I would recommend this room to others.</td><td>
<input type="radio" name="recommend" value="1" />
</td><td>
<input type="radio" name="recommend" value="2" />
</td><td>
<input type="radio" name="recommend" value="3" />
</td><td>
<input type="radio" name="recommend" value="4" />
</td><td>
<input type="radio" name="recommend" value="5" />
</td></tr>
</table>

<br /><br />
Other Comments:<br />
<textarea name="comment" cols="100" rows="10" wrap="soft"></textarea>
<br />
<label><input type="checkbox" name="lived" value="1" /> I have lived in this room.</label><br />
<input type="submit" value="Submit!" />
</form>
</div>
_END;
}
}
require_once 'footer.php';
}


