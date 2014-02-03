<?php
include('config.php');
include('database.php');
global $database;
$iident = $_GET['id'];
$ident = mysql_real_escape_string(strip_tags($iident));
if(!preg_match('/[0-9]+/',$ident)) {
   $ipAddr = getenv('REMOTE_ADDR');
   $errorBad = "video.php: GET of video id was not null and not numeric: HACK ATTEMPT";
   $priority = '1';
   $database->logBad($errorBad, $ipAddr, $priority);
}
if(!is_numeric($ident)) {
   if(is_null($ident)) {
      echo '<div align="center">';
	  echo "Something is wrong. I couldn't get the value passed to me.<br>";
	  echo "Please ensure that your register_globals isn't screwy.<br>";
   } else {
      $ipAddr = getenv('REMOTE_ADDR');
	  $errorBad = "video.php: GET of video id was not null and not numeric: HACK ATTEMPT";
      $priority = '1';
	  $database->logBad($errorBad, $ipAddr, $priority);
   }
} else {
   $s = $database->GoogleVideo($ident);
   $t = $database->getNameDate($ident);
   $simple = mysql_fetch_array($s);
   $tooth = mysql_fetch_array($t);
   $embed = $simple['embedCode'];
   $videoComments = $simple['videoComments'];
   $name = $tooth['name'];
   $date = $tooth['formatDate'];
   echo '<div align="center"><div class="newsBox">';
   echo $embed."<br><br>".$videoComments."<br>";
   echo '<br><div class="comment">'.$name.' posted this comment on '.$date.'</div></div>';
   echo '<br><a href="'.webRoot.indexPage.'">Return to Main Page</a>';
}
?>