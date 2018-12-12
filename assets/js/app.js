

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
// loads the jquery package from node_modules

var $ = require('jquery');

 // import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
var greet = require('./greet');

 $(document).ready(function() {
        $('body').prepend('<h1>'+greet('MARKO POKKO')+'</h1>');
 });