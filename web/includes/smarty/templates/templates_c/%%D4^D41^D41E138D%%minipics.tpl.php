<?php /* Smarty version 2.6.22, created on 2010-10-22 20:11:42
         compiled from parts/minipics.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'parts/minipics.tpl', 4, false),)), $this); ?>
<div class="module">
	<?php if (! $this->_tpl_vars['user']['guest']): ?><div style="float:right; font-size:80%;"><a href="upload-pictures.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
">Add Pictures</a></div><?php endif; ?>
	<h1>Pictures</h1>
	<?php if (count($this->_tpl_vars['pictures']) > 0): ?>
		<div id="pictures" style="overflow-x:scroll;"> 
			<table>
				<tr>
					<?php $_from = $this->_tpl_vars['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['picture']):
?>
						<td>
							<a href="<?php echo $this->_tpl_vars['picture']['set_url']; ?>
"><img src="picture.php?url=<?php echo $this->_tpl_vars['picture']['source']; ?>
&amp;h=160&w=230" /></a>
						</td>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
			</table>
		</div>
	<?php else: ?>
		<div id="pictures"> 
			<table width="100%">
				<tr>
					<td>No pictures found.</td>
				</tr>
			</table>
		</div>
	<?php endif; ?>
</div>