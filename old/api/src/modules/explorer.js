const Util = require('../utilities/util');
const MySQL = require('../utilities/mysql');
const Sync = require('../utilities/sync');
const lookup = require('digiasset-lookup');
lookup.initS3({
    accessKeyId:    process.env.AWS_USER,
    secretAccessKey:process.env.AWS_PASS
});

/**** Router ****/
const express = require('express');
const router = express.Router();

/**** Middleware ****/
router.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Content-Type', 'application/json');
    next();
});

/**** Routes ****/
router.get('/ipfs/:cid', async (req, res) => {
    Util.log("IPFS Fetching: " + "https://ipfs.io/ipfs/" + req.params.cid);
    var data = await Util.GetData("https://ipfs.io/ipfs/" + req.params.cid);
    
    if (data) {
        Util.log("IPFS Fetched: " + req.params.cid);
        Util.log({ cid: req.params.cid, ipfs: data })

        var result1 = await MySQL.Query("CALL IpfsCids_Read_CID(?)", [req.params.cid]);
        

        var result2 = await MySQL.Query("CALL IpfsCids_Update_Data(?,?)", [result1[0].IpfsCidID, JSON.stringify(data, undefined, 2)]);
        var result3 = await MySQL.Query("CALL DigiAssets_Update_Data(?,?,?,?)", [result1[0].DigiAssetID, data.data.assetName, data.data.issuer, data.data.description])

        res.send({ cid: req.params.cid, ipfs: data });
    } else {
        Util.log("IPFS Not Fetched: " + req.params.cid);
        res.send({ error: "server error" });
    }
});
router.get('/assets/:address', async (req, res) => {
    Util.log("Address Fetching: " + req.params.address);
    var data = await lookup.getAddress(req.params.address);
    
    if (data) {
        var assets = [];
        var quantity = 0;

        var assetsId = Object.keys(data.assets);
        for (var i = 0; i < assetsId.length; i++) {
            quantity++;
            assets.push({
                id: assetsId[i],
                amount: data.assets[assetsId[i]]
            });
        }

        Util.log("Address Fetched: " + req.params.address);
        Util.log({ address: req.params.address, quantity, assets })
        res.send({ address: req.params.address, quantity, assets });
    } else {
        Util.log("Address Not Fetched: " + req.params.address);
        res.send({ error: "server error" });
    }
});
router.get('/holders/:asset', async (req, res) => {
    Util.log("Holders Fetching: " + req.params.asset);
    var data = await lookup.getAsset(req.params.asset);
    if (data) {
        var issuer = data.issuer;
        var supply = data.supply.initial;
        var current = data.supply.current;
        
        var addresses = Object.keys(data.holders);
        var quantity = addresses.length;
        var holders = [];

        addresses.forEach(addr => {
            holders.push({address: addr, amount: data.holders[addr]});
        });
               
        Util.log("Holders Fetched: " + req.params.asset);
        Util.log({ asset: req.params.asset, quantity, holders, issuer, supply, current })
        res.send({ asset: req.params.asset, quantity, holders, issuer, supply, current });
    } else {
        Util.log("Holders Not Fetched: " + req.params.asset);
        res.send({ error: "server error" });
    }
});
router.get("/sync", async (req, res) => {
    Sync();
});

module.exports = router;