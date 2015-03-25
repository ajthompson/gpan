<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_Ihrss_display']) && $_POST['frm_Ihrss_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	$Ihrss_success = '';
	$Ihrss_success_msg = FALSE;
	
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
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'ihrss'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('Ihrss_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_Ihrss_TABLE."`
					WHERE `Ihrss_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$Ihrss_success_msg = TRUE;
			$Ihrss_success = __('Selected record was successfully deleted.', 'ihrss');
		}
	}
	
	if ($Ihrss_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $Ihrss_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2>
	<?php _e('Image horizontal reel scroll slideshow', 'ihrss'); ?>
	<a class="add-new-h2" href="<?php echo WP_IHRSS_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'ihrss'); ?></a>
	</h2>
    <div class="tool-box">
	<?php
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
		$limit = 10;
		$offset = ($pagenum - 1) * $limit;
		$sSql = "SELECT COUNT(Ihrss_id) AS count FROM ". WP_Ihrss_TABLE;
		$total = 0;
		$total = $wpdb->get_var($sSql);
		$total = ceil( $total / $limit );
	
		$sSql = "SELECT * FROM `".WP_Ihrss_TABLE."` order by Ihrss_type, Ihrss_order LIMIT $offset, $limit";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo WP_IHRSS_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_Ihrss_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Ihrss_group_item[]" /></th>
			<th scope="col"><?php _e('Image Preview', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Reference', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Type', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Target', 'ihrss'); ?></th>
            <th scope="col"><?php _e('Order', 'ihrss'); ?></th>
            <th scope="col"><?php _e('Display', 'ihrss'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Ihrss_group_item[]" /></th>
			<th scope="col"><?php _e('Image Preview', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Reference', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Type', 'ihrss'); ?></th>
			<th scope="col"><?php _e('Target', 'ihrss'); ?></th>
            <th scope="col"><?php _e('Order', 'ihrss'); ?></th>
            <th scope="col"><?php _e('Display', 'ihrss'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			foreach ($myData as $data)
			{
				if($data['Ihrss_status'] == 'YES') 
				{
					$displayisthere = TRUE; 
				}
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td align="left"><input type="checkbox" value="<?php echo $data['Ihrss_id']; ?>" name="Ihrss_group_item[]"></th>
					<td>
					<a href="<?php echo esc_html(stripslashes($data['Ihrss_path'])); ?>" target="_blank">
						<img title="Click to view image" alt="Click to view image" src="<?php echo WP_IHRSS_PLUGIN_URL; ?>/icon.png" />					
					</a>
					<span class="edit">
					&nbsp; 
					<a title="Click to Edit" href="<?php echo WP_IHRSS_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['Ihrss_id']; ?>">
						<?php _e('Edit', 'ihrss'); ?>
					</a>  
					</span>
					<span class="trash">
					&nbsp; 
					<a title="Click to Delete" onClick="javascript:Ihrss_delete('<?php echo $data['Ihrss_id']; ?>')" href="javascript:void(0);">
						<?php _e('Delete', 'ihrss'); ?>
					</a>
					</span> 
					</td>
					<td><?php echo esc_html(stripslashes($data['Ihrss_title'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Ihrss_type'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Ihrss_target'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Ihrss_order'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Ihrss_status'])); ?></td>
				</tr>
				<?php 
				$i = $i+1; 
				} 
			?>
			<?php 
			if ($displayisthere == FALSE) 
			{ 
				?><tr><td colspan="7" align="center"><?php _e('No records available.', 'ihrss'); ?></td></tr><?php 
			} 
			?>
		</tbody>
        </table>
		<?php
		  $page_links = paginate_links( array(
				'base' => add_query_arg( 'pagenum', '%#%' ),
				'format' => '',
				'prev_text' => __( ' &lt;&lt; ' ),
				'next_text' => __( ' &gt;&gt; ' ),
				'total' => $total,
				'show_all' => False,
				'current' => $pagenum
			) );
		 ?>	
		<?php wp_nonce_field('Ihrss_form_show'); ?>
		<input type="hidden" name="frm_Ihrss_display" value="yes"/>
      </form>
	  <div class="tablenav">
			<div class="alignleft actions">
			<h2>
				<a class="button add-new-h2" href="<?php echo WP_IHRSS_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'ihrss'); ?></a>
				<a class="button add-new-h2" href="<?php echo WP_IHRSS_ADMIN_URL; ?>&amp;ac=set"><?php _e('Widget setting', 'ihrss'); ?></a>
				<a class="button add-new-h2" href="<?php echo WP_Ihrss_FAV; ?>" target="_blank"><?php _e('Help', 'ihrss'); ?></a>
			</h2>
		  	</div>
			<div class="tablenav-pages">
				<span class="pagination-links"><?php echo $page_links; ?></span>
			</div>
	  </div>
	  <br />
	  <p class="description">
		<?php _e('Check official website for more information', 'ihrss'); ?>
		<a target="_blank" href="<?php echo WP_Ihrss_FAV; ?>"><?php _e('click here', 'ihrss'); ?></a>
	  </p>
	</div>
</div>