<?php
   if($_SESSION['lastipaddr'] == '') {
      echo '<br />Your account is verified! The password you registered with is now your account password.<br />';
   } else {
      echo '<br>You last logged in from ';
      echo $_SESSION['lastipaddr'].' ';
      echo 'on '.strftime("%d%b%y at %H:%M:%S.",$_SESSION['lastlogin']).'<br>';
      if($_COOKIE[verifiedCookie] && $_SESSION[adminVariable] && $_COOKIE[adminVariable] && $_SESSION['version'] !== '') {
         if($_SESSION['version'] !== versionNum) {
	        echo '<div class="errorText">tinyMuw '.$_SESSION['version'].' is available!</div>';
	     } else {
	        echo 'Your version is current!<br />';
	     }
      }
   }
?>