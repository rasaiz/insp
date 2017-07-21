<div class="tab-category-container listCategotyProducts pos-content">
	<div class="container-inner">
		<div class="tab-category">
		
			<div class="pos_container row">
		
			{foreach from=$productCates item=productCate name=posTabCategory}
				
				 <div id="tab_{$productCate.id}" class="list_categoryproducts col-md-3 col-sm-6 col-xs-12">
					<div class ="pos-title">
						<h2>
							<span>{$productCate.name}</span>
						</h2>
					</div>
					<div class="row_edit">
						<div class="productTabCategory_{$productCate.id}">
							{foreach from=$productCate.product item=product name=myLoop}
							{if $smarty.foreach.myLoop.index % $product_on_row == 0 || $smarty.foreach.myLoop.first }
							<div class="item  {if $smarty.foreach.myLoop.first}first_item{/if} {if $smarty.foreach.myLoop.iteration == $product_on_row}last_item{/if}">
							{/if}
								<div class="item-product">
									<div class="products-inner">
										<a class = "bigpic_{$product.id_product}_tabproduct tabproduct-img product_image" href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">
											<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}" alt="{$product.legend|escape:'html':'UTF-8'}" />
																	
										</a>
																
									</div>
									<div class="product-contents">
										<h5 class="product-name"><a class="product-name" href="{$product.link|escape:'html'}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h5>
										
										{capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$product}{/capture}
									<!-- 	{if $smarty.capture.displayProductListReviews}
											<div class="hook-reviews">
											{hook h='displayProductListReviews' product=$product}
											</div>
										{/if}  -->
											
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
										
									</div>
								</div>	
							{if $smarty.foreach.myLoop.iteration % $product_on_row == 0 || $smarty.foreach.myLoop.last }		
							</div>
							{/if}
							{/foreach}
						</div>
						<div class="boxprevnext">
							<a class="prev prev_{$productCate.id}"><i class="icon-chevron-left"></i></a>
							<a class="next next_{$productCate.id}"><i class="icon-chevron-right"></i></a>
						</div>
					</div>
					<script type="text/javascript"> 
						$(document).ready(function() {
							var owl = $(".productTabCategory_{$productCate.id}");
							owl.owlCarousel({
							items :1,
							slideSpeed: 1000,
							 pagination :false,
							itemsDesktop : [1199,1],
							itemsDesktopSmall : [991,1],
							itemsTablet: [767,1],
							itemsMobile : [480,1],
							afterAction: function(el){
							   this.$owlItems.removeClass('first-active')
							   this.$owlItems .eq(this.currentItem).addClass('first-active')  
							}
							});
							$(".next_{$productCate.id}").click(function(){
							owl.trigger('owl.next');
							})
							$(".prev_{$productCate.id}").click(function(){
							owl.trigger('owl.prev');
							})  
						});
					</script>
				</div>
				
			{/foreach}
			</div>
		
		</div>
	</div>
</div>

