{if $up2Bu == 'true'}
        <script src="http://soshake.com/api/LikeButton.js"></script>
        <div id="u2s-CrossSellProduct-single" style="display:none;">
                <div class="up2-text">{$up2_intro}</div>
                <ul class="up2-carrousel-products">
                {foreach name=crossSell from=$up2CrossSellProducts item=u2sProduct}
                        <li id="up2-product{$smarty.foreach.crossSell.iteration}" style="display:none;">
                        <table>
                                <tr>
                                        <td>
                                                <a href="{$link->getProductLink($u2sProduct->id, $u2sProduct->id.link_rewrite)|escape:'htmlall':'UTF-8'}" class="u2s-CrossSellProduct-lien">
                                                <img src="{$link->getImageLink($u2sProduct->link_rewrite, $u2sProduct->getCoverWs(), 'large')}" />
                                                </a>
                                        </td>
                                        <td>
                                                <a href="{$link->getProductLink($u2sProduct->id, $u2sProduct->id.link_rewrite)|escape:'htmlall':'UTF-8'}" class="u2s-CrossSellProduct-lien">
                                                <div class="up2-h2">{$u2sProduct->name[$cookie->id_lang]}</div>
                                                <p class="up2-price">{$u2sProduct->price}</p>
                                                <p>{$u2sProduct->description_short[$cookie->id_lang]}</p>
                                                </a>
                                                <a href="{$link->getPageLink('cart.php')}?qty=1&id_product={$u2sProduct->id|intval}&token={$static_token}&add" class="u2s-CrossSellProduct-lien u2s-add-to-cart">Ajouter au panier</a>
                                        </td>
                               </tr>
                        </table>
                        </li>
                {/foreach}
                </ul>
                <img src="http://up2social.com/lib/images/arrow_left.png" id="up2-arrow-left" />
                <img src="http://up2social.com/lib/images/arrow_right.png" id="up2-arrow-right" />
        </div>
        <div id="u2s-CrossSellProduct-grid" style="display:none;">
                <div class="up2-text">{$up2_intro}</div>
                <table>
                        <tr>
                {foreach name=crossSell from=$up2CrossSellProducts item=u2sProduct}
                                <td>
                                        <a href="{$link->getProductLink($up2CrossSellProducts[u2si]->id, $up2CrossSellProducts[u2si]->id.link_rewrite)|escape:'htmlall':'UTF-8'}" class="u2s-CrossSellProduct-lien">
                                                <img src="{$link->getImageLink($u2sProduct->link_rewrite, $u2sProduct->getCoverWs(), 'large')}" />
                                                <div class="up2-h2">{$up2CrossSellProducts[u2si]->name[$cookie->id_lang]}</div>
                                                <p class="up2-price">{$up2CrossSellProducts[u2si]->price}</div>
                                        </a>
                                </td>
                {/foreach}
                       </tr>
                </table>
        </div>

{/if}
{if $up2FB == 'true'}
        <script src="http://soshake.com/api/FanBox.js"></script>
{/if}
{if $up2FBC == 'true'}
        <script src="http://soshake.com/api/FBConnect.js"></script>
{/if}
