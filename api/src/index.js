const Util = require('./utilities/util');

/**** Server ****/
const express = require('express');
const app = express();

/**** Middleware ****/
//app.use(express.json());
//app.use(express.urlencoded({ extended: true }));

/**** Sections ****/
app.use('/', require('./modules/explorer'));

/**** Start ****/
app.listen(process.env.PORT, () => {
    console.log("Server started on : " + Util.GetDate());
    console.log("Server port:        " + process.env.PORT);
    console.log("Environment:        " + process.env.NODE_ENV);
    console.log("====================================================================");
});