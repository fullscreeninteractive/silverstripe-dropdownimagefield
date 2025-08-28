# DropdownImageField

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
DropdownImageField::create('LanguageID', 'Select language',
	LanguageObj::get(), // Source for items.
	'ID',		// Key field on item.
	'Title',	// Caption field on item.
	'Icon'		// Image field on item. Can be a method/relation that returns an image.
)
```

## Notes

This plugin uses a `setTimeout` function with additional parameters.
