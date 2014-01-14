<?php
/**************************************************************
 *                                                            *
 *   Provides a notification to the user everytime            *
 *   your WordPress Theme is updated                          *
 *															  *
 *	 Based on the script by Unisphere:						  *
 *   https://github.com/unisphere/unisphere_notifier          *
 *                                                            *
 *   Author: Jeton Ramadani                                   *
 *   Profile: http://themeforest.net/user/jetonr              *
 *   Follow me: http://twitter.com/jetonr		              *
 *                                                            *
 **************************************************************/

/**************** CHAGNE THE FOLLOWING VALUES *****************/

define( 'NOTIFIER_XML_FILE', 'http://www.yourdomain.com/johndoe/notifier.xml' );  // path to notifier.xml
define( 'NOTIFIER_CACHE_INTERVAL', 6 ); // interval to refresh the xml in database with remote XML (default 6 hours)
define( 'NOTIFIER_THEMEFOREST_USERNAME', 'yourthemeforestusername' ); // your themeforest username

/**************** DO NOT EDIT AFTER THIS LINE *****************/

/* Add Dashboard page and notice for theme update */ 
function update_notifier_menu() {
	
	$xml = get_latest_theme_version();
	if( isset( $xml->latest )    ? $latest_ver = $xml->latest    : $latest_ver  = false );  
	if( isset( $xml->changelog ) ? $changelog  = $xml->changelog : $changelog   = false );

	$theme_data 	= wp_get_theme(); // since WordPress 3.4.0
	$theme_ver 	    = $theme_data->get('Version');
	$dashboard_slug = sanitize_title( $theme_data .'-update' );
	$how_to_page    = admin_url( 'themes.php?page='. $dashboard_slug );
	$diss_notice    = get_user_meta( get_current_user_id(), '_upnf_update_hide_notice', true );
	$comparison		= version_compare( $latest_ver, $theme_ver );
		
	if( ( $comparison == 1 ) && ( !$diss_notice ) ) {
		echo '<div class="updated ntf_hide"><p>There is a new version of the <strong>'. $theme_data .'</strong> theme available. You have version<strong> '. $theme_ver .' </strong>installed. Update to version <strong>'. $latest_ver. '</strong></p>';
		echo '<p><strong><a href='.$how_to_page.'>Update Instructions</a> | <a class="dismiss-notice" href="' .  esc_url( add_query_arg( 'upnf_update_nag', wp_create_nonce( 'upnf_update_nag' ) ) ) . '" target="_parent">Dismiss this notice</a></strong></p>';
		echo '</div>';
	}
	
	if( $comparison == 1 ) {
		add_theme_page($theme_data .'Theme Updates', 'Theme Updates <span class="update-plugins count-1"><span class="update-count">1</span></span>', 'administrator', $dashboard_slug, 'update_notifier');
	}
}
add_action('admin_menu', 'update_notifier_menu');

/* Hide theme update notice if user dissmised */
function update_notifier_hide_notice() {
    if ( ! isset( $_GET['upnf_update_nag'] ) ) {
        return;
    }
    // Check nonce
    check_admin_referer( 'upnf_update_nag', 'upnf_update_nag' );
 
    // updated user meta to indicate dismissed notice
    update_user_meta( get_current_user_id(), '_upnf_update_hide_notice', 1 );
}
add_action( 'admin_init', 'update_notifier_hide_notice' );

/* Update Information Page */
function update_notifier() {
	
	$xml = get_latest_theme_version();
	if( isset( $xml->latest )    ? $latest_ver = $xml->latest    : $latest_ver = false );  
	if( isset( $xml->changelog ) ? $changelog  = $xml->changelog : $changelog  = false );
	  
	$theme_data  = wp_get_theme(); 					// since WordPress 3.4.0
	$theme_ver   = $theme_data->get('Version'); 	// Theme Version
	$theme_URI   = $theme_data->get('ThemeURI'); 	// Theme Demo URL
	$author_name = $theme_data->get('Author');  	// Theme Author Name
	$author_URI  = $theme_data->get('AuthorURI'); 	// Theme Author Url 
	$themeFolder = get_template(); 					// Template Folder Name

	add_thickbox(); // Add Thickbox to open Videos and images
?>	
	<style>
	.ntf_border { border-right: 1px solid #e5e5e5; };
	.ntf_center { margin: 0 auto; text-align: center; }
	.ntf_hide   { display: none; }
	</style>
    
	<div class="wrap">
    	<div id="icon-themes" class="icon32"></div>
		<h2>Updating <?php echo $theme_data; ?> Theme</h2>
		<table class="wp-list-table widefat" cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="ntf_border"><strong><?php echo $theme_data; ?> Screenshot</strong></th>
              <th scope="col" class="ntf_border"><strong>Update Instructions</strong></th>
              <th scope="col" class="ntf_border"><strong>Version <?php echo $latest_ver; ?> Changelog</strong></th>
            </tr>
          </thead>
		  <tfoot>
		    <tr>
		      <th><?php echo $theme_data; ?> is Designed by <a href="<?php echo $author_URI; ?>" title="Visit author homepage" target="_blank"><?php echo $author_name; ?></a></th>
              <th></th>
              <th></th>
            </tr>
		  </tfoot>
          <tbody id="the-list">
          	<tr>
              <td class="ntf_border ntf_center">
                  <a href="<?php echo $theme_URI; ?>" title="Visit <?php echo $theme_data; ?> site" target="_blank">
                  <img width="400" height="auto" src="<?php echo get_template_directory_uri() . '/screenshot.png'; ?>" />
                  </a>
              </td>
              <td class="column-description desc ntf_border">
             	  <h3>Method 1 - ( Automatic - The easy way )</h3>
                  <ol>
                      <li>Install and activate <a href="https://github.com/envato/envato-wordpress-toolkit/archive/master.zip">Envato WordPress Toolkit Plugin</a></li>
                      <li><p>Login to your <a href="http://themeforest.net/?ref=<?php echo NOTIFIER_THEMEFOREST_USERNAME; ?>">Themeforest</a> account, go to <strong>Settings -> API Keys</strong>, select and copy your API Key ( <a href="http://i.imgur.com/Mfukg3C.jpg" title="How to Copy Envato API Key" class="thickbox">See Image</a> )</p></li>
                      <li>Go to <strong>WP Admin -> Envato Toolkit plugin page</strong>, type in your username and paste the API key you copied in the previous step. Click Save Settings.</li>
                  </ol>
                  <p>Once have you filled in your envato marketplace username and  API key, all your purchased themes can be installed and updated on a single click. ( <a href="http://i.imgur.com/ZGzvanc.jpg" title="Envato WordPress Toolkit Plugin Page" class="thickbox">See Image</a> )</p>
              	  <h3>Method 2 - ( Manual - The hard way )</h3>
                  <p><strong>Please note:</strong> make a <strong>backup</strong> of the Theme inside your WordPress installation folder <strong>/wp-content/themes/<?php echo $themeFolder; ?>/</strong></p>
                  <ol>
                      <li><p>Login to your <a href="http://themeforest.net/?ref=<?php echo NOTIFIER_THEMEFOREST_USERNAME; ?>">Themeforest</a> account, head over to your <strong>downloads</strong> section and re-download the theme</p></li>
                      <li>Make sure you've backed up your data via Tools > Export</li>
                      <li>Switch Back to the default Theme (Twenty Twelve) momentarily</li>
                      <li>Delete your current version of "<?php echo $theme_data; ?>". (this is why it's important to backup any changes you've made to the theme files)</li>
                      <li>Install the Theme Via your Wordpress Dashboard. <a href="http://www.youtube.com/embed/q4iO0qxfN5g?feature=oembed&amp;showinfo=0&amp;iv_load_policy=3&amp;modestbranding=0&amp;nologo=1&amp;vq=large&amp;autoplay=1&amp;ps=docs&amp;wmode=opaque&amp;rel=0&amp;TB_iframe=true&width=800&height=500"  title="Theme Install via Wordpress Dashboard" class="thickbox">How to Install your Theme Video</a></li> 
                  </ol>
                  <p>If you didn't make any changes to the theme files, you are free to update withou risk of losing theme settings, pages, posts, and other content.</p>
            </td>
              <td class="column-description desc">
				  <?php echo $changelog; ?>
              </td>
         	</tr>
          </tbody>
		</table>
	</div>  
<?php } 

/* This function retrieves a remote xml file on my server to see if there's a new update */
function get_latest_theme_version() {
	
	$notifier_xml    = NOTIFIER_XML_FILE;
	$theme_data  	 = wp_get_theme(); // since WordPress 3.4.0
	$notifier_name 	 = sanitize_title( $theme_data ) .'-notifier-cache'; // name for transients and options
	$notifier_data 	 = get_transient( $notifier_name ); // read data from transient
	
	libxml_use_internal_errors(true); // !!!
	
	$xml_data 		 = simplexml_load_string( $notifier_data ); 
	
	// if transient expired will return false
	if ( false === $notifier_data ) {

		// cache doesn't exist, or is old, so refresh it
		$response 	   = wp_remote_get( $notifier_xml, array( 'sslverify' => false ) );
		$notifier_data = wp_remote_retrieve_body( $response );
		// if we cannot connect to xml use last update from options 
		if ( is_wp_error($response) )	{
			$notifier_data = get_option($notifier_name);
		}
		
		// Refresh xml_data with new xml file
		$xml_data = simplexml_load_string($notifier_data); 
		
		// We have good response set transient and update option
		if ( isset( $xml_data->latest ) ) {			
			// we got good results
			set_transient( $notifier_name, $notifier_data, NOTIFIER_CACHE_INTERVAL*60*60 );
			update_option( $notifier_name, $notifier_data );
		}
	}

	if( isset( $xml_data->latest ) ) {
		return $xml_data; // Process XML structure here
	} else {
		foreach(libxml_get_errors() as $error) {
			error_log('Error parsing XML file: ' . $error->message);
		}
	}
}

?>