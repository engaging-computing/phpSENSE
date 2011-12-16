{ if $user.guest }
  { if $title == 'Reset Password' }
    Not logged in. Please <a href="login.php">login</a> or <a href="register.php">register</a>.
  { else }
    Not logged in. Please <a href="login.php?ref={$smarty.server.SCRIPT_NAME}?{$smarty.server.QUERY_STRING}">login</a> or <a href="register.php">register</a>.
  { /if } 
 { else }
Logged in as <a href="profile.php" id="profile_link" title="View your profile">{ $user.first_name } { $user.last_name }</a> | <a href="logout.php" title="Logout of your account">logout</a>
{ /if }
