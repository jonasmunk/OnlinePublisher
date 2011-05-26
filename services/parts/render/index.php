<?
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once '../../../Editor/Classes/Request.php';
require_once '../../../Editor/Classes/Services/PartService.php';

$type = Request::getString('type');
$id = Request::getInt('id');

if ($controller = PartService::getController($type)) {
	if ($part = PartService::load($type,$id)) {
		header("Content-Type: text/html; charset=UTF-8");
		$context = new PartContext();
		$context->setSynchronize(Request::getBoolean('synchronize'));
		echo $controller->render($part,$context);
	}
}
?>