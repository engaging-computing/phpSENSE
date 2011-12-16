<?php /* Smarty version 2.6.22, created on 2011-08-16 16:21:38
         compiled from upload.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'upload.tpl', 41, false),array('modifier', 'capitalize', 'upload.tpl', 65, false),array('modifier', 'sprintf', 'upload.tpl', 98, false),array('modifier', 'count', 'upload.tpl', 164, false),)), $this); ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
<div id="main-full">
	<div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
<?php else: ?>
	<div id="main">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
			<fieldset id="basic-info">
				<?php if ($this->_tpl_vars['state'] == 1): ?>
					<legend>Create a new session</legend>
			    	<p>Your session will be created with the following information.</p>
					<label for="session_name">* Name:</label><input type="text" name="session_name" value="<?php echo $this->_tpl_vars['session_name']; ?>
"/><br/>
			    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="session_description">* Procedure:</label><textarea name="session_description"><?php echo $this->_tpl_vars['session_description']; ?>
</textarea><br/>
			    	<span class="hint">Describe the session procedure and other details.</span><br/>
					<label for="session_street">* Street:</label><input type="text" name="session_street" value=" "/><br/>
					<span class="hint">Example: "4 Yawkey Way"</span><br/>
					<label for="session_citystate">* City, State:</label><input type="text" name="session_citystate" value="<?php echo $this->_tpl_vars['session_citystate']; ?>
"/><br/>
					<span class="hint">Example: "Boston, Ma"</span><br/>
					<label for="session_type">Session Type:</label>
					<div style="width:480px;">
					    <input type="radio" id="manual_upload" name="session_type" group="session_type" value="manual" style="width:20px;" selected="selected" checked/><span>Manual Entry</span>
						<input type="radio" id="file_upload" name="session_type" group="session_type" value="file" style="width:20px;"/><span>Data File</span>
					</div>
					<br/>
					<label for="session_file" >* Session Data:</label>
					<div id="type_file" style="display:none;">
						<input type="file" name="session_file"/><br/>
						<span class="hint">Click browse and select your CSV data file.</span><br/>
					</div>
					<div id="type_manual">
						<table width="480px" cellpadding="3" id="manual_table">
							<tr>
								<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
									<td><?php echo $this->_tpl_vars['field']['field_name']; ?>
 (<?php echo $this->_tpl_vars['field']['unit_abbreviation']; ?>
)</td>
								<?php endforeach; endif; unset($_from); ?>
							</tr>
							<tr id="template" style="display:none;">
								<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
									<td><input type="text" id="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
_xxx" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
_xxx"  style="width:90%;"></td>
								<?php endforeach; endif; unset($_from); ?>
							</tr>
							<tr>
								<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
									<td><input type="text" id="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
_1" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['field_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
_1" style="width:90%;"></td>
								<?php endforeach; endif; unset($_from); ?>
							</tr>
						</table>
						<input type="hidden" id="row_count" name="row_count" value="1" />
						<span class="hint"><a href="javascript:addManualDataRow();">Add Row</a></span>
					</div>
				<?php elseif ($this->_tpl_vars['state'] == 2): ?>
					<legend>Field to Header Matching</legend>
					<p>Match the fields in the experiment to the headers in your CSV file</p>
					<?php if ($this->_tpl_vars['unmatched_fields'] && $this->_tpl_vars['unmatched_header']): ?>
						<table width="100%" cellspacing="0" cellpadding="5" class="matching">
							<tr>
								<td width="50%" style="font-weight:bold; background:#EEEEEE;">Field</td>
								<td width="50%" style="font-weight:bold; background:#EEEEEE;">Header</td>
							</tr>
							<?php $_from = $this->_tpl_vars['unmatched_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outfield'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outfield']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['unf']):
        $this->_foreach['outfield']['iteration']++;
?>
							<tr>
								<td>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['unf'][1])) ? $this->_run_mod_handler('capitalize', true, $_tmp, true) : smarty_modifier_capitalize($_tmp, true)); ?>

									<input type="hidden" name="field_<?php echo ($this->_foreach['outfield']['iteration']-1); ?>
" value="<?php echo $this->_tpl_vars['unf'][0]; ?>
" />
								</td>
								<td>
									<select style="width:50%;"  name="header_<?php echo ($this->_foreach['outfield']['iteration']-1); ?>
">
									<?php $_from = $this->_tpl_vars['unmatched_header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uh']):
?>
										<option value="<?php echo $this->_tpl_vars['uh'][0]; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['uh'][1])) ? $this->_run_mod_handler('capitalize', true, $_tmp, true) : smarty_modifier_capitalize($_tmp, true)); ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
								</td>
							</tr>
							<?php endforeach; endif; unset($_from); ?>
						</table>
					<?php endif; ?>
				<?php elseif ($this->_tpl_vars['state'] == 3): ?>
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
											<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)1;
$this->_sections['foo']['loop'] = is_array($_loop=13) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
												<option value="<?php echo $this->_sections['foo']['index']; ?>
"><?php echo ((is_array($_tmp="%02d")) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_sections['foo']['index']) : sprintf($_tmp, $this->_sections['foo']['index'])); ?>
</option>
											<?php endfor; endif; ?>
										</select>
										<select id="minute" name="minute">
											<option value="MM">MM</option>
											<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)0;
$this->_sections['foo']['loop'] = is_array($_loop=59) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
												<option value="<?php echo $this->_sections['foo']['index']; ?>
"><?php echo ((is_array($_tmp="%02d")) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_sections['foo']['index']) : sprintf($_tmp, $this->_sections['foo']['index'])); ?>
</option>
											<?php endfor; endif; ?>
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
				<?php elseif ($this->_tpl_vars['state'] == 4): ?>
					<legend>Successfully <?php if ($this->_tpl_vars['sessiontype'] == 'file'): ?>Uploaded CSV File<?php else: ?>Added Session Data<?php endif; ?></legend>
					<p>You've successfully <?php if ($this->_tpl_vars['sessiontype'] == 'file'): ?>uploaded csv file<?php else: ?>added session data<?php endif; ?>. You can <a href="upload.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
"><?php if ($this->_tpl_vars['sessiontype'] == 'file'): ?>upload another csv file<?php else: ?>add more session data here<?php endif; ?></a>.</p><form><input id='viewdatabtn' type='button' value="Examine Your Data" onclick='window.location.href="vis.php?sessions=<?php echo $this->_tpl_vars['session']; ?>
"'/></form>
				<?php endif; ?>
				
				<div id="state_wrapper" style="display:none;">
					<input type="hidden" id="state" name="state" value="<?php echo $this->_tpl_vars['state']; ?>
" />
					<input type="hidden" id="timefix" name="timefix" value="<?php echo $this->_tpl_vars['time_fix']; ?>
" />
					<input type="hidden" id="columnfix" name="columnfix" value="<?php echo $this->_tpl_vars['column_fix']; ?>
" />
					<?php if ($this->_tpl_vars['state'] > 1): ?>
					
						<input type="hidden" id="session_type" name="session_type" value="<?php echo $this->_tpl_vars['session_type']; ?>
" />
						<input type="hidden" name="session_type" value="<?php echo $this->_tpl_vars['session_type']; ?>
"/>
						<input type="hidden" name="session_name" value="<?php echo $this->_tpl_vars['session_name']; ?>
"/>
						<input type="hidden" name="session_street" value="<?php echo $this->_tpl_vars['session_street']; ?>
"/>
						<input type="hidden" name="session_citystate" value="<?php echo $this->_tpl_vars['session_citystate']; ?>
"/>
						<input type="hidden" name="session_description" value="<?php echo $this->_tpl_vars['session_description']; ?>
"/>
						
						<?php if ($this->_tpl_vars['unmatched_fields']): ?>
							<input type="hidden" id="unmatched_field_count" name="unmatched_field_count" value="<?php echo count($this->_tpl_vars['unmatched_fields']); ?>
">
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['target_path']): ?>
							<input type="hidden" id="target_path" name="target_path" value="<?php echo $this->_tpl_vars['target_path']; ?>
" />
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['debug_data']): ?>
							<input type="hidden" id="debug_data" name="debug_data" value="<?php echo $this->_tpl_vars['debug_data']; ?>
" />
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['year'] && $this->_tpl_vars['month'] && $this->_tpl_vars['day'] && $this->_tpl_vars['hour'] && $this->_tpl_vars['minute'] && $this->_tpl_vars['second']): ?>
							<input type="hidden" id="year" name="year" value="<?php echo $this->_tpl_vars['year']; ?>
" />
							<input type="hidden" id="month" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
" />
							<input type="hidden" id="day" name="day" value="<?php echo $this->_tpl_vars['day']; ?>
" />
							<input type="hidden" id="hour" name="hour" value="<?php echo $this->_tpl_vars['hour']; ?>
" />
							<input type="hidden" id="minute" name="minute" value="<?php echo $this->_tpl_vars['minute']; ?>
" />
							<input type="hidden" id="second" name="second" value="<?php echo $this->_tpl_vars['second']; ?>
" />
						<?php endif; ?>
						
					<?php endif; ?>
				</div>
				<?php if ($this->_tpl_vars['state'] == 1): ?><span id="requiredfields">* <span id="requiredfieldstext">Denotes a required field.</span></span> <?php endif; ?>
			</fieldset>
			<?php if ($this->_tpl_vars['state'] != 4): ?>
				<fieldset>
					<legend>Review and Finish</legend>
				    <p>Your session will be created with the information entered above.</p>
					<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
" />
					<button type="button" name="session_create" onclick="readyUploadForm();">
						<?php if ($this->_tpl_vars['state'] == 1): ?>Create Session<?php endif; ?>
						<?php if ($this->_tpl_vars['state'] == 2 || $this->_tpl_vars['state'] == 3 || $this->_tpl_vars['state'] == 4): ?>Complete Session<?php endif; ?>
					</button>
				</fieldset>
			<?php endif; ?>
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
<?php endif; ?>