op.feedback.Controller = {
	busy : false,
	
	init : function(a) {
		this.busy = false;
		this.link = a;
		a.style.display = 'none';
		var html = 
			'<h1>Send besked</h1>'+
			'<p class="layout_feedback_form_hint">Beskriv venligst hvordan vi kan gøre siden bedre...</p>'+
			'<p><label>Din e-post-adresse - ikke krævet</label><input type="text" name="email"></p>'+
			'<p><label>Tekst</label><textarea></textarea></p>'+
			'<p><a href="javascript://" onclick="op.feedback.Controller.send(this)">Send besked</a><a href="javascript://" onclick="op.feedback.Controller.cancel()">Annuller</a></p>';
		this.element = hui.build('div',{before:a.parentNode,className:'layout_feedback_form',html:html});
		
		this.email = hui.get.firstByTag(this.element,'input');
		this.message = hui.get.firstByTag(this.element,'textarea');
		this.email.focus();
	},
	send : function(button) {
		if (this.busy) {
			return;
		}
		var textarea = hui.get.firstByTag(this.element,'textarea');
		var email = hui.get.firstByTag(this.element,'input');
		if (hui.isBlank(textarea.value)) {
			textarea.focus();
			return;
		}
		var params = {
			pageId : op.page.id,
			text : this.message.value,
			email : this.email.value
		}
		this.busy = true;
		hui.ui.request({
			url : op.page.path+'services/issues/feedback/',
			parameters : params,
			$success : function() {
				this.busy = false;
				this.showMessage('Tak for det!','Beskeden er sendt og vi ser på sagen hurtigst muligt.')
			}.bind(this),
			$failure : function() {
				this.busy = false;
				this.showMessage('Der skete en fejl','Kontakt os venligst på anden vis.')
			}.bind(this)
		})
	},
	cancel : function() {
		this._clear();
	},
	_clear : function() {
		hui.dom.remove(this.element);
		this.link.style.display = '';
	},
	showMessage : function(title,text) {
		this.element.innerHTML = 
			'<h1>'+hui.string.escape(title)+'</h1>'+
			'<p class="layout_feedback_form_hint">'+hui.string.escape(text)+'</p>'+
			'<p><a href="javascript://" onclick="op.feedback.Controller.cancel()">OK</a></p>'
	}
}