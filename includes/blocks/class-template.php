<?php
/**
 * Template block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Renders a template.
 */
class Template extends Block {

	/**
	 * Template name.
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * Custom blocks.
	 *
	 * @var array
	 */
	protected $blocks = [];

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		$blocks  = [];
		$context = [];

		// Get class.
		$class = '\HivePress\Templates\\' . $this->template;

		if ( class_exists( $class ) ) {
			if ( $class::get_meta( 'label' ) ) {

				// Get content.
				$content = get_page_by_path( $class::get_meta( 'name' ), OBJECT, 'hp_template' );

				if ( $content && 'publish' === $content->post_status ) {

					// Set blocks.
					$blocks = [
						'page_container' => [
							'type'   => 'page',
							'_order' => 10,

							'blocks' => [
								'page_content' => [
									'type'    => 'content',
									'content' => apply_filters( 'the_content', $content->post_content ),
									'_order'  => 10,
								],
							],
						],
					];
				}
			}

			if ( ! $blocks ) {

				// Create template.
				$template = hp\create_class_instance(
					$class,
					[
						[
							'context' => $this->context,
							'blocks'  => $this->blocks,
						],
					]
				);

				if ( $template ) {

					// Set blocks.
					$blocks = $template->get_blocks();

					// Set context.
					$context = $template->get_context();
				}
			}
		}

		return ( new Container(
			[
				'tag'     => false,
				'context' => $context,
				'blocks'  => $blocks,
			]
		) )->render();
	}
}
