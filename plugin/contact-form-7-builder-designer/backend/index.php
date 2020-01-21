<?php

/**
* 
*/
class Cf7_builder_backend{
    
    function __construct(){
        # code...
        add_filter("wpcf7_editor_panels",array($this,"custom_form"));
        add_action( 'admin_enqueue_scripts', array($this, 'add_lib' ));
        add_action('save_post', array($this,'save_meta_box'));

    }
    function custom_form($panels){
       $panels["form-panel"] = array(
                'title' => __( 'Form', 'contact-form-7' ),
                'callback' => array($this,"wpcf7_editor_panel_form_builder") );
        return $panels;
    }
    function add_lib(){
        if( isset($_GET["page"]) ):
            if( $_GET["page"] == "wpcf7-new" || $_GET["page"] == "wpcf7" ):   
                     wp_enqueue_style( 'cf7-builder',CT7_BUILDER_PLUGIN_URL."backend/css/cf7-builder.css",array("thickbox") );
            wp_enqueue_style( 'cf7-icon_picker',CT7_BUILDER_PLUGIN_URL."backend/icon_picker/css/fontawesome-iconpicker.min.css",array("thickbox") );
            wp_enqueue_style( 'fontawesome',CT7_BUILDER_PLUGIN_URL."font-awesome/css/font-awesome.min.css",array("thickbox") );
            wp_enqueue_script( 'cf7-builder', CT7_BUILDER_PLUGIN_URL.'backend/js/cf7-builder.js',array("jquery","jquery-ui-core","jquery-ui-draggable","jquery-ui-droppable","jquery-ui-sortable","thickbox") );
            wp_enqueue_script( 'cf7-icon_picker', CT7_BUILDER_PLUGIN_URL.'backend/icon_picker/js/fontawesome-iconpicker.min.js',array("jquery","jquery-ui-core","jquery-ui-draggable","jquery-ui-droppable","jquery-ui-sortable","thickbox") );
        endif;
        endif;
       
    }
    function save_meta_box($post_id){
       $cf7_builder_type = @$_POST["cf7_builder_type"];
       add_post_meta($post_id, '_cf7_builder_type', $deal_expired,true) or update_post_meta($post_id, '_cf7_builder_type', $cf7_builder_type);
    }
    function wpcf7_editor_panel_form_builder($post){
    ?>
        <h2><?php echo esc_html( __( 'Form', 'contact-form-7' ) ); ?></h2>
        <?php if ( is_plugin_active( 'contact-form-7-multistep-pro/index.php' )  ) { ?>
        <p><a href="<?php echo remove_query_arg("builder"); ?>">Use Form Multistep </a></p>
        <?php } ?>
        <?php
            $tag_generator = WPCF7_TagGenerator::get_instance();
             ob_start();
                $tag_generator->print_buttons();
            $tags = ob_get_clean();
            ob_end_flush();
           $tags = preg_replace('#<span id="tag-generator-list">#', "", $tags); 
           $tags = preg_replace('#</span>#', "", $tags); 
           $tags = preg_replace("#<a#", "<li><a", $tags); 
           $tags = preg_replace("#/a>#", "/a></li>", $tags); 
        $type = get_post_meta($post->id,"_cf7_builder_type",true);
        ?>
        <ui class="cf7-tag-generator-list">
             <li id="cf7-builder-row" ><a href="#" class="button" title="">Row</a></li>
            <?php echo $tags; ?>
        </ui>
        <div class="clear"></div><!-- /.clear -->
        <div>
            <div class="form-body">
                <input type="hidden" name="cf7_builder_type" id="cf7_builder_type" value="<?php echo $type; ?>">
                <div class="wp-editor-tabs-cf7">
                    <button type="button" class="cf7-switch-builder <?php if( $type == 1){ echo "active";} ?>"  >Visual</button>
                    <button type="button" class="cf7-switch-html <?php if( $type != 1){ echo "active";} ?>">Text</button>
                </div>
                <?php 
                    $content = str_replace('<div class="cf7-builder-container-row1 cf7-builder-container-row-inner cf7-main-builder dropped ui-sortable-handle ui-sortable-placeholder" ></div>',"",$post->prop( 'form' ));
                    //var_dump($content);
                ?>
                <textarea id="wpcf7-form" name="wpcf7-form" cols="100" rows="24" class="large-text code <?php if( $type == 1){ echo "hidden";} ?>"" data-config-field="form.body"><?php echo esc_textarea( $content ); ?></textarea>
                <div class="cf7-builder-container-row <?php if( $type != 1){ echo "hidden";} ?>" id="cf7-builder-container-row">
                <?php 
                    echo $content;
                ?>
                </div>
                
            </div>
        </div>


        <?php add_thickbox(); ?>
        <div id="cf7_add_thickbox" style="display:none;">
            <input type="hidden" value="" id="cf7_pu_type">
             <div class="insert-box">
                <div class="cf7_col_data"> 
                            <?php _e("Row layout",CT7_BUILDER_DOMAIN) ?>
                        <ul class="cf7-row-cl">
                            <li data-col="12">1/1</li>
                            <li data-col="6+6">1/2+1/2</li>
                            <li data-col="8+4">2/3+1/3</li>
                            <li data-col="4+4+4">1/3+1/3+1/3</li>
                            <li data-col="3+3+3+3">1/4+1/4+1/4+1/4</li>
                            <li data-col="3/9">1/4+3/4</li>
                            <li data-col="3+6+3">1/4+2/4+1/4</li>
                            <li data-col="10+2">5/6+1/6</li>
                        </ul>
                        <?php _e("Select row layout from predefined options.
                        Enter custom layout for your row",CT7_BUILDER_DOMAIN) ?>
                        <input type="text" class="tag code" id="cf7-builder-id-value">
                </div>
                <div class="cf7_col_text">           
                   <?php _e("Label",CT7_BUILDER_DOMAIN) ?> <input type="text" class="tag code" id="cf7-builder-id-label">
                   <?php _e("Icon",CT7_BUILDER_DOMAIN) ?> <input type="text" class="tag code" id="cf7-builder-id-icon">
                    <textarea id="cf7-builder-id-value-content" cols="50" rows="5" class="code " ></textarea>
                </div>
                <input type="hidden" id="cf7-builder-id-col">

                <div class="submitbox">
                    <input type="button" class="button button-primary update-cf7" value="Update">
                </div>

                <br class="clear">
            </div>
        </div>
        
        <?php
    }
}
new Cf7_builder_backend;