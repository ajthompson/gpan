<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_Ihrss_TABLE."
	WHERE `Ihrss_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'ihrss'); ?></strong></p></div><?php
}
else
{
	$Ihrss_errors = array();
	$Ihrss_success = '';
	$Ihrss_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_Ihrss_TABLE."`
		WHERE `Ihrss_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'Ihrss_path' => $data['Ihrss_path'],
		'Ihrss_link' => $data['Ihrss_link'],
		'Ihrss_target' => $data['Ihrss_target'],
		'Ihrss_title' => $data['Ihrss_title'],
		'Ihrss_order' => $data['Ihrss_order'],
		'Ihrss_status' => $data['Ihrss_status'],
		'Ihrss_type' => $data['Ihrss_type']
	);
}
// Form submitted, check the data
if (isset($_POST['Ihrss_form_submit']) && $_POST['Ihrss_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Ihrss_form_edit');
	
	$form['Ihrss_path'] = isset($_POST['Ihrss_path']) ? $_POST['Ihrss_path'] : '';
	if ($form['Ihrss_path'] == '')
	{
		$Ihrss_errors[] = __('Enter image path', 'ihrss');
		$Ihrss_error_found = TRUE;
	}

	$form['Ihrss_link'] = isset($_POST['Ihrss_link']) ? $_POST['Ihrss_link'] : '';
	if ($form['Ihrss_link'] == '')
	{
		$Ihrss_errors[] = __('Enter target link', 'ihrss');
		$Ihrss_error_found = TRUE;
	}
	
	$form['Ihrss_target'] = isset($_POST['Ihrss_target']) ? $_POST['Ihrss_target'] : '';
	$form['Ihrss_title'] = isset($_POST['Ihrss_title']) ? $_POST['Ihrss_title'] : '';
	$form['Ihrss_order'] = isset($_POST['Ihrss_order']) ? $_POST['Ihrss_order'] : '';
	$form['Ihrss_status'] = isset($_POST['Ihrss_status']) ? $_POST['Ihrss_status'] : '';
	$form['Ihrss_type'] = isset($_POST['Ihrss_type']) ? $_POST['Ihrss_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($Ihrss_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_Ihrss_TABLE."`
				SET `Ihrss_path` = %s,
				`Ihrss_link` = %s,
				`Ihrss_target` = %s,
				`Ihrss_title` = %s,
				`Ihrss_order` = %d,
				`Ihrss_status` = %s,
				`Ihrss_type` = %s
				WHERE Ihrss_id = %d
				LIMIT 1",
				array($form['Ihrss_path'], $form['Ihrss_link'], $form['Ihrss_target'], $form['Ihrss_title'], $form['Ihrss_order'], 
					$form['Ihrss_status'], $form['Ihrss_type'], $did)
			);
		$wpdb->query($sSql);
		$Ihrss_success = __('Image details was successfully updated.', 'ihrss');
	}
}

if ($Ihrss_error_found == TRUE && isset($Ihrss_errors[0]) == TRUE)
{
?>
  <div class="error fade">
    <p><strong><?php echo $Ihrss_errors[0]; ?></strong></p>
  </div>
  <?php
}
if ($Ihrss_error_found == FALSE && strlen($Ihrss_success) > 0)
{
?>
  <div class="updated fade">
    <p><strong><?php echo $Ihrss_success; ?> <a href="<?php echo WP_IHRSS_ADMIN_URL; ?>"><?php _e('Click here', 'ihrss'); ?></a> 
	 <?php _e('to view the details', 'ihrss'); ?></strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo WP_IHRSS_PLUGIN_URL; ?>/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Image horizontal reel scroll slideshow', 'ihrss'); ?></h2>
	<form name="Ihrss_form" method="post" action="#" onsubmit="return Ihrss_submit()"  >
      <h3><?php _e('Update image details', 'ihrss'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'ihrss'); ?></label>
      <input name="Ihrss_path" type="text" id="Ihrss_path" value="<?php echo esc_html(stripslashes($form['Ihrss_path'])); ?>" size="100" />
      <p><?php _e('Where is the picture located on the internet', 'ihrss'); ?></p>
      <label for="tag-link"><?php _e('Enter target link', 'ihrss'); ?></label>
      <input name="Ihrss_link" type="text" id="Ihrss_link" value="<?php echo esc_html(stripslashes($form['Ihrss_link'])); ?>" size="100" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them. url must start with either http or https.', 'ihrss'); ?></p>
      <label for="tag-target"><?php _e('Enter target option', 'ihrss'); ?></label>
      <select name="Ihrss_target" id="Ihrss_target">
        <option value='_blank' <?php if($form['Ihrss_target']=='_blank') { echo 'selected' ; } ?>>_blank</option>
        <option value='_parent' <?php if($form['Ihrss_target']=='_parent') { echo 'selected' ; } ?>>_parent</option>
        <option value='_self' <?php if($form['Ihrss_target']=='_self') { echo 'selected' ; } ?>>_self</option>
        <option value='_new' <?php if($form['Ihrss_target']=='_new') { echo 'selected' ; } ?>>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'ihrss'); ?></p>
      <label for="tag-title"><?php _e('Enter image reference', 'ihrss'); ?></label>
      <input name="Ihrss_title" type="text" id="Ihrss_title" value="<?php echo esc_html(stripslashes($form['Ihrss_title'])); ?>" size="100" />
      <p><?php _e('Enter image reference. This is only for reference.', 'ihrss'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery type', 'ihrss'); ?></label>
      <select name="Ihrss_type" id="Ihrss_type">
        <option value='GROUP1' <?php if($form['Ihrss_type']=='GROUP1') { echo 'selected' ; } ?>>GROUP1</option>
        <option value='GROUP2' <?php if($form['Ihrss_type']=='GROUP2') { echo 'selected' ; } ?>>GROUP2</option>
        <option value='GROUP3' <?php if($form['Ihrss_type']=='GROUP3') { echo 'selected' ; } ?>>GROUP3</option>
        <option value='GROUP4' <?php if($form['Ihrss_type']=='GROUP4') { echo 'selected' ; } ?>>GROUP4</option>
        <option value='GROUP5' <?php if($form['Ihrss_type']=='GROUP5') { echo 'selected' ; } ?>>GROUP5</option>
        <option value='GROUP6' <?php if($form['Ihrss_type']=='GROUP6') { echo 'selected' ; } ?>>GROUP6</option>
        <option value='GROUP7' <?php if($form['Ihrss_type']=='GROUP7') { echo 'selected' ; } ?>>GROUP7</option>
        <option value='GROUP8' <?php if($form['Ihrss_type']=='GROUP8') { echo 'selected' ; } ?>>GROUP8</option>
        <option value='GROUP9' <?php if($form['Ihrss_type']=='GROUP9') { echo 'selected' ; } ?>>GROUP9</option>
        <option value='GROUP0' <?php if($form['Ihrss_type']=='GROUP0') { echo 'selected' ; } ?>>GROUP0</option>
		<option value='Widget' <?php if($form['Ihrss_type']=='Widget') { echo 'selected' ; } ?>>Widget</option>
		<option value='sample' <?php if($form['Ihrss_type']=='Sample') { echo 'selected' ; } ?>>Sample</option>
		<option value='GROUP10' <?php if($form['Ihrss_type']=='GROUP10') { echo 'selected' ; } ?>>GROUP10</option>
		<option value='GROUP11' <?php if($form['Ihrss_type']=='GROUP11') { echo 'selected' ; } ?>>GROUP11</option>
		<option value='GROUP12' <?php if($form['Ihrss_type']=='GROUP12') { echo 'selected' ; } ?>>GROUP12</option>
		<option value='GROUP13' <?php if($form['Ihrss_type']=='GROUP13') { echo 'selected' ; } ?>>GROUP13</option>
		<option value='GROUP14' <?php if($form['Ihrss_type']=='GROUP14') { echo 'selected' ; } ?>>GROUP14</option>
		<option value='GROUP15' <?php if($form['Ihrss_type']=='GROUP15') { echo 'selected' ; } ?>>GROUP15</option>
		<option value='GROUP16' <?php if($form['Ihrss_type']=='GROUP16') { echo 'selected' ; } ?>>GROUP16</option>
		<option value='GROUP17' <?php if($form['Ihrss_type']=='GROUP17') { echo 'selected' ; } ?>>GROUP17</option>
		<option value='GROUP18' <?php if($form['Ihrss_type']=='GROUP18') { echo 'selected' ; } ?>>GROUP18</option>
		<option value='GROUP19' <?php if($form['Ihrss_type']=='GROUP19') { echo 'selected' ; } ?>>GROUP19</option>
		<option value='GROUP20' <?php if($form['Ihrss_type']=='GROUP20') { echo 'selected' ; } ?>>GROUP20</option>
      </select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'ihrss'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'ihrss'); ?></label>
      <select name="Ihrss_status" id="Ihrss_status">
        <option value='YES' <?php if($form['Ihrss_status']=='YES') { echo 'selected' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['Ihrss_status']=='NO') { echo 'selected' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'ihrss'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'ihrss'); ?></label>
      <input name="Ihrss_order" type="text" id="Ihrss_order" size="10" value="<?php echo $form['Ihrss_order']; ?>" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'ihrss'); ?></p>
      <input name="Ihrss_id" id="Ihrss_id" type="hidden" value="">
      <input type="hidden" name="Ihrss_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="<?php _e('Save Details', 'ihrss'); ?>" type="submit" />&nbsp; 
        <input name="publish" lang="publish" class="button" onclick="Ihrss_redirect()" value="<?php _e('Cancel', 'ihrss'); ?>" type="button" />&nbsp; 
        <input name="Help" lang="publish" class="button" onclick="Ihrss_help()" value="<?php _e('Help', 'ihrss'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('Ihrss_form_edit'); ?>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'ihrss'); ?>
	<a target="_blank" href="<?php echo WP_Ihrss_FAV; ?>"><?php _e('click here', 'ihrss'); ?></a>
</p>
</div>