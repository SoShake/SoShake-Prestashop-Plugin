
{if $u2sFeature == 'fanbox'}
        <div class="up2-fanbox" url="{$u2sFanPage}"></div>
{elseif $u2sFeature == 'fbconnect'}
        <div class="up2-fbconnect" url="{$base_dir}"></div>
{else}
        <table style="width:100%">
        
        {foreach from=$featuresToDisplay item=u2sFeature}
                {if $u2sAlign == "vertical"}
                        <tr>
                                <td style="text-align:{$u2sPosition};">
                {else}
                        <td style="text-align:{$u2sPosition};">
                {/if}
                
                {if $u2sFeature == 'tweet'}
                        <div><div class="up2-tweet" categorie="{$u2sCategory}" ></div></div>
                {elseif $u2sFeature == 'like'}
                        <div><div class="up2-like" categorie="{$u2sCategory}" ></div></div>
                {elseif $u2sFeature == 'gplus'}
                        <div><div class="up2-gplus" categorie="{$u2sCategory}" ></div></div>
                {elseif $u2sFeature == 'linkedin'}
                        <div><div class="up2-linkedin" categorie="{$u2sCategory}" ></div></div>
                {elseif $u2sFeature == 'pinit'}
                        <div><div class="up2-pinit" categorie="{$u2sCategory}" ></div></div>
                {/if}
                
                {if $u2sAlign == "vertical"}
                                </td>
                        </tr>
                {else}
                        </td>
                {/if}
        {/foreach}
        
        </table>
{/if}
