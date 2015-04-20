<?php

// Don't allow the file to be called directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'friendly_responsiveslides_slider' ) ) {

	/**
	 * ResponsiveSlides Slider Widget
	 * Unfortunately, this class name was given before I started developing to WP coding style standards
	 * I don't wish to change it in case others have coded uses this class name
	 *
	 * @package Friendly RS Slider
	 * @author iamfriendly
	 * @version 0.1
	 * @since 0.1
	 */

	class friendly_responsiveslides_slider extends WP_Widget {

		const name = 'Friendly ResponsiveSlides Slider';
		const locale = 'frss';
		const slug = 'friendly_responsiveslides_slider';

		public static $post_cat = null;
		public static $num_to_show = null;
		public static $container_id = null;
		public static $slideshow_interval = null;
		public static $bullets = null;
		public static $arrows = null;

		/**
		 * The widget constructor. Specifies the classname and description, instantiates
		 * the widget, loads localization files, and includes necessary scripts and
		 * styles.
		 *
		 * @package Friendly RS Slider
		 * @author iamfriendly
		 * @version 0.1
		 * @since 0.1
		 */

		function friendly_responsiveslides_slider() {

			$widget_opts = array(
				'classname' => 'friendly_responsiveslides_slider',
				'description' => __( 'Using VilJamis\'s responsive slides slider - images only, responsive slider', self::locale )
			);

			$control_opts = array(
				'width' => 200,
				'height' => 400,
			);

			$this->WP_Widget( self::slug, __( esc_html( self::name ), esc_attr__( self::locale ) ), $widget_opts, $control_opts );

			$this->register_scripts_and_styles();

		}/* function friendly_page_post_content_as_row() */


		/**
		 * Outputs the content of the widget.
		 *
		 * @package Friendly RS Slider
		 * @author iamfriendly
		 * @version 0.1
		 * @since 0.1
		 */

		function widget( $args, $instance ) {

			//Extract the widget arguments
			extract( $args, EXTR_SKIP );

			//Begin the widget output
			echo wp_kses_post( $before_widget );

			//Retrieve each of the options from the widget
			$post_cat 			= $instance['post_cat'];
	    	$num_to_show 		= $instance['num_to_show'];
	    	$container_id 		= $instance['container_id'];
	    	$slideshow_interval = $instance['slideshow_interval'];
	    	$bullets 			= $instance['bullets'];
	    	$arrows 			= $instance['arrows'];

			static::$post_cat 			= $post_cat;
			static::$num_to_show 		= $num_to_show;
			static::$container_id 		= $container_id;
			static::$slideshow_interval = $slideshow_interval;
			static::$bullets 			= $bullets;
			static::$arrows 			= $arrows;

	    	//Ensure we're only skipping through the posts we need
			$post_args = array( 'posts_per_page' => $num_to_show, 'cat' => $post_cat );
			$frss = new WP_Query( $post_args );

			if ( $frss->have_posts() ) : ?>

				<div id="<?php echo esc_attr__( $container_id ); ?>" class="widget responsiveslides_slider">

					<ul class="rslides">

						<?php while ( $frss->have_posts() ) : $frss->the_post(); global $style_dir; ?>

							<?php if ( has_post_thumbnail() ) : ?>
								<li>

								<?php
									$image_id = get_post_thumbnail_id();
									$image_url = wp_get_attachment_image_src( $image_id, 'slider-fullwidth-tall' );
									$image_url = ( isset( $image_url[0] ) ) ? $image_url[0] : false;
									$title = get_the_title();

									$slide_link = get_post_meta( get_the_ID(), 'slide_link', true );
								?>

								<?php if ( $slide_link && $slide_link != '' ) : ?><a href="<?php echo esc_url( $slide_link ); ?>" title=""><?php endif; ?>
								<?php if ( false !== $image_url ) : ?><img src="<?php echo esc_attr( $image_url ); ?>" alt="<?php echo esc_attr__( $title ); ?>" title="<?php echo esc_attr__( $title ); ?>" /><?php endif; ?>
								<?php if ( $slide_link && $slide_link != '' ) : ?></a><?php endif; ?>


			            		</li>
							<?php endif; ?>

			            <?php endwhile; wp_reset_postdata(); ?>

					</ul>

				</div>

	    	<?php endif;

	    	//End the widget output
			echo wp_kses_post( $after_widget );

			//Load our JS in the footer
			add_action( 'wp_footer', array( $this, 'friendly_responsiveslides_slider_helper' ), 999 );

		}/* widget() */


		/**
		 * Processes the widget's options to be saved.
		 *
		 * @package Friendly RS Slider
		 * @author iamfriendly
		 * @version 0.1
		 * @since 0.1
		 */

		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

	    	$instance['post_cat'] = $new_instance['post_cat'];
	    	$instance['num_to_show'] = $new_instance['num_to_show'];
	    	$instance['container_id'] = $new_instance['container_id'];
	    	$instance['slideshow_interval'] = $new_instance['slideshow_interval'];
	    	$instance['bullets'] = $new_instance['bullets'];
	    	$instance['arrows'] = $new_instance['arrows'];

			return $instance;

		}/* update() */


		 /**
		  * Generates the administration form for the widget.
		  *
		  * @package Friendly RS Slider
		  * @author iamfriendly
		  * @version 0.1
		  * @since 0.1
		  */

		function form( $instance ) {

	    	//Default Values
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'post_cat' => '1',
					'num_to_show' => '3',
					'slideshow_interval' => '6000',
					'bullets' => '1',
					'arrows' => '1',
				)
			);

			// Display the admin form
			$defaultSelected = ( 'default' == $instance['post_cat'] ) ? 'selected="selected"' : '';
	    	?>

	    		<p>

					<label for="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>">
						<?php _e( 'Show This Category', 'frss' ); ?>
					</label>

					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_cat' ) ); ?>">

						<option value="default" <?php echo esc_attr( $defaultSelected ); ?>><?php _e( 'Select Category', 'frss' ); ?></option>

						<?php $all_categories = get_categories( 'hide_empty=0' ); foreach ( $all_categories as $category ) : ?>
							<option value="<?php echo esc_attr( absint( $category->term_id ) ); ?>" <?php selected( esc_attr( absint( $category->term_id ) ), $instance['post_cat'] ); ?> >
								<?php echo esc_html( $category->cat_name ); ?>
							</option>

						<?php endforeach; ?>

					</select>

				</p>

				<p>

					<label for="<?php echo esc_attr( $this->get_field_id( 'num_to_show' ) ); ?>">
						<?php _e( 'How many slides?', 'frss' ); ?>
					</label>

					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_to_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_to_show' ) ); ?>">
						<option value="1" <?php selected( '1', $instance['num_to_show'] ); ?>>1</option>
						<option value="2" <?php selected( '2', $instance['num_to_show'] ); ?>>2</option>
						<option value="3" <?php selected( '3', $instance['num_to_show'] ); ?>>3</option>
						<option value="4" <?php selected( '4', $instance['num_to_show'] ); ?>>4</option>
						<option value="5" <?php selected( '5', $instance['num_to_show'] ); ?>>5</option>
						<option value="6" <?php selected( '6', $instance['num_to_show'] ); ?>>6</option>
						<option value="7" <?php selected( '7', $instance['num_to_show'] ); ?>>7</option>
						<option value="8" <?php selected( '8', $instance['num_to_show'] ); ?>>8</option>
					</select>

				</p>

				<p>

					<label for="<?php echo esc_attr( $this->get_field_id( 'slideshow_interval' ) ); ?>">
						<?php _e( 'Slideshow Interval (in ms)', 'frss' ); ?>:
					</label>

					<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'slideshow_interval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slideshow_interval' ) ); ?>" value="<?php echo esc_attr( absint( $instance['slideshow_interval'] ) ); ?>" />

				</p>

				<p>

					<label for="<?php echo esc_attr( $this->get_field_id( 'bullets' ) ); ?>">
						<?php _e( 'Show Bullets?', 'frss' ); ?>:
					</label>

					<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'bullets' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bullets' ) ); ?>" <?php checked( $instance['bullets'], 1 ); ?> value="1" />

				</p>

				<p>

					<label for="<?php echo esc_attr( $this->get_field_id( 'arrows' ) ); ?>">
						<?php _e( 'Show Arrows?', 'frss' ); ?>:
					</label>

					<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'arrows' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'arrows' ) ); ?>" <?php checked( $instance['arrows'], 1 ); ?> value="1" />

				</p>

				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'container_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'container_id' ) ); ?>" value="<?php echo esc_attr( $this->get_field_id( 'container_id' ) ); ?>" />

	    	<?php

		}/* form() */


		/**
		 * Load front-end and back-end scripts and styles
		 *
		 * @package Friendly RS Slider
		 * @author iamfriendly
		 * @version 0.1
		 * @since 0.1
		 */

		private function register_scripts_and_styles() {

			global $style_dir;

			if ( is_admin() ) {
				return;
			}

			$this->load_file( 'responsiveslides-slider-js', friendly_rs_slider::get_url( '_a/js/responsiveslides.min.js' ), true );
	      	$this->load_file( 'responsiveslides-slider-css', friendly_rs_slider::get_url( '_a/css/responsiveslides.css' ), false );

		}/* register_scripts_and_styles() */

		/**
		 * Helper function for registering and enqueueing scripts and styles.
		 *
		 * @name			The ID to register with WordPress
		 * @file_path		The path to the actual file
		 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
		 * @package Friendly RS Slider
		 * @author iamfriendly
		 * @version 0.1
		 * @since 0.1
		 */

		function load_file( $name, $file_path, $is_script = false ) {

			$url = $file_path;

			if ( $is_script ) {
				wp_register_script( $name, $url, '' , '', true );
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url, '', '', false );
				wp_enqueue_style( $name );
			}

		}/* load_file() */


		/**
		 * Helper Function for the Earth Slider to output the relevant ja
		 *
		 * @package Earth
		 * @author iamfriendly
		 * @version 1.0
		 * @since 1.0
		 */

		function friendly_responsiveslides_slider_helper() {

			//$container_id is something like "widget-friendly_responsiveslides_slider-3-container_id", need to strip it
			$strip_id = explode( 'widget-', static::$container_id );
			$strip_id = explode( '-container_id', $strip_id[1] );

			if ( 1 == static::$bullets ) {
				$pager = 'true';
			} else {
				$pager = 'false';
			}

			if ( 1 == static::$arrows ) {
				$nav = 'true';
			} else {
				$nav = 'false';
			}

			$slideshow_interval = ( static::$slideshow_interval != '' ) ? static::$slideshow_interval : '6000';

			?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$(document).on( 'ready', function(){

		jQuery('#<?php echo esc_attr__( $strip_id[0] ); ?> .rslides').responsiveSlides({

			timeout: <?php echo esc_attr( absint( $slideshow_interval ) ); ?>,
			pager: <?php echo esc_attr__( $pager ); ?>,
			nav: <?php echo esc_attr__( $nav ); ?>

		});

	} );

});
</script>

			<?php

		}/* friendly_responsiveslides_slider_helper() */

	}/* class friendly_responsiveslides_slider */

}

add_action( 'widgets_init', create_function( '', 'register_widget("friendly_responsiveslides_slider");' ) );

?>
