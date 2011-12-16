<?php /* Smarty version 2.6.22, created on 2010-10-22 21:46:18
         compiled from admin/event-add.tpl */ ?>
<div id="main-full">
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			<?php if ($this->_tpl_vars['done']): ?>
				<p>You have successfully created a new event. <a href="admin.php?action=eventadd">Click here</a> to create another.</p>
			<?php else: ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<form method="post">
					<table width="100%" id="management_table" class="profile">
						<tr>
							<td class="heading" valign="top">Title:</td>
							<td><input type="text" name="title" id="title" /</td>
						</tr>
						<tr>
							<td class="heading" valign="top">Location:</td>
							<td><input type="text" name="location" id="location" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Starts:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="start" id="start" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Ends:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="end" id="end" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Description:</td>
							<td><textarea cols="60" name="description" id="description" rows="10"></textarea></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" id="create" name="create" value="Create"></td>
						</tr>
					</table>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>