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
