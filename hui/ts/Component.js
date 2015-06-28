var __extends = this.__extends || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
var Component = (function () {
    function Component(element, name) {
        this.element = element;
        this.name = name;
    }
    Component.prototype.greet = function () {
        return "Hello, " + this.element;
    };
    return Component;
})();
var SelectOption = (function () {
    function SelectOption() {
    }
    SelectOption.prototype.constructur = function (value, text) {
        this.value = value;
        this.text = text;
    };
    return SelectOption;
})();
var SelectOne = (function (_super) {
    __extends(SelectOne, _super);
    function SelectOne(element, options) {
        _super.call(this, { element: element });
    }
    SelectOne.prototype.setValue = function (value) {
        this.value = value;
    };
    SelectOne.prototype.getValue = function () {
        return this.value;
    };
    return SelectOne;
})(Component);
var node = document.createElement('div');
var s = new SelectOne({ element: node });
s.setValue(5);
