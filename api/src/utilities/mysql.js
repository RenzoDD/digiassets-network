const mysql = require('mysql');
const sync_mysql = require('sync-mysql');
const Util = require('./util');

class MySQL {
    static callback = [];
    static sync = [];

    static ConnectCallback() {
        MySQL.callback = mysql.createConnection({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
    }
    static DisconnectCallback() {
        MySQL.callback.end();
        MySQL.callback = [];
    }
    static ConnectSync() {
        MySQL.sync = new sync_mysql({
            host: process.env.DB_HOST,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASS
        });
    }

    static CallbackQuery(sql, callback) {
        MySQL.callback.query(sql, (error, result) => {
            if (!error) {
                callback(result[0]);
            } else {
                callback([]);
                Util.log("======================DATABASE CallbackQuery ERROR======================");
                Util.log("Query: " + sql);
                Util.log("==========================================================");
            }
        });
    }

    static CallbackQuerySave(sql, parameters, callback) {
        MySQL.callback.query(sql, parameters, (error, result) => {
            if (!error) {
                callback(result[0]);
            } else {
                callback([]);
                Util.log("======================DATABASE CallbackQuerySave ERROR======================");
                Util.log("Query: " + sql);
                Util.log("==========================================================");
            }
        });
    }

    static SyncQuery(sql, values = []) {
        try {
            return MySQL.sync.query(sql, values)[0];
        } catch (e) {
            Util.log(e);
            return [];
        }
    }
}

module.exports = MySQL