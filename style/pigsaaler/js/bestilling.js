function Bestilling() {
	this.form = hui.get('bestilling');
	this.form.onsubmit = function() {this.submit(); return false;}.bind(this);
}

Bestilling.prototype = {
	submit : function(e) {
		if (hui.isBlank(this.form['name'].value)) {
			this.form['name'].focus();
			hui.ui.showMessage({text:'Navn skal udfyldes',duration:2000});
			return;
		}
		if (hui.isBlank(this.form['address'].value)) {
			this.form['address'].focus();
			hui.ui.showMessage({text:'Adressen skal udfyldes',duration:2000});
			return;
		}
		if (hui.isBlank(this.form['zipcode'].value)) {
			this.form['zipcode'].focus();
			hui.ui.showMessage({text:'Postnummeret skal udfyldes',duration:2000});
			return;
		}
		if (hui.isBlank(this.form['city'].value)) {
			this.form['city'].focus();
			hui.ui.showMessage({text:'Byen skal udfyldes',duration:2000});
			return;
		}
		if (hui.isBlank(this.form['phone'].value)) {
			this.form['phone'].focus();
			hui.ui.showMessage({text:'Telefonnummeret skal udfyldes',duration:2000});
			return;
		}
		if (hui.isBlank(this.form['email'].value)) {
			this.form['email'].focus();
			hui.ui.showMessage({text:'Emailadressen skal udfyldes',duration:2000});
			return;
		}
		var small = parseInt(this.form['small'].value);
		var large = parseInt(this.form['large'].value);
		if (!(small>0) && !(large>0)) {
			this.form['small'].focus();
			hui.ui.showMessage({text:'Udfyld antal store eller små pigsåler',duration:2000});
			return;
		}
		var message = [
			'Navn: '+this.form['name'].value,
			'Adresse: '+this.form['address'].value,
			'Postnummer: '+this.form['zipcode'].value,
			'By: '+this.form['city'].value,
			'Telefon: '+this.form['phone'].value,
			'Email: '+this.form['email'].value,
			'Antal små: '+this.form['small'].value,
			'Antal store: '+this.form['large'].value,
			'Besked: '+this.form['message'].value
		];
		message = message.join('\n');
		parameters = {
			name : this.form['name'].value,
			email : this.form['email'].value,
			message : message
		}
		hui.ui.showMessage({text:'Sender bestilling, vent venligst...'});
		hui.ui.request({url:op.page.path+'services/feedback/',parameters:parameters,onSuccess:function() {
			hui.ui.showMessage({text:'Sender bestilling, vent venligst... OK',duration:2000});
			hui.ui.alert({title:'Bestillingen er modtaget',text:'Vi kontakter dig hurtigst muligt med yderligere information '});
		},onFailure:function() {
			hui.ui.showMessage({text:'Sender bestilling, vent venligst... Fejl!',duration:2000});
			alert('Der skete en uventet fejl, kontakt venligst via email eller telefon istedet');
		}})
	}
}