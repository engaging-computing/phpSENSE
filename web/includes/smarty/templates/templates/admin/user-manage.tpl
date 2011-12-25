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
