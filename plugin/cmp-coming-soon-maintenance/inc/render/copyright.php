<?php 

if ( get_option( 'niteoCS_copyright', 'Made by <a href="https://niteothemes.com">NiteoThemes</a> with love.' ) !== '' ) { 
    $copyright = stripslashes( get_option('niteoCS_copyright', 'Made by <a href="https://niteothemes.com">NiteoThemes</a> with love.') );
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
    );

    $html = '<p class="copyright">' . wp_kses( $copyright, $allowed_html ) . '</p>';
    
}