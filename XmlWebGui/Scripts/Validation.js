function validateEmail( strValue) {
    var objRegExp  = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;

    return objRegExp.test(strValue);
}

function validateInteger( strValue ) {
    var objRegExp  = /(^-?\d\d*$)/;    
    return objRegExp.test(strValue);
}

function validateNotEmpty( strValue ) {
    if(strValue.length > 0)
		return true;
	else
		return false;
}
 
