<?php
/**
 * Template for rendering the `carousel-with-preview` template for gallery block in single listing page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$items_per_row = min( 3, count( $gallery_items ) );
$items_per_row_mobile = min( 2, count( $gallery_items ) );
?>

<div class="<?php echo esc_attr( $block->get_wrapper_classes() ) ?>" id="<?php echo esc_attr( $block->get_wrapper_id() ) ?>">
	<div class="element slider-padding gallery-block">
		<div class="pf-body">
			<div class="gallerySlider car-slider">
				<div class="owl-carousel galleryPreview photoswipe-gallery">

					<?php foreach ( $gallery_items as $item ): ?>
						<a class="item photoswipe-item" href="<?php echo esc_url( $item['full_size_url'] ) ?>">
						   <img src="<?php echo esc_url( $item['url'] ) ?>" alt="<?php echo esc_attr( $item['alt'] ) ?>">
						</a>
					<?php endforeach ?>

				</div>
				<div class="gallery-thumb owl-carousel" data-items="<?php echo esc_attr( $items_per_row ) ?>" data-items-mobile="<?php echo esc_attr( $items_per_row_mobile ) ?>">

					<?php foreach ( $gallery_items as $key => $item ): ?>
						<a
							class="item slide-thumb"
							data-slide-no="<?php echo esc_attr( $key ) ?>"
							href="<?php echo esc_url( $item['url'] ) ?>"
							style="background-image: url('<?php echo esc_url( $item['url'] ) ?>')"
						></a>
					<?php endforeach ?>

				</div>
				<div class="gallery-nav">
					<ul>
						<li><a href="#" class="gallery-prev-btn"><i class="mi keyboard_arrow_left"></i></a></li>
						<li><a href="#" class="gallery-next-btn"><i class="mi keyboard_arrow_right"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>