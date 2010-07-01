<?
/**
 * @package OnlinePublisher
 * @subpackage Services.Parts
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Part.php';

$part = Part::load(Request::getString('type'),Request::getInt('id'));
header("Content-Type: text/html; charset=UTF-8");
echo $part->preview();
?>