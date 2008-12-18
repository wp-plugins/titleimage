<?php

	prepareTitleimage();

	function prepareTitleimage () {

		global $wpdb, $titleimage;

		if ($titleimage['defaultstyle']) {
			// Load the default styles if option is set
			add_action('wp_head', 'loadTitleimageCSS');
		}

	}



	function showTitleimage () {
		
		// This function has to be called on any template inside the theme
 		// where the titleimage should be displayed ... via showTitleimage() ...

		// TODO: Check i NextGEN Gallery is installed

		global $wpdb, $titleimage;

		if (is_paged() || $titleimage['state'] != 'true') {
			return; // display only if on startpage and display status is 'true'
		}
		
//		print_r ($titleimage);

		$picturelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE galleryid = '".$titleimage['galleryid']."' ORDER BY sortorder ASC");

		if (!$picturelist) {

			print '<h1>Galeriefehler</h1>';
			return;

		} else {

			$disp_picture_id = null;
			$disp_imgwidth = $titleimage['imagewidth'];
			$disp_imgheight = $titleimage['imageheight'];
			$disp_description = '';

			if ($titleimage['selectmode'] == 'randompic') {
				$picturelist_key = rand(0,sizeof($picturelist) - 1);
			} else {
				$picturelist_key = '0';
			}

			$disp_picture_id = $picturelist[$picturelist_key]->pid;

			if ($titleimage['showtitle'] == 'true') {

				if ($titleimage['description'] != '') {
					$disp_description = $titleimage['description'];
				} else if ($picturelist[$picturelist_key]->description != '') {
					$disp_description = $picturelist[$picturelist_key]->description;
				}

				if ($titleimage['url'] != '') {
					$disp_description = '<a href="'.$titleimage['url'].'">'.stripslashes($disp_description).'</a>';
				}

			}

		}

?>
		<div id="Titleimage">
			<div class="TI_Inner">
				<div class="TI_Image">
					<?=nggSinglePicture($disp_picture_id, $disp_imgwidth, $disp_imgheight, '', '', 'titleimage');?>
				</div>
				<?php if ($disp_description != '') { ?>
				<div class="TI_Description">
					<p><?=$disp_description?></p>
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
