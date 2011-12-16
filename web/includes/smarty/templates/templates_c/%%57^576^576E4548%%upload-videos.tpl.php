<?php /* Smarty version 2.6.22, created on 2010-11-06 12:51:45
         compiled from upload-videos.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
<div id="main-full">
	<div>Guests do not have access to contribute to videos. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
</div>
<?php else: ?>
	<div id="main">
		<?php if ($this->_tpl_vars['done']): ?>
			<fieldset id="basic-info">
				<legend>Successfully Uploaded a Video</legend>
				<p>You've successfully uploaded a video. You can <a href="upload-video.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
">upload another</a> or <a href="experiment.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
">watch your video.</a></p>
			</fieldset>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
				<fieldset id="basic-info">
					<legend>Upload a new video</legend>
			    	<p>Your video will be uploaded with the following information.</p>
			    	<label for="video_name">Title:</label><input type="text" name="video_name" value="<?php echo $this->_tpl_vars['values']['vtitle']; ?>
" /><br/>
			    	<span class="hint">Example: "Northern River Afternoon Test"</span><br/>
			    	<label for="video_description">Description:</label><textarea name="video_description"><?php echo $this->_tpl_vars['values']['description']; ?>
</textarea><br/>
			    	<span class="hint">Describe what the video is depicting.</span><br/>
					<label for="video_street">Street:</label><input type="text" name="video_street" values="<?php echo $this->_tpl_vars['values']['street']; ?>
" /><br/>
					<span class="hint">Example: "4 Yawkey Way"</span><br/> 
					<label for="video_citystate">City, State:</label><input type="text" name="video_citystate" values="<?php echo $this->_tpl_vars['values']['citystate']; ?>
"/><br/>
					<span class="hint">Example: "Boston, Ma"</span><br/>
					<label for="video_file">Video File:</label><input type="file" name="video_file"/><br/>
					<span class="hint">Click browse and select your video file.</span><br/>
			  	</fieldset>
				<fieldset>
					<legend>Review and Finish</legend>
			    	<p>Your video will be uploaded with the information entered above.</p>
					<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
" />
					<input type="submit" name="video_create" value="Upload Video" /><br/>
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