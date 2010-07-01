<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Functions.php';

$id=requestGetNumber('id',0);

$sql="select filename from image where object_id=".$id;
if ($row = Database::selectFirst($sql)) {
	redirect('../../../../images/'.$row['filename']);
}
else {
	redirect('../Graphics/ImageNotFound.gif');
}
?>