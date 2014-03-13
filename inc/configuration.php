<?php
class Configuration{
	
	public $appid;
	public $appsecret;
	
	function __construct(){
		$this->appid = get_option( APPID );
		$this->appsecret = get_option( APPSECRET );
	}
	
	function saveConfiguration($data){
		if(	!isset($data[ APPID ]) || !isset($data[ APPSECRET ]) )
			return false;
		
		$message = new Messages();
		$this->appid = $data[ APPID ];
		$this->appsecret = $data[ APPSECRET ];
			
		if($this->appid != "" && $this->appsecret != ""){
			$this->saveOrUpdateOption(APPID, $this->appid);
			$this->saveOrUpdateOption(APPSECRET, $this->appsecret);
		}else{
			$message->addErrorMessage("The App Id and the App Secret can't be blank. For more information check the <a href='https://developers.facebook.com/apps' target='_blank'>developers site</a> of Facebook.");
			$message->printMessages();
		}
	}
	
	private function saveOrUpdateOption($optionName, $optionValue, $deprecated = null, $autoload = 'no'){
		if ( get_option( $optionName ) !== false ) {
			update_option( $optionName, $optionValue );
		} else {
			add_option( $optionName, $optionValue, $deprecated, $autoload );
		}
	}
}