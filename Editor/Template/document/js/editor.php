<?php
require_once '../../../Include/Private.php';
header('Content-type: text/javascript');

require_once '../../../Include/Private.php';

$controllers = PartService::getAllControllers();
?>

hui.ui.listen({
	$ready : function() {
		var editor = hui.ui.Editor.get();
		editor.setOptions({
			rowClass:'document_row',
			columnClass:'document_column',
			partClass:'part_section'
		});
		editor.addPartController('header','Overskrift',op.Editor.Header);
		editor.addPartController('text','Text',op.Editor.Text);
		editor.ignite();
		editor.activate();
	}
})

<?php
foreach ($controllers as $controller) {
	if ($controller->isLiveEnabled()) {
		require_once '../../../Parts/'.$controller->getType().'/live.js';
		echo "\n\n\n\n";
	}
}
?>

op.FieldResizer = function(options) {
	this.options = options;
	this.options.field.style.overflow='hidden';
	this.dummy = document.createElement('div');
	document.body.appendChild(this.dummy);
	this.dummy.style.position='absolute';
	this.dummy.style.left='-999999px';
	this.dummy.style.top='-999999px';
	this.dummy.style.visibility='hidden';
	var self = this;
	hui.listen(options.field,'keyup',function(){self.resize(false,true)});
	hui.listen(options.field,'keydown',function(){self.options.field.scrollTop=0;});
}

op.FieldResizer.prototype = {
	resize : function(instantly,focused) {
				
		var field = this.options.field;
		hui.style.copy(field,this.dummy,[
			'font-size','line-height','font-weight','letter-spacing','word-spacing','font-family','text-transform','font-variant','text-indent'
		]);
		var html = field.value;
		if (html[html.length-1]==='\n') {
			html+='x';
		}
		// Force webkit redraw
		if (!focused) {
			field.style.display='none';
			field.offsetHeight; // no need to store this anywhere, the reference is enough
			field.style.display='block';
		}
		this.dummy.innerHTML = html.replace(/\n/g,'<br/>');
		this.options.field.style.webkitTransform = 'scale(1)';
		this.dummy.style.width=this.options.field.clientWidth+'px';
		var height = Math.max(50,this.dummy.clientHeight)+'px';
		hui.log(height)
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			//this.options.field.scrollTop=0;
			hui.animate(this.options.field,'height',height,200,{ease:hui.ease.slowFastSlow});
		}
	}
}