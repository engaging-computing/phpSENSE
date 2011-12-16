<?php /* Smarty version 2.6.22, created on 2011-04-13 11:05:18
         compiled from session-edit.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
<div id="main-full">
	<div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
<?php elseif ($this->_tpl_vars['user']['user_id'] != $this->_tpl_vars['values']['owner_id'] && $this->_tpl_vars['user']['administrator'] != 1): ?>
    <div id="main-full">
		<div>Sorry, you are not the owner of this session so you are not allowed to edit it.</div>
	</div>
<?php else: ?>
	<div id="main">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php if (! $this->_tpl_vars['created']): ?>
		    <form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
    			<fieldset id="basic-info">
    			    <legend>Step 1: Make Your Changes</legend>
    		    	<p>Your session will be created with the following information.</p>
    				<label for="session_name">Name:</label><input type="text" name="session_name" value="<?php echo $this->_tpl_vars['values']['name']; ?>
"/><br/>
    		    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
    		    	<label for="session_description">Procedure:</label><textarea name="session_description"><?php echo $this->_tpl_vars['values']['description']; ?>
</textarea><br/>
    		    	<span class="hint">Describe the session procedure and other details.</span><br/>
    				<label for="session_street">Street:</label><input type="text" name="session_street" value="<?php echo $this->_tpl_vars['values']['street']; ?>
"/><br/>
    				<span class="hint">Example: "4 Yawkey Way"</span><br/>
    				<label for="session_citystate">City, State:</label><input type="text" name="session_citystate" value="<?php echo $this->_tpl_vars['values']['city']; ?>
"/><br/>
    				<span class="hint">Example: "Boston, Ma"</span><br/>
    				<?php if ($this->_tpl_vars['user']['administrator'] == 1): ?> 
    				    <label for="session_hidden">Hidden:</label><input type="checkbox" name="session_hidden" <?php if ($this->_tpl_vars['values']['finalized'] == 0): ?> checked="checked" <?php endif; ?> ><br/>
    				<?php endif; ?>
    			</fieldset>
    			<fieldset>
			    	<legend>Step 2: Review and Finish</legend>
			    	<p style="padding:6px 0px;">When you are finished reviewing changes, click the Save Session button to continue.</p>
			    	<input type="hidden" id="id" name="id" value="<?php echo $this->_tpl_vars['values']['session_id']; ?>
" />
					<button id="session_create" name="session_create" type="submit">Save Session</button>
				</fieldset>
    			
    		</form>
		<?php else: ?>
		    <fieldset id="basic-info">
				<legend>You've successfully edited your session!</legend>
				<p>Capital job, you've successfully edited your session! Click <a href="vis.php?sessions=<?php echo $this->_tpl_vars['sid']; ?>
">here</a> to view it.
			</fieldset>
		<?php endif; ?>
	</div>
<?php endif; ?>
