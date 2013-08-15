var controller = {
	dragDrop : [
		{drag:'user',drop:'folder'},
		{drag:'icon',drop:'folder'}
	],
	$ready : function() {
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
	$clickButton$list : function(info) {
		hui.ui.msg.success({text:'Button clicked : '+info.button.getData().key,duration:3000})
	},
	$select$selector : function(item) {
		list.setUrl(item.value);
	}
}