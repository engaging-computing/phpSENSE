<?php /* Smarty version 2.6.22, created on 2010-10-22 20:11:42
         compiled from parts/minishare.tpl */ ?>
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