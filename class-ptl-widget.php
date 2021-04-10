<?php
/**
 * PTL_Widget
 *
 * A widget to display a list of posts by post types.
 *
 * @package   PTL_Widget
 * @link      https://github.com/armandphilippot/post-types-list-widget
 * @author    Armand Philippot <contact@armandphilippot.com>
 *
 * @copyright 2020 Armand Philippot
 * @license   GPL-2.0-or-later
 * @since     0.0.1
 *
 * @wordpress-plugin
 * Plugin Name:       Post Types List
 * Plugin URI:        https://github.com/armandphilippot/post-types-list-widget
 * Description:       Display a list of posts by post types with custom options.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Armand Philippot
 * Author URI:        https://www.armandphilippot.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       PTLWidget
 * Domain Path:       /languages
 */

namespace PTLWidget;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PTLWIDGET_VERSION', '1.0.2' );

/**
 * Class used to implement a PTL_Widget widget.
 *
 * @since 0.0.1
 *
 * @see WP_Widget
 */
class PTL_Widget extends \WP_Widget {
	/**
	 * Set up a new PTL_Widget widget instance with id, name & description.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'ptlwidget',
			'description' => __( 'Display a list of posts by post types with custom options.', 'PTLWidget' ),
		);

		parent::__construct(
			'ptlwidget',
			__( 'Post Types List', 'PTLWidget' ),
			$widget_options
		);

		add_action(
			'widgets_init',
			function() {
				register_widget( 'PTLWidget\PTL_Widget' );
			}
		);

		add_action( 'plugins_loaded', array( $this, 'ptlwidget_load_plugin_textdomain' ) );

		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'ptlwidget_enqueue_public_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'ptlwidget_enqueue_public_scripts' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'ptlwidget_enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'ptlwidget_enqueue_admin_scripts' ) );
		}
	}

	/**
	 * Load text domain files
	 *
	 * @since 0.0.1
	 */
	public function ptlwidget_load_plugin_textdomain() {
		load_plugin_textdomain( 'PTLWidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue styles needed by the public view of
	 * PTL_Widget widget.
	 *
	 * @since 0.0.1
	 */
	public function ptlwidget_enqueue_public_styles() {
		$styles_url  = plugins_url( 'public/css/style.min.css', __FILE__ );
		$styles_path = plugin_dir_path( __FILE__ ) . 'public/css/style.min.css';

		if ( file_exists( $styles_path ) ) {
			wp_register_style( 'ptlwidget', $styles_url, array(), PTLWIDGET_VERSION );

			wp_enqueue_style( 'ptlwidget' );
			wp_style_add_data( 'ptlwidget', 'rtl', 'replace' );
		}
	}

	/**
	 * Register and enqueue scripts needed by the public view of
	 * PTL_Widget widget.
	 *
	 * @since 0.0.1
	 */
	public function ptlwidget_enqueue_public_scripts() {
		$scripts_url  = plugins_url( 'public/js/scripts.min.js', __FILE__ );
		$scripts_path = plugin_dir_path( __FILE__ ) . 'public/js/scripts.min.js';

		if ( file_exists( $scripts_path ) ) {
			wp_register_script( 'ptlwidget-scripts', $scripts_url, array(), PTLWIDGET_VERSION, true );
			wp_enqueue_script( 'ptlwidget-scripts' );
		}
	}

	/**
	 * Register and enqueue styles needed by the admin view of
	 * PTL_Widget widget.
	 *
	 * @since 0.0.1
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function ptlwidget_enqueue_admin_styles( $hook_suffix ) {
		$styles_url  = plugins_url( 'admin/css/style.min.css', __FILE__ );
		$styles_path = plugin_dir_path( __FILE__ ) . 'admin/css/style.min.css';

		if ( file_exists( $styles_path ) && 'widgets.php' === $hook_suffix ) {
			wp_register_style( 'ptlwidget', $styles_url, array(), PTLWIDGET_VERSION );

			wp_enqueue_style( 'ptlwidget' );
			wp_style_add_data( 'ptlwidget', 'rtl', 'replace' );
		}
	}

	/**
	 * Register and enqueue scripts needed by the admin view of
	 * PTL_Widget widget.
	 *
	 * @since 0.0.1
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function ptlwidget_enqueue_admin_scripts( $hook_suffix ) {
		$scripts_url  = plugins_url( 'admin/js/scripts.min.js', __FILE__ );
		$scripts_path = plugin_dir_path( __FILE__ ) . 'admin/js/scripts.min.js';

		if ( file_exists( $scripts_path && 'widgets.php' === $hook_suffix ) ) {
			wp_register_script( 'ptlwidget-scripts', $scripts_url, array(), PTLWIDGET_VERSION, true );
			wp_enqueue_script( 'ptlwidget-scripts' );
		}
	}

	/**
	 * Define the value to use by the order by argument in WP_Query.
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
	 * @since 0.0.1
	 *
	 * @param string $value A value to compare.
	 * @return string The correct value to use in WP_Query args.
	 */
	public function getOrderByValue( string $value ): string {
		switch ( $value ) {
			case 'update-date':
				return 'modified';
			case 'title':
				return 'title';
			case 'author':
				return 'author';
			case 'comment':
				return 'comment_count';
			case 'type':
				return 'type';
			case 'random':
				return 'rand';
			default:
				return 'date';
		}
	}

	/**
	 * Outputs the content for the current PTL_Widget widget instance.
	 *
	 * @since 0.0.1
	 *
	 * @param array $args HTML to display the widget title class and widget content class.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		include 'public/partials/ptl-widget-public-display.php';
	}

	/**
	 * Outputs the settings form for the PTL_Widget widget.
	 *
	 * @since 0.0.1
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		include 'admin/partials/ptl-widget-admin-display.php';
	}

	/**
	 * Handles updating settings for the current PTL_Widget widget instance.
	 *
	 * @since 0.0.1
	 *
	 * @param array $new_instance New settings for this instance as input by the user.
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$ptlwidget_post_types_list = get_post_types( array( 'public' => true ) );

		$instance                 = $old_instance;
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['sticky_posts'] = ! empty( $new_instance['sticky_posts'] ) ? 1 : 0;

		foreach ( $ptlwidget_post_types_list as $ptlwidget_post_type_name ) {
			$instance['post_types'][ $ptlwidget_post_type_name ] = ( ! empty( $new_instance['post_types'][ $ptlwidget_post_type_name ] ) ) ? 1 : 0;
		}

		$instance['posts_limit']                  = ( ! empty( $new_instance['posts_limit'] ) ) ? wp_strip_all_tags( $new_instance['posts_limit'] ) : 'all';
		$instance['posts_number']                 = ( ! empty( $new_instance['posts_number'] ) ) ? wp_strip_all_tags( $new_instance['posts_number'] ) : '';
		$instance['posts_order']                  = ( ! empty( $new_instance['posts_order'] ) ) ? wp_strip_all_tags( $new_instance['posts_order'] ) : '';
		$instance['posts_order_by']               = ( ! empty( $new_instance['posts_order_by'] ) ) ? wp_strip_all_tags( $new_instance['posts_order_by'] ) : '';
		$instance['complementary_sorting']        = ( ! empty( $new_instance['complementary_sorting'] ) ) ? wp_strip_all_tags( $new_instance['complementary_sorting'] ) : 'deactivated';
		$instance['posts_order_complementary']    = ( ! empty( $new_instance['posts_order_complementary'] ) ) ? wp_strip_all_tags( $new_instance['posts_order_complementary'] ) : '';
		$instance['posts_order_by_complementary'] = ( ! empty( $new_instance['posts_order_by_complementary'] ) ) ? wp_strip_all_tags( $new_instance['posts_order_by_complementary'] ) : '';
		$instance['categories']                   = ! empty( $new_instance['categories'] ) ? 1 : 0;
		$instance['tags']                         = ! empty( $new_instance['tags'] ) ? 1 : 0;
		$instance['author']                       = ! empty( $new_instance['author'] ) ? 1 : 0;
		$instance['publication_date']             = ! empty( $new_instance['publication_date'] ) ? 1 : 0;
		$instance['update_date']                  = ! empty( $new_instance['update_date'] ) ? 1 : 0;
		$instance['comments_number']              = ! empty( $new_instance['comments_number'] ) ? 1 : 0;
		$instance['excerpt']                      = ! empty( $new_instance['excerpt'] ) ? 1 : 0;

		return $instance;
	}
}

$ptlwidget = new PTL_Widget();
