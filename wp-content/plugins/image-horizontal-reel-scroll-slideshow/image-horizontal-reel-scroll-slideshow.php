<?php
/*
Plugin Name: Image horizontal reel scroll slideshow
Plugin URI: http://www.gopiplus.com/work/2011/05/08/wordpress-plugin-image-horizontal-reel-scroll-slideshow/
Description: Image horizontal reel scroll slideshow lets showcase images in a horizontal move style. This slideshow will pause on mouse over. The speed of the plugin gallery is customizable.
Author: Gopi Ramasamy
Version: 11.4
Author URI: http://www.gopiplus.com/work/2011/05/08/wordpress-plugin-image-horizontal-reel-scroll-slideshow/
Donate link: http://www.gopiplus.com/work/2011/05/08/wordpress-plugin-image-horizontal-reel-scroll-slideshow/
Tags: Horizontal, Image, Reel, Scroll, Slideshow, Gallery
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
 
global $wpdb, $wp_version;
define("WP_Ihrss_TABLE", $wpdb->prefix . "Ihrss_plugin");
define("WP_Ihrss_UNIQUE_NAME", "Ihrss");
define('WP_Ihrss_FAV', 'http://www.gopiplus.com/work/2011/05/08/wordpress-plugin-image-horizontal-reel-scroll-slideshow/');

if ( ! defined( 'WP_IHRSS_BASENAME' ) )
	define( 'WP_IHRSS_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_IHRSS_PLUGIN_NAME' ) )
	define( 'WP_IHRSS_PLUGIN_NAME', trim( dirname( WP_IHRSS_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_IHRSS_PLUGIN_URL' ) )
	define( 'WP_IHRSS_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_IHRSS_PLUGIN_NAME );
	
if ( ! defined( 'WP_IHRSS_ADMIN_URL' ) )
	define( 'WP_IHRSS_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=image-horizontal-reel-scroll-slideshow' );

function Ihrss() 
{
	global $wpdb;
	$Ihrss_package = "";
	$Ihrss_title = get_option('Ihrss_title');
	$Ihrss_sliderwidth = get_option('Ihrss_sliderwidth');
	$Ihrss_sliderheight = get_option('Ihrss_sliderheight');
	$Ihrss_slidespeed = get_option('Ihrss_slidespeed');
	$Ihrss_slidebgcolor = get_option('Ihrss_slidebgcolor');
	$Ihrss_slideshowgap = get_option('Ihrss_slideshowgap');
	$Ihrss_random = get_option('Ihrss_random');
	$Ihrss_type = get_option('Ihrss_type');
	
	if(!is_numeric($Ihrss_sliderwidth)) { $Ihrss_sliderwidth = 500; }
	if(!is_numeric($Ihrss_sliderheight)) { $Ihrss_sliderheight = 170; }
	if(!is_numeric($Ihrss_slidespeed)) { $Ihrss_slidespeed = 1; }
	if(!is_numeric($Ihrss_slideshowgap)) { $Ihrss_slideshowgap = 5; }
	
	$Ihrss_slideshowgaphtml = "padding-right:".$Ihrss_slideshowgap."px;";
	
	$sSql = "select Ihrss_path,Ihrss_link,Ihrss_target,Ihrss_title from ".WP_Ihrss_TABLE." where 1=1";
	if($Ihrss_type <> ""){ $sSql = $sSql . " and Ihrss_type='".$Ihrss_type."'"; }
	if($Ihrss_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY Ihrss_order"; }
	
	$data = $wpdb->get_results($sSql);
	
	$cnt = 0;
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{
			$Ihrss_path = trim($data->Ihrss_path);
			$Ihrss_link = trim($data->Ihrss_link);
			$Ihrss_target = trim($data->Ihrss_target);
			$Ihrss_title = trim($data->Ihrss_title);
			$Ihrss_package = $Ihrss_package ."IHRSS_SLIDESRARRAY[$cnt]='<a style=\"$Ihrss_slideshowgaphtml\" title=\"$Ihrss_title\"  target=\"$Ihrss_target\" href=\"$Ihrss_link\"><img alt=\"$Ihrss_title\" src=\"$Ihrss_path\" /></a>';	";
			$cnt++;
		}
		?>
		<script language="JavaScript1.2">
			var IHRSS_WIDTH = "<?php echo $Ihrss_sliderwidth."px"; ?>";
			var IHRSS_HEIGHT = "<?php echo $Ihrss_sliderheight."px"; ?>";
			var IHRSS_SPEED = <?php echo $Ihrss_slidespeed; ?>;
			IHRSS_BGCOLOR = "<?php echo $Ihrss_slidebgcolor; ?>";
			var IHRSS_SLIDESRARRAY=new Array();
			var IHRSS_FINALSLIDE ='';
			<?php echo $Ihrss_package; ?>
			var IHRSS_IMGGAP = " ";
			var IHRSS_PIXELGAP = 1;
			</script>
			<script language="JavaScript1.2" src="<?php echo WP_IHRSS_PLUGIN_URL; ?>/image-horizontal-reel-scroll-slideshow.js"></script>
		<?php
	}	
	else
	{
		_e('No images available in this Gallery Type. Please check admin setting.', 'ihrss');;
	}
}

function Ihrss_install() 
{
	global $wpdb;
	
	if($wpdb->get_var("show tables like '". WP_Ihrss_TABLE . "'") != WP_Ihrss_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS ". WP_Ihrss_TABLE . " (";
		$sSql = $sSql . "Ihrss_id INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "Ihrss_path TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "Ihrss_link TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "Ihrss_target VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_title VARCHAR( 500 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_order INT NOT NULL ,";
		$sSql = $sSql . "Ihrss_status VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_type VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_extra1 VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_extra2 VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "Ihrss_date datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( Ihrss_id )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
	
		$IsSql = "INSERT INTO `". WP_Ihrss_TABLE . "` (`Ihrss_path`, `Ihrss_link`, `Ihrss_target` , `Ihrss_title` , `Ihrss_order` , `Ihrss_status` , `Ihrss_type` , `Ihrss_date`)"; 
		
		$sSql = $IsSql . " VALUES ('".get_option('siteurl')."/wp-content/plugins/image-horizontal-reel-scroll-slideshow/images/Sing_1.jpg', '#', '_blank', 'Image 1', '1', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".get_option('siteurl')."/wp-content/plugins/image-horizontal-reel-scroll-slideshow/images/Sing_2.jpg' ,'#', '_blank', 'Image 2', '2', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".get_option('siteurl')."/wp-content/plugins/image-horizontal-reel-scroll-slideshow/images/Sing_3.jpg', '#', '_blank', 'Image 3', '1', 'YES', 'Widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".get_option('siteurl')."/wp-content/plugins/image-horizontal-reel-scroll-slideshow/images/Sing_4.jpg', '#', '_blank', 'Image 4', '2', 'YES', 'Widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	add_option('Ihrss_title', "Horizontal Slideshow");
	add_option('Ihrss_sliderwidth', "400");
	add_option('Ihrss_sliderheight', "75");
	add_option('Ihrss_slidespeed', "1");
	add_option('Ihrss_slidebgcolor', "#ffffff");
	add_option('Ihrss_slideshowgap', "10");
	add_option('Ihrss_random', "YES");
	add_option('Ihrss_type', "Widget");
}

function Ihrss_control() 
{
	echo '<p><b>';
	 _e('Image horizontal reel scroll slideshow', 'ihrss');
	echo '.</b> ';
	_e('Check official website for more information', 'ihrss');
	?> <a target="_blank" href="<?php echo WP_Ihrss_FAV; ?>"><?php _e('click here', 'ihrss'); ?></a></p><?php
}

function Ihrss_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('Ihrss_Title');
	echo $after_title;
	Ihrss();
	echo $after_widget;
}

function Ihrss_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		case 'set':
			include('pages/image-setting.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

add_shortcode( 'ihrss-gallery', 'Ihrss_shortcode' );

function Ihrss_shortcode( $atts ) 
{
	global $wpdb;
	
	$Ihrss = "";
	$Ihrss_package = "";
	
	// New code
	//[ihrss-gallery type="Widget" w="600" h="170" speed="1" bgcolor="#FFFFFF" gap="10" random="YES"]
	if ( ! is_array( $atts ) ) { return ''; }
	$Ihrss_type = $atts['type'];
	$Ihrss_sliderwidth = $atts['w'];
	$Ihrss_sliderheight = $atts['h'];
	$Ihrss_slidespeed = $atts['speed'];
	$Ihrss_slidebgcolor = $atts['bgcolor'];
	$Ihrss_slideshowgap = $atts['gap'];
	$Ihrss_random = $atts['random'];

	if(!is_numeric($Ihrss_sliderwidth)) { $Ihrss_sliderwidth = 250 ;}
	if(!is_numeric($Ihrss_sliderheight)) { $Ihrss_sliderheight = 200; }
	if(!is_numeric($Ihrss_slidespeed)) { $Ihrss_slidespeed = 1; }
	if(!is_numeric($Ihrss_slideshowgap)) { $Ihrss_slideshowgap = 5; }
	
	$Ihrss_slideshowgaphtml = "padding-right:".$Ihrss_slideshowgap."px;";
	
	$sSql = "select Ihrss_path,Ihrss_link,Ihrss_target,Ihrss_title from ".WP_Ihrss_TABLE." where 1=1";
	if($Ihrss_type <> ""){ $sSql = $sSql . " and Ihrss_type='".$Ihrss_type."'"; }
	if($Ihrss_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY Ihrss_order"; }
	
	$data = $wpdb->get_results($sSql);
	
	$cnt = 0;
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{
			$Ihrss_path = trim($data->Ihrss_path);
			$Ihrss_link = trim($data->Ihrss_link);
			$Ihrss_target = trim($data->Ihrss_target);
			$Ihrss_title = trim($data->Ihrss_title);
			$Ihrss_package = $Ihrss_package ."IHRSS_SLIDESRARRAY[$cnt]='<a style=\"$Ihrss_slideshowgaphtml\" title=\"$Ihrss_title\" target=\"$Ihrss_target\" href=\"$Ihrss_link\"><img alt=\"$Ihrss_title\" src=\"$Ihrss_path\" /></a>';	";
			$cnt++;
		}
		
		$Ihrss_pluginurl = get_option('siteurl') . "/wp-content/plugins/image-horizontal-reel-scroll-slideshow/";
	
		$Ihrss = $Ihrss .'<script language="JavaScript1.2">';
		$Ihrss = $Ihrss .'var IHRSS_WIDTH = "'.$Ihrss_sliderwidth.'px"; ';
		$Ihrss = $Ihrss .'var IHRSS_HEIGHT = "'.$Ihrss_sliderheight.'px"; ';
		$Ihrss = $Ihrss .'var IHRSS_SPEED = '. $Ihrss_slidespeed.'; ';
		$Ihrss = $Ihrss .'var IHRSS_BGCOLOR = "'.$Ihrss_slidebgcolor.'"; ';
		$Ihrss = $Ihrss .'var IHRSS_SLIDESRARRAY=new Array(); ';
		$Ihrss = $Ihrss .'var IHRSS_FINALSLIDE =" "; ';
		$Ihrss = $Ihrss .$Ihrss_package;
		$Ihrss = $Ihrss .'var IHRSS_IMGGAP = " "; ';
		$Ihrss = $Ihrss .'var IHRSS_PIXELGAP = 1; ';
		$Ihrss = $Ihrss .'</script>';
		$Ihrss = $Ihrss .'<script language="JavaScript1.2" src="'.$Ihrss_pluginurl.'/image-horizontal-reel-scroll-slideshow.js"></script>';
	}	
	else
	{
		$Ihrss = $Ihrss . __('No images available in this Gallery Type. Please check admin setting.', 'ihrss');
	}
	return $Ihrss;
}

function Ihrss_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page(__('Image horizontal reel scroll slideshow', 'ihrss'), 
							__('Image horizontal reel scroll slideshow', 'ihrss'), 'manage_options', "image-horizontal-reel-scroll-slideshow", 'Ihrss_admin_options' );
	}
}

function Ihrss_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Image-horizontal-reel-scroll-slideshow', __('Image horizontal reel scroll slideshow', 'ihrss'), 'Ihrss_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Image-horizontal-reel-scroll-slideshow', array(__('Image horizontal reel scroll slideshow', 'ihrss'), 'widgets'), 'Ihrss_control');
	} 
}

function Ihrss_deactivation() 
{
	// No action required.
}

function Ihrss_textdomain() 
{
	  load_plugin_textdomain( 'ihrss', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'Ihrss_textdomain');
add_action('admin_menu', 'Ihrss_add_to_menu');
add_action("plugins_loaded", "Ihrss_init");
register_activation_hook(__FILE__, 'Ihrss_install');
register_deactivation_hook(__FILE__, 'Ihrss_deactivation');
?>