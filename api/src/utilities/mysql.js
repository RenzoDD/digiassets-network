const mysql = require('mysql');
const sync_mysql = require('sync-mysql');

class MySQL {

    static CallbackQuery(sql, callback) {
        var conn = mysql.createConnection({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
        conn.query(sql, (error, result) => {
            if (!error) {
                callback(result);
            } else {
                callback(null);
                console.error("======================DATABASE CallbackQuery ERROR======================");
                console.error("Query: " + sql);
                console.error(error);
                console.error("==========================================================");
            }
            conn.end();
        });
    }

    static CallbackQuerySave(sql, parameters, callback) {
        var conn = mysql.createConnection({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
        conn.query(sql, parameters, (error, result) => {
            if (!error) {
                callback(result[0]);
            } else {
                callback(null);
                console.error("======================DATABASE CallbackQuerySave ERROR======================");
                console.error("Query: " + sql);
                console.error(error);
                console.error("==========================================================");
            }
            conn.end();
        });
    }

    static SyncQuery(sql, values = []) {
        var conn = new sync_mysql({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
        return conn.query(sql, values);
    }
}

module.exports = MySQL