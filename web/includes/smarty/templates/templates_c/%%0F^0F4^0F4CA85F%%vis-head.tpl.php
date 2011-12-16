<?php /* Smarty version 2.6.22, created on 2010-10-22 20:19:44
         compiled from parts/vis-head.tpl */ ?>
<!-- Start vis javascript includes -->
<link type="text/css" href="loader.php/viscss" rel="stylesheet" />
<?php if (! $this->_tpl_vars['activity']): ?>
<script type="text/javascript" src="ws/json.php?sessions=<?php echo $this->_tpl_vars['sessions']; ?>
&amp;state=<?php echo $this->_tpl_vars['state']; ?>
"></script>
<?php else: ?>
<script type="text/javascript" src="ws/json.php?sessions=<?php echo $this->_tpl_vars['sessions']; ?>
&amp;state=<?php echo $this->_tpl_vars['state']; ?>
&amp;aid=<?php echo $this->_tpl_vars['aid']; ?>
"></script>
<?php endif; ?>
<?php echo '<script type="text/javascript">var IS_ACTIVITY = '; ?>
<?php if ($this->_tpl_vars['activity']): ?>true<?php else: ?>false<?php endif; ?><?php echo ';</script>'; ?>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->_tpl_vars['GMAP_KEY']; ?>
&amp;sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="loader.php/vis"></script>
<!-- End vis javascript includes -->