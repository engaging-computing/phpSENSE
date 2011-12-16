<?php /* Smarty version 2.6.22, created on 2010-10-22 20:11:42
         compiled from parts/minivids.tpl */ ?>
<div class="module">
	<?php if (! $this->_tpl_vars['user']['guest']): ?><div style="float:right; font-size:80%;"><a href="upload-videos.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
">Add Videos</a></div><?php endif; ?>
	<h1>Videos</h1>
	<?php if ($this->_tpl_vars['videos']): ?>
		<div id="videos" style="overflow-x:scroll;"> 
			<table>
				<tr>
					<?php $_from = $this->_tpl_vars['videos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['video']):
?>
						<td>
							<a href="http://www.youtube.com/watch?v=<?php echo $this->_tpl_vars['video']['provider_id']; ?>
"><img src="http://i4.ytimg.com/vi/<?php echo $this->_tpl_vars['video']['provider_id']; ?>
/default.jpg" /></a>
						</td>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
			</table>
		</div>
	<?php else: ?>
		<div id="videos"> 
			<table width="100%">
				<tr>
					<td>No videos found.</td>
				</tr>
			</table>
		</div>
	<?php endif; ?>
</div>