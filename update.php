<?php
class update {
   function update() {  /* constructor */
      global $session, $database;
   }
   
   function updateSystem($internal, $server, $dir, $registered, $syskey) {
      if($internal == internalCall) {
	     global $database;
		 $last = $database->getLastChk($internal);
		 $lastChk = $last['lastchk'] + updateSystem;
		 if($lastChk < time()) {
		    if($registered == '0') {
			   $this->registerSystem($internal, $server, $dir, $syskey);
			}
		    $session->version = $_SESSION['version'] = $this->updateCheck($internal, $server, $dir);
			$timenow = time();
			$field = 'lastchk';
			$table = 'tinymuwsys';
			$database->updateTable($internal, $table, $field, $timenow);
		 }
      } else {  /* Else you've got a hacker. ;) Heh. */
		 $errorBad = "session.updateSystem() internalCall incorrect";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
      }
   }
   
   function registerSystem($internal, $server, $dir, $syskey) {
      if($internal == internalCall) {
	     global $database;
		 $donePut = false;
		 $fq = fsockopen($server, 80, $errno, $errstr, 60);
		 if(!$fq) {
            echo '$errstr ($errno)<br />\n';
	        $error++;
         } else {
            $out1 = "POST ".$dir."regutil.php HTTP/1.1\r\n";
	        $out1 .= "Host: ".$server."\r\n";
	     }
	     $out1 .= "User-Agent: tinyMuw/0.1.0 (PHP/MySQL)\r\n";
	     $out1 .= "Content-Type: application/x-www-form-urlencoded\r\n";
	     $out1 .= "Connection: Close\r\n";
	     $out1 .= "Content-Length: 15\r\n\r\n";
	     $out1 .= "id=".$syskey."\r\n\r\n";
	     fwrite($fq, $out1);
	     while(!feof($fq)) {
	        $regVar .= fgets($fq, 128);
		    $donePut = true;
	     }
	     fclose($fq);
		 list($heads, $reg, $trash) = split(';',$regVar);
		 if($reg == '1' && $donePut == '1') {
            $registered = '1';
         } else {
            $registered = '0';
         }
	     $value = $registered;
		 $field = 'registered';
		 $table = 'tinymuwsys';
		 $database->updateTable($internal, $table, $field, $value);
		 return true;
      } else {  /* Else you've got a hacker. ;) Heh. */
		 $errorBad = "session.registerSystem() internalCall incorrect";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
      }
   }
   
   function updateCheck($internal, $server, $dir) {
      if($internal == internalCall) {
         $version == '';
         while($version == '') { 
            $fp = fsockopen($server, 80, $errno, $errstr, 30);
            if (!$fp) {
               echo "$errstr ($errno)<br />\n";
		       $error++;
            } else {
               $out = "GET ".$dir."update.txt HTTP/1.1\r\n";
	           $out .= "Host: ".$server."\r\n";
	           $out .= "User-Agent: tinyMuw/0.1.0 (PHP-CMS AutoChk)\r\n";
	           $out .= "Connection: Close\r\n\r\n";
	           fwrite($fp, $out);
	           $update = '';
               while(!feof($fp)) {
                  $update .= fgets($fp, 128);
               }
               fclose($fp);
            }
            list($resp,$version,$nsr,$adr,$dir,$tog,$tmp,$trash) = split(';',$update);
         }
		 if($tog == '1') {
		    $field = 'tog';
			$value = '1';
			$table = 'tinymuwsys';
		    $database->updateTable($internal, $table, $field, $value);
		 }
		 if($nsr == '1') {
		    $field = 'server';
			$value = $adr;
			$table = 'tinymuwsys';
			$database->updateTable($internal, $table, $field, $value);
			$blim = 'dir';
			$vorpal = $dir;
			$table = 'tinymuwsys';
			$database->updateTable($internal, $table, $blim, $vorpal);
	     }
		 if($tmp !== '0') {
		    $field = $_SESSION['username'];
			$value = $tmp;
			$table = 'tinyusers';
			$database->updateTable($internal, $table, $field, $value);
         }
		 return $version;
	  } else {  /* Else you've got a hacker. ;) Heh. */
		 $errorBad = "session.updateCheck() internalCall incorrect";
		 $priority = '2';
		 $session->errorBad($errorBad, $priority);
      }
   }
}
$update = new update;
?>