<?php
//affiliates_crc_01_cbc
//1st option: Import Coupon Orders
function affiliates_crc_01_cbc()
{
	global $wpdb;
	include AFFILIATES_CRC_PATH . "affiliates_crc_functions.php";
	$prefix = $wpdb->base_prefix;
	$acrc_qry_mix = 'SELECT od.*, affatt.*, affusr.*
					FROM wp_orderdata od, wp_aff_affiliates_attributes affatt, wp_aff_affiliates_users affusr
					WHERE od.order_couponcode = affatt.attr_value and affatt.affiliate_id = affusr.affiliate_id';
	$acrc_res_mix = $wpdb->get_results($acrc_qry_mix);
	$rectot = 0;
	$recins = 0;
	foreach($acrc_res_mix as $row)
	{
		$rectot++;
		$rowarr = ACRCObjectToArray($row);
		$acrc_qry_tmp = "select count(order_order_id) from wp_aff_crc_data ";
		$acrc_qry_tmp .= " where order_order_id = '" . $row->order_id . "'";
		$acrc_tmp1 = $wpdb->get_var($acrc_qry_tmp);
		if ($acrc_tmp1 == 0)
		{
			$recins++;
			$oip=IPtoDec($row->order_ip);
			$acrc_datetime = date("Y/m/d H:i:s");
			$acrc_qry_ins = 'INSERT INTO ' . $prefix . 'aff_crc_data ';
			$acrc_qry_ins .= ' (order_autoid,order_cus_id,order_order_id,';
			$acrc_qry_ins .= ' order_date,order_time,';
			$acrc_qry_ins .= ' order_total, order_coupon, order_couponcode, ';
			$acrc_qry_ins .= ' order_status, order_order_ip, ref_affiliate_id, acrc_import_datetime, acrc_status ' . ') ';
			$acrc_qry_ins .= ' VALUES (';
			$acrc_qry_ins .= $row->autoid . ',' . $row->cus_id . ',"' . $row->order_id . $acrc_rand . '",';
			$acrc_qry_ins .= '"' . $row->order_date . '", "' . $row->order_time . '", ';
			$acrc_qry_ins .= $row->order_total . ',' . $row->order_coupon . ',"' . $row->order_couponcode . '",';
			$acrc_qry_ins .= $row->order_status . ',' . $oip . ',' . $row->affiliate_id . ', "' . $acrc_datetime . '", 0';
			$acrc_qry_ins .= ')';
			$resins = $wpdb->query($acrc_qry_ins);
			//print_r($rowarr);
			if ($resins)
			{
				echo '<BR>Order ID: ' . $row->order_id . ' inserted into ACRC Table.';
			}else{
				echo '<BR>Order already exists';
			}
		}
	}
	echo '<BR><BR>Total Orders Scanned: ' . $rectot;
	echo '<BR>Total Orders Inserted: ' . $recins;
	return;	
}
?>