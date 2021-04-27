<?php
/**
 * Elementor widget.
 *
 * @package HivePress\Integrations
 */

namespace HivePress\Integrations;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Elementor widget class.
 *
 * @class Elementor_Widget
 */
final class Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = [], $args = null ) {

		// Get block slug.
		$block_slug = hp\get_array_value( $data, 'widgetType' );

		if ( isset( $args['widgetType'] ) ) {
			$block_slug = $args['widgetType'];
		} else {
			$args['widgetType'] = $block_slug;
		}

		if ( $block_slug ) {

			// Get block class.
			$block_class = '\HivePress\Blocks\\' . str_replace( '-', '_', preg_replace( '/^hivepress-/', '', $block_slug ) );

			if ( class_exists( $block_class ) ) {

				// Set block arguments.
				$args['widgetClass'] = $block_class;
				$args['widgetTitle'] = $block_class::get_meta( 'label' );
			}
		}

		parent::__construct( $data, $args );
	}

	/**
	 * Gets widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->get_default_args( 'widgetType' );
	}

	/**
	 * Gets widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->get_default_args( 'widgetTitle' );
	}

	/**
	 * Gets widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-hivepress';
	}

	/**
	 * Gets widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'hivepress' ];
	}

	/**
	 * Gets widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'hivepress' ];
	}

	/**
	 * Registers widget controls.
	 */
	protected function _register_controls() {

		// Get block class.
		$block_class = $this->get_default_args( 'widgetClass' );

		if ( ! $block_class || ! $block_class::get_meta( 'settings' ) ) {
			return;
		}

		// Add controls section.
		$this->start_controls_section(
			'settings',
			[
				'label' => hivepress()->translator->get_string( 'settings' ),
				'tab'   => 'content',
			]
		);

		// Add controls.
		foreach ( $block_class::get_meta( 'settings' ) as $field_name => $field ) {

			// Get field arguments.
			$field_args = $field->get_args();

			if ( 'checkbox' === $field_args['type'] ) {
				$field_args['type'] = 'switcher';
			} elseif ( isset( $field_args['options'] ) ) {
				if ( is_array( hp\get_first_array_value( $field_args['options'] ) ) ) {
					$field_args['options'] = wp_list_pluck( $field_args['options'], 'label' );
				}

				if ( ! hp\get_array_value( $field_args, 'required' ) && ! isset( $field_args['options'][''] ) ) {
					$field_args['options'] = [ '' => '&mdash;' ] + $field_args['options'];
				}
			}

			// Add control.
			$this->add_control( $field_name, $field_args );
		}

		$this->end_controls_section();
	}

	/**
	 * Renders widget HTML.
	 */
	protected function render() {

		// Get block class.
		$block_class = $this->get_default_args( 'widgetClass' );

		if ( ! $block_class ) {
			return;
		}

		// Create block.
		$block = hp\create_class_instance( $block_class, [ $this->get_settings_for_display() ] );

		if ( $block ) {

			// Render block.
			echo $block->render();
		}
	}
}
