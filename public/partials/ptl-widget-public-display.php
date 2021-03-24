<?php
/**
 * Provide a public-facing view for the widget
 *
 * This file is used to markup the public-facing aspects of the widget.
 *
 * @package PTL_Widget
 * @link    https://github.com/armandphilippot/post-types-list-widget
 * @since   0.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ptlwidget_title = ! empty( $instance['title'] ) ? $instance['title'] : '';
$ptlwidget_title = apply_filters( 'widget_title', $ptlwidget_title, $instance, $this->id_base );

$ptlwidget_sticky_posts                 = ! empty( $instance['sticky_posts'] ) ? '1' : '0';
$ptlwidget_post_types                   = ( ! empty( $instance['post_types'] ) ) ? $instance['post_types'] : array( 'post' => 1 );
$ptlwidget_posts_limit                  = ( ! empty( $instance['posts_limit'] ) ) ? wp_strip_all_tags( $instance['posts_limit'] ) : 'all';
$ptlwidget_posts_number                 = ( ! empty( $instance['posts_number'] ) ) ? intval( wp_strip_all_tags( $instance['posts_number'] ) ) : '';
$ptlwidget_posts_order                  = ( ! empty( $instance['posts_order'] ) ) ? wp_strip_all_tags( $instance['posts_order'] ) : '';
$ptlwidget_posts_order_by               = ( ! empty( $instance['posts_order_by'] ) ) ? wp_strip_all_tags( $instance['posts_order_by'] ) : '';
$ptlwidget_complementary_sorting        = ( ! empty( $instance['complementary_sorting'] ) ) ? wp_strip_all_tags( $instance['complementary_sorting'] ) : 'deactivated';
$ptlwidget_posts_order_complementary    = ( ! empty( $instance['posts_order_complementary'] ) ) ? wp_strip_all_tags( $instance['posts_order_complementary'] ) : '';
$ptlwidget_posts_order_by_complementary = ( ! empty( $instance['posts_order_by_complementary'] ) ) ? wp_strip_all_tags( $instance['posts_order_by_complementary'] ) : '';
$ptlwidget_categories                   = ! empty( $instance['categories'] ) ? '1' : '0';
$ptlwidget_tags                         = ! empty( $instance['tags'] ) ? '1' : '0';
$ptlwidget_author                       = ! empty( $instance['author'] ) ? '1' : '0';
$ptlwidget_publication_date             = ! empty( $instance['publication_date'] ) ? '1' : '0';
$ptlwidget_update_date                  = ! empty( $instance['update_date'] ) ? '1' : '0';
$ptlwidget_comments_number              = ! empty( $instance['comments_number'] ) ? '1' : '0';
$ptlwidget_excerpt                      = ! empty( $instance['excerpt'] ) ? '1' : '0';

$ptlwidget_query_args = array(
	'query_label'      => 'ptlwidget_query',
	'suppress_filters' => false,
);

$ptlwidget_post_types_enabled = array();

foreach ( $ptlwidget_post_types as $ptlwidget_post_type_name => $ptlwidget_post_type_value ) {
	if ( 1 === $ptlwidget_post_type_value ) {
		$ptlwidget_post_types_enabled[] = $ptlwidget_post_type_name;
	}
}

$ptlwidget_query_args += array( 'post_type' => $ptlwidget_post_types_enabled );

if ( $ptlwidget_sticky_posts ) {
	$ptlwidget_query_args += array( 'ignore_sticky_posts' => true );
}

$ptlwidget_posts_order_by_array = array();
$ptlwidget_posts_orderby_arg    = $this->getOrderByValue( $ptlwidget_posts_order_by );

if ( $ptlwidget_posts_orderby_arg && ( 'ASC' === $ptlwidget_posts_order || 'DESC' === $ptlwidget_posts_order ) ) {
	$ptlwidget_posts_order_by_array += array( $ptlwidget_posts_orderby_arg => $ptlwidget_posts_order );
}

if ( 'activated' === $ptlwidget_complementary_sorting ) {
	$ptlwidget_posts_orderby_complementary_arg = $this->getOrderByValue( $ptlwidget_posts_order_by_complementary );
	$ptlwidget_posts_order_by_array           += array( $ptlwidget_posts_orderby_complementary_arg => $ptlwidget_posts_order_complementary );
}

$ptlwidget_query_args += array( 'order' => $ptlwidget_posts_order_array );
$ptlwidget_query_args += array( 'orderby' => $ptlwidget_posts_order_by_array );

if ( 'limited' === $ptlwidget_posts_limit && is_int( $ptlwidget_posts_number ) ) {
	$ptlwidget_query_args += array( 'posts_per_page' => $ptlwidget_posts_number );
	$ptlwidget_query_args += array( 'no_found_rows' => true );
} else {
	$ptlwidget_query_args += array( 'posts_per_page' => -1 );
}

$ptlwidget_posts_query = new \WP_Query( $ptlwidget_query_args );

echo wp_kses_post( $args['before_widget'] );

if ( ! empty( $ptlwidget_title ) ) {
	echo wp_kses_post( $args['before_title'] ) . esc_html( $ptlwidget_title ) . wp_kses_post( $args['after_title'] );
}

if ( $ptlwidget_posts_query->have_posts() ) {
	?>
	<ul class="ptl__list">
		<?php
		while ( $ptlwidget_posts_query->have_posts() ) {
			$ptlwidget_posts_query->the_post();
			?>
			<li class="ptl__item">
				<article class="ptl__post">
					<header class="ptl__header">
						<a href="<?php the_permalink(); ?>" class="ptl__title"><?php the_title(); ?></a>
					</header>
					<?php if ( $ptlwidget_categories || $ptlwidget_tags || $ptlwidget_author || $ptlwidget_publication_date || $ptlwidget_update_date || $ptlwidget_comments_number ) { ?>
						<footer class="ptl__footer">
							<dl class="ptl__list">
								<?php
								if ( $ptlwidget_categories && has_category() ) {
									$ptlwidget_post_categories = get_the_category();
									?>
									<div class="ptl__group ptl__categories">
										<dt class="ptl__term">
										<?php
										printf(
											esc_html(
												_n(
													'Category:',
													'Categories:',
													count( $ptlwidget_post_categories ),
													'PTLWidget'
												)
											)
										);
										?>
										</dt>
										<dd class="ptl__description">
											<?php the_category( ', ' ); ?>
										</dd>
									</div>
									<?php
								}
								if ( $ptlwidget_tags && has_tag() ) {
									$ptlwidget_post_tags = get_the_tags();
									?>
									<div class="ptl__group ptl__tags">
										<dt class="ptl__term">
										<?php
										printf(
											esc_html(
												_n(
													'Tag:',
													'Tags:',
													count( $ptlwidget_post_tags ),
													'PTLWidget'
												)
											)
										);
										?>
										</dt>
										<dd class="ptl__description">
											<?php the_tags( '', ', ' ); ?>
										</dd>
									</div>
									<?php
								}
								if ( $ptlwidget_author ) {
									?>
									<div class="ptl__group ptl__author">
										<dt class="ptl__term">
											<?php esc_html_e( 'Author:', 'PTLWidget' ); ?>
										</dt>
										<dd class="ptl__description">
											<?php the_author_posts_link(); ?>
										</dd>
									</div>
									<?php
								}
								if ( $ptlwidget_publication_date ) {
									?>
									<div class="ptl__group ptl__publication-date">
										<dt class="ptl__term">
											<?php esc_html_e( 'Published on', 'PTLWidget' ); ?>
										</dt>
										<dd class="ptl__description">
											<?php echo get_the_date(); ?>
										</dd>
									</div>
									<?php
								}
								if ( $ptlwidget_update_date ) {
									?>
									<div class="ptl__group ptl__update-date">
										<dt class="ptl__term">
											<?php esc_html_e( 'Updated on', 'PTLWidget' ); ?>
										</dt>
										<dd class="ptl__description">
											<?php the_modified_date(); ?>
										</dd>
									</div>
									<?php
								}
								if ( $ptlwidget_comments_number && comments_open() ) {
									?>
									<div class="ptl__group ptl__comments">
										<dt class="ptl__term">
											<?php esc_html_e( 'Comments:', 'PTLWidget' ); ?>
										</dt>
										<dd class="ptl__description">
											<?php comments_popup_link(); ?>
										</dd>
									</div>
									<?php
								}
								?>
							</dl>
						</footer>
						<?php
					}
					if ( $ptlwidget_excerpt ) {
						?>
						<div class="ptl__excerpt">
							<?php
							$ptlwidget_content = wp_strip_all_tags( get_the_excerpt(), true );
							echo esc_html( $ptlwidget_content );
							?>
						</div>
					<?php } ?>
				</article>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}

echo wp_kses_post( $args['after_widget'] );
