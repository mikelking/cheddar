<?php
/**
 * Utility class to more easily add pointers.
 *
 * @package genesis-design-pro
 */

namespace DPP\Utilities;

/**
 * Class Pointer.
 */
class Pointer {

	/**
	 * The current screen ID.
	 *
	 * @var string
	 */
	public $screen_id;

	/**
	 * Array of pointers that have been marked as valid
	 *
	 * @var array
	 */
	public $valid;

	/**
	 * Registered pointers.
	 *
	 * @var array
	 */
	public $pointers = [];

	/**
	 * Pointer constructor.
	 *
	 * @param array $pointers Pointers to register.
	 */
	public function __construct( $pointers = [] ) {
		$screen          = get_current_screen();
		$this->screen_id = $screen->id;

		$this->register_pointers( $pointers );

		add_action( 'admin_enqueue_scripts', [ $this, 'add_pointers' ], 1000 );
		add_action( 'admin_head', [ $this, 'add_scripts' ] );
	}

	/**
	 * Adds pointers to $pointers property.
	 *
	 * @param array $pointers Pointers to register.
	 */
	public function register_pointers( $pointers ) {
		foreach ( $pointers as $pointer ) {
			if ( $pointer['screen'] === $this->screen_id ) {
				$this->pointers[ $pointer['id'] ] = [
					'screen'  => $pointer['screen'],
					'target'  => $pointer['target'],
					'options' => [
						'content'  => sprintf(
							'<h3> %s </h3> <p> %s </p>',
							$pointer['title'],
							$pointer['content']
						),
						'position' => $pointer['position'],
					],
				];
			}
		}
	}

	/**
	 * Adds the pointers.
	 */
	public function add_pointers() {
		$pointers = $this->pointers;

		if ( ! $pointers || ! is_array( $pointers ) ) {
			return;
		}

		$dismissed      = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$valid_pointers = [
			'pointers' => [],
		];

		// Check pointers and remove dismissed ones.
		foreach ( $pointers as $pointer_id => $pointer ) {
			if (
				in_array( $pointer_id, $dismissed, true ) ||
				empty( $pointer ) ||
				empty( $pointer_id ) ||
				empty( $pointer['target'] ) ||
				empty( $pointer['options'] )
			) {
				continue;
			}

			$pointer['pointer_id'] = $pointer_id;

			$valid_pointers['pointers'][] = $pointer;
		}

		if ( empty( $valid_pointers ) ) {
			return;
		}

		$this->valid = $valid_pointers;

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}

	/**
	 * Adds pointers script.
	 */
	public function add_scripts() {
		$pointers = $this->valid;

		if ( empty( $pointers ) ) {
			return;
		}

		// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
		?>
		<script>
		jQuery( document ).ready( function($) {
			var WPHelpPointer = <?php echo json_encode( $pointers ); ?>;

			if ( typeof WPHelpPointer.pointers !== "undefined" ) {
				$.each(WPHelpPointer.pointers, function (i) {
					dpp_help_pointer_open(i);
				});
			}

			/**
			 * Opens the pointer.
			 *
			 * @param {{}} i The current pointer.
			 */
			function dpp_help_pointer_open( i ) {
				var pointer = WPHelpPointer.pointers[i],
					options = $.extend( pointer.options, {
						close: function() {
							$.post( ajaxurl, {
								pointer: pointer.pointer_id,
								action: 'dismiss-wp-pointer'
							});
						}
					});
				$( pointer.target ).pointer( options ).pointer( 'open' );
			}
		});
		</script>
		<?php
	}
}
