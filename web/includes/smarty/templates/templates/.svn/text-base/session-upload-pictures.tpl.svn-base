{ if $user.guest }
<div id="main-full">
	<div>Guests do not have access to contribute to pictures. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ else }
	<div id="main">
		{ if $done }
			<fieldset id="basic-info">
				<legend>Successfully Uploaded Pictures</legend>
				<p>You've successfully uploaded pictures. You can <a href="session-upload-pictures.php?id={ $id }&sid={ $sid }">upload more</a> or <a href="experiment.php?id={ $id }">view your pictures</a>.</p>
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
