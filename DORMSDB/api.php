<?php
require_once 'login.php';
if (isset ($_GET['documentation']))
echo <<<_END

DOCUMENTATION for DORMSdb API:
Retrieve file contents for https://dormsdb.alexthemitchell.com/api.php?documentation=true

Everything else requires a POST request to https://dormsdb.alexthemitchell.com/api.php


ROOM QUERIES:

Returns: Hall, Number, Type, Rating, Raters, Gender, AC, Laundry, Printer, Campus, Cluster, Subfree

?roomquery=true

appended with your query:

?roomquery=true -- Only option, must do this to get room results.

&ac=BOOL -- Searches for Air Conditioning, may be 1 or 0. Not including this does not affect search results.

&laundry=BOOL -- Searches for Laundry Facilities, may be 1 or 0. Not including this does not affect search results.

&printer=BOOL -- Searches for printers and computer labs, may be 1 or 0. Not including this does not affect search results.

&campus=STRING -- Searches for rooms in a specific campus division, may be NORTH, SOUTH, or EAST. Not including this does not affect search results.

&floor=INT -- Searches for room on a specified floor, may be 0 (for pit), 1, 2, 3, or 4. Not including this does not affect search results.

&type=STRING -- Searches for specific type of room based on the DORMSdb Official RoomType chart. Not including this does not affect search results.

&subfree=BOOL -- Searches for subfree housing, may be 1 or 0. Not including this does not affect search results.


//Not yet implimented.

(&gender=STRING -- Searches for gender specific housing, may be M for MALE ONLY, F for FEMALE ONLY, N for GENDER NEUTRAL or C for CO-ED. Not including this does not affect search results.)




USER INFORMATION:


?user=true&email=[USEREMAIL]&password=[USERPASSWORD]


Returns FALSE if email/password combination is incorrect.


User information:

Append &info=true

Returns: forename, surname, email, gradYear, roomPreferences, typeOfUser


Add room to myRooms:

Append &addroomtofavorites=[ROOMID]

Returns: "ROOMPREFERENCES UPDATED" on success, or a specific error on failure.



_END;


// ROOM QUERY API
elseif(isset($_GET['roomquery'])) {

@header ("content-type: text/json charset=utf-8");



//Set our variables
$ac=      			sanitizeString($_GET['ac']);
$laundry= 			sanitizeString($_GET['laundry']);
$printer= 			sanitizeString($_GET['printer']);
$campus=  			sanitizeString($_GET['campus']);
$floor=   			sanitizeString($_GET['floor']);
$type=    			sanitizeString($_GET['type']);
$room=    			sanitizeString($_GET['room']);
$subfree= 			sanitizeString($_GET['subfree']);
$gender= 			sanitizeString($_GET['gender']);



$query = "SELECT * FROM rooms NATURAL JOIN halls WHERE ";



if ('' !== $room)
{
    $query = $query . "room LIKE '" . sanitizeString($_GET['room'])."%';";
}

else
{

    if ('' !== $type) 		$query .= "type LIKE '$type%' AND ";
    if ('' !== $floor) 		$query .= "room LIKE '$floor' AND ";
    if ('' !== $ac)			$query .= "ac = $ac AND ";
    if ('' !== $laundry)	$query .= "laundry = $laundry AND ";
    if ('' !== $printer)	$query .= "printer = $printer AND ";
    if ('' !== $campus)		$query .= "campus = '$campus' AND ";
	if ('' !== $subfree)	$query .= "subfree = $subfree AND ";
    $query = $query . "1;";
}





//Run our query
$result = mysql_query($query);

//Preapre our output

$rooms = array();
while($thisroom = mysql_fetch_array($result, MYSQL_ASSOC)) {
$rooms[] = array('room'=>$thisroom);
}

$output = json_encode(array('rooms' => $rooms));




//Output the output.
echo $output;

}

else if (
	(isset($_POST['user'])) &&
	(isset($_POST['email'])) &&
	(isset($_POST['password']))
)
{
	$email=     sanitizeString($_POST['email']);
	$password= 	sanitizeString($_POST['password']);

	$token=

	$user=mysql_fetch_row(mysql_query("SELECT token, forename, surname, email, gradyear, roompreferences, type  FROM users WHERE email='$email'"));

	if (!$user || (!(crypt($password, $user[0]) == $user[0]))) die ("FALSE");


// ROOM ADD TO FAVORITES
	if(isset($_POST['addroomtofavorites']))
	{
	$roomnumber= sanitizeString($_POST['addroomtofavorites']);
	if (!mysql_fetch_row(mysql_query("SELECT room FROM rooms WHERE room='$roomnumber'"))) die ("ROOM DOES NOT EXIST");

	$array=unserialize($user[5]);

	array_push($array, $roomnumber);

	$array=serialize($array);

	if(!mysql_query("UPDATE users SET roompreferences = '$array' WHERE email='$user[3]'")) die ("DATABASE ERROR");

	die ("ROOMPREFERENCES UPDATED");

	}


// USER INFORMATION FUNCTION
	if(isset($_POST['info']))
	{
		$userarray= array(
	'forename' 	=> 	$user[1],
	'surname' 	=>  $user[2],
	'email' 	=>  $user[3],
	'gradyear' 	=>  $user[4],
	'roompreferences' 	=>  unserialize($user[5]),
	'usertype' => $user[6]);

		$output = json_encode($userarray);

		echo $output;
	}

}
