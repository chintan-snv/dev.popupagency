<?php
/**
 * Bookmark button for the preview card template.
 *
 * @since 2.2
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<li data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php esc_attr_e( 'Bookmark', 'my-listing' ) ?>">
    <a class="c27-bookmark-button <?php echo mylisting()->bookmarks()->is_bookmarked( $listing->get_id(), get_current_user_id() ) ? 'bookmarked' : '' ?>"
       data-listing-id="<?php echo esc_attr( $listing->get_id() ) ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('c27_bookmark_nonce') ) ?>">
       <i class="mi favorite_border"></i>
    </a>
</li>