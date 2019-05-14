<?php


add_action('admin_menu', 'wpgpxmaps_admin_menu');

function wpgpxmaps_admin_menu() {	
	
	$allow_other_users_upload = wpgpxmaps_findValue($attr, 'allow_other_users_upload', 'wpgpxmaps_allow_users_upload', false);
	if(current_user_can('administrator') || (!current_user_can('administrator') && $allow_other_users_upload)){
		add_menu_page('WP GPX Maps', 'WP GPX Maps', 'read', 'WP-GPX-Maps', 'WP_GPX_Maps_html_page');
		add_options_page('WP GPX Maps', 'WP GPX Maps', 'read', 'WP-GPX-Maps', 'WP_GPX_Maps_html_page');
	}
}



function wpgpxmaps_ilc_admin_tabs( $current  ) {
	
	if(current_user_can('edit_users')){
		$tabs = array(
				'tracks' => __( 'Tracks', 'wp-gpx-maps' ),
				'settings' => __( 'Settings', 'wp-gpx-maps' ),
				'help' => __( 'Help', 'wp-gpx-maps' )
				);
	}
	else{
		$tabs = array(
				'tracks' => __( 'Tracks', 'wp-gpx-maps' ),
				'help' => __( 'Help', 'wp-gpx-maps' )
				);
	}



    echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ){

        $class = ( $tab == $current ) ? ' nav-tab-active' : '';

        echo "<a class='nav-tab$class' href='?page=WP-GPX-Maps&tab=$tab'>$name</a>";

    }

    echo '</h2>';

}


function getPathFilesContents($dir, &$results = array()){
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getPathFilesContents($path, $results);
        }
    }

    return $results;
}



function WP_GPX_Maps_html_page() {
	
	$realGpxPath = gpxFolderPath();

	$cacheGpxPath = gpxCacheFolderPath();

	$relativeGpxPath = relativeGpxFolderPath();

	$relativeGpxPath = str_replace("\\","/", $relativeGpxPath);

	$tab = $_GET['tab'];


	if ($tab == '')

		$tab = 'tracks';

?>

	<div id="icon-themes" class="icon32"><br></div>

		<h2><?php _e( 'Settings', 'wp-gpx-maps' ); ?></h2>

<?php

	if(file_exists($realGpxPath) && is_dir($realGpxPath))

	{

		//dir exsist!

	}

	else

	{

		if (!@mkdir($realGpxPath,0755,true)) {
			echo '<div class=" notice notice-error"><p>';
			_e( 'Can not create the', 'wp-gpx-maps' );
			echo ' ' . '<strong>' . $realGpxPath . '</strong>' . ' ';
			_e( 'folder. Please create it and make it writable! If not, you will must update the file manually!', 'wp-gpx-maps' );
			echo '</p></div>';
		}

	}

	if(file_exists($cacheGpxPath) && is_dir($cacheGpxPath))

	{

		//dir exsist!

	}

	else

	{

		if (!@mkdir($cacheGpxPath,0755,true)) {
			echo '<div class=" notice notice-error"><p>';
			_e( 'Can not create the', 'wp-gpx-maps' );
			echo ' ' . '<strong>' . $cacheGpxPath . '</strong>' . ' ';
			_e( 'folder. Please create it and make it writable! If not, you will must update the file manually!', 'wp-gpx-maps' );
			echo '</p></div>';
		}

	}



	wpgpxmaps_ilc_admin_tabs($tab);

	if ($tab == "tracks")

	{

		include 'wp-gpx-maps_admin_tracks.php';

	}

	else if ($tab == "settings")

	{

		include 'wp-gpx-maps_admin_settings.php';

	}

	else if ($tab == "help")

	{

		include 'wp-gpx-maps_help.php';

    }

}

?>
