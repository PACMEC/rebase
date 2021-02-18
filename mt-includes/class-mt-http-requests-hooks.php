<?php
/**
 * HTTP API: Requests hook bridge class
 *
 * @package paCMec
 * @subpackage HTTP
 * @since 4.7.0
 */

/**
 * Bridge to connect Requests internal hooks to paCMec actions.
 *
 * @since 4.7.0
 *
 * @see Requests_Hooks
 */
class MT_HTTP_Requests_Hooks extends Requests_Hooks {
	/**
	 * Requested URL.
	 *
	 * @var string Requested URL.
	 */
	protected $url;

	/**
	 * paCMec MT_HTTP request data.
	 *
	 * @var array Request data in MT_Http format.
	 */
	protected $request = array();

	/**
	 * Constructor.
	 *
	 * @param string $url     URL to request.
	 * @param array  $request Request data in MT_Http format.
	 */
	public function __construct( $url, $request ) {
		$this->url     = $url;
		$this->request = $request;
	}

	/**
	 * Dispatch a Requests hook to a native paCMec action.
	 *
	 * @param string $hook       Hook name.
	 * @param array  $parameters Parameters to pass to callbacks.
	 * @return bool True if hooks were run, false if nothing was hooked.
	 */
	public function dispatch( $hook, $parameters = array() ) {
		$result = parent::dispatch( $hook, $parameters );

		// Handle back-compat actions.
		switch ( $hook ) {
			case 'curl.before_send':
				/** This action is documented in mt-includes/class-mt-http-curl.php */
				do_action_ref_array( 'http_api_curl', array( &$parameters[0], $this->request, $this->url ) );
				break;
		}

		/**
		 * Transforms a native Request hook to a paCMec action.
		 *
		 * This action maps Requests internal hook to a native paCMec action.
		 *
		 * @see https://github.com/rmccue/Requests/blob/master/docs/hooks.md
		 *
		 * @since 4.7.0
		 *
		 * @param array $parameters Parameters from Requests internal hook.
		 * @param array $request Request data in MT_Http format.
		 * @param string $url URL to request.
		 */
		do_action_ref_array( "requests-{$hook}", $parameters, $this->request, $this->url ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

		return $result;
	}
}
