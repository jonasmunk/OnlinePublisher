function XWGStyleListType(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleListType.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleListType.prototype.getValue = function() {
	return this.value;
};

XWGStyleListType.prototype.switchValue = function(value) {
	this.value=value;
	this.updateUI();
	this.fireChange();
};

XWGStyleListType.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleListType.prototype.updateUI = function() {
	window.status=this.value;
	if (this.value=='disc')
		document.getElementById(this.id+'-disc').src=this.path+'StyleListTypeDiscHilited.gif';
	else
		document.getElementById(this.id+'-disc').src=this.path+'StyleListTypeDisc.gif';
	if (this.value=='square')
		document.getElementById(this.id+'-square').src=this.path+'StyleListTypeSquareHilited.gif';
	else
		document.getElementById(this.id+'-square').src=this.path+'StyleListTypeSquare.gif';
	if (this.value=='circle')
		document.getElementById(this.id+'-circle').src=this.path+'StyleListTypeCircleHilited.gif';
	else
		document.getElementById(this.id+'-circle').src=this.path+'StyleListTypeCircle.gif';
	if (this.value=='1')
		document.getElementById(this.id+'-1').src=this.path+'StyleListType1Hilited.gif';
	else
		document.getElementById(this.id+'-1').src=this.path+'StyleListType1.gif';
	if (this.value=='a')
		document.getElementById(this.id+'-a').src=this.path+'StyleListTypeaHilited.gif';
	else
		document.getElementById(this.id+'-a').src=this.path+'StyleListTypea.gif';
	if (this.value=='A')
		document.getElementById(this.id+'-AA').src=this.path+'StyleListTypeAAHilited.gif';
	else
		document.getElementById(this.id+'-AA').src=this.path+'StyleListTypeAA.gif';
	if (this.value=='i')
		document.getElementById(this.id+'-i').src=this.path+'StyleListTypeiHilited.gif';
	else
		document.getElementById(this.id+'-i').src=this.path+'StyleListTypei.gif';
	if (this.value=='I')
		document.getElementById(this.id+'-II').src=this.path+'StyleListTypeIIHilited.gif';
	else
		document.getElementById(this.id+'-II').src=this.path+'StyleListTypeII.gif';
};