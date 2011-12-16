<?php /* Smarty version 2.6.22, created on 2010-10-22 20:09:18
         compiled from parts/google.tpl */ ?>
<?php echo '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker('; ?>
'<?php echo $this->_tpl_vars['google_account']; ?>
'<?php echo ');
pageTracker._trackPageview();
} catch(err) {}</script>
'; ?>
