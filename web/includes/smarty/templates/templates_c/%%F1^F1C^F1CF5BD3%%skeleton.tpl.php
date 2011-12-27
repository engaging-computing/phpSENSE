<?php /* Smarty version 2.6.22, created on 2011-12-26 16:54:06
         compiled from skeleton.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'skeleton.tpl', 31, false),)), $this); ?>
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php if ($this->_tpl_vars['title'] == 'Featured Experiment'): ?>Home<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
<?php endif; ?> - iSENSE</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		
		<!--<script type="text/javascript" src="loader.php/js"></script>-->
		<script type="text/javascript" src="/html/js/lib/jquery.js"></script>
		<script type="text/javascript" src="/html/js/lib/jquery-ui.js"></script>
        <script type="text/javascript" src="/html/js/lib/thickbox.js"></script>
        <script type="text/javascript" src="/html/js/lib/flydom.js"></script>
        <script type="text/javascript" src="/html/js/lib/autocomplete.js"></script>
        <script type="text/javascript" src="/html/js/lib/rating.js"></script>
        <script type="text/javascript" src="/html/js/isense.js"></script>
		
		<link rel="stylesheet" type="text/css" href="loader.php/css" />
		<link rel="shortcut icon" href="img/favicon.png" />
		
		<?php echo $this->_tpl_vars['head']; ?>

	</head>
	<body>
		<div id="container">
  			<div id="header">
    			<div id="logo"><a href="." title="iSENSE"><img src="html/img/logo.png" alt="iSENSE"/></a></div>
    			<div id="navigation">
      				<div id="quickbar">
        				<div id="user"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/user.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
      				</div>
      				<div id="links"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/links-new.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
    			</div>
  			</div>
            
			<?php if ($this->_tpl_vars['title'] != ""): ?>
				<div id="pagetitle"><?php if ($this->_tpl_vars['link'] != ""): ?><?php echo $this->_tpl_vars['link']; ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
<?php endif; ?></div>
			<?php endif; ?>
  			<div id="content"><?php echo $this->_tpl_vars['content']; ?>
</div>
  			<div class="button"></div>
		</div>
		<div id="footer"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "parts/google.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</body>
</html>