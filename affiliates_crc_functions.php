<?php
//Previously included in option
function affiliates_crc_install()
{
	//validate dp and std/pro
	/* wrdp2.wp_options.option_id, option_name, option_value, autoload */
	//add_option("affiliates_crc_data01", '', '', 'no');
	//add_option("affiliates_crc_data02", '', '', 'no');
	add_option("affiliates_crc_pagelimit", '', '', 'no');
	GenAffAlterQry();
	CreateACRCTable();	
	//create table farhan_affiliate_recalc
	
}
function affiliates_crc_remove() {
	//delete_option("affiliates_crc_data01");
	//delete_option("affiliates_crc_data02");
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


//affiliates_crc_main_cb
function affiliates_crc_main_cbc()
	{
		global $wpdb;   
		//return error
		$acrc_chk_aff = $wpdb->get_var('SELECT COUNT(*) FROM information_schema.tables WHERE table_name = ' . '"wp_aff_affiliates"');
		$acrc_chk_thm = wp_get_theme( 'directorypress' );
		$acrc_chk_affres = $wpdb->get_var($acrc_chk_aff);
		//wp_options->active_plugins
		//echo 'TEST2:' . $acrc_chk_affres . '-----' . $acrc_chk_thm->exists . '<BR>';
		print_r($acrc_chk_affres);
		if (is_admin() && $_GET['page'] == 'affiliates_crc/index.php')
		{
			echo '<DIV id="wrap">';
			echo '<DIV id="content">';
			echo '<h3 id="affiliate_recalc_head1">Farhan&acute;s Affiliate Admin Page for Recalculation of Affiliate Coupon Referral</h3>';
			echo '</div>';
			echo '</div>';
			echo 'testing';
			$acrcstr = 'select * from wp_aff_affiliates';
			$acrcrs = $wpdb->get_results($acrcstr);
			foreach ($acrcrs as $acrcobj)
			{
				print_r($acrcobj);
				echo '<HR>';
			}
		}
	}

function GenAffAlterQry()
{
	global $wpdb;
	$alterq  = 'alter table `wp_affiliates_crc_data` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_affiliates` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_affiliates_attributes` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_affiliates_users` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_hits` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_referrals` convert to character set utf8 collate utf8_unicode_ci;';
	$alterq .= 'alter table `wp_aff_robots` convert to character set utf8 collate utf8_unicode_ci;';
	$wpdb->query($alterq);
}


function ACRCArrayToObject($array) {
  $object = new stdClass();
  if (is_array($array) && count($array) > 0) {
     foreach ($array as $name=>$value) {
      $name = strtolower(trim($name));
      if (!empty($name)) {
        $object->$name = $value;
      }
     }
   }
   return $object;
}


function ACRCObjectToArray($object) {
  $array = array();
  if (is_object($object)) {
    $array = get_object_vars($object);
  }
  return $array;
} 

function IPtoDec($srcip)
{
	$iparr = split('\.', $srcip);
	//print_r($iparr);
    $ipa = $iparr[0] * 16777216;
    $ipb = $iparr[1] * 65536;
    $ipc = $iparr[2] * 256;
	$ipd = $iparr[3];
    $ipbase = $ipa + $ipb + $ipc + $ipd;
	return $ipbase;
}

function CreateACRCTable() {
	global $wpdb;
	$cacrc = 'DROP TABLE IF EXISTS `wp_aff_crc_data`;
CREATE TABLE `wp_aff_crc_data` (
  `sno` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_autoid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_cus_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_time` time DEFAULT NULL,
  `order_total` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_coupon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_couponcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_order_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_affiliate_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acrc_status` int(1) DEFAULT NULL,
  `acrc_notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acrc_import_datetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `acrc_last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sno`)
) ENGINE=MyISAM AUTO_INCREMENT=278 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
	$wpdb->query($cacrc);
//Farhan <farhan@stayonweb.com> added table create on initialize 201301060018
}
?>