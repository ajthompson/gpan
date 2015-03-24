<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Image horizontal reel scroll slideshow', 'ihrss'); ?></h2>
	<h3><?php _e('Widget setting', 'ihrss'); ?></h3>
    <?php
	$Ihrss_title = get_option('Ihrss_title');
	$Ihrss_sliderwidth = get_option('Ihrss_sliderwidth');
	$Ihrss_sliderheight = get_option('Ihrss_sliderheight');
	$Ihrss_slidespeed = get_option('Ihrss_slidespeed');
	$Ihrss_slidebgcolor = get_option('Ihrss_slidebgcolor');
	$Ihrss_slideshowgap = get_option('Ihrss_slideshowgap');
	$Ihrss_random = get_option('Ihrss_random');
	$Ihrss_type = get_option('Ihrss_type');
	
	if (isset($_POST['Ihrss_submit'])) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('Ihrss_form_setting');
			
		$Ihrss_title = stripslashes($_POST['Ihrss_title']);
		$Ihrss_sliderwidth = stripslashes($_POST['Ihrss_sliderwidth']);
		$Ihrss_sliderheight = stripslashes($_POST['Ihrss_sliderheight']);
		$Ihrss_slidespeed = stripslashes($_POST['Ihrss_slidespeed']);
		$Ihrss_slidebgcolor = stripslashes($_POST['Ihrss_slidebgcolor']);
		$Ihrss_slideshowgap = stripslashes($_POST['Ihrss_slideshowgap']);
		$Ihrss_random = stripslashes($_POST['Ihrss_random']);
		$Ihrss_type = stripslashes($_POST['Ihrss_type']);

		update_option('Ihrss_title', $Ihrss_title );
		update_option('Ihrss_sliderwidth', $Ihrss_sliderwidth );
		update_option('Ihrss_sliderheight', $Ihrss_sliderheight );
		update_option('Ihrss_slidespeed', $Ihrss_slidespeed );
		update_option('Ihrss_slidebgcolor', $Ihrss_slidebgcolor );
		update_option('Ihrss_slideshowgap', $Ihrss_slideshowgap );
		update_option('Ihrss_random', $Ihrss_random );
		update_option('Ihrss_type', $Ihrss_type );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'ihrss'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<script language="JavaScript" src="<?php echo WP_IHRSS_PLUGIN_URL; ?>/pages/setting.js"></script>
    <form name="Ihrss_form" method="post" action="">
      <label for="tag-title"><?php _e('Enter widget title', 'ihrss'); ?></label>
      <input name="Ihrss_title" id="Ihrss_title" type="text" value="<?php echo $Ihrss_title; ?>" size="80" />
      <p><?php _e('Enter widget title, Only for widget.', 'ihrss'); ?></p>
      
	  <label for="tag-width"><?php _e('Enter width', 'ihrss'); ?></label>
      <input name="Ihrss_sliderwidth" id="Ihrss_sliderwidth" type="text" value="<?php echo $Ihrss_sliderwidth; ?>" />
      <p><?php _e('Enter widget width, only number. (Example: 600)', 'ihrss'); ?></p>
      
	  <label for="tag-height"><?php _e('Enter height', 'ihrss'); ?></label>
      <input name="Ihrss_sliderheight" id="Ihrss_sliderheight" type="text" value="<?php echo $Ihrss_sliderheight; ?>" />
      <p><?php _e('Enter widget height, only number. (Example: 170)', 'ihrss'); ?></p>
      
	  <label for="tag-title"><?php _e('Enter slide speed', 'ihrss'); ?></label>
      <input name="Ihrss_slidespeed" id="Ihrss_slidespeed" type="text" value="<?php echo $Ihrss_slidespeed; ?>" />
      <p><?php _e('This box is to manage scroller speed. (Example: 1)', 'ihrss'); ?></p>
      
	  <label for="tag-height"><?php _e('Enter slide bgcolor', 'ihrss'); ?></label>
      <input name="Ihrss_slidebgcolor" id="Ihrss_slidebgcolor" type="text" value="<?php echo $Ihrss_slidebgcolor; ?>" />
      <p><?php _e('Background color of the slideshow. (Example: #ffffff)', 'ihrss'); ?></p>
      
	  <label for="tag-height"><?php _e('Enter slideshow gap', 'ihrss'); ?></label>
      <input name="Ihrss_slideshowgap" id="Ihrss_slideshowgap" type="text" value="<?php echo $Ihrss_slideshowgap; ?>" />
      <p><?php _e('This is pixels gap between each image in slideshow. Enter only number. (Example: 5)', 'ihrss'); ?></p>
	  
	  <label for="tag-height"><?php _e('Enter random display', 'ihrss'); ?></label>
      <input name="Ihrss_random" id="Ihrss_random" type="text" value="<?php echo $Ihrss_random; ?>" />
      <p><?php _e('This option is to retrieve the images in random order. (Enter: YES/NO Only)', 'ihrss'); ?></p>
	  
	  <label for="tag-height"><?php _e('Select gallery group (Type)', 'ihrss'); ?></label>
      <!--<input name="Ihrss_type" id="Ihrss_type" type="text" value="<?php //echo $Ihrss_type; ?>" />-->
	  <select name="Ihrss_type" id="Ihrss_type">
	  <option value="">Select</option>
	  <?php
		$sSql = "SELECT distinct(Ihrss_type) as Ihrss_type FROM `".WP_Ihrss_TABLE."` order by Ihrss_type";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$thisselected = "";
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		foreach ($myDistinctData as $DistinctData)
		{
			if(strtoupper($DistinctData['Ihrss_type']) == strtoupper($Ihrss_type)) 
			{ 
				$thisselected = "selected='selected'" ; 
			}
			?>
			<option value='<?php echo strtoupper($DistinctData['Ihrss_type']); ?>' <?php echo $thisselected; ?>><?php echo strtoupper($DistinctData['Ihrss_type']); ?></option>
			<?php
			$thisselected = "";
		}
		?>
		</select>
      <p><?php _e('This field is to group the images. Select your group name to fetch the images for widget. (Example: GROUP1)', 'ihrss'); ?></p>
	  <p class="submit">
		<input name="Ihrss_submit" id="Ihrss_submit" class="button" value="<?php _e('Submit', 'ihrss'); ?>" type="submit" />&nbsp; 
		<input name="publish" lang="publish" class="button" onclick="Ihrss_redirect()" value="<?php _e('Cancel', 'ihrss'); ?>" type="button" />&nbsp;
		<input name="Help" lang="publish" class="button" onclick="Ihrss_help()" value="<?php _e('Help', 'ihrss'); ?>" type="button" />
	 </p>
	  <?php wp_nonce_field('Ihrss_form_setting'); ?>
    </form>
  </div>
  <p class="description">
  	<?php _e('Check official website for more information', 'ihrss'); ?>
	<a target="_blank" href="<?php echo WP_Ihrss_FAV; ?>"><?php _e('click here', 'ihrss'); ?></a>
  </p>
</div>