<?php

if ($_GET['room'] == '')
{
	header( 'Location: index.php' ) ;
}

else
{
require_once 'header.php';
if (!$loggedin)
{
$path .= "?room=$room";
echo <<<_END
<div id="main">
<h2>You must be logged on to view details on this room.</h2><br />
<h4><a href='logon.php?refer=/room.php?room=$room'>Log in</a> or <a href='register.php'>Sign Up</a></h4>


_END;
}



else
{
echo <<<_END
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=517763838330880";
  fjs.parentNode.insertBefore(js, fjs)
}(document, 'script', 'facebook-jssdk'));</script>
_END;
$room = $_GET['room'];
$roomblob=mysql_fetch_row(mysql_query("SELECT roompreferences FROM users WHERE email='$user'"));
$roomarray = unserialize($roomblob[0]);
if (isset($_POST['add']))
{
$added=sanitizeString($_POST['add']);
$roomarray[]= $added;
$roomblob= serialize($roomarray);
mysql_query("UPDATE users SET roompreferences='$roomblob' WHERE email='$user'");
}
if (isset($_POST['remove']))
{
	unset($roomarray[array_search($_POST['remove'],$roomarray)]);
	$roomarray=array_values($roomarray);
	$roomblob= serialize($roomarray);
	mysql_query("UPDATE users SET roompreferences='$roomblob' WHERE email='$user'");

}

$favoriteposition=array_search($room, $roomarray)+ 1;

$favoritetext= in_array($room, $roomarray) ? "This room is #$favoriteposition in your Favorites. <form method='POST' action='room.php?room=$room'><input type='hidden' name='remove' value='$room'><input type='submit' value='Remove it'></form>" : "This room isn't in your Favorites. <form method='POST' action='room.php?room=$room'><input type='hidden' name='add' value='$room'><input type='submit' value='Add it'></form>";




	$row= mysql_fetch_row(mysql_query("SELECT hall,room,type,rating,subfree,ac,laundry,printer,campus FROM rooms NATURAL JOIN halls WHERE room='$room'"));
	$ac= booltoYN ($row[5]);
	$laun= booltoYN ($row[6]);
	$print= booltoYN ($row[7]);
	$type= uncontract ($row[2], $row[1]);
	$rating= ratingstar($row[3]);
	$roomnum= substr($row[1], 0, 4);
	$mainpicture= $room . "main.jpg";
	$mainpicturethumb= $room . "mainthumb.jpg";

echo <<<_END
<title>$school: $row[0] $roomnum</title>



<div id="main">

<div id="main-picture">
<a href="images/$mainpicture"><img src="images/$mainpicturethumb" onerror="this.src='images/roomthumbdefault.jpg'" height=250 width=250 /></a>
</div>

<div id="thumbnails">
<!--<img src="images/$picture" height=30 width=30><img src="images/$room" height=30 width=30><img src="images/$room" height=30 width=30><img src="images/$room" height=30 width=30>-->
</div>

<div id="roomname">
<h1>$row[0] $roomnum</h1> $favoritetext
</div>

<div id="rating">
<h1>$rating</h1>
</div>

<div id="room-attributes">
	<div id="room-type">
		Type: $type
	</div>
	<div id="room-campus">
		Campus: $row[8]
	</div>
	<div id="room-ac">
		Air Conditioning: $ac
	</div>
	<div id="room-laundry">
		Laundry: $laun
	</div>
	<div id="room-printer">
		Printer: $print
	</div>
</div>



_END;
$result=mysql_query("SELECT comment, lived, forename FROM reviews, users WHERE reviews.user = users.email AND room='$room' AND approved='1'");
$rows = mysql_num_rows($result);
if ($rows != 0)
{
    for ($j = 0 ; $j < $rows ; ++$j)
        {
			$row=mysql_fetch_row($result);
			$comment=$row[0];
			$name=$row[2];
			switch ($row[1])
			{
				case(1):
					$lived= "(Lived Here)";
					break;
				case(0):
					$lived= "(Guest)";
					break;
			}
			echo <<<_END
			<div class='room-review'>
			<i><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$comment</b></i> <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-$name $lived<br /><br />
			</div>

_END;
		}

}

if (isset ($_FILES['image']['name']))
{
if ($_FILES['image']['error'] == 4)
{
    echo <<<_END
    <div id='picture-request'>
    <div class="fb-like" data-href="https://dormsdb.alexthemitchell.com/room.php?room=$room" data-layout="standard" data-action="recommend" data-show-faces="true" data-share="true"></div><br />

    Know about this room? <a href='review.php?room=$room'>Review it!</a><br />You must select an image. <br />
<form method = 'post' action='room.php?room=$room' enctype='multipart/form-data'>
<input type='file' name='image' size='14' maxlength='32' />
<input type='submit' value='Submit for approval' /></div>"
_END;
}
else
{
	$filename="/images/pending/". $room . '_' . time().".jpg";
    $saveto=__DIR__ . $filename ;
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok= TRUE;

	switch ($_FILES['image']['type'])
	{
		case "image/gif": 	break;
		case "image/jpeg": 	break;
		case "image/pjpeg": break;
		case "image/png": 	break;
		default: 			$typeok= FALSE; break;
	}


    if ($typeok)
    {
    	mysql_query("INSERT INTO contentreview(type, user, content, roomid) VALUES('image', '$user', '$filename', '$room');");
        echo "<div id='picture-request'>Know about this room? <a href='review.php?room=$room'>Review it!</a><br />Thank you for your submission. It will be reviewed and posted shortly.</div>";
    }
    else
    {
        echo <<<_END
        <div id='picture-request'>
    <div class="fb-like" data-href="https://dormsdb.alexthemitchell.com/room.php?room=$room" data-layout="standard" data-action="recommend" data-show-faces="true" data-share="true"></div><br />

        <div id='picture-request'>Know about this room? <a href='review.php?room=$room'>Review it!</a><br />Not a valid image file. Please select a .GIF, .JPEG, or .PNG file.<br />
<form method = 'post' action='room.php?room=$room' enctype='multipart/form-data'>
<input type='file' name='image' size='14' maxlength='32' />
<input type='submit' value='Submit for approval' />
</form>
</div>"
_END;
    }
}

}
else
{
	echo <<<_END
	<div id='picture-request'>
	<div class="fb-like" data-href="https://dormsdb.alexthemitchell.com/room.php?room=$room" data-layout="standard" data-action="recommend" data-show-faces="true" data-share="true"></div><br />
Know about this room? <a href='review.php?room=$room'>Review it!</a><br />
Do you have a picture of this room? <br />
<form method = 'post' action='room.php?room=$room' enctype='multipart/form-data'>
<input type='file' name='image' size='14' maxlength='32' />
<input type='submit' value='Submit for approval' />
</form><br />
</div>
_END;
}

}
}


require_once 'footer.php';
