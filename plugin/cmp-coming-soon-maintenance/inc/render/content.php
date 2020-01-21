<?php

$html = stripslashes( get_option('niteoCS_body', '') );

if ( isset($GLOBALS['wp_embed']) ) {
    $html = $GLOBALS['wp_embed']->autoembed( $html );
}

$html = wpautop( do_shortcode( $html ) );