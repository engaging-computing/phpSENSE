<div style="margin-top:7px;" id="expimagewrapper">
    {if !empty($images)}
        {foreach from=$images item=img}
            {if $img.provider_url == $imgurl}
                <img class="selectexpimage" style="height:120px;width:165px;padding:1px 1px 1px 1px;border:4px solid #2396e6;background:#fff;" src="{$img.provider_url}"/>
            {/if}
        {/foreach}
        {foreach from=$images item=img}
            {if $img.provider_url != $imgurl}
                <img class="selectexpimage" style="height:120px;width:165px;margin:5px;" src="{$img.provider_url}"/>
            {/if}
        {/foreach} 
    {/if}
    <a href="upload-pictures.php?id={$expid}"><img style="height:120px;width:165px;margin:5px;" src="html/img/isenseaddimage.png"/></a>
</div>
<input type="hidden" id="storedexpid" value="{$expid}"/>
