const https = require('https');

class Util {
    static HttpsGet(url, callback) {
        https.get(url, (resp) => {
            let data = '';
            resp.on('data', (chunk) => { data += chunk; });
            resp.on('end', () => {
                var obj = null;
                try {
                    obj = JSON.parse(data);
                } catch (error) {
                    obj = null;
                } finally {
                    callback(obj);
                }
            });
        }).on('error', (error) => {
            callback(null);
        });
    }
    static HttpsPost(server, path, data, callback) {

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
                var obj = null;
                try {
                    obj = JSON.parse(data);
                } catch (error) {
                    obj = null;
                } finally {
                    callback(obj);
                }
            });
        }).on('error', (error) => {
            callback(null);
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