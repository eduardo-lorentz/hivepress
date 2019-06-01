<?php
/**
 * Listings block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;
use HivePress\Models;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listings block class.
 *
 * @class Listings
 */
class Listings extends Block {

	/**
	 * Block type.
	 *
	 * @var string
	 */
	protected static $type;

	/**
	 * Block title.
	 *
	 * @var string
	 */
	protected static $title;

	/**
	 * Block settings.
	 *
	 * @var array
	 */
	protected static $settings = [];

	/**
	 * Template type.
	 *
	 * @var string
	 */
	protected $template = 'view';

	/**
	 * Columns number.
	 *
	 * @var int
	 */
	protected $columns;

	/**
	 * Listings number.
	 *
	 * @var int
	 */
	protected $number;

	/**
	 * Listings category.
	 *
	 * @var int
	 */
	protected $category;

	/**
	 * Listings order.
	 *
	 * @var string
	 */
	protected $order;

	/**
	 * Class initializer.
	 *
	 * @param array $args Block arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'title'    => esc_html__( 'Listings', 'hivepress' ),

				'settings' => [
					'columns'  => [
						'label'   => esc_html__( 'Columns', 'hivepress' ),
						'type'    => 'select',
						'default' => 3,
						'order'   => 10,
						'options' => [
							2 => '2',
							3 => '3',
							4 => '4',
						],
					],

					'number'   => [
						'label'     => esc_html__( 'Number', 'hivepress' ),
						'type'      => 'number',
						'min_value' => 1,
						'default'   => 3,
						'order'     => 20,
					],

					'category' => [
						'label'    => esc_html__( 'Category', 'hivepress' ),
						'type'     => 'select',
						'options'  => 'terms',
						'taxonomy' => 'hp_listing_category',
						'default'  => '',
						'order'    => 30,
					],

					'order'    => [
						'label'   => esc_html__( 'Order', 'hivepress' ),
						'type'    => 'select',
						'default' => 'date',
						'order'   => 40,
						'options' => [
							'date'   => esc_html__( 'Date', 'hivepress' ),
							'title'  => esc_html__( 'Title', 'hivepress' ),
							'random' => esc_html__( 'Random', 'hivepress' ),
						],
					],
				],
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		global $wp_query;

		$output = '';

		// Get column width.
		$columns      = absint( $this->columns );
		$column_width = 12;

		if ( $columns > 0 && $columns <= 12 ) {
			$column_width = round( $column_width / $columns );
		}

		// Get listing query.
		$query = $wp_query;

		if ( is_single() || hp\get_array_value( $query->query, 'post_type' ) !== 'hp_listing' ) {

			// Set query arguments.
			$query_args = [
				'post_type'      => 'hp_listing',
				'post_status'    => 'publish',
				'posts_per_page' => absint( $this->number ),
			];

			// Get category.
			if ( $this->category ) {
				$query_args['tax_query'] = [
					[
						'taxonomy' => 'hp_listing_category',
						'terms'    => [ absint( $this->category ) ],
					],
				];
			}

			// Get order.
			if ( 'title' === $this->order ) {
				$query_args['orderby'] = 'title';
				$query_args['order']   = 'ASC';
			} elseif ( 'random' === $this->order ) {
				$query_args['orderby'] = 'rand';
			}

			// Query listings.
			$query = new \WP_Query( $query_args );
		}

		// Render listings.
		if ( $query->have_posts() ) {
			if ( 'edit' === $this->template ) {
				$output .= '<table class="hp-table">';
			} else {
				$output .= '<div class="hp-grid">';
				$output .= '<div class="hp-row">';
			}

			while ( $query->have_posts() ) {
				$query->the_post();

				// Get listing.
				$listing = Models\Listing::get( get_the_ID() );

				if ( ! is_null( $listing ) ) {
					if ( 'edit' !== $this->template ) {
						$output .= '<div class="hp-grid__item hp-col-sm-' . esc_attr( $column_width ) . ' hp-col-xs-12">';
					}

					// Render listing.
					$output .= ( new Template(
						[
							'template' => 'listing_' . $this->template . '_block',

							'context'  => [
								'listing' => $listing,
							],
						]
					) )->render();

					if ( 'edit' !== $this->template ) {
						$output .= '</div>';
					}
				}
			}

			if ( 'edit' === $this->template ) {
				$output .= '</table>';
			} else {
				$output .= '</div>';
				$output .= '</div>';
			}
		}

		// Reset query.
		wp_reset_postdata();

		return $output;
	}
}