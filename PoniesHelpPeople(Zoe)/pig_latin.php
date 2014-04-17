<!DOCTYPE html>
<head>
<title>Pig Latin</title>
<link rel="stylesheet" type="text/css" href="stylesheetgame.css"/>
</head>
<html>
<body>


<?php
function vowelindex($str)
{
  $lowest = strlen($str);
  $vow = array("a", "e", "i", "o", "u", "y");
 
  
  /*
  for($x=0; $x<6; $x++)
    {
      if(isnumeric(stripos($str,$vow[$x])) && $lowest > stripos($str, $vow[$x]))
        $lowest = stripos($str, $vow[$x]);
      echo $lowest;
    }
  */
  
  if(is_numeric(stripos($str, "a")) && $lowest > stripos($str, "a"))
    $lowest = stripos($str, "a");
  
  if(is_numeric(stripos($str, "e")) && $lowest > stripos($str, "e"))
    $lowest = stripos($str, "e");
 
  if(is_numeric(stripos($str, "i")) && $lowest > stripos($str, "i"))
    $lowest = stripos($str, "i");
 
  if(is_numeric(stripos($str, "o")) && $lowest > stripos($str, "o"))
    $lowest = stripos($str, "o");
  
  if(is_numeric(stripos($str, "u")) && $lowest > stripos($str, "u"))
    $lowest = stripso($str, "u");
  
  if(is_numeric(stripos($str, "y")) && $lowest > stripos($str, "y"))
    $lowest = stripos($str, "y");
  
  

  return $lowest;
}


?>
   <?php 

   echo "<br><br>";
     $txt = (explode(" ",$_GET["txt"]));
     $max = sizeof($txt);

     for($x=0; $x<$max; $x++)
       {
         $temp = $txt[$x];
         $firstvow = vowelindex($temp);
         if ($firstvow === 0)
           {
             echo $temp . "-ay ";
           }    
         else{
           echo substr($temp, $firstvow) ."-". substr($temp, 0, $firstvow) . "ay ";
         }
    
       
   }

?>
</body>
</html> 