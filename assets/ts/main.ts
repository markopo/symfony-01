const $ = require('jquery');

function hello(name: string) {
    return "Hello " + name + "!";
}

$(document).ready(() => {
    console.log("TS is awesome!");

     const msg = hello("Marko");
     console.log(msg);
});