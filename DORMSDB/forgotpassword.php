<?php
require_once 'header.php';

destroySession();



echo "<title>DORMSdb Password Reset</title><div id = 'main'><h1>Forgot your password?</h1><br />";

if (isset ($_POST['resetemail'])){

$resetemail= $_POST['resetemail'];

$resetuser= mysql_fetch_row (mysql_query("SELECT forename, surname, email, token FROM users WHERE email= '$resetemail'"));
if (!$resetuser) {
echo "Something went wrong, try again later.";
}

else {


$resetforename=$resetuser[0];
$resetsurname=$resetuser[1];
$confirmedemail=$resetuser[2];

$url= $SUPERrooturl . "forgotpassword.php?email=$resetemail&token=$resetuser[3]";
$to      = $confirmedemail;
$subject = "$resetforename $resetsurname: Your $school DORMSdb Password Reset";
$message = "Hello $resetforename, \r\n
\t Follow this link to reset your $school DORMSdb password:\r\n
$url \r\n\r\n
Have a great day! \r\n\r\n
Alex Mitchell \r\n
DORMSdb";
$headers = 'From: dormsdb@alexthemitchell.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

Echo "A password reset email has been sent to $confirmedemail.";

}



}

else if (isset ($_POST['newpassword'])) {
	$password = sanitizeString($_POST['newpassword']);
	$vpassword = sanitizeString($_POST['verifynewpassword']);

	if ($password == "") $error .= "You must enter a password.<br />";
	if ((!preg_match('/[a-z]/', $password)) || (!preg_match('/[A-Z]/', $password)) || (!preg_match('/[0-9]/', $password))) $error .= "Password must contain at least one uppercase, one lowercase, and one number<br />";
	if (strlen($password)< 8) $error .= "Your password must be at least 8 characters long.<br />";
    if ($vpassword == "") $error .= "Please verify your password.<br />";
    if ($password !== $vpassword) $error .= "Your passwords did not match.<br />";

    if ($error == "")
    {
        $newtoken= better_crypt($password);
        $formemail=$_POST['formemail'];
        if (!mysql_query("UPDATE users SET token = '$newtoken' WHERE email='$formemail'"))
        {
            @mail($dbcontact, "Query Failure: " . mysql_error(), "Failed Registration");
            die ("We're sorry, there was a problem with your password update. The error has been reported, but your password was not changed. Please try again later. <br /><br /><a href='index.php'>Go Home</a>");
        }
        else
        die ("<h4>Password Updated!</h4>Please <a href='logon.php'>Log On.</a><br /><br />");
    }
    else echo "<span class='unacceptable'>There was a problem with your password.<br /> $error<br /></span>";
}

else if (isset ($_GET['email']) and isset ($_GET['token'])) {
$email= sanitizeString($_GET['email']);
$token= sanitizeString($_GET['token']);

$previoustoken = mysql_fetch_row(mysql_query("SELECT token FROM users WHERE email='$email'"));


if ($token == $previoustoken[0]){

	echo "<form method='post' action='forgotpassword.php'>New Password: <input type='password' name='newpassword'><br />Verify: <input type='password' name='verifynewpassword'><input type=hidden name='formemail' value='$email'><br /><input type=submit value='Update Password'> </form>";

}

else echo "Something Went Wrong. Contact the site administrator.";
}





else echo "<br /><form method='post' action='forgotpassword.php'>Email: <input type='text' name='resetemail'><input type='submit' value='Reset Password!' /></form>";


require_once 'footer.php';
