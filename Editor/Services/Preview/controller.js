var controller = {
	pageId : null,
	
	pageDidLoad : function(id) {
		this.pageId = id;
		ui.request({url:'viewer/data/LoadPageStatus.php',parameters:{id:id},onJSON:function(obj) {
			publish.setEnabled(obj.changed);
		}});
	},
	pageDidChange : function() {
		publish.setEnabled(true);
	},
	
	$click$close : function() {
		window.parent.location='../../Tools/Pages/';
	},
	$click$edit : function() {
		window.parent.location='../../Template/Edit.php';
	},
	$click$properties : function() {
		var frame = window.parent.frames[1].frames[0];
		frame.op.Editor.editProperties();
	},
	$click$view : function() {
		window.parent.parent.location='ViewPublished.php';
	},
	$click$publish : function() {
		ui.request({url:'viewer/data/PublishPage.php',parameters:{id:this.pageId},onSuccess:function(obj) {
			publish.setEnabled(false);
		}});
	}
};

ui.listen(controller);