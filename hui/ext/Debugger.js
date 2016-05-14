/**
 * Help stuff
 * @namespace 
 */
hui.ui.Debugger = {
	showList : function() {
		var win = hui.ui.Window.create({title:'Debugger'});
		var list = hui.ui.List.create();
		win.show();
	}
}