<?php
/**
 * User register form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User register form class.
 *
 * @class User_Register
 */
class User_Register extends Form {

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
		$this->set_title( esc_html__( 'Register User', 'hivepress' ) );

		// Set fields.
		$this->set_fields(
			[
				'email'    => [
					'label'    => esc_html__( 'Email', 'hivepress' ),
					'type'     => 'email',
					'required' => true,
					'order'    => 10,
				],

				'password' => [
					'label'      => esc_html__( 'Password', 'hivepress' ),
					'type'       => 'password',
					'min_length' => 6,
					'required'   => true,
					'order'      => 20,
				],
			]
		);

		// Add terms checkbox.
		$page_id = hp_get_post_id(
			[
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post__in'    => [ absint( get_option( 'hp_page_user_registration_terms' ) ) ],
			]
		);

		if ( 0 !== $page_id ) {
			$this->set_fields(
				[
					'terms' => [
						'caption'  => sprintf( hp_sanitize_html( __( 'I agree to %s', 'hivepress' ) ), '<a href="' . esc_url( get_permalink( $page_id ) ) . '" target="_blank">' . get_the_title( $page_id ) . '</a>' ),
						'type'     => 'checkbox',
						'required' => true,
						'order'    => 100,
					],
				]
			);
		}
	}

	/**
	 * Submits form.
	 */
	public function submit() {
		parent::submit();

		if ( ! is_user_logged_in() ) {

			// Check username.
			if ( $this->get_value( 'username' ) ) {
				if ( sanitize_user( $this->get_value( 'username' ), true ) !== $this->get_value( 'username' ) ) {
					$this->errors[] = esc_html__( 'Username contains invalid characters.', 'hivepress' );
				} elseif ( username_exists( $this->get_value( 'username' ) ) ) {
					$this->errors[] = esc_html__( 'This username is already in use.', 'hivepress' );
				}
			}

			// Check email.
			if ( email_exists( $this->get_value( 'email' ) ) ) {
				$this->errors[] = esc_html__( 'This email is already registered.', 'hivepress' );
			}

			if ( empty( $this->errors ) ) {

				// Get username.
				list($username, $domain) = explode( '@', $this->get_value( 'email' ) );

				if ( $this->get_value( 'username' ) ) {
					$username = $this->get_value( 'username' );
				} else {
					$username = sanitize_user( $username, true );

					if ( empty( $username ) ) {
						$username = 'user';
					}

					while ( username_exists( $username ) ) {
						$username .= wp_rand( 1, 9 );
					}
				}

				// Register user.
				$user_id = wp_create_user( $username, $this->get_value( 'password' ), $this->get_value( 'email' ) );

				if ( ! is_wp_error( $user_id ) ) {

					// Hide admin bar.
					update_user_meta( $user_id, 'show_admin_bar_front', 'false' );

					// Authenticate user.
					wp_set_auth_cookie( $user_id, true );

					// Send emails.
					wp_new_user_notification( $user_id );

					// todo send email.
				}
			}
		}

		return empty( $this->errors );
	}
}
