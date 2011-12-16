<?php /* Smarty version 2.6.22, created on 2010-12-08 14:13:02
         compiled from admin/event-manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'admin/event-manage.tpl', 23, false),array('modifier', 'capitalize', 'admin/event-manage.tpl', 27, false),)), $this); ?>
<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="eventnew" value="New Event" onclick="window.location.href='admin.php?action=eventadd';" /> 
			<input type="submit" id="eventdelete" value="Delete Event" onclick="deleteEvent()" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllUpcomingEvents();">Upcoming</a>, 
			<a href="javascript:void(0);" onclick="checkAllPastEvents();">Past</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Title</td>
			<td>Creator</td>
			<td>Start Date</td>
			<td>End Date</td>
		</tr>
		<?php if (count($this->_tpl_vars['data']) > 0): ?>
			<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['event']):
?>
				<tr>
					<td align="center"><input type="checkbox" name="event_<?php echo $this->_tpl_vars['event']['event_id']; ?>
" id="event_<?php echo $this->_tpl_vars['event']['event_id']; ?>
" value="<?php echo $this->_tpl_vars['event']['event_id']; ?>
" /></td>
					<td><a href="events.php?id=<?php echo $this->_tpl_vars['event']['event_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['event']['title'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td><a href="profile.php?id=<?php echo $this->_tpl_vars['event']['author_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['event']['firstname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['event']['lastname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a></td>
					<td class="start"><?php echo $this->_tpl_vars['event']['start']; ?>
</td>
					<td class="end"><?php echo $this->_tpl_vars['event']['end']; ?>
</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any events.</td>
			</tr>
		<?php endif; ?>
	</table>
</div>