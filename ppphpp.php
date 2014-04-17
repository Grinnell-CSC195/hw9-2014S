<!DOCTYPE html>
<html>
<body>

<body style="font-family:comic sans ms; background-color:darkgreen" >


<h1> phphphphphphphphphph </h1>

<?php
echo date("r")
?>

<?php
//thank you http://www.tizag.com/phpT/forloop.php
$brush_price = 5;

echo "<table border=\"1\" align=\"center\">";
echo "<tr><th>Hi</th>";
echo "<th>Hey</th></tr>";
for ( $counter = 6; $counter <= 128; $counter += 10) {
        echo "<tr><td>";
        echo $counter;
        echo "</td><td>";
        echo $brush_price * $counter;
        echo "</td></tr>";
}
echo "</table>";
?>

</body>
</html>
