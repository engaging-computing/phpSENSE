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
{ if $is_following == "Yes" or $is_following == "No" }
<div class="module">
    {literal}<script>var follower = {/literal}{$user.user_id}{literal};</script>{/literal}
    {literal}<script>var followee = {/literal}{$userdata.user_id}{literal};</script>{/literal}
	<div id="graph_status_wrapper">
	    { if $is_following == "Yes"}
    	    <button id="graph_status" class="stop_following" onclick="stop_following({$user.user_id}, {$userdata.user_id})">Unfollow</button>
    	{ else }
    	    <button id="graph_status" class="start_following" onclick="start_following({$user.user_id}, {$userdata.user_id})">Follow</button>
        { /if }
    </div>
</div>
{ /if }

<div class="module">
    <h1>Followers</h1>
    <div>
    { if !empty($followers) }
		{ foreach from=$followers item=follower }
		    <a href="profile.php?id={$follower.user_id}">{ $follower.firstname } { $follower.lastname }</a><br/>
		{ /foreach }
	{ else }
	    { $userdata.firstname } doesn't have any followers.
	{ /if }
    </div>
</div>

<div class="module">
    <h1>Following</h1>
    { if !empty($following) }
		{ foreach from=$following item=followee }
		    <a href="profile.php?id={$followee.user_id}">{$followee.firstname} { $followee.lastname}</a><br/>
		{ /foreach }
	{ else }
    	{ $userdata.firstname } isn't following anyone.
	{ /if }
</div>