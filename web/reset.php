<?php

require_once 'includes/config.php';
require_once 'includes/database.php';

$errors = array();


if(isset($_POST['email'])) { 
  $email = safeString($_POST['email']); 
  $tmp = $db->query('select * from users where email="' . $email . '"');

  if(isset($tmp[0])) {
    $auth = $tmp[0]['auth'];

    $subject = 'Password Reset Link';
    $message = 'It seems you\'ve forgotten your password. Click <a href="http://isense.cs.uml.edu/reset.php?auth=' . $auth . '"> here </a> to reset your password';


    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

    $headers .= 'From: admin@127.0.0.1';

    if (  mail('"'.$email.'"', $subject, $message, $headers)) {
      $smarty->assign('success', 1);
    }
    else {
      $smarty->assign('success', 0);
    }

  } else {
      $smarty->assign('success', -1);
  }
  $smarty->assign('content', $smarty->fetch('reset.tpl'));
}


if( isset($_GET['auth']) && !isset($_POST['pass1']) ) {
  $smarty->assign('auth', $_GET['auth'] );
} 

if( isset($_POST['pass1']) ) {
  if( $_POST['pass1'] == $_POST['pass2'] ) {
    $smarty->assign('done', 1 );
    $db->query('UPDATE users SET password="' . md5($_POST['pass1']) . '" WHERE auth="' . $_GET['auth'] . '"' );
  }
}

$smarty->assign('errors', $errors);
if( isset($_SERVER['HTTP_REFERER']) )
  $smarty->assign('referer', $_SERVER['HTTP_REFERER']);

$smarty->assign('remember', isset($_POST['remember']));
$smarty->assign('content', $smarty->fetch('reset.tpl')); 
$smarty->assign('title', 'Reset Password');
$smarty->assign('user', $session->getUser());

$smarty->display('skeleton.tpl');

?>
