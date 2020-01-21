<?php
class cf7_builder_frontend {
	function __construct(){
		add_action("wp_enqueue_scripts",array($this,"add_lib"));
	}
	 function add_lib(){
            wp_enqueue_style( 'cf7-builder', CT7_BUILDER_PLUGIN_URL."frontend/css/cf7-builder.css" );
            wp_enqueue_style( 'fontawesome',CT7_BUILDER_PLUGIN_URL."font-awesome/css/font-awesome.min.css");
    }

}
new cf7_builder_frontend;