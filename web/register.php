<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
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
 -->
<?php

require_once 'includes/config.php';
require_once LIB_DIR . 'recaptchalib.php';

$email = "";
$confirm_email = "";
$password = "";
$confirm_password = "";
$first_name = "";
$last_name = "";
$street = "";
$city_state = "";
$country = "";
$errors = array();
$error = "";
$registered = false;

if(isset($_POST['submit'])) {
	
	if(isset($_POST['email']) && $_POST['email'] != "") { $email = safeString($_POST['email']); }
	if($email == "") { array_push($errors, 'Password can not be blank.'); }

	//if(isset($_POST['confirmemail'])) { $confirm_email = safeString($_POST['confirmemail']); }
	//if($confirm_email == "") { array_push($errors, 'Password can not be blank.'); }

	//if($email != $confirm_email) { array_push($errors, 'Email addresses do not match'); }

	if(isset($_POST['password'])) { $password = safeString($_POST['password']); }
	if($password == "") { array_push($errors, 'Password can not be blank.'); }

	if(isset($_POST['confirmpassword'])) { $confirm_password = safeString($_POST['confirmpassword']); }
	if($confirm_password == "") { array_push($errors, 'Password can not be blank.'); }

	if(isset($_POST['fname'])) { $first_name = safeString($_POST['fname']); }
	if($first_name == "") { array_push($errors, 'First name can not be blank.'); }

	if(isset($_POST['lname'])) { $last_name = safeString($_POST['lname']); }
	if($last_name == "") { array_push($errors, 'Last name can not be blank.'); }

	if(isset($_POST['street'])) { $street = safeString($_POST['street']); }
	if($street == "") { array_push($errors, 'Street can not be blank.'); }

	if(isset($_POST['citystate'])) { $city_state = safeString($_POST['citystate']); }
	if($city_state == "") { array_push($errors, 'City and state can not be blank.'); }

	if(isset($_POST['country'])) { $country = safeString($_POST['country']); }
	if($country == "") { array_push($errors, 'Country can not be blank.'); }
	
	if($password != $confirm_password) { array_push($errors, 'Passwords do not match'); }
	
	$resp = recaptcha_check_answer(	RECAPTCHA_PRIVATE,
                                  	$_SERVER["REMOTE_ADDR"],
                                   	$_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);
	if ($resp->is_valid) {
		if(count($errors) == 0) {
			if(register($email, $first_name, $last_name, $password, $street, $city_state, $country)) {
				$registered = true;
			} elseif (!assertUserDoesNotExists($email)) 
			{
                          array_push($errors, 'An account already exists with your email address.');
			}
		}
	}
	else {
		array_push($errors, 'You failed the reCAPATCHA test. Are you sure you\'re human?');
	} 
}

if(!$registered) {
	$smarty->assign('recapatcha', recaptcha_get_html(RECAPTCHA_PUBLIC, $error));
}
else {
	$session->login($email, $password);
}

$smarty->assign('errors', $errors);
$smarty->assign('registered', $registered);
$smarty->assign('email', $email);
$smarty->assign('fname', $first_name);
$smarty->assign('lname', $last_name);
$smarty->assign('street', $street);
$smarty->assign('country', $country);
$smarty->assign('citystate', $city_state);
$smarty->assign('confirmemail', $confirm_email);

$smarty->assign('title', 'Register');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('register.tpl'));
$smarty->display('skeleton.tpl');

?>
