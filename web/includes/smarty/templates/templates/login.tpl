<div id="main-full">
{ if $user.guest }
	{include file="parts/errors.tpl"}
  	<div style="padding-bottom:8px;">Don't have an account? Click <a href="register.php">here</a> to create one.</div>
  	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Username or Email:</label></td>
				<td><input type="text" name="email" value="{ $email }" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="password">Password:</label></td>
				<td><input type="password" name="password" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" name="remember" { if $remember }checked{ /if }/> Stay logged in for two weeks</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				    <div style="padding-top:8px;">
				        <button type="submit" name="submit">Login</button> or <a href='reset.php'>Forgot password?</a>
				    </div>
				</td>
			</tr>
  		</table>
  		<input type="hidden" name="referer" value="{ $referer }"/>
  	</form>
{ else }
	<p>You are already logged in as <a href="profile.php">{ $user.email }</a>. Click <a href="logout.php">here</a> to logout.</p>
{ /if }
</div>
