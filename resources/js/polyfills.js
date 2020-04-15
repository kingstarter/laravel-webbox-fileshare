Number.prototype.padZero = function(size) {
  var s = String(this);
  while (s.length < (size || 2)) {s = '0' + s;}
  return s;
}

if (typeof Array.isArray === 'undefined') {
  Array.isArray = function(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]'
  }
}

if (!String.prototype.endsWith) {
  String.prototype.endsWith = function(search, this_len) {
    if (this_len === undefined || this_len > this.length) {
      this_len = this.length
    }
    return this.substring(this_len - search.length, this_len) === search
  };
}