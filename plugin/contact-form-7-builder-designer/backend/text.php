<?php
class cf7_builder_text_backend {
	function __construct(){
        add_action( 'wpcf7_init', array($this,'wpcf7_add_form_tag_file') );
        add_action( 'wpcf7_admin_init', array($this,'wpcf7_add_tag_generator_text'), 0 );
}
function wpcf7_add_form_tag_file() {
    wpcf7_add_form_tag( array( 'text_content', 'text_content*' ),
        array($this,'wpcf7_file_form_tag_handler'), array( 'name-attr' => true ) );
}

function wpcf7_file_form_tag_handler( $tag ) {
     $value = (string) reset( $tag->values );
    $value = $tag->get_default_option( $value );

    $value = wpcf7_get_hangover( $tag->name, $value );

    return '<p>'.$value.'</p>';
}
function wpcf7_add_tag_generator_text() {
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'text_content', __( 'Text Content', 'contact-form-7' ),
        array($this,'wpcf7_tag_generator_text') );
}
function wpcf7_tag_generator_text( $contact_form, $args = '' ) {
        $args = wp_parse_args( $args, array() );
        $type = $args['id'];

        $type = 'text_content';

       

    ?>
    <div class="control-box">
    <fieldset>

    <table class="form-table">
    <tbody>
    <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Content', 'contact-form-7' ) ); ?></label></th>
        <td><input type="text" value="" placeholder="Text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
       </td>    
    </tr>


    </tbody>
    </table>
    </fieldset>
    </div>

    <div class="insert-box">
        <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

        <div class="submitbox">
        <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
        </div>

        <br class="clear" />
    </div>
    <?php
    }

}
new cf7_builder_text_backend;