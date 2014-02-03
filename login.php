<?php
include('config.php');
global $session;
$submitTrue = isset($_POST['submit']);  /* Check to see if we've submitted something. */
/* Ensure we're logged in. */
echo '<br /><div align="center">';
if($_COOKIE[verifiedCookie]) {
   if(isset($_POST['newsEntry']) && isset($_COOKIE[adminVariable]) && $_SESSION[adminVariable]) {
      /* If we *are* admin and newsEntry is set, go ahead and display our submission box. */
      echo '<form method="post" action="tinyMuw/process.php"> Name:<br>';
      echo '<input type="text" class="inputField" maxsize="24" size="13" name="name">';
      echo '<br> Post:<br> <textarea class="inputField" name="comments" rows="6" cols="10">';
      echo '</textarea><br> <input class="buttonFont" type="submit" name="submit" value="Submit">';
      echo '</form><br /><br />';
   }
   echo "Welcome, ".ucfirst($session->username)."!";
   include('motd.php');
   if($_COOKIE[verifiedCookie] && $_SESSION[adminVariable] && $_COOKIE[adminVariable] && !$_POST['newsEntry']) {
      echo '<form method="POST" action="'.indexPage.'">';
	  echo '<input type="submit" name="newsEntry" value="News Entry" class="buttonFont"></form>';
   }
   if($_COOKIE[verifiedCookie] && $_SESSION[adminVariable] && $_COOKIE[adminVariable]) {
	  echo '<form method="POST" action="'.adminPage.'">';
	  echo '<input type="submit" name="trashVar" value="Admin Options" class="buttonFont"></form>';
   }
   echo '<form method="POST" action="'.userPage.'">';
   echo '<input class="buttonFont" type="submit" name="trashVar" value="Preferences">';
   echo '</form><form method="POST" action="tinyMuw/process.php">';
   echo '<input class="buttonFont" type="submit" name="logout" value="Logout"></form>';
/* If we aren't logged in, let's display the login form. :) */
} else { 
   if($session->errorStatus && $_SESSION['errorForWho'] == 'admin') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div>';
   }
?>
<div align="center">
<form method="POST" action="tinyMuw/process.php">Username: <br>
<input type="text" class="inputField" name="username" maxlength="20" size="10"><br>Password: <br>
<input type="password" class="inputField" name="password" maxlength="20" size="10"><br>
<input type="submit" name="login" value="Login" class="buttonFont">
</form>
<div class="poweredBy">Not registered? <a href="<?php echo registerPage;?>">Register!</a><br>
Forgot your <a href="<?php echo forgotPage;?>">password?</a></div>
<?php
}
?>
<br />
<div class="poweredBy">Powered by tinyMuw
<?php
echo ' '.versionNum.'.';
?>
<br>
Learn more at <a href="http://www.tinymuw.com/tinyMuw" target="_blank">tinyMuw.com</a></div></div></div>