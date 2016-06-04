hui.store = {

  isSupported: function() {
    try {
      return hui.isDefined(window.localStorage);
    } catch (e) {
      return false;
    }
  },

  set : function(key, value) {
    if (this.isSupported()) {
      localStorage.setItem(key, value);
    }
  },
  get : function(key) {
    if (this.isSupported()) {
      return localStorage.getItem(key);
    }
    return null;
  },
  setObject : function(key, value) {
    this.set(key,hui.string.toJSON(value));
  },
  getObject : function(key) {
    return hui.string.fromJSON(this.get(key));
  }
};