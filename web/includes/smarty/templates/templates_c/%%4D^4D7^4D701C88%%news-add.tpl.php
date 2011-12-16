<?php /* Smarty version 2.6.22, created on 2010-10-26 09:55:21
         compiled from admin/news-add.tpl */ ?>
<div id="main-full">
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			<?php if ($this->_tpl_vars['done']): ?>
				<p>You have successfully created a new article. <a href="admin.php?action=newsadd">Click here</a> to create another.</p>
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
							<td><input type="text" name="title" id="title" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Description:</td>
							<td><textarea cols="60" name="content" id="content" rows="10"></textarea></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="create" id="create" value="Create"/></td>
						</tr>
					</table>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>