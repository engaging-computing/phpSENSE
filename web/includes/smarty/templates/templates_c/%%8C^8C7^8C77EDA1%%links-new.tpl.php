<?php /* Smarty version 2.6.22, created on 2011-12-26 16:54:06
         compiled from parts/links-new.tpl */ ?>
<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 -->
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