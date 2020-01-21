<?php
/**
 * Template for rendering a `countdown` block in single listing page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

// get field value
$countdown_date = $listing->get_field( $block->get_prop( 'show_field' ) );

// validate
if ( ! ( $countdown_date && strtotime( $countdown_date ) ) ) {
	return;
}

$end_date = new \DateTime( $countdown_date, c27()->get_timezone() );
$remain = $end_date->diff( new \DateTime );
?>

<div class="<?php echo esc_attr( $block->get_wrapper_classes() ) ?>" id="<?php echo esc_attr( $block->get_wrapper_id() ) ?>">
	<div class="element countdown-box countdown-block">
		<div class="pf-head">
			<div class="title-style-1">
				<i class="<?php echo esc_attr( $block->get_icon() ) ?>"></i>
				<h5><?php echo esc_html( $block->get_title() ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<ul class="countdown-list">
				<li>
					<p><?php echo $end_date && $remain->invert ? sprintf('%02d', $remain->format('%a')) : '00' ?></p>
					<span><?php _e( 'Days', 'my-listing' ) ?></span>
				</li>
				<li>
					<p><?php echo $end_date && $remain->invert ? $remain->format('%H') : '00' ?></p>
					<span><?php _e( 'Hours', 'my-listing' ) ?></span>
				</li>
				<li>
					<p><?php echo $end_date && $remain->invert ? $remain->format('%I') : '00' ?></p>
					<span><?php _e( 'Minutes', 'my-listing' ) ?></span>
				</li>
			</ul>
		</div>
	</div>
</div>
