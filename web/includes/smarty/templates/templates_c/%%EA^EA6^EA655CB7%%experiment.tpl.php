<?php /* Smarty version 2.6.22, created on 2011-12-27 00:51:27
         compiled from experiment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'experiment.tpl', 165, false),)), $this); ?>
<div id="main">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			<table width="100%" class="profile">
				<tr>
				    <?php if (! $this->_tpl_vars['activity']): ?>
					    <td class="heading" valign="top">Procedure:</td>
					<?php else: ?>
					    <td class="heading" valign="top">Instructions:</td>
					<?php endif; ?>
					<td><?php echo $this->_tpl_vars['meta']['description']; ?>
</td>
				</tr>
				<tr>
					<td class="heading" valign="top">Fields:</td>
					<td>
						<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
							<?php echo $this->_tpl_vars['fields'][$this->_sections['foo']['index']]['field_name']; ?>
 (<?php echo $this->_tpl_vars['fields'][$this->_sections['foo']['index']]['unit_abbreviation']; ?>
)<?php if ($this->_sections['foo']['total']-1 != $this->_sections['foo']['index']): ?>, <?php endif; ?>
						<?php endfor; endif; ?>
					</td>
				</tr>
				<tr>
					<td class="heading" valign="top">Tags:</td>
					<td>
						<?php if ($this->_tpl_vars['tags']): ?>
							<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['tags']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
								<a href="browse.php?action=search&amp;query=<?php echo $this->_tpl_vars['tag']['tag']; ?>
"><?php echo $this->_tpl_vars['tags'][$this->_sections['foo']['index']]['tag']; ?>
</a><?php if ($this->_sections['foo']['total']-1 != $this->_sections['foo']['index']): ?>, <?php endif; ?>
							<?php endfor; endif; ?>
						<?php endif; ?>
					</td>
				</tr>	
				<tr>
					<td class="heading" valign="top">Creator:</td>
					<td><a href="profile.php?id=<?php echo $this->_tpl_vars['meta']['owner_id']; ?>
"><?php echo $this->_tpl_vars['meta']['firstname']; ?>
 <?php echo $this->_tpl_vars['meta']['lastname']; ?>
</a></td>
				</tr>
				<tr>
					<td class="heading" valign="top">Created:</td>
					<td><?php echo $this->_tpl_vars['meta']['timecreated']; ?>
</td>
				</tr>
				<tr>
					<td class="heading" valign="top">Last Updated:</td>
					<td><?php echo $this->_tpl_vars['meta']['timemodified']; ?>
</td>
				</tr>
				<?php if ($this->_tpl_vars['user']['administrator'] == 1): ?>
					<tr>
						<td class="heading" valign="top">Feature:</td>
						<td>
							<input type="checkbox" id="feature_experiment" name="feature_experiment" value="<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
" <?php if ($this->_tpl_vars['meta']['featured'] == 1): ?>checked<?php endif; ?>/>
							<span id="loading_msg" style="display:none;">Loading...</span>
						</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Hidden:</td>
						<td>
							<input type="checkbox" id="hide_experiment" name="hide_experiment" value="<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
" <?php if ($this->_tpl_vars['meta']['hidden'] == 1): ?>checked<?php endif; ?>/>
							<span id="hidden_loading_msg" style="display:none;">Loading...</span>
						</td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
	<div id="sessions" style="min-height:60px; margin:20px 0px 0px 0px;">
	    <?php if ($this->_tpl_vars['activity'] && ! $this->_tpl_vars['user']['guest']): ?>
	        <div style="margin:6px 0px;">
    			<div class="featured_head">
    			    <div>Activity Tools</div>
    			</div>
        	    <div class="featured_body" style="padding:6px 0px 6px 6px;">
        	        <div><input type="submit" style="width:73px;" value="<?php if (! $this->_tpl_vars['activity']): ?>Visualize<?php else: ?>Complete<?php endif; ?>" onclick="loadVis(<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
, <?php if ($this->_tpl_vars['activity']): ?>true<?php else: ?>false<?php endif; ?>);"/> - <?php if ($this->_tpl_vars['activity']): ?>View data for this experiment and solve for the prompt.<?php else: ?>Select sessions below to visualize data<?php endif; ?></div>
        	    </div>
        	</div>
	    <?php endif; ?>
	    
	    <?php if (! $this->_tpl_vars['activity']): ?>
	        <div style="margin:6px 0px;">
			    <div class="featured_head">
			        <div>Experiment Tools</div>
			    </div>
    	        <div class="featured_body" style="padding:6px 0px 6px 6px;">
    	            <?php if (! $this->_tpl_vars['user']['guest']): ?>

						<?php if ($this->_tpl_vars['meta']['experiment_id'] == 350): ?>
    	                	<div style="margin:0px 0px 6px 0px;"><input type="submit" style="width:73px;" value="Contribute" onclick="window.location.href='./tsor.php';"/> - Contribute data to this experiment.</div>
						<?php else: ?>
							<div style="margin:0px 0px 6px 0px;"><input type="submit" style="width:73px;" value="Contribute" onclick="window.location.href='upload.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
';"/> - Contribute data to this experiment.</div>
						<?php endif; ?>

    	                <div style="margin:0px 0px 6px 0px;"><input type="submit" style="width:73px;" value="Export" onclick="loadExport(<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
);"/> - Download data from selected sessions.</div>
    	                <div style="margin:0px 0px 6px 0px;"><input type="submit" style="width:73px;" value="Activity" onclick="createActivity(<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
);"/> - Create an activity for users to complete.</div>
    	                <?php if ($this->_tpl_vars['user']['user_id'] == $this->_tpl_vars['meta']['owner_id'] || $this->_tpl_vars['user']['administrator'] == 1): ?>
    	                    <div style="margin:0px 0px 6px 0px;"><input type="submit" style="width:73px;" value="Edit" onclick="window.location.href='experiment-edit.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
'"/> - Edit this experiment.</div>
    	                <?php endif; ?>
    	            <?php endif; ?>
    	            <div><input type="submit" style="width:73px;" value="<?php if (! $this->_tpl_vars['activity']): ?>Visualize<?php else: ?>Complete<?php endif; ?>" onclick="loadVis(<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
, <?php if ($this->_tpl_vars['activity']): ?>true<?php else: ?>false<?php endif; ?>);"/> - <?php if ($this->_tpl_vars['activity']): ?>View data for this experiment and solve for the prompt.<?php else: ?>Select sessions below to visualize data<?php endif; ?></div>
    	            <?php if (! $this->_tpl_vars['activity'] && $this->_tpl_vars['user']['administrator'] == $this->_tpl_vars['user']['administrator']): ?>
            	        <div><input type="submit" style="width:73px;" value="<?php if (! $this->_tpl_vars['activity']): ?>Vis Beta<?php else: ?>Complete<?php endif; ?>" onclick="loadVis2(<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
, <?php if ($this->_tpl_vars['activity']): ?>true<?php else: ?>false<?php endif; ?>);"/> - Use our visualizations beta to examine your data. </div>
            	    <?php endif; ?>
    	        </div>
    	    </div>
	    <?php endif; ?>
	    
		<div style="margin:6px 0px;">
			<div class="popular_head">
				<div style="float:right;"><a href="javascript:void(0);" onclick="checkAll('#session_list');">Check All</a> | <a href="javascript:void(0);" onclick="uncheckAll('#session_list');">Uncheck All</a></div>
				<div>Experiment Data</div>
			</div>
			<div class="popular_body">
				<table id="session_list" width="100%" cellpadding="0" cellspacing="0">
					<?php if ($this->_tpl_vars['sessions']): ?>
						<?php $_from = $this->_tpl_vars['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['session']):
?>
							<tr>
								<td width="35%" style="border-bottom:1px solid #CCC;">
									<div style="padding:3px 0px;">
										<table width="100%" cellpadding="0" cellspacing="0">
											<tr>
												<td rowspan="4"><input type="checkbox" name="sessions" value="<?php echo $this->_tpl_vars['session']['session_id']; ?>
" <?php if ($this->_tpl_vars['i'] == 0): ?>checked<?php endif; ?>></td>
											</tr>
											<tr >
												<td rowspan="4" width="34px"><img src="picture.php?id=<?php echo $this->_tpl_vars['session']['owner_id']; ?>
&h=32&w=32" height="32px" width="32px"></td>
											</tr>
											<tr>
												<td valign="top"><a href="profile.php?id=<?php echo $this->_tpl_vars['session']['owner_id']; ?>
"><?php echo $this->_tpl_vars['session']['firstname']; ?>
 <?php echo $this->_tpl_vars['session']['lastname']; ?>
</a>
											</tr>
											<tr>
												<td valign="top">
													<?php echo $this->_tpl_vars['session']['timecreated']; ?>

												</td>
											</tr>
										</table>
									</div>
								</td>
								<td style="border-bottom:1px solid #CCC;" <?php if ($this->_tpl_vars['session']['owner_id'] != $this->_tpl_vars['user']['user_id']): ?> colspan="2" <?php endif; ?>>
									<a href="<?php if ($this->_tpl_vars['newvis'] == 1): ?>new<?php endif; ?>vis.php?sessions=<?php echo $this->_tpl_vars['session']['session_id']; ?>
"><?php echo $this->_tpl_vars['session']['name']; ?>
</a>
									<?php if ($this->_tpl_vars['user']['administrator'] == 1): ?><br/><?php echo $this->_tpl_vars['session']['debug_data']; ?>
<?php endif; ?>
								</td>
								<td style="border-bottom:1px solid #CCC;">
									<?php echo smarty_function_counter(array('start' => 0,'skip' => 1,'assign' => 'imginc'), $this);?>

									<?php $_from = $this->_tpl_vars['expimages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['expimg']):
?>
										<?php if ($this->_tpl_vars['expimg']['session_id'] == $this->_tpl_vars['session']['session_id'] && $this->_tpl_vars['imginc'] < 7): ?>
											<?php echo smarty_function_counter(array(), $this);?>

											<a class="nounderline" href="<?php echo $this->_tpl_vars['expimg']['provider_url']; ?>
">
												<img src="<?php echo $this->_tpl_vars['expimg']['provider_url']; ?>
" width="30px" height="30px"/>
											</a>
										<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
								</td>
								<?php if ($this->_tpl_vars['session']['owner_id'] == $this->_tpl_vars['user']['user_id'] || $this->_tpl_vars['user']['administrator'] == 1): ?>
								    <td style="border-bottom:1px solid #CCC;">
										<a href="session-upload-pictures.php?sid=<?php echo $this->_tpl_vars['session']['session_id']; ?>
&id=<?php echo $this->_tpl_vars['id']; ?>
">Add Image</a> - 
								        <a href="javascript:void(0);" onclick="window.location.href='session-edit.php?id=<?php echo $this->_tpl_vars['session']['session_id']; ?>
';">Edit</a>
								    </td>
								<?php endif; ?>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					<?php else: ?>
						<tr>
							<td style=" padding:6px 0px 0px 0px; font-weight:bold; font-style: italic;">No sessions were found.</td>
						</tr>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="sidebar">
	<div class="module">
		<h1>Session Map</h1>
		<div id="minimap"><div id="map_canvas" style="margin:4px 0px 0px 0px; width:240px; height:240px; overflow:hidden;"></div></div>
	</div>
	
	<div class="module">
		<h1>Community Rating</h1>
		<div id="community_rating" style="height:16px; padding:4px 0px; text-align:center;">
			<?php if ($this->_tpl_vars['user']['guest']): ?>
				<span class="star-rating-control">
					<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)1;
$this->_sections['foo']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
						<div id="star<?php echo $this->_sections['foo']['index']; ?>
" class="star-rating rater-0 star star-rating-applied <?php if ($this->_sections['foo']['index'] <= $this->_tpl_vars['rating']): ?>star-rating-hover<?php endif; ?> unclickable">
							<a title="on" disabled="disabled">on</a>
						</div>
					<?php endfor; endif; ?>
				</span>
			<?php else: ?>
				<?php unset($this->_sections['foo']);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['start'] = (int)1;
$this->_sections['foo']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
if ($this->_sections['foo']['start'] < 0)
    $this->_sections['foo']['start'] = max($this->_sections['foo']['step'] > 0 ? 0 : -1, $this->_sections['foo']['loop'] + $this->_sections['foo']['start']);
else
    $this->_sections['foo']['start'] = min($this->_sections['foo']['start'], $this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] : $this->_sections['foo']['loop']-1);
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = min(ceil(($this->_sections['foo']['step'] > 0 ? $this->_sections['foo']['loop'] - $this->_sections['foo']['start'] : $this->_sections['foo']['start']+1)/abs($this->_sections['foo']['step'])), $this->_sections['foo']['max']);
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
?>
					<input id="star<?php echo $this->_sections['foo']['index']; ?>
" name="star" type="radio" class="star"/>
				<?php endfor; endif; ?>
			<?php endif; ?>
		</div>
		<div id="rating_prompt">
			<input id="rating" name="rating" value="<?php echo $this->_tpl_vars['rating']; ?>
" type="hidden" />
			<?php if ($this->_tpl_vars['user']['guest']): ?>
				<a href="login.php?ref=<?php echo $_SERVER['SCRIPT_NAME']; ?>
?<?php echo $_SERVER['QUERY_STRING']; ?>
">Login</a> or <a href="register.php">Join</a> to rate.
			<?php else: ?>
				Click a star to add your rating.
			<?php endif; ?>
		</div>
	</div>
	
	<?php if (! $this->_tpl_vars['activity']): ?>
	    <div class="module">
    		<h1>Visualizations</h1>
    		<?php if ($this->_tpl_vars['vises']): ?>
    			<div id="vises">
    				<table width="100%">
    					<?php $_from = $this->_tpl_vars['vises']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vis']):
?>
    						<tr>
    							<td><a href="visdir.php?id=<?php echo $this->_tpl_vars['vis']['vis_id']; ?>
"><?php echo $this->_tpl_vars['vis']['name']; ?>
</a></td>
    						</tr>
    					<?php endforeach; endif; unset($_from); ?>
    				</table>
    			</div>
    		<?php else: ?>
    			<div id="vises"> 
    				<table width="100%">
    					<tr>
    						<td>No visualizations found.</td>
    					</tr>
    				</table>
    			</div>
    		<?php endif; ?>
    	</div> 
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/minipics.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/minivids.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/minishare.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
        <div class="module">
    		<h1>Responses</h1>
    		<?php if ($this->_tpl_vars['vises']): ?>
    			<div id="vises">
    				<table width="100%">
    					<?php $_from = $this->_tpl_vars['vises']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vis']):
?>
    						<tr>
    							<td><a href="visdir.php?id=<?php echo $this->_tpl_vars['vis']['vis_id']; ?>
"><?php echo $this->_tpl_vars['vis']['name']; ?>
</a></td>
    						</tr>
    					<?php endforeach; endif; unset($_from); ?>
    				</table>
    			</div>
    		<?php else: ?>
    			<div id="vises"> 
    				<table width="100%">
    					<tr>
    						<td>No responses found.</td>
    					</tr>
    				</table>
    			</div>
    		<?php endif; ?>
    	</div>
	<?php endif; ?>
</div>