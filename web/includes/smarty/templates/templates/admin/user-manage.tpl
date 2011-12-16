<div id="main-full">
	<div id="management_toolbar">
		<div>
			<!-- <input type="submit" id="usernew" value="New User" /> -->
			<input type="submit" id="userdelete" value="Delete User" onclick="deleteUser();" />
			<input type="submit" id="userreset" value="Reset Password" onclick="resetPass();" /> 
			<input type="submit" id="useradmin" value="Make Administrator" onclick="adminUser();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllAdminUsers();">Admins</a>, 
			<a href="javascript:void(0);" onclick="checkAllRegularUsers();">Regular Users</a>
		</div>
	</div>
	<table width="100%" id="management_table" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Email Address</td>
			<td>Joined On</td>
			<td>Administrator?</td>
			<td>Profile</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=datum }
				<tr>
					<td align="center"><input type="checkbox" name="news_{ $datum.user_id }" id="news_{ $datum.user_id }" value="{ $datum.user_id }" /></td>
					<td>{ $datum.firstname|capitalize }</td>
					<td>{ $datum.lastname|capitalize }</td>
					<td><a href="mailto:{$datum.email}">{ $datum.email }</a></td>
					<td>{ $datum.firstaccess }</td>
					<td class="administrator">{ if $datum.administrator == 1 }Yes{ else }No{ /if }</td>
					<td><a href="profile.php?id={ $datum.user_id}">View</a></td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any users.</td>
			</tr>
		{ /if }
	</table>
</div>
