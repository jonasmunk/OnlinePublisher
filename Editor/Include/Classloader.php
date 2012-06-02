<?php
function __autoload($class_name) {
	global $basePath;
	if (class_exists($class_name)) {
		return;
	}
	$folders = array('','Templates/','Services/','Utilities/','Objects/','Parts/','Core/','Model/','Network/','Interface/','Modules/News/','Modules/Images/','Modules/Links/','Modules/Graphs/','Modules/Review/','Formats/');
	foreach ($folders as $folder) {
		$path = $basePath.'Editor/Classes/'.$folder.$class_name . '.php';
		if (file_exists($path)) {
	    	require_once $path;
			break;
		}
	}
}
?>