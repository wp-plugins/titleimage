��             +         �     �     �     �     �          #     8  
   N     Y  	   e     o     �     �     �     �  
   �  	   �     �     �     �                8     N     `     z     �     �     �     �     �  �  �     �     �     �  �    �   �  �  �	  �   @     �     �  	          	   !     +     9     K     \     b     h     m          �     �  0   �     �     �          #     3     ?     L  
   _                                                                                                                
                   	             btn_save desc_no_description desc_optional help_basic_description help_furtherinformation help_gui_description help_use_in_templates lbl_active lbl_gallery lbl_image lbl_imageselection lbl_imagesize lbl_imagesubtitle lbl_maximageheight lbl_maximagewidth lbl_status lbl_style lbl_url lbl_usedefaultstyle msg_data_saved msg_imageheight_adjusted msg_imagewidth_adjusted msg_nogalleryselected optval_firstimage optval_gallerydescription optval_imagedescription optval_individualtext optval_nosubtitle optval_randomimage optval_selectgallery pluginname_titleimage Project-Id-Version: titleimage
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2008-12-29 12:13+0100
PO-Revision-Date: 
Last-Translator: Marc <marc@pixelshifter.de>
Language-Team: Marc Schmidt <marc@pixelshifter.de>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Poedit-Language: German
X-Poedit-Country: GERMANY
X-Poedit-SourceCharset: utf-8
X-Poedit-KeywordsList: __;_e
X-Poedit-Basepath: .
X-Poedit-SearchPath-0: .
X-Poedit-SearchPath-1: ..
 Save No description available Optional <h5>What is &quot;Titleimage&quot; good for?</h5><p>If you already use the fabulous plugin NextGEN Gallery than the titleimage plugin gives you the possibility to display the first or a random image from a selected NexGEN Gallery. This selected image will be displayed with the php function call showTitleimage(), which can be used inside your templates. The best place would be inside the index.php file directly after the get_header(); call.</p> <h5>More Information &amp; Ressources</h5><p>	<a href="http://wordpress.org/extend/plugins/titleimage/">wordpress.org/extend/plugins/titleimage/</a><br/>	<a href="http://www.pixelshifter.de/titleimage">www.pixelshifter.de/titleimage</a><br/></p> <h5>Options</h5><ul>	<li>		<strong>State</strong><br/>		Switches the display of the titleimage on or off.	</li>	<li>		<strong>Gallery Selection</strong><br/>		Selects the NGG Ressource-Gallery	</li>	<li><strong>Image Selection</strong><br/>		Selects the image that should be displayed from the given gallery. It is possible to select the first image, a random image or a specified image.	</li>	<li><strong>Imagesize</strong><br/>		Defines the maximum Imagewidth and -height.	</li>	<li><strong>Image Subtitle</strong><br/>		Enables the image subtitle in different options:		<ul>			<li>Gallery description</li>			<li>Image description</li>			<li>Individual text</li>			<li>No subtitlte</li>		</ul>	</li>	<li><strong>Link</strong><br/>		An URL eith which the subtitle will be linked.	</li>	<li><strong>Stil</strong><br/>		Defines wether the default CSS or an individual CSS File should be used.	</li></ul> <h5>Usage in Templates</h5><p>To show the selected image please use the following PHP function call in your template (e.g. on your index.php):<br/>&lt;?php showTitleimage(); ?&gt;</p> Active Gallery Image ... Image selection Imagesize Imagesubtitle Max. image height Max. image width State Style Link Use default style Data were saved Imageheight was adjusted Imagewidth was adjusted No Gallery selected. No image will be displayed. First image Gallery description Image description Individual text No Subtitle Random image Select gallery ... Titleimage 