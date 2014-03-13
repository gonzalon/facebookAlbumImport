<?php
/**
 * Plugin Name: Facebook Album Import
 * Plugin URI: http://gonzalonaveira.com/FacebookAlbumImport/
 * Description: Import a whole album of Facebook to Wordpress. Every single photo become a post.
 * Version: 0.1
 * Author: Gonzalo Naveira
 * Author http://gonzalonaveira.com
 * License: GPL2
 
Copyright 2014  Gonzalo Naveira  (email : gonzalo naveira DOT plugin AT gmail DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define( 'GN_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );

require_once( GN_PATH_INCLUDES. '/constants.php' );
require_once( GN_PATH_INCLUDES. '/exceptions.php' );
require_once( GN_PATH_INCLUDES. '/pluginBase.php' );
require_once( GN_PATH_INCLUDES. '/configuration.php' );
require_once( GN_PATH_INCLUDES. '/messages.php' );
require_once( GN_PATH_INCLUDES. '/import.php' );


$FacebookImportBase = new FacebookImportBase();