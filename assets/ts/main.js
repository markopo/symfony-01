var $ = require('jquery');
function hello(name) {
    return "Hello " + name + "!";
}
$(document).ready(function () {
    console.log("TS is awesome!");
    var msg = hello("Marko");
    console.log(msg);
});
