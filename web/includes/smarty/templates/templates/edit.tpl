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
                            <td>{if $point=='' or $point == 0}0{else}{$point}{/if}</td>
                        {/foreach}
                {/foreach}
            </tr>
        {/foreach}
        </tbody></table>

    {else}
        ohhh noez
    {/if}
</div>