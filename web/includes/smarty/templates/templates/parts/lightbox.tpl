<div id="hiddenModalContent" style="display:none;">
	<div id="wizard_wrapper" style="width:600px;">
		<div id="step_start" class="wizard_step">
		    
			<strong>Will you use the iSENSE PinPoint Board?</strong>
			<div class="wizard_step_content">
			    <br>
				<input type="radio" name="fieldSelect" value="custom" checked> Set up fields manually.</input><br>
				<input type="radio" name="fieldSelect" value="pinpoint"> Set up fields for Pinpoint.</input><br>
				<input type="radio" name="fieldSelect" value="upinpoint"> Set up fields for uPinpoint.</input><br><br>
				<button onclick="createWizard.step_start();">Next</button>
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
						<select id="external_port_A" name="external_port_A">
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
						<select id="external_port_B" name="external_port_B">
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
						<select id="external_port_C" name="external_port_C">
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
						<select id="external_port_D" name="external_port_D">
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
		
		<!--  -->
		
		<div id="step_upinpoint" class="wizard_step" style="display:none;">
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
					<td><input id="pressure" name="pressure" type="checkbox" /></td>
					<td colspan="2">Pressure</td>
				</tr>
				<tr>
					<td><input id="altitude" name="altitude" type="checkbox" /></td>
					<td colspan="2">Altitude</td>
				</tr>
				<tr>
					<td><input id="acceleration" name="acceleration" type="checkbox" /></td>
					<td colspan="2">Acceleration</td>
				</tr>
				<tr>
					<td><input id="light" name="light" type="checkbox" /></td>
					<td colspan="2">Light</td>
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

			</table>
			</div>
		</div>
		
		<!--  -->
		
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