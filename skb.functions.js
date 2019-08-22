// do NOT enclose in (document).ready !!
// and do NOT call these functions from this file!!

// Find Object (in array) by value, given a key to check
// Use when the key isn't 'name'
function findObjectByKey(array, key, value) {
  for (var i = 0; i < array.length; i++) {
  if (array[i][key] === value) {
  return array[i];
  }
  }
  return null;
}

// Find Object in array by property value, assuming key is 'name'
// Uses ES6 syntax (with the =>) -- I have no idea how to use a variable as the key name, here
function findObjByProperty(arr, value) {
  value = jQuery.trim(value);

  var obj = arr.filter( index => index.name.toLowerCase() === `${value.toLowerCase()}` );

  if( obj.length === 0 ) {
  	return null;

  } else {
  	return arr.findIndex( index => index.name.toLowerCase() === `${value.toLowerCase()}` );
  }
}

// returns TRUE if arrays match EXACTLY (order doesn't matter)
function compArrays(arr1, arr2) {
  return jQuery(arr1).not(arr2).length === 0 && jQuery(arr2).not(arr1).length === 0;
}

// from tutorial: https://gomakethings.com/check-if-two-arrays-or-objects-are-equal-with-javascript/
// explicitly for comparing ARRAYs and OBJECTs *only*
function isEqual(value, other) {
  // Get the value type
  var type = Object.prototype.toString.call(value);

  // If the two objects are not the same type, return false
  if (type !== Object.prototype.toString.call(other)) return false;

  // If items are not an object or array, return false
  if (['[object Array]', '[object Object]'].indexOf(type) < 0) return false;

  // Compare the length of the length of the two items
  var valueLen = type === '[object Array]' ? value.length : Object.keys(value).length;
  var otherLen = type === '[object Array]' ? other.length : Object.keys(other).length;
  if (valueLen !== otherLen) return false;

  // Compare two items
  var compare = function (item1, item2) {

    // Get the object type
    var itemType = Object.prototype.toString.call(item1);

    // If an object or array, compare recursively
    if (['[object Array]', '[object Object]'].indexOf(itemType) >= 0) {
      if (!isEqual(item1, item2)) return false;
    }

    // Otherwise, do a simple comparison
    else {

      // If the two items are not the same type, return false
      if (itemType !== Object.prototype.toString.call(item2)) return false;

      // Else if it's a function, convert to a string and compare
      // Otherwise, just compare
      if (itemType === '[object Function]') {
        if (item1.toString() !== item2.toString()) return false;
      } else {
        if (item1 !== item2) return false;
      }

    }
  };

  // Compare properties
  if (type === '[object Array]') {
    for (var i = 0; i < valueLen; i++) {
      if (compare(value[i], other[i]) === false) return false;
    }
  } else {
    for (var key in value) {
      if (value.hasOwnProperty(key)) {
        if (compare(value[key], other[key]) === false) return false;
      }
    }
  }

  // If nothing failed, return true
  return true;
}

function ucFirst(value) {
  value = jQuery.trim(value);
	return value.substr(0,1).toUpperCase()+value.substr(1);
}

function lcFirst(value) {
  value = jQuery.trim(value);
	return value.substr(0,1).toLowerCase()+value.substr(1);
}

function strpos(haystack, needle) {
  return haystack.indexOf(needle !== -1);
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