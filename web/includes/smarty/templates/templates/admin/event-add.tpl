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
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			{ if $done }
				<p>You have successfully created a new event. <a href="admin.php?action=eventadd">Click here</a> to create another.</p>
			{ else }
				{include file="parts/errors.tpl"}
				<form method="post">
					<table width="100%" id="management_table" class="profile">
						<tr>
							<td class="heading" valign="top">Title:</td>
							<td><input type="text" name="title" id="title" /</td>
						</tr>
						<tr>
							<td class="heading" valign="top">Location:</td>
							<td><input type="text" name="location" id="location" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Starts:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="start" id="start" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Ends:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="end" id="end" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Description:</td>
							<td><textarea cols="60" name="description" id="description" rows="10"></textarea></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" id="create" name="create" value="Create"></td>
						</tr>
					</table>
				</form>
			{ /if}
		</div>
	</div>
</div>