var controller = {
	pageId : null,
	
	$click$close : function() {
		window.parent.location='../../Tools/Sites/';
	},
	$click$publish : function() {
		hui.ui.request({url:'../../Services/Model/PublishPage.php',parameters:{id:this.pageId},onSuccess:function() {
			publish.disable();
		}});
	},
	$click$preview : function() {
		window.parent.location='../../Services/Preview/?id='+this.pageId;
	},
	$click$properties : function() {
		window.parent.location='../../Tools/Sites/?pageInfo='+this.pageId;
	}
};

hui.ui.listen(controller);