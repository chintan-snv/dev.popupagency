<?php

$ver = $this->cmp_theme_version( $themeslug );

if ( $gutenberg === true ) {
    echo '<link rel="stylesheet" href="'.includes_url('/css/dist/block-library/style.min.css').'" type="text/css" media="all" />' . PHP_EOL;
}

// theme stylesheet
echo '<link rel="stylesheet" href="' . $this->cmp_themeURL( $themeslug ) . $themeslug.'/style.css?v='.$ver . '" type="text/css" media="all">' . PHP_EOL;

if ( $font_ani !== false || $font_ani !== 'none' ) {
    echo '<link rel="stylesheet" href="'. esc_url( $this->cmp_asset_url('/css/animate.min.css') ) . '">' . PHP_EOL;
}

if ( $slider == '1' && ($banner_type == '0' || $banner_type == '1') ) {
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet">' . PHP_EOL;
}

if ( $fa === true ) {
    echo '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >' . PHP_EOL;
}