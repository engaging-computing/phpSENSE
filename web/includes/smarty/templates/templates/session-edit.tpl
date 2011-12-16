{ if $user.guest }
<div id="main-full">
	<div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ elseif $user.user_id != $values.owner_id and $user.administrator != 1 }
    <div id="main-full">
		<div>Sorry, you are not the owner of this session so you are not allowed to edit it.</div>
	</div>
{ else }
	<div id="main">
		{ include file="parts/errors.tpl" }
		{ if !$created }
		    <form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
    			<fieldset id="basic-info">
    			    <legend>Step 1: Make Your Changes</legend>
    		    	<p>Your session will be created with the following information.</p>
    				<label for="session_name">Name:</label><input type="text" name="session_name" value="{ $values.name }"/><br/>
    		    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
    		    	<label for="session_description">Procedure:</label><textarea name="session_description">{ $values.description }</textarea><br/>
    		    	<span class="hint">Describe the session procedure and other details.</span><br/>
    				<label for="session_street">Street:</label><input type="text" name="session_street" value="{ $values.street }"/><br/>
    				<span class="hint">Example: "4 Yawkey Way"</span><br/>
    				<label for="session_citystate">City, State:</label><input type="text" name="session_citystate" value="{ $values.city }"/><br/>
    				<span class="hint">Example: "Boston, Ma"</span><br/>
    				{ if $user.administrator == 1 } 
    				    <label for="session_hidden">Hidden:</label><input type="checkbox" name="session_hidden" { if $values.finalized == 0 } checked="checked" { /if } ><br/>
    				{ /if }
    			</fieldset>
    			<fieldset>
			    	<legend>Step 2: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing changes, click the Save Session button to continue.</p>
			    	<input type="hidden" id="id" name="id" value="{$values.session_id}" />
					<button id="session_create" name="session_create" type="submit">Save Session</button>
				</fieldset>
    			
    		</form>
		{ else }
		    <fieldset id="basic-info">
				<legend>You've successfully edited your session!</legend>
				<p>Capital job, you've successfully edited your session! Click <a href="vis.php?sessions={ $sid }">here</a> to view it.
			</fieldset>
		{ /if }
	</div>
{ /if }

