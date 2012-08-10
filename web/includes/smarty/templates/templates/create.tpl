{*
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
 *}
{ if $user.guest }
	<div id="main-full">
		<div>You must be logged in to create and experiment. </br> If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
{ else }
	<div id="main">
		{include file="parts/errors.tpl"}
		<form name="create_form" id="create_form" method="post">
		   
	 		{ if !$created }
				<fieldset id="basic-info">
				    
					<legend>Step 1: Basic Info</legend>
		    		<p>Please describe the basics of your experiment, and the experimental procedure, and its keywords.</p>
		    		
		    		<label for="experiment_name">* Name:</label>
		    		    <input type="text" name="experiment_name" id="experiment_name" class="required urlSafe" value="{$values.name}" />
		    		    <img id="experiment_name_validated" src="/html/img/validated.png" class="validated vfloat" />
		    		    <img id="experiment_name_failed" class="failed vfloat" src="/html/img/failed.png" /><br/>
		    		    <span class="hint">Example: "Salinity Levels in Rivers"</span><br/>

		    		<label for="experiment_description">* Procedure:</label>
		    		    <textarea name="experiment_description" class="required" id="experiment_description" >{$values.description}</textarea>
		    		    <img id="experiment_description_validated" src="/html/img/validated.png" style="top:-15px" class="validated vfloat" />
		    		    <img id="experiment_description_failed" class="failed vfloat" src="/html/img/failed.png" /><br/>
		    		    <span class="hint">Describe the experimental procedure and other details.</span><br/>
		    		
		    		<label for="experiment_tags">Tags:</label>
		    		    <input type="text" id="experiment_tags" name="experiment_tags" class="" value="{$values.tags}" />
		    		    <span class="hint">Tags are keywords associated with an experiment. Separate tags with commas.<br/>Example: salinity river water</span><br/>
		    		    
		    		<label for="experiment_subject">Subject:</label><br />
		    		    <div >
							<input style="margin:5px;float:left" id="add_tag_math" name="add_tag_math" value="yes" type="checkbox" class="checkbox" /> Mathematics 
							<input style="margin:5px;" id="add_tag_phys" name="add_tag_phys" value="yes" type="checkbox" class="checkbox" /> Physics  
							<input style="margin:5px;" id="add_tag_chem" name="add_tag_chem" value="yes" type="checkbox" class="checkbox" /> Chemistry 
							<input style="margin:5px;" id="add_tag_bio" name="add_tag_bio" value="yes" type="checkbox" class="checkbox" /> Biology  
							<input style="margin:5px;" id="add_tag_earth" name="add_tag_earth" value="yes" type="checkbox" class="checkbox" /> Earth Science
						</div><br />
                        
                        
    					<span style="float:none" class="hint">Select the subject area or areas that best describe your experiment.</span><br /><br />
                    
                        <legend>Step 2: Session Options</legend><br />
                 
                        <div class="options">
                        
                            <div>
                                <div style="float:left;width:50%">Should collaborators enter a procedure?</div>
                                    <div><select name="req_procedure" id="req_procedure">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select><br /><br /></div>
                            </div>

                            <div>
                                <div style="float:left;width:50%">Should collaborators enter session names?</div>
                                    <div><select class="sel" name="req_name" id="req_name">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select><br /><br /></div>
                                    
                                <div id="name_pref">&nbsp;&nbsp;<b>Enter name prefix:</b></div>
                                    <input type="text" name="name_prefix" id="name_input" style="position:absolute;left:31%;width:27%"/><br />
                                    <br />
                                    <span style="float:none;left:5%" id="name_hint" class="hint">e.g., Session #: 1 (numbers will be appended automatically)</span><br />                          
                           </div>

                           <div>
                                <div style="float:left;width:50%">Should collaborators enter a location?</div>
                                    <div><select name="req_location" id="req_location">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select><br /><br /></div>
                                    
                                <div id="loc_label">&nbsp;&nbsp;<b>Enter location:</b></div>
                                    <input type="text" name="location" id="loc_input" style="position:absolute;left:31%;width:27%" /><br />
                                    <br />
                                    <span style="float:none;left:5%" id="loc_hint" class="hint">i.e., All sessions will have the same location</span><br />

                           </div>
                           <div>
                                <div style="float:left;width:50%">Is there a recommended sample rate?</div>
                                    <div><select name="req_sample_rate" id="req_sample_rate">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select><br /><br /></div>
                                    
                                <div id="sample_rate_option" style="display:none;">
                                    <span id="srate_label">&nbsp;&nbsp;<b>Sample rate:</b></span>
                                    <input type="number" name="srate" id="srate_input" style="position:absolute;left:31%;width:15%" /><br /><br />
                                    <span style="float:none;left:5%" id="srate_hint" class="hint">i.e., 1000 milliseconds is 1 second</span><br />
                                </div>
                           </div>
                        </div>
				</fieldset>
				
				<fieldset id="fields">
				    
		    		<legend>Step 3: Data Fields</legend>
					    <div id="setup_button">
		    			    <p style="padding:6px 0px;">Click the 'Setup Data Fields' button to start the process of setting up your data fields.</p>
						    <a href="#TB_inline?height=400&width=600&inlineId=hiddenModalContent" class="thickbox" style="text-decoration:none;"><button type="button">Setup Data Fields</button></a>
					    </div>
					    <div id="setup_summary" style="display:none;">
						    <p style="padding:6px 0px;">The data fields you have selected for your experiment are:</p>
						    <div id="fields_list"></div>
						    <a href="#TB_inline?height=400&width=600&inlineId=hiddenModalContent" onclick="createWizard.reset_all()" class="thickbox" style="text-decoration:none;"><button type="button">Change Data Fields</button></a>

					    </div>

					<input id="number_of_fields" name="number_of_fields" type="hidden" value="0" />
					<div id="data_wrapper" style="display:none;"></div>

					{include file="parts/lightbox.tpl"}
					
		  		</fieldset>

				<fieldset>
			    	<legend>Step 4: Review and Finish</legend>
			    	    <p style="padding:6px 0px;">When you are finished reviewing your experiment, click the Create Experiment button to continue.</p>
					    <button id="experiment_create" name="experiment_create" type="submit">Create Experiment</button>
				</fieldset>
			{ else }
			
			
				<fieldset id="basic-info">
					<legend>You've successfully created an experiment!</legend>
					    <p>Congratulations you've successfully created a new experiment! Click <a href="experiment.php?id={ $eid }">here</a> to get started.
				</fieldset>
			{ /if }
		</form>
	</div>
	
	<div id="sidebar">
		<div class="module">
	    	<h1>Some Helpful Tips</h1>
	    	<ul style="list-style-type:disc; margin:0px 0px 0px 14px;">
	      		<li style="margin:0px 0px 6px 0px;">Please be as specific as possible in naming your experiment and describing your experimental procedure. This will help other users find your experiment and collect meaningful data.</li>
	      		<li>Associating keywords (aka "Tags") with your experiment is also important. Note that auto-complete is enabled in the Tags text entry area to prevent the introduction of duplicative tags.</li>
	    	</ul>
	  	</div>
	</div>
{ /if }
