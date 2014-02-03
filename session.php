<?php
include('config.php');
include('database.php');
include('update.php');
include('mailer.php');
class session {

   var $username;   /* Gets set to username after login and every checkLogin() = true. */
   var $userLevel;
   var $sessionID;  /* We store the session ID here for any reason we might need it. */
   var $userID;
   var $ipAddr;  /* Stores the IP Address of the person conducting HTTP requests. */
   var $ipAddrDec;  /* Stores the user IP Address in decimal format. */
   var $lastIpAddr;  /* Stores the IP Address from where user was last logged in from. */
   var $lastLogin;  /* Stores the timedate of last login. */
   var $timeNow;  /* This stores the current time as of pageload. */
   var $page;  /* This *should* give us the page currently being viewed by the user. */
   var $lastPage;  /* And this *should* give us the page which the user last visited. */
   var $loggedin;  /* Boolean whether login cookie has expired. */
   var $errorStatus;  /* This is used in errorInc() to test whether we have set the error bit. */
   var $errorForWho;  /* This tells us who the error is for. ;) */
   var $errorMsg;
   var $charset;  /* This determines if we use a character set with lowercase letters in random strings. */

   function session() {
      $this->ipAddr = getenv('REMOTE_ADDR');
	  $this->ipAddrDec = $this->convertIP($this->ipAddr);
      $this->timeNow = time();
      $this->startsession();
   }

   function startsession() {
      session_start();
	  $internal = internalCall;
	  $sessionExpire = $this->timeNow + expireTime;
	  $this->charset = '0';
	  $this->errorInc();
      global $database;
	  $this->sessionID = strip_tags(session_id());
	  /* Honestly, I would love to know more about the function below. It is currently */
	  /* listed as an undocumented function, and my localhost PHP (5.0) won't accept it */
	  /* as a valid function. DISCUSS THIS IN THE FORUM PLEASE! The syntax listed is: */
	  /* int msession_timeout ( string session [, int param] ) */
	  // msession_timeout($this->sessionID, 1200);
	  $this->updateVars($internal);
      $this->loggedin = $this->checkLogin();
	  if($this->loggedin) {
	     $this->updateCookies($internal);
      }
	  if($this->loggedin) {
		 $database->addActive($this->username, $sessionExpire, $internal);		 
	  } else {
	     $this->username = $_SESSION['username'] = 'guest';
		 $this->userLevel = $_SESSION['userLevel'] = '9';
		 $database->addGuest($this->ipAddr, $sessionExpire, $internal);
	  }	  
	  /* Here is where we delete users from our guest and active users table if */
	  /* their timestamp is less than $this->timeNow. :) */
	  $database->cleanActive($this->timeNow);
	  $database->cleanGuests($this->timeNow);	  
	  /* Check to see if pages are set, if not, set them. */
	  if(isset($_SESSION['page'])) {
         $this->lastPage = $_SESSION['lastPage'] = $_SESSION['page'];
	     $this->page = $_SESSION['page'] = $_SERVER['PHP_SELF'];
      } else {
	     $this->page = $_SESSION['page'] = $_SERVER['PHP_SELF'];
		 if(!isset($_SESSION['lastPage'])) {
		    $this->lastPage = $_SESSION['lastPage'] = $_SERVER['PHP_SELF'];
	     }
	  }
   }
   
   /* This function expresses the users IP Adress in decimal format, so that */
   /* we can compare it to the banned IP Address table. */
   function convertIP($ipAddr) {
      if($ipAddr == '') {
	     $ipAddrDec = '';
	  } else {
	     list($grp1, $grp2, $grp3, $grp4) = split ("/\./", $ipAddr, 4);
         $ipAddrDec = ((((($grp1 * 256 + $grp2) * 256) + $grp3) * 256) + $grp4);
      }
	  return $ipAddrDec;
   }
   
   /* Here we're just checking to see if the username and verified cookies are */
   /* set. If they're not, that means that the user hasn't done anything in */
   /* however many minutes you set the session to last. */
   function checkLogin() {
      if(!isset($_COOKIE[verifiedCookie]) || !isset($_COOKIE[usernameCookie])) {
	     unset($_SESSION[adminVariable]);
	     return false;
	  } else if($this->username == $_COOKIE[usernameCookie]) {
	     /* This next check might seem a little strange, but let me explain. It's */
		 /* actually quite simple. Every time a page loads, this test checks to */
		 /* see if a user suddenly has the admin cookie set, but doesn't have */
		 /* the session admin variable set. The only place the session admin */
		 /* variable gets set is during login after the userlevel is tested, so */
		 /* we know that a user is fucking with his cookies and SOMEHOW got the */
		 /* name of our adminVariable in config.php, which is VERY bad, and also */
		 /* means that it should be changed IMMEDIATELY. */
	     if(!isset($_SESSION[adminVariable]) && isset($_COOKIE[adminVariable])) {
		    $errorBad = "session.checkLogin() admin cookie set without session admin variable being set! Cookie tampering!<br>";
			$errorBad .= "Also they have the name of our admin variable! This is VERY, VERY, VERY, VERY BAD!<br>";
			$errorBad .= "Change it now in config.php before this turns into a full compromise!";
			$priority = '1';
			$this->errorBad($errorBad, $priority);
		 } else {
		    return true;
	     }
	  } else {  /* Else someone is trying something clever with their cookies! ;) */
	     $errorBad = "session.checkLogin() cookie username does NOT equal last recorded username! OMFG! Cookie tampering!";
		 $priority = '1';
		 $this->errorBad($errorBad, $priority);
	  }
   }
   
   function updateVars($internal) {
      if($internal == internalCall) {
         $this->username = $_SESSION['username'];
	     $this->userID = $_SESSION['userid'];
	     $this->userLevel = $_SESSION['userlevel'];
	     $this->lastIpAddr = $_SESSION['lastipaddr'];
         $this->lastLogin = $_SESSION['lastlogin'];
      } else {  /* 7337 h4x0r */
	     $errorBad = "session.setVars() internalCall incorrect";
		 $priority = '2';
		 $this->errorBad($errorBad, $priority);
      }
   }
   
   /* Just a little cookie-updating action! */
   function updateCookies($internal) {
      if($internal == internalCall) {
         setcookie(verifiedCookie, "true", time()+expireTime, "/");
	     setcookie(usernameCookie, $this->username, time()+expireTime, "/");
		 /* Yes, the next cookie expires in 5 years. So what. :P */
		 setcookie(lastUsername, $this->username, time()+157680000,"/");
      } else {  /* Hacker! */
	  	 $errorBad = "session.updateCookies() internalCall incorrect";
		 $priority = '2';
		 $this->errorBad($errorBad, $priority);
	  }
   }
   
   /* Doing our session's version of login. Basically, the else in this */
   /* function is in case someone calls this function without having their */
   /* cookies set properly, which indicates a grievous error or a hack. */
   function doLogin($username, $passwordHash, $internal) {
      global $database, $update;
      if($internal == internalCall) {
	     $usrInfo = $database->getUserInfo($username, $passwordHash, $internal);
		 $this->username = $_SESSION['username'] = $username;
		 $this->userID = $_SESSION['userid'] = $usrInfo['userid'];
		 $this->userLevel = $_SESSION['userlevel'] = $usrInfo['userlevel'];
		 $this->lastIpAddr = $_SESSION['lastipaddr'] = $usrInfo['ip'];
		 $this->lastLogin = $_SESSION['lastlogin'] = $usrInfo['lastlogin'];
		 $this->suspended = $_SESSION['suspended'] = $usrInfo['suspended'];
		 $this->unsuspendTime = $_SESSION['unsuspendTime'] = $usrInfo['timeunsuspend'];
		 if($this->suspended !== '1') {
		    if($this->userLevel == '1') {
		       $_SESSION[adminVariable] = true;
			   setcookie(adminVariable, 'true', time()+expireTime, '/');
			   $v = $database->checkTinymuwsys($internal);
			   $vapor = mysql_fetch_array($v);
			   if($vapor['tog'] == '0') {
			      $syskey = $vapor['syskey'];
				  $reg = $vapor['registered'];
			      $update->updateSystem($internal, $vapor['server'], $vapor['dir'], $reg, $syskey);
			   }
		    }
		    $this->updateDB($username, $passwordHash, $internal);
		    $this->updateCookies($internal);
		    $database->removeGuest($this->ipAddr);
		 } else {
		    return false;
		 }
      } else {  /* Else you've got a hacker. ;) Heh. */
		 $errorBad = "session.doLogin() internalCall incorrect";
		 $priority = '2';
		 $this->errorBad($errorBad, $priority);
      }
   }
   
   /* This is just a simple logout call to the database to get the active */
   /* user out of the active users list, since it's more elegant to do that */
   /* than to wait until they expire, right? :) */
   function doLogout() {
      global $database;
	  $username = $this->username;
	  $database->logoutActive($username);
   }
   
   /* This here is a function to update the database with the newest login */
   /* information such as the user's IP and the current time, which will */
   /* become the time of last login. */
   function updateDB($username, $passwordHash, $internal) {
      global $database;
      if($internal == internalCall) {
	     $database->updateDB($username, $internal, $this->ipAddr, $this->timeNow);
	  } else {  /* Else you know the drill. */
		 $errorBad = "session.updateDB() internalCall incorrect";
		 $priority = '2';
		 $this->errorBad($errorBad, $priority);
	  }
   }
	     
   /* This function tattles to database, which records our hack attempts. */
   /* If the error is bad enough, it straight up emails the admin while */
   /* freaking out. */
   function errorBad($errorBad, $priority) {
      global $database, $mailer;
	  $ip = $this->ipAddr;
	  echo 'errorbad: '.$errorBad.'<br />ip: '.$ip.'<br />priority: '.$priority.'<br />';
	  return;
	  $database->logBad($errorBad, $ip, $priority);
	  if($priority == '1') {
	     if(!$mailer->mailAdmin($internal, $errorBad, $ip, $priority)) {
		    echo '<br><div class="errorText">Error Code 10.</div><br>';
		 }
	  }
	  exit;
   }
   
   /* OK. I know this function looks weird, but it's really very simple. */
   /* Basically, every time we have an error that we need to pass on (such */
   /* as for logins or register errors), we store the error in a session */
   /* variable. The problem is that we only want the error to appear in the */
   /* page that is loaded immediately following our error occurrence, */
   /* because session variables don't go away. So even though our 'error' */
   /* session variable ALWAYS contains an error (after we have received */
   /* an error once), we need some way of determining if the error is old */
   /* or not. Therefore when we set a new 'error' session variable, we also */
   /* set the errorCount variable to 1, thereby incrementing it to 2 in */
   /* the next iteration of our session object, which will trigger this */
   /* function to set errorStatus to true, letting our page know that */
   /* the data in session variable 'error' is new data, not old data. */
   /* PHEW! Sorry for being verbose. */
   function errorInc() {
      $this->errorStatus = false;
      $_SESSION['errorCount']++;
   	  if($_SESSION['errorCount'] > '2') {
	     $_SESSION['errorCount'] = '0';
	  } 
	  else if($_SESSION['errorCount'] < '2') {
	     $_SESSION['errorCount'] = '0';
	  }
	  else {  /* Else errorCount == 2 and that means we need to set the error bit! */
	     $this->errorStatus = true;
	  }
   }
   
   /* getRandString($bit, $charset): This function is designed to take two arguments, */
   /* $bit and $charset. $bit is of course the length of the random string you want to */
   /* generate. $charset is a special bit that we set to '1' in case we need a random */
   /* string that is all lowercase for some reason. Also we have support for strings */
   /* containing special characters, where bit == '2'. It comes with built-in support */
   /* for these, but by default $charset is set to '0', giving us the standard random */
   /* string which includes uppercase letters. */
   function getRandString($bit, $charset) {
      if($charset == '1') {
         $charset = 'abcdefghijklmnopqrstuvwxyz0123456789';
	  } elseif($charset == '2') {
	     $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		 $charset .= '~!@#$%^&*()_+=-`,./;:?"\'[]{}|\\';
	  } else {
	     $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	  }
	  for($i=0;$i<$bit;$i++) {
	     $key .= $charset[rand(0,strlen($charset))];
	  }
	  return $key;
   }
}
$session = new session;
?>