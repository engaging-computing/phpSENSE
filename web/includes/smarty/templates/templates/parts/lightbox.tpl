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
				{ foreach from=$defaultSensors item=sval}
				<tr>
					<td><input id="{$sval}" name="{$sval}" type="checkbox" /></td>
					<td colspan="2">{$sval|capitalize}</td>
				</tr>
				{ /foreach }
				<tr>
					<td><input type="checkbox" id="external" name="external" {literal} onclick="if(this.checked){$('#external_type_wrapper').show(); }else{ $('#external_type_wrapper').hide(); }" {/literal} /></td>
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
		</div>
	</div>
</div>