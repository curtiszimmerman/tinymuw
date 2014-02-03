<?php
echo '<p>If you have forgotten your login information, ';
echo " we're sorry, but we cannot retrieve your password as it was. ";
echo 'You will have to reset your password, which will require you to have access to the ';
echo 'email address you registered with. You will be sent a new, temporary password that will ';
echo 'need to be changed upon login.</p>';
echo '<p>Please enter the email address you registered with below:<br>';
if($session->errorStatus && $_SESSION['errorForWho'] == 'forgot') {
   echo '<div class="errorText">'.$_SESSION['error'].'</div>';
}
echo '<br><br>';
echo '<form method="POST" action="tinyMuw/process.php">';
echo '<input type="text" size="50" maxsize="50" name="femail" class="inputField">';
echo '<input type="submit" name="forgotPass" value="Submit" class="buttonFont"></form>';
?>