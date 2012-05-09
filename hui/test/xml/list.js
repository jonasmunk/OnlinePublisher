var controller = {
	dragDrop : [
		{drag:'user',drop:'folder'},
		{drag:'icon',drop:'folder'}
	],
	$ready : function() {
		this.loadUser();
	},
	loadUser : function() {
		list.loadData('data/list_users.xml');
	},
	$drop$user$folder : function(dragged,target) {
		hui.ui.alert({text:hui.string.toJSON(dragged)+' was dropped on '+hui.string.toJSON(target)});
	},
	$drop$icon$folder : function(dragged,target) {
		hui.ui.alert({text:hui.string.toJSON(dragged)+' was dropped on '+hui.string.toJSON(target)});
	},
	$open$list : function(info) {
		alert(hui.string.toJSON(info))
	},
	$buttonClick$list : function(info,button) {
		hui.log('Button was clicked...');
		hui.log(info);
		hui.log(button);
	}
}