const $ = require('jquery');
import { UtilFunctions }  from './Utilities';


$(document).ready(() => {
    console.log("TS is awesome!");

    const msg = UtilFunctions.hello("Marko");
    console.log(msg);
});