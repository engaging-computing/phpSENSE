<?php /* Smarty version 2.6.22, created on 2010-12-16 13:37:59
         compiled from admin/experiment-manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'admin/experiment-manage.tpl', 28, false),array('modifier', 'capitalize', 'admin/experiment-manage.tpl', 32, false),)), $this); ?>
<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="experimentnew" value="New Experiment" onclick="window.location.href='create.php';" /> 
			<input type="submit" id="experimentdelete" value="Delete Experiment" onclick="deleteExperiment();" /> 
			<input type="submit" id="experimentfeature" value="Feature Experiment" onclick="featureExperiment();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllFeaturedExperiments();">Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllNonFeaturedExperiments();">Non-Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllVisibleExperiments();">Visible</a>,
			<a href="javascript:void(0);" onclick="checkAllHiddenExperiments();">Hidden</a> 
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Experiment Title</td>
			<td>Creator</td>
			<td>Created On</td>
			<td>Last Modified</td>
			<td>Hidden?</td>
			<td>Featured?</td>
		</tr>
		<?php if (count($this->_tpl_vars['data']) > 0): ?>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['experiment']):
?>
				<tr>
					<td align="center"><input type="checkbox" name="news_<?php echo $this->_tpl_vars['experiment']['experiment_id']; ?>
" id="news_<?php echo $this->_tpl_vars['experiment']['experiment_id']; ?>
" value="<?php echo $this->_tpl_vars['experiment']['experiment_id']; ?>
" /></td>
					<td><a href="experiment.php?id=<?php echo $this->_tpl_vars['experiment']['experiment_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['experiment']['name'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td><a href="profile.php?id=<?php echo $this->_tpl_vars['experiment']['owner_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['experiment']['firstname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['experiment']['lastname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td><?php echo $this->_tpl_vars['experiment']['timecreated']; ?>
</td>
					<td><?php echo $this->_tpl_vars['experiment']['timemodified']; ?>
</td>
					<td class="hidden"><?php if ($this->_tpl_vars['experiment']['hidden'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
					<td class="featured"><?php if ($this->_tpl_vars['experiment']['featured'] == 1): ?>Yes<?php else: ?>No<?php endif; ?></td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any experiments.</td>
			</tr>
		<?php endif; ?>
	</table>
</div>