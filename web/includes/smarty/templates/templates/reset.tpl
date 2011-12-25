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
<div id="main-full">
{include file="parts/errors.tpl"}
{ if $success eq 1 }
	<div>You have been sent a link to reset your password in your email.</div>
{ elseif $success eq -1 }
	<div>You have entered an invalid E-mail address. Please try again.</div>

{ elseif $auth }
	<div>Enter your new password.</a></div>
	<form method="post">
		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">New password:</label></td>
				<td><input type="password" name="pass1" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Confirm password:</label></td>
				<td><input type="password" name="pass2" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td><input type="hidden" name="referer" value="{ $referer }"/><button type="submit" name="submit">Reset</button></td>
			</tr>
  		</table>
  	</form>
{ elseif $done }  
	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 100%; padding-right: 10px;"><label for="email">Your password has been reset!</label></td>
			</tr>
  		</table>
  	</form>

{ else }
	<div>Need to reset your password?</a></div>	
	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Username or Email:</label></td>
				<td><input type="text" name="email" value="{ $email }" style="width: 200px;"/></td>
				<td><input type="hidden" name="referer" value="{ $referer }"/><button type="submit" name="submit">Reset</button></td>
			</tr>
  		</table>
  	</form>
	{ /if }

</div>
