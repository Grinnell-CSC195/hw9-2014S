 <!DOCTYPE html>
<html>
<body>

<?php

$dir = "pics";
$good_ext = array(".jpg",".gif");

if ($handle = opendir($dir)) {
while (false!== ($file = readdir($handle))) {
$ext = strrchr($file,".");
echo $ext."<br>";
if(in_array($ext,$good_ext))
{
//do something with file
echo '<img src="pics/'.$file.'"/>';
}
else
{
echo "<br>";
}
}
closedir($handle);
}
else
{
echo "Directory does not exist!";
}
?> 
//cited source: http://www.webmasterworld.com/forum88/13257.htm
</body>
</html>
