
hui.touch={};

hui.touch.makeScrollable = function(id) {
	var c = document.getElementById(id);
	var startScrollTop = 0;
	var startTop = 0;
	c.addEventListener('touchstart',function(event) {
		startScrollTop = c.scrollTop;
		startTop = event.targetTouches[0].pageY;
	});
	c.addEventListener('touchmove',function(event) {
	    event.preventDefault();
		log.innerHTML=event;
	    curX = event.targetTouches[0].pageX;
	    curTop = event.targetTouches[0].pageY;
	    c.scrollTop = startScrollTop+(curTop-startTop)*-1;
	},false);	
}

//hui.touch.makeScrollable('container');

	document.getElementById('mover').ontouchmove = function(e){
		if(e.touches.length == 1){ // Only deal with one finger
			e.preventDefault();
			var touch = e.touches[0]; // Get the information for finger #1
			var node = touch.target; // Find the node the drag started from
			node.style.position = "absolute";
			node.style.left = touch.pageX + "px";
			node.style.top = touch.pageY + "px";
		}
	}
	
	function log(str) {
		var log = document.getElementById('log');
		log.innerHTML=new Date()+str;
	}
	
hui.ui.listen({
	$ready : function() {
		hui.ui.request({
			url : 'data/list_pages.php',
			onJSON : function(list) {
				var c = hui.get('list');
				hui.each(list,function(item) {
					var li = hui.build('li',{text:item.title,parent:c});
					hui.listen(li,'click',function() {
						this.loadPage(item.id);
					}.bind(this))
				}.bind(this));
			}.bind(this)
		})
	},
	loadPage : function(id) {
		hui.get('container').innerHTML = '<div>'+id+'</div>';
	}
})
