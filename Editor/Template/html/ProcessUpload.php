<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/HtmlDocument.php';

$fileType=$_FILES["file"]["type"];
$tempFile=$_FILES['file']['tmp_name'];
$id = getPageId();

if ($fileType=='text/html') {
	$html = file_get_contents($tempFile);
	$doc = new HtmlDocument($html);
	$body = $doc->getBodyContents();
	

	$sql="update html set".
	" html=".Database::text($body).
	",valid=".Database::boolean(false).
	" where page_id=".$id;
	Database::update($sql);

	$sql="update page set".
	" changed=now()".
	" where id=".$id;
	Database::update($sql);

	redirect('Editor.php');

} else {
	redirect('Upload.php?error=invalid');
}
?>