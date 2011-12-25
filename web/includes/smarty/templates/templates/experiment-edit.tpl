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