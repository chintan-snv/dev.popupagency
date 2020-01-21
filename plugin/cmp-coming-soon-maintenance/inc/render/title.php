<?php 

global $allowedposttags;

$html = stripslashes( get_option('niteoCS_body_title', 'SOMETHING IS HAPPENING!') );

// wrap text between stars in title in span for future formatting
$title_array = explode('*', $html);

if ( count($title_array) == 3 ) {
    $html = '<span class="cmp-title light">' . $title_array[0] . '</span><span class="cmp-title bold">' . $title_array[1] . '</span><span class="cmp-title light">' . $title_array[2] . '</span>';
}

$html = ( $html == '' ) ? '' : '<h2 class="cmp-title animated '. $class .'">' . wp_kses( $html, $allowedposttags ) . '</h2>';