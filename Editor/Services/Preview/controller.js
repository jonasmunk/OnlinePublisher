ui.listen({
	$click$close : function() {
		window.parent.location='../../Tools/Pages/';
	},
	$click$edit : function() {
		window.parent.location='../../Template/Edit.php';
	},
	$click$properties : function() {
		window.parent.location='../../Tools/Pages/?action=pageproperties';
	},
	$click$beta : function() {
		var frame = window.parent.frames[1].frames[0];
		frame.op.Editor.editProperties();
	},
	$click$view : function() {
		window.parent.parent.location='ViewPublished.php';
	},
	$click$publish : function() {
		window.parent.location='../Publish/?close=../Preview/';
	}
});