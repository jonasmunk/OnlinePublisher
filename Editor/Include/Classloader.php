<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once $basePath.'Editor/Info/Classes.php';
function __autoload($class_name) {
	global $basePath,$HUMANISE_EDITOR_CLASSES;
	
	if (is_array($HUMANISE_EDITOR_CLASSES) && is_array($HUMANISE_EDITOR_CLASSES['all'])) {
		if (array_key_exists($class_name,$HUMANISE_EDITOR_CLASSES['all'])) {
			require_once $basePath.'Editor/Classes/'.$HUMANISE_EDITOR_CLASSES['all'][$class_name];
			return;
		}
	}
	
	// Special Twig handling
	if (Strings::startsWith($class_name,'Twig_')) {
		$parts = explode('_',$class_name);
		array_shift($parts);
		$path = $basePath.'Editor/Libraries/twig/'.implode('/',$parts).'.php';
		if (file_exists($path)) {
	    	require_once $path;
			return;
		} else {
			Log::debug('Not found: '.$path.' ('.$class_name.')');
		}
	}
	
    // Fall back to scanning (dev mode)
    // TODO: more dynamic
	$folders = array('', 'Templates/', 'Services/', 'Utilities/', 'Objects/', 'Parts/', 'Core/', 'Model/', 'Network/', 'Interface/', 'Modules/News/', 'Modules/Images/', 'Modules/Links/', 'Modules/Graphs/', 'Modules/Review/', 'Modules/Statistics/', 'Modules/Water/', 'Formats/', 'Integration/', 'Modules/Inspection/'/*,'Tests/'*/);
	foreach ($folders as $folder) {
		$path = $basePath.'Editor/Classes/'.$folder.$class_name . '.php';
		if (file_exists($path)) {
	    	require_once $path;
			break;
		}
	}
}
?>