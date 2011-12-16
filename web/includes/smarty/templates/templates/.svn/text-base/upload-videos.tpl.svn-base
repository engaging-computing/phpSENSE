{ if $user.guest }
<div id="main-full">
	<div>Guests do not have access to contribute to videos. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ else }
	<div id="main">
		{ if $done }
			<fieldset id="basic-info">
				<legend>Successfully Uploaded a Video</legend>
				<p>You've successfully uploaded a video. You can <a href="upload-video.php?id={ $meta.experiment_id }">upload another</a> or <a href="experiment.php?id={ $meta.experiment_id }">watch your video.</a></p>
			</fieldset>
		{ else }
			{ include file="parts/errors.tpl" }
			<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
				<fieldset id="basic-info">
					<legend>Upload a new video</legend>
			    	<p>Your video will be uploaded with the following information.</p>
			    	<label for="video_name">Title:</label><input type="text" name="video_name" value="{$values.vtitle}" /><br/>
			    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="video_description">Description:</label><textarea name="video_description">{$values.description}</textarea><br/>
			    	<span class="hint">Describe what the video is depicting.</span><br/>
					<label for="video_street">Street:</label><input type="text" name="video_street" values="{$values.street}" /><br/>
					<span class="hint">Example: "4 Yawkey Way"</span><br/> 
					<label for="video_citystate">City, State:</label><input type="text" name="video_citystate" values="{$values.citystate}"/><br/>
					<span class="hint">Example: "Boston, Ma"</span><br/>
					<label for="video_file">Video File:</label><input type="file" name="video_file"/><br/>
					<span class="hint">Click browse and select your video file.</span><br/>
			  	</fieldset>
				<fieldset>
					<legend>Review and Finish</legend>
			    	<p>Your video will be uploaded with the information entered above.</p>
					<input type="hidden" name="id" value="{ $meta.experiment_id }" />
					<input type="submit" name="video_create" value="Upload Video" /><br/>
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