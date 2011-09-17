/** @constructor */
hui.ui.Rendering = function(options) {
	this.options = hui.override({clickObjects:false},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
	//hui.listen(this.element,'click',this._click.bind(this));
}

hui.ui.Rendering.prototype = {
	_click : function(e) {
		e = hui.event(e);
		
	},
	setContent : function(html) {
		this.element.innerHTML = html;
	}
}