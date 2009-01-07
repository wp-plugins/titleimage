<?php
			
	load_plugin_textdomain('titleimage','wp-content/plugins/titleimage/lang/');



	function admin_menu() {

		$page = add_management_page(__("pluginname_titleimage","titleimage"), __("pluginname_titleimage","titleimage"), 'manage_options', 'admin', 'options');

		if (function_exists('add_contextual_help')) {
		    add_contextual_help($page, titleimage_help());
		}

	}


	
	function options ($value='') {

		$nonce = wp_create_nonce('titleimage');
		
		global $wpdb, $titleimage;

		if (isset($_POST['titleimage_submit'])) {
			
			$message = '';
			$messageaddition = '';

			// TODO: what about security ...?
			
			
			if ($_POST['titleimage']['galleryid'] == 'none') {
				$messageaddition .= '<li>'.__("msg_nogalleryselected","titleimage").'</li>';
			}

			$originalwidth = $_POST['titleimage']['imagewidth'];
			settype($_POST['titleimage']['imagewidth'], 'integer');
			settype($_POST['titleimage']['imagewidth'], 'string');
			
			if ($originalwidth != $_POST['titleimage']['imagewidth']) {
				$messageaddition .= '<li>'.__("msg_imagewidth_adjusted","titleimage").'</li>';
			}

			$originalheight = $_POST['titleimage']['imageheight'];
			settype($_POST['titleimage']['imageheight'], 'integer');
			settype($_POST['titleimage']['imageheight'], 'string');
			
			if ($originalheight != $_POST['titleimage']['imageheight']) {
				$messageaddition .= '<li>'.__("msg_imageheight_adjusted","titleimage").'</li>';
			}
			
			$_POST['titleimage']['imageurl_additions'] = htmlspecialchars(stripslashes($_POST['titleimage']['imageurl_additions']));

			$_POST['titleimage']['description'] = htmlspecialchars(stripslashes($_POST['titleimage']['description']));


			update_option('titleimage', $_POST['titleimage']);
			
			if ($messageaddition != '') {
				$messageaddition = '<ul style="list-style-type: square; margin-left: 30px;">'.$messageaddition.'</ul>';
			}
			
			$message = '<div class="updated fade"><p>'.__("msg_data_saved","titleimage").'</p>'.$messageaddition.'</div>';

			$titleimage = get_option('titleimage');

		}
		
		$gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY title ASC");

		$gallerylist_html = '';

		if (is_array($gallerylist)) {

			foreach ($gallerylist as $gallery) {

				$selectmarker = $titleimage['galleryid'] == $gallery->gid ? ' selected="selected"' : '';

				$gallerylist_html .= '<option value="'.$gallery->gid.'" name="galleryname"'.$selectmarker.'>'.$gallery->title.'&nbsp;&nbsp;&nbsp;&nbsp;['.$gallery->name.' - '.$gallery->gid.']</option>'."\n";

			}

		}
		

		$imagelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures  WHERE galleryid='".$titleimage['galleryid']."' ORDER BY sortorder ASC");
//		$imagelist = nggdb::get_gallery($titleimage['galleryid']);

		$imageselector_html = '';
		
		if (is_array($imagelist)) {

			foreach ($imagelist as $image) {

				$selectmarker = $titleimage['selectmode'] == $image->pid ? ' selected="selected"' : '';

				$imageselector_html .= '<option value="'.$image->pid.'" name="imagename"'.$selectmarker.'>'.$image->alttext.'&nbsp;&nbsp;&nbsp;&nbsp;['.$image->pid.']</option>'."\n";

			}
			
		}


?>

		<script type="text/javascript" charset="utf-8">
		
		
			jQuery(document).ready(function(){

				jQuery('#galleryid').change(function() {
					jQuery.ajax({
						type: "post",url: "admin-ajax.php",data: { action: 'ajaxgetimagelistoptions', galleryid:jQuery('#galleryid').val(), _ajax_nonce: '<?php echo $nonce; ?>' },
						beforeSend: function() {
							jQuery("#loading_imageselector").show();
						},
						success: function(html){
							jQuery("#loading_imageselector").hide();
							jQuery("#imageselector").fadeOut('fast');
							jQuery("#imageselector_imagegroup").html(html);
							jQuery("#imageselector").fadeIn("slow");
							update_descriptionfield();
						}
					});
					return false;
				})
				
				jQuery('#subtitleselector').change(function() {
					update_descriptionfield();
					update_subtitleurlfield();
					return false;
				})

				jQuery('#imageselector').change(function() {
					update_descriptionfield();
					return false;
				})
				
			});


			function update_descriptionfield () {

				var subtitletype = jQuery('#subtitleselector').val();

				if (subtitletype == 'none') {

					jQuery("#description").hide();
					jQuery("#desc_p").hide();

				} else {

					if (subtitletype == 'individual') {

						jQuery("#desc_p").hide();
						jQuery("#description").attr("value", jQuery('#description_cache').val());
						jQuery("#description").show();
						
					} else if (subtitletype == 'imagedesc') {

						jQuery.ajax({
							type: "post",url: "admin-ajax.php",data: { action: 'ajaxgetimagedescription', imageid:jQuery('#imageselector').val(), selectmode:jQuery('#imageselector').val(), galleryid:jQuery('#galleryid').val(), _ajax_nonce: '<?php echo $nonce; ?>' },
							beforeSend: function() {
								jQuery("#desc_p").hide();
								jQuery("#loading_description").show();
							},
							success: function(html){
								jQuery("#loading_description").hide();
								jQuery("#description").hide();
								jQuery("#desc_p").html(html);
								jQuery("#desc_p").show();
							}
						});

					} else if (subtitletype == 'gallerydesc') {

						jQuery.ajax({
							type: "post",url: "admin-ajax.php",data: { action: 'ajaxgetgallerydescription', galleryid:jQuery('#galleryid').val(), _ajax_nonce: '<?php echo $nonce; ?>' },
							beforeSend: function() {
								jQuery("#desc_p").hide();
								jQuery("#loading_description").show();
							},
							success: function(html){
								jQuery("#loading_description").hide();
								jQuery("#description").hide();
								jQuery("#desc_p").html(html);
								jQuery("#desc_p").show();
							}
						});

					};

					return false;

				};

			}

			function update_subtitleurlfield () {

				var subtitletype = jQuery('#subtitleselector').val();

				if (subtitletype == 'none') {

					jQuery("#url").attr('disabled', 'disabled');

				} else {

					jQuery("#url").attr('disabled', false);
					
				};
				
			}

		</script>

		<style type='text/css'>
			#loading_imageselector {
				display: none;
				padding-top: 5px;
			}
			#loading_description {
				display: none;
			}
		</style>

		<?php echo $message?>

		<div id="icon-tools" class="icon32"><br /></div> 
		
		<div class="wrap" id="top">
			<h2><?php echo __("pluginname_titleimage","titleimage")?></h2>
			<form action="" method="post" accept-charset="utf-8">

				<table class="form-table">

					<tr>
						<th scope="row"><label for="state"><?php echo __("lbl_status","titleimage")?></label></th>
						<td>
							<input type="checkbox" name="titleimage[state]" value="true" id="state" <?php if ($titleimage['state'] == 'true') { echo ' checked="checked"';} ?> /> <label for="state"><?php echo __("lbl_active","titleimage")?></label>
							<br/><br/>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="galleryid"><?php echo __("lbl_gallery","titleimage")?></label></th>
						<td>
							<select name="titleimage[galleryid]" id="galleryid">
								<option value="none" ><?php echo __("optval_selectgallery","titleimage")?></option>
								<?php echo $gallerylist_html?>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="imageselector"><?php echo __("lbl_imageselection","titleimage")?></label></th>
						<td>
							<select	name="titleimage[selectmode]" id="imageselector" style="margin-bottom: 5px;">
								<option value="firstpic"<?php echo $titleimage['selectmode']=='firstpic'?' selected="selected"':''?>><?php echo __("optval_firstimage","titleimage")?></option>
								<option value="randompic"<?php echo $titleimage['selectmode']=='randompic'?' selected="selected"':''?>><?php echo __("optval_randomimage","titleimage")?></option>
								<optgroup label="<?php echo __("lbl_image","titleimage")?>" style="padding-left: 10px;" id="imageselector_imagegroup">
									<?php echo $imageselector_html?>
								</optgroup>
							</select>&nbsp;<img src="images/loading.gif" id="loading_imageselector" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="imagewidth"><?php echo __("lbl_maximagesize","titleimage")?></label></th>
						<td>
							<input type="text" name="titleimage[imagewidth]" id="imagewidth" value="<?php echo $titleimage['imagewidth']?>" size="5" maxlength="4" />&nbsp;<?php echo __("lbl_width","titleimage")?>&nbsp;&nbsp;&nbsp;
							<input type="text" name="titleimage[imageheight]" id="imageheight" value="<?php echo $titleimage['imageheight']?>" size="5" maxlength="4" />&nbsp;<?php echo __("lbl_height","titleimage")?>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="imageurl"><?php echo __("lbl_imageurl","titleimage")?></label><span style="font-size: 80%; color: #999;">&nbsp;(<?php echo __("desc_optional","titleimage")?>)</span></th>
						<td><input type="text" name="titleimage[imageurl]" id="imageurl" value="<?php echo $titleimage['imageurl']?>" size="70" maxlength="255" />
							</td>
					</tr>

					<tr>
						<th scope="row"><label for="imageurl_additions"><?php echo __("lbl_imageurl_additions","titleimage")?></label><span style="font-size: 80%; color: #999;">&nbsp;(<?php echo __("desc_optional","titleimage")?>)</span></th>
						<td><input type="text" name="titleimage[imageurl_additions]" id="imageurl_additions" value="<?php echo $titleimage['imageurl_additions']?>" size="30" maxlength="255" /><br/><br/>
							</td>
					</tr>

					<tr>
						<th scope="row"><label for="subtitleselector"><?php echo __("lbl_imagesubtitle","titleimage")?></label></th>
						<td>
							<select name="titleimage[showtitle]" id="subtitleselector">
								<option value="none"<?php if ($titleimage[showtitle]=='none') { echo ' selected="selected"';} ?>><?php echo __("optval_nosubtitle","titleimage")?></option>
								<option value="gallerydesc"<?php if ($titleimage[showtitle]=='gallerydesc') { echo ' selected="selected"';} ?>><?php echo __("optval_gallerydescription","titleimage")?>:&nbsp;</option>
								<option value="imagedesc"<?php if ($titleimage[showtitle]=='imagedesc' || $titleimage[showtitle]=='true') { echo ' selected="selected"';} ?>><?php echo __("optval_imagedescription","titleimage")?>:&nbsp;</option>
								<option value="individual"<?php if ($titleimage[showtitle]=='individual') { echo ' selected="selected"';} ?>><?php echo __("optval_individualtext","titleimage")?>:&nbsp;</option>
							</select>
							<br/>
							<input type="text" name="titleimage[description]" id="description" value="<?php echo $titleimage['description']?>" size="70" maxlength="255" style="margin-top: 10px;" />
							<input type="hidden" name="description_cache" value="<?php echo $titleimage['description']?>" id="description_cache">
							<p id="loading_description"><img src="images/loading.gif" /></p>
							<p id="desc_p" style="padding: 2px 0px 0px 6px; margin-bottom: 0px;"></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="url"><?php echo __("lbl_subtitleurl","titleimage")?></label><span style="font-size: 80%; color: #999;">&nbsp;(<?php echo __("desc_optional","titleimage")?>)</span></th>
						<td><input type="text" name="titleimage[url]" id="url" value="<?php echo $titleimage['url']?>" size="70" maxlength="255" /><br/><br/>
							</td>
					</tr>

					<tr>
						<th scope="row"><label for="defaultstyle"><?php echo __("lbl_style","titleimage")?></label></th>
						<td><input type="checkbox" name="titleimage[defaultstyle]" value="true" id="defaultstyle" <?php if ($titleimage['defaultstyle'] == 'true') { echo ' checked="checked"';} ?> />&nbsp;<label for="defaultstyle"><?php echo __("lbl_usedefaultstyle","titleimage")?></label></td>
					</tr>
					<tr>
						<th scope="row">&nbsp;</th>
						<td>
							<br/>
							<input type="submit" name="titleimage_submit" value="<?php echo __("btn_save","titleimage")?> &raquo;" class="button-primary" accesskey="S" />
						</td>
					
				</table>
			</form>
			<p>&nbsp;</p>
		</div>
		<script type="text/javascript" charset="utf-8">
			update_descriptionfield();
		</script>
<?php
	}



	function ajaxGetImageListOptions () {

		check_ajax_referer("titleimage");

		global $wpdb;
		
		$imagelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures  WHERE galleryid='".$_POST['galleryid']."' ORDER BY sortorder ASC");

		$imageselector_html = '';

		foreach ($imagelist as $image):
			$imageselector_html .= '<option value="'.$image->pid.'" name="imagename"'.$selectmarker.'>'.$image->alttext.'&nbsp;&nbsp;&nbsp;&nbsp;['.$image->pid.']</option>'."\n";
//			echo '<option value="'.$image->pid.'">'.$image->alttext.' ['.$image->pid.']'.'</option>';
		endforeach;
		
		echo $imageselector_html;

		die;

	}



	function ajaxGetImageDescription () {

		check_ajax_referer("titleimage");

		global $wpdb;
		
		if ($_POST['selectmode'] == 'firstpic') {
			$firstpic = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE galleryid='".$_POST['galleryid']."' ORDER BY sortorder ASC LIMIT 1");
			$imageid = $firstpic[0]->pid;
		} else {
			$imageid = $_POST['imageid'];
		}

		$image = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE pid='".$imageid."'");

		if ($image[0]->description) {
			echo $image[0]->description;
		} else {
			echo "-- ".__('desc_no_description',"titleimage")." --";
		}
		
		die;

	}



	function ajaxGetGalleryDescription () {

		check_ajax_referer("titleimage");

		global $wpdb;

		$gallery = $wpdb->get_results("SELECT * FROM $wpdb->nggallery WHERE gid='".$_POST['galleryid']."'");

		if ($gallery[0]->galdesc) {
			echo $gallery[0]->galdesc;
		} else {
			echo "-- ".__('desc_no_description',"titleimage")." --";
		}
		
		die;

	}


	
	function titleimage_help () {
		
		$html = 	'<div class="side-info" style="background: transparent;">'.
					__("help_basic_description","titleimage").
					__('help_gui_description', 'titleimage').
					__('help_use_in_templates', 'titleimage').
					__('help_furtherinformation', 'titleimage').
					'</div>';
		
		return $html;
	}


	add_action('wp_ajax_ajaxgetimagelistoptions', 'ajaxGetImageListOptions');
	add_action('wp_ajax_ajaxgetimagedescription', 'ajaxGetImageDescription');
	add_action('wp_ajax_ajaxgetgallerydescription', 'ajaxGetGalleryDescription');

	add_action('admin_menu', 'admin_menu');
		

?>