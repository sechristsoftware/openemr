<?php
 require_once("../globals.php");
 
      $Password = $GLOBALS['callback_password'];
	  $Username = $GLOBALS['callback_username'];
	  
     $query = sqlStatement ("SELECT phone FROM facility");
	     
	      while($res = sqlFetchArray($query)){
		     $FromNumber = $res['phone'];
		 }
	 $FromNumber = preg_replace('/\D+/', '', $FromNumber);
 
?>