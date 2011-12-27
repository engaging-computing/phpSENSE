<?php /* Smarty version 2.6.22, created on 2011-12-26 16:58:57
         compiled from parts/minipics.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'parts/minipics.tpl', 31, false),)), $this); ?>
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
<div class="module">
	<?php if (! $this->_tpl_vars['user']['guest']): ?><div style="float:right; font-size:80%;"><a href="upload-pictures.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
">Add Pictures</a></div><?php endif; ?>
	<h1>Pictures</h1>
	<?php if (count($this->_tpl_vars['pictures']) > 0): ?>
		<div id="pictures" style="overflow-x:scroll;"> 
			<table>
				<tr>
					<?php $_from = $this->_tpl_vars['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['picture']):
?>
						<td>
							<a href="<?php echo $this->_tpl_vars['picture']['set_url']; ?>
"><img src="picture.php?url=<?php echo $this->_tpl_vars['picture']['source']; ?>
&amp;h=160&w=230" /></a>
						</td>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
			</table>
		</div>
	<?php else: ?>
		<div id="pictures"> 
			<table width="100%">
				<tr>
					<td>No pictures found.</td>
				</tr>
			</table>
		</div>
	<?php endif; ?>
</div>