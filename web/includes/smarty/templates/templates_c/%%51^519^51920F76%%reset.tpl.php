<?php /* Smarty version 2.6.22, created on 2011-03-22 11:51:18
         compiled from reset.tpl */ ?>
<div id="main-full">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['success'] == 1): ?>
	<div>You have been sent a link to reset your password in your email.</div>
<?php elseif ($this->_tpl_vars['success'] == -1): ?>
	<div>You have entered an invalid E-mail address. Please try again.</div>

<?php elseif ($this->_tpl_vars['auth']): ?>
	<div>Enter your new password.</a></div>
	<form method="post">
		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">New password:</label></td>
				<td><input type="password" name="pass1" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Confirm password:</label></td>
				<td><input type="password" name="pass2" style="width: 200px;"/></td>
			</tr>
			<tr>
				<td><input type="hidden" name="referer" value="<?php echo $this->_tpl_vars['referer']; ?>
"/><button type="submit" name="submit">Reset</button></td>
			</tr>
  		</table>
  	</form>
<?php elseif ($this->_tpl_vars['done']): ?>  
	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 100%; padding-right: 10px;"><label for="email">Your password has been reset!</label></td>
			</tr>
  		</table>
  	</form>

<?php else: ?>
	<div>Need to reset your password?</a></div>	
	<form method="post">
  		<table>
			<tr>
				<td style="text-align: right; width: 130px; padding-right: 10px;"><label for="email">Username or Email:</label></td>
				<td><input type="text" name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" style="width: 200px;"/></td>
				<td><input type="hidden" name="referer" value="<?php echo $this->_tpl_vars['referer']; ?>
"/><button type="submit" name="submit">Reset</button></td>
			</tr>
  		</table>
  	</form>
	<?php endif; ?>

</div>