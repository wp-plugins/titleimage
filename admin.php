<?php
			
	load_plugin_textdomain('titleimage','wp-content/plugins/titleimage/lang/');



	function admin_menu() {

		$page = add_management_page(__("pluginname_titleimage","titleimage"), __("pluginname_titleimage","titleimage"), 'manage_options', 'admin', 'options');

	    add_contextual_help($page, titleimage_help());

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
					return false;
				})

				jQuery('#imageselector').change(function() {
					update_descriptionfield();
					return false;
				})
				
			});


			function update_descriptionfield (init) {

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
								jQuery("#desc_p").html('-&gt;'+html);
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
								jQuery("#desc_p").html('-&gt;'+html);
								jQuery("#desc_p").show();
							}
						});

					};

					return false;

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

		<?=$message?>

		<div class="wrap" id="top">
			<h2><?=__("pluginname_titleimage","titleimage")?></h2>
			<form action="" method="post" accept-charset="utf-8">

				<table class="form-table">
					<tr>
						<th scope="row"><label for=""><?=__("lbl_status","titleimage")?></label></th>
						<td>
							<input type="checkbox" name="titleimage[state]" value="true" id="state" <?php if ($titleimage['state'] == 'true') { echo ' checked="checked"';} ?> /> <label for="state"><?=__("lbl_active","titleimage")?></label>
							<br/><br/>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="galleryid"><?=__("lbl_gallery","titleimage")?></label></th>
						<td>
							<select name="titleimage[galleryid]" id="galleryid">
								<option value="none" ><?=__("optval_selectgallery","titleimage")?></option>
								<?=$gallerylist_html?>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="imageselector"><?=__("lbl_imageselection","titleimage")?></label></th>
						<td>
							<select	name="titleimage[selectmode]" id="imageselector">
								<option value="firstpic"<?=$titleimage['selectmode']=='firstpic'?' selected="selected"':''?>><?=__("optval_firstimage","titleimage")?></option>
								<option value="randompic"<?=$titleimage['selectmode']=='randompic'?' selected="selected"':''?>><?=__("optval_randomimage","titleimage")?></option>
								<optgroup label="<?=__("lbl_image","titleimage")?>" style="padding-left: 10px;" id="imageselector_imagegroup">
									<?=$imageselector_html?>
								</optgroup>
							</select>&nbsp;<img src="images/loading.gif" id="loading_imageselector" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="imagewidth"><?=__("lbl_imagesize","titleimage")?></label></th>
						<td>
							<?=__("lbl_maximagewidth","titleimage")?>:&nbsp;<input type="text" name="titleimage[imagewidth]" id="imagewidth" value="<?=$titleimage['imagewidth']?>" size="5" maxlength="4" />&nbsp;&nbsp;&nbsp;
							<?=__("lbl_maximageheight","titleimage")?>:&nbsp;<input type="text" name="titleimage[imageheight]" id="imageheight" value="<?=$titleimage['imageheight']?>" size="5" maxlength="4" />
							<br/><br/>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="subtitleselector"><?=__("lbl_imagesubtitle","titleimage")?></label></th>
						<td>
							<select name="titleimage[showtitle]" id="subtitleselector">
								<option value="none"<?php if ($titleimage[showtitle]=='none') { echo ' selected="selected"';} ?>><?=__("optval_nosubtitle","titleimage")?></option>
								<option value="gallerydesc"<?php if ($titleimage[showtitle]=='gallerydesc') { echo ' selected="selected"';} ?>><?=__("optval_gallerydescription","titleimage")?>:&nbsp;</option>
								<option value="imagedesc"<?php if ($titleimage[showtitle]=='imagedesc' || $titleimage[showtitle]=='true') { echo ' selected="selected"';} ?>><?=__("optval_imagedescription","titleimage")?>:&nbsp;</option>
								<option value="individual"<?php if ($titleimage[showtitle]=='individual') { echo ' selected="selected"';} ?>><?=__("optval_individualtext","titleimage")?>:&nbsp;</option>
							</select>
							<br/>
							<input type="text" name="titleimage[description]" id="description" value="<?=$titleimage['description']?>" size="70" maxlength="255" style="margin-top: 10px;" />
							<input type="hidden" name="description_cache" value="<?=$titleimage['description']?>" id="description_cache">
							<p id="loading_description"><img src="images/loading.gif" /></p>
							<p id="desc_p" style="padding: 2px 0px 0px 6px; margin-bottom: 7px;"></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="url"><?=__("lbl_url","titleimage")?>&nbsp;(<?=__("desc_optional","titleimage")?>)</label></th>
						<td><input type="text" name="titleimage[url]" id="url" value="<?=$titleimage['url']?>" size="70" maxlength="255" />
							<br/><br/>
							</td>
					</tr>

					<tr>
						<th scope="row"><label for=""><?=__("lbl_style","titleimage")?></label></th>
						<td><input type="checkbox" name="titleimage[defaultstyle]" value="true" id="defaultstyle" <?php if ($titleimage['defaultstyle'] == 'true') { echo ' checked="checked"';} ?> />&nbsp;<label for="defaultstyle"><?=__("lbl_usedefaultstyle","titleimage")?></label></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<br/>
							<input type="submit" name="titleimage_submit" value="<?=__("btn_save","titleimage")?> &raquo;" class="button-primary" accesskey="S" />
						</td>
					
				</table>
			</form>
			<p>&nbsp;</p>
		</div>
		<script type="text/javascript" charset="utf-8">
			update_descriptionfield(true);
		</script>
<?php
	}

	function ajaxGetImageListOptions () {

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

		global $wpdb;
		
		if ($_POST['selectmode'] == 'firstpic') {
			$firstpic = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE galleryid='".$_POST['galleryid']."' ORDER BY sortorder ASC LIMIT 1");
			$imageid = $firstpic[0]->pid;
		} else {
			$imageid = $_POST['imageid'];
		}

		$image = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE pid='".$imageid."'");

		if ($image[0]->description) {
			echo $image[0]->description.$test;
		} else {
			echo "-- ".__('desc_no_description',"titleimage")." --";
		}
		
		die;

	}

	function ajaxGetGalleryDescription () {

		global $wpdb;

//		echo $_POST['galleryid'];

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