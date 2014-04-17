<?php

require_once 'config.php';

require_once 'login.php';

$copyyear = "\251".date("Y", time());

$time= time();


    // Original better_crypt PHP code by Chirp Internet: www.chirp.com.au
    // Please acknowledge use of this code by including this header.

  function better_crypt($input, $rounds = 7)
  {
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++) {
      $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
  }




function booltoYN ($bool)
{
    if (1 == $bool) return "Yes";
    else return "No";
}

function uncontract ($ins, $room)
{
    switch ($ins)
    {
        case "S":
            return "Single";
            break;
        case "SC":
            return "Cubby Single";
            break;
        case "D":
            return "Double";
            break;
        case "D2":
            return "Two-Room Double";
            break;
        case "D3":
            return "Three-Room Double";
            break;
        case "T":
            return "Triple";
            break;
        case "T2":
            return "Two-Room Triple";
            break;
        case "Q":
            return "Quad";
            break;
        case "AS":
            return "Student Advisor";
            break;
        case "HWC":
            return "Hall Wellness Coordinator";
            break;
        case "APART":
            return "Apartment Living";
            break;
        case "COOP":
            return "Co-Op";
            break;
        default:
            return "<a href='contact.php?subject=Room%20$room%20Type%20Report'>Unknown: Click here to report the correct type.</a>";
            break;


    }
}


session_start();

if (isset($_SESSION['user']))
{
    $user= $_SESSION['user'];
    $loggedin = TRUE;
}
else $loggedin= FALSE;

function ratingstar ($num)
{
    $num = round ( $num, 0, PHP_ROUND_HALF_UP);
    switch ($num)
    {
        case (1):
            $string = "<span style='color:red;'>"; break;
        case (2):
            $string = "<span style='color: orange;'>"; break;
        case (3):
            $string = "<span style='color: gold;'>"; break;
        case (4):
            $string = "<span style='color: #9ACD32;'>"; break;
        case (5):
            $string = "<span style='color: green;'>"; break;

    }
    for ($i=0; $i < $num; $i++)
    {
        $string .= "&#9733;";
    }
    for ($i=0; $i < (5-$num) ; $i++)
    {
        $string .= "&#9734;";
    }
    return $string . "</span>";
}

echo <<<_END
<!DOCTYPE html>
<html>
<head>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://dormsdb.alexthemitchell.com/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://dormsdb.alexthemitchell.com/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->





<!--
	All content found anywhere on this page or in its source code is $copyyear Alex Mitchell and DORMSdb. Inaccuracies and complaints can be directed directly to Alex at [mitchell17] or DORMSdb@alexthemitchell.com.
-->
<META NAME=author CONTENT="Alex Mitchell">
<META NAME=copyright CONTENT="$copyyear, Alex Mitchell">
<META name="keywords" content="Grinnell College Dorms, DORMSdb, Alex Mitchell, Dorm Management, Grinnell College">
<meta property="og:title" content="Grinnell College DORMSdb" />
<meta property="og:site_name" content="DORMSdb" />
<meta property="og:description" content="DORMSdb: The Perfect way to find your Perfect Room" />
<meta property="og:type" content="website" />
<meta property="og:image" content="https://dormsdb.alexthemitchell.com/logos/DORMSdbsquare.jpg" />
<meta property="fb:app_id" content="517763838330880" />

<style>
    @import url('styles.css');
</style>
<script type="text/javascript" src="functions.js"></script>

<link rel="shortcut icon" href="./logos/favicon.ico" />

</head>
<header>


<div id="logon-space">
_END;
if ($loggedin)
{
    echo "Logged in as $user <a href='myrooms.php'>myRooms</a> <a href='logout.php'>Log Out</a>";
}
else
{
echo <<<_END
<form method="POST" action="logon.php">
Email:
<input type="text" name="email" value="$email">
Password:
<input type="password" name="password" size="23">
<input type="hidden" name="submitted" value="yes">
<input type="submit" value="Log On!">
</form>

_END;
}

echo <<<_END
</div>
<a class="logo" href="index.php">
<div class="logo">
<img src="./logos/DORMSdb.png" height='50'> BETA
</div>
</a>
<br /><br />
</header>
_END;
