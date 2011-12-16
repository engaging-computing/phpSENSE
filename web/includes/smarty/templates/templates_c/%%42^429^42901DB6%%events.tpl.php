<?php /* Smarty version 2.6.22, created on 2010-10-22 21:44:55
         compiled from events.tpl */ ?>
<div id="main-full">
	<?php if ($this->_tpl_vars['error']): ?>
		<div>Sorry, we could not find the event you are looking for.</div>
	<?php else: ?>
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile" cellpadding="0" cellspacing="0">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="/profile.php?id=<?php echo $this->_tpl_vars['data']['author_id']; ?>
"><?php echo $this->_tpl_vars['data']['firstname']; ?>
 <?php echo $this->_tpl_vars['data']['lastname']; ?>
</a></td>
					</tr>
					<tr>
						<td class="heading" valign="top">Location:</td>
						<td><?php echo $this->_tpl_vars['data']['location']; ?>
</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Starts:</td>
						<td><?php echo $this->_tpl_vars['data']['start']; ?>
</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Ends:</td>
						<td><?php echo $this->_tpl_vars['data']['end']; ?>
</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Description:</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><?php echo $this->_tpl_vars['data']['description']; ?>
</td>
					</tr>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>