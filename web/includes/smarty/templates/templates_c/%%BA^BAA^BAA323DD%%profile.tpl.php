<?php /* Smarty version 2.6.22, created on 2011-11-09 20:06:36
         compiled from profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_diff', 'profile.tpl', 38, false),)), $this); ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
	<div id="main-full">
		<div>Guests do not have access to profiles. If you already have an account, click 
                     <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.
                </div>
	</div>
<?php else: ?>
	<div id="main">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<div id="searchbox" style="margin:0px 0px 10px 0px; padding:0px 0px 10px 0px; border-bottom:1px solid #CCC;">
			You are viewing: 
			<select id="filter" name="filter" onchange="filterProfile();">
				<option value="all">All Activity</option>
				<option value="experiment">Only Experiments</option>
				<option value="session">Only Sessions</option>
				<option value="vis">Only Visualizations</option>
				<option value="video">Only Videos</option>
				<option value="image">Only Images</option>
				<option value="activity">Only Activities</option>
			</select>
		</div>
				
		<div id="results">
			<?php if (! empty ( $this->_tpl_vars['results'] )): ?>
				<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>

					<?php if ($this->_tpl_vars['result']['type'] == 'experiment'): ?>
					
						<div class="result experiment" style="margin:0px 0px 10px 0px;">
							<div class="icon_column" style="float:left; width:40px; height:55px;"> 
							 <img src="../../../html/img/icons/archive.png"/>						
							</div>
							<div class="info_column" style="float;right; "> 
								<div class="name" style="font-size:18px;"> <a href="experiment.php?id=<?php echo $this->_tpl_vars['result']['experiment_id']; ?>
"><?php echo $this->_tpl_vars['result']['name']; ?>
</a></div>
        							<div class="description" style="font-size:13px;"><?php echo $this->_tpl_vars['result']['description']; ?>
</div>
								<div class="sub" style="font-size:10px;">
									<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
									<?php if ($this->_tpl_vars['result']['owner_id'] == $this->_tpl_vars['user']['user_id'] || $this->_tpl_vars['user']['administrator'] == 1): ?>
										<span><a href="javascript:void(0);" onclick="window.location.href='experiment-edit.php?id=<?php echo $this->_tpl_vars['result']['experiment_id']; ?>
';">
										Edit</a>
									</span>
									<?php endif; ?>
								</div>
							</div>
						</div>
						
					<?php elseif ($this->_tpl_vars['result']['type'] == 'session'): ?>
					
						<div class="result session" style="margin:0px 0px 10px 0px;">
                                                	<div class="icon_column" style="float:left; width:40px;"> 
								<img src="../../../html/img/icons/folder.png"/>						
							</div>
							<div class="info_column" style="float;right;"> 
								<div class="name" style="font-size:16px;"><a href="vis.php?sessions=<?php echo $this->_tpl_vars['result']['session_id']; ?>
"><?php echo $this->_tpl_vars['result']['name']; ?>
</a></div>
								<div class="description" style="font-size:13px;"><?php echo $this->_tpl_vars['result']['description']; ?>
</div>
								<div class="sub" style="font-size:10px;">
									<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
									<?php if ($this->_tpl_vars['result']['owner_id'] == $this->_tpl_vars['user']['user_id'] || $this->_tpl_vars['user']['administrator'] == 1): ?>
								    		<span><a href="javascript:void(0);" onclick="window.location.href='session-edit.php?id=<?php echo $this->_tpl_vars['result']['session_id']; ?>
';">Edit</a></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
						 
					<?php elseif ($this->_tpl_vars['result']['type'] == 'vis'): ?>

						<div class="result vis" style="margin:0px 0px 10px 0px;">
							<div class="icon_column" style="float:left; width:40px; height:55px;"> 
								<img src="../../../html/img/icons/chart_bar.png"/>					
							</div>
							<div class="info_column" style="float;right;"> 							
								<div class="name" style="font-size:16px;"><a href="vis.php?sessions=<?php echo $this->_tpl_vars['result']['sessions']; ?>
&state=<?php echo $this->_tpl_vars['result']['url_params']; ?>
"><?php echo $this->_tpl_vars['result']['name']; ?>
</a></div>
								<div class="description" style="font-size:13px;"><?php echo $this->_tpl_vars['result']['description']; ?>
blah</div>
								<div class="sub" style="font-size:13px;"><span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span></div>
							</div> 
						</div>
					
					<?php elseif ($this->_tpl_vars['result']['type'] == 'image'): ?>
					
						<div class="result image" style="margin:0px 0px 10px 0px;">
							<div class="name" style="font-size:16px;"><?php echo $this->_tpl_vars['result']['name']; ?>
</div>
							<div class="description" style="font-size:13px;"><img src="picture.php?url=<?php echo $this->_tpl_vars['result']['provider_url']; ?>
" /></div>
							<div class="sub" style="font-size:13px;">
								<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
							</div>
						</div>
					
					<?php elseif ($this->_tpl_vars['result']['type'] == 'video'): ?>
					
						<div class="result video" style="margin:0px 0px 10px 0px;">
							<div class="name" style="font-size:16px;"><?php echo $this->_tpl_vars['result']['name']; ?>
</div>
							<div class="description" style="font-size:13px;"><?php echo $this->_tpl_vars['result']['description']; ?>
</div>
							<div class="sub" style="font-size:13px;">
								<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
							</div>
						</div>
						
					<?php else: ?>
					    
					    <div class="result activity" style="margin:0px 0px 10px 0px;">
					        <div class="name">
					           <a href="visdir.php?id=<?php echo $this->_tpl_vars['result']['vis_id']; ?>
">
					               <?php echo $this->_tpl_vars['result']['owner_firstname']; ?>
 <?php echo $this->_tpl_vars['result']['owner_lastname']; ?>
 completed <?php echo $this->_tpl_vars['result']['exp_name']; ?>

					            </a>
					        </div>
					        <div class="description" style="font-size:13px;"><?php echo $this->_tpl_vars['result']['description']; ?>
</div>
					        <div class="sub" style="font-size:13px;">
								<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['timeobj'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
							</div>
					    </div>
					    
					<?php endif; ?>

				<?php endforeach; endif; unset($_from); ?>
			<?php else: ?>
			    <div class="result activity" style="margin:0px 0px 10px 0px;">
			        <div class="name">
			            <?php if ($this->_tpl_vars['is_owner']): ?>
			                No one has completed your activities.
			            <?php else: ?>
			                <?php echo $this->_tpl_vars['userdata']['firstname']; ?>
 has not completed any activities.
			            <?php endif; ?>
			        </div>
			    </div>
			<?php endif; ?>
		</div>
	</div>
	<div id="sidebar">
		<div class="module">
			<div style="text-align:center;">
				<img src="picture.php?id=<?php echo $this->_tpl_vars['id']; ?>
" alt="No picture uploaded." />
			</div>
			<div>
				<div>
					<?php echo $this->_tpl_vars['userdata']['firstname']; ?>
 is from <?php echo $this->_tpl_vars['userdata']['city']; ?>
 and joined iSENSE on <?php echo $this->_tpl_vars['userdata']['firstaccess']; ?>
.
				</div>
			</div>
		</div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/graph.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<!--
		<div class="module">
			<h1><?php echo $this->_tpl_vars['userdata']['firstname']; ?>
's Stats</h1>
			<div>
				<?php echo $this->_tpl_vars['counts']['experiments']; ?>
 Experiments<br/>
				<?php echo $this->_tpl_vars['counts']['sessions']; ?>
 Sessions<br/>
				<?php echo $this->_tpl_vars['counts']['vises']; ?>
 Visualizations<br/>
				<?php echo $this->_tpl_vars['counts']['videos']; ?>
 Videos<br/>
				<?php echo $this->_tpl_vars['counts']['images']; ?>
 Images<br/>
			</div>
		</div>
		-->
	</div>
<?php endif; ?>