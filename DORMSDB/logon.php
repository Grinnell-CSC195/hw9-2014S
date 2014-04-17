<?php
$refer= $_GET['refer'];
$validate= $_GET['validate'];
$verifyemail= $_GET['verifyemail'];
$verifytoken= $_GET['verifytoken'];




require_once "header.php";

echo "<title>$school DORMSdb Logon</title><div id='main'>";
if ($refer=='') $refer='index.php';

if ($validate==1)
{

		$validateuser= mysql_fetch_row(mysql_query("SELECT forename, surname, email, token FROM users WHERE email='$user'"));

		$url= $SUPERrooturl . "logon.php?verifyemail=$validateuser[2]&verifytoken=$validateuser[3]";
		$to      = $validateuser[2];
		$subject = "$validateuser[0] $validateuser[1]: Your $school DORMSdb Account Verification";
		$message = "Hello $validateuser[0], \r\n
			Follow this link to verify your $school DORMSdb account:\r\n
			$url \r\n\r\n
			Have a great day! \r\n\r\n
			Alex Mitchell \r\n
			DORMSdb";
		$headers = 'From: dormsdb@alexthemitchell.com' . "\r\n" .'Reply-To : mitchell17@grinnell.edu'. "\r\n" .'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
        die ("<h4>Email Resent!</h4>$to has been sent a validation email.<br /><br />");

}



if (isset ($verifyemail) && isset($verifytoken))
{

$servertoken = mysql_fetch_row(mysql_query("SELECT token FROM users WHERE email='$verifyemail'"));


if ($verifytoken == $servertoken[0]){

	mysql_query("UPDATE users SET type='User' WHERE email='$verifyemail'");
	echo "You have successfully authenticated your account!<br />";

}

else echo "Something Went Wrong. Contact the site administrator.<br/>";
}



if (isset ($_POST['submitted']))
{
    $email= strtolower(sanitizeString ($_POST['email']));
    $user= mysql_fetch_row( mysql_query ("SELECT * FROM users WHERE email ='" . $email . "';"));
        if (!$user)
        {
            echo <<<_END
             Invalid Username/Password combination.<br /> <br />

                    <br />
                    <form method="POST" action="logon.php?refer=$refer">
                    <br />
                    Email: <br />
                    <input type="text" name="email" value="$email"><br />
                    Password: <br />
                    <input type="password" name="password" size="23"><br />


                    <input type="hidden" name="submitted" value="yes">

                    <input type="submit" value="Log On!"><br />
                    <a href="register.php">Register Here</a><br />
                    <a href="forgotpassword.php">Forgot your password?</a>
                    </form>


_END;
        }
        else
        {
            if (crypt(sanitizeString ($_POST['password']), $user[3]) == $user[3])
            {
                $_SESSION['user']=$email;
                $_SESSION['password']=$user[3];
                $_SESSION['usertype']=$user[5];
                die ("
<meta HTTP-EQUIV='REFRESH' content='0; url=$refer'>");
            }
            else
            {
                echo <<<_END
Invalid Username/Password combination.
<br />
<form method="POST" action="logon.php?refer=$refer">
<br />
Email: <br />
<input type="text" name="email" value="$email"><br />
Password: <br />
<input type="password" name="password" size="23"><br />
<input type="hidden" name="submitted" value="yes">
<input type="submit" value="Log On!"><br />
                    <a href="register.php">Register Here</a><br />
                    <a href="forgotpassword.php">Forgot your password?</a>
</form>

_END;
            }
        }
}

else
{
destroySession();
echo <<<_END


<br />
<form method="POST" action="logon.php?refer=$refer">
<br />
Email: <br />
<input type="text" name="email"><br />
Password: <br />
<input type="password" name="password" size="23"><br />


<input type="hidden" name="submitted" value="yes">

<input type="submit" value="Log On!"><br />
                    <a href="register.php">Register Here</a><br />
                    <a href="forgotpassword.php">Forgot your password?</a>
</form>

_END;



}


require_once "footer.php";
