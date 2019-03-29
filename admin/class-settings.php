<?php
/**
 * The file that defines admin settings.
 *
 * @package lsx_cf_zoho/admin.
 */

namespace lsx_cf_zoho\admin;

use lsx_cf_zoho;
use lsx_cf_zoho\includes;
use lsx_cf_zoho\includes\zohoapi;

/**
 * Settings API.
 */
class Settings {

	/**
	 * Options class.
	 *
	 * @var \lsx_cf_zoho\includes\Options()
	 */
	public $options;

	/**
	 * Tokens class.
	 *
	 * @var \lsx_cf_zoho\includes\Tokens()
	 */
	private $tokens;

	/**
	 * LSX_Search constructor
	 */
	public function __construct() {
		$this->options = new includes\Options();
		$this->tokens  = new zohoapi\Tokens();
	}

	/**
	 * Register a LSX CF Zoho Settings page.
	 */
	public function settings_page() {

		add_options_page(
			'LSX CF Zoho Options',
			'LSX CF Zoho',
			'manage_options',
			'lsx_cf_zoho',
			[ $this, 'lsx_cf_zoho_settings_page_html' ]
		);
	}

	/**
	 *  Settings page.
	 */
	public function lsx_cf_zoho_settings_page_html() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Test for transient flush.
		if ( true === (bool) $this->options->get_option( 'flush_transients' ) ) {
			$this->flush_transients();
		}

		// Show error/update messages.
		settings_errors( 'lsx_cf_zoho_messages' );

		// Template.
		include_once LSX_CFZ_TEMPLATE_PATH . 'settings-form.php';
	}

	/**
	 * Inits the WP Settings API.
	 */
	public function settings_api_init() {

		// Test for redirect after tokens.
		if ( isset( $_GET['state'] ) ) {
			$this->request_token();
		}

		// Register app details.
		add_settings_section(
			'lsx_cf_zoho_section_developers',
			__( 'Registering a Zoho app for use with the Caldera Forms Zoho plugin', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho'
		);

		// API Details.
		add_settings_section(
			'lsx_cf_zoho_section_api_keys',
			__( 'API Settings', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho'
		);

		// Region.
		add_settings_field(
			'lsx_cf_zoho_url',
			__( 'ZOHO Oauth URL', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho',
			'lsx_cf_zoho_section_api_keys',
			[
				'label_for'          => 'lsx_cf_zoho_url',
				'class'              => 'lsx_cf_zoho_row',
				'lsx_cf_zoho_custom_data' => 'custom',
			]
		);

		// Client ID.
		add_settings_field(
			'lsx_cf_zoho_client_id',
			__( 'ZOHO Client ID', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho',
			'lsx_cf_zoho_section_api_keys',
			[
				'label_for'          => 'lsx_cf_zoho_client_id',
				'class'              => 'lsx_cf_zoho_row',
				'lsx_cf_zoho_custom_data' => 'custom',
			]
		);

		// Client Secret.
		add_settings_field(
			'lsx_cf_zoho_client_secret',
			__( 'ZOHO Client Secret', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho',
			'lsx_cf_zoho_section_api_keys',
			[
				'label_for'          => 'lsx_cf_zoho_client_secret',
				'class'              => 'lsx_cf_zoho_row',
				'lsx_cf_zoho_custom_data' => 'custom',
			]
		);

		// Tokens.
		add_settings_field(
			'lsx_cf_zoho_tokens',
			__( 'Generate Tokens', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_tokens_cb' ],
			'lsx_cf_zoho',
			'lsx_cf_zoho_section_api_keys',
			[
				'label_for'          => 'lsx_cf_zoho_tokens',
				'class'              => 'lsx_cf_zoho_row',
				'lsx_cf_zoho_custom_data' => 'custom',
			]
		);

		// Flush transients.
		add_settings_section(
			'flush_transients',
			__( 'Flush Transients', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho'
		);

		// Enable Debug
		add_settings_section(
			'lsx_cf_zoho_enable_debug',
			__( 'Enable Debug', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho'
		);

		// Enable Debug
		add_settings_section(
			'lsx_cf_zoho_enable_form_blocker',
			__( 'Enable BlockUI.js', 'lsx-cf-zoho' ),
			[ $this, 'lsx_cf_zoho_settings_field_cb' ],
			'lsx_cf_zoho'
		);
	}

	/**
	 * Section templates.
	 */
	private $templates = [
		'lsx_cf_zoho_section_developers'  => 'settings-section.php',
		'lsx_cf_zoho_section_api_keys'    => 'settings-api.php',
		'lsx_cf_zoho_url'                 => 'settings-url.php',
		'lsx_cf_zoho_client_id'           => 'settings-client-id.php',
		'lsx_cf_zoho_client_secret'       => 'settings-client-secret.php',
		'flush_transients'                => 'settings-flush-transients.php',
		'lsx_cf_zoho_enable_debug'        => 'settings-enable-debug.php',
		'lsx_cf_zoho_enable_form_blocker' => 'settings-enable-block-form.php',
	];

	/**
	 * Settings field callback.
	 *
	 * @param array $args Settings arguments.
	 */
	public function lsx_cf_zoho_settings_field_cb( $args ) {

		$id       = isset( $args['id'] ) ? esc_attr( $args['id'] ) : esc_attr( $args['label_for'] );
		$name     = LSX_CFZ_OPTION_SLUG . '[' . $id . ']';
		$value    = $this->options->get_option( $id );
		$template = $this->templates[ $id ];

		include_once LSX_CFZ_TEMPLATE_PATH . $template;
	}

	/**
	 * Generate tokens callback.
	 *
	 * @param array $args Settings arguments.
	 */
	public function lsx_cf_zoho_tokens_cb() {

		$url       = $this->options->get_option( 'lsx_cf_zoho_url' ) . '/auth';
		$url_text  = false === $this->tokens->has_refresh_token() ? 'Generate ' : 'Re-generate ';
		$url_text .= 'Access and Refresh Tokens';

		/**
		 * NB You can set scope to ZohoCRM.modules.leads.CREATE,ZohoCRM.modules.contacts.CREATE,ZohoCRM.modules.tasks.CREATE,
		 * however the response to this does not appear to include a refresh token.
		 */
		$params = [
			'scope'         => 'ZohoCRM.settings.all,ZohoCRM.users.all,ZohoCRM.modules.all',
			'client_id'     => $this->options->get_option( 'lsx_cf_zoho_client_id' ),
			'state'         => wp_create_nonce( 'zohotoken' ),
			'response_type' => 'code',
			'redirect_uri'  => lsx_cf_zoho_redirect_url(),
			'access_type'   => 'offline',
		];

		foreach ( $params as $key => $value ) {
			$url = add_query_arg( $key, $value, $url );
		}

		include_once LSX_CFZ_TEMPLATE_PATH . 'settings-tokens.php';
	}

	/**
	 * Called when a temporary oauth token has been generated.
	 */
	public function request_token() {

		$nonce = filter_input( INPUT_GET, 'state', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'zohotoken' ) ) {
			add_settings_error( 'lsx_cf_zoho_messages', 'lsx_cf_zoho_message', 'The token request is invalid.', 'error' );
			return;
		}

		$connect  = new zohoapi\Connect();
		$response = $connect->generate_token( 'authorization_code' );

		if ( true !== $response ) {
			add_settings_error( 'lsx_cf_zoho_messages', 'lsx_cf_zoho_message', $response, 'error' );
			return;
		}

		$url = menu_page_url( 'lsx_cf_zoho', false );

		// Redirect back to settings page to prevent resubmission.
		header( "Location: {$url}" );
	}

	/**
	 * Flushes any stored module data.
	 */
	public function flush_transients() {

		$cache = new includes\Cache();
		$cache->flush_plugin_cache();

		$this->options->reset_cache_option();

		add_settings_error( 'lsx_cf_zoho_messages', 'lsx_cf_zoho_message', 'Plugin cache successfully flushed', 'updated' );
	}
}
