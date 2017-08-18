<?php

class posspecialsproducts extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'posspecialsproducts';
		$this->tab = 'Modules';
		$this->version = '1.0';
		$this->author = 'posthemes';
		parent::__construct();
		
		$this->displayName = ('Specials products with slider ');
		$this->description = $this->l('Adds a block displaying your current discounted products');
	}

	function install()
	{
		if (!Configuration::updateValue('SPECIAL_PRODUCTS_NBR', 12) OR 
			!Configuration::updateValue('SPECIAL_PRODUCTS_ITEM', 1) OR 
			!Configuration::updateValue('SPECIAL_PRODUCTS_ROW', 1) OR 
			!parent::install() OR !$this->registerHook('posspecialsproducts') OR 
			!$this->registerHook('banner7Home') OR
			!$this->registerHook('displayRightColumn') OR
			!$this->registerHook('displayLeftColumn') OR 
			!$this->registerHook('blockPosition2') OR 
			!$this->registerHook('header'))
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitBlockViewed'))
		{
				Configuration::updateValue('SPECIAL_PRODUCTS_NBR', (int)Tools::getValue('SPECIAL_PRODUCTS_NBR'));
				Configuration::updateValue('SPECIAL_PRODUCTS_ITEM', (int)Tools::getValue('SPECIAL_PRODUCTS_ITEM'));
				Configuration::updateValue('SPECIAL_PRODUCTS_ROW', (int)Tools::getValue('SPECIAL_PRODUCTS_ROW'));
				$output .= $this->displayConfirmation($this->l('Settings updated.'));
		}
		return $output.$this->renderForm();
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Products to display'),
						'name' => 'SPECIAL_PRODUCTS_NBR',
						'class' => 'fixed-width-xs',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Column'),
						'name' => 'SPECIAL_PRODUCTS_ITEM',
						'class' => 'fixed-width-xs',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Row'),
						'name' => 'SPECIAL_PRODUCTS_ROW',
						'class' => 'fixed-width-xs',
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
			
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBlockViewed';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	function hookposspecialsproducts($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);		
		
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts.tpl');
	}
	function hookbanner7Home($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);		
		
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts_home.tpl');
	}
	function hookdisplayRightColumn($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);				
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts.tpl');
	}
	function hookdisplayLeftColumn($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);		
		
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts.tpl');
	}
	function hookblockPosition1($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);		
		
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts.tpl');
	}
	
	function hookblockPosition2($params)
	{
	global $smarty;
    global $cookie;
        $category = new Category(1);
        $nb = (int)Configuration::get('SPECIAL_PRODUCTS_NBR');
        $item = (int)Configuration::get('SPECIAL_PRODUCTS_ITEM');
        $row = (int)Configuration::get('SPECIAL_PRODUCTS_ROW');
        $products = Product::getPricesDrop((int)$cookie->id_lang, 0, ((int)$nb ? $nb : 4), false);		
		
		$smarty->assign(array(
			'allow_buy_when_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK', false),
			'max_quantity_to_allow_display' => Configuration::get('PS_LAST_QTIES'),
			'category' => $category,
			'products' => $products,
			'item' => $item,
			'row' => $row,
			'currency' => new Currency(intval($params['cart']->id_currency)),
			'lang' => Language::getIsoById(intval($params['cookie']->id_lang)),
			'productNumber' => sizeof($products),
			'homeSize' => Image::getSize('home')
		));
		return $this->display(__FILE__, 'posspecialsproducts.tpl');
	}
	
	public function getConfigFieldsValues()
	{		
		return array(
			'SPECIAL_PRODUCTS_NBR' => Tools::getValue('SPECIAL_PRODUCTS_NBR', Configuration::get('SPECIAL_PRODUCTS_NBR')),
			'SPECIAL_PRODUCTS_ITEM' => Tools::getValue('SPECIAL_PRODUCTS_ITEM', Configuration::get('SPECIAL_PRODUCTS_ITEM')),
			'SPECIAL_PRODUCTS_ROW' => Tools::getValue('SPECIAL_PRODUCTS_ROW', Configuration::get('SPECIAL_PRODUCTS_ROW')),
		);
	}
	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'posspecialsproducts.css', 'all');

	}
}
