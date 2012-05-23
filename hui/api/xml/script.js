hui.ui.listen({
	$ready : function() {
		var modules = hui.get.firstByClass(document.body,'modules');
		var ms = hui.get.byTag(modules,'a');
		for (var i=0; i < ms.length; i++) {
			hui.listen(ms[i],'click',this._onModuleClick.bind(this))
		}
	},
	_onModuleClick : function(e) {
		e = hui.event(e);
		var a = e.findByTag('a'),
			module = a.getAttribute('data')
		hui.ui.showMessage({text:module ? 'Module = '+module : 'All',duration:1000})
		this._showComponents({module:module})
	},
	_showComponents : function(filter) {
		var components = hui.get.byClass(document.body,'component');
		for (var i=0; i < components.length; i++) {
			var component = components[i]
			if (!filter.module || hui.cls.has(component,filter.module)) {
				component.style.display='block';
			} else {
				component.style.display='none';
			}
		};
	}
})
