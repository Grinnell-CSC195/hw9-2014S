<?php

require_once "header.php";
echo "<title>Contact DORMSdb</title><div id='main'>";
if ((isset ($_POST['name'])) && (isset ($_POST['email'])) && (isset ($_POST['subject']) && ($_POST['content'])))
{

$to      = $contentcontact;
$subject = sanitizeString($_POST['subject']);
$from= sanitizeString($_POST['email']);
$message = sanitizeString($_POST['content']);
$headers = 'From: DORMSdb@alexthemitchell.com' . "\r\n" .
'Reply-To: '. $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

    echo "Your message has been sent.
    <br /><br />
    <a href='index.php'>Return To Home Page</a>";

}

else
{
$email= $_SESSION['user'];
echo <<<_END
<form method="post" action="contact.php">
<b>All Fields required.</b>
<br /><br />
Your Name: <br />
<input type="text" name="name" onBlur='checkName(this,namecheck)'><span id='namecheck'></span><br />
Your Email Address: <br />
<input type="text" name="email" size="23" value='$email' onBlur='validateEmail(this)'><span id='emailcheck'></span><br />
Subject:<br />
<input type="text" name="subject" size="30" value="$subject">
<br /><br />
Your Message: <br />
<textarea name="content" cols="100" rows="10" wrap="hard">
</textarea>
<br /><br />

<input type="submit" value="Send">
</form>






_END;



}

require_once "footer.php";

