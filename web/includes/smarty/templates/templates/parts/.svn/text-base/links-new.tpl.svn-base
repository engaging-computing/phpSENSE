<ul>
    { if not $user.guest } <li><a href="profile.php?id={ $user.user_id }" { if $marker == 'user' } class="youarehere" { /if } ><img src="html/img/tags.png" alt="View the map"/>My Stuff</a></li> { /if }
<li><a href="browse.php?type=experiments" { if $marker == 'experiments' } class="youarehere" { /if } ><img src="html/img/drawer.png" alt="Browse experiments and their sessions"/>Experiments</a></li>
<li><a href="browse.php?type=people" { if $marker == 'people' } class="youarehere" { /if } ><img src="html/img/group.png" alt="Browse experiments and their sessions"/>People</a></li>
<li><a href="browse.php?type=visualizations" { if $marker == 'visualizations' } class="youarehere" { /if } ><img src="html/img/chart_bar.png" alt="Browse experiments and their sessions"/>Visualizations</a></li>
<li><a href="browse.php?type=activities" { if $marker == 'activities' } class="youarehere" { /if } ><img src="html/img/chart_line.png" alt="Browse activities"/>Activities</a></li>
{ if not $user.guest } <!--<li><a href="profile.php?id={ $user.user_id }" { if $marker == 'user' } class="youarehere" { /if } ><img src="html/img/tags.png" alt="View the map"/>My Stuff</a></li>--> { /if }
{ if not $user.guest } <li><a href="create.php" { if $marker == 'create' } class="youarehere" { /if } ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Create Experiment</a></li> { /if }
{ if $user.administrator == 1 } <li><a href="admin.php" { if $marker == 'admin' } class="youarehere" { /if } ><img src="html/img/document_plus.png" alt="Make a new experiment"/>Admin</a></li> { /if }
</ul>