const $ = require('jquery');

import { UtilFunctions }  from './Utilities';
import { Logger } from './logger';



$(document).ready(() => {
    console.log("TS is awesome!");

    const msg = UtilFunctions.hello("Marko");
    console.log(msg);

    Logger.log({ msg: "Logging stuff!! " });
});