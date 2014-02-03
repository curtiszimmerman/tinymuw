<?php
include('config.php');
include('session.php');
class adminprocess {

   function adminprocess() {  /* Class constructor. */
      global $session;
      $internal = internalCall;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->adminprocess()! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  unset($_SESSION['adminSection']);
      if(isset($_POST['adminAddUser'])) {
	     $this->adminAddUser($internal);
	  }
	  if(isset($_POST['adminChgGrp'])) {
	     $this->adminChgGrp($internal);
	  }
	  if(isset($_POST['suspendUser'])) {
	     $this->suspendUser($internal);
	  }
	  if(isset($_POST['unSuspendUser'])) {
	     $this->unSuspendUser($internal);
	  }
	  if(isset($_POST['deleteUser'])) {
	     $this->deleteUser($internal);
	  }
	  if(isset($_POST['banIPFunc'])) {
	     $this->banIP($internal);
	  }
	  if(isset($_POST['removeBanned'])) {
	     $this->removeBanned($internal);
	  }
	  if(isset($_POST['viewLogs'])) {
	     $this->viewLogs($internal);
	  }
	  if(isset($_POST['resetLogs'])) {
	     $this->resetLogs($internal);
	  }
   }
   
   function adminAddUser($internal) {
      global $database, $session, $mailer;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->adminAddUser! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  if($internal == internalCall) {
	     /* This is actually very, very strange. Our session object loses the values */
		 /* it has reliably kept in the variables $session->page and $session->lastPage */
		 /* for no good reason. I did lots of tests to see why this logic happens the */
		 /* way it does, to no avail. Alas, I must resort to some inelegant trickery. */
	     $referrer = $_SESSION['lastPage'];
	     $ipassword1 = $_POST['addPassword'];
		 $password1 = strip_tags(mysql_real_escape_string($ipassword1));		
		 if(!preg_match("/^.{5,30}$/",$password1)) {
		    $errorMsg = "The password must be between 5 and 30 characters.";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 } 
		 $iemail = $_POST['addEmail'];
		 $email = strip_tags(mysql_real_escape_string($iemail));
		 if(!preg_match("/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]+$/", $email)) {
	        $errorMsg = "You must enter a valid email address.";
			$adminSection = "adminAddUser";
		    $this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	     $iusernameWanted = $_POST['addUsername'];
		 $ipermGroup = $_POST['addPermGroup'];
		 $permGroup = strip_tags(mysql_real_escape_string($ipermGroup));
		 $usernameWanted = strip_tags(mysql_real_escape_string($iusernameWanted));
		 if(!preg_match("/^[1-9]$/",$permGroup)) {
		    $errorMsg = "The Permission Group must be in the range from 1 (Admin) to 9 (User).";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 if(!preg_match("/^.{6,24}$/",$usernameWanted)) {
		    $errorMsg = "The username must be between 6 and 24 characters.";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 } elseif(!preg_match("/^[A-Za-z0-9]+$/",$usernameWanted)) {
		    $errorMsg = "You may use only numbers and letters (any case) in your username.";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	     if($database->checkUsernameExists($internal, $usernameWanted)) {
		    $errorMsg = "Username is already in use. Please select another username.";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 if($database->checkEmailExists($internal, $email)) {
		    $errorMsg = 'That email is already in use.';
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 $passwordHashed = md5($password1);
		 $database->adminAddUser($internal, $usernameWanted, $email, $passwordHashed, $permGroup);
		 if($mailer->mailNewUser($internal, $usernameWanted, $email, $password1)) {
			$errorMsg = 'User successfully created. Email sent to new user at address specified.';
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 } else {
			$errorMsg = "Not sure what happened, but I'll bet anything the SMTP settings aren't right!";
			$adminSection = "adminAddUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	  } else {  /* hacker */
		 $error = "adminprocess.adminAddUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function adminChgGrp($internal) {
      global $session, $database;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->adminChgGrp! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      if($internal == internalCall) {
         $iusername = $_POST['chgGrpUsername'];
		 $username = strip_tags(mysql_real_escape_string($iusername));
		 $inewGroup = $_POST['chgGrpGroup'];
		 $newGroup = strip_tags(mysql_real_escape_string($inewGroup));
		 if(!$database->checkUsernameExists($internal, $username)) {
		    $errorMsg = "Username does not exist. Please select a valid username.";
			$adminSection = "adminChgGrp";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	     if(!preg_match("/^[1-9]$/",$newGroup)) {
		    $errorMsg = "The Group specified must be an integer from 1 to 9.";
			$adminSection = "adminChgGrp";
			$this->adminAddUserInfo($errorMsg, $adminSection);
	     }
		 if($database->changeGroup($internal, $username, $newGroup)) {
		    $errorMsg = "Permission Group for ".$username." successfully changed.";
			$adminSection = "adminChgGrp";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.adminChgGrp() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function suspendUser($internal) {
      global $session, $database;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->suspendUser! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      if($internal == internalCall) {
         $iusername = $_POST['suspendUsername'];
		 $username = strip_tags(mysql_real_escape_string($iusername));
		 $isuspendTime = $_POST['suspendTime'];
		 $suspendTime = strip_tags(mysql_real_escape_string($isuspendTime));
		 if(!$database->checkUsernameExists($internal, $username)) {
		    $errorMsg = "Username does not exist. Please select a valid username.";
			$adminSection = "adminSuspendUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 if($database->suspendUser($internal, $username, $suspendTime)) {
		    $errorMsg = "User ".$username." successfully suspended for ".$suspendTime." days.";
			$adminSection = "adminSuspendUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.suspendUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function unSuspendUser($internal) {
      global $session, $database;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->unSuspendUser! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      if($internal == internalCall) {
         $iusername = $_POST['unSuspendUsername'];
		 $username = strip_tags(mysql_real_escape_string($iusername));
		 if(!$database->checkUsernameExists($internal, $username)) {
		    $errorMsg = "Username does not exist. Please select a valid username.";
			$adminSection = "adminUnSuspendUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 if($database->unSuspendUser($internal, $username)) {
		    $errorMsg = "User ".$username." successfully unsuspended.";
			$adminSection = "adminUnSuspendUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.suspendUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function deleteUser($internal) {
      global $database, $session;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->deleteUser! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  if($internal == internalCall) {
         $iusername = $_POST['deleteUsername'];
		 $username = strip_tags(mysql_real_escape_string($iusername));
		 if(!$database->checkUsernameExists($internal, $username)) {
		    $errorMsg = "Username does not exist. Please select a valid username.";
			$adminSection = "adminDeleteUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
		 if($database->adminDeleteUser($internal, $username)) {
		    $errorMsg = "User ".$username." successfully deleted from the database.";
			$adminSection = "adminDeleteUser";
			$this->adminAddUserInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.deleteUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function banIP($internal) {
      global $database, $session;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->banIP! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  if($internal == internalCall) {
	     unset($_SESSION['which']);
		 if($_POST['banIPfrom'] !== '') {
		    $banRange = true;
			$ibanIPFrom = $_POST['banIPfrom'];
		    $ibanIPTo = $_POST['banIPto'];
		    $banIPFrom = strip_tags(mysql_real_escape_string($ibanIPFrom));
		    $banIPTo = strip_tags(mysql_real_escape_string($ibanIPTo));
			$_SESSION['which'] = '"From"';
			$this->validIPAddress($banIPFrom, $which);
			$_SESSION['which'] = '"To"';
		    $this->validIPAddress($banIPTo, $which);	
		 }
		 if($_POST['banIP'] !== '') {
		    $banSingle = true;
		    $ibanIP = $_POST['banIP'];
			$banIP = strip_tags(mysql_real_escape_string($ibanIP));
			$_SESSION['which'] = '"Specific IP"';
		    $this->validIPAddress($banIP);
	     }
		 if(!$banSingle && !$banRange) {
		    $errorMsg = "You must specify at least SOMETHING to ban. If you hate nothing else, ban the RIAA.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 }
		 $ibanReason = $_POST['banReason'];
		 $banReason = strip_tags(mysql_real_escape_string($ibanReason));
		 $ibanDuration = $_POST['banDuration'];
		 $banDuration = strip_tags(mysql_real_escape_string($ibanDuration));
		 if($banDuration == '') {
		    $errorMsg = "Duration must be set.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 }	  
		 if(strlen($banReason) > '250') {
		    $errorMsg = "Reason for Ban must be less than 250 characters in length.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 }
		 if(!preg_match("/^[0-9]*$/",$banDuration)) {
		    $errorMsg = "Duration specified not a valid number. Please specify a valid integer, ";
			$errorMsg .= "0 for indefinite duration.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 }
		 if($banRange) {
		    $database->adminBanRange($internal, $banIPFrom, $banIPTo, $banDuration, $banReason);
		    $errorMsg = "IP Range ".$banIPFrom." to ".$banIPTo." successfully banned.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 } else {
		    $database->adminBanIP($internal, $banIP, $banDuration, $banReason);
		    $errorMsg = "IP ".$banIP." successfully banned.";
			$adminSection = "adminBanIPFuncs";
			$this->adminBanIPInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.banIP() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function removeBanned($internal) {
      global $database, $session;
	  if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {
	     $errorBad = "adminprocess->deleteUser! Someone has tried to access functions without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our adminprocess.php trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  if($internal == internalCall) {
         $removeEntry = $_POST['removeBanEntry'];
		 if($database->adminRemoveBanned($internal, $removeEntry)) {
		    $errorMsg = "Entry successfully removed from banned IP list.";
			$adminSection = "adminRemoveBan";
			$this->adminBanRemoveInfo($errorMsg, $adminSection);
		 }
	  } else {
	     $error = "adminprocess.deleteUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   /* Function below determines if an entry contains a valid IP address. */
   function validIPAddress($ipAddr) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",$ipAddr)) {
		 $errorMsg = "Please specify a valid IP address in the ".$_SESSION['which']." field";
	     $adminSection = "adminBanIPFuncs";
		 $this->adminBanIPInfo($errorMsg, $adminSection);
	  }
	  return true;
   }
   
   /* The next set of functions are the handlers for our status messages, */
   /* though the terms used below are slightly misleading: while they DO */
   /* contain any errors, they also contain text that is to be displayed */
   /* upon successful process completion. It just saves us typing! ;) */
   function adminAddUserInfo($errorMsg, $adminSection) {
      $_SESSION['adminSection'] = "adminUserFuncs";
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = $adminSection;
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'].'#'.$adminSection;
	  header("Location: $referrer");
	  exit;
   }
   
   function adminBanIPInfo($errorMsg, $adminSection) {
      $_SESSION['adminSection'] = "adminBanIPFuncs";
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = $adminSection;
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
   
   function adminBanRemoveInfo($errorMsg, $adminSection) {
      $_SESSION['adminSection'] = "adminBanIPFuncs";
	  $_SESSION['adminSection2'] = "adminBanRemoveFuncs";
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = $adminSection;
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
}
$adminprocess = new adminprocess;
?>