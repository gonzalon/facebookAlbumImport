<?php

class FacebookAlbumImport{

	private $id;
	private $name;
	private $category;
	private $postFormat;
	private $download;
	private $author;
	private $status;
	
	private $facebookResponse;
	
	public function __construct($id, $name, $category, $postFormat, $download, $author, $status){
		$this->id = $id;
		$this->category = $category;
		$this->postFormat = $postFormat;
		$this->download = $download;
		$this->author = $author;
		$this->status = $status;

		if($name == ""){
			if($this->postFormat == POST_FORMAT_IMAGE){
				$this->name = IMAGE_NO_NAME;
			}else{
				$this->name = GALLERY_NO_NAME;
			}	
		}else{
			$this->name = $name;
		}
	}
	
	public function process(){
		$message = new Messages();
		$ret = 0;
		try{
			$this->initFacebook();
			
			$photos = $this->getFacebookPhotosURL();
			
			if($this->download == DOWNLOAD_YES){
				$photos = $this->downloadPhotos( $photos );
			}
			
			if($this->postFormat == POST_FORMAT_IMAGE){
				$this->createPosts($photos);
			}else{
				$this->createGallery($photos);
			}
		}catch(FacebookException $e){
			$ret = 1;
			$message->addErrorMessage($e->getMessage());
		}catch(ImportException $e1){
			$ret = 1;
			$message->addErrorMessage($e1->getMessage());
		}
		
		$message->printMessages();
		return $ret;
	}
	
	
	/**
	*
	*		Consume the Facebook API and get the photos of the album
	*
	**/
	private function initFacebook(){
		$configuration = new Configuration();
		try{
			require_once GN_PATH_INCLUDES.'/facebook/facebook.php';

			$facebook = new Facebook(array(
					'appId' => $configuration->appid,
					'secret' => $configuration->appsecret,
					'cookie' => true
			));
			
			if(is_null($facebook->getUser())){
				header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
				exit;
			}
			$response = $facebook->api("/$this->id?fields=photos");
			$this->facebookResponse = $response["photos"]["data"];
			
		}catch(Exception $e){
			throw new FacebookException($e); 
		}
	}
	
	
	/**
	*
	*		Get the cleans URL's of the photos in the Facebook response.
	*
	**/
	private function getFacebookPhotosURL(){
		
		$url = array();
		for($i = 0; $i < sizeof( $this->facebookResponse ); $i++){
			$source = $this->facebookResponse[$i]["images"][0]["source"];
			if($source !== null)
				$url[] = $source;
		}
		if(sizeof( $url ) == 0)
			throw new ImportException("Could not get the URL of the Facebook photos", 6);
		
		return $url;
	}
	
	
	/**
	*
	*		Download the photos and return an array of the attach id.
	*
	**/
	private function downloadPhotos( $url ){
		$photos = array();
		try{
			if(sizeof( $url )>0){
				for($i = 0; $i < sizeof( $url ); $i++){
					$ret = $this->importImage( $url[$i] );
					if($ret !== false)
						$photos[] = $ret;
				}
			}
		}catch(Exception $e){
			throw new ImportException($e->getMessage(), 10);
		}
		if(sizeof( $photos ) == 0)
			throw new ImportException("Could not download the photos of the Facebook album", 7);
		
		return $photos;
	}
	
	
	/**
	*
	*		Import the photo to the Wordpress media library.
	*
	**/
	private function importImage($url){
		if( !class_exists( 'WP_Http' ) )
		include_once( ABSPATH . WPINC. '/class-http.php' );
	 
		$http = new WP_Http();
		//make the request
		$photo = $http->request( $url );
	
		if( $photo['response']['code'] != 200 )
			throw new Exception("Could not make the request to the photo; ". $url, 8);
	 
		$attachment = wp_upload_bits( $this->name.".jpg", null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );
	
		if( !empty( $attachment['error'] ) )
			throw new Exception("Wordpress could not upload the photo. " . $attachment['error'], 9);
	 
		$filetype = wp_check_filetype( basename( $attachment['file'] ), null );
	 
		$imageInfo = array(
			'post_mime_type' => $filetype['type'],
			'post_title' => $this->name,
			'post_content' => '',
			'post_status' => 'inherit',
		);
	
		$filename = $attachment['file'];
		$attach_id = wp_insert_attachment( $imageInfo, $filename );
	 
		if( !function_exists( 'wp_generate_attachment_data' ) )
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		return $attach_id;
	}
	
	private function createPosts($photos){
		$count = sizeof( $photos );
		if( $count > 0 ){
			for($i = 0; $i < $count; $i++){
				if($this->download == DOWNLOAD_YES){
					$photo = wp_get_attachment_url( $photos[$i] );
				}else{
					$photo = $photos[$i];
				}
				$postId = $this->createPost($photo);
				$this->setPostFormat($postId, 'image');
				if($this->download == DOWNLOAD_YES)
					$this->setPostThumbnail($postId, $photos[$i]);
			}
		}
	}
	
	private function createGallery($photos){
		$postId = $this->createPost($photos);
		$this->setPostFormat($postId, 'gallery');
	}
	
	private function createPost($photo){
		$content = $this->createContent($photo);
		
		$post = array(
			'post_title' => $this->name,
			'post_content' => $content,
			'post_status' => $this->status,
			'post_author' => $this->author,
			'post_category' => array($category)
		);
	 
		// Insert the post into the database
		$postId = wp_insert_post( $post );
	 
		return $postId;
	}
	
	private function createContent($data){
		$content;
		if(is_array($data)){
			if($this->download == DOWNLOAD_NO){
				for($i=0; $i < sizeof($data); $i++){
					$content = $content . '<img src="'.$data[$i].'" alt="'.$this->name.'" title="'.$this->name.'"/>';
				}
			}else{
				$content = "[gallery link='none' ids='". implode(",",$data) ."' orderby='rand']";
			}
		}else{
			$content = '<img src="'.$data.'" alt="'.$this->name.'" title="'.$this->name.'"/>';
		}
		return $content;
	}
	
	private function setPostFormat($postId, $postFormat){
		set_post_format($postId, $postFormat );
	}
	
	private function setPostThumbnail($postId, $attachId){
		set_post_thumbnail( $postId, $attachId );
	}

}