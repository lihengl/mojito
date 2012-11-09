function createXHR() {
    if (typeof XMLHttpRequest != "undefined") {
        return new XMLHttpRequest();
    } else if (typeof ActiveXObject != "undefined") {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        throw new Error("no XHR object available.");
    }
}

function fireXHRWithValueAndMarkupRequest(query) {
    var xhr = createXHR();

    xhr.onreadystatechange = function() {
        var container = document.getElementById("resultlist");

        if (xhr.readyState == 4 && xhr.status == 200) {
            container.innerHTML = xhr.responseText;
        } else {
            container.innerHTML = "ajax failed";
        }
    }

    xhr.open("GET", "main.php?query="+query, true);
    xhr.send();
}

function processInput(oldValue, newValue) {

    if (oldValue.length == 0) {
        alert("fire xhr with value only");
    } else if (newValue.length == 0) {
        alert("clear ba");
    } else if (oldValue.length < newValue.length) {
        alert("filter out");
    } else {
        alert("loose");
    }
}

var scanInput = function() {

    var query = this.value;

    if (typeof scanInput.lastValue == "undefined") {
        fireXHRWithValueAndMarkupRequest(query);
    } else if (scanInput.lastValue == this.value) {
        // content not changed, do nothing?
    } else {
        processInput(scanInput.lastValue, this.value);
    }

    scanInput.lastValue = this.value;
};

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

function main() {
    var inputbox = document.getElementById("searchbox");
    EventManager.addHandler(inputbox, "keyup", scanInput);
}