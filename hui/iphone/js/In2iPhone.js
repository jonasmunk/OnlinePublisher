var In2iPhone = {};

In2iPhone.addTouchBehavior = function(element,delegate) {
	element.ontouchstart=function() {
		if (element.hasClassName('disabled')) return;
		element.touchEnded=false;
		element.touchCanceled = false;
		element.touchTimer = window.setTimeout(function() {
			if (element.touchEnded) return;
			element.addClassName('touched');
			element.touched=true;
		},100);
	};
	//=element.ontouchcancel
	element.ontouchmove = function() {
		element.touchCanceled = true;
		window.clearTimeout(element.touchTimer);
		if (element.touched) return false;
		//this.style.color='red';
		//this.removeClassName('touched');
		//return false;
	}
	element.ontouchend=function() {
		element.touchEnded=true;
		if (this.hasClassName('disabled')) return;
		if (!element.touched) {
			element.addClassName('touched');
		};
		window.setTimeout(function() {
			element.removeClassName('touched');
		},100);
		if (!element.touchCanceled) {
			delegate.elementWasTouched();
		}
		element.touched=false;
		element.touchCanceled = false;
	};
}

In2iPhone.Button = function(element,name,options) {
	this.element = $(element);
	this.name = name;
	hui.ui.extend(this);
	In2iPhone.addTouchBehavior(this.element,this);
}

In2iPhone.Button.prototype = {
	elementWasTouched : function() {
		hui.ui.callDelegates(this,'click');
	}
}

In2iPhone.goToPage = function(page) {
	
}

In2iPhone.Page = function(element,name,options) {
	this.element = $(element);
	this.name = name;
	hui.ui.extend(this);
}

In2iPhone.Page.prototype = {
	showLeft : function() {
		this.element.removeClassName('hidden_left');	
	},
	hideLeft : function() {
		this.element.addClassName('hidden_left');
	},
	showRight : function() {
		this.element.removeClassName('hidden_right');	
	},
	hideRight : function() {
		this.element.addClassName('hidden_right');
	}
}