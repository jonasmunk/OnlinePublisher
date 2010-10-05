<?
/**
 * @package OnlinePublisher
 * @subpackage Services.Parts
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Parts/LegacyPartController.php';
require_once '../../Classes/Services/PartService.php';

$type = Request::getString('type');
$id = Request::getInt('id');

$controller = PartService::getController($type);

if ($controller && method_exists($controller,'getFromRequest')) {
	$part = $controller->getFromRequest($id);
	header("Content-Type: text/html; charset=UTF-8");
	echo $controller->render($part,new PartContext());
} else {
	$part = LegacyPartController::load($type,$id);
	header("Content-Type: text/html; charset=UTF-8");
	echo $part->preview();
}
?>