<?php

require_once 'header.php';
echo "<title>$school DORMSdb Search</title>";
$ac_= $_GET['ac'];
$laundry_= $_GET['laundry?'];
$printer_= $_GET['printer?'];
$campus_= $_GET['campus'];
$floor_= $_GET['floor'];
$type_= $_GET['type'];
$subfree_= $_GET['subfree'];


$room_= sanitizeString($_GET['room']);


switch ($campus_)
    {
        case (""):
            $nothingC= 'selected';
            break;
        case ("North"):
            $selectedNorth='selected';
            break;
        case ("South"):
            $selectedSouth='selected';
            break;
        case ("East"):
            $selectedEast='selected';
            break;
    }

switch ($floor_)
    {
        case (""):
            $nothing= 'selected';
            break;
        case (0):
            $selected0='selected';
            break;
        case (1):
            $selected1='selected';
            break;
        case (2):
            $selected2='selected';
            break;
        case (3):
            $selected3='selected';
            break;
        case (4):
            $selected4='selected';
            break;
    }

switch ($type_)
    {
    case (""):
            $nothingt= 'selected';
            break;
    case ("S"):
            $selectedS='selected';
            break;
    case ("SC"):
            $selectedSC='selected';
            break;
    case ("D"):
            $selectedD='selected';
            break;
    case ("D2"):
            $selectedD2='selected';
            break;
    case ("D3"):
            $selectedD3='selected';
            break;
    case ("T"):
            $selectedT= 'selected';
            break;
    case ("T2"):
            $selectedT2='selected';
            break;
    case ("Q"):
            $selectedQ='selected';
            break;
    case ("AS"):
            $selectedAS='selected';
            break;
    case ("HWC"):
            $selectedHWC='selected';
            break;
    case ("COOP"):
            $selectedCOOP='selected';
            break;
    case ("APART"):
            $selectedAPART='selected';
            break;
    }


echo <<<_END

<nav>
<form method="GET" action="search.php">
<label><input type="checkbox" name="ac" value="checked" $ac_>Air Conditioning</label><br />

<label><input type="checkbox" name="laundry?" value="checked" $laundry_>Laundry Machines</label> <br />

<label><input type="checkbox" name="printer?" value="checked" $printer_ >Printer</label><br />

<label><input type="checkbox" name="subfree" value="checked" $subfree_>Substance Free</label><br />

Located on <select name="campus" size="1">
<option value=""$nothingC></option>
<option value="North" $selectedNorth>North</option>
<option value="South" $selectedSouth>South</option>
<option value="East" $selectedEast>East</option>
</select> campus.<br />

On the <select name="floor" size="1">
<option value="" $nothing></option>
<option value="0"$selected0>Pit</option>
<option value="1"$selected1>First</option>
<option value="2"$selected2>Second</option>
<option value="3"$selected3>Third</option>
<option value="4"$selected4>Fourth</option>
</select> floor. <br />

A <select name="type" size="1" >
<option value="" $nothingT></option>
<option value="S" $selectedS>Single</option>
<option value="SC" $selectedSC> -Cubby</option>
<option value="D" $selectedD>Double</option>
<option value="D2" $selectedD2>-Two-Room</option>
<option value="D3" $selectedD3>-Three-Room</option>
<option value="T" $selectedT>Triple</option>
<option value="T2" $selectedT2>-Two-Room</option>
<option value="Q" $selectedQ>Quad</option>
<option value="AS" $selectedAS>Student Advisor</option>
<option value="HWC" $selectedHWC>Hall Wellness Coordinator</option>
<option value="COOP" $selectedCOOP>Co-Op</option>
<option value="APART" $selectedAPART>Apartment Living</option>
</select>.<br /><br />

<h2>OR</h2>
Room Number: <input type="text" name="room" size="4" maxlength="4" /><br /><br />

<input type="hidden" name="results" value="yes">

<input type="submit" value="Search!">
</form>
<br />


</nav>
<div id="main">
_END;

if (isset ($_GET['results']))
{
$query = "SELECT hall, room, type, campus, ac, laundry, printer, subfree, rating FROM rooms NATURAL JOIN halls WHERE ";


if ('' !== $_GET['room'])
{
    $query = $query . "room LIKE '" . sanitizeString($_GET['room'])."%';";
}

else
{

    if ('' !== $type_) $query .= "type LIKE '" . $type_ ."%' AND ";
    if ('' !== $floor_) $query .= "room LIKE '_" . $floor_ ."%' AND ";
    if (isset ($_GET['ac'])) $query .= "ac = 1 AND ";
    if (isset ($_GET['laundry?'])) $query .=  "laundry = 1 AND ";
    if (isset ($_GET['printer?'])) $query .= "printer = 1 AND ";
    if (isset ($_GET['subfree'])) $query .= "subfree = 1 AND ";
    if ('' !== ($_GET['campus'])) $query .=  "campus = '" . sanitizeString($_GET['campus']) . "' AND ";


    $query = $query . "1 ORDER BY rating DESC;";
}

$result = mysql_query($query);


if (!$result)
{
    echo "We're sorry, something is wrong. The error has been reported, and will be addressed shortly. Check back soon! <br /><br />
    <a href='index.php'>Return Home</a>";
 @mail($dbcontact, "Database access failed: " . mysql_error(), $query);
}
$rows = mysql_num_rows($result);


$roomplurality = ($rows == 1) ? "Room" : "Rooms";

echo "<h2>$rows $roomplurality found</h2>";
if ($rows == 0)
{
    echo "We're sorry, your search returned no rooms. Try a more broad search.";
}
else
{
    for ($j = 0 ; $j < $rows ; ++$j)
        {

	$row = mysql_fetch_row($result);
	$ac= booltoYN ($row[4]);
	$laun= booltoYN ($row[5]);
	$print= booltoYN ($row[6]);
	$type= uncontract ($row[2], $row[1]);
	$roomnum= substr($row[1], 0, 4);
	$rating= ratingstar($row[8]);
	$picture= $row[1]."search.jpg";


    $liac=  strpos ($query, "ac = 1") ?  "<font class='criteria'>" : "<font>";
    $lilaun=  strpos ($query, "laundry = 1") ?  "<font class='criteria'>" :  "<font>";
    $liprint= strpos ($query, "printer = 1") ?  "<font class='criteria'>" :  "<font>";
    $litype= strpos ($query, "type LIKE") ?  "<font class='criteria'>" :  "<font>";
    $licampus= strpos ($query, "campus = ") ?  "<font class='criteria'>" : "<font>";
    $liroom= strpos ($query, "room LIKE '") ?  "<font class='criteria'>" :  "<font>";
    $lihall= strpos ($query, "hall =") ?  "<font class='criteria'>" :  "<font>";
    echo <<<_END
    <a class="search-result" href="room.php?room=$row[1]">
    <div class="search-result">
        <div class="mainimage">
         <img src="images/$picture" onerror="this.src=''; alt='Image not available.'" height="100" width="100" />
        </div>
        <div class="name">
            <h2>$lihall $row[0]</font> $liroom $roomnum</font></h2>
        </div>
        <div class="rating">
        <h2>$rating</h2>
        </div>
        <div class="criteria">
            <div class="type">
                $litype Type: $type </font>
            </div>
            <div class="ac">
                $liac Air Conditioning? $ac </font>
            </div>
            <div class="printer">
                $liprint Printer? $print </font>
            </div>
            <div class="campus">
                $licampus Campus: $row[3] </font>
            </div>
            <div class="laundry">
                $lilaun Laundry? $laun </font>
            </div>

        </div>
    </div>
</a>
_END;
}
}


}
require_once 'footer.php';
