<?php
include('config.php');
global $database;
$active = $database->getActiveList();
$guest = $database->getGuestList();
$blah = mysql_numrows($active);
if(!$blah == '0') {
   echo 'Registered Users: ';
   while($salt = mysql_fetch_array($active)) {
      echo $salt['username'].' ';
   }
   echo '<br>';
}
echo 'There are '.$blah.' active and '.$guest.' guest user(s) viewing this site.';
?>