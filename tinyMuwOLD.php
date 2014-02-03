<?php
include 'config.php';
$editTrue = isset($_POST['edit']);
$submitEditTrue = isset($_POST['newcomments']);
$connection = mysql_connect('localhost', 'l0j1k02_tinyNews', 'al4ph5a1') or die('Cannot connect to the database server. Here is the error:<br>'.mysql_error()); 
if(!mysql_select_db('l0j1k02_tinyNews')) { 
  die('It appears as though there is no database for the news.'); 
} 
$query = "SELECT id, name, comments, date, DATE_FORMAT(date, '%h:%i %p on %M %D %Y ') AS f_date FROM news ORDER BY date DESC"; 
if(!$r = mysql_query($query)) { 
   die(mysql_error());
}
if(mysql_num_rows($r) == 0) { 
  echo 'There are no entries.<br>'; 
}
while($row = mysql_fetch_array($r)) {
   if($row['id'] > 5) {
	  break 2;
   }
   echo '<div class="newsBox">';
   echo $row['comments']."<br>";
   echo '<br><div class="comment">'.$row['name'].' posted this comment on '.$row['f_date'].'</div>';
   if($_COOKIE['admin'] == "loggedin") {
      echo '<form method="POST" action="mainPage.php"><input type="hidden" value="';
      echo $row['id'];
	  echo '" name="item">';
	  echo '<input type="submit" name="edit" value="Edit" class="buttonFont"></form>';      
   }
   if($editTrue) {
      $editItem = $_POST['item'];
	  $currentRow = $row['id'];
	  if($currentRow == $editItem) {
         $editEntry = $row['comments'];
		 $origName = $row['name'];
         $origDate = $row['date'];
         echo '<form method="POST" action="mainPage.php">';
         echo '<textarea class="inputField" name="comments" rows="5" cols="65">'.$editEntry.'</textarea>';
		 echo '<input type="submit" name="newcomments" value="Submit New Entry" class="buttonFont">';
		 echo '<input type="hidden" value="';
		 echo $currentRow;
		 echo '" name="insertItem">';
		 echo '<input type="hidden" value="';
		 echo $origName;
		 echo '" name="origName">';
		 echo '<input type="hidden" value="';
		 echo $origDate;
		 echo '" name="origDate">';
         echo '</form>';
      }
   } 
   echo "</div><br>";
}

if($submitEditTrue) {
   if(ini_get('magic_quotes_gpc')) { 
      $insertItemNum = $_POST['insertItem']; 
      $newCommentsIn = stripslashes(strip_tags(nl2br($_POST['comments']))); 
   } 
   else { 
      $insertItemNum = $_POST['insertItem']; 
      $newCommentsIn = strip_tags(nl2br($_POST['comments'])); 
   }
   if(!mysql_select_db('l0j1k02_tinyNews')) { 
      die('Cannot select the news database.'); 
   }
   $newCommentsIn = mysql_real_escape_string($newCommentsIn);
   $originalName = $_POST['origName'];
   $originalDate = $_POST['origDate'];
   $editQuery = "REPLACE INTO news (id, name, comments, date) VALUES('$insertItemNum', '$originalName', '$newCommentsIn', '$originalDate')";
   if(mysql_query($editQuery)) { 
      echo '<div align="center">Entry has been edited. Refresh your page to see the results.</div><br><br>';
   } 
   else {
      echo "Could not insert your entry into the system due to the following error:<br>".mysql_error();
   }
}
mysql_close(); 
echo '<br>';
echo 'Powered by <a href="http://www.l0j1k.com/tinyMuw" target="_blank">tinyMuw</a> v0.0.1 &copy; L0j1k.com. All Rights Reserved.<br />';
?>