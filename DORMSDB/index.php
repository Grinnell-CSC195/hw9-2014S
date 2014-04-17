<?php
require_once 'header.php';

echo <<<_END
<title>$school DORMSdb</title>
<div id="main">
<center>
<h3>My Perfect Room...</h3>

<span class='checklist'>
<form method="GET" action="search.php">
<label>
<input type="checkbox" name="ac" value="checked" class='checklist'>Has Air Conditioning.            <br />   <br /></label><label>
<input type="checkbox" name="laundry?" value="checked" class='checklist'>Its building has Laundry Machines. <br /><br /></label><label>
<input type="checkbox" name="printer?" value="checked" class='checklist'>Its building has a printer.      <br />  <br /></label>
<label><input type="checkbox" name="subfree" value="checked" class='checklist'>Is Substance Free.</label><br /><br />
</span>
Is located on <select name="campus" size="1">
<option value=""></option>
<option value="North">North</option>
<option value="South">South</option>
<option value="East">East</option>
</select> campus.<br />
<br />
Is on the <select name="floor" size="1">
<option value=""></option>
<option value="0">Pit</option>
<option value="1">First</option>
<option value="2">Second</option>
<option value="3">Third</option>
<option value="4">Fourth</option>
</select> floor. <br />
<br />
Is a <select name="type" size="1" >
<option value=""></option>
<option value="S">Single</option>
<option value="SC"> -Cubby</option>
<option value="D">Double</option>
<option value="D2">-Two-Room</option>
<option value="D3">-Three-Room</option>
<option value="T">Triple</option>
<option value="T2">-Two-Room</option>
<option value="Q">Quad</option>
<option value="AS">Student Advisor</option>
<option value="HWC">Hall Wellness Coordinator</option>
<option value="COOP">Co-Op</option>
<option value="APART">Apartment Living</option>
</select>.<br /><br />
<h5>Or search by room number: <input type="text" name="room" size="4" maxlength="4" /></h5><br /><br />

<input type="hidden" name="results" value="yes">

<input type="submit" value="Search!">
</form>


</center>
_END;

require_once 'footer.php';
?>

