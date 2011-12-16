<?php /* Smarty version 2.6.22, created on 2010-12-08 14:15:10
         compiled from admin/news-manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'admin/news-manage.tpl', 24, false),array('modifier', 'capitalize', 'admin/news-manage.tpl', 28, false),)), $this); ?>
<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="newsnew" value="New Article" onclick="window.location.href='admin.php?action=newsadd';" /> 
			<input type="submit" id="newsdelete" value="Delete Article" onclick="deleteNews();" /> 
			<input type="submit" id="newspublish" value="Publish Article" onclick="publishNews();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllPublishedArticles();">Published</a>, 
			<a href="javascript:void(0);" onclick="checkAllUnpublishedArticles();">Unpublished</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Title</td>
			<td>Creator</td>
			<td>Published Date</td>
			<td>Published?</td>
		</tr>
		<?php if (count($this->_tpl_vars['data']) > 0): ?>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['news']):
?>
				<tr>
					<td align="center"><input type="checkbox" name="news_<?php echo $this->_tpl_vars['news']['article_id']; ?>
" id="news_<?php echo $this->_tpl_vars['news']['article_id']; ?>
" value="<?php echo $this->_tpl_vars['news']['article_id']; ?>
" /></td>
					<td><a href="news.php?id=<?php echo $this->_tpl_vars['news']['article_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['news']['title'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td><a href="profile.php?id=<?php echo $this->_tpl_vars['news']['author_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['news']['firstname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['news']['lastname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td><?php echo $this->_tpl_vars['news']['pubDate']; ?>
</td>
					<td class="published"><?php if ($this->_tpl_vars['news']['published'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any articles.</td>
			</tr>
		<?php endif; ?>
	</table>
</div>