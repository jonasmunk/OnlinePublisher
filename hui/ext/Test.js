/** @namespace */
hui.test = {
	status : null,
	busy : 0,
	run : function(recipe) {
		this.errorHandler = hui.listen(window,'error',function(e) {
			hui.log(e)
			hui.ui.showMessage({text:'Error ('+e.message+') ['+e.lineno+']',icon:'common/warning'});
			throw e;
		});
		this.status = {failures:0,successes:0};
		this.busy = 0;
		hui.ui.showMessage({text:'Running test',busy:true});
		this._next(0,recipe);
		
	},
	_next : function(num,recipe) {
		if (recipe[num]===undefined) {
			this._stop();
			return;
		}
		hui.ui.showMessage({text:'Running test ('+num+')',busy:true});
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
			hui.ui.showMessage({text:'Failure',icon:'common/warning',duration:2000});
		} else {
			hui.ui.showMessage({text:'Success',icon:'common/success',duration:2000});
		}
		hui.unListen(window,'error',this.errorHandler);
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
		if (window.console) {
			console.info(msg);
		}	
		this._log(msg);
		this.status.successes++;
	},
	_fail : function(msg,obj1,obj2) {
		if (window.console) {
			console.error(msg);
			console.info('Object 1:');
			console.info(obj1);
		}
			console.info('Object 2:');
			console.info(obj2);
		this._log(msg,true);
		this.status.failures++;
	},
	_log : function(msg,fail) {
		if (!this.log) {
			var log = this.log = hui.build('div',{parent:document.body,style:'border: 1px solid #eee; padding: 5px; position: fixed; bottom:20px;right:20px; width: 200px; max-height: 200px; overflow: auto; white-space: nowrap; font-family: Monaco, monospace; font-size: 9px; color: #00ff00; background: #000'});
			hui.listen(this.log,'click',function() {
				if (log.style.left=='20px') {
					log.style.left='';
					log.style.top='';
					log.style.width='200px';
					log.style.maxHeight='200px';
				} else {
					log.style.left='20px';
					log.style.top='20px';
					log.style.width='';
					log.style.maxHeight='';
				}
			});
		}
		hui.build('div',{parent:this.log,text:msg,style:fail?'color:red;':''});
	},
	
	// Assertion...
	
	assert : {
		equals : function(obj1,obj2,msg) {
			return hui.test.assertEquals(obj1,obj2,msg);
		},
		notEquals : function(obj1,obj2,msg) {
			return hui.test.assertNotEquals(obj1,obj2,msg);
		},
		'false' : function(value,msg) {
			return hui.test.assertFalse(value,msg)
		},
		'true' : function(value,msg) {
			return hui.test.assertTrue(value,msg)
		},
		'defined' : function(value,msg) {
			return hui.test.assertDefined(value,msg)
		}
	},
	
	assertTrue : function(value,msg) {
		if (value!==true) {
			this._fail('Failure ('+msg+'), not true...',value);
		} else {
			this._succeed('Success, true'+(msg ? ': '+msg : ''));
		}
	},
	assertFalse : function(value,msg) {
		if (value!==false) {
			this._fail('Failure ('+msg+'), not false...',value);
		} else {
			this._succeed('Success, false'+(msg ? ': '+msg : ''));
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
			this._fail('Failure ('+msg+') - '+obj1+'!=='+obj2+', not equal...',obj1,obj2);
		} else {
			this._succeed('Success, equal: '+obj1+'==='+obj2+(msg ? ', '+msg : ''));
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