<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Create a new cmp_render_html class that will extend the CMP_Coming_Soon_and_Maintenance
 */
class CMP_Coming_Soon_and_Maintenance_Render_HTML extends CMP_Coming_Soon_and_Maintenance {


    function __construct() {
        $this->jquery = FALSE;
    }
    // Render Background
    public function cmp_background( $niteoCS_banner, $themeslug ) {

        include dirname( __FILE__) . '/render/graphic-background.php';

        return $html;
    }


    // render slider
    public function cmp_slider( $themeslug ) {

        include dirname( __FILE__) . '/render/graphic-slider.php';
  

        return;
    }

    /**
     * render Overlay element.
     *
     * @since 2.8
     * @return HTML 
     **/
    public function background_overlay( $themeslug ) {

        include dirname( __FILE__) . '/render/graphic-overlay.php';

        return $html;
    }


    /**
     * render graphic Overlay text.
     *
     * @since 2.9.5
     * @return HTML 
     **/
    public function background_text_overlay( $themeslug ) {

        include dirname( __FILE__) . '/render/text-overlay.php';

        return $html;

    }


    // render Social Icons
    public function cmp_social_icons( $mode = 'icon', $title = false, $themeslug = false, $ulclass = '', $liclass = '' ) {

        include dirname( __FILE__) . '/render/social-icons.php';

        return $html;
        
    }

    // Render Logo
    public function cmp_logo( $themeslug, $class = '' ) {

        include dirname( __FILE__) . '/render/logo.php';

        return $html;
    }


    // render subscribe form
    public function cmp_subscribe_form( $label = FALSE, $firstname = FALSE, $lastname = FALSE ) {
        include dirname( __FILE__) . '/render/subscribe-form.php';

        return $html;

    }

    /**
     * returns body content.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_body() {

        include dirname( __FILE__) . '/render/content.php';

        return $html;
    }

    /**
     * render body title.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_title( $class = '' ) {

        include dirname( __FILE__) . '/render/title.php';

        return $html;

    }

    /**
     * render Google fonts link.
     *
     * @since 2.4
     * @param  array[font_family],[font_variant]
     * @return HTML 
     **/
    public function cmp_get_fonts( $heading_font = array(), $content_font = array() ) {

        include dirname( __FILE__) . '/render/fonts.php';

        return $google_fonts . PHP_EOL . $custom_font . PHP_EOL;
    }




    /**
     * render theme head SEO.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_seo() {

        include dirname( __FILE__) . '/render/seo.php';

        return $html;
    }

    /**
     * render custom CSS.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_custom_css() {

        include dirname( __FILE__) . '/render/custom-css.php';

        return $css;
    }

    /**
     * render copyright.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_copyright() {

        include dirname( __FILE__) . '/render/copyright.php';

        return $html;
    }

    /**
     * @since 3.2
     * echo CSS styles to head
     * @return void
     **/
    public function cmp_enqueue_styles( $themeslug = 'hardwork', $font_ani = false, $slider = '0', $banner_type = '2', $fa = false, $gutenberg = false ) {

        include dirname( __FILE__) . '/render/enqueue-styles.php';

        return;

    }

    /**
     * echo custom external CSS or Scripts
     *
     * @return echo HTML 
     **/
    public function cmp_head_scripts() {

        include dirname( __FILE__) . '/render/head-scripts.php';

        return;
    }


    /**
     * echo Javascripts for Themes.
     *
     * @param  array background type, themeslug
     * @return echo HTML 
     **/
    public function cmp_javascripts( $background, $themeslug ) {

        include dirname( __FILE__) . '/render/javascripts.php';

        return false;
    }

    /**
     * render Contact Form.
     *
     * @since 2.5
     * @return HTML 
     **/
    public function cmp_contact_form() {

        include dirname( __FILE__) . '/render/contact-form.php';

        return $html;
    }

    /**
     * render niteothemes info
     *
     * @since 3.2.3
     * @return HTML 
     **/
    public function cmp_render_nt_info() {

        include dirname( __FILE__) . '/render/niteothemes-info.php';

        return $html;
    }

    /**
     * get array of banner ids
     *
     * @since 3.4.8
     * @return array 
     **/
    public function cmp_get_banner_ids() {

        $banner_id = get_option('niteoCS_banner_id');
        $banner_ids = array();
                
        if ( $banner_id != '' ) {
            $banner_ids = explode(',', $banner_id);
        }

        return $banner_ids;
    }

     /**
     * helper function to render style css for custom fonts
     *
     * @since 3.5
     * @param  string,array
     * @return HTML 
     **/
    public function cmp_get_font_src( $family, $ids ) {

        foreach ( $ids as $attachment_id ) {

            $url = wp_get_attachment_url($attachment_id);
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $new_src = '';
            
            switch ($ext) {
                case 'eot':
                    $eot = 'src: url("'.esc_url($url).'");' . PHP_EOL;
                    break;
                case 'woff':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("woff"),';
                    break;
                case 'woff2':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("woff2"),';
                    break;
                case 'otf':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("opentype"),';
                    break;
                case 'ttf':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("truetype"),';
                    break;
                case 'svg':
                    $new_src = 'url("'.esc_url($url).'#filename")' . ' format("svg"),';
                    break;
                default:
                    break;
            }

            $src .=  $new_src;
        }

        return '<style>'. PHP_EOL .'@font-face {font-family: "'.$family.'";' . PHP_EOL . $eot  . 'src: ' . rtrim( $src, ',').';}'. PHP_EOL .'</style>';

    }

    /**
     * print whitelisted scripts and styles to cmp_head
     *
     * @since 3.5.6
     * @return html 
     **/
    public function cmp_wp_head() {
        // Plugin Name: Insert Headers and Footers
        if ( class_exists('InsertHeadersAndFooters') ) {
            $ihaf = new InsertHeadersAndFooters();
            $ihaf->frontendHeader();
        }

    }

    /**
     * filtered wp_footer for CMP
     *
     * @since 3.5.6
     * @return html 
     **/
    public function cmp_wp_footer() {

        include dirname( __FILE__) . '/render/footer.php';

        return;
    }

    /**
     * Helper function to return subscribe type
     *
     * @since 3.6.5
     * @access public
     * @return int
     */
    public function cmp_subscribe_type() {
        $subscribe_type = get_option('niteoCS_subscribe_type', '2');
        if ( $subscribe_type == '3' ) {
            $optin_type = $this->mailoptin_campaign_type();
            $subscribe_type = $optin_type === 'lightbox' ? '0' : $subscribe_type;
        }

        return $subscribe_type;
    }

    /**
     * Helper function to determine MailOptin campaign type
     *
     * @since 3.6.5
     * @access public
     * @return string
     */
    public function mailoptin_campaign_type() {
        
        if ( class_exists('MailOptin\Core\Repositories\OptinCampaignsRepository') ) {
            $campaign_id = get_option('niteoCS_mailoptin_selected');
            $campaign = MailOptin\Core\Repositories\OptinCampaignsRepository::get_optin_campaign_by_id($campaign_id);
            return $campaign['optin_type'];
        }

        return false;
    }

}