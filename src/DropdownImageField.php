<?php

namespace FullscreenInteractive\DropdownImageField;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;

class DropdownImageField extends DropdownField
{
    protected string $keyField = 'ID';

    protected string $labelField = 'Title';

    protected string $imageField = 'Image';

    protected ArrayList $sourceList;

    public function __construct($name, $title = null, $source = [], $value = null)
    {
        parent::__construct($name, $title, $source, $value);

        $this->addExtraClass('dropdown');
    }


    public function setSourceList(ArrayList $sourceList)
    {
        $this->sourceList = $sourceList;

        return $this;
    }


    public function setKeyField(string $field)
    {
        $this->keyField = $field;

        return $this;
    }

    public function setLabelField(string $field)
    {
        $this->labelField = $field;

        return $this;
    }


    public function setImageField($field)
    {
        $this->imageField = $field;

        return $this;
    }


    public function getSourceList()
    {
        return $this->sourceList;
    }


    public function getSourceEmpty()
    {
        $source = [];

        foreach ($this->getSourceList() as $k => $item) {
            if ($item instanceof ArrayData) {
                $value = $item->{$this->keyField};

                if (empty($this->labelField)) {
                    $title = '--nbsp';
                } else {
                    $title = $item->{$this->labelField};
                }
                if (method_exists($item, $this->imageField)) {
                    $image = $item->{$this->imageField}();
                } else {
                    $image = $item->{$this->imageField};
                }
            } else {
                $value = $k;
                $image = null;
                $title = $item;
            }

            $source[$value] = $title . ' (image: ' . $image . ')';
        }

        return $source;
    }



    /**
     * @param array $properties
     * @return string
     */
    public function Field($properties = [])
    {
        $options = [];

        // Add all options
        foreach ($this->getSourceEmpty() as $value => $title) {
            $options[] = $this->getFieldOption($value, $title);
        }

        $properties = array_merge($properties, [
            'Options' => ArrayList::create($options)
        ]);

        return parent::Field($properties);
    }


    public function getSourceValues()
    {
        return array_keys($this->getSourceEmpty());
    }
}
