if (!op) {var op={}};

if (!op.part) {op.part={}};

op.part.utils = {
	previewTimer : null,
	
	updatePreview : function(options) {
		var f = function() {
			var url = controller.context+'Editor/Services/Parts/Preview.php?type='+options.type;
			var parms = options.form.serialize(true);
			ui.request({url:url,parameters:parms,onSuccess:function(t) {
				options.node.innerHTML=t.responseText;
				if (options.onComplete) {
					options.onComplete();
				}
			},onFailure:function(e) {
				n2i.log(e);
			}});
			/*
			new Ajax.Request(url,{parameters:parms,onSuccess:function(t) {
				options.node.update(t.responseText);
				if (options.onComplete) {
					options.onComplete();
				}
			}});*/
		}
		window.clearTimeout(this.previewTimer);
		if (options.delay) {
			this.previewTimer = window.setTimeout(f,options.delay);
		} else {
			f();
		}
	}
}