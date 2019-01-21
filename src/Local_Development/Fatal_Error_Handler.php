<?php
/**
 * Local Development
 *
 * @package local-development
 * @author Andy Fragen <andy@thefragens.com>
 * @license GPLv2
 * @link https://github.com/afragen/local-development
 */

namespace Fragen\Local_Development;

/**
 * Class Shutdown_Handler
 *
 * @link https://gist.github.com/westonruter/583a42392a0b8684dc268b40d44eb7f1
 */
class Fatal_Error_Handler extends \WP_Fatal_Error_Handler {
	/**
	 * Handle.
	 *
	 * @return void
	 */
	public function handle() {
		// No-op.
	}
}
