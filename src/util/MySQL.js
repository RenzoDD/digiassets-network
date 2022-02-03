const mysql = require('mysql');

class MySQL {
    static connection = 0;
    
    static Connect() {
        MySQL.connection = mysql.createConnection({
            host: process.env.DB_HOST || 'localhost',
            database: process.env.DB_NAME || 'digiassets',
            user: process.env.DB_USER || 'root',
            password: process.env.DB_PASS || ''
        });
    }
    static Disconnect() {
        MySQL.connection.end();
        MySQL.connection = 0;
    }

    static Query(query, values) {
        return new Promise((resolve, reject) => {
            if (typeof MySQL.connection == 'number') {
                resolve([]);
                return;
            }

            if (!values)
                MySQL.connection.query(query, (error, result) => {
                    if (!error)
                        resolve(result[0]);
                    else
                        resolve([]);
                });
            else
                MySQL.connection.query(query, values, (error, result) => {
                    if (!error)
                        resolve(result[0]);
                    else
                        resolve([]);
                });
        });   
    }
}

module.exports = MySQL;