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
{ if $is_saved }
    <div id="instructions" style="padding:8px 0 0 0;">
        <span style="font-weight:bold;">Explanation:</span> { $vis.description }<br/>
    </div>
    <br/>
{ else }
    { if $activity }
    <div id="instructions" style="padding:8px 0 0 0;">
        <span style="font-weight:bold;">Activity Instructions:</span> { $activity_meta.description }<br/>
    </div>
    <br/>
    { /if }
{ /if }
<div id="vis"></div>
<a id="savestateopen" href="#TB_inline?height=350&amp;width=500&amp;inlineId=hiddenModalContent" style="display:none;" class="thickbox">you cant see me</a>
<div id="hiddenModalContent" style="display:none;">
	<div>
		<strong>Publish Visualization</strong>
		<table id="savetable">
		    <tr><td colspan="2">&nbsp;</td></tr>
		    <tr>
		        <td width="100px">Title:</td>
		        <td><input type="text" id="savename" name="name" style="width:100%;" /></td>
		    </tr>
		    <tr><td>&nbsp;</td><td>(Please be specific.)</td></tr>
		    <tr><td colspan="2">&nbsp;</td></tr>
		    <tr>
		        <td valign="top">Explanation:</td>
		        <td>
					<textarea name="desc" id="savedesc" cols="45" rows="10" style="width:100%;"></textarea>
				</td>
			</tr>
			<tr><td>&nbsp;</td><td>(Describe why this is interesting.)</td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
			    <td colspan="2">
			        <input type="submit" id="savebutton" name="save" value="Publish" />
			    </td>
			</tr>
		</table>
		<table id="savedtable" style="display:none;">
			<tr>
				<td colspan="2">Your vis has been saved.</td>
			</tr>
			<tr>
				<td>You can access your saved visualization by using this url:</td>
				<td id="urlholder"></td>
			</tr>
		</table>
	</div>
</div>
