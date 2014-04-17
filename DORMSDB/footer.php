<?php
mysql_close($link);
echo "
</div>
<br /><br /><br /><footer><p>$copyyear DORMSdb&nbsp&nbsp&nbsp&nbsp<a href='contact.php'>Contact Us!</a>
&nbsp&nbsp&nbsp&nbsp";





if (!$loggedin) echo '<a href="register.php">Sign Up for DORMSdb</a>&nbsp&nbsp&nbsp&nbsp';

echo '<a href="stats.php">Statistics</a>&nbsp&nbsp&nbsp&nbsp';

if ($_SESSION['usertype']=='Admin') echo '<a href="admin/index.php">Admin</a>';


echo'</p>
<a href="https://www.facebook.com/DORMSdb" target="_blank"><img src="./logos/fb.png"></a> &nbsp;
<a href="https://www.twitter.com/alexthemitchell" target="_blank"><img src="./logos/twitter.png" width="35" height="29"></a> &nbsp;
<a href="https://www.github.com/alexthemitchell" target="_blank"><img src="./logos/github.png"></a>
</footer>

</html>';

