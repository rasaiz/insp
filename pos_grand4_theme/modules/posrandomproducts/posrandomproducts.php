<?php
if (!defined('_PS_VERSION_'))
	exit;

class PosRandomProducts extends Module {
	private $token = '';
	private $_html = '';
	protected static $cache_specials;

	public function __construct() {
		$this->name 		= 'posrandomproducts';
		$this->tab 			= 'front_office_features';
		$this->version 		= '2.0';
		$this->author 		= 'posthemes';
		$this->bootstrap = true;
		$this->_html        = '';
		$this->displayName 	= $this->l('Pos Random Products module');
		$this->description 	= $this->l('Show random products on homepage.');
		parent :: __construct();
       
	}
	
	public function install() {
        Configuration::updateValue($this->name . '_limit', 20);
        Configuration::updateValue($this->name . '_row', 1);
        Configuration::updateValue($this->name . '_items', 2);
		Configuration::updateValue($this->name . '_speed', 1000);
        Configuration::updateValue($this->name . '_auto', 0);
		Configuration::updateValue($this->name . '_pause', 3000);
        Configuration::updateValue($this->name . '_arrow', 1);
        Configuration::updateValue($this->name . '_pagi', 0);
        Configuration::updateValue($this->name . '_per_md', 2);
        Configuration::updateValue($this->name . '_per_sm', 3);
        Configuration::updateValue($this->name . '_per_xs', 2);
        Configuration::updateValue($this->name . '_per_xxs', 1);
        
		
		return parent :: install()
			&& $this->registerHook('blockPosition2')
			&& $this->registerHook('blockPosition3')
			&& $this->registerHook('addproduct')
			&& $this->registerHook('updateproduct')
			&& $this->registerHook('deleteproduct')
			&& $this->registerHook('top')
			&& $this->registerHook('header')
			&& $this->installFixtures();
	}
	protected function installFixtures()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang){
			$this->installFixture((int)$lang['id_lang'], 'cms.png');
		}

		return true;
	}

	protected function installFixture($id_lang, $image = null)
	{	

		$values['posrandomproducts_title'][(int)$id_lang] = 'Random products';
		Configuration::updateValue($this->name . '_title', $values['posrandomproducts_title']);

	}
	
    public function uninstall() {
        $this->_clearCache('*');

		Configuration::deleteByName($this->name . '_limit');
        Configuration::deleteByName($this->name . '_row');
        Configuration::deleteByName($this->name . '_items');
		Configuration::deleteByName($this->name . '_speed');
        Configuration::deleteByName($this->name . '_auto');
		Configuration::deleteByName($this->name . '_pause');
        Configuration::deleteByName($this->name . '_arrow');
        Configuration::deleteByName($this->name . '_pagi');
        Configuration::deleteByName($this->name . '_per_lg');
        Configuration::deleteByName($this->name . '_per_md');
        Configuration::deleteByName($this->name . '_per_sm');
        Configuration::deleteByName($this->name . '_per_xs');;
        Configuration::deleteByName($this->name . '_img');
        Configuration::deleteByName($this->name . '_link');
		
        return parent::uninstall();
    }

  
	public function psversion() {
		$version=_PS_VERSION_;
		$exp=$explode=explode(".",$version);
		return $exp[1];
	}
	
    private function postProcess() {
		if (Tools::isSubmit('submitposrandomproducts'))
		{
			if($this->_postValidation()){
				$languages = Language::getLanguages(false);
				$values = array();
		        
				
				foreach ($languages as $lang){
					$values[$this->name . '_title'][$lang['id_lang']] = Tools::getValue('posr_title_'.$lang['id_lang']);
				}
				Configuration::updateValue($this->name . '_title', $values[$this->name . '_title']);

				Configuration::updateValue($this->name . '_row', Tools::getValue('posr_row'));
				Configuration::updateValue($this->name . '_items', Tools::getValue('posr_items'));
				Configuration::updateValue($this->name . '_speed', Tools::getValue('posr_speed'));
				Configuration::updateValue($this->name . '_auto', Tools::getValue('posr_auto'));
				Configuration::updateValue($this->name . '_pause', Tools::getValue('posr_pause'));
				Configuration::updateValue($this->name . '_arrow', Tools::getValue('posr_arrow'));
				Configuration::updateValue($this->name . '_pagi', Tools::getValue('posr_pagi'));
				Configuration::updateValue($this->name . '_move', Tools::getValue('posr_move'));
				Configuration::updateValue($this->name . '_pausehover', Tools::getValue('posr_pausehover'));
				Configuration::updateValue($this->name . '_limit', Tools::getValue('posr_limit'));
				Configuration::updateValue($this->name . '_per_md', Tools::getValue($this->name . '_per_md'));
				Configuration::updateValue($this->name . '_per_sm', Tools::getValue($this->name . '_per_sm'));
				Configuration::updateValue($this->name . '_per_xs', Tools::getValue($this->name . '_per_xs'));
				Configuration::updateValue($this->name . '_per_xxs', Tools::getValue($this->name . '_per_xxs'));
				
				
				return $this->displayConfirmation($this->l('The settings have been updated.'));
			}else{
				return $this->_html;
			}
		}
		
		return '';
    }
	
	public function getContent()
	{		
		return $this->postProcess().$this->renderForm();
	}

	protected function _postValidation()
	{
		$errors = array();
		if (Tools::isSubmit('submitposrandomproducts'))
		{

			if (!Validate::isInt(Tools::getValue('posr_row')) || !Validate::isInt(Tools::getValue('posr_items')) ||
				!Validate::isInt(Tools::getValue('posr_speed')) || !Validate::isInt(Tools::getValue('posr_pause')) || !Validate::isInt(Tools::getValue('posr_limit'))
			)
				$errors[] = $this->l('Invalid values');
		} 
		/* Returns if validation is ok */
		if (count($errors))
		{
			$this->_html .= $this->displayError(implode('<br />', $errors));

			return false;
		}

		return true;
	}
	public function renderForm()
	{	
		
        $id_lang = (int) Context::getContext()->language->id;
        //echo '<pre>';print_r($test);die;
			$fields_form[0]['form'] = array(
				'legend' => array(
					'title' => $this->l('Module Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					/* 	array(
							'type' => 'text',
							'lang' => true,
							'label' => $this->l('Module title'),
							'name' => 'posr_title',
							'desc' => $this->l('This title will be displayed on front-office.')
						), */
						array(
							'type' => 'text',
							'label' => $this->l('Products limit :'),
							'name' => 'posr_limit',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('Set the number of products which you would like to see displayed in this module')
						),
						
						
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			);
			$fields_form[1]['form'] = array(
				'legend' => array(
					'title' => $this->l('Slider configurations'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
							'type' => 'text',
							'label' => $this->l('Rows'),
							'name' => 'posr_row',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('Number rows of module')
					),
					array(
							'type' => 'text',
							'label' => $this->l('Number of Items:'),
							'name' => 'posr_items',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('Show number of product visible.')
					),
					array(
							'type' => 'text',
							'label' => $this->l('Slide speed:'),
							'name' => 'posr_speed',
							'class' => 'fixed-width-sm',
							'suffix' => 'milliseconds',
							'desc' => $this->l('')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Auto play'),
						'name' => 'posr_auto',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Default is 1000ms'),
						'values' => array(
							array(
								'id' => 'posr_auto_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posr_auto_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
							'type' => 'text',
							'label' => $this->l('Time auto'),
							'name' => 'posr_pause',
							'class' => 'fixed-width-sm',
							'suffix' => 'milliseconds',
							'desc' => $this->l('This field only is value when auto play function is enable. Default is 3000ms.')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Next/Back control:'),
						'name' => 'posr_arrow',
						'class' => 'fixed-width-xs',
						'desc' => $this->l(''),
						'values' => array(
							array(
								'id' => 'posr_arrow_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posr_arrow_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show pagination control:'),
						'name' => 'posr_pagi',
						'class' => 'fixed-width-xs',
						'desc' => $this->l(''),
						'values' => array(
							array(
								'id' => 'posr_pagi_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posr_pagi_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Scroll number:'),
						'name' => 'posr_move',
	                    'default_value' => 0,
						'values' => array(
							array(
								'id' => 'posr_move_on',
								'value' => 1,
								'label' => $this->l('1 item')),
							array(
								'id' => 'posr_move_off',
								'value' => 0,
								'label' => $this->l('All visible items')),
						),
	                    'validation' => 'isBool',
					),
					 array(
						'type' => 'switch',
						'label' => $this->l('Pause On Hover:'),
						'name' => 'posr_pausehover',
	                    'default_value' => 1,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'posr_pausehover_on',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'posr_pausehover_off',
								'value' => 0,
								'label' => $this->l('No')),
						),
	                    'validation' => 'isBool',
					),
					 'pos_fp_pro' => array(
	                    'type' => 'html',
	                    'id' => 'pos_fp_pro',
	                    'label'=> $this->l('Responsive:'),
	                    'name' => '',
	                ),
					
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)		
			);
		$fields_form[1]['form']['input']['pos_fp_pro']['name'] = $this->BuildDropListGroup($this->findCateProPer());
		
		$helper = new HelperForm();
		$helper->show_toolbar = true;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->module = $this;
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitposrandomproducts';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$module = _PS_MODULE_DIR_ ;
		$helper->tpl_vars = array(
			'module' =>$module,
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);

		return $helper->generateForm($fields_form);
	}
	
	public function getConfigFieldsValues()
	{
		$languages = Language::getLanguages(false);
		$fields = array(
			'posr_row'        => Configuration::get($this->name . '_row'),
			'posr_items'      => Configuration::get($this->name . '_items'),
			'posr_speed'      => Configuration::get($this->name . '_speed'),
			'posr_auto'       => Configuration::get($this->name . '_auto'),
			'posr_pause'      => Configuration::get($this->name . '_pause'),
			'posr_arrow'      => Configuration::get($this->name . '_arrow'),
			'posr_pagi'       => Configuration::get($this->name . '_pagi'),
			'posr_move'       => Configuration::get($this->name . '_move'),
			'posr_pausehover' => Configuration::get($this->name . '_pausehover'),
			'posr_limit'      => Configuration::get($this->name . '_limit'),

		);
		
		
		foreach ($languages as $lang)
		{	
			$fields['posr_title'][$lang['id_lang']] = Tools::getValue('posrandomproducts_title_'.$lang['id_lang'], Configuration::get($this->name . '_title', $lang['id_lang']));
		}
		
		return $fields;
	}

	public function hookHeader($params){
		$this->context->controller->addCSS($this->_path.'/posrandomproducts.css', 'all');
    }
        
    // Hook Home
	
	public function hookblockPosition2($params) {
        $nb = Configuration::get($this->name . '_limit');
        
		$id_lang =(int) Context::getContext()->language->id;
		$id_shop = (int) Context::getContext()->shop->id;
		if (!$this->isCached('posrandomproducts.tpl', $this->getCacheId())) {
			posrandomproducts::$cache_specials = $this->getProducts((int) $this->context->language->id, 1, ($nb ? $nb : 8) );

			$slider_options = array(
					'rows' => (int)Configuration::get($this->name . '_row'),
					'number_item' => (int)Configuration::get($this->name . '_items'),
					'speed_slide' => (int)Configuration::get($this->name . '_speed'),
					'auto_play' => (int)Configuration::get($this->name . '_auto'),
					'auto_time' => (int)Configuration::get($this->name . '_pause'),
					'show_arrow' => (int)Configuration::get($this->name . '_arrow'),
					'show_pagination' => (int)Configuration::get($this->name . '_pagi'),
					'move' => (int)Configuration::get($this->name . '_move'),
					'pausehover' => (int)Configuration::get($this->name . '_pausehover'),
					'items_md' => (int)Configuration::get($this->name . '_per_md'),	
					'items_sm' => (int)Configuration::get($this->name . '_per_sm'),	
					'items_xs' => (int)Configuration::get($this->name . '_per_xs'),	
					'items_xxs' => (int)Configuration::get($this->name . '_per_xxs'),		
				);

			$this->context->smarty->assign('slider_options', $slider_options);
			$imgname = Configuration::get($this->name . '_img', $this->context->language->id);

            $this->smarty->assign(array(
				'products' => posrandomproducts::$cache_specials,
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				'title' => Configuration::get($this->name . '_title', $this->context->language->id),				
            ));
            }
        if (posrandomproducts::$cache_specials === false)
				return false;
        
		return $this->display(__FILE__, 'posrandomproducts.tpl', $this->getCacheId());
	}
	
	public function hookblockPosition3($params) {
        $nb = Configuration::get($this->name . '_limit');
        
		$id_lang =(int) Context::getContext()->language->id;
		$id_shop = (int) Context::getContext()->shop->id;
		if (!$this->isCached('posrandomproducts.tpl', $this->getCacheId())) {
			posrandomproducts::$cache_specials = $this->getProducts((int) $this->context->language->id, 1, ($nb ? $nb : 8) );

			$slider_options = array(
					'rows' => (int)Configuration::get($this->name . '_row'),
					'number_item' => (int)Configuration::get($this->name . '_items'),
					'speed_slide' => (int)Configuration::get($this->name . '_speed'),
					'auto_play' => (int)Configuration::get($this->name . '_auto'),
					'auto_time' => (int)Configuration::get($this->name . '_pause'),
					'show_arrow' => (int)Configuration::get($this->name . '_arrow'),
					'show_pagination' => (int)Configuration::get($this->name . '_pagi'),
					'move' => (int)Configuration::get($this->name . '_move'),
					'pausehover' => (int)Configuration::get($this->name . '_pausehover'),
					'items_md' => (int)Configuration::get($this->name . '_per_md'),	
					'items_sm' => (int)Configuration::get($this->name . '_per_sm'),	
					'items_xs' => (int)Configuration::get($this->name . '_per_xs'),	
					'items_xxs' => (int)Configuration::get($this->name . '_per_xxs'),		
				);

			$this->context->smarty->assign('slider_options', $slider_options);
			$imgname = Configuration::get($this->name . '_img', $this->context->language->id);

            $this->smarty->assign(array(
				'products' => posrandomproducts::$cache_specials,
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				'title' => Configuration::get($this->name . '_title', $this->context->language->id),				
            ));
            }
        if (posrandomproducts::$cache_specials === false)
				return false;
        
		return $this->display(__FILE__, 'posrandomproducts.tpl', $this->getCacheId());
	}
	public function hookAddProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('*');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('*');
	}

	public function BuildDropListGroup($group)
    {
        if(!is_array($group) || !count($group))
            return false;

        $html = '<div class="row">';
        foreach($group AS $key => $k)
        {
             if($key==4)
                 $html .= '</div><div class="row">';

             $html .= '<div class="col-xs-4 col-sm-3">'.$k['label'].'</label>'.
             '<select name="'.$k['id'].'" 
             id="'.$k['id'].'" 
             class="'.(isset($k['class']) ? $k['class'] : 'fixed-width-md').'"'.
             (isset($k['onchange']) ? ' onchange="'.$k['onchange'].'"':'').' >';
            
            for ($i=1; $i < 7; $i++){
                $html .= '<option value="'.$i.'" '.(Configuration::get($k['id']) == $i ? ' selected="selected"':'').'>'.$i.'</option>';
            }
                                
            $html .= '</select></div>';
        }

        return $html.'</div>';
    }
    public function findCateProPer()
    {
        return array(
            array(
                'id' => 'posrandomproducts_per_md',
                'label' => $this->l('Desktops (>991 pixels)'),
            ),
            array(
                'id' => 'posrandomproducts_per_sm',
                'label' => $this->l('Tablets (>767 pixels)'),
            ),
            array(
                'id' => 'posrandomproducts_per_xs',
                'label' => $this->l('Phones (>480 pixels)'),
            ),
            array(
                'id' => 'posrandomproducts_per_xxs',
                'label' => $this->l('Small phones (>320 pixels)'),
            ),
        );
    }
    protected function getCacheId($name = null)
	{
		if ($name === null)
		$name = 'posrandomproducts';
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

	public function _clearCache($template, $cache_id = null, $compile_id = null)
	{
		parent::_clearCache('posrandomproducts.tpl');
	}

	public static function getProducts($id_lang, $start, $limit, $id_category = false,
        $only_active = false, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql = 'SELECT p.*, product_shop.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
			pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name 
				FROM `'._DB_PREFIX_.'product` p
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
					ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (
					product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (p.`id_manufacturer`= m.`id_manufacturer`)
				'.Product::sqlStock('p', 0).'
                WHERE product_shop.`active` = 1 AND product_shop.`visibility` != \'none\'
				ORDER BY RAND()';

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($rq as &$row) {
            $row = Product::getTaxesInformations($row);
        }

        return Product::getProductsProperties((int)$id_lang, $rq);
    }
}