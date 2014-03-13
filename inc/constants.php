<?php 
define( 'GN_VERSION', '1.3' );
define( 'GN_PATH', dirname( __FILE__ ) );

define( 'APPID', 'fai_appid' );
define( 'APPSECRET', 'fai_appsecret' );


define("POST_FORMAT_GALLERY", "Gallery");
define("POST_FORMAT_IMAGE", "Image");
define("DOWNLOAD_YES", "Yes");
define("DOWNLOAD_NO", "No");
define("IMAGE_NO_NAME", "Unamed Image");
define("GALLERY_NO_NAME", "Unnamed Galery");

function debug($data){
	var_dump($data);
}

function debugdie($data){
	debug($data);
	die();
}