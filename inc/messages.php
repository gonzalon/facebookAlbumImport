<?php
class Messages{

	private $errorMessages;
	private $successMessages;
	
	public function __construct(){
		$this->errorMessages = array();
		$this->successMessages = array();
	}
	
	public function addErrorMessage($message){
		if($message != ""){
			$this->errorMessages[] = $message;
		}
	}
	
	public function addSuccessMessage($message){
		if($message != ""){
			$this->successMessages[] = $message;
		}
	}
	
	public function printMessages(){
		if(sizeof($this->errorMessages) > 0){
			echo '<div id="message" class="error">';
			foreach($this->errorMessages as $message){
				echo '<p><strong>'.$message.'</strong></p>';
			}
			echo '</div>';
		}
		if(sizeof($this->successMessages) > 0){
			echo '<div id="message" class="updated">';
			foreach($this->successMessages as $message){
				echo '<p><strong>'.$message.'</strong></p>';
			}
			echo '</div>';
		}
	}
}