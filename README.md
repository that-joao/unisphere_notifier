# WordPress Theme Update Notifier

This is a simple WordPress theme update notifier that will provide your theme Buyers with a notification every time you issue a theme update.

It's a very simple script that requires and assumes you have an XML file in your own server. This XML file serves as an endpoint for the script to check what the latest version of the theme is, and compares it with the current version of the theme installed in the Client's server.

## Installation

Upload the **notifier.xml** file to your own server (the update notifier on the Client sites will check this file for the latest version)

Copy the **update-notifier.php** file to the root of the theme.

Edit your theme's **functions.php** file and include the **update-notifier.php** file with the following code:

	require('update-notifier.php');
	
Edit the **update-notifier.php** file and make the appropriate changes to the following constants:

	define( 'NOTIFIER_XML_FILE', 'http://www.yourdomain.com/johndoe/notifier.xml' );  // path to notifier.xml
	define( 'NOTIFIER_CACHE_INTERVAL', 6 ); // interval to refresh the xml in database with remote XML (default 6 hours)
	define( 'NOTIFIER_THEMEFOREST_USERNAME', 'yourthemeforestusername' ); // your themeforest username
	
Everything should be in place now. Every time you submit a theme update you should edit the XML file in your server and change the following code to the latest version you've updated:

	<latest>1.0</latest>
	
This way, your Clients will see an update notification on the WordPress admin panel informing them that there's a new version available and they should update.

**NOTE:** when you submit theme updates make sure to update the theme version number in the **style.css** file. This file is in the root of your theme and the "Version" is the value used for comparison.

## Screenshot

![theme update notifier](http://i.imgur.com/j4kFYer.jpg)
