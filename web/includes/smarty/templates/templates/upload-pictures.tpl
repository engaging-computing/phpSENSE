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
	<div>Guests do not have access to contribute to pictures. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ else }
	<div id="main">
		{ if $done }
			<fieldset id="basic-info">
				<legend>Successfully Uploaded Pictures</legend>
				<p>You've successfully uploaded pictures. You can <a href="upload-pictures.php?id={ $smarty.get.id }">upload more</a> or <a href="experiment.php?id={ $smarty.get.id }">view your pictures.</a></p>
			</fieldset>
		{ else }
			{ include file="parts/errors.tpl" }
			<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
				<fieldset id="basic-info">
					<legend>Upload a new picture</legend>
			    	<p>Your video will be uploaded with the following information.</p>
			    	<label for="picture_name">Title:</label><input type="text" name="picture_name" value="{$values.vtitle}" /><br/>
			    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="picture_description">Description:</label><textarea name="picture_description">{$values.description}</textarea><br/>
			    	<span class="hint">Describe what your pictures are illustrating.</span><br/>
					<table id="picture_table" width="480px" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top" width="90px"><label for="picture_file_1">Picture Files:</label></td>
							<td><input type="file" name="picture_file_1"/></td>
						</tr>
						<tr id="template" style="display:none;">
							<td>&nbsp;</td>
							<td><input type="file" name="picture_file_xxx"/></td>
						</tr>
					</table>
					<span class="hint">Click browse and select your image file.</span><br/>
			  	</fieldset>
				<input type="hidden" id="row_count" name="row_count" value="1" />
				<fieldset>
					<legend>Review and Finish</legend>
			    	<p>Your pictures will be uploaded with the information entered above.</p>
					<input type="hidden" name="id" value="{ $meta.experiment_id }" />
					<input type="submit" id="picture_create" name="picture_create" value="Upload Picture" onclick="this.value='Please wait...';" /><br/>
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
