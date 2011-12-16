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