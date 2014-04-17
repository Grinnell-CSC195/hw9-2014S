<?php
//Debugging tools
/*
ini_set('display_errors',1);
error_reporting(E_ALL);
*/
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
ini_set('display_errors',0);
require_once 'config.php';

$db_hostname = 'localhost';

$db_database = 'alexqndk_DORMSdb';

$db_username = 'alexqndk_Alex';

$db_password = '8YigOaCdAhpz';

$link = mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database,$link);

//$link = mysql_connect($db_hostname, $db_username, $db_password);

if (!$link)
{
    echo "We're sorry, something is wrong. The error has been reported, and will be addressed shortly. Check back soon! <br /><br />
    <a href='index.php'>Return Home</a>";
 @mail($dbcontact, "Database access failed: " . mysql_error(), "Failed DB Link");
}


function sanitizeString($input)
{
    return mysql_real_escape_string(stripslashes(htmlentities(strip_tags($input))));
}

function destroySession()
{
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '');

    session_destroy();
}

function average()
{
    for($i=0;$i<func_num_args();$i++)
    {
        $total += func_get_arg($i);
    }
    return $total/func_num_args();

}