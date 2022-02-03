const MySQL = require("./mysql");
const Util = require("./util");

module.exports = async () => {
    MySQL.Connect();

    var DigiAssets = await MySQL.Query("CALL DigiAssets_Read_Last ( )");

    var height = 1;
    if (DigiAssets.length > 0)
        height = DigiAssets[0].Height;

    var data = await Util.GetData("https://ipfs.digiassetx.com/" + height);
    var keys = Object.keys(data);

    var i = 0
    if (DigiAssets.length > 0) {
        for (var i = 0; i < keys.length; i++) {
            if (keys[i] == DigiAssets[0].AssetID) {
                i++;
                break;
            }
        }
    }

    for (var k = i; k < keys.length; k++) {
        var DA = await MySQL.Query("CALL DigiAssets_Create (?,?)", [keys[k], data[keys[k]].height]);
        Util.log("Added: " + DA[0].DigiAssetID, true)
        if (DA.length > 0) {
            for (var j = 0; j < data[DA[0].AssetID].cids.length; j++) {
                MySQL.Query("CALL IpfsCids_Create (?,?)", [DA[0].DigiAssetID, data[DA[0].AssetID].cids[j]]);
            }
        }
    }
}