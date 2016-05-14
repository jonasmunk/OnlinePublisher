if (window.hui===undefined) {
    hui = {};
}

hui._ready = false;

hui.ready = function(delegate) {
	if (window.addEventListener) {
		window.addEventListener('DOMContentLoaded',delegate,false);
	}
    else if(document.addEventListener) {
		document.addEventListener('load', delegate, false);
	}
	else if(typeof window.attachEvent != 'undefined') {
		window.attachEvent('onload', delegate);
	}
	else {
		if(typeof window.onload == 'function') {
			var existing = window.onload;
			window.onload = function() {
				existing();
				delegate();
			};
		} else {
			window.onload = delegate;
		}
	}  
};