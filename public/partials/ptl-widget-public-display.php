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

$ptlwidget_default_title = __( 'Post Types', 'PTLWidget' );
$ptlwidget_title         = ! empty( $instance['title'] ) ? $instance['title'] : $ptlwidget_default_title;
$ptlwidget_title         = apply_filters( 'widget_title', $ptlwidget_title, $instance, $this->id_base );

echo wp_kses_post( $args['before_widget'] );
if ( ! empty( $ptlwidget_title ) ) {
	echo wp_kses_post( $args['before_title'] ) . esc_html( $ptlwidget_title ) . wp_kses_post( $args['after_title'] );
}
?>
<!-- Your widget content here. -->
<?php
echo wp_kses_post( $args['after_widget'] );
