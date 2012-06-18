<div id="blahblahbody" width="100%" height="100%">
   {if isset($sortArray)}
        <table width="100%" id="edit_table">
            <thead>
                {foreach from=$tableKeys key=col item=tKey}
                    <th id='h{$col}'>{$tKey}</th>
                {/foreach}
            </thead>
        {foreach from=$sortArray key=r item=row}
            <tr > 
                {foreach from=$row key=d item=dp}
                        {foreach from=$dp item=point}
                            <td>{if $point=='' or $point == 0}0{else}{$point}{/if}</td>
                        {/foreach}
                {/foreach}
            </tr>
        {/foreach}
        </table>

    {else}
        ohhh noez
    {/if}
</div>