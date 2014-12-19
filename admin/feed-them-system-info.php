<?php

function feed_them_system_info_page(){
?>

<div class="fts-help-admin-wrap"> <a class="buy-extensions-btn" href="http://www.slickremix.com/downloads/category/feed-them-social/" target="_blank"><?php _e( 'Get Extensions Here!', 'feed-them-social' ); ?></a>
  <h2><?php _e( 'System Info', 'feed-them-social' ); ?></h2>
  <div class="fts-admin-help-wrap">
    <div class="use-of-plugin"><?php _e( "Can't figure out how to do something and need help? Use our", "feed-them-social" ); ?> <a href="http://www.slickremix.com/support-forum/" target="_blank"><?php _e('Support Forum','feed-them-social')?></a> <?php _e('and someone will respond to your request asap. Usually we will respond the same day, the latest the following day. You may also find some of the existing posts to be helpfull too, so take a look around first. If you do submit a question please','feed-them-social')?> <a href="#" class="fts-debug-report"><?php _e('generate a report','feed-them-social')?></a> <?php _e('and copy the info, ask your question in our','feed-them-social')?> <a href="http://www.slickremix.com/support-forum/" target="_blank"><?php _e('Support Forum','feed-them-social')?></a> <?php _e('then paste the info you just copied. That will help speed things along for sure.','feed-them-social')?></div>
    </h3>
    <h3><?php _e( 'Plugin &amp; System Info', 'feed-them-social' ); ?></h3>
    <p><?php _e( 'Please', 'feed-them-social' ); ?> <a href="#" class="fts-debug-report"><?php _e( 'click here to generate a report', 'feed-them-social' ); ?></a> <?php _e( 'You will need to paste this information along with your question in our', 'feed-them-social' ); ?> <a href="http://www.slickremix.com/support-forum/" target="_blank"><?php _e( 'Support Forum', 'feed-them-social' ); ?></a>. <?php _e( 'Ask your question then paste the copied text below it.', 'feed-them-social' ); ?></p>
    <textarea id="fts-debug-report" readonly="readonly"></textarea>
    <table class="wc_status_table widefat" cellspacing="0">
      <thead>
        <tr>
          <th colspan="2"><?php _e( 'Versions', 'feed-them-social' ); ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php _e('Feed Them Social Plugin version','feed-them-social')?></td>
          <td><?php echo ftsystem_version(); ?></td>
        </tr>
        <tr>
          <td><?php _e('WordPress version','feed-them-social')?></td>
          <td><?php if ( is_multisite() ) echo 'WPMU'; else echo 'WP'; ?>
            <?php echo bloginfo('version'); ?></td>
        </tr>
        <tr>
          <td><?php _e('Installed plugins','feed-them-social')?></td>
          <td><?php
             			$active_plugins = (array) get_option( 'active_plugins', array() );

             			if ( is_multisite() )
							$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

						$active_plugins = array_map( 'strtolower', $active_plugins );

						$wc_plugins = array();

						foreach ( $active_plugins as $plugin ) {
							//if ( strstr( $plugin, 'feed-them-social' ) ) {

								$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

	    						if ( ! empty( $plugin_data['Name'] ) ) {

	    							$wc_plugins[] = $plugin_data['Name'] . ' ' . __('by', 'feed-them-social') . ' ' . $plugin_data['Author'] . ' ' . __('version', 'feed-them-social') . ' ' . $plugin_data['Version'];

	    						}
    						//}
						}

						if ( sizeof( $wc_plugins ) == 0 ) echo '-'; else echo '<ul><li>' . implode( ', </li><li>', $wc_plugins ) . '</li></ul>';

             		?></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th colspan="2"><?php _e( 'Server Environment', 'feed-them-social' ); ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php _e('PHP Version','feed-them-social')?></td>
          <td><?php
                    	if ( function_exists( 'phpversion' ) )
						
						$phpversion = phpversion();
						$phpcheck = '5.2.9';
						
						if($phpversion > $phpcheck) {
							 echo phpversion();
						}
						else {
						    echo phpversion();
							echo '<br/><mark class="no">'; 
							_e('WARNING: ','feed-them-social');
							echo '</mark>';
							 _e('Your version of php must be 5.3 or greater to use this plugin. Please upgrade the php by contacting your host provider. Some host providers will allow you to change this yourself in the hosting control panel too.','feed-them-social');
						}
						
						
                    ?></td>
        </tr>
        <tr>
          <td><?php _e('Server Software','feed-them-social')?></td>
          <td><?php
                    	echo $_SERVER['SERVER_SOFTWARE'];
                    ?></td>
        </tr>
        <tr>
          <td><?php _e('WP Max Upload Size','feed-them-social'); ?></td>
          <td><?php
                    	echo size_format( wp_max_upload_size() );
                    ?></td>
        </tr>
        <tr>
          <td><?php _e('WP Debug Mode','feed-them-social')?></td>
          <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . __('Yes', 'feed-them-social') . '</mark>'; else echo '<mark class="no">' . __('No', 'feed-them-social') . '</mark>'; ?></td>
        </tr>
        <tr>
          <td><?php _e('fsockopen','feed-them-social')?></td>
          <td><?php
 if(function_exists('fsockopen')) {
      _e('fsockopen is ON','feed-them-social');
 }
 else {
	 _e('fsockopen is not enabled and must be set to ON for our plugin to work properly with all feeds.','feed-them-social');
 }
 ?></td>
        </tr>
        
        <tr>
        <td><?php _e('cURL','feed-them-social')?></td>
        <td><?php
		// Script to test if the CURL extension is installed on this server

// Define function to test
function _fts_is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}

// Ouput text to user based on test
if (_fts_is_curl_installed()) {
	 _e('cURL is ','feed-them-social');
	 echo '<span style="color:blue">';
	 _e('installed','feed-them-social');
	 echo '</span> ';
	 _e('on this server','feed-them-social');
	 
	 
} else {
	 _e('cURL is NOT ','feed-them-social');
	 echo '<span style="color:red">';
	 _e('installed','feed-them-social');
	 echo '</span> ';
	 _e('on this server','feed-them-social');
}
?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <!--/fts-admin-help-faqs-wrap--> 
  
  <a class="fts-settings-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
<!--/fts-help-admin-wrap--> 
<script type="text/javascript">
		jQuery('a.fts-debug-report').click(function(){

			if ( ! jQuery('#fts-debug-report').val() ) {

				// Generate report - user can paste into forum
				var report = '`';

				jQuery('thead, tbody', '.wc_status_table').each(function(){

					$this = jQuery( this );

					if ( $this.is('thead') ) {

						report = report + "\n=============================================================================================\n";
						report = report + " " + jQuery.trim( $this.text() ) + "\n";
						report = report + "=============================================================================================\n";

					} else {

						jQuery('tr', $this).each(function(){

							$this = jQuery( this );

							report = report + $this.find('td:eq(0)').text() + ": \t";
							report = report + $this.find('td:eq(1)').text() + "\n";

						});
					}
				});
				report = report + '`';
				jQuery('#fts-debug-report').val( report );
			}
			jQuery('#fts-debug-report').slideToggle('500', function() {
				jQuery(this).select();
			});
      		return false;
		});

	</script>
<?php
}
?>
