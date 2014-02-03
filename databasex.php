<?php

function postTav($tav, $now, $day, $postFix) {
   connection();
   /* This next line processes the query so we prevent SQL injection. */
   $tav = mysql_real_escape_string($tav); 
   $query = "INSERT INTO percenter (tav, date, day, postFix) VALUES ('$tav', '$now', '$day', '$postFix')"; 
   if(mysql_query($query)) { 
      echo '<br>Added.<br>';
   } else { 
      echo "Error:<br>".mysql_error(); 
   }
   mysql_close();
}

function grabFirst() {
   connection();
   $queryMe = "SELECT tav, date, day, postFix FROM percenter WHERE id = '1'";
   if(!$data = mysql_query($queryMe)) {
      die(mysql_error());
   }
   mysql_close();
   return($data);
}

function getTav() {
   connection();
   $Dataquery = "SELECT id, tav, date, day, postFix FROM percenter ORDER BY id DESC LIMIT 2";
   if(!$r = mysql_query($Dataquery)) {
      die(mysql_error());
   }
   mysql_close();
   return($r);
}

function getHigh() {
   connection();
   $Highquery = "SELECT high, span FROM percenterHigh ORDER BY id DESC LIMIT 1";
   if(!$l = mysql_query($Highquery)) {
      die(mysql_error());
   }
   mysql_close();
   return($l);
}

function putHigh($finalRate, $compare) {
   connection();
   $putMe = "INSERT INTO percentHigh (high, span) VALUES ('$finalRate', '$compare')";
   if(mysql_query($putMe)) {
      die(mysql_error());
   }
   mysql_close();
}
?>