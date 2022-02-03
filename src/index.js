const express = require('express');
const app = express();

const Sync = require('./util/Sync');

app.use(express.static(__dirname + '/assets'));
app.use(express.urlencoded());
app.use(express.json());

app.set('view engine', 'ejs');
app.set('views', __dirname + '/views')

app.use('/', require('./app/website'));

app.get('*', function(req, res){ res.redirect("/") });

app.listen(3000, () => {
    console.log('http://localhost:3000');
    Sync();
    setInterval(Sync, 2 * 60 * 1000);
});