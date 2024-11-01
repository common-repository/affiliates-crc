<?php
function affiliates_crc_install()
{
	//validate dp and std/pro
	/* wrdp2.wp_options.option_id, option_name, option_value, autoload */
	add_option("affiliates_crc_data01", '', '', 'no');
	add_option("affiliates_crc_data02", '', '', 'no');
	add_option("affiliates_crc_pagelimit", '', '', 'no');
	//create table farhan_affiliate_recalc
}
function affiliates_crc_remove() {
	delete_option("affiliates_crc_data01");
	delete_option("affiliates_crc_data02");
	delete_option("affiliates_crc_pagelimit");
}
function affiliates_crc_validate_two() {
	global $wpdb;
	$aff_table = 'aff_affiliates';
	$dbprefix = $wpdb->base_prefix;
	$acrc_chk_aff = $wpdb->get_var('SELECT COUNT(*) FROM information_schema.tables WHERE table_name = ' . '"' . $dbprefix . 'aff_affiliates"');
	$acrc_chk_thm = get_current_theme();		//wp_get_theme( 'directorypress123' );
	if($acrc_chk_aff <> 1 && $acrc_chk_thm <> '')
	{
		echo 'This plugin requires that you have 1. Directory Press, and 2. Affiliates plugins installed';
		wp_die('Cannot activate without required plugins!');
	}
//	echo 'TEST1:' . $acrc_chk_aff . 'exists . <BR>';
//	echo 'TEST2:' . $acrc_chk_thm . 'exists . <BR>';
	
	
	
	
}
?>
<?php
//<div class="wrap">
//	<?php screen_icon(); ? >
//	<h2>Affiliate Coupon ReCalculate Plugin</h2>
//    <h4>Admin Page for Recalculation of Affiliate Coupon Referral<BR />
//    By Farhan Sabir</h4> 
//	<form method="post" action="options.php">
//		<?php wp_nonce_field('update-options'); ? >
//		Enter Text: <input name="farhan_affiliate_data" type="text" id="farhan_affiliate_data" value="<?php echo get_option('farhan_affiliate_data'); ? >" />
//		<input type="hidden" name="action" value="update" />
//		<input type="hidden" name="page_options" value="farhan_affiliate_data" />
//		<input type="submit" value="Save Changes" />
//	</form>
//
//</div>
?>