<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

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

///////////////////////////
/* Disable mobile website /
///////////////////////////
if(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!= true){
    $smarty->assign('content', $smarty->fetch('login.tpl'));
    $smarty->display('skeleton.tpl');
}else{
    $smarty->display('mobile/login.tpl');
}
*/

?>
