function keypresshandler(e) {
	if(document.all) e=window.event;
	if(e.keyCode==13 && e.shiftKey==true) {
		window.location=(OP.Page.path+"Editor/index.php?page="+OP.Page.id);
	}
	return true;
}
document.onkeypress=keypresshandler;