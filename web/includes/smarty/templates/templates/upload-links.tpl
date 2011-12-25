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
{ if $user.guest }
<div id="main-full">
	<div>Guests do not have access to contribute to links. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ else }
	<div id="main">
		{ if $done }
			<fieldset id="basic-info">
				<legend>Successfully Added a Link</legend>
				<p>You've successfully added a link. You can <a href="upload-link.php?id={ $meta.experiment_id }">upload more</a> or <a href="experiment.php?id={ $meta.experiment_id }">view your links.</a></p>
			</fieldset>
		{ else }
			{ include file="parts/errors.tpl" }
			<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
				<fieldset id="basic-info">
					<legend>Upload a new link</legend>
					<p>Your video will be uploaded with the following information.</p>
					<table id="links_table" class="links_table" width="100%">
						<tr id="template" style="display:none;">
							<td class="heading" valign="top">Link 1:</td>
							<td class="link_box">
								<span class="text_box"><input id="link_xxx" name="link_xxx" type="text" /></span>
								<span class="title_box" style="display:none"></span>
							</td>
						</tr>
						<tr id="1">
							<td class="heading" valign="top">Link 1:</td>
							<td><input id="link_1" name="link_1" type="text" /></td>
						</tr>
					</table>
					<input type="hidden" id="row_count" name="row_count" value="1" />
					<button type="button" onclick="addLinkRow();">Add Another Link</button><br/>
			  	</fieldset>
				<fieldset>
					<legend>Review and Finish</legend>
			    	<p>Your video will be uploaded with the information entered above.</p>
					<input type="hidden" name="id" value="{ $meta.experiment_id }" />
					<input type="submit" name="link_create" value="Upload Link" /><br/>
			  	</fieldset>
			</form>
	  	{ /if }
	</div>

	<div id="sidebar">
		<div class="module">
	   		<h1>Also working on this project:</h1>
			<p>Here are a list of people working with { $first } on this experiment.</p>
	    	<ul>
				{ foreach from=$collabs item=collab }
	      			<li><a href="profile.php?id={ $collab.user_id }">{ $collab.firstname } { $collab.lastname }</a></li>
				{ /foreach }
	    	</ul>
	  	</div>
	</div>
{ /if }