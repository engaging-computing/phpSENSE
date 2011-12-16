<?php /* Smarty version 2.6.22, created on 2010-10-22 21:17:44
         compiled from experiment-edit.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
	<div id="main-full">
		<div>Guests do not have profile. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
<?php elseif ($this->_tpl_vars['user']['user_id'] != $this->_tpl_vars['values']['owner_id'] && $this->_tpl_vars['user']['administrator'] != 1): ?>
    <div id="main-full">
		<div>Sorry, you are not the owner of this experiment so you are not allowed to edit it.</div>
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
		    		<label for="experiment_name">Name:</label></input><input type="text" name="experiment_name" value="<?php echo $this->_tpl_vars['values']['name']; ?>
" /><br/>
		    		<span class="hint">Example: "Salinity Levels in Rivers"</span><br/>
		    		<label for="experiment_description">Procedure:</label><textarea name="experiment_description"><?php echo $this->_tpl_vars['values']['description']; ?>
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
				</fieldset>

				<fieldset>
			    	<legend>Step 2: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing changes, click the Save Experiment button to continue.</p>
			    	<input type="hidden" id="id" name="id" value="<?php echo $this->_tpl_vars['values']['experiment_id']; ?>
" />
					<button id="experiment_create" name="experiment_create" type="submit">Save Experiment</button>
				</fieldset>
			<?php else: ?>
			    <fieldset id="basic-info">
					<legend>You've successfully edited your experiment!</legend>
					<p>Nice job, you've successfully edited your experiment! Click <a href="experiment.php?id=<?php echo $this->_tpl_vars['eid']; ?>
">here</a> to view it.
				</fieldset>
		    <?php endif; ?>
		</form>
	</div>
<?php endif; ?>