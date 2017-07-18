
	<div class="block-smartblog">
		<div class="pos-title">
		<h2><a href="{smartblog::GetSmartBlogLink('smartblog')}"><span>{l s='latest blog' mod='smartbloghomelatestnews'}</span></a></h2>
		</div>
		<div class="row pos-content">
			<div class="sdsblog-box-content">
				{if isset($view_data) AND !empty($view_data)}
					{assign var='i' value=1}
					{foreach from=$view_data item=post name=post}
					   
							{assign var="options" value=null}
							{$options.id_post = $post.id}
							{$options.slug = $post.link_rewrite}
							<div class="sds_blog_post" >
								<div class="item-blog">
									<div class="news_module_image_holder">
										 <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"><img alt="{$post.title}" class="feat_img_small" src="{$modules_dir}smartblog/images/{$post.post_img}.jpg"></a>
									</div>
									<div class="blog_content">
									
										<h4 class="sds_post_title"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h4>
										<p>
											{$post.short_description|truncate:100:'...'|escape:'htmlall':'UTF-8'}
										</p>
										
										<span class="date-smart">{$post.date_added}</span>
										<a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"  class="r_more"><span>{l s='Read More' mod='smartbloghomelatestnews'}</span></a>
									</div>
								</div>
							</div>
						
						{$i=$i+1}
					{/foreach}
				{/if}
			</div>
				<div class="boxprevnext">
				<a class="prev prevblog"><i class="icon-angle-left"></i></a>
				<a class="next nextblog"><i class="icon-angle-right"></i></a>
			</div>	
		</div>	 
	</div>

<script>


    $(document).ready(function() {
     
    var owl = $(".sdsblog-box-content");
     
    owl.owlCarousel({
	autoPlay : false,
	 pagination :false,
    items : 3,
	slideSpeed: 1000,
	itemsDesktop : [1199,3],
	itemsDesktopSmall : [991,2],
	itemsTablet: [767,2],
	itemsMobile : [480,1]
    });
	// Custom Navigation Events
		$(".nextblog").click(function(){
		owl.trigger('owl.next');
		})
		$(".prevblog").click(function(){
		owl.trigger('owl.prev');
		})     
    });
</script>