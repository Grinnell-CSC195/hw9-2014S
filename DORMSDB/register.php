<?php

require_once "header.php";

		
		
echo "<title>DORMSdb Registration</title><div id='main'>";
$error = $forename = $surname = $email = $gradyear = $password = $vpassword = "";
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['submitted']))
{
    $forename= ucwords (strtolower(sanitizeString($_POST['forename'])));
    $surname= ucwords (strtolower(sanitizeString($_POST['surname'])));
    $email= strtolower(sanitizeString($_POST['email']));
    $gradyear= sanitizeString($_POST['gradyear']);
    $password= sanitizeString($_POST['password']);
    $vpassword= sanitizeString($_POST['vpassword']);
    if ($forename == "") $error .= "You must enter a first name.<br />";
  	if(preg_match('/[^a-zA-Z" "]/', $forename)) $error .= "Your first name may not contain numbers.<br />";
    if ($surname == "") $error .= "You must enter a last name.<br />";
    if(preg_match('/[^a-zA-Z" "]/', $surname)) $error .= "Your last name may not contain numbers.<br />";
    if (!(strpos ($email, "@grinnell.edu"))) $error .= "You must use your official @grinnell.edu email address to use DORMSdb.<br />";
    if ($email == "") $error .= "You must enter an email address.<br />";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error .= "Email address is invalid.<br />";
	if ($gradyear == "") $error .= "You must enter a graduation year.<br />";
    if(preg_match('/[^0-9]/', $gradyear)) $error .= "Your graduation year must be a four digit number.<br />";
    if(strlen($gradyear) !== 4) $error .= "Please enter your four digit graduation year.";
    //if (!(1900 < $gradyear < 2030)) $error .= "Your graduation year does not fall within our boundaries. Please try again when you're older.";


    if ($password == "") $error .= "You must enter a password.<br />";
	if ((!preg_match('/[a-z]/', $password)) || (!preg_match('/[A-Z]/', $password)) || (!preg_match('/[0-9]/', $password))) $error .= "Password must contain at least one uppercase, one lowercase, and one number<br />";
	if (strlen($password)< 8) $error .= "Your password must be at least 8 characters long.<br />";
    if ($vpassword == "") $error .= "Please verify your password.<br />";
    if ($password !== $vpassword) $error .= "Your passwords did not match.<br />";


    if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE email= '$email'")))
    $error = "That email already has an account registered. Try <a href='logon.php'>Logging On</a>";


    if ($error == "")
    {
        $token= better_crypt($password);
        $thistime= time();
        if (!mysql_query("INSERT INTO users(forename, surname, email, token, gradyear, type, signuptime, roompreferences) VALUES('$forename', '$surname', '$email', '$token', '$gradyear', 'Unverified', '$thistime', 'a:0:{}');"))
        {
            @mail($dbcontact,"Failed Registration", "Query Failure: " . mysql_error() );
            die ("We're sorry, there was a problem with your registration. The error has been reported, but your account was not created. Please try again later. <br /><br /><a href='index.php'>Go Home</a>");
        }
        else
        $url= $SUPERrooturl . "logon.php?verifyemail=$email&verifytoken=$token";
		$to      = $email;
		$subject = "$forename $surname: Your $school DORMSdb Account Verification";
		$message = "Hello $forename, \r\n
			Follow this link to verify your $school DORMSdb account:\r\n
			$url \r\n\r\n
			Have a great day! \r\n\r\n
			Alex Mitchell \r\n
			DORMSdb";
		$headers = 'From: dormsdb@alexthemitchell.com' . "\r\n" .'Reply-To : mitchell17@grinnell.edu'. "\r\n" .'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
        die ("<h4>Account Created!</h4>Please check your email to verify your account. This may take a few minutes.<br /><br />");
    }
    else echo "<span class='unacceptable'>There was a problem with your registration.<br /> $error<br /></span>";
}

echo <<<_END
<h3> Please enter your details to sign up</h3>
<form method="POST" action="register.php">

<span class='fieldname'>First Name:</span>
<input type="text" name="forename" size="20" maxlength="20" value='$forename' onBlur='checkName(this,fnamecheck)' /><span id='fnamecheck'></span><br />

<span class='fieldname'>Last Name:</span>
<input type="text" name="surname" size="20" maxlength="20" value='$surname'  onBlur='checkName(this,snamecheck)'><span id='snamecheck'></span><br />

<span class='fieldname'>Email Address:</span>
<input type="text" name="email" size="20" value='$email' onBlur='checkEmail(this)'><span id='emailcheck'></span><br />

<span class='fieldname'>Graduation Year:</span>
<input type="text" name="gradyear" size="4" maxlength="4" value='$gradyear' onBlur='checkGradyear(this)' /><span id='gyearcheck'></span><br />

<span class='fieldname'>Password:</span>
<input type="password" name="password" size="20" value='$password' onBlur='checkPassword(this)'  /><span id='pwordcheck'></span><br />

<span class='fieldname'>Verify Password:</span>
<input type="password" name="vpassword" size="20" onBlur='verifyPassword(password, this)' /><span id='vpasscheck'></span><br />

<input type="hidden" name="submitted" value="yes">

<span class='fieldname'>&nbsp;</span>
<input type="submit" value="Register!" />
</form><br />


_END;






require_once "footer.php";