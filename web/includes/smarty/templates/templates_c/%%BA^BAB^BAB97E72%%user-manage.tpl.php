<?php /* Smarty version 2.6.22, created on 2011-09-22 11:49:46
         compiled from admin/user-manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'admin/user-manage.tpl', 27, false),array('modifier', 'capitalize', 'admin/user-manage.tpl', 31, false),)), $this); ?>
<div id="main-full">
	<div id="management_toolbar">
		<div>
			<!-- <input type="submit" id="usernew" value="New User" /> -->
			<input type="submit" id="userdelete" value="Delete User" onclick="deleteUser();" />
			<input type="submit" id="userreset" value="Reset Password" onclick="resetPass();" /> 
			<input type="submit" id="useradmin" value="Make Administrator" onclick="adminUser();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllAdminUsers();">Admins</a>, 
			<a href="javascript:void(0);" onclick="checkAllRegularUsers();">Regular Users</a>
		</div>
	</div>
	<table width="100%" id="management_table" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Email Address</td>
			<td>Joined On</td>
			<td>Administrator?</td>
			<td>Profile</td>
		</tr>
		<?php if (count($this->_tpl_vars['data']) > 0): ?>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['datum']):
?>
				<tr>
					<td align="center"><input type="checkbox" name="news_<?php echo $this->_tpl_vars['datum']['user_id']; ?>
" id="news_<?php echo $this->_tpl_vars['datum']['user_id']; ?>
" value="<?php echo $this->_tpl_vars['datum']['user_id']; ?>
" /></td>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['datum']['firstname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</td>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['datum']['lastname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</td>
					<td><a href="mailto:<?php echo $this->_tpl_vars['datum']['email']; ?>
"><?php echo $this->_tpl_vars['datum']['email']; ?>
</a></td>
					<td><?php echo $this->_tpl_vars['datum']['firstaccess']; ?>
</td>
					<td class="administrator"><?php if ($this->_tpl_vars['datum']['administrator'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
					<td><a href="profile.php?id=<?php echo $this->_tpl_vars['datum']['user_id']; ?>
">View</a></td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any users.</td>
			</tr>
		<?php endif; ?>
	</table>
</div>