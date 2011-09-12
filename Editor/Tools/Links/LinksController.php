<?
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Utilities/ValidateUtils.php';

class LinksController {
	
	function buildSQL($query=array()) {
		$source = $query['source'];
		// Page to page
		$unions = array();
		if ($source=='page' || $source=='all') {
			$unions[] = "select 'Finished' as status,source_text as source_data,'page' as source_type,page.title as source_title,link.page_id as source_id,target_type,targetpage.title as target_value,target_id from link,page,page as targetpage where link.page_id=page.id and link.target_type='page' and link.target_id=targetpage.id";
			// Page to url + email
			$unions[] = "select 'Unknown' as status,source_text as source_data,'page' as source_type,page.title as source_title,link.page_id as source_id,target_type,target_value,target_id from link,page where link.page_id=page.id and (link.target_type='email' or link.target_type='url')";
			// Page to file
			$unions[] = "select 'Finished' as status,source_text as source_data,'page' as source_type,page.title as source_title,link.page_id as source_id,target_type,object.title as target_value,target_id from link,page,object where link.page_id=page.id and link.target_type='file' and link.target_id=object.id";
		}
		if ($source=='hierarchy' || $source=='all') {
			// Hierarchy to page+pageref
			$unions[] = "select 'Finished' as status,hierarchy_item.title as source_data,'hierarchy' as source_type,hierarchy.name as source_title,hierarchy_id as source_id,target_type,page.title as target_value,target_id from hierarchy_item,hierarchy,page where (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref') and page.id = hierarchy_item.target_id and hierarchy_item.hierarchy_id=hierarchy.id";
		}
		return implode($unions,' union ');
	}
	
	function analyzeLink(&$data) {
	    $out = array(
	        'sourceTitle'=>$data['source_title'],
	        'sourceIcon'=>'element/object',
	        'sourceLink'=>'#',
	        'sourceData'=>$data['source_data'],
	        'targetTitle'=>$data['target_value'],
	        'targetIcon'=>'element/object',
	        'targetLink'=>'#',
	        'status'=>$data['status'],
	        'message'=>''
	    );
		switch ($data['source_type']) {
		    case "page":
		        $out['sourceIcon'] = 'common/page';
		        $out['sourceLink'] = '#'.$data['source_id'];
		        break;
		    case "hierarchy":
		        $out['sourceIcon'] = 'common/hierarchy';
		        $out['sourceLink'] = '#'.$data['source_id'];
		        break;
		}
		switch ($data['target_type']) {
		    case "page":
		        $out['targetIcon'] = 'common/page';
		        $out['targetLink'] = '#'.$data['target_id'];
		        break;
		    case "pageref":
		        $out['targetIcon'] = 'common/pagereference';
		        $out['targetLink'] = '#'.$data['target_id'];
		        $out['targetTitle'] .= ' (reference)';
		        break;
		    case "file":
		        $out['targetIcon'] = 'file/generic';
		        $out['targetLink'] = '#'.$data['target_id'];
		        break;
		    case "email":
		        $out['targetIcon'] = 'common/email';
		        $out['targetLink'] = 'mailto:'.$data['target_value'];
		        if (ValidateUtils::validateEmail($data['target_value'])) {
		            $out['message'] = "Kontrollér manuelt";
		        } else {
		            $out['status'] = "Stopped";
		            $out['message'] = "Er ikke en valid e-postadresse";
		        }
		        break;
		    case "url":
		        $out['targetIcon'] = 'common/internet';
		        $out['targetLink'] = $data['target_value'];
		        if (ValidateUtils::validateUrl($data['target_value'])) {
		            $out['message'] = "Kontrollér manuelt";
		        } else {
		            $out['status'] = "Stopped";
		            $out['message'] = "Er ikke en valid internetadresse";
		        }
		        break;
		}
	    return $out;
	}
}

?>