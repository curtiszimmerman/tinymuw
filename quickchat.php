<?php
include('config.php');
global $database;
$archive = $_POST['archive'];
echo '<br /><div align="center"><div class="borderTop" />';
$v = $database->getQuickchat($archive);
$i = '1';
while($vault = mysql_fetch_array($v)) {
   if($i > '1') {
      echo '<div class="dotborderTop" />';
   }
   echo '<div class="comment">'.ucfirst($vault['username']).' said: </div>';
   echo $vault['chat'].'<br />';
   echo '<div class="comment">At '.date('H:ia \o\n M d',$vault['timest']).'</div>';
   $i++;
}
echo '<br />';
if($_COOKIE[verifiedCookie] && $_POST['newChat']) {
   if($session->errorStatus && $_SESSION['errorForWho'] == 'chat') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div>';
   }
   echo '<form method="post" action="tinyMuw/process.php">';
   echo 'Post (180 char limit):<br> <textarea class="inputField" name="comments" rows="6" cols="10">';
   echo '</textarea><br> <input class="buttonFont" type="submit" name="newChatEntry" value="Submit">';
   echo '</form><br />';
} elseif($_COOKIE[verifiedCookie]) {
   echo '<form method="POST" action="'.$_SESSION['page'].'">';
   echo '<input type="submit" name="newChat" value="Post QuickChat" class="buttonFont">';
   echo '</form>';
}
if(mysql_num_rows($v) !== 0) {
   echo '<form method="POST" action="'.webRoot.$_SESSION['page'].'">';
   echo '<input type="submit" name="archive" class="buttonFont" value="View More Entries">';
   echo '</form><br />';
}
echo '</div>';
?>