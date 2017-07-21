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
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}>
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
		{if isset($meta_description) AND $meta_description}
			<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
		{/if}
		{if isset($meta_keywords) AND $meta_keywords}
			<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
		{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" href="{$css_dir}animate.css" type="text/css" />
		<link rel="stylesheet" href="{$css_dir}style.css" type="text/css" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		{if isset($css_files)}
			{foreach from=$css_files key=css_uri item=media}
				<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
			{/foreach}
		{/if}
		{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
			{$js_def}
			{foreach from=$js_files item=js_uri}
			<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
			{/foreach}
		{/if}
		<script src="{$js_dir}owl.carousel.js" type="text/javascript"></script>
		{$HOOK_HEADER}
		<link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" type="text/css" media="all" />
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body{if isset($page_name)} itemscope itemtype="http://schema.org/WebPage" id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} show-left-column{/if}{if $hide_right_column} hide-right-column{else} show-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">
	{if !isset($content_only) || !$content_only}
		{if isset($restricted_country_mode) && $restricted_country_mode}
			<div id="restricted-country">
				<p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
			</div>
		{/if}
		<div id="page"{if $page_name !="index"} class="sub-page"{/if}>
			<div class="header-container">
				<header id="header">
					{capture name='displayBanner'}{hook h='displayBanner'}{/capture}
					{if $smarty.capture.displayBanner}
						<div class="banner">
							<div class="container">
								<div class="row">
									{$smarty.capture.displayBanner}
								</div>
							</div>
						</div>
					{/if}
					{capture name='displayNav'}{hook h='displayNav'}{/capture}
					{if $smarty.capture.displayNav}
						<div class="nav">
							<div class="container">
								<nav>
									{$smarty.capture.displayNav}
									<ul class="link-nav">
										<li><a class="link-checkout" href="order">{l s='Checkout'}</a></li>
										<li><a class="link-wishlist wishlist_block" href="module/blockwishlist/mywishlist">{l s='My wishlist'}</a></li>
									</ul>
								</nav>
							</div>
						</div>
					{/if}
					<div class="header-middle">
						<div class="container">
							<div class="row">
								<div class="pos_logo col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
										<img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
									</a>
								</div>
								<div class="header-middle-right col-lg-9 col-md-9 col-sm-12 col-xs-12">
									<div class="cart-search">
										{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="menu-wapper">
						<div class="container">
							<div class="pos-megamenu">
								{capture name='megamenu'}{hook h='megamenu'}{/capture}
								{if $smarty.capture.megamenu}
								{$smarty.capture.megamenu}
								{/if}
							</div>
						</div>
					</div>
				</header>
				{if $page_name =="index"}
				<div class=" pos_bannerslide">
					{capture name='bannerSlide'}{hook h='bannerSlide'}{/capture}
					{if $smarty.capture.bannerSlide}
					{$smarty.capture.bannerSlide}
					{/if}
				</div>
				{capture name='blockPosition1'}{hook h='blockPosition1'}{/capture}
				{if $smarty.capture.blockPosition1}
				{$smarty.capture.blockPosition1}
				{/if}
				{capture name='blockPosition2'}{hook h='blockPosition2'}{/capture}
				{if $smarty.capture.blockPosition2}
				{$smarty.capture.blockPosition2}
				{/if}
				<div class="container">
					<div class="row">
						{capture name='blockPosition3'}{hook h='blockPosition3'}{/capture}
						{if $smarty.capture.blockPosition3}
						{$smarty.capture.blockPosition3}
						{/if}
					</div>
				</div>
				<div class="container">
					<div class="row">
						{capture name='blockPosition4'}{hook h='blockPosition4'}{/capture}
						{if $smarty.capture.blockPosition4}
						{$smarty.capture.blockPosition4}
						{/if}
					</div>
				</div>
				{capture name='blockPosition7'}{hook h='blockPosition7'}{/capture}
				{if $smarty.capture.blockPosition7}
				{$smarty.capture.blockPosition7}
				{/if}
				{capture name='blockPosition5'}{hook h='blockPosition5'}{/capture}
				{if $smarty.capture.blockPosition5}
				{$smarty.capture.blockPosition5}
				{/if}
				<!-- <div class="container">
					<div class="BrandSlider">
						{capture name='BrandSlider'}{hook h='BrandSlider'}{/capture}
						{if $smarty.capture.BrandSlider}
						{$smarty.capture.BrandSlider}
						{/if}
					</div>
				</div> -->
				{/if}
			</div>
			<div class="banner-breadcrumb">
				{if $page_name !="index"}
					<div class="breadcrumb-wapper">
						<div class="container">
							{if $page_name !='index' && $page_name !='pagenotfound'}
								{include file="$tpl_dir./breadcrumb.tpl"}
							{/if}
						</div>
					</div>
				{/if}
				{if $page_name =='category'}
					<div class="banner-category" >
						<div class="ct_img">
							{if $category->id_image}
							<img class="category_img img-responsive" src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}"/>
							{/if}
						</div>
					</div>
				{/if}
			</div>
			<div class="columns-container">
				<div id="columns" class="container">
					<div id="slider_row" class="row">
						{capture name='displayTopColumn'}{hook h='displayTopColumn'}{/capture}
						{if $smarty.capture.displayTopColumn}
							<div id="top_column" class="center_column col-xs-12 col-sm-12">{$smarty.capture.displayTopColumn}</div>
						{/if}
					</div>
					<div class="row">
						{if isset($left_column_size) && !empty($left_column_size)}
						<div id="left_column" class="column col-xs-12 col-sm-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
						{/if}
						{if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}
						<div id="center_column" class="center_column col-xs-12 col-sm-{$cols|intval}">
						
						{if $page_name =="index"}
							
							
						{/if}
	{/if}
