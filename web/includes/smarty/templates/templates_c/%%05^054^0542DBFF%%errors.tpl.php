<?php /* Smarty version 2.6.22, created on 2010-10-22 20:09:18
         compiled from parts/errors.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'parts/errors.tpl', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['errors']) > 0): ?>
<div id="errors">
    <span>Please correct the following errors:</span>
    <ul style="margin:0px 0px 0px 15px;">
    <?php $_from = $this->_tpl_vars['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['error']):
?>
		<li style="color: #ff0000"><?php echo $this->_tpl_vars['error']; ?>
</li>
    <?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<?php endif; ?>