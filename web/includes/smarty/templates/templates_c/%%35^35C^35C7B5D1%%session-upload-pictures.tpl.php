<?php /* Smarty version 2.6.22, created on 2011-04-12 15:50:11
         compiled from session-upload-pictures.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
<div id="main-full">
	<div>Guests do not have access to contribute to pictures. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
<?php else: ?>
	<div id="main">
		<?php if ($this->_tpl_vars['done']): ?>
			<fieldset id="basic-info">
				<legend>Successfully Uploaded Pictures</legend>
				<p>You've successfully uploaded pictures. You can <a href="session-upload-pictures.php?id=<?php echo $this->_tpl_vars['id']; ?>
&sid=<?php echo $this->_tpl_vars['sid']; ?>
">upload more</a> or <a href="experiment.php?id=<?php echo $this->_tpl_vars['id']; ?>
">view your pictures</a>.</p>
			</fieldset>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
				<fieldset id="basic-info">
					<legend>Upload a new picture</legend>
			    	<p>Your video will be uploaded with the following information.</p>
			    	<label for="picture_name">Title:</label><input type="text" name="picture_name" value="<?php echo $this->_tpl_vars['values']['vtitle']; ?>
" /><br/>
			    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="picture_description">Description:</label><textarea name="picture_description"><?php echo $this->_tpl_vars['values']['description']; ?>
</textarea><br/>
			    	<span class="hint">Describe what your pictures are illustrating.</span><br/>
					<table id="picture_table" width="480px" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top" width="90px"><label for="picture_file_1">Picture Files:</label></td>
							<td><input type="file" name="picture_file_1"/></td>
						</tr>
						<tr id="template" style="display:none;">
							<td>&nbsp;</td>
							<td><input type="file" name="picture_file_xxx"/></td>
						</tr>
					</table>
					<span class="hint">Click browse and select your image file.</span><br/>
			  	</fieldset>
				<input type="hidden" id="row_count" name="row_count" value="1" />
				<fieldset>
					<legend>Review and Finish</legend>
			    	<p>Your pictures will be uploaded with the information entered above.</p>
					<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
" />
					<input type="submit" id="picture_create" name="picture_create" value="Upload Picture" onclick="this.value='Please wait...';" /><br/>
			  	</fieldset>
			</form>
	  	<?php endif; ?>
	</div>

	<div id="sidebar">
		<div class="module">
	   		<h1>Also working on this project:</h1>
			<p>Here are a list of people working with <?php echo $this->_tpl_vars['first']; ?>
 on this experiment.</p>
	    	<ul>
				<?php $_from = $this->_tpl_vars['collabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['collab']):
?>
	      			<li><a href="profile.php?id=<?php echo $this->_tpl_vars['collab']['user_id']; ?>
"><?php echo $this->_tpl_vars['collab']['firstname']; ?>
 <?php echo $this->_tpl_vars['collab']['lastname']; ?>
</a></li>
				<?php endforeach; endif; unset($_from); ?>
	    	</ul>
	  	</div>
	</div>
<?php endif; ?>