{ if $user.guest }
	<div id="main-full">
		<div>Guests do not have permission to create activities. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
{ else }
	<div id="main">
		{include file="parts/errors.tpl"}
		{ if not $done }
		    <form method="post">
    		    <fieldset id="basic-info">
    				<legend>Activity Setup</legend>
    	    		<p>Please describe the basics of your activity, and what you would like students to do for the prompt.</p>
    	    		<label for="experiment_name">Name:</label><input type="text" name="name" value="{$values.name}" /><br/>
    	    		<span class="hint">Example: "Cooling Curves Of Cups"</span><br/>
    	    		<label for="experiment_description">Instructions:</label><textarea name="description">{$values.description}</textarea><br/>
    	    		<span class="hint">Determine which material cools the fastest.</span><br/>
    	    	</fieldset>
    	    	<fieldset>
    		    	<legend>Review and Finish</legend>
    		    	<p style="padding:6px 0px;">When you are finished reviewing your activity, click the Create Activity button to continue.</p>
    				<button id="activity_create" name="activity_create" type="submit">Create Activity</button>
    			</fieldset>
    			<input type="hidden" id="sessions" name="sessions" value="{$values.sessions}" />
    			<input type="hidden" id="eid" name="eid" value="{$values.eid}" />
    			<input type="hidden" id="uid" name="uid" value="{$values.uid}" />
    		</form>
    	{ else }
    	    <div>You've successfully created an activity! You can access your activity using this link: <a href="http://{$smarty.server.SERVER_NAME}/activity.php?id={$aid}">http://{$smarty.server.SERVER_NAME}/activity.php?id={$aid}</a></div>
		{ /if }
	</div>
	<div id="sidebar">
	</div>
{ /if }
