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

$ptlwidget_sticky_posts     = ! empty( $instance['sticky_posts'] ) ? '1' : '0';
$ptlwidget_post_types       = ( ! empty( $instance['post_types'] ) ) ? $instance['post_types'] : array( 'post' => 1 );
$ptlwidget_posts_limit      = ( ! empty( $instance['posts_limit'] ) ) ? wp_strip_all_tags( $instance['posts_limit'] ) : '';
$ptlwidget_posts_number     = ( ! empty( $instance['posts_number'] ) ) ? intval( wp_strip_all_tags( $instance['posts_number'] ) ) : '';
$ptlwidget_posts_order      = ( ! empty( $instance['posts_order'] ) ) ? wp_strip_all_tags( $instance['posts_order'] ) : '';
$ptlwidget_posts_order_by   = ( ! empty( $instance['posts_order_by'] ) ) ? wp_strip_all_tags( $instance['posts_order_by'] ) : '';
$ptlwidget_categories       = ! empty( $instance['categories'] ) ? '1' : '0';
$ptlwidget_tags             = ! empty( $instance['tags'] ) ? '1' : '0';
$ptlwidget_author           = ! empty( $instance['author'] ) ? '1' : '0';
$ptlwidget_publication_date = ! empty( $instance['publication_date'] ) ? '1' : '0';
$ptlwidget_update_date      = ! empty( $instance['update_date'] ) ? '1' : '0';
$ptlwidget_comments_number  = ! empty( $instance['comments_number'] ) ? '1' : '0';
$ptlwidget_excerpt          = ! empty( $instance['excerpt'] ) ? '1' : '0';

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

if ( 'ASC' === $ptlwidget_posts_order || 'DESC' === $ptlwidget_posts_order ) {
	$ptlwidget_query_args += array( 'order' => $ptlwidget_posts_order );
}

switch ( $ptlwidget_posts_order_by ) {
	case 'update-date':
		$ptlwidget_posts_orderby_arg = 'modified';
		break;
	case 'title':
		$ptlwidget_posts_orderby_arg = 'name';
		break;
	default:
		$ptlwidget_posts_orderby_arg = 'date';
		break;
}

if ( $ptlwidget_posts_orderby_arg ) {
	$ptlwidget_query_args += array( 'orderby' => $ptlwidget_posts_orderby_arg );
}

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
				</article>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}

echo wp_kses_post( $args['after_widget'] );
