<?php
/**
 * Listing submit complete page template.
 *
 * @package HivePress\Configs\Templates
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'parent' => 'listing_submit_page',

	'blocks' => [
		'page_content' => [
			'blocks' => [
				'listing_complete_message' => [
					'type'     => 'element',
					'filepath' => 'listing/submit/complete-message',
					'order'    => 10,
				],
			],
		],
	],
];