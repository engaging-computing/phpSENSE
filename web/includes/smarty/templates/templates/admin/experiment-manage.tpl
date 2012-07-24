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
			<input type="submit" id="experimentnew" value="New Experiment" onclick="window.location.href='create.php';" /> 
			<input type="submit" id="experimentdelete" value="Delete Experiment" onclick="deleteExperiment();" /> 
			<input type="submit" id="experimentfeature" value="Feature Experiment" onclick="featureExperiment();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllFeaturedExperiments();">Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllNonFeaturedExperiments();">Non-Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllVisibleExperiments();">Visible</a>,
			<a href="javascript:void(0);" onclick="checkAllHiddenExperiments();">Hidden</a> 
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Experiment Title</td>
			<td>Creator</td>
			<td>Created On</td>
			<td>Last Modified</td>
			<td>Hidden?</td>
			<td>Featured?</td>
            <td>Manage Sessions</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=experiment }
				<tr>
					<td align="center"><input type="checkbox" name="news_{ $experiment.experiment_id }" id="news_{ $experiment.experiment_id }" value="{ $experiment.experiment_id }" /></td>
					<td><a href="experiment.php?id={ $experiment.experiment_id }">{ $experiment.name|capitalize }</a></td>
					<td><a href="profile.php?id={ $experiment.owner_id }">{ $experiment.firstname|capitalize } { $experiment.lastname|capitalize }</a></td>
					<td>{ $experiment.timecreated }</td>
					<td>{ $experiment.timemodified }</td>
					<td class="hidden">{ if $experiment.hidden == 1 }Yes{ else }No{ /if }</td>
					<td class="featured">{ if $experiment.featured == 1 }Yes{ else }No{ /if }</td>
                    <td><a href="admin.php?action=sessionmanage&id={$experiment.experiment_id}"><input type="button"  value="Manage"/></a></td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any experiments.</td>
			</tr>
		{ /if }
	</table>
</div>
