<script type="text/javascript">

$(document).ready(function() {

	$(".tab_category").hide();
	$(".tab_category:first").show(); 

	$("ul.tab_cates li").click(function() {
		$("ul.tab_cates li").removeClass("active");
		$(this).addClass("active");
		$(".tab_category").hide();
		$(".tab_category").removeClass("animate1 {$tab_effect}");
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab) .addClass("animate1 {$tab_effect}");
		$("#"+activeTab).fadeIn(); 
	});
});

</script>

<div class="tab-category-container-slider">
	<div class="container-tab">
		<div class="container-inner">
			<div class="tab-category">	
				<div class="pos_tab">
				
					<div class ='pos-title'>
							
						<h2><span>{l s='all Products' mod='postabcateslider1'}</span></h2>
						
					</div> 
					<ul class="tab_cates"> 
						{$count=0}
						{foreach from=$productCates item=productCate name=posTabCategory}
								<li data-title="tabtitle_{$productCate.id}" rel="tab_{$productCate.id}" {if $count==0} class="active"  {/if} > {$productCate.name}</li>
								{$count= $count+1}
						{/foreach}	
					</ul>

				</div>
				<div class="row  pos-content">
				
					<div class="tab1_container"> 
					{foreach from=$productCates item=productCate name=posTabCategory}
						
						<div id="tab_{$productCate.id}" class="tab_category">

							<div>
								<div class="productTabCategorySlider1">
									{foreach from=$productCate.product item=product name=myLoop}
										{if $smarty.foreach.myLoop.index % 1 == 0 || $smarty.foreach.myLoop.first }
										<div class="cate_item">
										{/if}
											<div class="item-product">
												<div class="products-inner">
													<a class ="bigpic_{$product.id_product}_tabcategory product_image" href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">
														<img class="img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html'}" alt="{$product.name|escape:html:'UTF-8'}" />								
													</a>
													{if isset($product.new) && $product.new == 1}
														<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
															<span class="new-label">{l s='New' mod='postabcateslider1'}</span>
														</a>
													{/if}
													{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
														<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
															<span class="sale-label">{l s='Sale!' mod='postabcateslider1'}</span>
														</a>
													{/if}
													{capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$product}{/capture}
													{if $smarty.capture.displayProductListReviews}
														<div class="hook-reviews">
														{hook h='displayProductListReviews' product=$product}
														</div>
													{/if}
													<div class="actions">
																
														<div class="actions-inner">
															
															<ul class="add-to-links">

																<li>
																	{hook h='displayProductListFunctionalButtons' product=$product}
																</li>
																<li>
																{if isset($comparator_max_item) && $comparator_max_item}
																  <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to Compare' mod='postabcateslider1'}">{l s='Compare' mod='postabcateslider1'}
																
																  </a>
																 {/if}
																</li>
																<li>
																	{if isset($quick_view) && $quick_view}
																		<a class="quick-view" title="{l s='Quick view' mod='postabcateslider1'}" href="{$product.link|escape:'html':'UTF-8'}">

																		</a>
																	{/if}
																</li>
															</ul>
														</div>
													</div>	
											
												</div>
												<div class="product-contents">

													<h5 class="product-name"><a href="{$product.link|escape:'html'}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></h5>

													{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
													<div class="price-box">
													{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
														{hook h="displayProductPriceBlock" product=$product type='before_price'}
														<span class="price product-price">
															{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
														</span>
														{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
															{hook h="displayProductPriceBlock" product=$product type="old_price"}
															<span class="old-price product-price">
																{displayWtPrice p=$product.price_without_reduction}
															</span>
															{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
															{if $product.specific_prices.reduction_type == 'percentage'}
																<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
															{/if}
														{/if}
														{hook h="displayProductPriceBlock" product=$product type="price"}
														{hook h="displayProductPriceBlock" product=$product type="unit_price"}
														{hook h="displayProductPriceBlock" product=$product type='after_price'}
													{/if}
													</div>
													{/if}
													<div class="cart">
														{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
														{if ($product.allow_oosp || $product.quantity > 0)}
														{if isset($static_token)}
															<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow"  title="{l s='Add to cart' mod='postabcateslider1'}" data-id-product="{$product.id_product|intval}">
																<span>{l s='Add to cart' mod='postabcateslider1'}</span>
																
															</a>
														{else}
														<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='postabcateslider1'}" data-id-product="{$product.id_product|intval}">
															<span>{l s='Add to cart' mod='postabcateslider1'}</span>
														</a>
														   {/if}      
														{else}
														<span class="button ajax_add_to_cart_button btn btn-default disabled" >
															<span>{l s='Add to cart' mod='postabcateslider1'}</span>
														</span>
														{/if}
														{/if}
													</div>
												</div>
										
											</div>
										{if $smarty.foreach.myLoop.iteration % 1 == 0 || $smarty.foreach.myLoop.last  }				
										</div>
										{/if}	
									{/foreach}
								</div>
							
							</div>
						
						</div>
								
					{/foreach}	
					 </div> <!-- .tab_container -->
					<div class="boxprevnext">
						<a class="prev prevtabcate1"><i class="icon-angle-left"></i></a>
						<a class="next nexttabcate1"><i class="icon-angle-right"></i></a>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript"> 
			$(document).ready(function() {
				var owl = $(".productTabCategorySlider1");
				owl.owlCarousel({
				items :4,
				slideSpeed: 1000,
				pagination : false,
				itemsDesktop : [1199,4],
				itemsDesktopSmall : [991,3],
				itemsTablet: [767,2],
				itemsMobile : [480,1]
				});
				$(".nexttabcate1").click(function(){
				owl.trigger('owl.next');
				})
				$(".prevtabcate1").click(function(){
				owl.trigger('owl.prev');
				})  
			});
		</script>
