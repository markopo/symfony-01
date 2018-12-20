"use strict";
exports.__esModule = true;
// NOT RECOMMENDED! use ES6 modules instead. https://stackoverflow.com/questions/37565709/how-to-use-namespaces-with-import-in-typescript
var Logger;
(function (Logger) {
    function log(parameters) {
        var msg = parameters.msg;
        console.log(msg);
    }
    Logger.log = log;
    function error(parameters) {
        var msg = parameters.msg;
        console.error(msg);
    }
    Logger.error = error;
})(Logger = exports.Logger || (exports.Logger = {}));
