<div id="edit_table" width="100%" height="100%">
   {if isset($sortArray)}
        <table width="100%" class="edit_table">
            <thead>
                {foreach from=$tableKeys key=col item=tKey}
                    <th>{$tKey}</th>
                {/foreach}
            </thead><tbody>
        {foreach from=$sortArray key=r item=row}
            <tr > 
                {foreach from=$row key=d item=dp}
                        {foreach from=$dp item=point}
                            <td>{$point}</td>
                        {/foreach}
                {/foreach}
            </tr>
        {/foreach}
        </tbody></table>

    {else}
        ohhh noez
    {/if}
</div>

<div id="ExperimentID" style="display:none"> {$eid} </div>
<div id="SessionID" style="display:none"> {$sid} </div>