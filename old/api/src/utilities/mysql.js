const mysql = require('mysql');
const Util = require('./util');

class MySQL {
    static callback = [];
    static Connect() {
        MySQL.callback = mysql.createConnection({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
    }
    static Disconnect() {
        MySQL.callback.end();
        MySQL.callback = [];
    }

    static Query(sql, parameters) {
        return new Promise((resolve, reject) => {
            if (!parameters)
                MySQL.callback.query(sql, (error, result) => {
                    if (!error)
                        resolve(result[0]);
                    else
                    {
                        resolve({error})
                        Util.log("======================DATABASE CallbackQuery ERROR======================");
                        Util.log("Query: " + sql);
                        Util.log("========================================================================");
                    }
                });
            else
                MySQL.callback.query(sql, parameters, (error, result) => {
                    if (!error)
                        resolve(result[0]);
                    else {
                        resolve({error})
                        Util.log("======================DATABASE CallbackQuerySave ERROR======================");
                        Util.log("Query: " + sql);
                        Util.log("==========================================================");
                    }
                });
        });   
    }
}

module.exports = MySQL