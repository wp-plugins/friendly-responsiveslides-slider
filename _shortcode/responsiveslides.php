<?php

// Don't allow the file to be called directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode for the ResponsiveSlides slider
 *
 * @package Friendly RS Slider
 * @author iamfriendly
 * @version 0.1
 * @since 0.1
 * @todo:	A *lot*. To start with it would be nice to be able to use a [gallery] shortcode to use with the images
 *				rather than having to manually put the urls in. Soon my precious.
 */

if ( ! function_exists( 'friendly_shortcode_responsive_slides_slider' ) ) :

	function friendly_shortcode_responsive_slides_slider( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'image_1'	=> 'http://www.dummyimage.com/600x300',
			'image_2'	=> '',
			'image_3'	=> '',
			'image_4'	=> '',
			'image_5'	=> '',
			'image_6'	=> '',
			'image_7'	=> '',
			'image_8'	=> '',
			'image_9'	=> '',
			'image_10'	=> '',
			'auto' => 'false',
			'speed'	=> '700',
			'timeout' => '4000',
			'pager' => 'false',
			'nav' => 'true',
			'random' => 'false',
			'pause' => 'false',
			'prevText' => 'Previous',
			'nextText' => 'Next',
			'maxwidth' => '',
			'namespace' => 'shortcode_rslides',
		 ), $atts ) );

		if ( $image_1 != '' ) {

			global $post; $post_id = $post->ID;
			$out = "<div class='rslides_container'><ul class='rslides_shortcode' id='rs_post_".absint( $post_id )."'>";

			$out .= "<li><img src='".esc_url( $image_1 )."' alt='' /></li>";

			if ( $image_2 && $image_2 != '' ) {
				$out .= "<li><img src='".esc_url( $image_2 )."' alt='' /></li>";
			}

			if ( $image_3 && $image_3 != '' ) {
				$out .= "<li><img src='".esc_url( $image_3 )."' alt='' /></li>";
			}

			if ( $image_4 && $image_4 != '' ) {
				$out .= "<li><img src='".esc_url( $image_4 )."' alt='' /></li>";
			}

			if ( $image_5 && $image_5 != '' ) {
				$out .= "<li><img src='".esc_url( $image_5 )."' alt='' /></li>";
			}

			if ( $image_6 && $image_6 != '' ) {
				$out .= "<li><img src='".esc_url( $image_6 )."' alt='' /></li>";
			}

			if ( $image_7 && $image_7 != '' ) {
				$out .= "<li><img src='".esc_url( $image_7 )."' alt='' /></li>";
			}

			if ( $image_8 && $image_8 != '' ) {
				$out .= "<li><img src='".esc_url( $image_8 )."' alt='' /></li>";
			}

			if ( $image_9 && $image_9 != '' ) {
				$out .= "<li><img src='".esc_url( $image_9 )."' alt='' /></li>";
			}

			if ( $image_10 && $image_10 != '' ) {
				$out .= "<li><img src='".esc_url( $image_10 )."' alt='' /></li>";
			}

			$out .= '</ul></div>';

			$out .= '<script>';
				$out .= "jQuery(document).ready(function($) {

					jQuery( '#rs_post_".absint( $post_id )."' ).responsiveSlides({
						auto: ".esc_attr( $auto ).",
						speed: ".absint( $speed ).",
						timeout: ".absint( $timeout ).",
						pager: ".esc_attr( $pager ).",
						nav: ".esc_attr( $nav ).",
						random: ".esc_attr( $random ).",
						pause: ".esc_attr( $pause ).",
						prevText: '".esc_html( $prevText )."',
						nextText: '".esc_html( $nextText )."',
						maxwidth: '".esc_attr( $maxwidth )."'
					});

				});";
			$out .= '</script>';

		}

		wp_enqueue_style( 'responsiveslides-slider-css', friendly_rs_slider::get_url( '_a/css/responsiveslides.css' ), '', friendly_rs_slider::version, 'screen' );

		return $out;

	}/* friendly_shortcode_responsive_slides_slider() */

endif;

add_shortcode( 'responsiveslides', 'friendly_shortcode_responsive_slides_slider' );

?>
