<?php
/**
 * LSX API Manager Class
 *
 * @package   LSX API Manager
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_CF_API_Manager {

	/**
	 * Holds the API Key
	 *
	 * @var      string
	 */
	public $api_key = false;

	/**
	 * Holds the mode (dev/live)
	 *
	 * @var      string
	 */
	public $dev_mode = false;

	/**
	 * Holds the Email address used to purchase the API key
	 *
	 * @var      string
	 */
	public $email = false;

	/**
	 * Holds the Products Title
	 *
	 * @var      string
	 */
	public $product_id = false;

	/**
	 * Holds the Products Slug
	 *
	 * @var      string
	 */
	public $product_slug = false;

	/**
	 * Holds the current version of the plugin
	 *
	 * @var      string
	 */
	public $version = false;

	/**
	 * Holds the unique password for this site.
	 *
	 * @var      string
	 */
	public $password = false;

	/**
	 * Holds any messages for the user.
	 *
	 * @var      string
	 */
	public $messages = false;

	/**
	 * Holds any path to the plugin file.
	 *
	 * @var      string
	 */
	public $file = false;

	/**
	 * Holds the activate / deactivate button.
	 *
	 * @var      string
	 */
	public $button = false;

	/**
	 * Holds the documentation slug button.
	 *
	 * @var      string
	 */
	public $documentation = false;

	/**
	 * Holds class instance
	 *
	 * @var      string
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 */
	public function __construct($api_array = array()) {

		if(isset($api_array['api_key'])){
			$api_array['api_key'] = trim($api_array['api_key']);
			if('dev-' === substr($api_array['api_key'], 0, 4)){
				$this->dev_mode = true;
				$api_array['api_key'] = preg_replace('/^(dev-)(.*)$/i', '${2}', $api_array['api_key']);
			}
			$this->api_key = $api_array['api_key'];
		}
		if(isset($api_array['email'])){
			$this->email = trim($api_array['email']);
		}
		if(isset($api_array['product_id'])){
			$this->product_id = $api_array['product_id'];
			$this->product_slug = sanitize_title($api_array['product_id']);
		}
		if(isset($api_array['version'])){
			$this->version = $api_array['version'];
		}
		if(isset($api_array['instance'])){
			$this->password = $api_array['instance'];
		}
		if(isset($api_array['file'])){
			$this->file = $api_array['file'];
		}

		if(isset($api_array['documentation'])){
			$this->documentation = $api_array['documentation'];
		}

		if ($this->dev_mode) {
			$this->api_url = 'https://lsdev.feedmybeta.com/wc-api/product-key-api';
			$this->products_api_url = 'https://lsdev.feedmybeta.com/';
			$this->license_check_url = 'https://lsdev.feedmybeta.com/wc-api/license-status-check';
		} else {
			$this->api_url = 'https://www.lsdev.biz/wc-api/product-key-api';
			$this->products_api_url = 'https://www.lsdev.biz/';
			$this->license_check_url = 'https://www.lsdev.biz/wc-api/license-status-check';
		}

		add_filter( 'plugin_action_links_' . plugin_basename(str_replace('.php','',$this->file).'/'.$this->file), array($this,'add_action_links'));
		$this->status = get_option($this->product_slug.'_status',false);

		if( isset( $_GET['page'] ) && in_array( $_GET['page'], array('cf-zoho') ) ) {

			//Maybe activate the software, do this before the status checks.
			$this->activate_deactivate();
			if(false === $this->status){
				$this->status = $this->check_status();
				update_option($this->product_slug.'_status',$this->status);
			}

			$button_url = '<a data-product="'.$this->product_slug.'" style="margin-top:-5px;" href="';
			$button_label = '';
			$admin_url_base = 'options-general.php?page=cf-zoho';
			if(false === $this->status || 'inactive' === $this->status){
				$button_url .= admin_url($admin_url_base.'&action=activate&product='.$this->product_slug);
				$button_label = 'Activate';
			}elseif('active' === $this->status){
				$button_url .= admin_url($admin_url_base.'&action=deactivate&product='.$this->product_slug);
				$button_label = 'Deactivate';
			}
			$button_url .= '" class="button-secondary activate">'.$button_label.'</a>';
			$this->button = $button_url;
		}

		//TODO This transient is not running before the get_site_transient so it doesnt get included
		add_filter( 'site_transient_update_plugins', array( $this,'injectUpdate' ), 20, 1 );
		//add_action( "in_plugin_update_message-cf-zoho/".$this->file, array($this,'plugin_update_message') , 10 );

		add_action( 'cf_zoho_settings_tab', array( $this, 'dashboard_tabs' ), 1, 1 );

		add_action('wp_ajax_wc_api_'.$this->product_slug,array($this,'activate_deactivate'));
		add_action('wp_ajax_nopriv_wc_api_'.$this->product_slug,array($this,'activate_deactivate'));
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Outputs the dashboard tab pages.
	 *
	 * @since 1.0.0
	 *
	 * @return    object A single instance of this class.
	 */
	public function dashboard_tabs($tab='general') {
		if('api' !== $tab){ return false;}

		if('active' === $this->status){
			$description = __( '<span style="color:#008000;">Your license is now active</span>', $this->product_slug );
		}else{
			$description = __( 'You can find your key on your <a target="_blank" href="https://www.lsdev.biz/my-account/">My Account</a> page.', $this->product_slug );
		}

		?>
		<tr class="form-field <?php echo $this->product_slug; ?>-wrap">
			<th class="<?php echo $this->product_slug; ?>_table_heading" style="padding-bottom:0px;" scope="row" colspan="2">

				<?php
				$colour = 'red';
				if('active' === $this->status){
					$colour = 'green';
				}
				?>

				<h4 style="margin-bottom:0px;">
					<span><?php echo $this->product_id; ?></span>
					- <span><?php echo $this->version; ?></span>
					- <span style="color:<?php echo $colour;?>;"><?php echo $this->status; ?></span>
					- <?php echo $this->button; ?>
				</h4>

				<?php if ( $this->dev_mode && is_array( $this->messages ) ) { ?><p><small class="messages" style="font-weight:normal;"><?php echo implode( '. ', $this->messages ); ?></small></p><?php }  ?>

			</th>
		</tr>

		<tr class="form-field api-email-wrap">
			<th style="font-size:13px;" scope="row">
				<i class="dashicons-before dashicons-email-alt"></i> <?php esc_html_e( 'Registered Email', $this->product_slug ); ?>
			</th>
			<td>
				<input type="text" {{#if api_email}} value="{{api_email}}" {{/if}} name="api_email" /><br />
			</td>

		</tr>
		<tr class="form-field api-key-wrap">
			<th style="font-size:13px;" scope="row">
				<i class="dashicons-before dashicons-admin-network"></i> <?php esc_html_e( 'API Key', $this->product_slug ); ?>
			</th>
			<td>
				<input type="text" {{#if api_key}} value="{{api_key}}" {{/if}} name="api_key" />
			</td>
		</tr>

		<?php
		$this->settings_page_scripts();
	}

	/**
	 * outputs the scripts for the dashboard settings pages.
	 */
	public function settings_page_scripts(){ ?>
		{{#script}}
		jQuery( function( $ ){
			$( '.api-email-wrap input' ).on( 'change', function() {
				$('input[name="<?php echo $this->product_slug; ?>_api_action"]').remove();

				var action = 'activate';
				if('' == $(this).val() || undefined == $(this).val()){
				action = 'deactivate';
				}

				console.log($('.<?php echo $this->product_slug; ?>-wrap'));
				$('.<?php echo $this->product_slug; ?>-wrap').append('<input type="hidden" value="'+ action +'" name="<?php echo $this->product_slug; ?>_api_action" />');
			});

			$( '.activate[data-product="<?php echo $this->product_slug; ?>"]' ).on( 'click', function() {
				event.preventDefault();

				var url = $(this).attr('href');
				$('a[data-save-object="true"]').click();

				var maxTime = 10000; // 2 seconds
				var time = 0;

				var interval = setInterval(function () {
					if( ! $('a[data-save-object="true"] span').is(':visible') ) {
						// visible, do something
						clearInterval(interval);
						window.location.href = url;
					} else {
						console.log( time );
						if (time > maxTime) {
						// still hidden, after 2 seconds, stop checking
							clearInterval(interval);
							return;
						}

						// not visible yet, do something
						time += 100;
					}
				}, 200);


			});

		});
		{{/script}}
		<?php
	}

	/**
	 * Return an instance of this class.
	 */
	public function activate_deactivate(){
		if(isset($_GET['action']) && 'activate' === $_GET['action']
			&& isset($_GET['product']) && $this->product_slug === $_GET['product']
			&& false !== $this->api_key && '' !== $this->api_key
			&& false !== $this->email && '' !== $this->email){

			$response = $this->query('activation');
			if(is_object($response) && isset($response->activated) && true === $response->activated){
				update_option($this->product_slug.'_status','active');
				$this->status = 'active';
			}
		}

		if((isset($_GET['action']) && 'deactivate' === $_GET['action'] && isset($_GET['product']) && $this->product_slug === $_GET['product'])
			|| (false === $this->api_key || '' === $this->api_key || false === $this->email || '' === $this->email)){

			if('active' === $this->status) {
				$this->query('deactivation');
				update_option($this->product_slug.'_status','inactive');
				$this->status = 'inactive';
			}
		}
	}

	/**
	 * Generates the API URL
	 */
	public function create_software_api_url( $args ) {

		$endpoint = 'am-software-api';
		if('pluginupdatecheck' === $args['request']){
			$endpoint = 'upgrade-api';
		}
		$api_url = add_query_arg( 'wc-api', $endpoint, $this->products_api_url );
		return $api_url . '&' . http_build_query( $args );
	}

	/**
	 * Checks if the software is activated or deactivated
	 * @return string
	 */
	public function check_status($response = false) {
		if(false === $response){
			$response = $this->query('status');
		}
		if ( $this->dev_mode ) {
			$this->messages[] = print_r( $response, true );
		}
		$status = 'inactive';
		if(is_object($response)){

			if(isset($response->error)){
				$this->messages[] = $this->format_error_code($response->code);
			}elseif(isset($response->status_check)){
				$status = $response->status_check;
				if(isset($response->activations_remaining)){
					$this->messages[] = $response->activations_remaining;
				}
				if(isset($response->message)){
					$this->messages[] = $response->message;
				}
			}
		}
		return $status;
	}

	/**
	 * Does the actual contacting to the API.
	 * @param  string $action
	 * @return array
	 */
	public function query($action='status') {
		if ( 'status' === $action ) {
			$transient_status_id = 'lsx_addon_' . $this->product_id . '_status';
			$response =  get_transient( $transient_status_id );
		} else {
			$response = false;
		}

		if ( ! $response ) {
			$args = array(
				'request' 		=> $action,
				'email' 		=> $this->email,
				'licence_key'	=> $this->api_key,
				'product_id' 	=> $this->product_id,
				'platform' 		=> home_url(),
				'instance' 		=> $this->password
			);
			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

			$request = wp_remote_get( $target_url );

			if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				// Request failed
				return false;
			}
			$response = wp_remote_retrieve_body( $request );
			if ( $this->dev_mode ) {
				$this->messages[] = print_r( $response, true );
			}
			set_transient( $transient_status_id, $response, MINUTE_IN_SECONDS );
		}

		return json_decode($response);
	}

	/**
	 * Formats the error code into a readable format.
	 * @param  array $args
	 * @return array
	 */
	public function format_error_code($code=false){
		switch ( $code ) {
			case '101' :
				$error = array( 'error' => esc_html__( 'Invalid API License Key. Login to your My Account page to find a valid API License Key', $this->product_slug ), 'code' => '101' );
				break;
			case '102' :
				$error = array( 'error' => esc_html__( 'Software has been deactivated', $this->product_slug ), 'code' => '102' );
				break;
			case '103' :
				$error = array( 'error' => esc_html__( 'Exceeded maximum number of activations', $this->product_slug ), 'code' => '103' );
				break;
			case '104' :
				$error = array( 'error' => esc_html__( 'Invalid Instance ID', $this->product_slug ), 'code' => '104' );
				break;
			case '105' :
				$error = array( 'error' => esc_html__( 'Invalid API License Key', $this->product_slug ), 'code' => '105' );
				break;
			case '106' :
				$error = array( 'error' => esc_html__( 'Subscription Is Not Active', $this->product_slug ), 'code' => '106' );
				break;
			default :
				$error = array( 'error' => esc_html__( 'Invalid Request', $this->product_slug ), 'code' => '100' );
				break;
		}
	}

	public static function generatePassword($length = 20) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}

		return $result;
	}

	public function set_update_status(){
		$this->status = $this->check_status();
		$this->upgrade_response = get_transient($this->product_slug.'_upgrade_response',false);

		if(false !== $this->upgrade_response){
			$this->upgrade_response = maybe_unserialize($this->upgrade_response);
		}

		if(isset($this->status) && 'active' === $this->status && false === $this->upgrade_response){
			$args = array(
				'request' 			=> 'pluginupdatecheck',
				'plugin_name' 		=> $this->product_slug.'/'.$this->file,
				'version' 			=> $this->product_slug,
				'activation_email' 	=> $this->email,
				'api_key'			=> $this->api_key,
				'product_id' 		=> $this->product_id,
				'domain' 			=> home_url(),
				'instance' 			=> $this->password,
				'software_version'	=> $this->version,
			);
			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );
			$request = wp_remote_get( $target_url );
			if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				// Request failed
				$this->upgrade_response=false;
			}
			$response = wp_remote_retrieve_body( $request );
			$this->upgrade_response = maybe_unserialize($response);
			set_transient($this->product_slug . '_upgrade_response', $response, 60 * 30);
		}
	}

	/**
	 * Insert the latest update (if any) into the update list maintained by WP.
	 *
	 * @param StdClass $updates Update list.
	 * @return StdClass Modified update list.
	 */
	public function injectUpdate($updates=false){
		$this->set_update_status();

		if(isset($this->status) && 'active' === $this->status && null !== $this->upgrade_response && is_object($this->upgrade_response) && isset($this->upgrade_response->new_version) && version_compare ( $this->upgrade_response->new_version , $this->version , '>' )){

			//setup the response if our plugin is the only one that needs updating.
			if ( !is_object($updates) ) {
				$updates = new StdClass();
				$updates->response = array();
			}
			$this->upgrade_response->plugin = 'cf-zoho/'.$this->file;
			$this->upgrade_response->slug = 'zoho-crm-addon-for-caldera-forms';
			$this->upgrade_response->id = 'cf-zoho';
			$this->upgrade_response->url = $this->upgrade_response->package;

			$updates->response['cf-zoho/'.$this->file] = $this->upgrade_response;

		}

		return $updates;
	}

	/**
	 * Adds in the "settings" link for the plugins.php page
	 */
	public function add_action_links ( $links ) {
		$admin_url_base = 'options-general.php?page=cf-zoho';
		$documentation = $this->product_slug;
		if(false !== $this->documentation){$documentation = $this->documentation; }
		$mylinks = array(
			'<a href="' . admin_url( $admin_url_base ) . '">'.esc_html__('Settings',$this->product_slug).'</a>',
			'<a href="https://www.lsdev.biz/documentation/'.$documentation.'/" target="_blank">'.esc_html__('Documentation',$this->product_slug).'</a>',
			'<a href="https://www.lsdev.biz/contact-us/" target="_blank">'.esc_html__('Support',$this->product_slug).'</a>',
		);
		return array_merge( $links, $mylinks );
	}

}
