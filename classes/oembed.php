<?php

class JM_RWD_Video_Embeds {

	public function __construct() {
		add_filter( 'oembed_dataparse', array( __CLASS__, 'oembed_dataparse' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'scripts_front' ) );
	}

	/**
	 * Enqueue scripts for use in front
	 */
	static function scripts_front() {
		wp_register_style( 'rwd-embed', JM_RWD_EMBED_URL . 'css/rwd-embed.min.css', array(), JM_RWD_EMBED_VERSION );
		wp_enqueue_style( 'rwd-embed' );
	}

	/**
	 * Wrap embed in container to make it responsive
	 *
	 * @link https://developer.wordpress.org/reference/hooks/oembed_dataparse/
	 * @author Julien Maury
	 *
	 * @param $return
	 * @param $data
	 *
	 * @return string
	 */
	static function oembed_dataparse( $return, $data ) {

		if ( empty( $data->type ) || 'video' !== $data->type ) {
			return $return;
		}

		if ( ! isset( $data->width, $data->height ) ) {
			return $return;
		}

		return '<div class="iframe-container" ' . self::get_item_padding( $data->width, $data->height ) . '>' .  $return  . '</div>';

	}

	/**
	 * Return padding or nothing
	 *
	 * @param $width
	 * @param $height
	 *
	 * @author Julien Maury
	 * @return bool|string
	 */
	static function get_item_padding( $width, $height ) {
		$width   = absint( $width );
		$height  = absint( $height );
		$ratio   = round( $height / $width, 2 );
		$padding = 100 * $ratio;

		/**
		 * tips props to Greglone
		 * that would go as a margin of tolerance
		 * ultimately the CSS padding (in stylesheet) is there as fallback
		 */

		if ( abs( $padding - 56.25 ) < .04 ) {
			return false;
		}

		return 'style="padding-bottom: ' . $padding . '&#37;"';

	}

}