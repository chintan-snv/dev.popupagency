<?php

//Remove Options for Uninstall

if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit;

	delete_option("WPOP_check_enable");
	delete_option("WPOP_adv_enable");
	delete_option("WPOP_html_enable");
	delete_option("WPOP_comm_enable");
	delete_option("WPOP_emoj_enable");
	delete_option("WPOP_migr_enable");
	delete_option("WPOP_shor_enable");
	delete_option("WPOP_quer_enable");
	delete_option("WPOP_foot_enable");
	delete_option("WPOP_async_enable");
	delete_option("WPOP_lazy_enable");
	delete_option("WPOP_cach_enable");
	delete_option("WPOP_embd_enable");
	delete_option("WPOP_admn_enable");

	
	//***