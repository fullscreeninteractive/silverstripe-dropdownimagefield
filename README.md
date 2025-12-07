# SilverStripe DropdownImageField

Adds a `DropdownImageField` field which enables you to display images alongside the captions.
Uses a plugin for Chosen.js (which is used by SS), [Image-Select](https://github.com/websemantics/Image-Select). The plugin is modified.

![Working screenshot](https://github.com/fullscreeninteractive/silverstripe-dropdownimagefield/raw/main/docs/img/ss.png)

## Requirements

SilverStripe 6

## Install

```sh
composer require fullscreeninteractive/silverstripe-dropdownimagefield
```

## Usage


Example:

```php
DropdownImageField::create('Icon', 'Select icon')
    ->setSourceList($this->getAvailableIcons())

...
    /**
     * Get the available icons for the dropdown
     *
     * @return ArrayList<ArrayData>
     */
    public function getAvailableIcons(): ArrayList
    {
        $list = ArrayList::create();
        $icons = [
            'car' => '/_resources/app/images/car.svg'
        ];
    
        foreach ($icons as $key => $value) {
            $list->push(ArrayData::create([
                'ID' => $key,
                'Title' => $key,
                'Image' => $value
            ]));
        }

        return $list;
    }
```
