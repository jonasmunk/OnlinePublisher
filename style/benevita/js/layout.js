ui.onReady(function() {
	var blocks = n2i.get('blockquote');
	var timer;
	var index = -1;
	timer = function() {
		if (index>-1) {
			blocks[index].style.display='';
		}
		index++;
		if (index>blocks.length-1) {
			index = 0;
		}
		blocks[index].style.display='block';
		window.setTimeout(timer,6000);
	}
	timer();
})