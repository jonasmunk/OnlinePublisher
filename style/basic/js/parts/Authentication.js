op.part.Authentication = function(options) {
	this.element = hui.get(options.element);
  this.nodes = {
    form : 'part_authentication_form',
		username : 'part_authentication_username',
		password : 'part_authentication_password',
		login : 'part_authentication_login'
	};
	hui.ui.extend(this);
	this._attach();
}

op.part.Authentication.prototype = {
	busy : false,
	
	_attach : function() {
		hui.listen(this.nodes.form,'submit',this.login.bind(this))
	},
  _setMessage : function(str) {
    
  },
	login : function() {
		var nodes = this.nodes;
		var values = {
			username : nodes.username.value,
			password : nodes.password.value,
		};
		if (hui.isBlank(values.username) || hui.isBlank(values.password)) {
			return;
		}
		var target = hui.location.getInt('page');
		var redirect = document.location.href;
		if (target) {
			redirect = op.context + '?id=' + target;
		}
		hui.cls.add(this.element,'part_authentication_busy');
		hui.ui.request({
			url : op.context + 'services/authentication/',
			parameters : values,
			$success : function() {
				document.location.href = redirect;
			},
			$failure : function() {
				
			},
			$forbidden : function() {
				
			},
			$finally : function() {
				hui.cls.remove(this.element,'part_authentication_busy');
			}.bind(this)
		})
	}
}

window.define && define(
	'op.part.Authentication',
	op.part.Authentication
);