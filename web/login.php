<?php

require_once 'includes/config.php';

$errors = array();

$email = "";
if(isset($_POST['email'])) { $email = safeString($_POST['email']); }

$password = "";
if(isset($_POST['password'])) { $password = safeString($_POST['password']); }

if (isset($_POST['submit'])) {	
	if ($email  == '') {
    	array_push($errors, 'Email address cannot be blank');
  	}
  
  	if($password == '') {
    	array_push($errors, 'Password cannot be blank');
  	}
  	
  	if(!$session->login($email, $password, isset($_POST['remember']) ? 14 : 0)) {
		array_push($errors, 'Username and/or password did not match.');
  	}
  
  	if (count($errors) == 0) {
	  if( isset($_REQUEST['ref']) )
		header("Location: http://" . $_SERVER['SERVER_NAME'] . $_REQUEST['ref']);
	  else
		header("Location: http://" . $_SERVER['SERVER_NAME'] . '/index.php' );
  	}
}

$smarty->assign('errors', $errors);
$smarty->assign('email', $email);
if( isset( $_SERVER['HTTP_REFERER'] ) )
  $smarty->assign('referer', $_SERVER['HTTP_REFERER']);
$smarty->assign('remember', isset($_POST['remember']));

$smarty->assign('title', 'Login');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('login.tpl'));
$smarty->display('skeleton.tpl');

?>
