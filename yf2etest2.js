var ReceiverId = "searchbox";
var ResultListId = "resultlist";
var SuggestListId = "suggestlist";

var requestHandler = "index.php";
var QueryName = "ajax_query";

function createXHR() {
    if (typeof XMLHttpRequest != "undefined") {
        return new XMLHttpRequest();
    } else if (typeof ActiveXObject != "undefined") {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        throw new Error("no XHR object available.");
    }
}

function appendURLParam(url, name, value) {
    url += (url.indexOf("?") == -1 ? "?" : "&");
    url += encodeURIComponent(name) + "=" + encodeURIComponent(value);
    return url;
}

var queriedData = {

    displaySuggest: function() {
        var suggestList = document.getElementById(SuggestListId);
        suggestList.innerHTML = "";    
        while (suggestlist.hasChildNodes()) {
            suggestList.removeChild(suggestList.lastChild);
        }

        var inputField = document.getElementById(ReceiverId);
        var filter = inputField.value.toLowerCase();
        var resultCount = this.wordList.length;
        var displayedCount = 0;

        for (index = 0; index < resultCount; index++) {
            var word = this.wordList[index];

            if (displayedCount >= 3) {
                break;
            } else if (word.name.indexOf(filter) !== 0) {
                // got filtered out, do not display it
                continue;
            } else {
                var suggestItem = document.createElement("li");
                suggestItem.innerHTML = word.name;
                suggestList.appendChild(suggestItem);
                displayedCount++;
            }
        }
    },

    displayResult: function() {
        var resultList = document.getElementById(ResultListId);
        resultList.innerHTML = "";
        while (resultlist.hasChildNodes()) {
            resultList.removeChild(resultList.lastChild);
        }        

        var inputField = document.getElementById(ReceiverId);
        var filter = inputField.value.toLowerCase();
        var resultCount = this.wordList.length;        

        for (index = 0; index < resultCount; index++) {
            var word = this.wordList[index];

            if (word.name.indexOf(filter) !== 0) {
                // got filtered out, do not display it
                continue;
            } else {
                var titleItem = document.createElement("dt");
                var descriptionItem = document.createElement("dd");

                titleItem.innerHTML = word.name;
                descriptionItem.innerHTML = word.description;

                resultList.appendChild(titleItem);
                resultList.appendChild(descriptionItem);
            }
        }
    }

};

function processResponse(response) {
    queriedData.wordList = JSON.parse(response);

    queriedData.displaySuggest();
    queriedData.displayResult();
}

function sendRequest(queryValue) {
    var xhr = createXHR();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
                processResponse(xhr.responseText);
            } else {
                // TODO: handle AJAX failure
            }
        }
    }

    var query = queryValue.charAt(0).toLowerCase();

    requestHandler = appendURLParam(requestHandler, QueryName, query);

    xhr.open("get", requestHandler, true);
    xhr.send(null);
}

function flushDisplay() {
    var suggestList = document.getElementById(SuggestListId);
    var resultList = document.getElementById(ResultListId);    
    
    suggestList.innerHTML = "";
    resultList.innerHTML = "";

    // handle situations when setting innerHTML to empty string fails
    while (suggestlist.hasChildNodes()) {
        suggestList.removeChild(suggestList.lastChild);
    }
    while (resultlist.hasChildNodes()) {
        resultList.removeChild(resultList.lastChild);
    }    
}

function processInput(oldValue, newValue) {

    if (oldValue.length == 0 && newValue.length > 0) {
        sendRequest(newValue);
    } else if (newValue.length == 0) {
        flushDisplay();
    } else {
        queriedData.displaySuggest();
        queriedData.displayResult();
    }
}

var queryProcessor = function() {

    var currValue = this.value;

    if (typeof queryProcessor.lastValue == "undefined") {
        if (currValue.length > 0) {
            sendRequest(currValue);
        } else {
            // do nothing
        }
    } else if (queryProcessor.lastValue == currValue) {
        // content not changed, do nothing?
    } else {
        processInput(queryProcessor.lastValue, currValue);
    }

    queryProcessor.lastValue = this.value;
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
    var inputField = document.getElementById(ReceiverId);
    EventManager.addHandler(inputField, "keyup", queryProcessor);
}