{ if $user.guest }
	<div id="main-full">
		<div>Guests do not have profile. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
{ elseif $user.user_id != $values.owner_id and $user.administrator != 1 }
    <div id="main-full">
		<div>Sorry, you are not the owner of this experiment so you are not allowed to edit it.</div>
	</div>
{ else }
	<div id="main">
		{include file="parts/errors.tpl"}
		<form method="post">
	 		{ if !$created }
				<fieldset id="basic-info">
					<legend>Step 1: Basic Info</legend>
		    		<p>Please describe the basics of your experiment, and the experimental procedure, and its keywords.</p>
		    		<label for="experiment_name">Name:</label></input><input type="text" name="experiment_name" value="{$values.name}" /><br/>
		    		<span class="hint">Example: "Salinity Levels in Rivers"</span><br/>
		    		<label for="experiment_description">Procedure:</label><textarea name="experiment_description">{$values.description}</textarea><br/>
		    		<span class="hint">Describe the experimental procedure and other details.</span><br/>
		    		<label for="experiment_tags">Tags:</label><input type="text" id="experiment_tags" name="experiment_tags" value="{$values.tags}" />
		    		<span class="hint">Tags are keywords associated with an experiment. Separate tags with commas.<br/>Example: salinity river water</span><br/>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="90px" valign="top"><label for="experiment_subject">Subject:</label></td>
							<td>
								<div style="padding:0px 0px 7px 0px;">
									<input id="add_tag_math" name="add_tag_math" value="yes" type="checkbox" class="checkbox"> Mathematics 
									<input id="add_tag_phys" name="add_tag_phys" value="yes" type="checkbox" class="checkbox"> Physics  
									<input id="add_tag_chem" name="add_tag_chem" value="yes" type="checkbox" class="checkbox"> Chemistry 
									<input id="add_tag_bio" name="add_tag_bio" value="yes" type="checkbox" class="checkbox"> Biology  
									<input id="add_tag_earth" name="add_tag_earth" value="yes" type="checkbox" class="checkbox"> Earth Science
								</div>
							</td>
						</tr>
					</table>
					<span class="hint">Select the subject area or areas that best describe your experiment.</span>
				</fieldset>

				<fieldset>
			    	<legend>Step 2: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing changes, click the Save Experiment button to continue.</p>
			    	<input type="hidden" id="id" name="id" value="{$values.experiment_id}" />
					<button id="experiment_create" name="experiment_create" type="submit">Save Experiment</button>
				</fieldset>
			{ else }
			    <fieldset id="basic-info">
					<legend>You've successfully edited your experiment!</legend>
					<p>Nice job, you've successfully edited your experiment! Click <a href="experiment.php?id={ $eid }">here</a> to view it.
				</fieldset>
		    { /if }
		</form>
	</div>
{ /if }