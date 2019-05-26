<?php
/**
 * Listing sort form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing sort form class.
 *
 * @class Listing_Sort
 */
class Listing_Sort extends Form {

	/**
	 * Form name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Form fields.
	 *
	 * @var array
	 */
	protected static $fields = [];

	/**
	 * Form button.
	 *
	 * @var object
	 */
	protected static $button;

	/**
	 * Class initializer.
	 *
	 * @param array $args Form arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'fields' => [
					'sort'      => [
						'label'    => esc_html__( 'Sort by', 'hivepress' ),
						'type'     => 'select',
						'options'  => [],
						'required' => true,
						'order'    => 10,
					],

					's'         => [
						'type' => 'hidden',
					],

					'category'  => [
						'type' => 'hidden',
					],

					'post_type' => [
						'type'    => 'hidden',
						'default' => 'hp_listing',
					],
				],

				'button' => null,
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Class constructor.
	 *
	 * @param array $args Form arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'action' => home_url( '/' ),
				'method' => 'GET',
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps form properties.
	 */
	protected function bootstrap() {

		// Set attributes.
		$this->attributes = hp\merge_arrays(
			$this->attributes,
			[
				'data-submit' => 'true',
			]
		);

		parent::bootstrap();
	}
}
