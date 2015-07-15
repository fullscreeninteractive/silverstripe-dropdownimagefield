<?php

class DropdownImageField extends FormField {

	/**
	 * @var boolean $source Associative or numeric array of all dropdown items,
	 * with array key as the submitted field value, and the array value as a
	 * natural language description shown in the interface element.
	 */
	protected $sourceObject;
        
	/**
	 * @ignore
	 */
	protected $keyField, $labelField, $imageField;
        
	/**
	 * @var boolean $isSelected Determines if the field was selected
	 * at the time it was rendered, so if {@link $value} matches on of the array
	 * values specified in {@link $source}
	 */
	protected $isSelected;
	
	/**
	 * @var boolean $hasEmptyDefault Show the first <option> element as
	 * empty (not having a value), with an optional label defined through
	 * {@link $emptyString}. By default, the <select> element will be
	 * rendered with the first option from {@link $source} selected.
	 */
	protected $hasEmptyDefault = false;
	
	/**
	 * @var string $emptyString The title shown for an empty default selection,
	 * e.g. "Select...".
	 */
	protected $emptyString = '';
	
	/**
	 * @var array $disabledItems The keys for items that should be disabled (greyed out) in the dropdown
	 */
	protected $disabledItems = array();
	
	public function __construct($name, $title=null, $sourceObject='Group', $keyField = 'ID', $labelField = null, $imageField = 'Image', $value='', $form=null, $emptyString=null) {
		$this->setSourceObject($sourceObject);
                
		$this->keyField     = $keyField;
		$this->labelField   = $labelField;
                $this->imageField   = $imageField;

		if($emptyString === true) {
			Deprecation::notice('3.1',
				'Please use setHasEmptyDefault(true) instead of passing a boolean true $emptyString argument',
				Deprecation::SCOPE_GLOBAL);
		}
		if(is_string($emptyString)) {
			Deprecation::notice('3.1', 'Please use setEmptyString() instead of passing a string emptyString argument.',
				Deprecation::SCOPE_GLOBAL);
		}

		if($emptyString) $this->setHasEmptyDefault(true);
		if(is_string($emptyString)) $this->setEmptyString($emptyString);

		parent::__construct($name, ($title===null) ? $name : $title, $value, $form);
                
                $this->addExtraClass('dropdown');
	}
	
	public function Field($properties = array()) {
                $dirName = basename(dirname(dirname(__FILE__)));;
                
		Requirements::javascript($dirName.'/javascript/ImageSelect.jquery.js');
                
		$source = $this->getSourceObject();
		$options = array();
		if($source) {
			// SQLMap needs this to add an empty value to the options
			if(is_object($source) && $this->emptyString) {
				$options[] = new ArrayData(array(
					'Value' => '',
					'Title' => $this->emptyString,
                                        'Image' => ''
				));
			}

			foreach($source as $item) {
                                $value = $item->{$this->keyField};
                                $title = $item->{$this->labelField};
                                $image = $item->{$this->imageField}();
                                
				$selected = false;
				if($value === '' && ($this->value === '' || $this->value === null)) {
					$selected = true;
				} else {
					// check against value, fallback to a type check comparison when !value
					if($value) {
						$selected = ($value == $this->value);
					} else {
						$selected = ($value === $this->value) || (((string) $value) === ((string) $this->value));
					}

					$this->isSelected = $selected;
				}
				
				$disabled = false;
				if(in_array($value, $this->disabledItems) && $title != $this->emptyString ){
					$disabled = 'disabled';
				}

				$options[] = new ArrayData(array(
					'Title' => $title,
					'Value' => $value,
                                        'Image' => $image,
					'Selected' => $selected,
					'Disabled' => $disabled,
				));
			}
		}

		$properties = array_merge($properties, array('Options' => new ArrayList($options)));

		return parent::Field($properties);
	}
	
	/**
	 * Mark certain elements as disabled,
	 * regardless of the {@link setDisabled()} settings.
	 * 
	 * @param array $items Collection of array keys, as defined in the $source array
	 */
	public function setDisabledItems($items){
		$this->disabledItems = $items;
		return $this;
	}
	
	/**
	 * @return Array
	 */
	public function getDisabledItems(){
		return $this->disabledItems;
	}

	public function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array('type' => null, 'value' => null)
		);
	}

	/**
	 * @return boolean
	 */
	public function isSelected() {
		return $this->isSelected;
	}

	/**
	 * Gets the source array including any empty default values.
	 * 
	 * @return array
	 */
	public function getSourceObject() {
		if(is_array($this->sourceObject) && $this->getHasEmptyDefault()) {
                        return ArrayList::create()->unshift(array($this->keyField = '', $this->labelField = $this->emptyString, $this->imageField = ''));
		} else {
			return $this->sourceObject;
		}
	}

	/**
	 * @param array $source
	 */
	public function setSourceObject($source) {
		$this->sourceObject = $source;
		return $this;
	}
	
	/**
	 * @param boolean $bool
	 */
	public function setHasEmptyDefault($bool) {
		$this->hasEmptyDefault = $bool;
		return $this;
	}
	
	/**
	 * @return boolean
	 */
	public function getHasEmptyDefault() {
		return $this->hasEmptyDefault;
	}

	/**
	 * Set the default selection label, e.g. "select...".
	 * Defaults to an empty string. Automatically sets
	 * {@link $hasEmptyDefault} to true.
	 *
	 * @param string $str
	 */
	public function setEmptyString($str) {
		$this->setHasEmptyDefault(true);
		$this->emptyString = $str;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmptyString() {
		return $this->emptyString;
	}

	public function performReadonlyTransformation() {
		$field = $this->castedCopy('LookupField');
		$field->setSourceObject($this->getSourceObject());
		$field->setReadonly(true);
		
		return $field;
	}
}
