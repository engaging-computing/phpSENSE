<?php /* Smarty version 2.6.22, created on 2011-11-29 17:16:02
         compiled from browse.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'browse.tpl', 31, false),array('modifier', 'date_diff', 'browse.tpl', 45, false),array('modifier', 'capitalize', 'browse.tpl', 66, false),array('modifier', 'substr', 'browse.tpl', 103, false),array('function', 'math', 'browse.tpl', 144, false),)), $this); ?>
<div id="main">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="searchboxwrapper">
		<div id="searchbox">
			<form method="GET" action="browse.php">
				<div>Search: <input type="text" name="query" value="<?php echo $this->_tpl_vars['query']; ?>
" /> <input type="hidden" name="type" value="<?php echo $this->_tpl_vars['type']; ?>
" /> <input type="submit" name="action" value="Search" /></div>
			</form>
			<?php if ($this->_tpl_vars['type'] != 'people' && $this->_tpl_vars['type'] != 'visualizations'): ?> 
				<div>
					<span>Sort:</span>
					<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['page']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=recent">Recent</a> | 
					<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['page']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=popularity">Popularity</a> | 
					<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['page']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=activity">Activity</a> | 
					<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['page']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=rating">Rating</a>
				</div>
			<?php endif; ?>
		</div>
		<div id="results">
			<?php if (! empty ( $this->_tpl_vars['results'] )): ?>
				
				<?php if ($this->_tpl_vars['type'] == 'visualizations'): ?>
				    
					<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
						<div class="result<?php if ($this->_tpl_vars['result']['is_activity'] == 1): ?> activity <?php else: ?> vis <?php endif; ?>">
							<table width="100%" cellpaddding="0" cellspacing="0">
								<tr>
									<td valign="top">
										<div class="name">
											<a href="visdir.php?id=<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
"><?php echo $this->_tpl_vars['result']['meta']['name']; ?>
</a>
										</div>
										<div class="description" ><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 180, "...", true) : smarty_modifier_truncate($_tmp, 180, "...", true)); ?>
</div>	
										<div class="sub">
                                            <?php if ($this->_tpl_vars['user']['administrator'] == 1): ?>
                                                <span class="loading_msg" style="display:none;">Loading...</span>
                                                <span style="color:#444">Feature:</span> <input type="checkbox" class="feature_vis" value="<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
" <?php if ($this->_tpl_vars['result']['meta']['featured'] == 1): ?>checked<?php endif; ?> />
                                                <?php if ($this->_tpl_vars['result']['meta']['featured'] == 1): ?>
                                                    <a style="display:inline" id="pickimage_<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
" href="pickvisimage.php?id=<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
">Pick Image</a>
                                                <?php else: ?>
                                                    <a style="display:none;" id="pickimage_<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
" href="pickvisimage.php?id=<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
">Pick Image</a>
                                                <?php endif; ?>
                                                <span style="color:#444">Hidden:</span> <input type="checkbox" class="hide_vis" value="<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
" <?php if ($this->_tpl_vars['result']['meta']['hidden'] == 1): ?> checked <?php endif; ?> />
                                                
                                                
                                            <?php endif; ?>
                                            <span> Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['timecreated'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
 </span>
										</div>
									</td>
									<!--
									<td width="48px">
										<img src="picture.php?type=experiment&amp;id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
&amp;w=75&amp;h=75" height="75px" width="75px" />
									</td>
									-->
								</tr>
							</table>
						</div>
					<?php endforeach; endif; unset($_from); ?>
				
				<?php elseif ($this->_tpl_vars['type'] == 'people'): ?>
				
					<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
						<div class="result">
							<table width="100%" cellpaddding="0" cellspacing="0">
								<tr>
									<td valign="top">
										<div class="name">
											<a href="profile.php?id=<?php echo $this->_tpl_vars['result']['user_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['firstname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['lastname'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a>
										</div>
										<div class="description" >
										    Created <?php echo $this->_tpl_vars['result']['experiment_count']; ?>
 <?php if ($this->_tpl_vars['result']['experiment_count'] == 1): ?>experiment<?php else: ?>experiments<?php endif; ?> and contributed <?php echo $this->_tpl_vars['result']['session_count']; ?>
 <?php if ($this->_tpl_vars['result']['session_count'] == 1): ?>session<?php else: ?>sessions<?php endif; ?>.
										</div>	
										<div class="sub">
											<span>Joined <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['firstaccess'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
										</div>
									</td>
									<!--
									<td width="48px">
										<img src="picture.php?id=<?php echo $this->_tpl_vars['result']['user_id']; ?>
&amp;w=75&amp;h=75" height="75px" width="75px" />
									</td>
									-->
								</tr>
							</table>
						</div>
					<?php endforeach; endif; unset($_from); ?>
					
				<?php else: ?>
				
					<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
						<div class="result">
							<table width="100%" cellpaddding="0" cellspacing="0">
								<tr>
									<td valign="top">
										<div class="name">
											<?php if ($this->_tpl_vars['type'] != 'activities'): ?>
											    <a href="experiment.php?id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['name'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a>
											<?php else: ?>
											    <a href="activity.php?id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['name'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</a>
											<?php endif; ?>
										</div>
										<div class="description" ><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 180, "...", true) : smarty_modifier_truncate($_tmp, 180, "...", true)); ?>
</div>	
										<div class="sub">
										    <a class="session_count"><?php echo $this->_tpl_vars['result']['session_count']; ?>
</a>
										    <a class="contrib_count"><?php echo $this->_tpl_vars['result']['contrib_count']; ?>
</a>
										    <a class="rating_browse"><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['rating_comp'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)); ?>
</a>
										    <?php if ($this->_tpl_vars['user']['administrator'] == 1): ?>
                                                <span class="loading_msg" style="display:none;">Loading...</span>
                                                <span style="color:#444">Feature:</span> <input type="checkbox" class="feature_experiment" value="<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
" <?php if ($this->_tpl_vars['result']['meta']['featured'] == 1): ?>checked<?php endif; ?> />
                                                <?php if ($this->_tpl_vars['result']['meta']['featured'] == 1): ?>
                                                    <a style="display:inline" id="pickimage_<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
" href="pickexpimage.php?id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
">Pick Image</a>
                                                <?php else: ?>
                                                    <a style="display:none;" id="pickimage_<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
" href="pickexpimage.php?id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
">Pick Image</a>
                                                <?php endif; ?>
                                                <span style="color:#444">Hidden:</span> <input type="checkbox" id="hide_vis" value="<?php echo $this->_tpl_vars['result']['meta']['vis_id']; ?>
" <?php if ($this->_tpl_vars['result']['meta']['hidden'] == 1): ?> checked <?php endif; ?> />
                                                
                                            <?php endif; ?>
											<span>Last Modified <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['meta']['timemodified'])) ? $this->_run_mod_handler('date_diff', true, $_tmp) : smarty_modifier_date_diff($_tmp)); ?>
</span>
											<?php if ($this->_tpl_vars['result']['meta']['hidden'] == 1): ?> 
												<br><span>This experiment is hidden</span>
											<?php endif; ?>
										</div>
									</td>
									<!--
									<td width="48px">
										<img src="picture.php?type=experiment&amp;id=<?php echo $this->_tpl_vars['result']['meta']['experiment_id']; ?>
&amp;w=75&amp;h=75" height="75px" width="75px" />
									</td>
									-->
								</tr>
							</table>
						</div>
					<?php endforeach; endif; unset($_from); ?>
					
				<?php endif; ?>
				
			<?php else: ?>
				<div class="result">Sorry, it we could not find any <?php echo $this->_tpl_vars['marker']; ?>
 matching your criteria.</div>
			<?php endif; ?>
		</div>
		<div class="pagination" style="margin-bottom: 10px;">
			<table cellpadding="0" cellspacing="0">
				<tr>

				<?php if ($this->_tpl_vars['page'] != 1): ?> 

					<td width="44">
							<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "y - x",'x' => 1,'y' => $this->_tpl_vars['page']), $this);?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=<?php echo $this->_tpl_vars['sort']; ?>
">Previous</a>
					</td>	

				<?php endif; ?>

					<?php if ($this->_tpl_vars['page'] > 5): ?>

						<td>
							<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=1&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=<?php echo $this->_tpl_vars['sort']; ?>
">
							1
							</a>
						</td>
						<td>
						...
						</td>

					<?php endif; ?>

					<?php $_from = $this->_tpl_vars['navbarpages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['navbar']):
?>

						<td>

							<?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['navbar']): ?> <u> <?php endif; ?>
							
								<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['navbar']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=<?php echo $this->_tpl_vars['sort']; ?>
">
									<?php echo $this->_tpl_vars['navbar']; ?>

								</a>

							<?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['navbar']): ?> </u> <?php endif; ?>

						</td>					

					<?php endforeach; endif; unset($_from); ?>

					<?php if ($this->_tpl_vars['page'] < $this->_tpl_vars['numpages'] - 4): ?>

						<td>
						...
						</td>
						<td>
							<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo $this->_tpl_vars['numpages']; ?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=<?php echo $this->_tpl_vars['sort']; ?>
">
							<?php echo $this->_tpl_vars['numpages']; ?>

							</a>
						</td>

					<?php endif; ?>

					<td width="44">
						<?php if ($this->_tpl_vars['next'] == true): ?>
								<a href="browse.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "x + y",'x' => 1,'y' => $this->_tpl_vars['page']), $this);?>
&amp;limit=<?php echo $this->_tpl_vars['limit']; ?>
&amp;action=<?php echo $this->_tpl_vars['action']; ?>
&amp;query=<?php echo $this->_tpl_vars['query']; ?>
&amp;sort=<?php echo $this->_tpl_vars['sort']; ?>
">Next</a>
						<?php else: ?>
							&nbsp;
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div id="sidebar">
	<div class="module">
		<?php if ($this->_tpl_vars['action'] == 'browse'): ?>
			You are currently viewing all <span style="font-weight:bold;"><?php echo $this->_tpl_vars['type']; ?>
</span> sorted <span style="font-weight:bold;"><?php echo $this->_tpl_vars['sorttext']; ?>
</span>.
		<?php else: ?>
			You are currently viewing all <span style="font-weight:bold;"><?php echo $this->_tpl_vars['type']; ?>
</span> with the term <span style="font-weight:bold;"><?php echo $this->_tpl_vars['query']; ?>
</span> sorted <span style="font-weight:bold;"><?php echo $this->_tpl_vars['sorttext']; ?>
</span>.
		<?php endif; ?>
	</div>
</div>