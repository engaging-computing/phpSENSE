<?php /* Smarty version 2.6.22, created on 2011-05-31 11:56:25
         compiled from vis.tpl */ ?>
<?php if ($this->_tpl_vars['is_saved']): ?>
    <div id="instructions" style="padding:8px 0 0 0;">
        <span style="font-weight:bold;">Explanation:</span> <?php echo $this->_tpl_vars['vis']['description']; ?>
<br/>
    </div>
    <br/>
<?php else: ?>
    <?php if ($this->_tpl_vars['activity']): ?>
    <div id="instructions" style="padding:8px 0 0 0;">
        <span style="font-weight:bold;">Activity Instructions:</span> <?php echo $this->_tpl_vars['activity_meta']['description']; ?>
<br/>
    </div>
    <br/>
    <?php endif; ?>
<?php endif; ?>
<div id="vis"></div>
<a id="savestateopen" href="#TB_inline?height=350&amp;width=500&amp;inlineId=hiddenModalContent" style="display:none;" class="thickbox">you cant see me</a>
<div id="hiddenModalContent" style="display:none;">
	<div>
		<strong>Publish Visualization</strong>
		<table id="savetable">
		    <tr><td colspan="2">&nbsp;</td></tr>
		    <tr>
		        <td width="100px">Title:</td>
		        <td><input type="text" id="savename" name="name" style="width:100%;" /></td>
		    </tr>
		    <tr><td>&nbsp;</td><td>(Please be specific.)</td></tr>
		    <tr><td colspan="2">&nbsp;</td></tr>
		    <tr>
		        <td valign="top">Explanation:</td>
		        <td>
					<textarea name="desc" id="savedesc" cols="45" rows="10" style="width:100%;"></textarea>
				</td>
			</tr>
			<tr><td>&nbsp;</td><td>(Describe why this is interesting.)</td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
			    <td colspan="2">
			        <input type="submit" id="savebutton" name="save" value="Publish" />
			    </td>
			</tr>
		</table>
		<table id="savedtable" style="display:none;">
			<tr>
				<td colspan="2">Your vis has been saved.</td>
			</tr>
			<tr>
				<td>You can access your saved visualization by using this url:</td>
				<td id="urlholder"></td>
			</tr>
		</table>
	</div>
</div>