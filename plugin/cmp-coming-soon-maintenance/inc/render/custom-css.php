
<?php

$css = '';

$themeslug = $this->cmp_selectedTheme();

if ( isset($_GET['theme']) && !empty($_GET['theme']) ) {
    $themeslug  = esc_attr($_GET['theme']);
}

$banner = get_option('niteoCS_banner', '2');

if ( isset( $_GET['background'] ) && !empty($_GET['background']) ) {
    $banner = esc_attr( $_GET['background'] );

    if ( $themeslug === 'pluto' && $banner === '4' ) {
        $banner = '2';
    }
}

// add CMP CSS to all themes
ob_start();

// add blur effect if enabled
$blur = get_option('niteoCS_effect_blur', '0.0');

if (  $blur != '0.0' ) {
     ?>
    <!-- blur effect -->
    <style>
        #background-image,
        .slide-background,
        .video-banner {filter:blur(<?php echo esc_attr($blur);?>px);}
        #background-image,
        .video-banner,
        .background-overlay,
        .slide-background {transform:scale(1.1);}
        #background-wrapper, #slider-wrapper {overflow:hidden;}
        #background-image:not(.slide) {background-attachment: initial;}
    </style>

    <?php 
} ?>

<style>
    /* wp video shortcode  */
    .wp-video {margin: 0 auto;}
    .wp-video-shortcode {max-width: 100%;}
    /* google recaptcha badge */
    .grecaptcha-badge {display: none;}
</style>

<?php 
if ( get_option('niteoCS_logo_custom_size', '0') !== '0' ) { ?>
<!-- custom logo height -->
<style>
    @media screen and (min-width:1024px) {
    .logo-wrapper img {max-height: <?php echo esc_attr(get_option('niteoCS_logo_size', '0'));?>px}
    }
</style>
<?php 
}

// CHAMELEON BACKGROUND STYLES
if ( $themeslug === 'pluto' && $banner === '2' ): ?>
    <style>
        #background-image {
            -webkit-animation: chameleon 19s infinite;
                animation: chameleon 19s infinite;
        }
        @-webkit-keyframes chameleon {
        0% {background: #2ecc71;}
        25% { background: #f1c40f;}
        50% { background: #e74c3c;}
        75% {background: #3498db;}
        100% {background: #2ecc71;}
        }
        @keyframes chameleon {
        0% {background: #2ecc71; }
        25% {background: #f1c40f;}
        50% {background: #e74c3c;}
        75% {background: #3498db;}
        100% {background: #2ecc71;}
        }
    </style>
<?php endif;?>

<?php 
// check for premium themes special effects
if ( in_array( $themeslug, $this->cmp_premium_themes_installed() ) )  { 

    $effect = get_option('niteoCS_special_effect', 'disabled');

    // change effect for preview 
    if ( isset($_GET['effect']) && is_numeric($_GET['effect'])) {
        $effect = $_GET['effect'];
    }

    switch ( $effect ) {
         case 'constellation':
         case '1': ?>
            <!-- constellation effect -->
            <style>
                .particles-js-canvas-el {position: absolute; top:0; left:0;}
                <?php 
                switch ( $themeslug ) {
                    case 'frame': ?>
                         .particles-js-canvas-el {z-index: -1;}
                         #background-image, .video-banner {z-index: -3;}
                         .background-overlay {z-index: -2;}
                         <?php 
                        break;

                    case 'stylo':
                    case 'eclipse': ?>
                        .particles-js-canvas-el {z-index: 1;}
                        <?php
                        break;
                    default:
                        break;
                } ?>
            </style>
            <?php
             break;

         case 'bubbles':
         case '2': ?>
            <!-- constellation effect -->
            <style>
                canvas {position: absolute; top:0; left:0;}
            </style>
            <?php
             break;
         
         default:
             break;
    }
}

$css = ob_get_clean();

$custom_css = ( get_option('niteoCS_custom_css', '') != '' ) ? '<style>'.stripslashes( wp_filter_nohtml_kses( get_option('niteoCS_custom_css') ) ).'</style>' : '';

$css .= $custom_css;