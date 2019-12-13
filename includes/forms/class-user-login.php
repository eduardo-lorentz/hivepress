<?php
/**
 * User login form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User login form class.
 *
 * @class User_Login
 */
class User_Login extends Model_Form {

	/**
	 * Form title.
	 *
	 * @var string
	 */
	protected static $title;

	/**
	 * Model name.
	 *
	 * @var string
	 */
	protected static $model;

	/**
	 * Form action.
	 *
	 * @var string
	 */
	protected static $action;

	/**
	 * Form captcha.
	 *
	 * @var bool
	 */
	protected static $captcha = false;

	/**
	 * Form redirect.
	 *
	 * @var mixed
	 */
	protected static $redirect = false;

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
				'title'    => esc_html__( 'Login User', 'hivepress' ),
				'model'    => 'user',
				'action'   => hp\get_rest_url( '/users/login' ),
				'redirect' => true,

				'fields'   => [
					'username_or_email' => [
						'label'      => esc_html__( 'Username or Email', 'hivepress' ),
						'type'       => 'text',
						'max_length' => 254,
						'required'   => true,
						'excluded'   => true,
						'order'      => 10,
					],

					'password'          => [
						'min_length' => null,
						'required'   => true,
						'order'      => 20,
					],
				],

				'button'   => [
					'label' => esc_html__( 'Sign In', 'hivepress' ),
				],
			],
			$args
		);

		parent::init( $args );
	}
}
