<?php
include('config.php');
global $database, $session;
/* Begin User Functions! */
if(!isset($_COOKIE[adminVariable]) && !isset($_SESSION[adminVariable])) {;
   $errorBad = "admin.php->pageload! Someone attempted to access admin.php without being admin!";
   $priority = '2';
   $session->errorBad($errorBad, $priority);
}
if(!isset($_POST['viewUserFuncs']) && $_SESSION['adminSection'] !== 'adminUserFuncs') {
   echo '<div class="borderTop" />';
   echo '<b>User Functions:</b><br />';
   echo '<div class="borderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="viewUserFuncs" value="Expand Options" class="buttonFont">';
   echo '</form><br />';
} elseif($_SESSION['adminSection'] == "adminUserFuncs" OR isset($_POST['viewUserFuncs'])) {
   unset($_SESSION['adminSection']);
   echo '<div class="dotborderTop" />';
   echo '<a name="adminAddUser"><b>Add User:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminAddUser') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo 'Permission Group must be an integer between 1 and 9. The use of 1 is for ';
   echo 'Administrators, 9 is for Guest Users, 8 is Normal User level.<br /><br />';
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Username:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="addUsername" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">Password:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="addPassword" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">Email:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="addEmail" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">Permission Group:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="5" size="10" name="addPermGroup" class="inputField"></div></div><br />';
   echo '<input type="submit" name="adminAddUser" value="Submit" class="buttonFont"><br /></form><br />';
   echo '<div class="dotborderTop" />';
   echo '<a name="adminChgGrp"><b>Change User Permission Group:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminChgGrp') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo 'Permission Group must be an integer between 1 and 9. The use of 1 is for ';
   echo 'Administrators, 9 is for Guest Users, 8 is Normal User level.<br /><br />';
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Username:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="chgGrpUsername" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">New Permission Group:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="5" size="10" name="chgGrpGroup" class="inputField"></div></div><br />';
   echo '<input type="submit" name="adminChgGrp" value="Submit" class="buttonFont"><br /></form><br />';
   echo '<div class="dotborderTop" />';
   echo '<a name="adminSuspendUser"><b>Suspend User:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminSuspendUser') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Username:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="suspendUsername" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">Time in Days (0 for indefinite):</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="5" size="10" name="suspendTime" class="inputField"></div></div><br />';
   echo '<input type="submit" name="suspendUser" value="Submit" class="buttonFont"><br /></form><br />';
   echo '<div class="dotborderTop" />';
   echo '<a name="adminUnSuspendUser"><b>Un-Suspend User:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminUnSuspendUser') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Username:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="unSuspendUsername" class="inputField"></div></div><br />';
   echo '<input type="submit" name="unSuspendUser" value="Submit" class="buttonFont"><br /></form><br />';
   echo '<div class="dotborderTop" />';
   echo '<a name="adminDeleteUser"><b>Delete User:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminDeleteUser') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Username:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="25" size="30" name="deleteUsername" class="inputField"></div></div><br />';
   echo '<input type="submit" name="deleteUser" value="Submit" class="buttonFont"><br /></form><br />';
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="trashVar" value="Collapse Options" class="buttonFont"></form><br />';
}
/* End User Functions! :) */
/* Begin Ban IP Functions! :) */
if(!isset($_POST['viewBanIPFuncs']) && $_SESSION['adminSection'] !== 'adminBanIPFuncs') {
   echo '<div class="borderTop" />';
   echo '<b>IP Ban Functions:</b><br />';
   echo '<div class="borderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="viewBanIPFuncs" value="Expand Options" class="buttonFont">';
   echo '</form><br />';
} elseif($_SESSION['adminSection'] == "adminBanIPFuncs" OR isset($_POST['viewBanIPFuncs'])) {
   echo '<div class="dotborderTop" />';
   echo '<b>Ban IP / IP Range:</b><br />';
   echo '<div class="dotborderTop" /><br />';
   if($session->errorStatus && $_SESSION['errorForWho'] == 'adminBanIPFuncs') {
      echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
   }
   echo '<form method="POST" action="tinyMuw/adminprocess.php">';
   echo '<div class="fields">';
   echo '<div class="fieldName">Specific IP:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="16" size="20" name="banIP" class="inputField"></div></div><br />';
   echo '<div class="fields">';
   echo '<div class="fieldName">(Range) From IP Address:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="16" size="20" name="banIPfrom" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">(Range) To IP Address:</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="16" size="20" name="banIPto" class="inputField"></div></div><br />';
   echo '<div class="fields">';
   echo '<div class="fieldName">Duration in Days (0 for indefinite):</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="5" size="10" name="banDuration" class="inputField"></div></div>';
   echo '<div class="fields">';
   echo '<div class="fieldName">Reason (up to 250 characters):</div>';
   echo '<div class="fieldEntry">';
   echo '<input type="text" maxsize="250" size="45" name="banReason" class="inputField"></div></div><br />';
   echo '<input type="submit" name="banIPFunc" value="Submit" class="buttonFont"><br /><br /></form>';
   if(!isset($_POST['viewBanned']) && $_SESSION['adminSection2'] !== 'adminBanRemoveFuncs') {
      echo '<form method="POST" action="adminPage.php">';
      echo '<input type="submit" name="viewBanned" value="View Banned IPs" class="buttonFont">';
      echo '<input type="hidden" name="viewBanIPFuncs"></form><br />';
   }
   if(isset($_POST['viewBanned']) || $_SESSION['adminSection2'] == 'adminBanRemoveFuncs') {
      unset($_SESSION['adminSection2']);
      if($session->errorStatus && $_SESSION['errorForWho'] == 'adminRemoveBan') {
         echo '<div class="errorText">'.$_SESSION['error'].'</div><br />';
      }
      echo '<div align="center"><form method="POST" action="tinyMuw/adminprocess.php">';
	  echo '<table width="100%" border="1">';
	  echo '<tr><td width="3">Select</td>';
	  echo '<td width="3">ID</td>';
	  echo '<td width="16">IP</td>';
	  echo '<td width="16">From IP</td>';
	  echo '<td width="16">To IP</td>';
	  echo '<td width="50">Reason</td>';
	  echo '<td width="20">Expire Time</td></tr>';
	  $s = $database->getBannedData(internalCall);
	  while($row = mysql_fetch_array($s)) {
	     echo '<tr><td width="3"><input type="checkbox" name="removeBanEntry" value="'.$row['entry'].'">';
		 echo '<td width="3">'.$row['entry'].'</td>';
	     echo '<td width="16">'.$row['ip'].'</td>';
	     echo '<td width="16">'.$row['ipfrom'].'</td>';
	     echo '<td width="16">'.$row['ipto'].'</td>';
	     echo '<td width="50">'.$row['reason'].'</td>';
	     echo '<td width="20">'.date('dMY \a\t H:i:s',$row['expiretime']).'</td></tr>';
	  }
	  echo '</table></div><br />';
	  echo 'NOTE: You can only remove one entry at a time.<br /><br />';
	  echo '<input type="submit" name="removeBanned" value="Remove Selected" class="buttonFont">';
	  echo '</form><br /><br />';
   }   
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="trashVar" value="Collapse Options" class="buttonFont"></form><br />';
}
/* End Ban IP Functions! */
/* Begin Log Functions */
if(!isset($_POST['viewLogFuncs'])) {
   echo '<div class="borderTop" />';
   echo '<b>Log Functions:</b><br />';
   echo '<div class="borderTop" /><br />';
   echo '<form method="POST" action="'.$_SESSION['page'].'#logs">';
   echo '<input type="submit" name="viewLogFuncs" value="Expand Options" class="buttonFont">';
   echo '</form><br />';
} else {
   echo '<div class="dotborderTop" />';
   echo '<a name="logs"><b>View / Reset Logs:</b></a><br />';
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="'.$_SESSION['page'].'#logs">';
   if(isset($_POST['viewLogs'])) {
      echo '<div align="center"><table width="550" border="1">';
	  echo '<tr><td width="5">ID</td>';
	  echo '<td width="40">Time</td>';
	  echo '<td width="5">Priority</td>';
	  echo '<td width="90">Error</td>';
	  echo '<td width="20">IP Addr</td></tr>';
	  $s = $database->getLogData(internalCall);
	  while($row = mysql_fetch_array($s)) {
         echo '<tr><td width="5">'.$row['id'].'</td>';
		 echo '<td width="40">'.$row['time'].'</td>';
		 echo '<td width="5">'.$row['priority'].'</td>';
		 echo '<td width="90">'.$row['error'].'</td>';
		 echo '<td width="20">'.$row['ip'].'</td></tr>';
	  }
	  echo '</table></div><br />';
   }
   if(isset($_POST['resetLogs'])) {
      if(isset($_COOKIE[adminVariable]) && isset($_SESSION[adminVariable])) {
	     $database->resetLogData(internalCall);
	  } else {
	     $errorBad = "admin.php->resetLogs! Someone has tried to reset the logs without being admin! ";
		 $errorBad .= "This means that they are doing raw HTTP POSTs to our admin page trying to hack it!";
		 $priority = '1';
		 $session->errorBad($errorBad, $priority);
	  }
   }
   echo '<div align="center"><div class="fields">';
   echo '<div class="fieldName">';
   echo '<input type="submit" value="View Logs" name="viewLogs" class="buttonFont"></div>';
   echo '<div class="fieldEntry">';
   echo '<input type="submit" value="Reset Logs" name="resetLogs" class="buttonFont"></div></div><br />';
   echo '<input type="hidden" name="viewLogFuncs" value="true"></form></div>';
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="trashVar" value="Collapse Options" class="buttonFont"></form><br />';
}
/* End Log Functions */
/* Begin Update Functions */
if(!isset($_POST['viewUpdateFuncs'])) {
   echo '<div class="borderTop" />';
   echo '<b>Update Functions:</b><br />';
   echo '<div class="borderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="viewUpdateFuncs" value="Expand Options" class="buttonFont">';
   echo '</form><br />';
} else {
   echo '<div class="dotborderTop" />';
   echo '<b>Update System:</b><br />';
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="hidden" name="viewUpdateFuncs" value="true">';
   echo '<input type="submit" name="chkUpdate" value="Check Version" class="buttonFont"></form><br />';
   if(isset($_POST['chkUpdate'])) {
      if(!$_COOKIE[adminVariable] && !$_SESSION[adminVariable]) {;
         $errorBad = "admin.php->chkUpdate! Someone is attempting to use raw HTTP POSTs to ";
		 $errorBad .= "hack into the site!";
         $priority = '1';
         $session->errorBad($errorBad, $priority);
      }
      $version == '';
      while($version == '') { 
         $fp = fsockopen("www.L0j1k.com", 80, $errno, $errstr, 30);
         if (!$fp) {
            echo "$errstr ($errno)<br />\n";
		    $error++;
         } else {
            $out = "GET /tinyMuw/update.txt HTTP/1.1\r\n";
	        $out .= "Host: www.l0j1k.com\r\n";
	        $out .= "User-Agent: tinyMuw/0.1.0 (PHP/MySQL)\r\n";
	        $out .= "Connection: Close\r\n\r\n";
	        fwrite($fp, $out);
	        $update = '';
            while(!feof($fp)) {
               $update = fgets($fp, 128);
            }
            fclose($fp);
         }
         list($resp,$version,$nsr,$adr,$dir,$tog,$tmp,$trash) = split(';',$update);
      }
      echo '<div class="fields"><div class="fieldName">Version on Server:</div>';
	  echo '<div class="fieldEntry">'.$version.'</div></div><br />';
	  if(versionNum == $version) {
	     echo 'System is up-to-date!<br /><br />';
	  } else {
	     echo 'You have version '.versionNum.'<br /><br />';
	  }
   }
   echo '<div class="dotborderTop" /><br />';
   echo '<form method="POST" action="adminPage.php">';
   echo '<input type="submit" name="trashVar" value="Collapse Options" class="buttonFont"></form><br />';
}
/* End Update Functions */
?>