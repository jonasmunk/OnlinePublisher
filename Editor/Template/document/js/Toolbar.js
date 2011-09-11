var controller = {
	pageId : null,
	
	$click$close : function() {
		window.parent.location='../../Tools/Pages/index.php';
	},
	$click$publish : function() {
		hui.ui.request({url:'../../Services/Model/PublishPage.php',parameters:{id:this.pageId},onSuccess:function(obj) {
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
		var win = window.parent.frames[1].EditorFrame.getWindow();
		win.controller.newLink();
	},
	$click$editLinks : function() {
		window.parent.frames[1].location='Links.php';
	},
	$click$history : function() {
		window.parent.frames[1].EditorFrame.setUrl('../../Services/PageHistory/');
	}
};

hui.ui.listen(controller);