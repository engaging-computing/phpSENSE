<?php /* Smarty version 2.6.22, created on 2011-12-26 16:58:57
         compiled from parts/minishare.tpl */ ?>
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
	<h1>Share this Experiment</h1>
	<div id="share">
		<a href="http://twitter.com/home?status=I found this on @isenseproject http://<?php echo $_SERVER['SERVER_NAME']; ?>
<?php echo $_SERVER['REQUEST_URI']; ?>
" title="Click to send this page to Twitter!" target="_blank">Share with Twitter</a><br/>
		<?php echo '<script>function fbs_click() {u=location.href;t=document.title;window.open(\'http://www.facebook.com/sharer.php?u=\'+encodeURIComponent(u)+\'&t=\'+encodeURIComponent(t),\'sharer\',\'toolbar=0,status=0,width=626,height=436\');return false;}</script>'; ?>

		<a href="http://www.facebook.com/share.php?u=http://<?php echo $_SERVER['SERVER_NAME']; ?>
<?php echo $_SERVER['REQUEST_URI']; ?>
" onclick="return fbs_click()" target="_blank">Share with Facebook</a><br/>
		<a href="mailto:?Subject=Hello&Body=Hey,%0D%0DI wanted to share this experiment with you: http://isense/experiment.php?id=<?php echo $this->_tpl_vars['meta']['experiment_id']; ?>
%0D--%0D<?php echo $this->_tpl_vars['meta']['name']; ?>
%0D<?php echo $this->_tpl_vars['meta']['description']; ?>
 %0D%0D Let me know what you think.%0D%0DThanks,%0D<?php echo $this->_tpl_vars['owner']; ?>
">Share with Email</a>
	</div>
</div>