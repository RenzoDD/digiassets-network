const https = require('https');

class Util {
    static GetData (url) {
        return new Promise((resolve, reject) => {
            https.get(url, (resp) => {
                let data = '';
                resp.on('data', (chunk) => { data += chunk; });
                resp.on('end', () => {
                    try {
                        var obj = JSON.parse(data);
                        resolve(obj);
                    } catch (error) {
                        resolve({ error });
                    }
                });
            }).on('error', (error) => {
                resolve({ error })
            });
        });

       
    }
    static PostData(server, path, data) {
        return new Promise((resolve, reject) => {
            data = JSON.stringify(data);

            const options = {
                hostname: server,
                port: 443,
                path: path,
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Content-Length': data.length
                }
            }

            const req = https.request(options, res => {
                let data = '';
                resp.on('data', (chunk) => { data += chunk; });
                resp.on('end', () => {
                    try {
                        var obj = JSON.parse(data);
                        resolve(obj);
                    } catch (error) {
                        resolve({ error });
                    }
                });
            }).on('error', (error) => {
                resolve({ error })
            });
        });
    }
    static GetDate(date = new Date) {
        var dt = new Date(date);
        return dt.getFullYear().toString().padStart(4, '0') + "/" +
            (dt.getMonth() + 1).toString().padStart(2, '0') + "/" +
            dt.getDate().toString().padStart(2, '0') + " " +
            dt.getHours().toString().padStart(2, '0') + ":" +
            dt.getMinutes().toString().padStart(2, '0') + ":" +
            dt.getSeconds().toString().padStart(2, '0');
    }
    static log(data, strict = false) {
        if (process.env.NODE_ENV != "production" || strict)
            console.log(Util.GetDate() + ":", data);
    }
}

module.exports = Util;