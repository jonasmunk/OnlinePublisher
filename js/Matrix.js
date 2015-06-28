hui.ui.Matrix = function(options) {
	this.options = options = options || {};
	this.element = hui.get(options.element);
	hui.ui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

hui.ui.Matrix.create = function(options) {	
	options.element = hui.build('div',{'class':'hui_matrix',parent:hui.get(options.parent),style:'width: 100%; height: 100%;'});
	return new hui.ui.Matrix(options);
}

hui.ui.Matrix.prototype = {
	$$layout : function() {
		
	},
	$objectsLoaded : function(data) {
		this.setData(data);
		this.render();
	}
}