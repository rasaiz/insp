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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
					
				</div><!-- #columns -->

			</div><!-- .columns-container -->
			{if isset($HOOK_FOOTER)}
				<!-- Footer -->
				<!-- {if $page_name !="index"}
					<div class="BrandSlider">
						<div class="container">
							{capture name='BrandSlider'}{hook h='BrandSlider'}{/capture}
							{if $smarty.capture.BrandSlider}
							{$smarty.capture.BrandSlider}
							{/if}
						</div>
					</div>
				{/if} -->
				<div class="footer-container">
					<footer id="footer">
						<div class="container">
							<div class="footer-static-top">	
								<div class="row">
									<div class="col-sm-6 col-md-4 col-xs-12">
									{capture name='blockFooter1'}{hook h='blockFooter1'}{/capture}
									{if $smarty.capture.blockFooter1}
									{$smarty.capture.blockFooter1}
									{/if}
									</div>
									<div class="col-sm-6 col-md-2 col-xs-12">
									{capture name='blockFooter2'}{hook h='blockFooter2'}{/capture}
									{if $smarty.capture.blockFooter2}
									{$smarty.capture.blockFooter2}
									{/if}
									</div>
									<div class="col-sm-6 col-md-2 col-xs-12 clear">
									{capture name='blockFooter3'}{hook h='blockFooter3'}{/capture}
									{if $smarty.capture.blockFooter3}
									{$smarty.capture.blockFooter3}
									{/if}
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12">
										<div class="footer-block last">
											{capture name='blockFooter4'}{hook h='blockFooter4'}{/capture}
											{if $smarty.capture.blockFooter4}
											{$smarty.capture.blockFooter4}
											{/if}
										</div>
									</div>
								</div>
							</div>
						
						</div>
					</footer>
				</div>
				<div class="container">
					<div class="footer-bottom">
						<div class="row">
							{capture name='blockFooter5'}{hook h='blockFooter5'}{/capture}
							{if $smarty.capture.blockFooter5}
							{$smarty.capture.blockFooter5}
							{/if}

						</div>		
					</div>
					{capture name='posscroll'}{hook h='posscroll'}{/capture}
					{if $smarty.capture.posscroll}
					{$smarty.capture.posscroll}
					{/if}
				</div><!-- #footer -->
			{/if}
		</div><!-- #page -->
{/if}
{include file="$tpl_dir./global.tpl"}
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
	</body>
</html>
<script type="text/javascript">
 $(document).ready(function(){
 
  $(window).scroll(function() {    
   var scroll = $(window).scrollTop();
   if (scroll < 245) {
    $(".menu-wapper").removeClass("scroll-header");
   }else{
    $(".menu-wapper").addClass("scroll-header");
   }
  });

 });
</script>