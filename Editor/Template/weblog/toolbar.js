var controller = {
	pageId : null,
	
	$click$close : function() {
		window.parent.location='../../Tools/Pages/index.php';
	},
	$click$publish : function() {
		ui.request({url:'../../Services/Model/PublishPage.php',parameters:{id:this.pageId},onSuccess:function() {
			publish.disable();
		}});
	},
	$click$preview : function() {
		window.parent.location='../../Services/Preview/?id='+this.pageId;
	},
	$click$properties : function() {
		window.parent.location='../../Tools/Pages/?action=pageproperties&amp;id='+this.pageId;
	},
	$click$history : function() {
		window.parent.Frame.location=('../../Services/PageHistory/');
	}
};

ui.get().listen(controller);