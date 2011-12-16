<?php /* Smarty version 2.6.22, created on 2011-02-22 10:57:10
         compiled from parts/user.tpl */ ?>
<?php if ($this->_tpl_vars['user']['guest']): ?>
  <?php if ($this->_tpl_vars['title'] == 'Reset Password'): ?>
    Not logged in. Please <a href="login.php">login</a> or <a href="register.php">register</a>.
  <?php else: ?>
    Not logged in. Please <a href="login.php?ref=<?php echo $_SERVER['SCRIPT_NAME']; ?>
?<?php echo $_SERVER['QUERY_STRING']; ?>
">login</a> or <a href="register.php">register</a>.
  <?php endif; ?> 
 <?php else: ?>
Logged in as <a href="profile.php" id="profile_link" title="View your profile"><?php echo $this->_tpl_vars['user']['first_name']; ?>
 <?php echo $this->_tpl_vars['user']['last_name']; ?>
</a> | <a href="logout.php" title="Logout of your account">logout</a>
<?php endif; ?>