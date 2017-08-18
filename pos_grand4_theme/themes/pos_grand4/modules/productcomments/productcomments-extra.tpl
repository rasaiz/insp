 {*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if (!$content_only && (($nbComments == 0 && $too_early == false && ($is_logged || $allow_guests)) || ($nbComments != 0)))}
<div id="product_comments_block_extra" class="no-print" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">


	<ul class="comments_advices">
		{if $nbComments != 0}
			<li>
				<a href="#idTab5" class="reviews">
					{l s='Read reviews' mod='productcomments'} (<span itemprop="reviewCount">{$nbComments}</span>)
				</a>
			</li>
		{/if}
		{if ($too_early == false AND ($is_logged OR $allow_guests))}
			<li>
				<a class="open-comment-form" href="#new_comment_form">
					{l s='Write a review' mod='productcomments'}
				</a>
			</li>
		{/if}
	</ul>
</div>
{/if}
<!--  /Module ProductComments -->