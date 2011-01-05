<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/Product.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class ProductlistingController extends LegacyTemplateController {
    
    function ProductlistingController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into productlisting (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from productlisting where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from productlisting_productgroup where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$data='<productlisting xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/productlisting/1.0/">';

		$sql="select * from productlisting where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data.=
		'<title>'.StringUtils::escapeXML($row['title']).'</title>'.
		'<text>'.StringUtils::escapeXMLBreak($row['text'],'<break/>').'</text>'.
		'<!--dynamic-->'.
		'</productlisting>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }
    
	function dynamic(&$state) {
		$makeOffer = Request::getInt('makeOffer');
		if ($makeOffer>0) {
			$product = Product::load($makeOffer);
			if ($product->isAllowOffer()) {
				$name = Request::getString('productlistingName');
				$email = Request::getString('productlistingEmail');
				$offer = Request::getString('productlistingOffer');
				$error = Request::getString('productlistingError');
				$success = Request::getBoolean('productlistingSuccess');
				$xml = '<make-offer>';
				$xml .= '<value key="name" value="'.StringUtils::escapeXML($name).'"/>';
				$xml .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
				$xml .= '<value key="offer" value="'.StringUtils::escapeXML($offer).'"/>';
				if ($error!='') {
					$xml.='<error key="'.StringUtils::escapeXML($error).'"/>';
				} else if ($success) {
					$xml.='<success/>';
				}
				$xml.= Object::getObjectData($makeOffer);
				$xml.= '</make-offer>';
			} else {
				$xml='<error/>';
			}
		} else {
			$sql="select object.data from productgroup_product,object,productlisting_productgroup where object.id=productgroup_product.product_id and productlisting_productgroup.productgroup_id=productgroup_product.productgroup_id and productlisting_productgroup.page_id=".$this->id." order by object.title";
			$result = Database::select($sql);
			$xml='<list>';
			while ($row = Database::next($result)) {
				$xml.=$row['data'];
			}
			$xml.='</list>';
			Database::free($result);
		}
		$state['data']=str_replace('<!--dynamic-->', $xml, $state['data']);
	}
}
?>