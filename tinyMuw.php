<?php
include('config.php');
/* These are all conditions. If one is true, then we know we want to take a certain action. */
$editTrue = isset($_POST['editEntry']);  /* Edit an entry. */
$delTrue = isset($_POST['deleteEntry']);  /* Delete an entry. */
$addVideoTrue = isset($_POST['addVideo']);  /* Add Google Video link. */
$archiveTrue = isset($_POST['archive']);  /* View the Last 50 entries. */
$r = $database->selectAll();
/* OK, the $displayLast variable is just a counter variable to keep the number of comments listed to 5. */
$displayLast = 0;
/* And here we grab each row from the database in sequential order. */
/* We also test here to see if we've already displayed more than 5 entries, or */
/* if we have selected the button to view the last 50 entries. */
while($row = mysql_fetch_array($r) AND ($displayLast < 5 OR $archiveTrue)) {
   if($row['comments'] !== "") {  /* If the comment itself is blank, let's just not display it. */
      $displayLast = $displayLast + 1;
      echo '<div class="newsBox">';
      echo $row['comments']."<br>";
      echo '<br><div class="comment">'.$row['name'].' posted this comment at '.$row['formatDate'].'</div>';
	  /* If we're logged in, let's display some admin options, like Edit and Delete for each entry. */
      if($_COOKIE[verifiedCookie] && $_COOKIE[adminVariable]) {
		 /* If we have selected to delete something, let's verify that is what we want to do. */
         if($delTrue) {
            $delItem = $_POST['item'];
	        $currentRow = $row['id'];
	        if($currentRow == $delItem) {
			   echo '<div class="buttons"><div class="buttonName">';
	           echo '<form method="POST" action="tinyMuw/process.php">';
		       echo '<input type="submit" name="delConfirm" value="Delete Confirm" class="buttonFont">';
		       echo '<input type="hidden" value="';
		       echo $currentRow;
		       echo '" name="deleteThis"></form>';
			   echo '</div><div class="buttonEntry">';
			   echo '<form method="POST" action="'.indexPage.'">';
			   echo '<input type="submit" name="editEntry" value="Delete Cancel" class="buttonFont">';
			   echo '</form></div></div>';
	        }
         } else {
		    /* If we haven't selected to delete anything, let's just display the Edit and Delete buttons. */
		    echo '<form method="POST" action="'.indexPage.'"><input type="hidden" value="';
            echo $row['id'];
	        echo '" name="item">';
            echo '<input type="submit" name="editEntry" value="Edit" class="buttonFont">';
			echo '<input type="submit" name="addVideo" value="AddGoogleVideo" class="buttonFont">';
	        echo '<input type="submit" name="deleteEntry" value="Delete" class="buttonFont"></form>';
		 }
		 /* If we want to edit something, we need to gather some info about it. */
         if($editTrue OR $addVideoTrue) {
            $editItem = $_POST['item'];
	        $currentRow = $row['id'];
			/* Below we check to see if the entry being displayed right now is the entry we want to edit. */
			/* If so, we then display our textbox with the entry in it, waiting to be modified! :) */
	        if($currentRow == $editItem) {
               $editEntry = $row['comments'];
		       $origName = $row['name'];
               $origDate = $row['date'];
               echo '<form method="POST" action="tinyMuw/process.php">';
               echo '<textarea class="inputField" name="comments" rows="5" cols="65">'.$editEntry.'</textarea>';
		       echo '<input type="submit" name="newcomments" value="Submit New Entry" class="buttonFont">';
		       echo '<input type="hidden" value="';
		       echo $currentRow;
		       echo '" name="insertItem">';
               if($addVideoTrue) {
			      echo '<textarea class="inputField" name="embedCode" rows="5" cols="65">';
				  echo "Add EXACT cut'n'paste embed code from Google Video here.           ";
				  echo "WARNING: If you screw this part up, it's best just to ";
				  echo 'delete and make a whole new entry.</textarea>';
				  echo '<textarea class="inputField" name="comments" rows="5" cols="65">';
				  echo 'Add comments for the video here (these comments will appear on ';
				  echo 'the video page underneath the video being displayed).</textarea>';
				  echo '<textarea class="inputField" name="linkText" rows="2" cols="65">';
				  echo 'Enter link text for video here (what you want the hyperlink to say).';
				  echo '</textarea>';
				  echo '<input type="submit" name="submitVideo" value="Submit Google Video Entry" ';
				  echo 'class="buttonFont">';
			   }
               echo '</form>';
            }
         }
	  }
      echo "</div><br />";  /* Here ends a comment. */
   }  /* And here ends our entire comment loop. */
}
echo '<div align="right"><form method="POST" action="'.indexPage.'">';
if($archiveTrue) {
   echo '<input type="submit" name="noArchive" value="Return to Normal View" class="buttonFont" />';
} else {
   echo '<input type="submit" name="archive" value="View Last 50 Items" class="buttonFont" />';
}
echo '</form></div><br />';
echo '<div align="center">';
echo 'Powered by <a href="http://www.tinyMuw.com" target="_blank">tinyMuw</a> '.versionNum.' &copy; L0j1k.com. All Rights Reserved.<br />';
echo '</div>';
echo '<br />';
?>