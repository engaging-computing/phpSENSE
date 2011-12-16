<?php /* Smarty version 2.6.22, created on 2010-10-26 17:27:18
         compiled from news.tpl */ ?>
<div id="main-full">
	<?php if ($this->_tpl_vars['error']): ?>
		<div>Sorry, we could not find the article you are looking for.</div>
	<?php else: ?>
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="/profile.php?id=<?php echo $this->_tpl_vars['data']['author_id']; ?>
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
	<?php endif; ?>
</div>