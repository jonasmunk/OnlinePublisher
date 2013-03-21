
hui.touch={};

hui.touch.makeScrollable = function(id) {
	var c = document.getElementById(id);
	var startScrollTop = 0;
	var startTop = 0;
	c.addEventListener('touchstart',function(event) {
		startScrollTop = c.scrollTop;
		startTop = event.targetTouches[0].pageY;
	});
	c.addEventListener('touchmove',function(event) {
	    event.preventDefault();
		log.innerHTML=event;
	    curX = event.targetTouches[0].pageX;
	    curTop = event.targetTouches[0].pageY;
	    c.scrollTop = startScrollTop+(curTop-startTop)*-1;
	},false);	
}

hui.touch.Button = function(options) {
	this.element = hui.get(options.element);
	hui.ui.extend(this);
}

hui.touch.Button.prototype = {
	_addBehavior : function() {
		
	}
}


//hui.touch.makeScrollable('container');

	document.getElementById('mover').ontouchmove = function(e){
		if(e.touches.length == 1){ // Only deal with one finger
			e.preventDefault();
			var touch = e.touches[0]; // Get the information for finger #1
			var node = touch.target; // Find the node the drag started from
			node.style.position = "absolute";
			node.style.left = touch.pageX + "px";
			node.style.top = touch.pageY + "px";
		}
	}
	
	function log(str) {
		var log = document.getElementById('log');
		log.innerHTML=new Date()+str;
	}
	
var loginController = {
	loginButton : null,
	username : new hui.ui.Input({element:'username'}),
	password : new hui.ui.Input({element:'password'}),
	
	initialize : function() {
		var login = hui.get('login');
		var button = this.loginButton = hui.get.firstByTag(login,'button');
		hui.listen(button,'touchstart',function(e) {
			hui.stop(e);
		})
		hui.listen(button,'touchend',function(e) {
			hui.stop(e);
			this._logIn();
		}.bind(this))
		
		hui.listen(button,'click',function(e) {
			hui.stop(e);
			this._logIn();
		}.bind(this))
	},
	_logIn : function() {
		var values = {
			username : this.username.getValue(),
			password : this.password.getValue()
		}
		try {document.activeElement.blur()} catch(e){}
		hui.cls.add(this.loginButton,'disabled');
		hui.dom.setText(this.loginButton,'Logging in...');
		hui.ui.request({
			url : '../Services/Core/Authentication.php',
			parameters : values,
			$object : this._loginSuccess.bind(this),
			$failure : function() {
				hui.ui.showMessage({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'},icon:'common/warning',duration:4000});
			}.bind(this)
		});
	},
	_loginSuccess : function(response) {
		hui.cls.remove(this.loginButton,'disabled');
		if (response.success) {
			hui.cls.add('login','effect_moveup')
		} else {
			this.password.focus();
			hui.dom.setText(this.loginButton,'Failure, try again');
			hui.effect.wiggle({element:this.loginButton,duration:1000});
		}
	}
}
	
hui.ui.listen({
	$ready : function() {
		if (login) {
			loginController.initialize();
		} else {
			this._reloadList();
		}
	},
	_reloadList : function() {
		hui.ui.request({
			url : 'data/list_pages.php',
			onJSON : function(list) {
				var c = hui.get('list');
				hui.each(list,function(item) {
					var li = hui.build('li',{text:item.title,parent:c});
					hui.listen(li,'click',function() {
						this.loadPage(item.id);
					}.bind(this))
				}.bind(this));
			}.bind(this)
		})
	},
	loadPage : function(id) {
		hui.ui.request({
			url : 'data/page_contents.php?id='+id+'&content=true',
			onSuccess : function(t) {
				hui.get('container').innerHTML = t.responseText;
			}
		})
	}
})
