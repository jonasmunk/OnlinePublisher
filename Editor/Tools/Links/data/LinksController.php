<?
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Utilities/ValidateUtils.php';

class LinksController {
	
	function buildSQL($query=array()) {
		$source = $query['source'];
		$target = $query['target'];
		$unions = array();
		if ($source=='page' || $source=='all') {
			// Page to page
			if ($target=='page' || $target=='all') {
				$unions[] = "select 'Finished' as status,source_text as source_data,'page' as source_type,page.title as source_title,
link.page_id as source_id,target_type,targetpage.title as target_value,targetpage.id as target_id from link left join page as targetpage on link.target_id=targetpage.id,page
 where link.page_id=page.id and link.target_type='page'";
			}

			// Page to url
			if ($target=='url' || $target=='all') {
				$unions[] = "select 'Unknown' as status,source_text as source_data,'page' as source_type,page.title as source_title,".
					"link.page_id as source_id,target_type,target_value,target_value as target_id from link,page where link.page_id=page.id".
					" and link.target_type='url'";
			}

			// Page to email
			if ($target=='email' || $target=='all') {
				$unions[] = "select 'Unknown' as status,source_text as source_data,'page' as source_type,page.title as source_title,".
					"link.page_id as source_id,target_type,target_value,target_value as target_id from link,page where link.page_id=page.id".
					" and link.target_type='email'";
			}

			// Page to file
			if ($target=='file' || $target=='all') {
				$unions[] = "select 'Finished' as status,source_text as source_data,'page' as source_type,page.title as source_title,".
					"link.page_id as source_id,target_type,object.title as target_value,target_id from link,page,object".
					" where link.page_id=page.id and link.target_type='file' and link.target_id=object.id";
			}
		}
		if ($source=='hierarchy' || $source=='all') {
			// Hierarchy to page+pageref
			if ($target=='page' || $target=='all') {
				$unions[] = "select 'Finished' as status,hierarchy_item.title as source_data,'hierarchy' as source_type,hierarchy.name as source_title,
hierarchy_id as source_id,target_type,page.title as target_value,page.id as target_id from hierarchy_item left join page on page.id=hierarchy_item.target_id,hierarchy
 where (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')
 and hierarchy_item.hierarchy_id=hierarchy.id";
			}
			if ($target=='url' || $target=='all') {
				$unions[] = "select 'Finished' as status,hierarchy_item.title as source_data,'hierarchy' as source_type,hierarchy.name as source_title,".
					"hierarchy_id as source_id,target_type,hierarchy_item.target_value,target_id from hierarchy_item,hierarchy".
					" where (hierarchy_item.target_type='url')".
					" and hierarchy_item.hierarchy_id=hierarchy.id";
			}
			if ($target=='email' && false) {
				$unions[] = "select 
'Unknown' as status,
'' as source_data,
hierarchy_item.target_type as source_type,
page.title as source_title,
hierarchy_item.target_id as source_id,

child.target_type as target_type,
target_page.title as target_value ,
child.target_id as target_id

from hierarchy_item 

join `hierarchy_item` as child on `hierarchy_item`.id=child.parent

left join page as target_page on `child`.target_id=target_page.id

left join page on `hierarchy_item`.target_id=page.id

union
select 
'Unknown' as status,
'' as source_data,
'hierarchy' as source_type,
hierarchy.name as source_title, 
hierarchy.id as source_id,
hierarchy_item.target_type as target_type, 
page.title as target_value,
hierarchy_item.target_id as target_id
from hierarchy 

left join hierarchy_item on `hierarchy_item`.`hierarchy_id`=hierarchy.id and hierarchy_item.parent=0 left join page on `hierarchy_item`.target_id=page.id";
			}
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
				if (!$data['target_id']) {
					$out['message'] = 'Siden findes ikke';
		        	$out['targetIcon'] = 'common/warning';
					$out['targetTitle'] = 'Siden findes ikke';
				} else {
		        	$out['targetIcon'] = 'common/page';
		        	$out['targetLink'] = '#'.$data['target_id'];
				}
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
		        if (ValidateUtils::validateHref($data['target_value'])) {
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