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
	
	'baseUrl' => '/~username/path/to/site/', // The root url of your site, it must and with a slash (/)
	
	'super' => array( // A super user hat can be used to perform admin tasks like creating users and updating the database scheme
		'user' => 'peter',
		'password' => '$u9er$ecr3t'
	)
);
?>