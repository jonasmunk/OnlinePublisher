<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$databaseTables = array(
	
	'address' => array(
			array("object_id","int(11)","YES","","",""),
			array("street","varchar(255)","YES","","",""),
			array("zipcode","varchar(255)","YES","","",""),
			array("city","varchar(255)","YES","","",""),
			array("country","varchar(255)","YES","","","")
	),
	'authentication' => array(
			array("page_id","int(11)","","","0",""),
			array("title","varchar(255)","YES","","","")
	),
	'cachedurl' => array(
			array("object_id","int(11)","","","0",""),
			array("url","varchar(255)","YES","","",""),
			array("synchronized","datetime","YES","","",""),
			array("mimeType","varchar(50)","YES","","","")
	),
	'calendar' => array(
			array("object_id","int(11)","","","0","")
	),
	'calendar_event' => array(
			array("calendar_id","int(11)","","","0",""),
			array("event_id","int(11)","","","0","")
	),
	'calendarsource' => array(
			array("object_id","int(11)","","","0",""),
			array("url","varchar(255)","YES","","",""),
			array("synchronized","datetime","YES","","",""),
			array("sync_interval","int(11)","NO","","3600",""),
			array("display_title","varchar(255)","YES","","",""),
			array("filter","varchar(255)","YES","","","")
	),
	'calendarsource_event' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("calendarsource_id","int(11)","YES","","",""),
			array("summary","text","YES","","",""),
			array("description","text","YES","","",""),
			array("startdate","datetime","YES","","",""),
			array("enddate","datetime","YES","","",""),
			array("timestamp","datetime","YES","","",""),
			array("uniqueid","varchar(255)","YES","","",""),
			array("location","text","YES","","",""),
			array("recurring","tinyint(4)","","","0",""),
			array("frequency","varchar(20)","YES","","",""),
			array("until","datetime","YES","","",""),
			array("count","int(11)","YES","","",""),
			array("interval","int(11)","YES","","",""),
			array("bymonth","varchar(255)","YES","","",""),
			array("bymonthday","varchar(255)","YES","","",""),
			array("byday","varchar(255)","YES","","",""),
			array("byyearday","varchar(255)","YES","","",""),
			array("byweeknumber","varchar(255)","YES","","",""),
			array("weekstart","char(2)","YES","","",""),
			array("duration","int(11)","YES","","",""),
			array("url","varchar(1024)","YES","","","")
	),
	'calendarviewer' => array(
			array("page_id","int(11)","","","0",""),
			array("title","varchar(255)","YES","","",""),
			array("weekview_starthour","int(11)","","","0",""),
			array("standard_view","varchar(128)","NO","","week","")
	),
	'calendarviewer_object' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("object_id","int(11)","","","0","")
	),
	'design' => array(
			array("object_id","int(11)","YES","","",""),
			array("id","int(11)","","PRI","","auto_increment"),
			array("unique","varchar(255)","","","",""),
			array("name","varchar(255)","YES","","",""),
			array("parameters","text","YES","","","")
		),
	'design_parameter' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("design_id","int(11)","","","0",""),
			array("key","varchar(255)","YES","","",""),
			array("type","varchar(255)","YES","","",""),
			array("value","varchar(255)","YES","","","")
		),
	'document' => array(
			array("page_id","int(11)","","","0","")
		),
	'document_column' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("row_id","int(11)","","","0",""),
			array("index","int(11)","","","0",""),
			array("page_id","int(11)","","","0",""),
			array("width","varchar(50)","","","",""),
			array("top","varchar(10)","YES","","",""),
			array("bottom","varchar(10)","YES","","",""),
			array("left","varchar(10)","YES","","",""),
			array("right","varchar(10)","YES","","","")
		),
	'document_row' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("index","int(11)","","","0",""),
			array("top","varchar(10)","YES","","",""),
			array("bottom","varchar(10)","YES","","","")
		),
	'document_section' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("column_id","int(11)","","","0",""),
			array("index","int(11)","","","0",""),
			array("type","varchar(20)","","","",""),
			array("top","varchar(10)","YES","","",""),
			array("bottom","varchar(10)","YES","","",""),
			array("left","varchar(10)","YES","","",""),
			array("right","varchar(10)","YES","","",""),
			array("part_id","int(11)","YES","","0",""),
			array("float","varchar(10)","YES","","",""),
			array("width","varchar(10)","YES","","","")
		),
	'email_validation_session' => array(
            array("id","int(11)","","PRI","","auto_increment"),
            array("unique","varchar(255)","","","",""),
            array("user_id","int(11)","","","0",""),
            array("email","varchar(255)","","","",""),
            array("timelimit","datetime","","","0000-00-00 00:00:00","")
	    ),
	'emailaddress' => array(
			array("object_id","int(11)","","","0",""),
            array("address","varchar(255)","YES","","",""),
            array("containing_object_id","int(11)","","","0","")
		),
	'event' => array(
			array("object_id","int(11)","YES","","",""),
			array("startdate","datetime","","","0000-00-00 00:00:00",""),
			array("enddate","datetime","","","0000-00-00 00:00:00",""),
			array("location","varchar(255)","YES","","","")
		),
	'feedback' => array(
			array("object_id","int(11)","YES","","",""),
			array("name","varchar(255)","","","",""),
			array("email","varchar(255)","","","",""),
			array("message","mediumtext","","","","")
		),
	'file' => array(
			array("object_id","int(11)","YES","","",""),
			array("filename","varchar(255)","","","",""),
			array("size","int(11)","","","0",""),
			array("type","varchar(255)","","","","")
		),
	'filegroup' => array(
			array("object_id","int(11)","YES","","","")
		),
	'filegroup_file' => array(
			array("file_id","int(11)","","","0",""),
			array("filegroup_id","int(11)","","","0","")
		),
	'frame' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("title","varchar(255)","YES","","",""),
			array("name","varchar(255)","YES","","",""),
			array("hierarchy_id","int(11)","","","0",""),
			array("searchbuttontitle","varchar(255)","YES","","",""),
			array("searchenabled","tinyint(4)","","","0",""),
			array("searchpage_id","int(11)","YES","","0",""),
			array("searchpages","tinyint(4)","","","0",""),
			array("searchimages","tinyint(4)","","","0",""),
			array("searchfiles","tinyint(4)","","","0",""),
			array("searchnews","tinyint(4)","","","0",""),
			array("searchpersons","tinyint(4)","","","0",""),
			array("searchproducts","tinyint(4)","","","0",""),
			array("data","text","YES","","",""),
			array("changed","datetime","YES","","",""),
			array("published","datetime","YES","","",""),
			array("bottomtext","text","YES","","",""),
			array("dynamic","tinyint(4)","","","0",""),
			array("userstatusenabled","tinyint(4)","","","0",""),
			array("userstatuspage_id","int(11)","YES","","0","")
		),
	'frame_link' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("frame_id","int(11)","","","0",""),
			array("position","varchar(10)","","","",""),
			array("index","int(11)","","","0",""),
			array("target","varchar(10)","YES","","",""),
			array("target_type","varchar(10)","YES","","",""),
			array("target_value","text","YES","","",""),
			array("target_id","int(11)","YES","","",""),
			array("alternative","varchar(255)","YES","","",""),
			array("title","varchar(255)","YES","","","")
		),
	'frame_newsblock' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("frame_id","int(11)","","","0",""),
			array("index","int(11)","","","0",""),
			array("title","varchar(255)","YES","","",""),
			array("sortby","varchar(20)","YES","","",""),
			array("sortdir","varchar(20)","YES","","",""),
			array("maxitems","int(11)","YES","","",""),
			array("timetype","varchar(20)","YES","","",""),
			array("timecount","int(11)","YES","","",""),
			array("startdate","datetime","YES","","",""),
			array("enddate","datetime","YES","","","")
		),
	'frame_newsblock_newsgroup' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("frame_newsblock_id","int(11)","","","0",""),
			array("newsgroup_id","int(11)","","","0","")
		),
	'guestbook' => array(
			array("page_id","int(11)","","","0",""),
			array("title","varchar(255)","","","",""),
			array("text","mediumtext","","","","")
		),
	'guestbook_item' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("time","datetime","YES","","",""),
			array("text","text","YES","","",""),
			array("name","varchar(255)","YES","","","")
		),
	'hierarchy' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("name","varchar(255)","YES","","",""),
			array("data","text","YES","","",""),
			array("changed","datetime","YES","","",""),
			array("published","datetime","YES","","",""),
			array("language","varchar(5)","YES","","","")
		),
	'hierarchy_item' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("hierarchy_id","int(11)","YES","","",""),
			array("parent","int(11)","","","0",""),
			array("index","int(11)","","","0",""),
			array("type","varchar(255)","YES","","",""),
			array("title","varchar(255)","YES","","",""),
			array("alternative","varchar(255)","YES","","",""),
			array("target","varchar(50)","YES","","",""),
			array("target_type","varchar(255)","YES","","",""),
			array("target_id","int(11)","YES","","",""),
			array("target_value","text","YES","","",""),
			array("hidden","tinyint(4)","","","0","")
		),
	'html' => array(
			array("page_id","int(11)","","","0",""),
			array("html","text","YES","","",""),
			array("valid","tinyint(4)","","","1",""),
			array("title","varchar(255)","YES","","","")
		),
	'image' => array(
			array("object_id","int(11)","YES","","",""),
			array("filename","varchar(255)","","","",""),
			array("size","int(11)","","","0",""),
			array("width","int(11)","","","0",""),
			array("height","int(11)","","","0",""),
			array("type","varchar(10)","","","","")
		),
	'imagegallery' => array(
			array("page_id","int(11)","","PRI","0",""),
			array("title","varchar(255)","","","",""),
			array("text","text","YES","","",""),
            array("imagesize","int(11)","","","48",""),
            array("showtitle","tinyint(1)","","","1",""),
            array("shownote","tinyint(1)","","","1",""),
            array("rotate","int(11)","","","0","")
		),
	'imagegallery_custom_info' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("image_id","int(11)","","","0",""),
			array("title","varchar(255)","YES","","",""),
			array("note","text","YES","","","")
		),
	'imagegallery_object' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("object_id","int(11)","","","0",""),
			array("position","int(11)","","","0","")
		),
	'imagegroup' => array(
			array("object_id","int(11)","YES","","","")
		),
	'imagegroup_image' => array(
			array("image_id","int(11)","","","0",""),
			array("imagegroup_id","int(11)","","","0","")
		),
	'issue' => array(
			array("object_id","int(11)","","","0",""),
			array("kind","varchar(255)","YES","","",""),
			array("issuestatus_id","int(11)","","","0","")
		),
	'issuestatus' => array(
			array("object_id","int(11)","","","0","")
		),
	'link' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("part_id","int(11)","","","0",""),
			array("source_type","varchar(10)","YES","","",""),
			array("source_text","text","YES","","",""),
			array("target","varchar(10)","YES","","",""),
			array("target_type","varchar(10)","YES","","",""),
			array("target_value","text","YES","","",""),
			array("target_id","int(11)","YES","","",""),
			array("alternative","varchar(255)","YES","","","")
		),
	'log' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("time","datetime","YES","","",""),
			array("category","varchar(50)","YES","","",""),
			array("event","varchar(50)","YES","","",""),
			array("entity","int(11)","YES","","",""),
			array("message","varchar(255)","YES","","",""),
			array("user_id","int(11)","YES","","",""),
			array("ip","varchar(255)","YES","","",""),
			array("session","varchar(255)","YES","","","")
		),
	'mailinglist' => array(
			array("object_id","int(11)","YES","","","")
	    ),
	'milestone' => array(
			array("object_id","int(11)","","","0",""),
			array("deadline","datetime","YES","","",""),
			array("containing_object_id","int(11)","","","0",""),
			array("completed","tinyint(1)","YES","","0","")
	    ),
	'news' => array(
			array("object_id","int(11)","","","0",""),
			array("startdate","datetime","YES","","0000-00-00 00:00:00",""),
			array("enddate","datetime","YES","","0000-00-00 00:00:00",""),
			array("image_id","int(11)","YES","","","")
		),
	'newsgroup' => array(
			array("object_id","int(11)","","","0","")
		),
	'newsgroup_news' => array(
			array("news_id","int(11)","","","0",""),
			array("newsgroup_id","int(11)","","","0","")
		),
	'newssource' => array(
			array("object_id","int(11)","","","0",""),
			array("url","varchar(255)","YES","","",""),
			array("synchronized","datetime","YES","","",""),
			array("sync_interval","int(11)","NO","","3600","")
		),
	'newssourceitem' => array(
			array("object_id","int(11)","","","0",""),
			array("newssource_id","int(11)","","","0",""),
			array("text","text","YES","","",""),
			array("date","datetime","YES","","0000-00-00 00:00:00",""),
			array("url","varchar(255)","YES","","",""),
			array("guid","varchar(255)","YES","","","")
		),
	'object' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("title","varchar(255)","YES","","",""),
			array("type","varchar(50)","","","",""),
			array("note","text","YES","","",""),
			array("data","text","YES","","",""),
			array("created","datetime","","","0000-00-00 00:00:00",""),
			array("updated","datetime","","","0000-00-00 00:00:00",""),
			array("published","datetime","","","0000-00-00 00:00:00",""),
			array("searchable","tinyint(4)","","","1",""),
			array("index","text","YES","","",""),
			array("owner_id","int(11)","","","0","")
		),
	'object_link' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("object_id","int(11)","","","0",""),
			array("target","varchar(10)","YES","","",""),
			array("target_type","varchar(10)","YES","","",""),
			array("target_value","text","YES","","",""),
			array("alternative","varchar(255)","YES","","",""),
			array("title","varchar(255)","YES","","",""),
			array("position","int(11)","","","0","")
		),
	
	'page' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("title","varchar(100)","","","",""),
			array("description","text","YES","","",""),
			array("keywords","varchar(255)","","","",""),
			array("template_id","int(11)","","","0",""),
			array("data","longtext","YES","","",""),
			array("created","datetime","","","0000-00-00 00:00:00",""),
			array("changed","datetime","","","0000-00-00 00:00:00",""),
			array("published","datetime","","","0000-00-00 00:00:00",""),
			array("design_id","int(11)","","","0",""),
			array("frame_id","int(11)","","","0",""),
			array("index","text","YES","","",""),
			array("dynamic","tinyint(4)","","","0",""),
			array("language","varchar(5)","YES","","",""),
			array("searchable","tinyint(4)","","","1",""),
			array("secure","tinyint(4)","","","0",""),
			array("disabled","tinyint(4)","","","0",""),
			array("name","varchar(255)","YES","","",""),
			array("path","varchar(255)","YES","","",""),
			array("next_page","int(11)","","","0",""),
			array("previous_page","int(11)","","","0","")
		),
	'page_cache' => array(
			array("page_id","int(11)","YES","","",""),
			array("stamp","datetime","YES","","",""),
			array("html","mediumtext","YES","","",""),
			array("path","varchar(1024)","YES","","","")
	),
	'pageblueprint' => array(
			array("object_id","int(11)","","","0",""),
			array("design_id","int(11)","","","0",""),
			array("frame_id","int(11)","","","0",""),
			array("template_id","int(11)","","","0","")
		),
	'page_history' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("user_id","int(11)","","","0",""),
			array("time","datetime","YES","","",""),
			array("data","longtext","YES","","",""),
			array("message","text","YES","","","")
		),
	'page_translation' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("translation_id","int(11)","","","0","")
		),
	'part' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("type","varchar(50)","","","",""),
			array("created","datetime","","","0000-00-00 00:00:00",""),
			array("updated","datetime","","","0000-00-00 00:00:00",""),
			array("dynamic","tinyint(4)","","","0","")
		),
	'part_file' => array(
			array("part_id","int(11)","","","0",""),
			array("file_id","int(11)","","","0",""),
			array("text","varchar(255)","YES","","","")
		),
	'part_formula' => array(
			array("part_id","int(11)","","","0",""),
			array("receivername","varchar(255)","YES","","",""),
			array("receiveremail","varchar(255)","YES","","",""),
			array("recipe","text","YES","","","")
		),
	'part_header' => array(
			array("part_id","int(11)","","","0",""),
			array("level","int(11)","","","1",""),
			array("text","text","YES","","",""),
			array("textalign","varchar(50)","YES","","",""),
			array("fontfamily","varchar(50)","YES","","",""),
			array("fontsize","varchar(50)","YES","","",""),
			array("lineheight","varchar(50)","YES","","",""),
			array("fontweight","varchar(50)","YES","","",""),
			array("color","varchar(50)","YES","","",""),
			array("wordspacing","varchar(50)","YES","","",""),
			array("letterspacing","varchar(50)","YES","","",""),
			array("textdecoration","varchar(50)","YES","","",""),
			array("textindent","varchar(50)","YES","","",""),
			array("texttransform","varchar(50)","YES","","",""),
			array("fontstyle","varchar(50)","YES","","",""),
			array("fontvariant","varchar(50)","YES","","",""),
		),
	'part_horizontalrule' => array(
			array("part_id","int(11)","","","0","")
		),
	'part_html' => array(
			array("part_id","int(11)","","","0",""),
			array("html","text","YES","","",""),
		),
	'part_image' => array(
			array("part_id","int(11)","","","0",""),
			array("image_id","int(11)","","","0",""),
			array("align","varchar(10)","YES","","",""),
			array("greyscale","tinyint(4)","","","0",""),
			array("adaptive","tinyint(4)","","","0",""),
			array("scalemethod","varchar(20)","YES","","",""),
			array("scalewidth","int(11)","YES","","",""),
			array("scaleheight","int(11)","YES","","",""),
			array("scalepercent","int(11)","YES","","",""),
			array("text","varchar(255)","YES","","",""),
			array("frame","varchar(30)","YES","","","")
		),
	'part_imagegallery' => array(
			array("part_id","int(11)","","","0",""),
			array("imagegroup_id","int(11)","","","0",""),
			array("height","int(11)","NO","","64",""),
			array("width","int(11)","NO","","64",""),
			array("framed","tinyint(4)","YES","","0",""),
			array("show_title","tinyint(4)","YES","","0",""),
			array("variant","varchar(10)","YES","","",""),
			array("frame","varchar(30)","YES","","","")
		),
	'part_link' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("part_id","int(11)","","","0",""),
			array("source_type","varchar(20)","YES","","",""),
			array("source_text","text","YES","","",""),
			array("target","varchar(10)","YES","","",""),
			array("target_type","varchar(10)","YES","","",""),
			array("target_value","text","YES","","",""),
			array("alternative","varchar(255)","YES","","",""),
			array("title","varchar(255)","YES","","",""),
			array("position","int(11)","","","0","")
		),
	'part_list' => array(
			array("part_id","int(11)","","","0",""),
			array("title","varchar(255)","YES","","",""),
			array("align","varchar(20)","YES","","",""),
			array("width","varchar(20)","YES","","",""),
			array("maxitems","int(11)","YES","","10",""),
			array("maxtextlength","int(11)","YES","","",""),
			array("variant","varchar(50)","NO","","box",""),
			array("time_count","int(11)","NO","","7",""),
			array("time_type","varchar(255)","NO","","days",""),
			array("show_source","tinyint(4)","NO","","0",""),
			array("show_text","tinyint(4)","NO","","1",""),
			array("show_timezone","tinyint(4)","NO","","0",""),
			array("sort_direction","varchar(10)","NO","","ascending",""),
			array("timezone","varchar(255)","NO","","days","")
		),
	'part_list_object' => array(
			array("part_id","int(11)","","","0",""),
			array("object_id","int(11)","","","0","")
		),
	'part_listing' => array(
			array("part_id","int(11)","","","0",""),
			array("text","text","YES","","",""),
			array("textalign","varchar(50)","YES","","",""),
			array("fontfamily","varchar(50)","YES","","",""),
			array("fontsize","varchar(50)","YES","","",""),
			array("lineheight","varchar(50)","YES","","",""),
			array("fontweight","varchar(50)","YES","","",""),
			array("color","varchar(50)","YES","","",""),
			array("wordspacing","varchar(50)","YES","","",""),
			array("letterspacing","varchar(50)","YES","","",""),
			array("textdecoration","varchar(50)","YES","","",""),
			array("textindent","varchar(50)","YES","","",""),
			array("texttransform","varchar(50)","YES","","",""),
			array("fontstyle","varchar(50)","YES","","",""),
			array("fontvariant","varchar(50)","YES","","",""),
			array("type","varchar(20)","YES","","","")
		),
	'part_mailinglist' => array(
			array("part_id","int(11)","","","0",""),
		),
	'part_mailinglist_mailinglist' => array(
			array("part_id","int(11)","","","0",""),
			array("mailinglist_id","int(11)","","","0","")
		),
	'part_map' => array(
			array("part_id","int(11)","","","0",""),
			array("provider","varchar(50)","YES","","",""),
			array("latitude","decimal(20,17)","YES","","",""),
			array("longitude","decimal(20,17)","YES","","",""),
			array("text","text","YES","","",""),
			array("maptype","varchar(50)","YES","","",""),
			array("markers","text","YES","","",""),
			array("zoom","int(11)","","","0",""),
			array("width","varchar(11)","YES","","",""),
			array("height","varchar(11)","YES","","",""),
			array("frame","varchar(50)","YES","","","")
		),
	'part_menu' => array(
			array("part_id","int(11)","","","0",""),
			array("hierarchy_id","int(11)","","","0",""),
			array("variant","varchar(255)","YES","","",""),
    		array("depth","int(11)","","","0","")
		),
	'part_movie' => array(
			array("part_id","int(11)","","","0",""),
			array("file_id","int(11)","","","0",""),
			array("image_id","int(11)","","","0",""),
			array("text","text","YES","","",""),
			array("code","text","YES","","",""),
			array("url","text","YES","","",""),
            array("width","varchar(20)","YES","","",""),
			array("height","varchar(20)","YES","","","")
		),
	'part_news' => array(
			array("part_id","int(11)","","","0",""),
			array("align","varchar(20)","YES","","",""),
			array("width","varchar(20)","YES","","",""),
			array("news_id","int(11)","YES","","",""),
			array("mode","varchar(20)","YES","","",""),
			array("title","varchar(255)","YES","","",""),
			array("sortby","varchar(20)","YES","","",""),
			array("sortdir","varchar(20)","YES","","",""),
			array("maxitems","int(11)","YES","","",""),
			array("timetype","varchar(20)","YES","","",""),
			array("timecount","int(11)","YES","","",""),
			array("startdate","datetime","YES","","",""),
			array("enddate","datetime","YES","","",""),
			array("variant","varchar(50)","YES","","box","")
		),
	'part_news_newsgroup' => array(
			array("part_id","int(11)","","","0",""),
			array("newsgroup_id","int(11)","","","0","")
		),
	'part_person' => array(
			array("part_id","int(11)","","","0",""),
			array("align","varchar(50)","YES","","",""),
			array("person_id","int(11)","","","0",""),
			array("show_firstname","int(1)","","","1",""),
			array("show_middlename","int(1)","","","1",""),
			array("show_surname","int(1)","","","1",""),
			array("show_initials","int(1)","","","0",""),
			array("show_nickname","int(1)","","","0",""),
			array("show_jobtitle","int(1)","","","1",""),
			array("show_sex","int(1)","","","0",""),
			array("show_email_job","int(1)","","","1",""),
			array("show_email_private","int(1)","","","1",""),
			array("show_phone_job","int(1)","","","1",""),
			array("show_phone_private","int(1)","","","1",""),
			array("show_streetname","int(1)","","","1",""),
			array("show_zipcode","int(1)","","","1",""),
			array("show_city","int(1)","","","1",""),
			array("show_country","int(1)","","","1",""),
			array("show_webaddress","int(1)","","","1",""),
			array("show_image","int(1)","","","1","")
		),
	'part_poster' => array(
			array("part_id","int(11)","","","0",""),
			array("recipe","text","YES","","","")
	),
	'part_richtext' => array(
			array("part_id","int(11)","","","0",""),
			array("html","text","YES","","",""),
		),
	'part_table' => array(
			array("part_id","int(11)","","","0",""),
			array("html","text","YES","","",""),
		),
	'part_text' => array(
			array("part_id","int(11)","","","0",""),
			array("text","text","YES","","",""),
			array("textalign","varchar(50)","YES","","",""),
			array("fontfamily","varchar(50)","YES","","",""),
			array("fontsize","varchar(50)","YES","","",""),
			array("lineheight","varchar(50)","YES","","",""),
			array("fontweight","varchar(50)","YES","","",""),
			array("color","varchar(50)","YES","","",""),
			array("wordspacing","varchar(50)","YES","","",""),
			array("letterspacing","varchar(50)","YES","","",""),
			array("textdecoration","varchar(50)","YES","","",""),
			array("textindent","varchar(50)","YES","","",""),
			array("texttransform","varchar(50)","YES","","",""),
			array("fontstyle","varchar(50)","YES","","",""),
			array("fontvariant","varchar(50)","YES","","",""),
			array("image_id","int(11)","YES","","",""),
			array("imagefloat","varchar(50)","YES","","left",""),
			array("imagewidth","int(11)","YES","","",""),
			array("imageheight","int(11)","YES","","","")
		),
	'path' => array(
			array("object_id","int(11)","","","0",""),
			array("path","text","YES","","",""),
			array("page_id","int(11)","YES","","","")
	),
	'person' => array(
			array("object_id","int(11)","","","0",""),
			array("firstname","varchar(50)","YES","","",""),
			array("middlename","varchar(50)","YES","","",""),
			array("surname","varchar(50)","YES","","",""),
			array("initials","varchar(10)","YES","","",""),
			array("nickname","varchar(20)","YES","","",""),
			array("jobtitle","varchar(30)","YES","","0000-00-00 00:00:00",""),
			array("sex","varchar(10)","YES","","",""),
			array("email_job","varchar(50)","YES","","",""),
			array("email_private","varchar(50)","YES","","",""),
			array("phone_job","varchar(20)","YES","","",""),
			array("phone_private","varchar(20)","YES","","",""),
			array("streetname","varchar(50)","YES","","",""),
			array("zipcode","varchar(4)","YES","","",""),
			array("city","varchar(30)","YES","","",""),
			array("country","varchar(30)","YES","","",""),
			array("webaddress","varchar(30)","YES","","",""),
			array("image_id","int(11)","YES","","0","")
		),
	'person_mailinglist' => array(
			array("person_id","int(11)","","","0",""),
			array("mailinglist_id","int(11)","","","0","")
		),
  	'persongroup' => array(
			array("object_id","int(11)","","","0","")
		),
	'personrole' => array(
			array("object_id","int(11)","","","0",""),
			array("person_id","int(11)","","","0","")
		),
	'persongroup_person' => array(
			array("person_id","int(11)","","","0",""),
			array("persongroup_id","int(11)","","","0","")
		),
	'phonenumber' => array(
		array("object_id","int(11)","","","0",""),
		array("number","varchar(255)","YES","","",""),
		array("context","varchar(255)","YES","","",""),
		array("containing_object_id","int(11)","","","0","")
	    ),
	'problem' => array(
			array("object_id","int(11)","","","0",""),
			array("deadline","datetime","YES","","",""),
			array("containing_object_id","int(11)","","","0",""),
			array("completed","tinyint(1)","YES","","0",""),
			array("milestone_id","int(11)","","","0",""),
			array("priority","float","","","0","")
	    ),
	'product' => array(
			array("object_id","int(11)","","","0",""),
			array("number","varchar(100)","YES","","",""),
			array("image_id","int(11)","YES","","0",""),
			array("producttype_id","int(11)","YES","","0",""),
			array("allow_offer","tinyint(4)","NO","","0","")
		),
	'productattribute' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("product_id","int(11)","","","0",""),
			array("name","varchar(255)","YES","","",""),
			array("value","varchar(255)","YES","","",""),
			array("index","int(11)","","","0","")
		),
	'productgroup' => array(
			array("object_id","int(11)","","","0","")
		),
	'productgroup_product' => array(
			array("product_id","int(11)","","","0",""),
			array("productgroup_id","int(11)","","","0","")
		),
	'productoffer' => array(
			array("object_id","int(11)","","PRI","","auto_increment"),
			array("offer","double","YES","","",""),
			array("product_id","int(11)","","","0",""),
			array("person_id","int(11)","","","0",""),
			array("expiry","datetime","YES","","","")
		),
	'productprice' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("product_id","int(11)","","","0",""),
			array("amount","double","YES","","",""),
			array("type","varchar(255)","YES","","",""),
			array("price","double","YES","","",""),
			array("currency","varchar(5)","YES","","",""),
			array("index","int(11)","","","0","")
		),
	'producttype' => array(
			array("object_id","int(11)","","","0","")
		),
	'project' => array(
		    array("object_id","int(11)","","","0",""),
		    array("parent_project_id","int(11)","","","0","")
		),
	'relation' => array(
			array("from_type","varchar(255)","YES","","object",""),
			array("from_object_id","int(11)","","","0",""),
			array("to_type","varchar(255)","YES","","object",""),
			array("to_object_id","int(11)","","","0",""),
			array("kind","varchar(255)","YES","","","")
		),
	'remotepublisher' => array(
			array("object_id","int(11)","","","0",""),
			array("url","varchar(255)","YES","","","")
		),
	'review' => array(
			array("object_id","int(11)","","","0",""),
			array("accepted","tinyint(4)","YES","","0",""),
			array("date","datetime","YES","","","")
		),
	'search' => array(
			array("page_id","int(11)","","PRI","0",""),
			array("title","varchar(255)","YES","","",""),
			array("text","text","YES","","",""),
			array("pagesenabled","tinyint(4)","YES","","0",""),
			array("pageslabel","varchar(255)","YES","","",""),
			array("pagesdefault","tinyint(4)","YES","","0",""),
			array("pageshidden","tinyint(4)","YES","","0",""),
			array("imagesenabled","tinyint(4)","YES","","0",""),
			array("imageslabel","varchar(255)","YES","","",""),
			array("imagesdefault","tinyint(4)","YES","","0",""),
			array("imageshidden","tinyint(4)","YES","","0",""),
			array("filesenabled","tinyint(4)","YES","","0",""),
			array("fileslabel","varchar(255)","YES","","",""),
			array("filesdefault","tinyint(4)","YES","","0",""),
			array("fileshidden","tinyint(4)","YES","","0",""),
			array("newsenabled","tinyint(4)","YES","","0",""),
			array("newslabel","varchar(255)","YES","","",""),
			array("newsdefault","tinyint(4)","YES","","0",""),
			array("newshidden","tinyint(4)","YES","","0",""),
			array("personsenabled","tinyint(4)","YES","","0",""),
			array("personslabel","varchar(255)","YES","","",""),
			array("personsdefault","tinyint(4)","YES","","0",""),
			array("personshidden","tinyint(4)","YES","","0",""),
			array("productsenabled","tinyint(4)","YES","","0",""),
			array("productslabel","varchar(255)","YES","","",""),
			array("productsdefault","tinyint(4)","YES","","0",""),
			array("productshidden","tinyint(4)","YES","","0",""),
			array("buttontitle","varchar(255)","YES","","","")
		),
	'securityzone' => array(
			array("object_id","int(11)","YES","","",""),
			array("authentication_page_id","int(11)","","","0","")
		),
	'securityzone_page' => array(
			array("securityzone_id","int(11)","","","0",""),
			array("page_id","int(11)","","","0","")
		),
	'securityzone_user' => array(
			array("securityzone_id","int(11)","","","0",""),
			array("user_id","int(11)","","","0","")
		),
	'setting' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("domain","varchar(30)","YES","","",""),
			array("subdomain","varchar(30)","YES","","",""),
			array("key","varchar(30)","YES","","",""),
			array("value","text","YES","","",""),
			array("user_id","int(11)","YES","","0",""),
		),
	'specialpage' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("page_id","int(11)","","","0",""),
			array("type","varchar(30)","YES","","",""),
			array("language","varchar(11)","YES","","","")
		),
	'statistics' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("ip","varchar(255)","YES","","",""),
			array("country","varchar(255)","YES","","",""),
			array("agent","varchar(4096)","YES","","",""),
			array("method","varchar(255)","YES","","",""),
			array("uri","varchar(4096)","YES","","",""),
			array("language","varchar(255)","YES","","",""),
			array("type","varchar(10)","YES","","",""),
			array("value","int(11)","YES","","",""),
			array("session","varchar(255)","YES","","",""),
			array("time","datetime","YES","","",""),
			array("referer","varchar(4096)","YES","","",""),
			array("host","varchar(255)","YES","","",""),
			array("robot","tinyint(4)","YES","","",""),
			array("known","tinyint(4)","YES","","","")
		),
	'task' => array(
			array("object_id","int(11)","","","0",""),
			array("deadline","datetime","YES","","",""),
			array("containing_object_id","int(11)","","","0",""),
			array("completed","tinyint(1)","YES","","0",""),
			array("milestone_id","int(11)","","","0",""),
			array("priority","float","","","0","")
	    ),
	'template' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("unique","varchar(50)","","","","")
		),
	'testphrase' => array(
			array("object_id","int(11)","","","0","")
		),
	'tool' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("unique","varchar(255)","YES","","","")
		),
	'user' => array(
			array("object_id","int(11)","YES","","",""),
			array("username","varchar(50)","","","",""),
			array("password","varchar(50)","","","",""),
			array("email","varchar(50)","","","",""),
			array("language","varchar(5)","","","",""),
			array("internal","tinyint(1)","","","0",""),
			array("external","tinyint(1)","","","0",""),
			array("administrator","tinyint(1)","","","0",""),
			array("secure","tinyint(1)","","","0","")
		),
	'user_permission' => array(
			array("id","int(11)","","PRI","","auto_increment"),
			array("user_id","int(11)","","","0",""),
			array("entity_type","varchar(50)","","","",""),
			array("entity_id","int(11)","","","0",""),
			array("permission","varchar(50)","YES","","","")
		),
	'watermeter' => array(
			array("object_id","int(11)","YES","","0",""),
			array("number","varchar(50)","","","","")
		),
	'waterusage' => array(
			array("object_id","int(11)","YES","","0",""),
			array("watermeter_id","int(11)","YES","","0",""),
			array("value","int(11)","","","0",""),
			array("date","datetime","YES","","",""),
			array("status","int(11)","","","0",""),
			array("source","int(11)","","","0","")
		),
	'weblog' => array(
			array("page_id","int(11)","YES","","",""),
			array("pageblueprint_id","int(11)","YES","","",""),
			array("title","varchar(255)","YES","","","")
		),
	'weblog_webloggroup' => array(
			array("page_id","int(11)","","","0",""),
			array("webloggroup_id","int(11)","","","0","")
		),
	'weblogentry' => array(
			array("object_id","int(11)","YES","","0",""),
			array("text","text","YES","","",""),
			array("date","datetime","YES","","",""),
			array("page_id","int(11)","","","0","")
		),
	'webloggroup' => array(
			array("object_id","int(11)","YES","","","")
		),
	'webloggroup_weblogentry' => array(
			array("weblogentry_id","int(11)","","","0",""),
			array("webloggroup_id","int(11)","","","0","")
		)
);
// Unicode! again
?>