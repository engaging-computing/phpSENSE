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
<div id="main-full">
	<div id="management_toolbar">
		<div>
			
			<input type="submit" id="sessiondelete" value="Delete Session" onclick="deleteSession();" /> 
			<input type="submit" id="sessionhide" value="Hide Session" onclick="hideSession();" /> 
            <input type="submit" id="sessionunhide" value="Unhide Session" onclick="unhideSession();" /> 
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllHiddenSessions();">Hidden</a> 
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Session Name</td>
			<td>Creator</td>
            <td>Created</td>
			<td>Hidden?</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=session }
				<tr>
					<td align="center"><input type="checkbox" name="news_{ $session.session_id }" id="news_{ $session.session_id }" value="{ $session.session_id }" /></td>
                    <td><a href="newvis.php?sessions={$session.session_id}">{$session.name|substr:0:15|capitalize}</a></td>        
					<td>{$session.firstname|capitalize}</td>
                    <td>{$session.timecreated}</td>
					<td class="hidden">{ if $session.finalized == 0 }Yes{ else }No{ /if }</td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any sessions.</td>
			</tr>
		{ /if }
	</table>
</div>





