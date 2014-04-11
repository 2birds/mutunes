<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/php_utils/db_query.php");
if(isset($_POST['email'])) {
  $q = "INSERT INTO contributors (`name`,`email`,`contributions`) VALUES ('".$_POST['name']."','".$_POST['email']."',".$_SESSION['contributions'].");";
  if(query($q)){
    $msg = "Thanks very much! Your name has been entered into our database.";
    $expiration = 2 * 30 * 24 * 60 * 60 + time();
    setcookie('email',$_POST['email'],$expiration);
  }else{
    $msg =  "It looks like I\'ve already got your name.";
  }
}
?>
