<?php
require_once 'login.php';

if (isset($_POST['email']))
{
	$email = sanitizeString($_POST['email']);
	if (!(strpos ($email, "@grinnell.edu"))) echo "<span class='unacceptable'>&nbsp;&#x2718; Please use your official @grinnell.edu email address.</span>";
	else if (filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE email='$email';")))
		{
		echo "<span class='unacceptable'>&nbsp;&#x2718; Account already exists. <a href='logon.php'>Log On instead.</a></span>";
		}
		else
		{
		echo "<span class= 'acceptable'>&nbsp;&#x2714; </span> ";
		}
	}
	else echo "<span class='unacceptable'>&nbsp;&#x2718; This is not a valid email address.";
}
