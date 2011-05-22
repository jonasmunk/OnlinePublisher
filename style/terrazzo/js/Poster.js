hui.ui.onReady(function() {
	var recipe1 = [
		{duration:500},
		{element:'image-1',property:'scrollLeft',value:'348',duration:10000,ease:hui.ease.slowFastSlow},
		{duration:4000},
		{element:'image-1',property:'scrollLeft',value:'0',duration:10000,ease:hui.ease.slowFastSlow}
	];
	new hui.animation.Loop(recipe1).start();
	
	var recipe2 = [
		{duration:8000},
		{element:'image-2',property:'scrollLeft',value:'172',duration:5000,ease:hui.ease.slowFastSlow},
		{duration:4000},
		{element:'image-2',property:'scrollLeft',value:'0',duration:5000,ease:hui.ease.slowFastSlow}
	];
	new hui.animation.Loop(recipe2).start();
	
	var recipe3 = [
		{duration:1000},
		{element:'image-3',property:'scrollLeft',value:'169',duration:5000,ease:hui.ease.slowFastSlow},
		{duration:6000},
		{element:'image-3',property:'scrollLeft',value:'0',duration:5000,ease:hui.ease.slowFastSlow}
	];
	new hui.animation.Loop(recipe3).start();
	
	var recipe4 = [
		{duration:3000},
		{element:'image-4',property:'scrollLeft',value:'197',duration:7000,ease:hui.ease.slowFastSlow},
		{duration:8000},
		{element:'image-4',property:'scrollLeft',value:'0',duration:7000,ease:hui.ease.slowFastSlow}
	];
	new hui.animation.Loop(recipe4).start();
});