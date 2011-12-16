<?php /* Smarty version 2.6.22, created on 2010-10-23 07:09:45
         compiled from help.tpl */ ?>
<div id="main-full">
	<?php if ($this->_tpl_vars['single']): ?>
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="profile.php?id=<?php echo $this->_tpl_vars['data']['author_id']; ?>
"><?php echo $this->_tpl_vars['data']['firstname']; ?>
 <?php echo $this->_tpl_vars['data']['lastname']; ?>
</a></td>
					</tr>
					<tr>
						<td class="heading" valign="top">Published On:</td>
						<td><?php echo $this->_tpl_vars['data']['pubDate']; ?>
</td>
					</tr>
					<tr>
						<td colspan="2"><?php echo $this->_tpl_vars['data']['content']; ?>
</td>
					</tr>
				</table>
			</div>
		</div>
	<?php else: ?>
		<p>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['headline']):
?>
				<a href="#<?php echo $this->_tpl_vars['headline']['article_id']; ?>
"><?php echo $this->_tpl_vars['headline']['title']; ?>
</a><br/>
			<?php endforeach; endif; unset($_from); ?>
		</p>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['article']):
?>
			<p id="<?php echo $this->_tpl_vars['article']['article_id']; ?>
" style="padding:0px 0px 16px 0px;">
				<h3><?php echo $this->_tpl_vars['article']['title']; ?>
</h3>
				<div>
					<?php echo $this->_tpl_vars['article']['content']; ?>

				</div>
			</p>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</div>