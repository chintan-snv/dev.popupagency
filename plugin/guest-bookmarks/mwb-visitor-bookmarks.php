<?php
do_action( 'case27_woocommerce_bookmarks_before' );

$_page = isset( $_GET['_page'] ) ? (int) $_GET['_page'] : 1;
$bookmark_ids = get_user_meta( get_current_user_id(), '_case27_user_bookmarks', true ) ? : [];
$endpoint_url = wc_get_endpoint_url( '' );
$endpoint_url = rtrim($endpoint_url, '/');

if ( ! $bookmark_ids ) {
	$guest_users_items =  get_option('mwb_guest_bookmarks_item', false);
	$guest_users_items = json_decode($guest_users_items, true);
	$user_ip = $_SERVER['REMOTE_ADDR'];
	if(is_array($guest_users_items) && !empty($guest_users_items) && array_key_exists($user_ip, $guest_users_items)){
		$bookmark_ids = $guest_users_items[$user_ip];
	}
}
if( !$bookmark_ids ){
	$bookmark_ids = [0];
}

$bookmarks = new WP_Query( [
	'post_type' => 'job_listing',
	'posts_per_page' => 10,
	'post_status' => 'publish',
	'paged' => $_page,
	'post__in' => $bookmark_ids,
	] );
// print_r($bookmarks);
	?>

	<?php if ( $bookmarks->have_posts() ) : ?>
		<div class="woocommerce">
			<div class="mwb_visitors_bookmarks_wrapper container" style="margin-top: 30px;">
				<div class="row">
					<div class="col-md-12">
						<table class="job-manager-jobs c27-bookmarks-table shop_table">
							<thead>
								<tr>
									<th class="bookmark-photo"><i class="mi photo"></i></th>
									<th class="bookmark-title"><?php _e( 'Name', GUEST_BOOKMARKS_TEXT_DOMAIN ) ?></th>
									<th class="bookmark-actions"><?php _e( 'Actions', GUEST_BOOKMARKS_TEXT_DOMAIN ) ?></th>
								</tr>
							</thead>
							<tbody>
								<?php while ( $bookmarks->have_posts() ):
								$bookmarks->the_post();
								$listing = \MyListing\Src\Listing::get( get_the_ID() );
								?>
								<tr>
									<td class="bookmark-photo">
										<?php if ($gallery = $listing->get_field( 'gallery' ) ): ?>
											<!-- <img src="<?php //echo $listing->get_logo('thumbnail') ?: c27()->image( 'marker.jpg' ) ?>"> -->
											<div class="owl-carousel lf-background-carousel owlCarouselWithArrows">
												<?php foreach ( array_slice( $gallery, 0, 3 ) as $gallery_image ): ?>
													<?php if($gallery_image != ''):?>
														<div class="item">
															<div class="lf-background" style="background-image: url('<?php echo esc_url( job_manager_get_resized_image( $gallery_image, 'large' ) ) ?>');"></div>
														</div>
													<?php endif; ?>
												<?php endforeach; ?>
											</div>
											<?php //require CASE27_THEME_DIR.'/templates/single-listing/previews/partials/gallery-nav.php'; ?>
											<!-- <div class="gallery-nav mwb_custom_slides">
											    <ul>
											        <li><a href="#" class="lf-item-prev-btn mwb_custom_slides_prev"><i class="mi keyboard_arrow_left"></i></a></li>
											        <li><a href="#" class="lf-item-next-btn mwb_custom_slides_next"><i class="mi keyboard_arrow_right"></i></a></li>
											    </ul>
											</div> -->
										<?php endif; ?>
									</td>
									<td class="bookmark-title">
										<h5>
											<a href="<?php echo esc_url( $listing->get_link() ) ?>">
												<?php echo esc_html( $listing->get_name() ) ?>
											</a>
										</h5>
									</td>
									<td class="listing-actions">
										<ul class="job-dashboard-actions">
											<li><a href="<?php echo esc_url( $listing->get_link() ) ?>" class="view-action"><?php _e( 'View Listing', GUEST_BOOKMARKS_TEXT_DOMAIN ) ?></a></li>
											<li>
												<a href="<?php echo esc_url( add_query_arg( [ 'listing_id' => $listing->get_id(), 'action' => 'mwb_remove_bookmark' ], $endpoint_url ) ) ?>"
													class="delete-action"><?php _e( 'Remove Bookmark', GUEST_BOOKMARKS_TEXT_DOMAIN ) ?></a>
												</li>
											</ul>


										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
		<div class="pagination center-button">
			<?php echo paginate_links([
				'format'  => '?_page=%#%',
				'current' => $_page,
				'total'   => $bookmarks->max_num_pages,
				]);
				wp_reset_postdata(); ?>
			</div>
		<?php else: ?>
			<div class="no-listings">
				<i class="no-results-icon material-icons">mood_bad</i>
				<?php _e( 'No bookmarks yet.', GUEST_BOOKMARKS_TEXT_DOMAIN ) ?>
			</div>
		<?php endif ?>

		<?php do_action( 'case27_woocommerce_bookmarks_after' );?>
		