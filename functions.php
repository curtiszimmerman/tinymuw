<?php

function mysql_fetch_all($r) {
   $i = 0;
   for ($i=0; $i < mysql_num_rows($r); $i++) {
       $rFinal[$i] = mysql_fetch_array($r);
   }
   return $rFinal;
}

?>