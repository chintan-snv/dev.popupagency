<?php
/**
 * Template for rendering a colored list.
 *
 * @since 1.0
 * @var  $items { name, icon, color, text_color, link }
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="listing-details item-count-<?php echo count( $items ) ?>">
	<ul>

		<?php foreach ( $items as $item ): ?>
			<li>
				<a href="<?php echo esc_url( $item['link'] ) ?>">
					<span class="cat-icon" style="background-color: <?php echo esc_attr( $item['color'] ) ?>;">
                        <?php echo $item['icon'] ?>
					</span>
					<span class="category-name"><?php echo esc_html( $item['name'] ) ?></span>
				</a>
			</li>
		<?php endforeach ?>

	</ul>
</div>