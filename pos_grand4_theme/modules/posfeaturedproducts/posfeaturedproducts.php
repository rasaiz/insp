<?php
class posfeaturedproducts extends Module {
	private $token = '';
	private $_html = '';
	public static $sort_by = array(
        1 => array('id' =>1 , 'name' => 'Product Name'),
        2 => array('id' =>2 , 'name' => 'Price'),
        3 => array('id' =>3 , 'name' => 'Product ID'),       
        4 => array('id' =>4 , 'name' => 'Position'),
        5 => array('id' =>5 , 'name' => 'Date updated'),
        6 => array('id' =>6 , 'name' => 'Date added'),
        7 => array('id' =>7 , 'name' => 'Random'),
    );

    public static $order_by = array(
        1 => array('id' =>1 , 'name' => 'Descending'),
        2 => array('id' =>2 , 'name' => 'Ascending'),
    );
	public function __construct() {
		$this->name 		= 'posfeaturedproducts';
		$this->tab 			= 'front_office_features';
		$this->version 		= '2.0';
		$this->author 		= 'posthemes';
		$this->bootstrap = true;
		$this->_html        = '';
		$this->displayName 	= $this->l('Pos Featured Products module');
		$this->description 	= $this->l('Show featured products on homepage.');
		parent :: __construct();
       
	}
	
	public function install() {
        Configuration::updateValue($this->name . '_limit', 20);
        Configuration::updateValue($this->name . '_row', 3);
        Configuration::updateValue($this->name . '_items', 3);
		Configuration::updateValue($this->name . '_speed', 1000);
        Configuration::updateValue($this->name . '_auto', 0);
		Configuration::updateValue($this->name . '_pause', 3000);
        Configuration::updateValue($this->name . '_arrow', 1);
        Configuration::updateValue($this->name . '_pagi', 0);
        Configuration::updateValue($this->name . '_per_md', 3);
        Configuration::updateValue($this->name . '_per_sm', 2);
        Configuration::updateValue($this->name . '_per_xs', 2);
        Configuration::updateValue($this->name . '_per_xxs', 1);
        Configuration::updateValue($this->name . '_sort', 7);
        Configuration::updateValue($this->name . '_order', 1);
        
		
		return parent :: install()
			&& $this->registerHook('blockPosition1')
			&& $this->registerHook('blockPosition2')
			&& $this->registerHook('blockPosition3')
			&& $this->registerHook('blockPosition5')
			&& $this->registerHook('top')
			&& $this->registerHook('header')
			&& $this->installFixtures();
	}
	protected function installFixtures()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang){
			$this->installFixture((int)$lang['id_lang'], 'cms.jpg');
		}

		return true;
	}

	protected function installFixture($id_lang, $image = null)
	{	
		$values['posfeaturedproducts_img'][(int)$id_lang] = $image;
		$values['posfeaturedproducts_link'][(int)$id_lang] = '#';
		$values['posfeaturedproducts_title'][(int)$id_lang] = 'Featured products';
		Configuration::updateValue($this->name . '_title', $values['posfeaturedproducts_title']);
		Configuration::updateValue($this->name . '_img', $values['posfeaturedproducts_img']);
		Configuration::updateValue($this->name . '_link', $values['posfeaturedproducts_link']);

	}
	
    public function uninstall() {
        $this->_clearCache('posfeaturedproducts.tpl');

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
        Configuration::deleteByName($this->name . '_per_xs');
        Configuration::deleteByName($this->name . '_sort');
        Configuration::deleteByName($this->name . '_order');
        Configuration::deleteByName($this->name . '_title');
		Configuration::deleteByName($this->name . '_img');
		
        return parent::uninstall();
    }

  
	public function psversion() {
		$version=_PS_VERSION_;
		$exp=$explode=explode(".",$version);
		return $exp[1];
	}
	
    private function postProcess() {
		if (Tools::isSubmit('submitposfeaturedproducts'))
		{
			if($this->_postValidation()){
				$languages = Language::getLanguages(false);
				$values = array();
				$update_images_values = false;
		        
				
				foreach ($languages as $lang){
					if (isset($_FILES['posfp_img_'.$lang['id_lang']])
					&& isset($_FILES['posfp_img_'.$lang['id_lang']]['tmp_name'])
					&& !empty($_FILES['posfp_img_'.$lang['id_lang']]['tmp_name']))
					{
						if ($error = ImageManager::validateUpload($_FILES['posfp_img_'.$lang['id_lang']], 4000000))
							return $error;
						else
						{
							$ext = substr($_FILES['posfp_img_'.$lang['id_lang']]['name'], strrpos($_FILES['posfp_img_'.$lang['id_lang']]['name'], '.') + 1);
							$file_name = md5($_FILES['posfp_img_'.$lang['id_lang']]['name']).'.'.$ext;

							if (!move_uploaded_file($_FILES['posfp_img_'.$lang['id_lang']]['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file_name))
								return $this->displayError($this->l('An error occurred while attempting to upload the file.'));
							else
							{
								if (Configuration::hasContext('posfp_img', $lang['id_lang'], Shop::getContext())
									&& Configuration::get('posfp_img', $lang['id_lang']) != $file_name)
									@unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.Configuration::get('posfp_img', $lang['id_lang']));

								$values[$this->name . '_img'][$lang['id_lang']] = $file_name;
								
							}
						}

						$update_images_values = true;
					}
					$values[$this->name . '_link'][$lang['id_lang']] = Tools::getValue('posfp_link_'.$lang['id_lang']);
					$values[$this->name . '_title'][$lang['id_lang']] = Tools::getValue('posfp_title_'.$lang['id_lang']);
				}
				if ($update_images_values)
				Configuration::updateValue($this->name . '_img', $values[$this->name . '_img']);
				Configuration::updateValue($this->name . '_link', $values[$this->name . '_link']);
				Configuration::updateValue($this->name . '_title', $values[$this->name . '_title']);

				Configuration::updateValue($this->name . '_row', Tools::getValue('posfp_row'));
				Configuration::updateValue($this->name . '_items', Tools::getValue('posfp_items'));
				Configuration::updateValue($this->name . '_speed', Tools::getValue('posfp_speed'));
				Configuration::updateValue($this->name . '_auto', Tools::getValue('posfp_auto'));
				Configuration::updateValue($this->name . '_pause', Tools::getValue('posfp_pause'));
				Configuration::updateValue($this->name . '_arrow', Tools::getValue('posfp_arrow'));
				Configuration::updateValue($this->name . '_pagi', Tools::getValue('posfp_pagi'));
				Configuration::updateValue($this->name . '_move', Tools::getValue('posfp_move'));
				Configuration::updateValue($this->name . '_pausehover', Tools::getValue('posfp_pausehover'));
				Configuration::updateValue($this->name . '_limit', Tools::getValue('posfp_limit'));
				Configuration::updateValue($this->name . '_sort', Tools::getValue('posfp_sort'));
				Configuration::updateValue($this->name . '_order', Tools::getValue('posfp_order'));
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
		if (Tools::isSubmit('submitposfeaturedproducts'))
		{

			if (!Validate::isInt(Tools::getValue('posfp_row')) || !Validate::isInt(Tools::getValue('posfp_items')) ||
				!Validate::isInt(Tools::getValue('posfp_speed')) || !Validate::isInt(Tools::getValue('posfp_pause')) || !Validate::isInt(Tools::getValue('posfp_limit'))
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
						array(
							'type' => 'text',
							'lang' => true,
							'label' => $this->l('Module title'),
							'name' => 'posfp_title',
							'desc' => $this->l('This title will be displayed on front-office.')
						),
						// array(
							// 'type' => 'file_lang',
							// 'label' => $this->l('Banner image'),
							// 'name' => 'posfp_img',
							// 'desc' => $this->l('Upload an image for your banner. The recommended dimensions are 875 x 542px.'),
							// 'lang' => true,
						// ),
						// array(
							// 'type' => 'text',
							// 'lang' => true,
							// 'label' => $this->l('Banner Link'),
							// 'name' => 'posfp_link',
							// 'desc' => $this->l('Enter the link associated to your banner. When clicking on the banner, the link opens in the same window.')
						// ),
						array(
		                    'type' => 'select',
		                    'label' => $this->l('Sort by:'),
		                    'name' => 'posfp_sort',
		                    'options' => array(
		                        'query' => self::$sort_by,
		                        'id' => 'id',
		                        'name' => 'name',
		                    ),
		                    'validation' => 'isUnsignedInt',
		                ),
		                array(
		                    'type' => 'select',
		                    'label' => $this->l('Order by:'),
		                    'name' => 'posfp_order',
		                    'options' => array(
		                        'query' => self::$order_by,
		                        'id' => 'id',
		                        'name' => 'name',
		                    ),
		                    'validation' => 'isUnsignedInt',
		                ), 
						array(
							'type' => 'text',
							'label' => $this->l('Products limit :'),
							'name' => 'posfp_limit',
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
							'name' => 'posfp_row',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('Number rows of module')
					),
					array(
							'type' => 'text',
							'label' => $this->l('Number of Items:'),
							'name' => 'posfp_items',
							'class' => 'fixed-width-sm',
							'desc' => $this->l('Show number of product visible.')
					),
					array(
							'type' => 'text',
							'label' => $this->l('Slide speed:'),
							'name' => 'posfp_speed',
							'class' => 'fixed-width-sm',
							'suffix' => 'milliseconds',
							'desc' => $this->l('')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Auto play'),
						'name' => 'posfp_auto',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Default is 1000ms'),
						'values' => array(
							array(
								'id' => 'posfp_auto_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posfp_auto_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
							'type' => 'text',
							'label' => $this->l('Time auto'),
							'name' => 'posfp_pause',
							'class' => 'fixed-width-sm',
							'suffix' => 'milliseconds',
							'desc' => $this->l('This field only is value when auto play function is enable. Default is 3000ms.')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Next/Back control:'),
						'name' => 'posfp_arrow',
						'class' => 'fixed-width-xs',
						'desc' => $this->l(''),
						'values' => array(
							array(
								'id' => 'posfp_arrow_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posfp_arrow_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show pagination control:'),
						'name' => 'posfp_pagi',
						'class' => 'fixed-width-xs',
						'desc' => $this->l(''),
						'values' => array(
							array(
								'id' => 'posfp_pagi_on',
								'value' => 1,
								'label' => $this->l('Enabled')
								),
							array(
								'id' => 'posfp_pagi_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Scroll number:'),
						'name' => 'posfp_move',
	                    'default_value' => 0,
						'values' => array(
							array(
								'id' => 'posfp_move_on',
								'value' => 1,
								'label' => $this->l('1 item')),
							array(
								'id' => 'posfp_move_off',
								'value' => 0,
								'label' => $this->l('All visible items')),
						),
	                    'validation' => 'isBool',
					),
					 array(
						'type' => 'switch',
						'label' => $this->l('Pause On Hover:'),
						'name' => 'posfp_pausehover',
	                    'default_value' => 1,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'posfp_pausehover_on',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'posfp_pausehover_off',
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
		$helper->submit_action = 'submitposfeaturedproducts';
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
			'posfp_row'        => Configuration::get($this->name . '_row'),
			'posfp_items'      => Configuration::get($this->name . '_items'),
			'posfp_speed'      => Configuration::get($this->name . '_speed'),
			'posfp_auto'       => Configuration::get($this->name . '_auto'),
			'posfp_pause'      => Configuration::get($this->name . '_pause'),
			'posfp_arrow'      => Configuration::get($this->name . '_arrow'),
			'posfp_pagi'       => Configuration::get($this->name . '_pagi'),
			'posfp_move'       => Configuration::get($this->name . '_move'),
			'posfp_pausehover' => Configuration::get($this->name . '_pausehover'),
			'posfp_sort'       => Configuration::get($this->name . '_sort'),
			'posfp_order'      => Configuration::get($this->name . '_order'),
			'posfp_limit'      => Configuration::get($this->name . '_limit'),

		);
		
		
		foreach ($languages as $lang)
		{	
			$fields['posfp_title'][$lang['id_lang']] = Tools::getValue('posfeaturedproducts_title_'.$lang['id_lang'], Configuration::get($this->name . '_title', $lang['id_lang']));
			$fields['posfp_img'][$lang['id_lang']] = Tools::getValue('posfeaturedproducts_img_'.$lang['id_lang'], Configuration::get($this->name . '_img', $lang['id_lang']));
			$fields['posfp_link'][$lang['id_lang']] = Tools::getValue('posfeaturedproducts_link_'.$lang['id_lang'], Configuration::get($this->name . '_link', $lang['id_lang']));
		}
		
		return $fields;
	}

	public function hookHeader($params){
		
		$this->context->controller->addCSS($this->_path.'/posfeaturedproducts.css', 'all');
    }
        
    // Hook Home
	
	public function hookblockPosition1($params) {
	        $nb = Configuration::get($this->name . '_limit');
	        $random = false;
	        $sortby = Configuration::get($this->name . '_sort');
	        switch($sortby)
	        {
	        	case 1:
                $sortby = 'name';
           		break;
           		case 2:
                $sortby = 'price';
           		break;
           		case 3:
                $sortby = 'id_product';
           		break;
           		case 4:
                $sortby = 'position';
           		break;
           		case 5:
                $sortby = 'date_upd';
           		break;
           		case 6:
                $sortby = 'date_add';
           		break;
           		case 7:
                $sortby = null;
                $random = true;
           		break;
	        }
	        $orderby = Configuration::get($this->name . '_order');
	        if($orderby == 1) {
	        	$orderby = 'DESC';
	        } else {
	        	$orderby = 'ASC';
	        };
	        
			$id_lang =(int) Context::getContext()->language->id;
			$id_shop = (int) Context::getContext()->shop->id;

			$category = new Category(Context::getContext()->shop->getCategory(), (int) Context::getContext()->language->id);

            $products = $category->getProducts((int) Context::getContext()->language->id, 1, ($nb ? $nb : 8), $sortby , $orderby ,false,true,$random,($nb ? $nb : 8));
			
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

			if ($imgname && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$imgname))
				$this->smarty->assign('banner_img', $this->context->link->protocol_content.Tools::getMediaServer($imgname).$this->_path.'img/'.$imgname);
			
            $this->smarty->assign(array(
				'products' => $products,
                'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				'title' => Configuration::get($this->name . '_title', $this->context->language->id),
				'image_link' => Configuration::get($this->name . '_link', $this->context->language->id),		
            ));
		return $this->display(__FILE__, 'posfeaturedproducts.tpl');
	}
	
	public function hookblockPosition2($params) {
	    return $this->hookblockPosition1($params);
	}
	
	public function hookblockPosition3($params) {
	    return $this->hookblockPosition1($params);
	}
	public function hookblockPosition5($params) {
	    return $this->hookblockPosition1($params);
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
                'id' => 'posfeaturedproducts_per_md',
                'label' => $this->l('Desktops (<1400 pixels)'),
            ),
            array(
                'id' => 'posfeaturedproducts_per_sm',
                'label' => $this->l('Tablets (<1024 pixels)'),
            ),
            array(
                'id' => 'posfeaturedproducts_per_xs',
                'label' => $this->l('Phones (>767 pixels)'),
            ),
            array(
                'id' => 'posfeaturedproducts_per_xxs',
                'label' => $this->l('Small phones (>479 pixels)'),
            ),
        );
    }
}