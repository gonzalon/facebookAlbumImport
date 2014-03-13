<?php 
class FacebookImportBase {

	function __construct() {
		// add scripts and styles only available in admin
		add_action( 'admin_enqueue_scripts', array( $this, 'fai_add_admin_JS' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'fai_add_admin_CSS' ) );
		
		// register admin pages for the plugin
		add_action( 'admin_menu', array( $this, 'fai_admin_pages_callback' ) );
		
		//message for configuration
		add_action('admin_notices', array( $this, 'fai_admin_show_message' ));
	}
	
	function fai_add_admin_JS() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'samplescript-admin', plugins_url( '/js/facebookImport.js' , __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'samplescript-admin' );
	}

	function fai_add_admin_CSS() {
		wp_register_style( 'samplestyle', plugins_url( '/css/style.css', __FILE__ ), array(), '1.0', 'screen' );
		wp_enqueue_style( 'samplestyle' );
	}
	
	function fai_admin_pages_callback() {
		add_menu_page(__( "Facebook Album Import", 'faibase' ), __( "Facebook Album Import", 'faibase' ), 'edit_themes', 'fai-plugin-import', array( $this, 'fai_plugin_import' ) );		
		add_submenu_page( 'fai-plugin-import', __( "Configure", 'faibase' ), __( "Configure", 'faibase' ), 'edit_themes', 'fai-base-configure', array( $this, 'fai_plugin_configure' ) );
	}
	
	function fai_plugin_import() {
		include_once( GN_PATH_INCLUDES . '/importAlbum.php' );
	}
	
	function fai_plugin_configure(){
		include_once( GN_PATH_INCLUDES . '/configure.php' );
	}
	
	function fai_admin_show_message(){
		$message = new Messages();	
		if(!get_option( APPID ) || !get_option( APPSECRET )){
			$message->addErrorMessage('The Facebook Import Album needs to be configured. <a href="admin.php?page=fai-base-configure">Configure it now</a>.');
		}
		$message->printMessages();
	}
}