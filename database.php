<?php
include('config.php');
$database = new database;
if(class_exists('database')) {
   return;
}
class database {
   var $connect;
   var $timeNow;

   function database() {
      $internal = internalCall;
      /* Edit these constants in config.php to reflect your site-specific credentials! */
      $this->connect = mysql_connect(dbServer, dbUsername, dbPassword) or die(mysql_error()); 
	  $this->timeNow = time();
      if(!mysql_select_db(dbDatabase)) { 
         die('Cannot select the news database.'); 
      }
   }

   function submitEntry($internal, $name, $entry) {
      if($internal == internalCall) {
         /* These next two lines process the query so we prevent SQL injection. */
         $name = mysql_real_escape_string($name); 
         $entry = mysql_real_escape_string($entry);
         $query = "INSERT INTO news (name, comments, date) VALUES ('$name', '$entry', NOW())"; 
         if(!$s = mysql_query($query, $this->connect)) { 
	        die(mysql_error()); 
         }
	     return true;
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.submitEntry() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }

   function selectAll() {
      /* Now let's grab up to 50 of the latest entries from the database. */
      $query = "SELECT id, name, comments, date, DATE_FORMAT(date, '%h:%i %p on %M %D %Y ') AS formatDate FROM news ORDER BY date DESC LIMIT 50"; 
      if(!$r = mysql_query($query, $this->connect)) { 
         die(mysql_error());
      }
      if(mysql_num_rows($r) == 0) { 
        echo 'There are no entries.<br>';  /* And here we check to see if there aren't any entries. */
      }
      return $r;
   }
   
   function getQuickchat($archive) {
      /* Here we're grabbing all the Quickchat entries. */
	  if($archive == '') {
	     $number = quickchatNum;
	  } else {
	     $number = 25;
	  }
      $query = "SELECT entry, username, chat, timest FROM quickchat ORDER BY timest DESC LIMIT $number"; 
      if(!$r = mysql_query($query, $this->connect)) { 
         die(mysql_error());
      }
      if(mysql_num_rows($r) == 0) { 
        echo 'There are no entries in Quickchat.<br>';  /* And here we check to see if there aren't any entries. */
      }
      return $r;
   }
   
   function submitChat($internal, $username, $entry) {
   if($internal == internalCall) {
         $timest = time();
         $query = "INSERT INTO quickchat (username, chat, timest) VALUES ('$username', '$entry', '$timest')"; 
         if(!$s = mysql_query($query, $this->connect)) { 
	        die(mysql_error()); 
         }
	     return true;
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.submitEntry() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function getLogData($internal) {
      if($internal == internalCall) {
         /* Now let's grab up to 50 of the latest entries from the database. */
         $query = "SELECT id, time, priority, error, ip FROM log ORDER BY id DESC LIMIT 50"; 
         if(!$r = mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
         if(mysql_num_rows($r) == 0) { 
           echo 'There are no entries.<br>';  /* And here we check to see if there aren't any entries. */
         }
         return($r);
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.getLogData() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function getBannedData($internal) {
      if($internal == internalCall) {
         /* Now let's grab up to 50 of the latest entries from the database. */
         $query = "SELECT entry, ip, ipfrom, ipto, reason, expiretime FROM banned ORDER BY entry DESC LIMIT 50"; 
         if(!$r = mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
         if(mysql_num_rows($r) == 0) { 
           echo 'There are no entries.<br>';  /* And here we check to see if there aren't any entries. */
         }
         return($r);
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.getLogData() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function resetLogData($internal) {
      if($internal == internalCall) {
         $query = "DELETE FROM log WHERE id > '0' LIMIT 50"; 
         if(!$r = mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
		 return true;
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.getLogData() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function adminRemoveBanned($internal, $entry) {
      if($internal == internalCall) {
         $query = "DELETE FROM banned WHERE entry = '$entry' LIMIT 1"; 
         if(!$r = mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
		 return true;
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.getLogData() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function changeInfo($internal, $username, $field, $value) {
      if($internal == internalCall) {
         $query = "UPDATE tinyusers SET `$field` = '$value' WHERE username = '$username'"; 
         if(!$r = mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
		 return true;
	  } else {  /* Hackarific! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.changeInfo() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }

   function selectData($insertItemNum) {
      $query = "SELECT name, comments, date FROM news WHERE id = '$insertItemNum'";
      if(!$m = mysql_query($query, $this->connect)) {
         die(mysql_error());
      }
      return($m);
   }

   function deleteEntry($deleteThis) {
      $deleteQuery = "DELETE FROM news WHERE id = '$deleteThis' LIMIT 1";
      if(!mysql_query($deleteQuery, $this->connect)) {
         die(mysql_error());
      }
   }

   function submitEdit($internal, $insertItemNum, $newCommentsIn) {
      global $session;
      if($internal == internalCall) {
         /* We had to gather the original entry's name and date because for some reason, MySQL doesn't */
         /* like having only one piece of data specified in the REPLACE statements. It wants every single */
         /* field to be specified, even if you don't want to replace that field. I'm pretty sure this has */
         /* to do with our specifying "not null" in some of those MySQL fields. Hmmm.. */
	     /* UPDATE as of v0.1.0: Yes, I have looked in the MySQL 5.0 reference and this is the case. Since */
	     /* specifying to REPLACE or INSERT INTO columns generates null data (for string column data types) */
	     /* for non-specified columns, and we have explicitly specified 'not null' in our tables, we can't */
	     /* just not put anything. */
	     /* OK! So, as of v0.1.0b (heh.. the final v0.1.0 release), I've changed this line: */
         /* $editQuery = "REPLACE INTO news (id, name, comments, date) ... */
	     /* VALUES('$insertItemNum', '$originalName', '$newCommentsIn', '$originalDate')"; */
	     /* To THIS line: */
	     $query = "UPDATE news SET comments = '$newCommentsIn' where id = '$insertItemNum'";
         if(!mysql_query($query, $this->connect)) { 
            die(mysql_error());
         }
      } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.submitEdit() internalCall incorrect";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }

   function addGoogleVideo($internal, $insertItemNum, $embedCode, $comments) {
      global $session;
      if($internal == internalCall) {
         /* OK, folks. Here is the end result. This function adds our video information into the video table. */
         $query = "INSERT INTO video (id, embedCode, videoComments) VALUES ('$insertItemNum', '$embedCode', '$comments')";
         if(!mysql_query($query, $this->connect)) {
            die(mysql_error());
         }
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.addGoogleVideo() internalCall incorrect";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }

   function GoogleVideo($id) {
      /* This is for when we're displaying the video stuff. It's safer to pass all this data into */
      /* the database and retrieve it on the other page, as well as generally more efficient. It  */
      /* does all this while maintaining those dashing good looks the whole time! :)              */
      $query = "SELECT embedCode, videoComments FROM video WHERE id = '$id'"; 
      if(!$s = mysql_query($query, $this->connect)) { 
  	     die(mysql_error());
      }
      return($s);
   }

   function getNameDate($id) {
      /* Here we're retrieving the name and date from the original news comments from the news table */
      /* for preservation on the video page. This, like the function above, is bunches more secure   */
      /* and happy and efficient than passing the data as variables in the URL or (blech) a form.    */
      $query = "SELECT name, date, DATE_FORMAT(date, '%h:%i %p on %M %D %Y ') AS formatDate FROM news WHERE id = '$id'"; 
      if(!$t = mysql_query($query, $this->connect)) { 
         die(mysql_error());
      }
      return($t);
   }
   
   function verifyLogin($username, $passwordHash) {
      /* Simply because passing data around like candy doesn't make me feel good, we're gonna go  */
	  /* ahead and do the username/password comparisons here, instead of just having a function */
	  /* that mindlessly grabs data from the database with only a username supplied. :P */
	  $query = "SELECT password FROM tinyusers WHERE username = '$username'";
	  if(!$t = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
	  $result = mysql_fetch_array($t);
	  if($result['password'] == $passwordHash) {
	     return true;
	  } else {
		 return false;
      }
   }
   
   function getUserInfo($username, $passwordHash, $internal) {
      global $session;
      /* Here we're gonna first check the login credentials, then we're gonna return an array */
	  /* that'll load up the session variables with all the info it could ever want or need. :) */
	  $query = "SELECT password FROM tinyusers WHERE username = '$username'";
	  if(!$t = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
	  $result = mysql_fetch_array($t);
	  if($result['password'] == $passwordHash && $internal == internalCall) {
	     $query2 = "SELECT userid, userlevel, ip, lastlogin, suspended, timeunsuspend FROM tinyusers WHERE username = '$username'";
		 if(!$s = mysql_query($query2, $this->connect)) {
		    die(mysql_error());
		 }
		 $resultArray = mysql_fetch_array($s);
		 return $resultArray;
	  } else {  /* Else you've got a hacker. Heh. Sucks. */
	     $ip = $session->ipAddr;
		 $errorBad = "database.getUserInfo() username/password mismatch and/or internalCall incorrect";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function isSuspended($internal, $username) {
   global $session;
	  if($internal == internalCall) {
	     $query2 = "SELECT suspended, timeunsuspend FROM tinyusers WHERE username = '$username'";
		 if(!$s = mysql_query($query2, $this->connect)) {
		    die(mysql_error());
		 }
		 $resultArray = mysql_fetch_array($s);
		 return $resultArray;
	  } else {  /* Else you've got a hacker. Heh. Sucks. */
	     $ip = $session->ipAddr;
		 $errorBad = "database.isSuspended() internalCall incorrect";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function updateDB($username, $internal, $ipAddr, $timeNow){
      if($internal == internalCall) {
         $q = "UPDATE tinyusers SET ip = '".$ipAddr."' WHERE username = '$username'";
         if(!$q = mysql_query($q, $this->connect)) {
		    die(mysql_error());
         }
		 $r = "UPDATE tinyusers SET lastlogin = '".$timeNow."' WHERE username = '$username'";
		 if(!$r = mysql_query($r, $this->connect)) {
		    die(mysql_error());
         }
	  } else {  /* Chinese food is a favorite of the person who gets to this else! ;) */
	     $ip = $ipAddr;
		 $errorBad = "database.updateDB() internalCall incorrect";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
      }
   }
   
   /* After a person logs in, we add them to the active users table. */
   function addActive($username, $timestamp, $internal) {
      if($internal == internalCall) {
	     $query = "REPLACE INTO activeusers VALUES ('$username', '$timestamp')";
		 if(!$r = mysql_query($query, $this->connect)) {
		    die(mysql_error());
         }
	  } else {  /* Else hacker. */
	     $ip = $session->ipAddr;
		 $errorBad = "database.addActive() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
      }
   }
   
   /* Anytime anyone views the site and is not verified as being logged in, */
   /* they are considered a guest and added to the guest table, complete with */
   /* IP address and time of last pageload. */
   function addGuest($ip, $timestamp, $internal) {
      if($internal == internalCall) {
	     $query = "REPLACE INTO guestusers VALUES ('$ip', '$timestamp')";
		 if(!$t = mysql_query($query, $this->connect)) {
		    die(mysql_error());
         }
	  } else {  /* Else el hacko */
	     $ip = $session->ipAddr;
		 $errorBad = "database.addGuest() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   /* The only place this function gets called is when a user logs in. What */
   /* we want to do here is to remove their entry from the guest list so that */
   /* we remain as accurate as possible when counting users viewing the site. */
   function removeGuest($ip) {
      $query = "DELETE FROM guestusers WHERE ip = '$ip'";
	  if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
   }
   
   /* If a user logs out, we want to remove them from the active users list */
   /* immediately, instead of waiting for them to time out, since it's just */
   /* more efficient and well.. elegant. */
   function logoutActive($username) {
	  $query = "DELETE FROM activeusers WHERE username = '$username'";
      if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
   }
   
   /* Every time anyone loads a page anywhere, we want to clean up the tables */
   /* containing active users and guest users, so that we don't keep on showing */
   /* users who haven't loaded a page in expireTime. That's what the next two */
   /* functions are for. :) */
   function cleanActive($expireTime) {
      $query = "DELETE FROM activeusers WHERE `timest` < '$expireTime'";
	  if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
   }
   
   function cleanGuests($expireTime) {
      $query = "DELETE FROM guestusers WHERE `timest` < '$expireTime'";
	  if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
      }
   }
   
   /* Pretty self-explanatory. This is used to show the active users on the site. */
   function getActiveList() {
      $query = "SELECT * FROM activeusers";
	  if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
	  }
	  return $s;
   }
   
   /* Same as above except, of course, we're grabbing the guest list. */
   function getGuestList() {
      $query = "SELECT * FROM guestusers";
	  if(!$s = mysql_query($query, $this->connect)) {
	     die(mysql_error());
	  }
	  $result = mysql_numrows($s);
	  return $result;
   }
   
   function checkUsernameExists($internal, $usernameWanted) {
      if($internal == internalCall) {
		 $query = "SELECT * FROM tinyusers WHERE username = '$usernameWanted' LIMIT 1";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $b = mysql_numrows($s);
		 if($b == '1') {
		    return true;
	     } else {
		    return false;
		 }
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.checkUsernameExists() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function checkEmailExists($internal, $email) {
      if($internal == internalCall) {
		 $query = "SELECT * FROM tinyusers WHERE email = '$email' LIMIT 1";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $result = mysql_numrows($s);
		 return $result;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.checkEmailExists() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function addUser($internal, $usernameWanted, $email, $passwordHashed, $tempPassword, $time) {
      if($internal == internalCall) {
	     $password = 'p('.$passwordHashed.')';
		 $query = "REPLACE INTO tinyusers (username, password, userlevel, lastlogin, email, emailcount, ";
		 $query .= "lastemail, temp, `membersince`, suspended, timeunsuspend) values ('$usernameWanted',";
		 $query .= "'$tempPassword','8','0','$email','0','0','$password','$time','0','0');";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return $result;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.addUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function adminAddUser($internal, $usernameWanted, $email, $passwordHashed, $permGroup) {
      if($internal == internalCall) {
	     $timeNow = time();
		 $query = "REPLACE INTO tinyusers (username, password, userlevel, lastlogin, email, emailcount, ";
		 $query .= "lastemail, temp, `membersince`, suspended, timeunsuspend) values ('$usernameWanted',";
		 $query .= "'$passwordHashed','$permGroup','$timeNow','$email','1','$timeNow','0','$timeNow','0','0');";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $result = mysql_numrows($s);
		 return $result;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.adminAddUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function changeGroup($internal, $username, $newGroup) {
      if($internal == internalCall) {
	     $timeNow = time();
		 $query = "UPDATE tinyusers SET userlevel = '$newGroup' WHERE username = '$username'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return true;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.changeGroup() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function suspendUser($internal, $username, $suspendTime) {
      if($internal == internalCall) {
	     $timeNow = time();
		 $itotaltime = $suspendTime * 24 * 60 * 60;
		 $totaltime = $timeNow + $itotaltime;
		 $query = "UPDATE tinyusers SET `suspended` = '1', `timeunsuspend` = '$totaltime' ";
		 $query .= "WHERE `username` = '$username'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return true;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.suspendUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function unSuspendUser($internal, $username) {
      if($internal == internalCall) {
		 $query = "UPDATE tinyusers SET `suspended` = '0', `timeunsuspend` = '0' ";
		 $query .= "WHERE `username` = '$username'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return true;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.unSuspendUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function adminDeleteUser($internal, $username) {
      if($internal == internalCall) {
		 $query = "DELETE FROM tinyusers WHERE `username` = '$username'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return true;
	  } else {  /* hack-tastic! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.adminDeleteUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function adminBanRange($internal, $banIPFrom, $banIPTo, $iduration, $reason) {
      if($internal == internalCall) {
	     $duration = $iduration * 24 * 60 * 60;
		 if($duration !== '0') {
		    $expireTime = $duration + time();
	     }
		 $query = "REPLACE INTO banned (ipfrom, ipto, reason, expiretime) VALUES ";
		 $query .= "('$banIPFrom', '$banIPTo', '$reason', '$expireTime')";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
	  } else {  /* hackapalooza! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.adminBanRange() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function adminBanIP($internal, $banIP, $banDuration, $reason) {
      if($internal == internalCall) {
	     $duration = $iduration * 24 * 60 * 60;
		 if($duration !== '0') {
		    $expireTime = $duration + time();
	     }
		 $query = "REPLACE INTO banned (ip, reason, expiretime) VALUES ('$banIP', '$reason', '$expireTime')";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
	  } else {  /* hackapalooza! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.adminBanIP() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function getBanned($internal) {
      if($internal == internalCall) {
	     $query = "SELECT * FROM banned";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 return $s;
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.getBanned() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function cleanupBanned($num) {
      if($internal == internalCall) {
	     $query = "DELETE FROM banned WHERE `entry` = '$num'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.cleanupBanned() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function getLastChk($internal) {
      if($internal == internalCall) {
	     $query = "SELECT lastchk FROM tinymuwsys LIMIT 1";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $salt = mysql_fetch_array($s);
		 return $salt;
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.getLastChk() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function updateTable($internal, $table, $field, $value) {
      if($internal == internalCall) {
	     $query = "UPDATE `$table` SET `$field` = '$value'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
         return true;
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.updateTinymuwsys() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function checkTinymuwsys($internal) {
      if($internal == internalCall) {
	     $query = "SELECT lastchk, syskey, server, dir, tog, registered, version FROM tinymuwsys";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
         return $s;
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.updateTinymuwsys() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   /* This function serves the simple purpose of deleting a new user's temporary */
   /* verification password and replacing it with the password they used to register with. */
   function newUser($internal, $usr) {
      if($internal == internalCall) {
	     global $parser;
	     $query = "SELECT temp FROM tinyusers WHERE username = '$usr'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $t = mysql_fetch_array($s);
		 $descriptor = 'p';
		 $data = $parser->parseData($internal, $t['temp'], $descriptor);
		 if($data == false) {
		    echo "Something isn't right. Contact the administrator with Error 15.";
			return false;
		 } else {
		    $pwdExt = $data[0];
			$tmp = $data[1];
		    $query = "UPDATE tinyusers SET password = '$pwdExt', temp = '$tmp' WHERE username = '$usr'";
		    if(!$r = mysql_query($query, $this->connect)) {
		       die(mysql_error());
		    }
		    return true;
	     }
	  } else {
	     $ip = $session->ipAddr;
		 $errorBad = "database.newUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function getUsername($internal, $email) {
      if($internal == internalCall) {
	     $query = "SELECT username FROM tinyusers WHERE email = '$email'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    die(mysql_error());
		 }
		 $t = mysql_fetch_array($s);
		 return $t['username'];
	  } else {  /* Hacker.. I'm fucking starving.. */
	     $ip = $session->ipAddr;
		 $errorBad = "database.newUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function updateForgotUser($internal, $username, $passwordHashed) {
      if($internal == internalCall) {
	     $query = "UPDATE tinyusers SET password = '$passwordHashed' WHERE username = '$username'";
		 if(!$s = mysql_query($query, $this->connect)) {
		    echo 'here! you got it! updateforgotuser! ;)';
		    die(mysql_error());
		 }
	  } else {  /* Hacker!! I'm still fucking hungryyyyy! :( */
	     $ip = $session->ipAddr;
		 $errorBad = "database.newUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function updateMailedUser($internal, $username, $time) {
      if($internal == internalCall) {
	     $que = "SELECT lastemail, emailcount FROM tinyusers WHERE username = '$username'";
		 if(!$r = mysql_query($que, $this->connect)) {
		    die(mysql_error());
		 }
		 $route = mysql_fetch_array($r);
		 $difference = $time - $route['lastemail'];
		 $number = $route['emailcount'];
		 if($difference > emailTime) {
	        $query = "UPDATE tinyusers SET `emailcount` = '1', `lastemail` = '$time' WHERE username = '$username'";
		    if(!$s = mysql_query($query, $this->connect)) {
		       die(mysql_error());
		    }
		    return true;
		 } elseif($number < emailNum) {
		    $quer = "UPDATE tinyusers SET `emailcount` = `emailcount` + 1 WHERE username = '$username'";
			if(!$t = mysql_query($quer, $this->connect)) {
		       die(mysql_error());
		    }
		    return true;
		 } else {
		    return false;
		 }		    
	  } else {  /* Hungry Hungry Hackers! */
	     $ip = $session->ipAddr;
		 $errorBad = "database.updateMailedUser() internalCall incorrect.";
		 $priority = '2';
		 $this->logBad($errorBad, $ip, $priority);
	  }
   }
   
   function logBad($errorBad, $ip, $priority) {
      /* Basically, if program flow gets here, someone is trying to hack your site, */
	  /* or there is something *very* strange going on with function calls in here. */
	  /* Essentially what has happened is someone has tried (and almost succeeded) */
	  /* in calling an object function through abnormal means. Thank goodness you */
	  /* changed the settings in config.php, right? :D Thought so! */
	  echo "You are doing something Very Bad. IP ".$ip." logged for ban.<br>";
	  echo "You simply cannot get this message unless you have attempted a security breach.<br>";
      echo "I'd suggest emailing the webmaster immediately with a damned good explanation for the<br>";
      echo "reason they'll see this error if you don't want to be banned (or worse) whenever they get back.<br>";
	  echo "Keep it up and I will take the liberty of banning you temporarily to keep this site secure.";
	  $query = "INSERT INTO log (priority, error, ip) values('$priority', '$errorBad', '$ip')";
	  if(!$m = mysql_query($query, $this->connect)) {
	     die(mysql_error());
	  } else {
	     echo "<br><br>Error logged.<br>";
		 echo $errorBad;
	  }
   }
   
}
?>