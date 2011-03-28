In2iGui.test = {
	status : null,
	busy : 0,
	run : function(recipe) {
		this.status = {failures:0,successes:0};
		this.busy = 0;
		ui.showMessage({text:'Running test',busy:true});
		this._next(0,recipe);
	},
	_next : function(num,recipe) {
		if (recipe[num]===undefined) {
			this._stop();
			return;
		}
		ui.showMessage({text:'Running test ('+num+')',busy:true});
		if(typeof(recipe[num])=='function') {
			recipe[num]();
			this._next(num+1,recipe);
		} else {
			window.setTimeout(function(){this._next(num+1,recipe)}.bind(this),recipe[num]);
		}
	},
	_stop : function() {
		if (this.busy>0) {
			window.setTimeout(this._stop.bind(this),100);
			return;
		}
		if (this.status.failures>0) {
			ui.showMessage({text:'Failure',icon:'common/warning',duration:2000});
		} else {
			ui.showMessage({text:'Success',icon:'common/success',duration:2000});
		}
	},
	click : function(node,func) {
		this.busy++;
		Syn.click(node,function() {
			if (func) {func()};
			this.busy--;
		}.bind(this));
	},
	type : function(node,text,func) {
		this.busy++;
		Syn.type(node,text,function() {
			if (func) {func()};
			this.busy--;
		}.bind(this));
	},
	_succeed : function(msg) {
		console.info(msg);
		this.status.successes++;
	},
	_fail : function(msg,obj1,obj2) {
		console.error(msg);
		console.info(obj1);
		if (obj2!=undefined) {
			console.info(obj2);
		}
		this.status.failures++;
	},
	
	// Assertion...
	
	assertTrue : function(value,msg) {
		if (value!==true) {
			this._fail('Failure ('+msg+'), not true...',value);
		} else {
			this._succeed('Success, true: '+msg);
		}
	},
	assertFalse : function(value,msg) {
		if (value!==false) {
			this._fail('Failure ('+msg+'), not false...',value);
		} else {
			this._succeed('Success, false: '+msg);
		}
	},
	assertDefined : function(value,msg) {
		if (value===null || value===undefined) {
			this._fail('Failure ('+msg+'), defined...',value);
		} else {
			this._succeed('Success, defined: '+msg);
		}
	},
	assertEquals : function(obj1,obj2,msg) {
		if (obj1!==obj2) {
			this._fail('Failure ('+msg+'), not equal...',obj1,obj2);
		} else {
			this._succeed('Success, equal: '+obj1+'==='+obj2+', '+msg);
		}
	},
	assertNotEquals : function(obj1,obj2,msg) {
		if (obj1===obj2) {
			this._fail('Failure ('+msg+'), not not equal...',obj1,obj2);
		} else {
			this._succeed('Success, not equal: '+obj1+'!=='+obj2+', '+msg);
		}
	},
	assertVisible : function(node,msg) {
		if (node.style.display==='none') {
			this._fail('Failure ('+msg+'), not visible...',node);
		} else {
			this._succeed('Success, visible: '+msg);
		}
	},
	assertNotVisible : function(node,msg) {
		if (node.style.display!=='none') {
			this._fail('Failure ('+msg+'), visible...',node);
		} else {
			this._succeed('Success, not visible: '+msg);
		}
	}
}