"use strict";
exports.__esModule = true;
var $ = require('jquery');
var Utilities_1 = require("./Utilities");
var logger_1 = require("./logger");
$(document).ready(function () {
    console.log("TS is awesome!");
    var msg = Utilities_1.UtilFunctions.hello("Marko");
    console.log(msg);
    logger_1.Logger.log({ msg: "Logging stuff!! " });
});
