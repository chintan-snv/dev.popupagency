<?php
/**
 * `Report Listing` quick action.
 *
 * @since 2.0
 */

$target_modal = is_user_logged_in() ? '#report-listing-modal' : '#sign-in-modal';
?>

<li id="<?php echo esc_attr( $action['id'] ) ?>" class="<?php echo esc_attr( $action['class'] ) ?>">
    <a href="#" data-toggle="modal" data-target="<?php echo $target_modal ?>">
    	<?php echo c27()->get_icon_markup( $action['icon'] ) ?>
    	<span><?php echo $action['label'] ?></span>
    </a>
</li>