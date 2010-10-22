var partController = {
	$ready : function() {
		var field = this.field = $('PartTextTextarea');
		field.focus();
		field.select();
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
	},
	syncSize : function() {
		this.resizer.resize();
	}
}

ui.listen(partController);

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
	options.field.observe('keyup',function(){self.resize()});
}

op.FieldResizer.prototype = {
	resize : function(instantly) {
				
		var field = this.options.field;		n2i.copyStyle(field,this.dummy,[
			'fontSize','lineHeight','fontWeight','letterSpacing','wordSpacing','fontFamily','textTransform','fontVariant','textIndent'
		]);
		var html = field.value;
		if (html[html.length-1]==='\n') {
			html+='x';
		}
		// Force webkit redraw
		field.style.display='none';
		field.offsetHeight; // no need to store this anywhere, the reference is enough
		field.style.display='block';
		
		this.dummy.innerHTML = html.replace(/\n/g,'<br/>');
		this.options.field.style.webkitTransform = 'scale(1)';
		this.dummy.style.width=this.options.field.clientWidth+'px';
		var height = Math.max(50,this.dummy.clientHeight)+'px';
		n2i.log(this.options.field.clientWidth)
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			n2i.animate(this.options.field,'height',height,200,{ease:n2i.ease.slowFastSlow});
		}
	}
}