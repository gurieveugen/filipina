<?php
/**
 * Themex Form
 *
 * Handles custom forms
 *
 * @class ThemexForm
 * @author Themex
 */
 
class ThemexForm {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//refresh data
		self::refresh();
		
		//add field action
		add_action('wp_ajax_themex_form_add', array(__CLASS__,'addField'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public
     * @return void
     */
	public static function refresh() {
		self::$data=(array)ThemexCore::getOption(__CLASS__);
	}
	
	/**
	 * Renders module settings
     *
     * @access public
     * @return string
     */
	public static function renderSettings($name) {
		
		$out='<input type="hidden" name="'.__CLASS__.'['.$name.']" value="" />';
		if(isset(self::$data[$name]) && is_array(self::$data[$name])) {
			foreach(self::$data[$name] as $ID=>$field) {
				$field['form']=$name;
				$field['id']=$ID;
				$out.=self::renderField($field);
			}
		} else {
			$out.=self::renderField(array(
				'id' => uniqid(),
				'name' => '',
				'type' => 'string',
				'form' => $name,
			));
		}
			
		return $out;
	}
	
	/**
	 * Saves module settings
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function saveSettings($data) {
		if(is_array($data)) {		
			foreach($data as $form) {
				if(is_array($form)) {
					foreach($form as $field) {
						$ID=themex_sanitize_key($field['name']);
						if(isset($field['name']) && !empty($field['name'])) {
							themex_add_string($ID, 'name', $field['name']);
						}
						
						if(isset($field['options']) && !empty($field['options'])) {
							themex_add_string($ID, 'options', $field['options']);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Renders module data
     *
     * @access public
     * @return string
     */
	public static function renderData($name, $options=array(), $values=array()) {
		$options=wp_parse_args($options, array(
			'edit' => false,
			'search' => false,
			'before_title' => '',
			'after_title' => '',
			'before_content' => '',
			'after_content' => '',			
		));
		
		$out='';
		if(isset(self::$data[$name]) && is_array(self::$data[$name])) {
			foreach(self::$data[$name] as $field) {
				if(!empty($field['name']) && (!$options['search'] || isset($field['search']))) {
					$ID=themex_sanitize_key($field['name']);
					$field['name']=themex_get_string($ID, 'name', $field['name']);
					$out.=$options['before_title'].$field['name'].$options['after_title'].$options['before_content'];					
					
					if($options['edit']) {
						$items=array('0' => '&ndash;');
						if($field['type']=='select') {
							$field['options']=themex_get_string($ID, 'options', $field['options']);
							$items=array_merge($items, explode(',', $field['options']));
							$out.='<div class="select-field"><span></span>';
						} else {
							$out.='<div class="field-wrap">';							
						}
					
						$out.=ThemexInterface::renderOption(array(
							'id' => $ID,
							'type' => $field['type'],
							'options' => $items,
							'value' => isset($values[$ID])?$values[$ID]:'',
							'wrap' => false,
						));			

						$out.='</div>';
					} else if(isset($values[$ID])) {
						if($field['type']=='select') {
							$field['options']=themex_get_string($ID, 'options', $field['options']);
							$items=array_merge(array('0' => '&ndash;'), explode(',', $field['options']));							
							if(isset($items[$values[$ID]])) {
								$values[$ID]=$items[$values[$ID]];
							}	
						}
						
						if(empty($values[$ID])) {
							$values[$ID]='&ndash;';
						}
					
						$out.=$values[$ID];
					}
					
					$out.=$options['after_content'];
				}			
			}
		}
		
		echo $out;		
	}
	
	/**
	 * Adds new field
     *
     * @access public
     * @return void
     */
	public static function addField() {
		$name=sanitize_text_field($_POST['value']);
		$out=self::renderField(array(
			'id' => uniqid(),
			'name' => '',
			'type' => 'string',
			'form' => $name,
		));
		
		echo $out;		
		die();
	}
	
	/**
	 * Renders field option
     *
     * @access public
	 * @param array $field
     * @return string
     */
	public static function renderField($field) {
		$out='<div class="themex-form-item themex-option" id="'.$field['form'].'_'.$field['id'].'">';
		$out.='<a class="themex-button themex-remove-button themex-trigger" data-action="themex_form_remove" data-element="'.$field['form'].'_'.$field['id'].'"></a>';
		$out.=ThemexInterface::renderOption(array(
			'id' => $field['form'].'_'.$field['id'].'_value',
			'type' => 'hidden',
			'value' => $field['form'],
			'wrap' => false,
			'after' => '<a class="themex-button themex-add-button themex-trigger" data-action="themex_form_add" data-element="'.$field['form'].'_'.$field['id'].'" data-value="'.$field['form'].'_'.$field['id'].'_value"></a>',				
		));
		
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].']['.$field['id'].'][name]',
			'type' => 'text',
			'attributes' => array('placeholder' => __('Name', 'lovestory')),
			'value' => isset(self::$data[$field['form']][$field['id']]['name'])?themex_stripslashes(self::$data[$field['form']][$field['id']]['name']):'',
			'wrap' => false,
		));
	
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].']['.$field['id'].'][type]',
			'type' => 'select',
			'options' => array(
				'text' => __('String', 'lovestory'),
				'number' => __('Number', 'lovestory'),
				'textarea' => __('Text', 'lovestory'),
				'select' => __('Select', 'lovestory'),
			),
			'value' => isset(self::$data[$field['form']][$field['id']]['type'])?self::$data[$field['form']][$field['id']]['type']:'',
			'wrap' => false,
		));
		
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].']['.$field['id'].'][options]',
			'type' => 'text',
			'attributes' => array('placeholder' => __('Options', 'lovestory')),
			'value' => isset(self::$data[$field['form']][$field['id']]['options'])?themex_stripslashes(self::$data[$field['form']][$field['id']]['options']):'',
			'wrap' => false,
		));
		
		$out.=ThemexInterface::renderOption(array(
			'id' => __CLASS__.'['.$field['form'].']['.$field['id'].'][search]',
			'name' => __('Searchable', 'lovestory'),
			'type' => 'checkbox',			
			'value' => isset(self::$data[$field['form']][$field['id']]['search'])?self::$data[$field['form']][$field['id']]['search']:'',
			'wrap' => false,
			'before' => '<div class="clear"></div>',
		));
		
		$out.='</div>';
		
		return $out;
	}
}