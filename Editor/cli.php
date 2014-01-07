<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once 'Include/Public.php';

Console::exitIfNotConsole();

$args = Console::getArguments();

if ($args[1]=='test') {
  if (!Database::testConnection()) {
    echo "No database - no testing!\n";
    exit;
  }
  if (ini_get('display_errors')) {
      ini_set('display_errors', 0);
  }
  error_reporting(E_ERROR | E_PARSE);
  
  if ($args[2]) {
    TestService::runTest($args[2],new ConsoleReporter());
  } else {
    TestService::runAllTests(new ConsoleReporter());
  }
  
}
if ($args[1]=='rebuild') {
  DesignService::rebuild();
}


exit;

echo "Are you sure you want to do this?  Type 'yes' to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim($line) != 'yes'){
    echo "ABORTING!\n";
    exit;
}
echo "\n";
echo "Thank you, continuing...\n";
?>