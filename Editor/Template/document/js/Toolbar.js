var controller = {
	pageId : null,
	
	$click$close : function() {
		window.parent.location='../../Tools/Pages/index.php';
	},
	$click$publish : function() {
		ui.request({url:'../../Services/Model/PublishPage.php',parameters:{id:this.pageId},onSuccess:function(obj) {
			publish.setEnabled(false);
		}});
	},
	/*
	$click$publish : function() {
		window.location='Publish.php';
	},*/
	$click$preview : function() {
		window.parent.location='../../Services/Preview/?id='+this.pageId;
	},
	$click$properties : function() {
		window.parent.location='../../Tools/Pages/?action=pageproperties&amp;id='+this.pageId;
	},
	$click$newLink : function() {
		document.location='Toolbar.php?link=true';
	},
	$click$editLinks : function() {
		window.parent.Frame.location='ListOfLinks.php';
	},
	$click$editLayout : function() {
		window.parent.Frame.EditorFrame.setUrl('Editor.php?toggleLayoutMode=true');
	},
	$click$history : function() {
		window.parent.Frame.EditorFrame.setUrl('../../Services/PageHistory/');
	}
};

ui.get().listen(controller);