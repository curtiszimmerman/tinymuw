<?php
include('config.php');
class parser {
   function parser() {
   }
   
   /* char parseData(char internal, char input, char descriptor) -- Takes char input */
   /* and char descriptor, and returns an array, [0] containing string desired, [1] */
   /* containing the rest of the temp data WITHOUT the desired result, [2] containing the */
   /* full temp data string WITH the desired result even though it's an argument for the */
   /* function, which is really all it's passing back, anyways. ;) Function returns false */
   /* if no data was found. */ 
   function parseData($internal, $data, $returnDescriptor) {
	  $input = $data;
	  $returnData = '';
	  do {
         list($var,$tmp) = preg_split('/\)/', $input, 2);
	     list($descriptor, $payload) = preg_split('/\(/', $var, 2);
	     if($descriptor == $returnDescriptor) {
			$returnData = $payload;
		    $returnTemp .= $tmp;
			break;
	     } else {
			$input = $tmp;
			$returnTemp .= $var.')';
	     }
	  } while($tmp !== '');
	  if($returnData == '') {
		 return false; 
	  } else {
		 $returnArray = array($returnData, $returnTemp, $data);
		 return $returnArray;  
	  }	  
   }
}
$parser = new parser;
?>
