<?php
if(isset($_POST) && !empty($_POST)){
	$albumId = $_POST['albumId'];
	$message = new Messages();
	if(isset($albumId) && !empty($albumId)){
		$name = $_POST['name'];
		$category = $_POST['cat'];
		$user = $_POST['user'];
		$postFormat = $_POST['postFormat'];
		$download = $_POST['download'];
		$status = $_POST['status'];
		
		$fai = new FacebookAlbumImport($albumId, $name, $category, $postFormat, $download, $user, $status);
		if($fai->process() == 0){
			$message->addSuccessMessage("Awesome! The photo album was imported successfully.");
		}
	}else{
		$message->addErrorMessage("You must provide the id of the album.");
	}
	$message->printMessages();
}
?>
<div class="wrap">
<h2><?php _e( "Facebook Album Import", 'faibase' ); ?></h2>
	<form class="form-horizontal" id="dx-plugin-base-form" action="admin.php?page=fai-plugin-import" method="POST">
		<h3><?php _e( "Save the data of your Facebook application.", 'faibase' ); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="albumId" class="col-lg-2 control-label">Album Id</label></th>
				<td><input type="text" name="albumId" value="" /></td>
			</tr><tr>
				<th><label for="name" class="col-lg-2 control-label">Name</label></th>
				<td><input type="text" name="name" value="" /></td>
			</tr><tr>
				<th><label for="cat" class="col-lg-2 control-label">Category</label></th>
				<th><?php wp_dropdown_categories('hide_empty=0'); ?></th>
			</tr><tr>
				<th><label for="user" class="col-lg-2 control-label">Author</label></th>
				<th><?php wp_dropdown_users(); ?></th>
			</tr><tr>
				<th><label for="status" class="col-lg-2 control-label">Status</label></th>
				<th>
					<select name="status">
						<option>publish</option>
						<option>draft</option>
					</select>
				</th>
			</tr><tr>
				<th><label for="postFormat" class="col-lg-2 control-label">Post Type</label></th>
				<th>
					<select name="postFormat">
						<option>Image</option>
						<option>Gallery</option>
					</select>
				</th>
			</tr><tr>
				<th><label for="download" class="col-lg-2 control-label">Download Photos</label></th>
				<th>
					<select name="download">
						<option>Yes</option>
						<option>No</option>
					</select>
				</th>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Import photos"  /></p>
	</form>
</div>