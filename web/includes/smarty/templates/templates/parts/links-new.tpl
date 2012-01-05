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
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF"/html/ SUCH
 * DAMAGE.
 -->
<ul>
    { if not $user.guest } <li><a href="/profile.php?id={ $user.user_id }" { if $marker == 'user' } class="youarehere" { /if } ><img src="/html/img/tags.png" alt="View the map"/>My Stuff</a></li> { /if }
<li><a href="/browse.php?type=experiments" { if $marker == 'experiments' } class="youarehere" { /if } ><img src="/html/img/drawer.png" alt="Browse experiments and their sessions"/>Experiments</a></li>
<li><a href="/browse.php?type=people" { if $marker == 'people' } class="youarehere" { /if } ><img src="/html/img/group.png" alt="Browse experiments and their sessions"/>People</a></li>
<li><a href="/browse.php?type=visualizations" { if $marker == 'visualizations' } class="youarehere" { /if } ><img src="/html/img/chart_bar.png" alt="Browse experiments and their sessions"/>Visualizations</a></li>
<li><a href="/browse.php?type=activities" { if $marker == 'activities' } class="youarehere" { /if } ><img src="/html/img/chart_line.png" alt="Browse activities"/>Activities</a></li>
{ if not $user.guest } <!--<li><a href="profile.php?id={ $user.user_id }" { if $marker == 'user' } class="youarehere" { /if } ><img src="/html/img/tags.png" alt="View the map"/>My Stuff</a></li>--> { /if }
{ if not $user.guest } <li><a href="/create.php" { if $marker == 'create' } class="youarehere" { /if } ><img src="/html/img/document_plus.png" alt="Make a new experiment"/>Create Experiment</a></li> { /if }
{ if $user.administrator == 1 } <li><a href="/admin.php" { if $marker == 'admin' } class="youarehere" { /if } ><img src="/html/img/document_plus.png" alt="Make a new experiment"/>Admin</a></li> { /if }
</ul>