<?
require_once '../../Config/Setup.php';
require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/Request.php');
require_once('../../Editor/Classes/Response.php');
require_once('../../Editor/Classes/Utilities/DateUtils.php');
require_once('../../Editor/Classes/Model/Query.php');

$number = Request::getInt('number');
$year = DateUtils::getCurrentYear();

$usage = Query::after('waterusage')->withProperty('number',$number)->withProperty('year',2010)->first();

if ($usage==null) {
	Response::notFound('Nummeret kunne ikke findes');
	exit;
}
header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="css/receipt.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<title>Måleraflæsning : Hals Vandværk</title>
</head>

<body>
<div class="receipt">
	<h1>Kvittering for aflæsning af måler</h1>
	<table>
		<tbody>
			<tr><th>Målernummer:</th><td><?=$usage->getNumber()?></td></tr>
			<tr><th>År:</th><td><?=$usage->getYear()?></td></tr>
			<tr><th>Aflæsnings-dato:</th><td><?=DateUtils::formatDate($usage->getDate())?></td></tr>
			<tr><th>Værdi:</th><td><?=$usage->getValue()?></td></tr>
		</tbody>
		<tbody>
			<tr><th>Registreret:</th><td><?=DateUtils::formatLongDateTime($usage->getUpdated())?></td></tr>
		</tbody>
	</table>
</div>
</body>
</html>
