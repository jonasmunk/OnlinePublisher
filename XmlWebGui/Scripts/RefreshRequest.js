In2iGui.RefreshRequest = function (interval,url) {
	this.interval = interval;
	this.url = url;
	this.request();
}

In2iGui.RefreshRequest.prototype.request = function () {
	var self = this;
	
	setTimeout(function() {
		var delegate = {
			onSuccess : function(t) {
				if (t.responseText=='true') {
					document.location.reload();
				} else {
					self.request();
				}
			}
		}
		var request = new N2i.Request(delegate);
		request.request(self.url);
	}, this.interval);
}