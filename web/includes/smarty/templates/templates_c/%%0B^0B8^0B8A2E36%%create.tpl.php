<?php /* Smarty version 2.6.22, created on 2011-08-18 08:33:59
         compiled from create.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
	<div id="main-full">
		<div>Guests do not have profile. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
<?php else: ?>
	<div id="main">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<form method="post">
	 		<?php if (! $this->_tpl_vars['created']): ?>
				<fieldset id="basic-info">
					<legend>Step 1: Basic Info</legend>
		    		<p>Please describe the basics of your experiment, and the experimental procedure, and its keywords.</p>
		    		<label for="experiment_name">* Name:</label><input type="text" name="experiment_name" value="<?php echo $this->_tpl_vars['values']['name']; ?>
" /><br/>
		    		<span class="hint">Example: "Salinity Levels in Rivers"</span><br/>
		    		<label for="experiment_description">* Procedure:</label><textarea name="experiment_description"><?php echo $this->_tpl_vars['values']['description']; ?>
</textarea><br/>
		    		<span class="hint">Describe the experimental procedure and other details.</span><br/>
		    		<label for="experiment_tags">Tags:</label><input type="text" id="experiment_tags" name="experiment_tags" value="<?php echo $this->_tpl_vars['values']['tags']; ?>
" />
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
										<td colspan="2">Temperature</td>
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
									<tr>
										<td><input type="checkbox" id="external" name="external" <?php echo ' onclick="if(this.checked){$(\'#external_type_wrapper\').show(); }else{ $(\'#external_type_wrapper\').hide(); }" '; ?>
 /></td>
										<td>External</td>
										<td style="display:none;"></td>
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
									<tr id="external_type_wrapper" style="display:none;">
										<td>&nbsp;</td>
										<td>External Sensor Type:</td>
										<td>
											<select id="external_type" name="external_type">
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
												<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
												<option value="<?php echo $this->_tpl_vars['type']['type_id']; ?>
"><?php echo $this->_tpl_vars['type']['name']; ?>
</option>
												<?php endforeach; endif; unset($_from); ?>
											</select>
										</td>
										<td>
											<select id="custom_field_unit_xxx" id="custom_field_unit_xxx" style="width:201px;">
												<option value="-1">Select one...</option>
												<?php $_from = $this->_tpl_vars['type_units']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['unit']):
?>
												<option ref="<?php echo $this->_tpl_vars['unit']['type_id']; ?>
" value="<?php echo $this->_tpl_vars['unit']['unit_id']; ?>
"><?php echo $this->_tpl_vars['unit']['unit_name']; ?>
</option>
												<?php endforeach; endif; unset($_from); ?>
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
												<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
												<option value="<?php echo $this->_tpl_vars['type']['type_id']; ?>
"><?php echo $this->_tpl_vars['type']['name']; ?>
</option>
												<?php endforeach; endif; unset($_from); ?>
											</select>
										</td>
										<td>
											<select id="custom_field_unit_1" name="custom_field_unit_1" style="width:201px;" class="custom_field_unit">
												<option ref="-1" value="-1">Select one...</option>
												<?php $_from = $this->_tpl_vars['type_units']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['unit']):
?>
												<option ref="<?php echo $this->_tpl_vars['unit']['type_id']; ?>
" value="<?php echo $this->_tpl_vars['unit']['unit_id']; ?>
"><?php echo $this->_tpl_vars['unit']['unit_name']; ?>
</option>
												<?php endforeach; endif; unset($_from); ?>
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
							</div>
						</div>
					</div>
		  		</fieldset>

				<fieldset>
			    	<legend>Step 3: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing your experiment, click the Create Experiment button to continue.</p>
					<button id="experiment_create" name="experiment_create" type="submit" disabled="disabled">Create Experiment</button>
				</fieldset>
			<?php else: ?>
				<fieldset id="basic-info">
					<legend>You've successfully created an experiment!</legend>
					<p>Congratulations you've successfully created a new experiment! Click <a href="experiment.php?id=<?php echo $this->_tpl_vars['eid']; ?>
">here</a> to get started.
				</fieldset>
			<?php endif; ?>
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
<?php endif; ?>