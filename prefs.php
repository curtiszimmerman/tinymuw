<?php
include('config.php');
if($_COOKIE[verifiedCookie]) {
   echo 'This page contains account settings and user preferences.<br /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'change') {
      echo '<div class="errorText">'.$_SESSION['error'].'<br /><br /></div>';
   }
   echo '<form method="POST" action="tinyMuw/process.php">';
   echo '<div class="fields"><div class="fieldName">Email:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="50" size="45" name="changeEmail" class="inputField"></div></div>';
   echo '<div class="fields"><div class="fieldName">Old Password: *</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="password" maxsize="25" size="30" name="oldPassword" class="inputField"></div></div>';
   echo '<div class="fields"><div class="fieldName">New Password:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="password" maxsize="25" size="30" name="newPassword" class="inputField"></div></div>';
   echo '<div class="fields"><div class="fieldName">Confirm New Password:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="password" maxsize="25" size="30" name="cnewPassword" class="inputField"></div></div><br />';
   echo '<input type="submit" value="Submit" name="changeInfo" class="buttonFont"></form><br />';
   echo '* denotes required field';
} else {
   echo 'You know better than that. LOG IN!<br /><br />';
}
?>