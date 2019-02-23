<?php
/**
 * User request password form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User request password form class.
 *
 * @class User_Request_Password
 */
class User_Request_Password extends Form {

	/**
	 * Form captcha.
	 *
	 * @var bool
	 */
	protected $captcha = false;

	/**
	 * Class constructor.
	 *
	 * @param array $args Form arguments.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( $args );

		// Set title.
		$this->set_title( esc_html__( 'Reset Password', 'hivepress' ) );

		// Set fields.
		$this->set_fields(
			[
				'username' => [
					'label'      => esc_html__( 'Username or Email', 'hivepress' ),
					'type'       => 'text',
					'max_length' => 254,
					'required'   => true,
					'order'      => 10,
				],
			]
		);
	}

	/**
	 * Submits form.
	 */
	public function submit() {
		parent::submit();

		if ( ! is_user_logged_in() ) {

			// Get user.
			$user = false;

			if ( is_email( $this->get_value( 'username' ) ) ) {
				$user = get_user_by( 'email', $this->get_value( 'username' ) );
			} else {
				$user = get_user_by( 'login', $this->get_value( 'username' ) );
			}

			if ( false !== $user ) {

				// Get URL.
				$url = add_query_arg(
					[
						'username' => $user->user_login,
						'key'      => get_password_reset_key( $user ),
					],
					'todo reset page URL here'
				);

				// Send email.
				// todo send email.
			} else {
				if ( is_email( $this->get_value( 'username' ) ) ) {
					$this->errors[] = esc_html__( "User with this email doesn't exist.", 'hivepress' );
				} else {
					$this->errors[] = esc_html__( "User with this username doesn't exist.", 'hivepress' );
				}
			}
		}

		return empty( $this->errors );
	}
}
