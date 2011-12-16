<?php /* Smarty version 2.6.22, created on 2011-12-05 13:57:24
         compiled from create-activity.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
	<div id="main-full">
		<div>Guests do not have permission to create activities. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
	</div>
<?php else: ?>
	<div id="main">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php if (! $this->_tpl_vars['done']): ?>
		    <form method="post">
    		    <fieldset id="basic-info">
    				<legend>Activity Setup</legend>
    	    		<p>Please describe the basics of your activity, and what you would like students to do for the prompt.</p>
    	    		<label for="experiment_name">Name:</label><input type="text" name="name" value="<?php echo $this->_tpl_vars['values']['name']; ?>
" /><br/>
    	    		<span class="hint">Example: "Cooling Curves Of Cups"</span><br/>
    	    		<label for="experiment_description">Instructions:</label><textarea name="description"><?php echo $this->_tpl_vars['values']['description']; ?>
</textarea><br/>
    	    		<span class="hint">Determine which material cools the fastest.</span><br/>
    	    	</fieldset>
    	    	<fieldset>
    		    	<legend>Review and Finish</legend>
    		    	<p style="padding:6px 0px;">When you are finished reviewing your activity, click the Create Activity button to continue.</p>
    				<button id="activity_create" name="activity_create" type="submit">Create Activity</button>
    			</fieldset>
    			<input type="hidden" id="sessions" name="sessions" value="<?php echo $this->_tpl_vars['values']['sessions']; ?>
" />
    			<input type="hidden" id="eid" name="eid" value="<?php echo $this->_tpl_vars['values']['eid']; ?>
" />
    			<input type="hidden" id="uid" name="uid" value="<?php echo $this->_tpl_vars['values']['uid']; ?>
" />
    		</form>
    	<?php else: ?>
    	    <div>You've successfully created an activity! You can access your activity using this link: <a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>
/activity.php?id=<?php echo $this->_tpl_vars['aid']; ?>
">http://<?php echo $_SERVER['SERVER_NAME']; ?>
/activity.php?id=<?php echo $this->_tpl_vars['aid']; ?>
</a></div>
		<?php endif; ?>
	</div>
	<div id="sidebar">
	</div>
<?php endif; ?>