<?php /* Smarty version 2.6.22, created on 2010-12-07 18:33:08
         compiled from parts/links-new.tpl */ ?>
<ul>
    <?php if (! $this->_tpl_vars['user']['guest']): ?> <li><a href="profile.php?id=<?php echo $this->_tpl_vars['user']['user_id']; ?>
" <?php if ($this->_tpl_vars['marker'] == 'user'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/tags.png" alt="View the map"/>My Stuff</a></li> <?php endif; ?>
<li><a href="browse.php?type=experiments" <?php if ($this->_tpl_vars['marker'] == 'experiments'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/drawer.png" alt="Browse experiments and their sessions"/>Experiments</a></li>
<li><a href="browse.php?type=people" <?php if ($this->_tpl_vars['marker'] == 'people'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/group.png" alt="Browse experiments and their sessions"/>People</a></li>
<li><a href="browse.php?type=visualizations" <?php if ($this->_tpl_vars['marker'] == 'visualizations'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/chart_bar.png" alt="Browse experiments and their sessions"/>Visualizations</a></li>
<li><a href="browse.php?type=activities" <?php if ($this->_tpl_vars['marker'] == 'activities'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/chart_line.png" alt="Browse activities"/>Activities</a></li>
<?php if (! $this->_tpl_vars['user']['guest']): ?> <!--<li><a href="profile.php?id=<?php echo $this->_tpl_vars['user']['user_id']; ?>
" <?php if ($this->_tpl_vars['marker'] == 'user'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/tags.png" alt="View the map"/>My Stuff</a></li>--> <?php endif; ?>
<?php if (! $this->_tpl_vars['user']['guest']): ?> <li><a href="create.php" <?php if ($this->_tpl_vars['marker'] == 'create'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Create Experiment</a></li> <?php endif; ?>
<?php if ($this->_tpl_vars['user']['administrator'] == 1): ?> <li><a href="admin.php" <?php if ($this->_tpl_vars['marker'] == 'admin'): ?> class="youarehere" <?php endif; ?> ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Admin</a></li> <?php endif; ?>
</ul>