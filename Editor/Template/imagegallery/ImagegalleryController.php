<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ImagegalleryController extends LegacyTemplateController {
    
    function ImagegalleryController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into imagegallery (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from imagegallery where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from imagegallery_object where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from imagegallery_custom_info where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
        $sql="select * from imagegallery where page_id=".$this->id;
        if ($row = Database::selectFirst($sql)) {
            $data=
            '<imagegallery xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/imagegallery/1.0/">'.
            '<title>'.StringUtils::escapeXML($row['title']).'</title>'.
            '<text>'.StringUtils::escapeXMLBreak($row['text'],'<break/>').'</text>'.
            '<display'.
            ' size="'.$row['imagesize'].'"'.
            ' rotate="'.$row['rotate'].'"'.
            ' show-title="'.($row['showtitle'] ? 'true' : 'false').'"'.
            ' show-note="'.($row['shownote'] ? 'true' : 'false').'"'.
            '/>'.
            '<custom>';
            $sql="select * from imagegallery_custom_info where page_id=".$this->id;
            $result = Database::select($sql);
            while ($row = Database::next($result)) {
                $data.=
                '<image id="'.$row['image_id'].'">'.
                '<title>'.StringUtils::escapeXML($row['title']).'</title>'.
                '<note>'.StringUtils::escapeXMLBreak($row['note'],'<break/>').'</note>'.
                '</image>';
            }
            Database::free($result);
            $data.=
            '</custom>'.
            '<images>'.
            '<!--dynamic-->'.
            '</images>'.
            '</imagegallery>';
            $index = '';
        } else {
            $data = '';
            $index = '';
        }
        return array('data' => $data, 'dynamic' => true, 'index' => $index);
    }

	function dynamic(&$state) {
		global $basePath,$baseUrl;
		$xml='';
		$sql="select object.type,object.id,object.data".
		" from object,imagegallery_object".
		" where (object.type='imagegroup' or object.type='image')".
		" and object.id=imagegallery_object.object_id".
		" and imagegallery_object.page_id=".$this->id.
		" order by imagegallery_object.position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			if ($row['type']=='imagegroup') {
		    	$sql="select object.data from object,imagegroup_image".
		    	" where object.id=imagegroup_image.image_id".
		    	" and imagegroup_image.imagegroup_id=".$row['id']." order by title";
		        $result2 = Database::select($sql);
		        while ($row2 = Database::next($result2)) {
		    	    $xml.=$row2['data'];
		        }
		        Database::free($result2);
			} else {
		    	$xml.=$row['data'];
			}
		}
		Database::free($result);

		$state['data']=str_replace("<!--dynamic-->", $xml, $state['data']);
	}
    
}
?>