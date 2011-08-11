var controller = {
	pageId : null,
	
	$ready : function() {
		if (window.parent) {
			window.parent.baseController.changeSelection('service:preview');
		}
	},
	pageDidLoad : function(id) {
		this.pageId = id;
		hui.ui.request({url:'viewer/data/LoadPageStatus.php',parameters:{id:id},onJSON:function(obj) {
			publish.setEnabled(obj.changed);
		}});
	},
	pageDidChange : function() {
		publish.setEnabled(true);
	},
	
	$click$close : function() {
		this.getFrame().location='../../Tools/Pages/';
	},
	$click$edit : function() {
		var frame = window.frames[0];
		if (frame.templateController!==undefined) {
			frame.templateController.edit();
		} else {
			this.getFrame().location='../../Template/Edit.php';
		}
	},
	$click$properties : function() {
		var frame = window.frames[0];
		frame.op.Editor.editProperties();
	},
	$click$view : function() {
		window.parent.location='ViewPublished.php';
	},
	$click$publish : function() {
		hui.ui.request({
			url : 'viewer/data/PublishPage.php',
			parameters : {id:this.pageId},
			onSuccess : function(obj) {
				publish.setEnabled(false);
			}
		});
	},
	getFrame : function() {
		return window.parent.frames[0];
	}
};

hui.ui.listen(controller);