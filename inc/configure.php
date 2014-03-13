<?php
$configuration = new Configuration();
if(isset($_POST) && !empty($_POST)){
	$configuration->saveConfiguration($_POST);
}
?>
<div class="wrap">
	<h2><?php _e( "Facebook Album Import - Configure", 'faibase' ); ?></h2>
	<p>For you to import the album's photos you must have an application id and the application secret key.</p>
	
	<form class="form-horizontal" id="dx-plugin-base-form" action="admin.php?page=fai-base-configure" method="POST">
		<h3><?php _e( "Save the data of your Facebook application.", 'faibase' ); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="appid" class="col-lg-2 control-label">App Id</label></th>
				<td><input type="text" name="fai_appid" value="<?php echo $configuration->appid; ?>" /></td>
			</tr><tr>
				<th><label for="appsecret" class="col-lg-2 control-label">App Secret</label></th>
				<td><input type="text" name="fai_appsecret" value="<?php echo $configuration->appsecret; ?>" /></td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save configuration"  /></p>  
	</form>
</div>