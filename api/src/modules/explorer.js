const Util = require('../utilities/util');

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
router.get('/ipfs/:cid', (req, res) => {
    Util.HttpsGet("https://ipfs.io/ipfs/" + req.params.cid, (data) => {
        if (data) {
            Util.log("IPFS Fetched: " + req.params.cid);
            res.send(data);
        } else {
            Util.log("IPFS Not Fetched: " + req.params.cid);
            res.send({ error: "server error" });
        }

    });

});
router.get('/assets/:address', (req, res) => {
    Util.HttpsGet("https://api.digiassets.net:443/v3/addressinfo/" + req.params.address, (data) => {
        if (data) {
            var assets = [];
            var quantity = 0;
            for (var i = 0; i < data.utxos.length; i++) {
                for (var j = 0; j < data.utxos[i].assets.length; j++) {
                    quantity++;
                    assets.push({
                        id: data.utxos[i].assets[j].assetId,
                        amount: data.utxos[i].assets[j].amount
                    });
                }
            }
            Util.log("Holders Fetched: " + req.params.address);
            res.send({ quantity, assets });
        } else {
            Util.log("Holders Not Fetched: " + req.params.address);
            res.send({ error: "server error" });
        }
    });
});
router.get('/holders/:asset', (req, res) => {
    Util.HttpsGet("https://api.digiassets.net:443/v3/stakeholders/" + req.params.asset, (data) => {
        if (data) {
            var holders = [];
            var quantity = 0;
            for (var i = 0; i < data.holders.length; i++) {
                quantity++;
                holders.push({
                    address: data.holders[i].address,
                    amount: data.holders[i].amount
                });

            }
            Util.log("Holders Fetched: " + req.params.asset);
            res.send({ quantity, holders });
        } else {
            Util.log("Holders Not Fetched: " + req.params.asset);
            res.send({ error: "server error" });
        }
    });
});


module.exports = router;