const https = require('https');

class HTTPS {
    static Get(url) {
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
}

module.exports = HTTPS;