<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../../../Classes/Person.php';
require_once '../Functions.php';

header('content-type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';

$id = requestGetNumber("id");
if ($id>0) {
	$person = Person::load($id);

	$data = $person->getCurrentXml();
	echo $data;
} else {
	echo '<root/>';
}
?>