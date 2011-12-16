<ul>
<li><a href="index.php" { if $marker == 'home' } class="youarehere" { /if } ><img src="html/img/home.png" alt="Your experiments and sessions"/>Home</a></li>
<li><a href="browse.php" { if $marker == 'browse' } class="youarehere" { /if } ><img src="html/img/drawer.png" alt="Browse experiments and their sessions"/>Browse</a></li>
{ if not $user.guest } <li><a href="profile.php?id={ $user.user_id }" { if $marker == 'user' } class="youarehere" { /if } ><img src="html/img/tags.png" alt="View the map"/>My Stuff</a></li> { /if }
{ if not $user.guest } <li><a href="create.php" { if $marker == 'create' } class="youarehere" { /if } ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Create Experiment</a></li> { /if }
{ if $user.administrator == 1 } <li><a href="admin.php" { if $marker == 'admin' } class="youarehere" { /if } ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Admin</a></li> { /if }
</ul>
