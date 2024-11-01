<?php
//affiliates_crc_main_cb
function affiliates_crc_main_cb()
{
	if (is_admin() && $_GET['page'] == 'affiliates_crc/index.php')
	{
		$acrc_wraps = '<DIV id="wrap"><DIV id="content">';
		$acrc_wrape = '</DIV></DIV>';
		$acrc_t1 = '<h1 id="affiliate_recalc_head1">Affiliate Admin Page for Recalculation of Affiliate Coupon Referral</h1>';
		$acrc_t3 = '<h3>Description:</h3>';
		$acrc_t6 = '<h6 id="affiliate_recalc_head1">Brought to you by Farhan Sabir - www.stayonweb.com</h6>';
		$acrc_d1 = $acrc_t1 . $acrc_t3 . 'COUPONS -> Coupons are created in Directory Press and Assigned in ';
		$acrc_d1 .= 'Affiliates plugin to the resellers;<BR><BR>';
		$acrc_d1 .= 'REFERRALS -> Referrals are automated by the referrals plugins; However, when a COUPON is used <BR>';
		$acrc_d1 .= 'in an Order, the credit should be given to the referring affliates who owns that Coupon<BR>';
		$acrc_d1 .= 'This plugin assigns that credit to the reseller; otherwise referred to as "referrals" by the <BR>';
		$acrc_d1 .= 'Affiliates plugin. It has no other purpose.';
		$acrc_d1 .= '<BR><BR>';
		$acrc_d1 .= '<BR>* Each affiliate must have a user_id assigned';
		$acrc_d1 .= '<BR>* Each affiliate must have coupons assigned';
		$acrc_d1 .= '<BR>';
		$acrc_d1 .= '<BR>Steps:';
		$acrc_d1 .= '<BR>1. Click on Import Coupon Orders [This will import all orders with assigned affiliate coupons]';
		$acrc_d1 .= '<BR>2. Click on Apply Coupon Credit [This will display the coupon orders to apply credit]';
		$acrc_d1 .= '<BR>3. Click on Submit (below orders) to actually process these orders.';
		$acrc_d1 .= '<BR>Note: Although this plugin is harmless for other plugins, use at your own risk.';
		$acrc_d1 .= '<BR><BR><BR><BR><BR><BR><BR><BR>' . $acrc_t6;
		$acrc_joined = $acrc_wraps . $acrc_d1 . $acrc_wrape;
		echo $acrc_joined;
	}
}
?>