<?php
/**
 * Vendor controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Blocks;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Vendor controller class.
 *
 * @class Vendor
 */
class Vendor extends Controller {

	/**
	 * Controller name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Controller routes.
	 *
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Controller arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					'view_vendor' => [
						'match'  => 'is_vendor_view_page',
						'action' => 'render_vendor_view_page',
					],
				],
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Matches vendor view page.
	 *
	 * @return bool
	 */
	public function is_vendor_view_page() {
		return is_singular( 'hp_vendor' );
	}

	/**
	 * Renders vendor view page.
	 *
	 * @return string
	 */
	public function render_vendor_view_page() {
		the_post();

		// Query listings.
		query_posts(
			[
				'post_type'      => 'hp_listing',
				'post_status'    => 'publish',
				'post_parent'    => get_the_ID(),
				'posts_per_page' => absint( get_option( 'hp_listings_per_page' ) ),
				'paged'          => hp\get_current_page(),
			]
		);

		return ( new Blocks\Template(
			[
				'template' => 'vendor_view_page',

				'context'       => [
					'vendor' => Models\Vendor::get( get_the_ID() ),
				],
			]
		) )->render();
	}
}