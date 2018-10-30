<?php

namespace Copperis\DropdownImageField;

use SilverStripe\Forms\DropdownField;
use SilverStripe\View\Requirements;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Forms\FormField;

class DropdownImageField extends DropdownField {

    protected $keyField, $labelField, $imageField;

    public function __construct($name, $title, $sourceObject, $keyField = 'ID', $labelField = 'Title', $imageField = 'Image', $value = '', $form = null) {

        $this->keyField = $keyField;
        $this->labelField = $labelField;
        $this->imageField = $imageField;

        parent::__construct($name, ($title === null) ? $name : $title, $sourceObject, $value, $form);

        $this->addExtraClass('dropdown');
    }

    public function Field($properties = array()) {

        $dirName = basename(dirname(dirname(__FILE__)));
        ;

        Requirements::javascript('Copperis\DropdownImageField: javascript/Polyfill.js');
        Requirements::javascript('Copperis\DropdownImageField: javascript/ImageSelect.jquery.js');
        Requirements::css('Copperis\DropdownImageField: css/ImageSelect.css');

        $source = $this->getSource();
        $options = array();
        if ($source) {
            if (is_object($source) && $this->hasEmptyDefault) {
                $options[] = new ArrayData(array(
                    'Value' => '',
                    'Title' => $this->emptyString,
                    'Image' => ''
                ));
            }

            foreach ($source as $item) {
                $value = $item->{$this->keyField};
                if (empty($this->labelField)) {
                    $title = '--nbsp';
                } else {
                    $title = $item->{$this->labelField};
                }

                $image = $item->{$this->imageField}();

                $selected = false;
                if ($value === '' && ($this->value === '' || $this->value === null)) {
                    $selected = true;
                } else {
                    // check against value, fallback to a type check comparison when !value
                    if ($value) {
                        $selected = ($value == $this->value);
                    } else {
                        $selected = ($value === $this->value) || (((string) $value) === ((string) $this->value));
                    }

                    $this->isSelected = $selected;
                }

                $disabled = false;
                if (in_array($value, $this->disabledItems) && $title != $this->emptyString) {
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

        return FormField::Field($properties);
    }

    /**
     * Get the source of this field as an array
     * Transform the source DataList to an key => value array
     *
     * @return array
     */
    public function getSourceAsArray() {
        $source = $this->getSource();
        if (is_array($source)) {
            return $source;
        }

        return $source->map($this->keyField, $this->labelField)->toArray();
    }

}
