<?php

	prepareTitleimage();


	if (!function_exists("htmlspecialchars_decode")) {
	    function htmlspecialchars_decode($string,$style=ENT_COMPAT) {
	        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
	        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
	        return strtr($string,$translation);
	    }
	}

	function prepareTitleimage () {

		global $titleimage;

		if ($titleimage['state'] == 'true' && $titleimage['defaultstyle']) {

			// Load the default styles if option is set and titleimage is active

			add_action('wp_head', 'loadTitleimageCSS');

		}

	}



	function showTitleimage () {
		
		// This function has to be called on any template inside the theme
 		// where the titleimage should be displayed ... via showTitleimage() ...

		// TODO: Check wether NextGEN Gallery is installed

		global $wpdb, $titleimage;


		if (is_paged() || $titleimage['state'] != 'true') {
			
			// display only if on startpage and display status is 'true'
			return;

		}

		$picturelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE galleryid = '".$titleimage['galleryid']."' ORDER BY sortorder ASC");

		if (!$picturelist) {

			// If picturelist is empty display nothing
			return;

		} else {

			$disp_picture_id = null;
			$disp_imgwidth = $titleimage['imagewidth'];
			$disp_imgheight = $titleimage['imageheight'];
			$disp_description = '';

			if ($titleimage['selectmode'] == 'randompic') {

				$picturelist_key = rand(0,sizeof($picturelist) - 1);
				$disp_picture_id = $picturelist[$picturelist_key]->pid;

			} elseif ($titleimage['selectmode'] == 'firstpic') {

				$disp_picture_id = $picturelist['0']->pid;

			} else {

				$disp_picture_id = $titleimage['selectmode'];
				
			}

			// Use NGGs caching and thumbnail functions
			$picture = nggdb::find_image($disp_picture_id);
			$ngg_options = get_option('ngg_options');

			if ($ngg_options['imgCacheSinglePic']) {

				$image_path = $picture->cached_singlepic_file($disp_imgwidth, $disp_imgheight);

			} else {

				$image_path = NGGALLERY_URLPATH . 'nggshow.php?pid=' . $disp_picture_id . '&amp;width=' . $disp_imgwidth . '&amp;height=' . $disp_imgheight;

			}
			
			if ($titleimage['imageurl'] != '') {
				$image_link = $titleimage['imageurl'];
			} else {
				$image_link = $picture->imageURL;
			}
			
			if ($titleimage['imageurl_additions'] != '') {
				$image_link_additions = htmlspecialchars_decode($titleimage['imageurl_additions']);
			} else {
				$image_link_additions = 'class="lightview"';
			}

			switch ($titleimage['showtitle']) {

				case 'imagedesc':
					
					foreach ($picturelist as $pic) {
						if ($pic->pid == $disp_picture_id) {
							$disp_description = $pic->description;
							break;
						}
					}
				
					break;
				
				case 'gallerydesc':

					$albumdetails = $wpdb->get_results("SELECT * FROM $wpdb->nggallery WHERE gid = '".$titleimage['galleryid']."'");
					$disp_description = $albumdetails[0]->galdesc;

					break;
					
				case 'individual':

					$disp_description = $titleimage['description'];

					break;
						

			}

			if ($disp_description != '' && $titleimage['url'] != '') {
				$disp_description = '<a href="'.$titleimage['url'].'">'.$disp_description.'</a>';
				
			}



		}

?>
		<div id="Titleimage">
			<div class="TI_Inner">
				<div class="TI_Image">
					<a href="<?php echo $image_link?>" <?php echo $image_link_additions?>><img src="<?php echo $image_path?>" /></a>
				</div>
				<?php if ($disp_description != '') { ?>
				<div class="TI_Description">
					<p><?php echo $disp_description?></p>
				</div>
				<?php } ?>
			</div>
		</div>
<?php
	
	}
	
	

	function loadTitleimageCSS () {
		echo "\n".'<style type="text/css" media="screen">@import "'.TITLEIMAGE_URLPATH.'css/titleimage.css";</style>'."\n";
	}
	
?>