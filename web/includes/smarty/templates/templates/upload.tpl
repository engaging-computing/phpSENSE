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
	<div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
{ else }
	<div id="main">
		{ include file="parts/errors.tpl" }
		<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
			<fieldset id="basic-info">
				{ if $state == 1 }
					<legend>Create a new session</legend>
			    	<p>Your session will be created with the following information.</p>
					<label for="session_name">* Name:</label><input type="text" name="session_name" id="session_name" class="required urlSafe" value="{ $session_name }"/><img height="10px" width="10px" id="session_name_validated" src="/html/img/validated.png" class="validated" style="position:relative;left:-15px;" /><img height="10px" width="10px" id="session_name_failed" src="/html/img/failed.png" class="failed" style="position:relative;left:-15px;" /><br/>
			    	<span id="session_name_hint" class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="session_description">* Procedure:</label><textarea name="session_description" id="session_description" class="required">{ $session_description }</textarea><img height="10px" width="10px" id="session_description_validated" src="/html/img/validated.png" class="validated" style="position:relative;left:-15px;top:-15px;" /><img height="10px" width="10px" id="session_description_failed" src="/html/img/failed.png" class="failed" style="position:relative;left:-15px;top:-15px;" /><br/>
			    	<span id="session_description_hint" class="hint">Describe the session procedure and other details.</span><br/>
					<label for="session_street">* Street:</label><input type="text" name="session_street" id="session_street" value=" " /><img height="10px" width="10px" id="session_street_validated" src="/html/img/validated.png" class="validated" style="position:relative;left:-15px;" /><img height="10px" width="10px" id="session_street_failed" src="/html/img/failed.png" class="failed" style="position:relative;left:-15px;" /><br/>
					<span id="session_street_hint" class="hint">Example: "4 Yawkey Way"</span><br/>
					<label for="session_citystate">* City, State:</label><input type="text" name="session_citystate" id="session_citystate" value="{ $session_citystate }" class="required"/><img height="10px" width="10px" id="session_citystate_validated" src="/html/img/validated.png" class="validated" style="position:relative;left:-15px;" /><img height="10px" width="10px" id="session_citystate_failed" src="/html/img/failed.png" class="failed" style="position:relative;left:-15px;" /><br/>
					<span id="session_citystate_hint" class="hint">Example: "Boston, Ma"</span><br/>
					<label for="session_type">Session Type:</label>
					<div style="width:480px;">
					    <input type="radio" id="manual_upload" name="session_type" group="session_type" value="manual" style="width:20px;" CHECKED /><span>Manual Entry</span>
						<input type="radio" id="file_upload" name="session_type" group="session_type" value="file" style="width:20px;"/><span>Data File</span>
					</div>
					<div id="error_rows" style="display:none;text-align:center"></div><br/>
					<label for="session_file" >* Session Data:</label>
					<div id="type_file" style="display:none;">
						<input type="file" name="session_file"/><br/>
						<span class="hint">Click browse and select your CSV data file.</span><br/>
					</div>
					<div id="type_manual">
						<table width="480px" cellpadding="3" id="manual_table">
							<tr>
								{ foreach from=$fields item=field }
									<td>{ $field.field_name } ({$field.unit_abbreviation})</td>
								{ /foreach }
							</tr>
							<tr id="template" style="display:none;">
								{ foreach from=$fields item=field }
									<td><input type="text" id="{ $field.field_name|replace:' ':'_' }_xxx" name="{ $field.field_name|replace:' ':'_'  }_xxx"  style="width:90%;"></td>
								{ /foreach }
							</tr>
							<tr>
								{ foreach from=$fields item=field }
								    { if $field.field_name == 'Time' }
								      	 <td><input type="text" id="{ $field.field_name|replace:' ':'_' }_1" name="{ $field.field_name|replace:' ':'_' }_1" style="width:90%;" class="time"></td>
								    { else }
									<td><input type="text"  id="{ $field.field_name|replace:' ':'_' }_1" name="{ $field.field_name|replace:' ':'_' }_1" style="width:90%;" ></td>
								    { /if }
								{ /foreach }
							</tr>
						</table>
						<input type="hidden" id="row_count" name="row_count" value="1" />
						<span class="hint"><a href="javascript:addManualDataRow();">Add Row</a></span>
					</div>
				{ elseif $state == 2 }
					<legend>Field to Header Matching</legend>
					<p>Match the fields in the experiment to the headers in your CSV file</p>
					{ if $unmatched_fields and $unmatched_header }
						<table width="100%" cellspacing="0" cellpadding="5" class="matching">
							<tr>
								<td width="50%" style="font-weight:bold; background:#EEEEEE;">Field</td>
								<td width="50%" style="font-weight:bold; background:#EEEEEE;">Header</td>
							</tr>
							{ foreach name=outfield from=$unmatched_fields item=unf }
							<tr>
								<td>
									{ $unf[1]|capitalize:true }
									<input type="hidden" name="field_{ $smarty.foreach.outfield.index }" value="{$unf[0]}" />
								</td>
								<td>
									<select style="width:50%;"  name="header_{ $smarty.foreach.outfield.index }">
									{ foreach from=$unmatched_header item=uh }
										<option value="{$uh[0]}">{ $uh[1]|capitalize:true }</option>
									{ /foreach }
									</select>
								</td>
							</tr>
							{ /foreach }
						</table>
					{ /if }
				{ elseif $state == 3 }
					<legend>Experiment Start Date and Time</legend>
					<p>Your data file does not have a valid time and date. Please enter it bellow</p>
					<p>
						<div style="padding-top:10px;">
							<table width="100%">
								<tr>
									<td>1. Select the Date of your experiment</td>
									<td>2. Select the Time of your experiment</td>
								</tr>
								<tr>
									<td width="50%">
										<div id="datetime"></div>
										<input type="hidden" id="date" name="date" value="date" />
									</td>
									<td width="50%" valign="top">
										<select id="hour" name="hour">
											<option value="HH">HH</option>
											{section name=foo start=1 loop=13 step=1 }
												<option value="{$smarty.section.foo.index}">{"%02d"|sprintf:$smarty.section.foo.index}</option>
											{/section}
										</select>
										<select id="minute" name="minute">
											<option value="MM">MM</option>
											{section name=foo start=0 loop=59 step=1 }
												<option value="{$smarty.section.foo.index}">{"%02d"|sprintf:$smarty.section.foo.index}</option>
											{/section}
										</select>
										<select id="part" name="part">
											<option value="am">AM</option>
											<option value="pm">PM</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
					</p>
					<!--
					<table>
						<td><input type="text" style="margin:0px 5px 0px 0px;" name="start" id="start" /></td>
						<tr>
							<td width="50%" style="font-weight:bold; background:#EEEEEE;">Time Unit</td>
							<td width="50%" style="font-weight:bold; background:#EEEEEE;">Value</td>
						</tr>
						<tr>
							<td>Year</td>
							<td><input type="text" id="year" name="year" />
						</tr>
						<tr>
							<td>Month</td>
							<td><input type="text" id="month" name="month" />
						</tr>
						<tr>
							<td>Day</td>
							<td><input type="text" id="day" name="day" />
						</tr>
						<tr>
							<td>Hour</td>
							<td><input type="text" id="hour" name="hour" />
						</tr>
						<tr>
							<td>Minute</td>
							<td><input type="text" id="minute" name="minute" />
						</tr>
					</table>
					-->
				{ elseif $state == 4 }
					<legend>Successfully { if $sessiontype == "file" }Uploaded CSV File{ else }Added Session Data{ /if }</legend>
					<p>You've successfully { if $sessiontype == "file" }uploaded csv file{ else }added session data{ /if }. You can <a href="upload.php?id={ $meta.experiment_id }">{ if $sessiontype == "file" }upload another csv file{ else }add more session data here{ /if }</a>.</p><form><input id='viewdatabtn' type='button' value="Examine Your Data" onclick='window.location.href="vis.php?sessions={ $session }"'/></form>
				{ /if }
				
				<div id="state_wrapper" style="display:none;">
					<input type="hidden" id="state" name="state" value="{ $state }" />
					<input type="hidden" id="timefix" name="timefix" value="{ $time_fix }" />
					<input type="hidden" id="columnfix" name="columnfix" value="{ $column_fix }" />
					{ if $state > 1 }
					
						<input type="hidden" id="session_type" name="session_type" value="{ $session_type }" />
						<input type="hidden" name="session_type" value="{ $session_type }"/>
						<input type="hidden" name="session_name" value="{ $session_name }"/>
						<input type="hidden" name="session_street" value="{ $session_street }"/>
						<input type="hidden" name="session_citystate" value="{ $session_citystate }"/>
						<input type="hidden" name="session_description" value="{ $session_description }"/>
						
						{ if $unmatched_fields }
							<input type="hidden" id="unmatched_field_count" name="unmatched_field_count" value="{ $unmatched_fields|@count }">
						{ /if }
						
						{ if $target_path }
							<input type="hidden" id="target_path" name="target_path" value="{ $target_path }" />
						{ /if }
						
						{ if $debug_data }
							<input type="hidden" id="debug_data" name="debug_data" value="{ $debug_data }" />
						{ /if }
						
						{ if $year and $month and $day and $hour and $minute and $second }
							<input type="hidden" id="year" name="year" value="{ $year }" />
							<input type="hidden" id="month" name="month" value="{ $month }" />
							<input type="hidden" id="day" name="day" value="{ $day }" />
							<input type="hidden" id="hour" name="hour" value="{ $hour }" />
							<input type="hidden" id="minute" name="minute" value="{ $minute }" />
							<input type="hidden" id="second" name="second" value="{ $second }" />
						{ /if }
						
					{ /if }
				</div>
				{ if $state == 1 }<span id="requiredfields">* <span id="requiredfieldstext">Denotes a required field.</span></span> { /if }
			</fieldset>
			{ if $state != 4 }
				<fieldset>
					<legend>Review and Finish</legend>
				    <p>Your session will be created with the information entered above.</p>
					<input type="hidden" name="id" value="{ $meta.experiment_id }" />
					<button type="submit" name="session_create">
						{ if $state == 1 }Create Session{ /if }
						{ if $state == 2 or $state == 3 or $state == 4 }Complete Session{ /if }
					</button>
				</fieldset>
			{ /if }
		</form>
	</div>
	
	<div id="sidebar">
		
		<div class="module">
			<h1>First Time Uploading?</h1>
			<div>
				<p>
					Check out our quick step-by-step walkthrough on how to contribute data.
				</p>
				<p>
					<a href="actions/tutorials.php?tutorial=upload&amp;height=620&amp;width=888" class="thickbox">Watch Tutorial</a> 
				</p>
			</div>
		</div>
		
		<div class="module">
			<h1>Uploading from a PinPoint?</h1>
			<div>
				<p>
					First time using a PinPoint? Watch our quick tutorial to learn how to upload data from the PinPoint. 
				</p>
				<p>
					<a href="actions/tutorials.php?tutorial=pincushion&amp;height=570&amp;width=520" class="thickbox">Watch Tutorial</a>
				</p>
			</div>
		</div>
		
	</div>
{ /if }
