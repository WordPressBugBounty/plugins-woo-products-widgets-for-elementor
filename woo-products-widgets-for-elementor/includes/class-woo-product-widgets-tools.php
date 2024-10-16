<?php
/**
 * Cherry addons tools class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Woo_Product_Widgets_Elementor_Tools' ) ) {

	/**
	 * Define Woo_Product_Widgets_Elementor_Tools class
	 */
	class Woo_Product_Widgets_Elementor_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Returns columns classes string
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function col_classes( $columns = array() ) {

			$columns = wp_parse_args( $columns, array(
				'desk' => 1,
				'tab'  => 1,
				'mob'  => 1,
			) );

			$classes = array();

			foreach ( $columns as $device => $cols ) {
				if ( ! empty( $cols ) ) {
					$classes[] = sprintf( 'col-%1$s-%2$s', $device, $cols );
				}
			}

			return implode( ' ' , $classes );
		}

		/**
		 * Returns disable columns gap nad rows gap classes string
		 *
		 * @param  string $use_cols_gap [description]
		 * @param  string $use_rows_gap [description]
		 * @return [type]               [description]
		 */
		public function gap_classes( $use_cols_gap = 'yes', $use_rows_gap = 'yes' ) {

			$result = array();

			foreach ( array( 'cols' => $use_cols_gap, 'rows' => $use_rows_gap ) as $element => $value ) {
				if ( 'yes' !== $value ) {
					$result[] = sprintf( 'disable-%s-gap', $element );
				}
			}

			return implode( ' ', $result );

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'woo-product-widgets-for-elementor' ), ), $result );
		}

		/**
		 * Get categories list.
		 *
		 * @return array
		 */
		public function get_categories() {

			$categories = get_categories();

			if ( empty( $categories ) || ! is_array( $categories ) ) {
				return array();
			}

			return wp_list_pluck( $categories, 'name', 'slug' );

		}

		/**
		 * Return post terms.
		 *
		 * @since  1.0.0
		 * @param [type] $tax - category, post_tag, post_format.
		 * @param [type] $return_key - slug, term_id.
		 * @return array
		 */
		public function get_terms_array( $tax = array( 'category' ), $return_key = 'slug' ) {
			$terms = array();
			$tax = is_array( $tax ) ? $tax : array( $tax ) ;

			foreach ( $tax as $key => $value ) {
				if ( ! taxonomy_exists( $value ) ) {
					unset( $tax[ $key ] );
				}
			}
			
			$all_terms = get_terms( array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
				'hierarchical' => false
			) );
			
// 			$all_terms = (array) get_terms( $tax, array(
// 				'hide_empty'   => 0,
// 				'hierarchical' => 0,
// 			) );

			if ( empty( $all_terms ) || is_wp_error( $all_terms ) ) {
				return '';
			}
			foreach ( $all_terms as $term ) {
				$terms[ $term->$return_key ] = $term->name;
			}
			return $terms;
		}

		/**
		 * Returns icons data list.
		 *
		 * @return array
		 */
		public function get_theme_icons_data() {

			$default = array(
				'icons'  => false,
				'format' => 'fa %s',
				'file'   => false,
			);

			/**
			 * Filter default icon data before useing
			 *
			 * @var array
			 */
			$icon_data = apply_filters( 'woo-product-widgets-elementor/controls/icon/data', $default );
			$icon_data = array_merge( $default, $icon_data );

			return $icon_data;
		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function orderby_arr() {
			return array(
				'none'          => esc_html__( 'None', 'woo-product-widgets-for-elementor' ),
				'ID'            => esc_html__( 'ID', 'woo-product-widgets-for-elementor' ),
				'author'        => esc_html__( 'Author', 'woo-product-widgets-for-elementor' ),
				'title'         => esc_html__( 'Title', 'woo-product-widgets-for-elementor' ),
				'name'          => esc_html__( 'Name (slug)', 'woo-product-widgets-for-elementor' ),
				'date'          => esc_html__( 'Date', 'woo-product-widgets-for-elementor' ),
				'modified'      => esc_html__( 'Modified', 'woo-product-widgets-for-elementor' ),
				'rand'          => esc_html__( 'Rand', 'woo-product-widgets-for-elementor' ),
				'comment_count' => esc_html__( 'Comment Count', 'woo-product-widgets-for-elementor' ),
				'menu_order'    => esc_html__( 'Menu Order', 'woo-product-widgets-for-elementor' ),
			);
		}

		/**
		 * Returns allowed order fields for options
		 *
		 * @return array
		 */
		public function order_arr() {

			return array(
				'desc' => esc_html__( 'Descending', 'woo-product-widgets-for-elementor' ),
				'asc'  => esc_html__( 'Ascending', 'woo-product-widgets-for-elementor' ),
			);

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function verrtical_align_attr() {
			return array(
				'baseline'    => esc_html__( 'Baseline', 'woo-product-widgets-for-elementor' ),
				'top'         => esc_html__( 'Top', 'woo-product-widgets-for-elementor' ),
				'middle'      => esc_html__( 'Middle', 'woo-product-widgets-for-elementor' ),
				'bottom'      => esc_html__( 'Bottom', 'woo-product-widgets-for-elementor' ),
				'sub'         => esc_html__( 'Sub', 'woo-product-widgets-for-elementor' ),
				'super'       => esc_html__( 'Super', 'woo-product-widgets-for-elementor' ),
				'text-top'    => esc_html__( 'Text Top', 'woo-product-widgets-for-elementor' ),
				'text-bottom' => esc_html__( 'Text Bottom', 'woo-product-widgets-for-elementor' ),
			);
		}

		/**
		 * Returns array with numbers in $index => $name format for numeric selects
		 *
		 * @param  integer $to Max numbers
		 * @return array
		 */
		public function get_select_range( $to = 10 ) {
			$range = range( 1, $to );
			return array_combine( $range, $range );
		}

		/**
		 * Rturns image tag or raw SVG
		 *
		 * @param  string $url  image URL.
		 * @param  array  $attr [description]
		 * @return string
		 */
		public function get_image_by_url( $url = null, $attr = array() ) {

			$url = esc_url( $url );

			if ( empty( $url ) ) {
				return;
			}

			$ext  = pathinfo( $url, PATHINFO_EXTENSION );
			$attr = array_merge( array( 'alt' => '' ), $attr );

			if ( 'svg' !== $ext ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			$base_url = network_site_url( '/' );
			$svg_path = str_replace( $base_url, ABSPATH, $url );
			$key      = md5( $svg_path );
			$svg      = get_transient( $key );

			if ( ! $svg ) {
				$svg = wp_remote_get( $svg_path );
			}

			if ( ! $svg ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			set_transient( $key, $svg, DAY_IN_SECONDS );

			unset( $attr['alt'] );

			return sprintf( '<div%2$s>%1$s</div>', $svg, $this->get_attr_string( $attr ) ); ;
		}

		/**
		 * Return attributes string from attributes array.
		 *
		 * @param  array  $attr Attributes string.
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			if ( empty( $attr ) || ! is_array( $attr ) ) {
				return;
			}

			$result = '';

			foreach ( $attr as $key => $value ) {
				$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}

			return $result;
		}

		/**
		 * Returns carousel arrow
		 *
		 * @param  array $classes Arrow additional classes list.
		 * @return string
		 */
		public function get_carousel_arrow( $classes ) {

			$format = apply_filters( 'woo_products_widgets/carousel/arrows_format', '<i class="%s woo-products-arrow"></i>', $classes );

			return sprintf( $format, implode( ' ', $classes ) );
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'woo-product-widgets-elementor/post-types-list/deprecated',
				array( 'attachment', 'elementor_library' )
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_prev_arrows_list() {

			return apply_filters(
				'woo_products_widgets/carousel/available_arrows/prev',
				array(
					'fa fa-angle-left'          => __( 'Angle', 'woo-product-widgets-for-elementor' ),
					'fa fa-chevron-left'        => __( 'Chevron', 'woo-product-widgets-for-elementor' ),
					'fa fa-angle-double-left'   => __( 'Angle Double', 'woo-product-widgets-for-elementor' ),
					'fa fa-arrow-left'          => __( 'Arrow', 'woo-product-widgets-for-elementor' ),
					'fa fa-caret-left'          => __( 'Caret', 'woo-product-widgets-for-elementor' ),
					'fa fa-long-arrow-left'     => __( 'Long Arrow', 'woo-product-widgets-for-elementor' ),
					'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'woo-product-widgets-for-elementor' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'woo-product-widgets-for-elementor' ),
					'fa fa-caret-square-o-left' => __( 'Caret Square', 'woo-product-widgets-for-elementor' ),
				)
			);

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_next_arrows_list() {

			return apply_filters(
				'woo_products_widgets/carousel/available_arrows/next',
				array(
					'fa fa-angle-right'          => __( 'Angle', 'woo-product-widgets-for-elementor' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'woo-product-widgets-for-elementor' ),
					'fa fa-angle-double-right'   => __( 'Angle Double', 'woo-product-widgets-for-elementor' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'woo-product-widgets-for-elementor' ),
					'fa fa-caret-right'          => __( 'Caret', 'woo-product-widgets-for-elementor' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'woo-product-widgets-for-elementor' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'woo-product-widgets-for-elementor' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'woo-product-widgets-for-elementor' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'woo-product-widgets-for-elementor' ),
				)
			);

		}

		/**
		 * Apply carousel wrappers for shortcode content if carousel is enabled.
		 *
		 * @param  string $content Module content.
		 * @param  array $settings Module settings.
		 *
		 * @return string
		 */
		public function get_carousel_wrapper_atts( $content = null, $settings = array() ) {

			if ( 'yes' !== $settings['carousel_enabled'] ) {
				return $content;
			}

			$options = array(
				'slidesToShow'   => array(
					'desktop' => absint( $settings['columns'] ),
					'tablet'  => absint( $settings['columns_tablet'] ),
					'mobile'  => absint( $settings['columns_mobile'] ),
				),
				'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
				'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
				'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
				'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
				'speed'          => absint( $settings['speed'] ),
				'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
				'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
				'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
				'prevArrow'      => $this->get_carousel_arrow(
					array( $settings['prev_arrow'], 'prev-arrow' )
				),
				'nextArrow'      => $this->get_carousel_arrow(
					array( $settings['next_arrow'], 'next-arrow' )
				),
			);

			if ( 1 === absint( $settings['columns'] ) ) {
				$options['fade'] = ( 'fade' === $settings['effect'] );
			}

			return sprintf(
				'<div class="woo-products-carousel elementor-slick-slider" data-slider_options="%1$s" dir="ltr">%2$s</div>',
				htmlspecialchars( wp_json_encode( $options ) ), $content
			);
		}

		/**
		 * Get term permalink.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_term_permalink( $id = 0 ) {
			return esc_url( get_category_link( $id ) );
		}

		/**
		 * Trim text
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function trim_text( $text = '', $length = -1, $trimmed_type = 'word', $after ) {

			if( '' === $text ){
				return $text;
			}

			if ( 0 === $length ){
				return '';
			}

			if ( -1 !== $length ) {
				if ( 'word' === $trimmed_type ) {
					$text = wp_trim_words( $text, $length, $after );
				} else {
					$text = wp_html_excerpt( $text, $length, $after );
				}
			}

			return $text;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Woo_Product_Widgets_Elementor_Tools
 *
 * @return object
 */
function woo_product_widgets_elementor_tools() {
	return Woo_Product_Widgets_Elementor_Tools::get_instance();
}
