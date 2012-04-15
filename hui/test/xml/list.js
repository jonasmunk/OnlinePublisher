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
		hui.ui.alert({text:Object.toJSON(dragged)+' was dropped on '+Object.toJSON(target)});
	},
	$drop$icon$folder : function(dragged,target) {
		hui.ui.alert({text:Object.toJSON(dragged)+' was dropped on '+Object.toJSON(target)});
	},
	$open$list : function(info) {
		alert(Object.toJSON(info))
	},
	$buttonClick$list : function(info,button) {
		hui.log('Button was clicked...');
		hui.log(info);
		hui.log(button);
	}
}