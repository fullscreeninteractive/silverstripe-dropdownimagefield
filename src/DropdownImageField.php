<?php

namespace FullscreenInteractive\DropdownImageField;

use SilverStripe\Forms\DropdownField;
use SilverStripe\View\Requirements;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\SS_List;
use SilverStripe\Forms\FormField;

class DropdownImageField extends DropdownField {

    protected $keyField = 'ID';

    protected $labelField = 'Title';

    protected $imageField = 'Image';

    protected $sourceObject;

    public function __construct($name, $title, $sourceObject, $keyField = 'ID', $labelField = 'Title', $imageField = 'Image', $value = '', $form = null) {

        $this->keyField = $keyField;
        $this->labelField = $labelField;
        $this->imageField = $imageField;

        parent::__construct($name, ($title === null) ? $name : $title, $sourceObject, $value, $form);

        $this->addExtraClass('dropdown');
        $this->sourceObject = $sourceObject;
    }

    public function setSourceObject(SS_List $source)
    {
        $this->sourceObject = $source;

        return $this;
    }

    public function setImageField($field)
    {
        $this->imageField = $field;

        return $this;
    }

    public function Field($properties = array()) {

        $dirName = basename(dirname(dirname(__FILE__)));
        $source = $this->sourceObject;
        $options = array();

        if ($source) {
            if (is_object($source) && $this->hasEmptyDefault) {
                $options[] = new ArrayData([
                    'Value' => '',
                    'Title' => $this->emptyString,
                    'Image' => ''
                ]);
            }

            foreach ($source as $k => $item) {
                if (is_object($item)) {
                    $value = $item->{$this->keyField};
                    if (empty($this->labelField)) {
                        $title = '--nbsp';
                    } else {
                        $title = $item->{$this->labelField};
                    }

                    $image = $item->{$this->imageField}();
                } else {
                    $value = $k;
                    $image = null;
                    $title = $item;
                }

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

                $options[] = new ArrayData([
                    'Title' => $title,
                    'Value' => $value,
                    'Image' => $image,
                    'Selected' => $selected,
                    'Disabled' => $disabled,
                ]);
            }
        }

        $properties = array_merge($properties, [
            'Options' => new ArrayList($options)
        ]);

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
