var EventManager = {

    addHandler: function(element, type, handler) {

        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },

    removeHandler: function(element, type, handler) {

        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on", + type] = null;
        }
    }

};

var update = function() {
    // TODO
};

function main() {
    var inputbox = document.getElementById("searchbox");
    EventManager.addHandler(inputbox, "keyup", update);
}