<?php
/**
 * Provide an admin-facing view for the widget
 *
 * This file is used to markup the admin-facing aspects of the widget.
 *
 * @package PTL_Widget
 * @link    https://github.com/armandphilippot/post-types-list-widget
 * @since   0.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ptlwidget_title            = ! empty( $instance['title'] ) ? $instance['title'] : '';
$ptlwidget_sticky_posts     = ! empty( $instance['sticky_posts'] ) ? $instance['sticky_posts'] : false;
$ptlwidget_post_types       = ! empty( $instance['post_types'] ) ? $instance['post_types'] : array();
$ptlwidget_posts_limit      = ! empty( $instance['posts_limit'] ) ? $instance['posts_limit'] : '';
$ptlwidget_posts_number     = ! empty( $instance['posts_number'] ) ? $instance['posts_number'] : '';
$ptlwidget_posts_order      = ! empty( $instance['posts_order'] ) ? $instance['posts_order'] : '';
$ptlwidget_posts_order_by   = ! empty( $instance['posts_order_by'] ) ? $instance['posts_order_by'] : '';
$ptlwidget_categories       = ! empty( $instance['categories'] ) ? $instance['categories'] : false;
$ptlwidget_tags             = ! empty( $instance['tags'] ) ? $instance['tags'] : false;
$ptlwidget_author           = ! empty( $instance['author'] ) ? $instance['author'] : false;
$ptlwidget_publication_date = ! empty( $instance['publication_date'] ) ? $instance['publication_date'] : false;
$ptlwidget_update_date      = ! empty( $instance['update_date'] ) ? $instance['update_date'] : false;
$ptlwidget_comments_number  = ! empty( $instance['comments_number'] ) ? $instance['comments_number'] : false;
$ptlwidget_excerpt          = ! empty( $instance['excerpt'] ) ? $instance['excerpt'] : false;
$ptlwidget_post_types_list  = get_post_types( array( 'public' => true ) );
?>
<p>
	<label
		for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		<?php echo esc_html__( 'Title:', 'PTLWidget' ); ?>
	</label>
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		type="text" value="<?php echo esc_attr( $ptlwidget_title ); ?>" />
</p>
<p>
	<input class="checkbox"
		id="<?php echo esc_attr( $this->get_field_id( 'sticky_posts' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'sticky_posts' ) ); ?>"
		type="checkbox" <?php checked( $ptlwidget_sticky_posts ); ?> />
	<label class="ptlwidget__label"
		for="<?php echo esc_attr( $this->get_field_id( 'sticky_posts' ) ); ?>">
		<?php echo esc_html__( 'Ignore sticky posts', 'PTLWidget' ); ?>
	</label>
</p>
<fieldset class="ptlwidget__fieldset">
	<legend class="ptlwidget__legend"><?php esc_html_e( 'Choose post types:', 'PTLWidget' ); ?></legend>
	<p>
		<?php
		foreach ( $ptlwidget_post_types_list as $ptlwidget_post_type_name => $ptlwidget_post_type_value ) {
			?>
			<label class="ptlwidget__label ptlwidget__label--capitalize"
			for="<?php echo esc_attr( $this->get_field_id( 'post_types' ) . '-' . $ptlwidget_post_type_name ); ?>">
				<input
					type="checkbox"
					class="checkbox"
					id="<?php echo esc_attr( $this->get_field_id( 'post_types' ) . '-' . $ptlwidget_post_type_name ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'post_types' ) . '[' . $ptlwidget_post_type_name . ']' ); ?>"
					<?php checked( $ptlwidget_post_types[ $ptlwidget_post_type_name ] ); ?>
				/>
				<?php echo esc_html( $ptlwidget_post_type_name ); ?>
			</label>
			<br />
			<?php
		}
		?>
	</p>
</fieldset>
<fieldset class="ptlwidget__fieldset">
	<legend class="ptlwidget__legend"><?php esc_html_e( 'How many posts do you want to display:', 'PTLWidget' ); ?></legend>
	<p>
		<input type="radio" name="<?php echo esc_attr( $this->get_field_name( 'posts_limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'posts_limit_all' ) ); ?>" value="all" <?php checked( $ptlwidget_posts_limit, 'all' ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'posts_limit_all' ) ); ?>">
			<?php echo esc_html__( 'Display all posts', 'PTLWidget' ); ?>
		</label>
		<input type="radio" name="<?php echo esc_attr( $this->get_field_name( 'posts_limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'posts_limit_fixed' ) ); ?>" value="limited" <?php checked( $ptlwidget_posts_limit, 'limited' ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'posts_limit_fixed' ) ); ?>">
			<?php echo esc_html__( 'Fix a limit', 'PTLWidget' ); ?>
		</label>
		<span class="ptlwidget__conditionally-hidden">
			<label class="ptlwidget__label" for="<?php echo esc_attr( $this->get_field_id( 'posts_number' ) ); ?>"><?php echo esc_html__( 'Number of posts to display:', 'PTLWidget' ); ?></label>
			<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'posts_number' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'posts_number' ) ); ?>"
				type="number" value="<?php echo esc_attr( $ptlwidget_posts_number ); ?>" min="1" />
		</span>
	</p>
</fieldset>
<fieldset class="ptlwidget__fieldset">
	<legend class="ptlwidget__legend"><?php esc_html_e( 'Choose a sorting method:', 'PTLWidget' ); ?></legend>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'posts_order' ) ); ?>">
			<?php esc_html_e( 'Order:', 'PTLWidget' ); ?>
		</label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'posts_order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'posts_order' ) ); ?>">
			<option value="desc" <?php selected( $ptlwidget_posts_order, 'desc' ); ?>><?php esc_html_e( 'Descending', 'PTLWidget' ); ?></option>
			<option value="asc" <?php selected( $ptlwidget_posts_order, 'asc' ); ?>><?php esc_html_e( 'Ascending', 'PTLWidget' ); ?></option>
		</select>
		<label for="<?php echo esc_attr( $this->get_field_id( 'posts_order_by' ) ); ?>">
			<?php esc_html_e( 'Order by:', 'PTLWidget' ); ?>
		</label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'posts_order_by' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'posts_order_by' ) ); ?>">
			<option value="publication-date" <?php selected( $ptlwidget_posts_order_by, 'publication-date' ); ?>><?php esc_html_e( 'Publication date', 'PTLWidget' ); ?></option>
			<option value="update-date" <?php selected( $ptlwidget_posts_order_by, 'update-date' ); ?>><?php esc_html_e( 'Update date', 'PTLWidget' ); ?></option>
			<option value="title" <?php selected( $ptlwidget_posts_order_by, 'title' ); ?>><?php esc_html_e( 'Title', 'PTLWidget' ); ?></option>
		</select>
	</p>
</fieldset>
<fieldset class="ptlwidget__fieldset">
	<legend class="ptlwidget__legend"><?php esc_html_e( 'Choose the information to display:', 'PTLWidget' ); ?></legend>
	<p>
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'categories' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_categories ); ?>
			/>
			<?php esc_html_e( 'Categories', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_tags ); ?>
			/>
			<?php esc_html_e( 'Tags', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'author' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_author ); ?>
			/>
			<?php esc_html_e( 'Author', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'publication_date' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'publication_date' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'publication_date' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_publication_date ); ?>
			/>
			<?php esc_html_e( 'Publication date', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'update_date' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'update_date' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'update_date' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_update_date ); ?>
			/>
			<?php esc_html_e( 'Update date', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'comments_number' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'comments_number' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'comments_number' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_comments_number ); ?>
			/>
			<?php esc_html_e( 'Comments number', 'PTLWidget' ); ?>
		</label>
		<br />
		<label class="ptlwidget__label"
			for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>">
			<input class="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>"
				type="checkbox" <?php checked( $ptlwidget_excerpt ); ?>
			/>
			<?php esc_html_e( 'Excerpt', 'PTLWidget' ); ?>
		</label>
	</p>
</fieldset>
