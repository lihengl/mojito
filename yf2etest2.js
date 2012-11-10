var ReceiverId = "searchbox";
var DisplayerId = "resultlist";

var Handler = "main.php";
var QueryKey = "query";

var Result = ["hoo", "haa"];

function createXhr() {
    if (typeof XMLHttpRequest != "undefined") {
        return new XMLHttpRequest();
    } else if (typeof ActiveXObject != "undefined") {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        throw new Error("no XHR object available.");
    }
}

function appendUrlParam(url, name, value) {
    url += (url.indexOf("?") == -1 ? "?" : "&");
    url += encodeURIComponent(name) + "=" + encodeURIComponent(value);
    return url;
}

function filter(candidate, criteria) {
    var passed = false;

    if (candidate.indexOf(criteria) != 0) {
        passed = false;
    } else {
        passed = true;
    }

    return passed;
}

function processResponse(response) {
    var container = document.getElementById(DisplayerId);
    var results = JSON.parse(response);

    var index = 0;
    var count = results.length;

    for (index = 0; index < count; index++) {
        var wordEntry = document.createElement("ul");
        var titleItem = document.createElement("li");
        var descriptionItem = document.createElement("li");

        wordEntry.appendChild(titleItem);
        wordEntry.appendChild(descriptionItem);

        titleItem.innerHTML = results[index].wordtitle;
        descriptionItem.innerHTML = results[index].descriptiontext;

        wordEntry.className = "entry";
        container.appendChild(wordEntry);
    }
}

function request(key) {
    var xhr = createXhr();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
                processResponse(xhr.responseText);
            } else {
                var container = document.getElementById(DisplayerId);                
                container.innerHTML = "ajax failed";
            }
        }
    }

    Handler = appendUrlParam(Handler, QueryKey, key);

    xhr.open("get", Handler, true);
    xhr.send(null);
}

function flush() {
    var container = document.getElementById(DisplayerId);
    container.innerHTML = "";
}

function processInput(oldValue, newValue) {

    if (oldValue.length == 0) {
        request(newValue);
    } else if (newValue.length == 0) {
        flush();
    } else if (oldValue.length < newValue.length) {
        alert("filter out");
    } else {
        alert("loosen");
    }
}

var scanInput = function() {

    var query = this.value;

    if (typeof scanInput.lastValue == "undefined") {
        request(query);
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
    var inputbox = document.getElementById(ReceiverId);
    EventManager.addHandler(inputbox, "keyup", scanInput);
}