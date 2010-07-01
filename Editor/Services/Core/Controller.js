if (!OP) var OP = {};

OP.Controller = function() {
	this.startCommunication();
}

OP.Controller.getInstance = function() {
	if (!OP.Controller.instance) {
		OP.Controller.instance = new OP.Controller();
	}
	return OP.Controller.instance;
}


OP.Controller.prototype.startCommunication = function() {
	var self = this;
	this.lifeline = window.setInterval(
		function() {
			var req = new N2i.Request(self);
			req.request('Services/Core/Server.php');
		}
		,5000
	);
}

OP.Controller.prototype.onFailure = function(t) {
}

OP.Controller.prototype.onSuccess = function(t) {
	window.status = t.responseText;
}


//OP.Controller.getInstance();