<?php /* Smarty version 2.6.22, created on 2010-12-08 14:13:21
         compiled from admin/faq-manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'admin/faq-manage.tpl', 24, false),)), $this); ?>
<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="faqnew" value="New FAQ" onclick="window.location.href='admin.php?action=faqadd';" /> 
			<input type="submit" id="faqdelete" value="Delete FAQ" onclick="deleteFaq();" /> 
			<input type="submit" id="faqpublish" value="Publish FAQ" onclick="publishFaq();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>
			<a href="javascript:void(0);" onclick="checkAllPublishedArticles();">Published</a>, 
			<a href="javascript:void(0);" onclick="checkAllUnpublishedArticles();">Unpublished</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Issue</td>
			<td>Creator</td>
			<td>Created On</td>
			<td>Published</td>
		</tr>
		<?php if (count($this->_tpl_vars['data']) > 0): ?>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['help']):
?>
				<tr>
					<td align="center"><input type="checkbox" name="faq_<?php echo $this->_tpl_vars['help']['article_id']; ?>
" id="faq_<?php echo $this->_tpl_vars['help']['article_id']; ?>
" value="<?php echo $this->_tpl_vars['help']['article_id']; ?>
" /></td>
					<td><a href="faq.php?id=<?php echo $this->_tpl_vars['help']['article_id']; ?>
"><?php echo $this->_tpl_vars['help']['title']; ?>
</a></td>
					<td><a href="help.php?id=<?php echo $this->_tpl_vars['help']['author_id']; ?>
"><?php echo $this->_tpl_vars['help']['firstname']; ?>
 <?php echo $this->_tpl_vars['help']['lastname']; ?>
</a></td>
					<td><?php echo $this->_tpl_vars['help']['pubDate']; ?>
</td>
					<td class="published"><?php if ($this->_tpl_vars['help']['published'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any FAQs.</td>
			</tr>
		<?php endif; ?>
	</table>
</div>