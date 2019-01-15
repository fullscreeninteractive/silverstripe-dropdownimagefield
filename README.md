# DropdownImageField

Adds a `DropdownImageField` field which enables you to display images alongside the captions.
Uses a plugin for Chosen.js (which is used by SS), [Image-Select](https://github.com/websemantics/Image-Select). The plugin is modified.

![Working screenshot](https://github.com/Copperis/DropdownImageField/raw/master/docs/img/ss.png)

## Requirements

SilverStripe 4.0+

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

This plugin uses a `setTimeout` function with additional parameters. A polyfill is loaded to support IE9 and lower.
