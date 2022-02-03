const MySQL = require('./MySQL');
const HTTPS = require('./HTTPS');
const Util = require('./Util');

async function Sync() {
    MySQL.Connect();

    var height = await MySQL.Query('CALL DigiAssets_Read_Last ()');
    if (height.length != 0)
        height = height[0].Height;
    else
        height = 1;

    var totalAdded = 0;
    do {
        var data = await HTTPS.Get('https://ipfs.digiassetx.com/' + height);

        var assets = Object.keys(data);
        
        var added = 0;
        for (var i = 0; i < assets.length; i++) {
            if (data[assets[i]].height >= height) {
                var sql = await MySQL.Query('CALL DigiAssets_Create (?, ?)', [assets[i], data[assets[i]].height]);
                if (sql.length != 0) {
                    Util.log("Added: " + sql[0].AssetID)
                    added++;
                }
            }
        }
        height = data[assets[assets.length - 1]].height;
        totalAdded += added;
    } while (added != 0)

    MySQL.Disconnect();

    return totalAdded;
}

module.exports = Sync;