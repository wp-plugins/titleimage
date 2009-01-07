=== Titleimage ===
Contributors: Marc Schmidt
Donate link: 
Tags: image, post
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.7

This plugin displays a manually or randomly selected image of a chosen gallery from the great plugin NextGEN Gallery.

== Description ==

This plugin is just as simple as it sounds. If you already use the fabulous plugin [NextGEN Gallery](http://wordpress.org/extend/plugins/nextgen-gallery/) than the titleimage plugin gives you the ability to display the first image or a random image from a selected NexGEN Gallery. This selected image will be displayed with the php function call showTitleimage(), which can be used inside your templates. The best place would be inside the index.php file after the get_header(); call.

The following parameters are accessible on the Tools->Titleimage administration page:

* State - Switches the display of the titleimage on or off.
* Gallery - Selects the NGG Ressource-Gallery
* Image selection - Selects the image that should be displayed from the given gallery. It is possible to select the first image, a random image or a specified image.
* Imagesize - Defines the maximum Imagewidth and -height.
* Image Link - An URL with which the image will be linked.
* Image Link Attributes - Additional attributes for the image link to apply CSS classes for Javascript Lightbox Effects etc.
* Image Subtitle - Enables the image subtitle in different options (Gallery description, Image description, Individual text, No subtitlte).
* Subtitle Link - An URL with which the subtitle will be linked.
* Style - Defines wether the default CSS or an individual CSS File should be used.

Localizations:

* German
* English


= New in Version 0.7 =
* The plugin will now work even without the PHP directive "short_open_tag" turned "off".
* The image can now get an own link, independet from the subtitle link, an additional link attributes to apply CSS classes etc.
* GUI cleanup

You will find more information on [pixelshifter.de](http://www.pixelshifter.de), but for now in german language only ... sorry for that.


== Installation ==

1. Upload `titleimage` directory to `/wp-content/plugins/`
2. Activate the plugin on the 'Plugins' area in your WordPress installation
3. Use the php function call showTitleimage(); inside of your template

Please keep in mind, that this plugin also requires the NextGEN Gallery plugin: [NextGEN Gallery](http://wordpress.org/extend/plugins/nextgen-gallery/)!

== Frequently Asked Questions ==
http://www.pixelshifter.de/titleimage

