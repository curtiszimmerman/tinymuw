<?php
/* This here is register.php. Here is where users register themselves */
/* so that they can view your site and do the other cool things that */
/* users do, like feel part of a community that you provide for them. */
include('config.php');
echo 'Please enter your information below. All fields are required. ';
echo 'Do not capitalize either your username or your email address. The password you enter here ';
echo 'is case-sensitive and should use both letters and numbers. You should also consider ';
echo 'using special characters like periods and commas.<br /><br />';
if($session->errorStatus && $_SESSION['errorForWho'] == 'register') {
   echo '<div class="errorText">'.$_SESSION['error'].'</div><br /><br />';
}
echo '<div class="registerMargin">';
echo '<form method="POST" action="tinyMuw/process.php">';
echo '<div class="fields">';
echo '<div class="fieldName">Username desired:</div>';
echo '<div class="fieldEntry">';
echo '<input type="text" maxsize="25" size="30" name="username" class="inputField"><br /></div>';
echo '</div><div class="fields">';
echo '<div class="fieldName">Desired Password:</div>';
echo '<div class="fieldEntry">';
echo '<input type="password" maxsize="25" size="30" name="password" class="inputField"><br /></div>';
echo '</div><div class="fields">';
echo '<div class="fieldName">Confirm Password:</div>';
echo '<div class="fieldEntry">';
echo '<input type="password" maxsize="25" size="30" name="vpassword" class="inputField"><br /></div>';
echo '</div><div class="fields">';
echo '<div class="fieldName">Email:</div>';
echo '<div class="fieldEntry">';
echo '<input type="text" maxsize="60" size="30" name="email" class="inputField"></p></div>';
echo '</div>';
echo '<div class="fields"><input type="checkbox" name="termsAgree">';
echo ' I have read and agree to the <a href="javascript:pop';
echo "('".termsPage."')";
echo '">Terms and Conditions</a> of this site.<br /><div class="fieldName">';
echo '<input type="submit" value="Submit" name="register" class="buttonFont">';
echo '</div></div></div>';
echo '</form>';
?>
