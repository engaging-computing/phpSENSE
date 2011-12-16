<?php /* Smarty version 2.6.22, created on 2011-02-22 10:57:14
         compiled from login.tpl */ ?>
<div id="main-full">
<?php if ($this->_tpl_vars['user']['guest']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  	<div style="padding-bottom:8px;">Don't have an account? Click <a href="register.php">here</a> to create one.</div>
  	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Username or Email:</label></td>
				<td><input type="text" name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="password">Password:</label></td>
				<td><input type="password" name="password" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="checkbox" name="remember" <?php if ($this->_tpl_vars['remember']): ?>checked<?php endif; ?>/> Stay logged in for two weeks</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				    <div style="padding-top:8px;">
				        <button type="submit" name="submit">Login</button> or <a href='reset.php'>Forgot password?</a>
				    </div>
				</td>
			</tr>
  		</table>
  		<input type="hidden" name="referer" value="<?php echo $this->_tpl_vars['referer']; ?>
"/>
  	</form>
<?php else: ?>
	<p>You are already logged in as <a href="profile.php"><?php echo $this->_tpl_vars['user']['email']; ?>
</a>. Click <a href="logout.php">here</a> to logout.</p>
<?php endif; ?>
</div>