<?php
include('session.php');
include('database.php');
include('parser.php');
include('config.php');
/* process.php -- main processing of logins, logouts, anything with user stuff, etc. */
class process {
   function process() {
      $internal = internalCall;
      global $session;
      if(isset($_POST['login'])) {
	     if($this->checkBanned($internal)) {
		    $referrer = webRoot.bannedPage;
			header("Location: $referrer");
	     } else {
	        $this->doLogin();
		 }
	  }
	  if(isset($_POST['logout'])) {
	     $this->doLogout();
	  }
	  if(isset($_POST['submit'])) {  /* Check to see if we've submitted something. */
	     $this->submitEntry($internal);
	  }
	  if(isset($_POST['deleteThis'])) {  /* Confirm the deletion of an entry. */
	     $this->delItem($internal);
	  }
	  if(isset($_POST['newcomments'])) {  /* Submit a modified entry. */
	     $this->submitEdit($internal);
	  }
	  if(isset($_POST['submitVideo'])) {  /* Submitting Google Video entry. */
	     $this->submitVideo($internal);
	  }
	  if(isset($_POST['register'])) {  /* Register a new user. */
	     if($this->checkBanned($internal)) {
		    $referrer = webRoot.bannedPage;
			header("Location: $referrer");
	     } else {
	        $this->registerUser($internal);
		 }
	  }
	  if(isset($_POST['forgotPass'])) {  /* Forgotten password. */
	     $this->forgotPass($internal);
	  }
	  if(isset($_POST['rmInstallFiles'])) {  /* Remove installation files. */
	     $this->rmInstallFiles($internal);
	  }
	  if(isset($_POST['changeInfo'])) {
	     $this->changeUserInfo($internal);
	  }
	  if(isset($_POST['newChatEntry'])) {
	     $this->newChatEntry($internal);
	  }
   }
   
   function doLogin() {
      global $session, $database;
	  $internal = internalCall;
	  $user = $_POST['username'];
	  $password = $_POST['password'];
      $username = strtolower($user);
      /* Test login credentials if we're trying to login (by calling database object). */
	  $passwordHash = md5($password);
      if($database->verifyLogin($username, $passwordHash)) {
		 $session->doLogin($username, $passwordHash, $internal);
		 if($_SESSION['suspended'] == '1') {
		    $errorMsg = "Your account is suspended until ";
			$errorMsg .= date('H:i:s \o\n d M Y',$_SESSION['unsuspendTime']).".";  /* User Suspended. */
		    $this->adminInfo($errorMsg);
		 }
		 if($_SESSION['lastlogin'] == '0') {  /* If this is our first login, go to special page. */
		    $this->newUser($internal, $username);
		    $referrer = webRoot.indexPage;
			header("Location: $referrer");
			exit;
		 } else {
		    /* OK. There is something here that took me quite a while to figure out. I */
			/* ended up having to get my answer in a forum from a Filipino girl who knew */
		    /* her shit (laserlight @ phpbuilder.com forums). Apparently, you can specify */
			/* multiple headers using the header() function, so to TRULY refresh a page */
			/* to the page you want, you have to specify exit; or die; in order for PHP */
			/* to STOP its processing of your code and obey the header() you just sent. */
			$referrer = $_SESSION['lastPage'];
	        header("Location: $referrer");  /* And then refresh. */
			exit;
		 }
      } else { 
         $errorMsg = "The credentials you provided are incorrect.";  /* Bad password. */
		 $this->adminInfo($errorMsg);
      }
   }
   
   function doLogout() {
      global $session;
      setcookie(verifiedCookie, "false", time()-expireTime, "/");  /* If we want to logout, let's remove the login cookie. */
	  setcookie(usernameCookie, "logged_out", time()-expireTime, "/"); /* And the username cookie, too! */
	  if(isset($_COOKIE[adminVariable])) {
	     setcookie(adminVariable, "logged_out", time()-expireTime, "/"); /* And the admin cookie, too! */
      }
	  if(isset($_SESSION[adminVariable])) {
	     unset($_SESSION[adminVariable]);
	  }
	  $session->doLogout();
	  $referrer = webRoot.indexPage;
      header("Location: $referrer");  /* And then refresh. */
	  exit;
   }
      
   function submitEntry($internal) {
      global $database, $session;
	  if(!$_COOKIE[adminVariable] || !$_SESSION[adminVariable]) {
	     $errorBad = "process.submitEntry() admin cookie or admin session var not set! Hack attempt!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      if($internal == internalCall) {
         /* First though, let's clean up our entry so it doesn't do Bad Things. */
		 $name = ucfirst($this->cleanseEntry($internal, $_POST['name']));
		 $entry = $this->cleanseEntry($internal, $_POST['comments']); 
         if($database->submitEntry($internal, $name, $entry)) {
		    $referrer = $_SESSION['lastPage'];
		    header("Location: $referrer");
			exit;
		 }
      } else { /* sigh. else you know what's up. */
	     $errorBad = "process.submitEntry() internalCall incorrect";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function newChatEntry($internal) {
      global $database, $session;
	  if(!$_COOKIE[verifiedCookie]) {
	     $errorBad = "process.newChatEntry() user cookie not set! Hack attempt!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      if($internal == internalCall) {
         /* Let's clean up our entry so it doesn't do Bad Things. */
		 $ientry = $_POST['comments'];
		 if(ini_get('magic_quotes_gpc')) { 
            $icleanEntry = stripslashes(strip_tags($ientry,'<br><p><em><i><b>'));  /* Allow some html tags. */
         } else {  
            $icleanEntry = strip_tags($ientry,'<br><p><em><i><b>');  /* Allow some html tags. */
         }
		 $entry = mysql_real_escape_string($icleanEntry);
		 if(!preg_match('/[a-zA-Z0-9\.\-\<\>\=\/]{1,199}/',$entry)) {
		    $_SESSION['error'] = "Chat cannot be more than 200 chars long.";
            $_SESSION['errorForWho'] = "chat";
	        $_SESSION['errorCount'] = '1';
	        $referrer = $_SESSION['lastPage'];
	        header("Location: $referrer");
	        exit;
		 } 
		 $username = $_SESSION['username'];
         if($database->submitChat($internal, $username, $entry)) {
		    $_SESSION['error'] = "Quickchat Submitted.";
            $_SESSION['errorForWho'] = "chat";
	        $_SESSION['errorCount'] = '1';
		    $referrer = $_SESSION['lastPage'];
		    header("Location: $referrer");
			exit;
		 }
      } else { /* sigh. else you know what's up. */
	     $errorBad = "process.submitEntry() internalCall incorrect";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function delItem($internal) {
      global $database, $session;
      if($internal == internalCall) {
         /* If we want to delete an item, after confirming, we delete the entry by referencing the 'id' in the MySQL query. */
         /* We're really paranoid about making sure we're logged in. But rather that than an empty page! ;) */
         if($_COOKIE[verifiedCookie] && $_COOKIE[adminVariable]) {
            $deleteThis = $_POST['deleteThis'];
            $database->deleteEntry($deleteThis);
         }
		 $referrer = $_SESSION['lastPage'];
		 header("Location: $referrer");
		 exit;
	  } else {  /* hacker */
		 $error = "process.delItem() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function submitEdit($internal) {
      global $database, $session;
	  if(!$_COOKIE[adminVariable] || !$_SESSION[adminVariable]) {
	     $errorBad = "process.submitEdit() admin cookie or admin session var not set! Hack attempt!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
      /* OK, so we have modified an entry. Now let's clean it up a bit and put it in the database. */
	  if($internal == internalCall) {
         $insertItemNum = $_POST['insertItem'];
         $m = $database->selectData($insertItemNum);
         $mume = mysql_fetch_array($m);
		 $newCommentsIn = $this->cleanseEntry($internal, $_POST['comments']);
         $database->submitEdit($internal, $insertItemNum, $newCommentsIn);
		 $referrer = $_SESSION['lastPage'];
		 header("Location: $referrer");	
	     exit; 
      } else {  /* hacktified */
		 $error = "process.submitEdit() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   /* Excellent. We have put in our <embed> code for the Google Video. Now let's store it */
   /* all so we can retrieve it later! :) */
   function submitVideo($internal) {
      global $database;
	  if(!$_COOKIE[adminVariable] || !$_SESSION[adminVariable]) {
	     $errorBad = "process.submitVideo() admin cookie or admin session var not set! Hack attempt!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
	  if($internal == internalCall) {
         $iembedCode = $_POST['embedCode'];
         $insertItemNum = $_POST['insertItem'];
         $m = $database->selectData($insertItemNum);
         $mume = mysql_fetch_array($m);
         $editEntry = $mume['comments'];
		 $embedCode = mysql_real_escape_string($iembedCode);
		 $comments = $this->cleanseEntry($internal, $_POST['comments']);
		 $formatLinkText = $this->cleanseEntry($internal, $_POST['linkText']);
         /* This next line is a very important construct. It appends the hyperlink we'll use to reference */
         /* our Video, so that when people click on it, the right info gets sent to the video page! */
         $newCommentsIn = $editEntry.'<br><br><a href="'.webRoot.'videoPage.php?id='.$insertItemNum.'">'.$formatLinkText.'</a>';
		 $database->submitEdit($internal, $insertItemNum, $newCommentsIn);
         $database->addGoogleVideo($internal, $insertItemNum, $embedCode, $comments);
		 $referrer = $_SESSION['lastPage'];
		 header("Location: $referrer");
		 exit; 
      } else {
	     $error = "process.submitVideo() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   
   /* Function here checks banned IP address table to see if user is banned. */
   function checkBanned($internal) {
      global $database, $session;
	  if($internal == internalCall) {
	     $ip = $session->ipAddrDec;
	     $s = $database->getBanned($internal);
		 while($row = mysql_fetch_array($s)) {
		    if($row['expiretime'] >= time()) {
			   $database->cleanupBanned($row['entry']);
			}
		    if($row['ip'] == '') {
			   $ipto = $session->convertIP($row['ipto']);
			   $ipfrom = $session->convertIP($row['ipfrom']);
			   if(($ipto <= $ip && $ip <= $ipfrom) || ($ipfrom <= $ip && $ip <= $ipto)) {
			      $banned = true;
			   }
			} else {
			   $ipSingle = $session->convertIP($row['ip']);
			   if($ip == $ipSingle) {
			      $banned = true;
			   }
			}
			if($banned) {
			   return true;
			   exit;
			}
		 }  /* while fetch array loop */
	  } else {
	     $error = "process.checkBanned() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function registerUser($internal) {
      global $database, $session, $mailer;
	  if($internal == internalCall) {
	     /* This is actually very, very strange. Our session object loses the values */
		 /* it has reliably kept in the variables $session->page and $session->lastPage */
		 /* for no good reason. I did lots of tests to see why this logic happens the */
		 /* way it does, to no avail. Alas, I must resort to some inelegant trickery. */
	     $referrer = $_SESSION['lastPage'];
	     if(!isset($_POST['termsAgree'])) {
		    $errorMsg = "You must agree to the Terms and Conditions.";
			$this->registerInfo($errorMsg);
	     }
	     $ipassword1 = $_POST['password'];
		 $vpassword2 = $_POST['vpassword'];
		 $password1 = strip_tags(mysql_real_escape_string($ipassword1));
		 $password2 = strip_tags(mysql_real_escape_string($vpassword2));		
		 if(!preg_match("/^.{5,30}$/",$password1)) {
		    $errorMsg = "Your password must be between 5 and 30 characters.";
			$this->registerInfo($errorMsg);
		 } 
		 if($password1 !== $password2) {
		    $errorMsg = "The passwords do not match.";  /* Bad password. */
			$this->registerInfo($errorMsg);
	     }
		 $iemail = $_POST['email'];
		 $email = strip_tags(mysql_real_escape_string($iemail));
		 if(!preg_match("/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]+$/", $email)) {
	        $errorMsg = "You must enter a valid email address.";
		    $this->registerInfo($errorMsg);
		 }
	     $iusernameWanted = $_POST['username'];
		 $usernameWanted = strip_tags(mysql_real_escape_string($iusernameWanted));
		 if(!preg_match("/^.{6,24}$/",$usernameWanted)) {
		    $errorMsg = "Your username must be between 6 and 24 characters.";
			$this->registerInfo($errorMsg);
		 } elseif(!preg_match("/^[A-Za-z0-9]+$/",$usernameWanted)) {
		    $errorMsg = "You may use only numbers and letters (any case) in your username.";
			$this->registerInfo($errorMsg);
		 }
	     if($database->checkUsernameExists($internal, $usernameWanted)) {
		    $errorMsg = "Username is already in use. Please select another username.";
			$this->registerInfo($errorMsg);
		 }
		 if($database->checkEmailExists($internal, $email)) {
		    $errorMsg = 'That email is already in use. Forgot your password? Click <a href="'.forgotPage.'">here</a>.';
			$this->registerInfo($errorMsg);
		 }
		 /* Here is where we generate our random password for users that are signing up */
		 /* for the first time. We store the password they want to use in the temp */
		 /* varchar(254) field of our tinyusers table until they log in for the first */
		 /* time. :) */
		 $bit = '8';
		 $itempPassword = $session->getRandString($bit, $session->charset);
		 /* Here is where we md5 the password the user wants before sending all the info */
		 /* off to be inserted into the database. */
		 $passwordHashed = md5($password1);
		 $tempPassword = md5($itempPassword);
		 $database->addUser($internal, $usernameWanted, $email, $passwordHashed, $tempPassword, $session->timeNow);
		 if($mailer->mailConfirm($internal, $usernameWanted, $email, $itempPassword)) {
			$errorMsg = 'You have successfully registered. Check your email to verify your account.';
			$this->registerInfo($errorMsg);
		 } else {
			$errorMsg = "Not sure what happened, but I'll bet anything the SMTP settings aren't right!";
			$this->registerInfo($errorMsg);
		 }
	  } else {  /* hacker */
		 $error = "process.registerUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function newUser($internal, $username) {
      global $database, $session;
      if($internal == internalCall) {
	     $database->newUser($internal, $username);
	  } else {
		 $error = "process.newUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   function forgotPass($internal) {
      global $database, $session, $mailer;
	  if($internal == internalCall) {
	     $iemail = $_POST['femail'];
		 $email = strip_tags(mysql_real_escape_string($iemail));
         if(!preg_match("/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]+$/", $email)) {
	        $errorMsg = "You must enter a valid email address.";
		    $this->forgotInfo($errorMsg);
		 }
		 $username = $database->getUsername($internal, $email);
		 if($username == '') {
		    $errorMsg = "This email does not exist. Please enter a correct email address.";
			$this->forgotInfo($errorMsg);
		 }
		 $bit = '8';
		 $ipasswordHashed = $session->getRandString($bit, $session->charset);
		 $passwordHashed = md5($ipasswordHashed);
	     $database->updateForgotUser($internal, $username, $passwordHashed);
		 if(!$database->updateMailedUser($internal, $username, $session->timeNow)) {
		    $errorMsg = "This account has been emailed too many times. Try again in a little while.";
			$this->forgotInfo($errorMsg);
		 }
		 if($mailer->forgotPass($internal, $email, $username, $ipasswordHashed)) {
		    $errorMsg = "Information sent. Check your email for your new account information.";
			$this->forgotInfo($errorMsg);
		 } else {
			$errorMsg = "Not sure what happened, but I'll bet anything the SMTP settings aren't right!";
			$this->forgotInfo($errorMsg);
		 }
	  } else {  /* Playing with something they shouldn't be. */
	     $error = "process.newUser() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   /* Removes temporary files used during installation. Fairly straight-forward. */
   function rmInstallFiles($internal) {
      if($internal == internalCall) {
	     $install1 = '../install.php';
		 $install2 = '../installProc.php';
		 $install3 = '../installSession.php';
	     unlink($install1);
		 unlink($install2);
		 unlink($install3);
		 $referrer = 'README.txt';
		 header("Location: $referrer");
		 exit;
	  } else {
	     $error = "process.rmInstallFiles() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   /* This function changes user information from the userPage. */
   function changeUserInfo($internal) {
      if($internal == internalCall) {
	     global $database;
	     $username = $_SESSION['username'];
	     $ipassword1 = $_POST['newPassword'];
		 $vpassword2 = $_POST['cnewPassword'];
		 $opassword = $_POST['oldPassword'];
		 $password = md5($opassword);
		 if(!$database->verifyLogin($username, $password)) {
		    $errorMsg = "That password is incorrect.";
			$this->changeInfo($errorMsg);
		 }
		 $password1 = strip_tags(mysql_real_escape_string($ipassword1));
		 $password2 = strip_tags(mysql_real_escape_string($vpassword2));		
		 if($password1 !== '') {
		    if(!preg_match("/^.{5,30}$/",$password1)) {
		       $errorMsg = "Your password must be between 5 and 30 characters.";
			   $this->changeInfo($errorMsg);
		    } 
		    if($password1 !== $password2) {
		       $errorMsg = "The passwords do not match.";  /* Bad password. */
			   $this->changeInfo($errorMsg);
	        }
			$field = 'password';
			$passwordHash = md5($password1);
			$value = $passwordHash;
			if($database->changeInfo($internal, $username, $field, $value)) {
		       $errorMsg = 'Information successfully changed.';
		    }
		 }
		 $iemail = $_POST['changeEmail'];
		 $email = strip_tags(mysql_real_escape_string($iemail));
		 if($email !== '') {
		    if(!preg_match("/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]+$/", $email)) {
	           $errorMsg = "You must enter a valid email address.";
		       $this->changeInfo($errorMsg);
		    }
		    if($database->checkEmailExists($internal, $email)) {
		       $errorMsg = 'That email is already in use.';
			   $this->changeInfo($errorMsg);
		    }
		    $passwordHash = md5($password1);
			$field = 'email';
			$value = $email;
		    if($database->changeInfo($internal, $username, $field, $value)) {
		       $errorMsg = 'Information successfully changed.';
		    }
         }
		 if($errorMsg == '') {
		    $errorMsg = 'Cannot change anything, as there is no input!';
		    $this->changeInfo($errorMsg);
	     } else {
		    $this->changeInfo($errorMsg);
		 }
	  } else {
	     $error = "process.changeInfo() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   
   /* The next four functions essentially just save us some typing. If these are */
   /* called, we have an error message, so it's safe to just set errorCount, set the */
   /* page the error is for, and refresh back to that page to take care of the error. :) */
   function forgotInfo($errorMsg) {
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = "forgot";
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
   
   function registerInfo($errorMsg) {
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = "register";
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
   
   function adminInfo($errorMsg) {
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = "admin";
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
   
   function changeInfo($errorMsg) {
      $_SESSION['error'] = $errorMsg;
      $_SESSION['errorForWho'] = "change";
	  $_SESSION['errorCount'] = '1';
	  $referrer = $_SESSION['lastPage'];
	  header("Location: $referrer");
	  exit;
   }
   
   /* cleanseEntry() -- This function allows us to cleanse any user entries (including */
   /* admin) of rogue transversal html and MySQL escape characters to prevent hacks or */
   /* insecurity on the part of admin's laziness. */
   function cleanseEntry($internal, $entry) {
      if($internal == internalCall) {
         if(ini_get('magic_quotes_gpc')) { 
            $icleanEntry = stripslashes(strip_tags($entry,'<br><p><img><a><em><i><b><embed>'));  /* Allow some html tags. */
         } else {  
            $icleanEntry = strip_tags($entry,'<br><p><img><a><em><i><b><embed>');  /* Allow some html tags. */
         }
		 $cleanEntry = mysql_real_escape_string($icleanEntry);
		 return $cleanEntry;
	  } else {
	     $error = "process.cleanseEntry() internalCall incorrect.";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
	  }
   }
}
$process = new process;
?>
