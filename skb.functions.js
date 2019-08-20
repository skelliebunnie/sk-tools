// do NOT enclose in (document).ready !!
// and do NOT call these functions from this file!!
function findObjByProperty(arr, value) {
    value = jQuery.trim(value);
	var obj = arr.filter( index => ( index.name.toLowerCase() === `${value.toLowerCase()}` ) );

	if( obj.length === 0 ) {
		return null;

	} else {
		return arr.findIndex( index => index.name.toLowerCase() === `${value.toLowerCase()}` );
	}
}

function ucFirst(value) {
    value = jQuery.trim(value);
	return value.substr(0,1).toUpperCase()+value.substr(1);
}

function lcFirst(value) {
    value = jQuery.trim(value);
	return value.substr(0,1).toLowerCase()+value.substr(1);
}

function strpos(value) {
    return value.indexOf(',' !== -1);
}

function inArrayCaseInsensitive(haystackArray, needle){
    //Iterates over an array of items to return the index of the first item that matches the provided val ('needle') in a case-insensitive way.  Returns -1 if no match found.
    var defaultResult = -1;
    var result = defaultResult;
    jQuery.each(haystackArray, function(index, value) { 
        if (result == defaultResult && value.toLowerCase() == needle.toLowerCase()) {
            result = index;
        }
    });

    if( result === -1 ) {
    	return false;
    } else {
    	return true;
    }
}