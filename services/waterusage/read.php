<?
require_once '../../Config/Setup.php';
require_once('../../Editor/Classes/Request.php');
require_once('../../Editor/Classes/Database.php');

$number = Request::getString('number');

$year = date('Y');
$year = 2008;
$sql = "select DATE_FORMAT(date, '%d-%m-%Y') as `date`,`value` from waterusage where year=".Database::int($year)." and number = ".Database::text($number);
error_log($sql);
$row = Database::selectFirst($sql);
header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>'.
'<result>'.
'<value>'.$row['value'].'</value>'.
'<date>'.$row['date'].'</date>'.
'</result>';
?>