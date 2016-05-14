<?php
/**
 * @package OnlinePublisher
 * @subpackage Config
 */
/**
 * Create a copy of this file named Setup.php and change the variables below...
 */
$CONFIG = array(
	'database' => array(
		'host' => 'localhost',
		'user' => 'root',
		'password' => 'secret',
		'database' => 'onlinepubisher'
	),
	
	'baseUrl' => '/~username/path/to/site/', // The root url of your site, it must end with a slash (/)
	
	'super' => array( // A super user hat can be used to perform admin tasks like creating users and updating the database scheme
		'user' => 'peter',
		'password' => '$u9er$ecr3t'
	),
	'debug' => false
);

/*
Add this to enable multiple sites
$dev_file = dirname(__file__) . '/dev.json';
if (file_exists($dev_file)) {
  $server = @$_SERVER['SERVER_NAME'];
  $dev = json_decode(file_get_contents($dev_file));
  $sites = [];
  foreach ($dev->clients as $key => $info) {
    if ($info->local->domain == $server) {
      $CONFIG['baseUrl'] = '/';
      $CONFIG['dataDir'] = $info->local->data . '/';
      $CONFIG['database']['database'] = $info->local->database;
    }
  }
}*/
?>