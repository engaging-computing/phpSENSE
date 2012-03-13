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
		<div>Guests do not have profile. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
{ else }
	<div id="main">
		{include file="parts/errors.tpl"}
		<form name="create_form" id="create_form" method="post">
	 		{ if !$created }
				<fieldset id="basic-info">
					<legend>Step 1: Basic Info</legend>
		    		<p>Please describe the basics of your experiment, and the experimental procedure, and its keywords.</p>
		    		<label for="experiment_name">* Name:</label><input type="text" name="experiment_name" id="experiment_name" class="required urlSafe" /><img height="10px" width="10px" id="experiment_name_validated" src="/html/img/validated.png" style="position:relative;left:-15px;" class="validated" /><img height="10px" width="10px" id="experiment_name_failed" class="failed" src="/html/img/failed.png" style="position:relative;left:-15px;" /><br/>
		    		<span class="hint">Example: "Salinity Levels in Rivers"</span><br/>
		    		<label for="experiment_description">* Procedure:</label><textarea name="experiment_description" class="required" id="experiment_description" >{$values.description}</textarea><img height="10px" width="10px" id="experiment_description_validated" src="/html/img/validated.png" style="position:relative;left:-15px;top:-15px" class="validated" /><img height="10px" width="10px" id="experiment_description_failed" class="failed" src="/html/img/failed.png" style="position:relative;left:-15px;top:-15px" /><br/>
		    		<span class="hint">Describe the experimental procedure and other details.</span><br/>
		    		<label for="experiment_tags">Tags:</label><input type="text" id="experiment_tags" name="experiment_tags" class="required" value="{$values.tags}" />
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
					<span id="requiredfields">* <span id="requiredfieldstext">Denotes a required field.</span></span>
				</fieldset>
				<fieldset id="fields">
		    		<legend>Step 2: Data Fields</legend>
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

					<div id="hiddenModalContent" style="display:none;">
						<div id="wizard_wrapper" style="width:600px;">
							<div id="step_start" class="wizard_step">
								<strong>Will you use the iSENSE PinPoint Board?</strong>
								<div class="wizard_step_content">
									<input type="submit" value="Yes" onclick="createWizard.step_start(true);" /> <input type="submit" value="No" onclick="createWizard.step_start(false);" />
								</div>
							</div>
							<div id="step_pinpoint" class="wizard_step" style="display:none;">
								<strong>Select the PinPoint sensors you will use.</strong>
								<div class="wizard_step_content">
									<table width="100%">
									<tr id="error" style="display:none;">
										<td id="error_msg" colspan="3"></td>
									</tr>
									<tr>
										<th align="left">&nbsp;</th>
										<th colspan="2" align="left" width="169px">Sensor</th>
									</tr>
									<tr>
										<td><input id="temperature" name="temperature" type="checkbox" /></td>
										<td colspan="2">Air Temperature</td>
									</tr>
									<tr>
										<td><input id="light" name="light" type="checkbox" /></td>
										<td colspan="2">Light</td>
									</tr>
									<tr>
										<td><input id="acceleration" name="acceleration" type="checkbox" /></td>
										<td colspan="2">Acceleration</td>
									</tr>
									<tr>
										<td><input id="altitude" name="altitude" type="checkbox" /></td>
										<td colspan="2">Altitude</td>
									</tr>
									<tr>
										<td><input id="pressure" name="pressure" type="checkbox" /></td>
										<td colspan="2">Pressure</td>
									</tr>
									<tr>
										<td><input id="humidity" name="humidity" type="checkbox" /></td>
										<td colspan="2">Humidity</td>
									</tr>

									<!--
									<tr id="external_label_wrapper" style="display:none;">
										<td>&nbsp;</td>
										<td>External Label:</td>
										<td>
											<input id="external_label" name="external_label" type="text" disabled />
										</td>
									</tr>
									-->
									
									<tr>
										<td><input type="checkbox" id="external_A" name="external_A" {literal} onclick="if(this.checked){$('#external_type_wrapper_A').show(); }else{ $('#external_type_wrapper_A').hide(); }" {/literal} /></td>
										<td>External</td>
										<td style="display:none;"></td>
									</tr>
									
									<tr id="external_type_wrapper_A" style="display:none;">
										<td>&nbsp;</td>
										<td>External Sensor Type:</td>
										<td>
											<select id="external_type_A" name="external_type_A">
												<option value="0">Select one</option>
												<option value="1">Temperature</option>
												<option value="3">Geiger Counter</option>
												<option value="4">Heart Rate</option>
												<option value="2">Voltage</option>
												<option value="5">PH</option>
												<option value="6">Salinity</option>
												<option value="7">CO2</option>
												<option value="8">Dissolved Oxygen</option>
												<option value="9">Anemometer</option>
												<option value="10">Turbidity</option>
												<option value="11">Flow Rate</option>
												<option value="12">Motor Monitor</option>
												<option value="13">Conductivity</option>
											</select>
										</td>
										<td>
											<select id="external_port_A" name="external_type_A">
												<option value="0">Any</option>
												<option value="1">BTA1</option>
												<option value="2">BTA2</option>
												<option value="3">MINI1</option>
												<option value="4">MINI2</option>
											</select>
										</td>
									</tr>
								
									<tr>
										<td><input type="checkbox" id="external_B" name="external_B" {literal} onclick="if(this.checked){$('#external_type_wrapper_B').show(); }else{ $('#external_type_wrapper_B').hide(); }" {/literal} /></td>
										<td>External</td>
										<td style="display:none;"></td>
									</tr>
									
									<tr id="external_type_wrapper_B" style="display:none;">
										<td>&nbsp;</td>
										<td>External Sensor Type:</td>
										<td>
											<select id="external_type_B" name="external_type_B">
												<option value="0">Select one</option>
												<option value="1">Temperature</option>
												<option value="3">Geiger Counter</option>
												<option value="4">Heart Rate</option>
												<option value="2">Voltage</option>
												<option value="5">PH</option>
												<option value="6">Salinity</option>
												<option value="7">CO2</option>
												<option value="8">Dissolved Oxygen</option>
												<option value="9">Anemometer</option>
												<option value="10">Turbidity</option>
												<option value="11">Flow Rate</option>
												<option value="12">Motor Monitor</option>
												<option value="13">Conductivity</option>
											</select>
										</td>
										<td>
											<select id="external_port_B" name="external_type_B">
												<option value="0">Any</option>
												<option value="1">BTA1</option>
												<option value="2">BTA2</option>
												<option value="3">MINI1</option>
												<option value="4">MINI2</option>
											</select>
										</td>
									</tr>
									
									<tr>
										<td><input type="checkbox" id="external_C" name="external_C" {literal} onclick="if(this.checked){$('#external_type_wrapper_C').show(); }else{ $('#external_type_wrapper_C').hide(); }" {/literal} /></td>
										<td>External</td>
										<td style="display:none;"></td>
									</tr>
									
									<tr id="external_type_wrapper_C" style="display:none;">
										<td>&nbsp;</td>
										<td>External Sensor Type:</td>
										<td>
											<select id="external_type_C" name="external_type_C">
												<option value="0">Select one</option>
												<option value="1">Temperature</option>
												<option value="3">Geiger Counter</option>
												<option value="4">Heart Rate</option>
												<option value="2">Voltage</option>
												<option value="5">PH</option>
												<option value="6">Salinity</option>
												<option value="7">CO2</option>
												<option value="8">Dissolved Oxygen</option>
												<option value="9">Anemometer</option>
												<option value="10">Turbidity</option>
												<option value="11">Flow Rate</option>
												<option value="12">Motor Monitor</option>
												<option value="13">Conductivity</option>
											</select>
										</td>
										<td>
											<select id="external_port_C" name="external_type_C">
												<option value="0">Any</option>
												<option value="1">BTA1</option>
												<option value="2">BTA2</option>
												<option value="3">MINI1</option>
												<option value="4">MINI2</option>
											</select>
										</td>
									</tr>
									
									<tr>
										<td><input type="checkbox" id="external_D" name="external_D" {literal} onclick="if(this.checked){$('#external_type_wrapper_D').show(); }else{ $('#external_type_wrapper_D').hide(); }" {/literal} /></td>
										<td>External</td>
										<td style="display:none;"></td>
									</tr>
	
									<tr id="external_type_wrapper_D" style="display:none;">
										<td>&nbsp;</td>
										<td>External Sensor Type:</td>
										<td>
											<select id="external_type_D" name="external_type_D">
												<option value="0">Select one</option>
												<option value="1">Temperature</option>
												<option value="3">Geiger Counter</option>
												<option value="4">Heart Rate</option>
												<option value="2">Voltage</option>
												<option value="5">PH</option>
												<option value="6">Salinity</option>
												<option value="7">CO2</option>
												<option value="8">Dissolved Oxygen</option>
												<option value="9">Anemometer</option>
												<option value="10">Turbidity</option>
												<option value="11">Flow Rate</option>
												<option value="12">Motor Monitor</option>
												<option value="13">Conductivity</option>
											</select>
										</td>
										<td>
											<select id="external_port_D" name="external_type_D">
												<option value="0">Any</option>
												<option value="1">BTA1</option>
												<option value="2">BTA2</option>
												<option value="3">MINI1</option>
												<option value="4">MINI2</option>
											</select>
										</td>
									</tr>
									
									<tr>
										<td><input id="gps" name="gps" type="checkbox" onclick="$('#gps_label').attr('disabled', !($('#gsp_label').attr('disabled')))" /></td>
										<td>GPS</td>
										<td><input id="gps_label" name="gps_label" type="hidden" disabled /></td>
									</tr>
								</table>
								</div>
							</div>
							<div id="step_custom" class="wizard_step" style="display:none;">
								<strong>Add the fields you will measure.</strong>
								<div class="wizard_step_content">
								<table width="100%" id="customFields">
									<tr id="error_custom" style="display:none;">
										<td id="error_msg_custom" colspan="3"></td>
									</tr>
									<tr id="theader">
										<th align="left">What is this measuring?</th>
										<th	align="left">Type</th>
										<th align="left">Unit</th>
									</tr>
									
									<tr id="template" style="display:none;">
										<td>
											<input id="custom_field_label_xxx" name="custom_field_label_xxx" type="text" />
										</td>
										<td>
											<select id="custom_field_type_xxx" name="custom_field_type_xxx" style="width:201px;">
												<option value="-1">Select one...</option>
												{ foreach from=$types item=type }
												<option value="{ $type.type_id }">{ $type.name }</option>
												{ /foreach }
											</select>
										</td>
										<td>
											<select id="custom_field_unit_xxx" name="custom_field_unit_xxx" style="width:201px;">
												<option value="-1">Select one...</option>
												{ foreach from=$type_units item=unit }
												<option ref="{$unit.type_id}" value="{ $unit.unit_id }">{ $unit.unit_name }</option>
												{ /foreach }
											</select>
										</td>
									</tr>
								
									<tr id="1">
										<td>
											<input id="custom_field_label_1" name="custom_field_label_1" type="text" />
										</td>
										<td>
											<select id="custom_field_type_1" name="custom_field_type_1" style="width:201px;">
												<option value="-1">Select one...</option>
												{ foreach from=$types item=type }
												<option value="{ $type.type_id }">{ $type.name }</option>
												{ /foreach }
											</select>
										</td>
										<td>
											<select id="custom_field_unit_1" name="custom_field_unit_1" style="width:201px;" class="custom_field_unit">
												<option ref="-1" value="-1">Select one...</option>
												{ foreach from=$type_units item=unit }
												<option ref="{$unit.type_id}" value="{ $unit.unit_id }">{ $unit.unit_name }</option>
												{ /foreach }
											</select>
										</td>
									</tr>
									
								</table>
									<div>
										<a href="javascript:void(0);" style="text-decoration:none;" onclick="addField();"><button type="button">Add Data Fields</button></a>
										<a href="javascript:void(0);" style="text-decoration:none;" onclick="removeField();"><button type="button">Remove Data Field</button></a>
										<input type="hidden" id="row_count" name="row_count" value="1" />
									</div>
								</div>
							</div>
							<div id="step_done" class="wizard_step" style="display:none;">
								<strong>Done.</strong>
								<div class="wizard_step_content">You have successfully set up your data types. Simply click the 'Done' button to return to your experiment setup.</div>
							</div>
							<div id="wizard_options" style="display:none;">
								<div class="wizard_step_content">
									<input type="submit" id="create_previous" name="create_previous" value="Back" onclick="createWizard.prev_step();" /> <input type="submit" id="create_advance" name="create_advance" value="Next" onclick="createWizard.next_step();" />
								</div>
								<br>
								<div id="wizard_error" style="display:none;">Error test</div>
							</div>
						</div>
					</div>
		  		</fieldset>

				<fieldset>
			    	<legend>Step 3: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing your experiment, click the Create Experiment button to continue.</p>
					<button id="experiment_create" name="experiment_create" type="submit" disabled="disabled">Create Experiment</button>
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
