In2iGui.test = {
	run : function(recipe) {
		ui.showMessage({text:'Starting test',busy:true});
		this._next(0,recipe);
	},
	_next : function(num,recipe) {
		if (recipe[num]===undefined) {
			window.setTimeout(function() {ui.showMessage({text:'Test completed',duration:1000})},1000);
		}
		else if(typeof(recipe[num])=='function') {
			recipe[num]();
			this._next(num+1,recipe);
		} else {
			window.setTimeout(function(){this._next(num+1,recipe)}.bind(this),recipe[num]);
		}
	},
	click : function(node) {
		fireunit.click(node);
	},
	mouseDown : function(node) {
		fireunit.mouseDown(node);
	},
	key : function(node,character) {
		fireunit.key( node, character );
	}
}

In2iGui.assert = {
	'true' : function(value,msg) {
		if (value!==true) {
			n2i.log('Failure ('+msg+'), not true...');
			n2i.log(value);
		}
	},
	'false' : function(value,msg) {
		if (value===true) {
			n2i.log('Failure ('+msg+'), not false...');
			n2i.log(value);
		}
	},
	visible : function(node,msg) {
		this.true(node.style.display!=='none',msg);
	},
	notVisible : function(node,msg) {
		this.true(node.style.display==='none',msg);
	},
	equals : function(obj1,obj2,msg) {
		if (obj1!==obj2) {
			n2i.log('Failure ('+msg+'), not equal...');
			n2i.log(obj1);
			n2i.log(obj2);
		}
	},
	notEquals : function(obj1,obj2,msg) {
		this.true(obj1!==obj2,msg);
	}
}

if (!window.fireunit) {
	window.fireunit = {
		ok : function(value,msg) {
			
		},
		click : function(node) {
			
		},
		mouseDown : function(node) {
			
		},
		key : function(node,character) {
			
		}
	}
}