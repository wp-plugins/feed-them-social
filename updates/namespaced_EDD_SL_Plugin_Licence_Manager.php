<?php
namespace feedthemsocial;
/***
 * Plugins Activation Manager
 *
 * @author SlickRemix
 */
class EDD_SL_Plugin_Licence_Manager	{

	// static variables
	private static	$instance	= false;
	
	public function __construct($plugin_identifier, $item_name, $store_url)
	{
	    $this->plugin_identifier   = $plugin_identifier;
		$this->item_name		   = $item_name;
		$this->store_url		   = $store_url;
		
		$this->install();
	}
	function install() {
		if ( !self::$instance ) {
			self::$instance = true;
			  add_action('admin_menu', array($this, 'license_menu')); 
		}
		add_action('admin_init', array($this, 'register_option'));
		add_action('admin_init', array($this, 'activate_license'));
		add_action('admin_init', array($this, 'deactivate_license'));
	}
function license_menu() {
	add_plugins_page( 'Plugin License', 'Plugin License', 'manage_options', 'pluginname-license', array($this, 'license_page') );
}

function license_page() {
?>
	<div class="wrap">
			<h2><?php _e('Plugin License Options'); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields('EDD_license_manager_page'); ?>
				
				<table class="form-table">
					<tbody>
                   		<?php  do_settings_fields(__FILE__,'main_section');?>
					</tbody>
				</table>	
				<?php submit_button(); ?>
			</form>
<?php
	}

function register_option() {
	// creates our settings in the options table
	register_setting('EDD_license_manager_page',$this->plugin_identifier.'_license_key', array($this,'edd_sanitize_license') );
	add_settings_section('main_section', '', null, __FILE__);
	add_settings_field($this->plugin_identifier.'_license_key', '',  array($this,'registered_option'), __FILE__, 'main_section');
}

function main_registered_option(){
}
function registered_option()  {
	$license 	= get_option($this->plugin_identifier.'_license_key');
	$status 	= get_option($this->plugin_identifier.'_license_status');
	?>
	<tr valign="top">	
		<th scope="row" valign="top">
			<?php _e($this->item_name.''); ?>
		</th>
		<td>
			<input id="<?php echo $this->plugin_identifier?>_license_key" name="<?php echo $this->plugin_identifier?>_license_key" type="text" placeholder="<?php _e('Enter your license key'); ?>" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
			<label class="description" for="<?php print $this->plugin_identifier?>_license_key"><?php if( $status !== false && $status == 'valid' ) { ?>
                  <?php wp_nonce_field('license_page_nonce','license_page_nonce'); ?>
                  <input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                  <span style="color:green;"><?php _e('- Active'); ?></span>
              <?php } else {
                  wp_nonce_field('license_page_nonce','license_page_nonce'); ?>
                  <input type="submit" class="button-secondary" name="edd_license_activate"  value="<?php _e('Activate License'); ?>"/>
              <?php } ?></label>
		</td>
	</tr>
<?php 
}
	

	function edd_sanitize_license( $new ) {
		$old = get_option( $this->plugin_identifier.'_license_key' );
		if( $old && $old != $new ) {
			delete_option( $this->plugin_identifier.'_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}

	
	/************************************
		activate license key
	*************************************/
	/**
	 * Activate API request
	 * @return type
	 */
	function activate_license() {
	
		// listen for our activate button to be clicked
		if( isset( $_POST['edd_license_activate'] ) ) {
	
	
			// retrieve the license from the database
			$license = trim( get_option($this->plugin_identifier.'_license_key') );
				
	
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'activate_license', 
				'license' 	=> $license, 
				'item_name' => urlencode($this->item_name), // the name of our product in EDD
				'url'       => home_url(),
			);
			
			// Call the custom API.
			$response = wp_remote_get(add_query_arg( $api_params, $this->store_url), array( 'timeout' => 15, 'sslverify' => false ) );
	
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
	
			// decode the license data
			$license_data = json_decode(wp_remote_retrieve_body($response) );
			
			// $license_data->license will be either "active" or "inactive"
	
			update_option($this->plugin_identifier.'_license_status', $license_data->license);
			
			} 
	}
	
	
	/***********************************************
		deactivate a license key.
	***********************************************/
	function deactivate_license() {
	
		// listen for our activate button to be clicked
		if( isset( $_POST['edd_license_deactivate'] ) ) {
	
			// retrieve the license from the database
			$license = trim( get_option( $this->plugin_identifier.'_license_key' ) );
				
	
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'deactivate_license', 
				'license' 	=> $license, 
				'item_name' => urlencode($this->item_name), // the name of our product in EDD
				'url'       => home_url(),
			);
			
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $this->store_url), array( 'timeout' => 15, 'sslverify' => false ) );
	
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' )
				delete_option( $this->plugin_identifier.'_license_status' );
	
		}
	}


}//END Slick_Licence_Manager
?>