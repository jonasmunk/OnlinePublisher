<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
//$id,$template,$data
$sql = "select * from document_person, person, object where document_person.person_id = person.object_id and person.object_id = object.id and document_person.page_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$xml = $row['data'];
	$data=str_replace('<!--PERSON#'.$row['person_id'].'-->', $xml, $data);
}
Database::free($result);
?>