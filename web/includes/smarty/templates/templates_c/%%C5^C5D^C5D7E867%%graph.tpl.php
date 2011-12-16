<?php /* Smarty version 2.6.22, created on 2010-10-23 16:19:14
         compiled from parts/graph.tpl */ ?>
<?php if ($this->_tpl_vars['is_following'] == 'Yes' || $this->_tpl_vars['is_following'] == 'No'): ?>
<div class="module">
    <?php echo '<script>var follower = '; ?>
<?php echo $this->_tpl_vars['user']['user_id']; ?>
<?php echo ';</script>'; ?>

    <?php echo '<script>var followee = '; ?>
<?php echo $this->_tpl_vars['userdata']['user_id']; ?>
<?php echo ';</script>'; ?>

	<div id="graph_status_wrapper">
	    <?php if ($this->_tpl_vars['is_following'] == 'Yes'): ?>
    	    <button id="graph_status" class="stop_following" onclick="stop_following(<?php echo $this->_tpl_vars['user']['user_id']; ?>
, <?php echo $this->_tpl_vars['userdata']['user_id']; ?>
)">Unfollow</button>
    	<?php else: ?>
    	    <button id="graph_status" class="start_following" onclick="start_following(<?php echo $this->_tpl_vars['user']['user_id']; ?>
, <?php echo $this->_tpl_vars['userdata']['user_id']; ?>
)">Follow</button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="module">
    <h1>Followers</h1>
    <div>
    <?php if (! empty ( $this->_tpl_vars['followers'] )): ?>
		<?php $_from = $this->_tpl_vars['followers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['follower']):
?>
		    <a href="profile.php?id=<?php echo $this->_tpl_vars['follower']['user_id']; ?>
"><?php echo $this->_tpl_vars['follower']['firstname']; ?>
 <?php echo $this->_tpl_vars['follower']['lastname']; ?>
</a><br/>
		<?php endforeach; endif; unset($_from); ?>
	<?php else: ?>
	    <?php echo $this->_tpl_vars['userdata']['firstname']; ?>
 doesn't have any followers.
	<?php endif; ?>
    </div>
</div>

<div class="module">
    <h1>Following</h1>
    <?php if (! empty ( $this->_tpl_vars['following'] )): ?>
		<?php $_from = $this->_tpl_vars['following']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['followee']):
?>
		    <a href="profile.php?id=<?php echo $this->_tpl_vars['followee']['user_id']; ?>
"><?php echo $this->_tpl_vars['followee']['firstname']; ?>
 <?php echo $this->_tpl_vars['followee']['lastname']; ?>
</a><br/>
		<?php endforeach; endif; unset($_from); ?>
	<?php else: ?>
    	<?php echo $this->_tpl_vars['userdata']['firstname']; ?>
 isn't following anyone.
	<?php endif; ?>
</div>