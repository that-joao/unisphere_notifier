# WordPress Theme Update Notifier

This is a simple WordPress theme update notifier that will provide your theme Buyers with a notification every time you issue a theme update.

It's a very simple script that requires and assumes you have an XML file in your own server. This XML file serves as an endpoint for the script to check what the latest version of the theme is, and compares it with the current version of the theme installed in the Client's server.

One of the script **requirements** is that the version number are **floats**. Versions like 1.0, 1.1, 2.3 are acceptable and required.

## Installation

Upload the **notifier.xml** file to your own server (the update notifier on the Client sites will check this file for the latest version)

Copy the **update-notifier.php** file to the root of the theme.

Edit your theme's **functions.php** file and include the **update-notifier.php** file with the following code:

	require('update-notifier.php');
	
Edit the **update-notifier.php** file and make the appropriate changes to the following constants:

	define( 'NOTIFIER_THEME_NAME', 'JohnDoe' ); // The theme name
	define( 'NOTIFIER_THEME_FOLDER_NAME', 'johndoe' ); // The theme folder name
	define( 'NOTIFIER_XML_FILE', 'http://www.yourdomain.com/johndoe/notifier.xml' ); // The remote notifier XML file containing the latest version of the theme and changelog
	define( 'NOTIFIER_CACHE_INTERVAL', 21600 ); // The time interval for the remote XML cache in the database (21600 seconds = 6 hours)
	
Everything should be in place now. Every time you submit a theme update you should edit the XML file in your server and change the following code to the latest version you've updated:

	<latest>1.0</latest>
	
This way, your Clients will see an update notification on the WordPress admin panel informing them that there's a new version available and they should update.

## Screenshot

![theme update notifier](http://content.screencast.com/users/unispheredesign/folders/Jing/media/589c4a03-9155-4af9-8769-608245f12ba4/00000107.png)
