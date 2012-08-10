<?php
require_once('../../Editor/Include/Public.php');

$id = Request::getInt('id');
$year = DateUtils::getCurrentYear();

//$usage = Query::after('waterusage')->withProperty('number',$number)->first();

$usage = Waterusage::load($id);

if ($usage==null) {
	Response::notFound('Nummeret kunne ikke findes');
	exit;
}

$meter = Watermeter::load($usage->getWatermeterId());

if ($meter==null) {
	Response::notFound('Måleren kunne ikke findes');
	exit;
}


$address = Query::after('address')->withRelationFrom($meter)->first();

header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="css/receipt.css" type="text/css" charset="utf-8">
	<title>Måleraflæsning : Hals Vandværk</title>
</head>

<body>
<div class="receipt">
	<h1>Kvittering for aflæsning af måler</h1>
	<p>Aflæsningen er nu registreret med følgende oplysninger...</p>
	<table>
		<tbody>
			<tr><th>Målernummer:</th><td><?=number_format ( $meter->getNumber() , 0 , '' , ' ' )?></td></tr>
			<?php if ($address) {?>
			<tr><th>Adresse:</th><td><?=StringUtils::toUnicode($address->toString())?></td></tr>
			<?php
				}
			?>
			<tr><th>Værdi:</th><td><?=number_format ( $usage->getValue() , 0 , '' , '.' )?></td></tr>
			<tr><th>Aflæsningsdato:</th><td><?=DateUtils::formatDate($usage->getDate())?></td></tr>
		</tbody>
		<tbody>
			<tr><th>Registreret:</th><td><?=DateUtils::formatLongDateTime($usage->getUpdated())?></td></tr>
		</tbody>
	</table>
	<p class="footer">Hals Vandværk <?=$year?>, <a href="mailto:halsvand@halsvand.dk">halsvand@halsvand.dk</a></p>
	<p class="actions">
		<button onclick="history.back()">Tilbage</button>
		<button onclick="window.print()">Udskriv</button>		
	</p>
</div>
</body>
</html>
