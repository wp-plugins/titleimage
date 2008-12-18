<?php
			
	load_plugin_textdomain('titleimage','wp-content/plugins/titleimage/lang/');

	function admin_menu() {
		add_management_page(__("pluginname_titleimage","titleimage"), __("pluginname_titleimage","titleimage"), 'manage_options', 'admin', 'options');
	}
	
	function options ($value='') {
		
		global $wpdb, $titleimage;

		if (isset($_POST['titleimage_submit'])) {

			// TODO: what about security ...?
			
			$originalwidth = $_POST['titleimage']['imagewidth'];
			settype($_POST['titleimage']['imagewidth'], 'integer');
			settype($_POST['titleimage']['imagewidth'], 'string');
			
			if ($originalwidth != $_POST['titleimage']['imagewidth']) {
				$updateaddition .= '<br/>'.__("msg_imagewidth_adjusted","titleimage");
			}

			$originalheight = $_POST['titleimage']['imageheight'];
			settype($_POST['titleimage']['imageheight'], 'integer');
			settype($_POST['titleimage']['imageheight'], 'string');
			
			if ($originalheight != $_POST['titleimage']['imageheight']) {
				$updateaddition .= '<br/>'.__("msg_imageheight_adjusted","titleimage");
			}

			update_option('titleimage', $_POST['titleimage']);
			
			echo '<div class="updated fade"><p>'.__("msg_data_saved","titleimage").$updateaddition.'</p></div>';

			$titleimage = get_option('titleimage');

		}
		
		$gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC");
		
?>
		<div class="wrap">
			<h2><?=__("pluginname_titleimage","titleimage")?></h2>
			<p><?=__("titleimage_description","titleimage")?></p>
			<form action="" method="post" accept-charset="utf-8">
				<table class="form-table">
					<tr>
						<th scope="row"><label for=""><?=__("lbl_status","titleimage")?></label></th>
						<td>
							<input type="checkbox" name="titleimage[state]" value="true" id="state" <?php if ($titleimage['state'] == 'true') { echo ' checked="checked"';} ?> /> <label for="state"><?=__("lbl_active","titleimage")?></label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="galleryselect"><?=__("lbl_gallery","titleimage")?></label></th>
						<td>
							<select name="titleimage[galleryid]" id="galleryid">
								<option value="0" ><?=__("optval_selectgallery","titleimage")?></option>
<?php
								if(is_array($gallerylist)) {
									foreach($gallerylist as $gallery) {
										$selectmarker = $titleimage['galleryid'] == $gallery->gid ? ' selected="selected"' : '';
										echo '<option value="'.$gallery->gid.'"'.$selectmarker.'>'.$gallery->name.' | '.$gallery->title.'</option>'."\n";
									}
								}
?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="selectmode"><?=__("lbl_imageselection","titleimage")?></label></th>
						<td>
							<select	name="titleimage[selectmode]" id="selectmode">
								<option value="firstpic"<?=$titleimage['selectmode']=='firstpic'?' selected="selected"':''?>><?=__("optval_firstimage","titleimage")?></option>
								<option value="randompic"<?=$titleimage['selectmode']=='randompic'?' selected="selected"':''?>><?=__("optval_randomimage","titleimage")?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="imagewidth"><?=__("lbl_maximagewidth","titleimage")?></label></th>
						<td><input type="text" name="titleimage[imagewidth]" id="imagewidth" value="<?=$titleimage['imagewidth']?>" size="5" maxlength="4" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="imageheight"><?=__("lbl_maximageheight","titleimage")?></label></th>
						<td><input type="text" name="titleimage[imageheight]" id="imageheight" value="<?=$titleimage['imageheight']?>" size="5" maxlength="4" /></td>
					</tr>
					<tr>
						<th scope="row"><label for=""><?=__("lbl_imagtitle","titleimage")?></label></th>
						<td><input type="checkbox" name="titleimage[showtitle]" value="true" id="showtitle" <?php if ($titleimage['showtitle'] == 'true') { echo ' checked="checked"';} ?> />&nbsp;<label for="showtitle"><?=__("lbl_display","titleimage")?></label></td>
					</tr>
					<tr>
						<th scope="row"><label for="description"><?=__("lbl_alttitle","titleimage")?></label></th>
						<td><input type="text" name="titleimage[description]" id="description" value="<?=$titleimage['description']?>" size="50" maxlength="255" />&nbsp;(<?=__("desc_alttitle","titleimage")?>)</td>
					</tr>
					<tr>
						<th scope="row"><label for="url"><?=__("lbl_url","titleimage")?></label></th>
						<td><input type="text" name="titleimage[url]" id="url" value="<?=$titleimage['url']?>" size="50" maxlength="255" />&nbsp;(<?=__("desc_optional","titleimage")?>)</td>
					</tr>
					<tr>
						<th scope="row"><label for=""><?=__("lbl_style","titleimage")?></label></th>
						<td><input type="checkbox" name="titleimage[defaultstyle]" value="true" id="defaultstyle" <?php if ($titleimage['defaultstyle'] == 'true') { echo ' checked="checked"';} ?> />&nbsp;<label for="defaultstyle"><?=__("lbl_usedefaultstyle","titleimage")?></label></td>
					</tr>
					
				</table>
		
				<p><input type="submit" name="titleimage_submit" value="<?=__("btn_save","titleimage")?> &raquo;" class="button-primary" accesskey="S" /></p>
			</form>
		</div>
<?php
	}

	add_action('admin_menu', 'admin_menu');
?>