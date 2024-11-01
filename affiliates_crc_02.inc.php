<?php
function affiliates_crc_02_cbc() {
	$tmpcnt = 0;
	echo '<div id="wrap"><div id="content">';
	global $wpdb;
	include AFFILIATES_CRC_PATH . "affiliates_crc_functions.php";
	$acrc_name = $wpdb->base_prefix . 'aff_crc_data';
	$ref_name = $wpdb->base_prefix . 'aff_referrals';
	//$acrc_sql = 'select * from ' . $acrc_name . ' where acrc_status = 0 order by sno;';
	//$acrc_sql2 = 'SELECT acrc.*, aff.name as acrc_aff_name, affu.user_id as acrc_aff_uid ';
	//$acrc_sql2 .= 'FROM wp_aff_crc_data AS acrc, wp_aff_affiliates AS aff, wp_aff_affiliates_users AS affu ';
	//$acrc_sql2 .= 'where acrc.ref_affiliate_id = aff.affiliate_id and aff.affiliate_id = affu.user_id';
	$acrc_sql3 = 'SELECT acrc.*, aff.name as acrc_aff_name, affu.user_id as acrc_aff_uid, wpu.user_login as acrc_wpu_login ';
	$acrc_sql3 .= 'FROM wp_aff_crc_data AS acrc, wp_aff_affiliates AS aff, wp_aff_affiliates_users AS affu, wp_users as wpu ';
	$acrc_sql3 .= 'where acrc.ref_affiliate_id = aff.affiliate_id and aff.affiliate_id = affu.user_id and affu.user_id = wpu.ID';
	$acrc_sql3 .= ' AND acrc.acrc_status = 0;';
	$acrc_sql4 = 'SELECT acrc.*, aff.name as acrc_aff_name, affu.user_id as acrc_aff_uid, wpu.user_login as acrc_wpu_login ';
	$acrc_sql4 .= 'FROM wp_aff_crc_data AS acrc, wp_aff_affiliates AS aff, wp_aff_affiliates_users AS affu, wp_users as wpu ';
	$acrc_sql4 .= 'where acrc.ref_affiliate_id = aff.affiliate_id and acrc.ref_affiliate_id = affu.affiliate_id ';
	$acrc_sql4 .= 'and affu.user_id = wpu.ID ';
	$acrc_sql4 .= ' AND acrc.acrc_status = 0;';
	$acrc_result = $wpdb->get_results($acrc_sql4);
	
	$tstart = '<table width="950" border="1" cellspacing="1" cellpadding="1">';
	$thead = '<tr><th>S.No.</th><th>OrderSNo</th><th>Cust</th><th>OrderID</th><th>Order Date/Time</th>';
	$thead .= '<th>Order Total</th><th>Discount</th><th>Total Value</th><th>Order Status</th>';
	$thead .= '<th>Coupon Code</th><th>Affiliate ID</th><!--<th>Imported On</th>--></tr>';
	if(!$acrc_result)
	{
		echo 'Could not find any orders to apply credit for!!!';
		$dddd = unserialize('a:1:{s:9:"user-name";a:3:{s:5:"title";s:8:"Username";s:6:"domain";s:17:"affiliate-members";s:5:"value";s:6:"AFF-99";}}');
		//print_r($dddd);
		exit; //
	}else{
		$trow = '';
		$acrc_ins_qry = '';
		foreach($acrc_result as $row)
		{
			$rowarr = ACRCObjectToArray($row);
			$acrc_order_total = $rowarr['order_total'];
			$acrc_order_total += $rowarr['order_coupon'];
			//print_r($rowarr);
			$trow .= '	<tr align="center">';
			$trow .= '		<td>' . $rowarr['sno'] . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_autoid'] . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_cus_id'] . '&nbsp;</td>';
			$trow .= '		<td width="220">' . $rowarr['order_order_id'] . '&nbsp;</td>';
			$trow .= ' 		<td width="150">' . $rowarr['order_date'] . ' ' . $rowarr['order_time'] . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_total'] . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_coupon'] . '&nbsp;</td>';
			$trow .= '		<td>' . $acrc_order_total . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_status'] . '&nbsp;</td>';
			$trow .= '		<td>' . $rowarr['order_couponcode'] . '&nbsp;</td>';
			$trow .= '		<td width="150">' . $rowarr['ref_affiliate_id'] . '/uid:' . $rowarr['acrc_aff_uid'];
			$trow .= '/' . $rowarr['acrc_wpu_login'] . '&nbsp;</td>';
			//$trow .= '		<td width="150">' . $rowarr['acrc_import_datetime'] . '&nbsp;</td>';
			$trow .= '	</tr>';
			//extra
			//acrc_aff_uid
			//acrc_wpu_login
			$trow .= '<tr><td colspan="11">';
				$tmpdata3 = array('title'=>'Username', 'domain'=>'affiliate-members', 'value'=>$rowarr['acrc_wpu_login']); 
				$tmpdata2 = array('user-name'=>$tmpdata3);
				$tmpdatas = serialize($tmpdata2);
				$wpdb->show_errors();
				$insrres = $wpdb->insert( 
					$ref_name, 
					array( 
						'affiliate_id' => $rowarr['ref_affiliate_id'], 
						'post_id' => 0, 
						'datetime' => date("Y/m/d H:i:s"), 
						'description' => 'Affiliates CRC-' . $rowarr['order_order_id'], 
						'ip' => $rowarr['order_order_ip'], 
						'user_id' => 0, 
						'data' => $tmpdatas, 
						'status' => 'accepted' 
					) 
				);
				//update acrc
				$insrresid = $wpdb->get_var( $wpdb->prepare( "SELECT LAST_INSERT_ID()" ) );
				if($insrres == 1 && $insrresid)
				{
					$tmpcnt++;
					$acrc_upd_qry = 'update wp_aff_crc_data set acrc_status = 1 where order_order_id = "' . $rowarr['order_order_id'] . '"';
					$wpdb->query($acrc_upd_qry);
					$tmps += 10;
					//Add 10 seconds to time before each insert, otherwise gives a duplicate error FARHAN:201212221327					
					$tmpdate 	 = date('Y-m-d H:i:s');
					$tmpnewtime = strtotime($tmpdate . ' + ' . $tmps . ' seconds');
					//INSERT INTO `wp_aff_hits` (`affiliate_id`, `date`, `time`, `datetime`, `ip`, `ipv6`, `is_robot`, `user_id`, `count`, `type`) VALUES (9, '2012-12-22', '12:34:36', '2012-12-22 12:34:42', 416598114, NULL, 0, NULL, 1, NULL);
					$acrc_affhits_qry  = 'INSERT INTO `wp_aff_hits` (`affiliate_id`, `date`, `time`, `datetime`,';
					$acrc_affhits_qry .= '`ip`, `ipv6`, `is_robot`, `user_id`, `count`, `type`) VALUES (';
					$acrc_affhits_qry .= $rowarr['ref_affiliate_id'] . ', "' ;
					$acrc_affhits_qry .= date("Y/m/d", $tmpnewtime) . '", "' . date("H:i:s", $tmpnewtime) . '", "'; 
					$acrc_affhits_qry .= date("Y/m/d H:i:s", $tmpnewtime) . '", ' . $rowarr['order_order_ip'] ;
					$acrc_affhits_qry .= ', NULL, 0, NULL, 1, NULL);';
					$wpdb->query($acrc_affhits_qry);
					echo '<H4>Referral Applied for (' . $insrresid . ') Order: ' . $rowarr['order_order_id'] . '...</H4>';
				}else{
					echo '<H4>Something went wrong, referral data coulnd not be inserted!';
				}
				//$insares = $wpdb->insert(
				
				
				
			$trow .= '</td></tr>';
			//}
		}
		echo $tstart . $thead . $trow . '</table>';
	}

//SUBMIT FORM	
$acrc_02_form  = '<form name="form1" method="post" action="">';
$acrc_02_form .= '    <label for="submit">Process Orders</label>';
$acrc_02_form .= '    <input type="submit" name="submit" id="submit" value="Submit" accesskey="1" tabindex="1">';
$acrc_02_form .= '</form>';
//echo $acrc_02_form;


//if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
//    echo 'Submit->SaveData/UpdateAcrc';
//} else {
//    echo ''; 
//}
	
	
echo '</div></div>';
}
?>