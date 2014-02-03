<?php
/* Script by Curtis Zimmerman.     */
/* Copyright (C) L0j1k.com.        */
/* This just lets you input a lil  */
/* percent-tracker on your index   */
/* for things like stocks.         */
include 'databaseX.php';
include 'functions.php';
$subTavTrue = isset($_POST['subtav']);
$authed = $_COOKIE['homeslice'];
$now = date('Ymd');
$day = date('l');
$postFix = date('jS');
echo '<div class="quickLinksHR"></div><div class="percenterText"><br>';
if($subTavTrue) {
   $tav = $_POST['tav'];
   if(!is_numeric($tav)) {
      echo "You must enter a number.<br>";
   } else {
   postTav($tav, $now, $day, $postFix);
   }
}
if($authed == "logged_in") {
   echo '<form method="POST" action="mainPage.php">';
   echo 'TAV: <input type="text" size="10" name="tav"><br>';
   echo '<input type="submit" name="subtav" value="Submit" class="buttonFont">';
   echo '</form>';
}
$r = getTav();
$rFinal = mysql_fetch_all($r);
$s = $rFinal['0'];
$t = $rFinal['1'];
$newVar = $s['tav'];
$oldVar = $t['tav'];
$diffVar = $oldVar / $newVar;
$rateVar = 1 - $diffVar;
$rateVar = 100 * $rateVar;
$finalRate = number_format($rateVar, 3);
$newCompDate = $s['date'];
$oldCompDate = $t['date'];
$today = date('Ymd');
$newCompDay = $s['day'];
$oldCompDay = $t['day'];
$newPostFix = $s['postFix'];
$oldPostFix = $t['postFix'];
$newDateDiff = $today - $newCompDate;
$oldDateDiff = $today - $oldCompDate;
$compare = $newCompDate - $oldCompDate;
if($compare == '0') {
   echo 'The rate change just today is:<br>'.$finalRate.'%<br>';
} else {
   if($newDateDiff == '0') {
      $finalNewDate = "today ";
   } else {
      if($newDateDiff == '1') {
         $finalNewDate = "yesterday ";
      } else {
         if($newDateDiff > '1' AND $newDateDiff < '8') {
	        $finalNewDate = "last ".$newCompDay." ";
	     } else {
	        $finalNewDate = $newCompDay." the ".$newPostFix." ";
	     }
      }
   }
   if($oldDateDiff == '0') {
      $finalOldDate = "today ";
   } else {
      if($oldDateDiff == '1') {
         $finalOldDate = "yesterday ";
      } else {
         if($oldDateDiff > '1' AND $oldDateDiff < '8') {
	        $finalOldDate = "last ".$oldCompDay." ";
	     } else {
	        $finalOldDate = $oldCompDay." the ".$oldPostFix." ";
	     }
      }
   }
   echo 'The change between<br>'.$finalNewDate.'and '.$finalOldDate.'is:<br>';
   if($finalRate < '0') {
      echo '<div class="negative">';
   } else {
      echo '<div class="positive">';
   }
   echo $finalRate.'%</div><br>';
}
$data = grabFirst();
$dat = mysql_fetch_array($data);
$oldestVar = $dat['tav'];
$diffzVar = $oldestVar / $newVar;
$ratezVar = 1 - $diffzVar;
$ratezVar = 100 * $ratezVar;
$overall = number_format($ratezVar, 3);
$Highest = getHigh();
$b = mysql_fetch_array($Highest);
$prevHigh = $b['high'];
$span = $b['span'];
if($finalRate > $prevHigh) {
   putHigh($finalRate, $compare);
}
echo 'The highest periodic change<br>';
echo 'recorded is:<br>';
echo '<div class="positive">'.$prevHigh.'%</div>';
echo '(over a span of '.$span.' days)<br><br>';
echo 'The overall change <br>';
echo '(since 29 March 2006) is:<br>';
if($overall < '0') {
   echo '<div class="negative">';
} else {
   echo '<div class="positive">';
}
echo $overall.'%</div>';
echo '</div>';
?>