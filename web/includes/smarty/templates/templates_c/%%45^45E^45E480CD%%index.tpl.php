<?php /* Smarty version 2.6.22, created on 2011-12-26 16:54:06
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'index.tpl', 37, false),array('modifier', 'truncate', 'index.tpl', 37, false),)), $this); ?>
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
<div id="featuredsix">
	<?php if ($this->_tpl_vars['six']): ?>
		<?php $_from = $this->_tpl_vars['six']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['exp']):
?>
			<div class="featureditemwrapper">
				<div class="featureditem">
					<div class="featuredimage">
						<a href="experiment.php?id=<?php echo $this->_tpl_vars['exp']['experiment_id']; ?>
"><img height="200" width="287" src="picture.php?url=<?php echo $this->_tpl_vars['exp']['provider_url']; ?>
&h=200&w=287"></a>
					</div>
					<div class="featuredtext">
						<a href="experiment.php?id=<?php echo $this->_tpl_vars['exp']['experiment_id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['exp']['name'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, "", true) : smarty_modifier_truncate($_tmp, 32, "", true)); ?>
</a>
					</div>
					<div class="featuredsubtext">
						Created by <a href="profile.php?id=<?php echo $this->_tpl_vars['exp']['owner_id']; ?>
"><?php echo $this->_tpl_vars['exp']['firstname']; ?>
 <?php echo $this->_tpl_vars['exp']['lastname']; ?>
</a>
					</div>
				</div>
			</div>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
</div>
<div id="subsix">
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>News and Events</h1>
			<div>
				<?php if ($this->_tpl_vars['events']): ?> 
					<?php $_from = $this->_tpl_vars['events']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['event']):
?>
						<p><a href="<?php echo $this->_tpl_vars['event']['link']; ?>
"><?php echo $this->_tpl_vars['event']['title']; ?>
</a></p><br/>
					<?php endforeach; endif; unset($_from); ?>
				<?php else: ?>
					<p>No upcoming events, check back later.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>PINPoint Data Logger</h1>
			<div>
				<p>
					<img src="http://isense.cs.uml.edu/html/img/PINPointStack.jpg" width="203px" />
					The portable iSENSE Network Point (PINPoint) is a GPS-enabled data logger designed specifically for use with the iSENSE web site.<br/>
					<a href="/blog/doc/pinpoint-data-logger">Learn More...</a>
				</p>
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>Resources</h1>
			<div>
				<p>
					<a href="/blog/downloads">Duck!</a><br/>
					Get the software needed to use the PINPoint data logger.
				</p>
				<br/>				
				<p>
					<a href="/blog/doc/data-upload">Data Upload Tutorial</a><br/>
					Learn how to upload and share data on iSENSE.
				</p>
				<br/>				
				<p>
					<a href="/blog/doc/lesson-plans">Lesson Plans</a><br/>
					View ideas for using iSENSE in your classroom.
				</p>
				<br/>
				<p>
					<a href="/blog/help">iSENSE Help Guide</a><br/>
					Learn more about using the iSENSE web system.
				</p>
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:142px;">
			<div>
				<p>
					<div style="text-align:center">
						<img src="http://isense.cs.uml.edu/html/img/VernierLogo.gif" />
					</div>
				</p>
				<p>
					Data collected with Vernier probes can be imported into iSENSE. <br/>
					<a href="/blog/doc/vernier-logger-lite-instructions">Learn More...</a>
				</p>
			</div>
		</div>
		
		<div class="module_subsix module_subsix_right" style="height:142px;">
			<h1>Site Statistics</h1>
			<div>
				<p>
					<ul style="list-style-type:disc; padding-left:10px; margin-left:15px;">
						<li><?php echo $this->_tpl_vars['count_users']; ?>
 teachers and students, who created</li>
						<li><?php echo $this->_tpl_vars['count_exps']; ?>
 experiments, containing</li>
						<li><?php echo $this->_tpl_vars['count_sessions']; ?>
 sessions.</li>
					</ul>
				</p>
			</div>
		</div>
		
	</div>
</div>