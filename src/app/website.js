const express = require('express');
const router = express.Router();

const lookup = require('digiasset-lookup');
lookup.initS3({
    accessKeyId: process.env.AWS_USER,
    secretAccessKey: process.env.AWS_PASS
});

const MySQL = require('../util/MySQL');
const Util = require('../util/Util');
const HTTPS = require('../util/HTTPS');

const Sync = require('../util/Sync');

router.get('/sync', async (req, res) => {
    var total = await Sync();
    res.send("Added " + total + " assets");
});

router.get('/about', async (req, res) => {
    var info = {
        title: "DigiAsset Explorer - About",
        description: "Lookup for Assets in the DigiByte blockchain",
        path: "/about"
    };
    var address = 'DKmrANhi7eNcE1fRAbaXaPy91A66fBVios';

    res.render('about', { info, address });
});

router.get('/assets/:page?', async (req, res) => {
    var page = req.params.page ? parseInt(req.params.page) : 1;
    var quantity = 10;

    MySQL.Connect();
    var assets = await MySQL.Query('CALL DigiAssets_Read_Page(?, ?)', [page, quantity]);
    var total = (await MySQL.Query('CALL DigiAssets_Read_Quantity()'))[0].Quantity;
    MySQL.Disconnect();

    var pages = Math.ceil(total / quantity);


    var pagesToShow = 8;
    var pageStart = page - Math.floor(pagesToShow / 2);
    pageStart = (pageStart <= 0) ? 1 : pageStart;

    var pageEnd = pageStart + (pagesToShow - 1);
    pageEnd = (pageEnd > pages) ? pages : pageEnd;
    pageStart = pageEnd - (pagesToShow - 1);

    pageStart = (pageStart <= 0) ? 1 : pageStart;

    var info = {
        title: "DigiAsset Explorer - Asset Directory",
        description: "All the DigiAssets ever minted",
        path: "/assets"
    };
    res.render('assets', { info, assets, page, quantity, pages, pageStart, pageEnd })
});

router.get('/asset/:AssetID', async (req, res) => {
    try {
        var DigiAsset = await lookup.getAsset(req.params.AssetID);
    } catch {
        return res.redirect("/no-asset")
    }
    // Enchant divisibility
    if (DigiAsset.divisibility != 0) {
        DigiAsset.supply.initial = [DigiAsset.supply.initial.slice(0, (DigiAsset.supply.initial.length - DigiAsset.divisibility)), ".", DigiAsset.supply.initial.slice((DigiAsset.supply.initial.length - DigiAsset.divisibility))].join('');
        DigiAsset.supply.initial = DigiAsset.supply.initial[0] == '.' ? '0' + DigiAsset.supply.initial : DigiAsset.supply.initial;

        DigiAsset.supply.current = [DigiAsset.supply.current.slice(0, (DigiAsset.supply.current.length - DigiAsset.divisibility)), ".", DigiAsset.supply.current.slice((DigiAsset.supply.current.length - DigiAsset.divisibility))].join('');
        DigiAsset.supply.current = DigiAsset.supply.current[0] == '.' ? '0' + DigiAsset.supply.current : DigiAsset.supply.current;
    }

    // Get metadata
    var fetchData = await Promise.all([HTTPS.Get("https://cloudflare-ipfs.com/ipfs/" + DigiAsset.metadata[0].cid), HTTPS.Get("https://auction.digiassetx.com/sales.json")] );
    var MetaData = fetchData[0] ? fetchData[0].data ? fetchData[0].data : {} : {};
    var auctions = fetchData[1] ? fetchData[1] : [];

    console.log(fetchData[0])

    var auction = auctions.filter(x => x.assets[DigiAsset.assetId]);
    auction = auction.length > 0 ? auction[0] : null;

    // Get icon
    var image = null, interactive = null, thumbnail = null;
    if (MetaData.urls) {
        var image = MetaData.urls.filter(x => x.name == 'icon');
        if (image[0].mimeType.indexOf("gif") != -1 || image[0].mimeType.indexOf("svg") != -1)
            thumbnail = "https://digiassets.network/img/thumbnail.png"
        
        image = image.length != 0 ? image[0].url.replace("ipfs://", "https://cloudflare-ipfs.com/ipfs/") : "https://digiassets.network/img/thumbnail.png";

        var interactive = MetaData.urls.filter(x => x.name == 'description');
        interactive = interactive.length != 0 ? interactive[0].url.replace("ipfs://", "https://cloudflare-ipfs.com/ipfs/") : null;
    }

    var info = {
        title: "DigiAsset - " + MetaData.assetName,
        description: (auction) ? "This asset is available in auction" :  MetaData.description,
        image,
        thumbnail,
        path: "/asset"
    };

    // Url
    for (var i = 0; i < MetaData.urls.length; i++) {
        if (MetaData.urls[i].mimeType) {
            if (MetaData.urls[i].mimeType.startsWith("video"))
                MetaData.urls[i].url = MetaData.urls[i].url.replace("ipfs://", "https://ipfs.io/ipfs/");
            else
                MetaData.urls[i].url = MetaData.urls[i].url.replace("ipfs://", "https://cloudflare-ipfs.com/ipfs/");
        } else {
            MetaData.urls[i].mimeType = "file/bytes";
        }
        if (MetaData.urls[i].mimeType.startsWith("image"))
            MetaData.urls[i].icon = "file-earmark-image";
        else if (MetaData.urls[i].mimeType.startsWith("video"))
            MetaData.urls[i].icon = "file-earmark-play";
        else if (MetaData.urls[i].mimeType == "text/html")
            MetaData.urls[i].icon = "file-earmark-code";
        else if (MetaData.urls[i].mimeType == "text/javascript")
            MetaData.urls[i].icon = "filetype-js";
        else if (MetaData.urls[i].mimeType.startsWith("text"))
            MetaData.urls[i].icon = "file-earmark-text";
        else if (MetaData.urls[i].mimeType.startsWith("audio"))
            MetaData.urls[i].icon = "file-earmark-music";
        else
            MetaData.urls[i].icon = "file-earmark-binary";

    }

    var rules = DigiAsset.rules ? DigiAsset.rules.length > 0 ? DigiAsset.rules[0] : {} : {};

    // Get royalties
    var royalties = "No royalties"
    if (DigiAsset.rules) {
        if (DigiAsset.rules.length != 0) {
            var rules = DigiAsset.rules[0];
            if (rules.royalties) {
                var currency = (rules.currency) ? rules.currency.name : "DGB";
                var addresses = Object.keys(rules.royalties);
                var total = 0;
                addresses.forEach(addr => {
                    total += parseInt(rules.royalties[addr]);
                });
                var royalties = '00000000000000000000' + total.toString();
                royalties = [royalties.slice(0, (royalties.length - 8)), ".", royalties.slice((royalties.length - 8))].join('').replace(/^0+/, "").replace(/0+$/, "").replace(/\.+$/, "");
                royalties += " " + currency;

                royalties = royalties[0] == '.' ? '0' + royalties : royalties;
            }
        }
    }

    // Deflation
    if (rules.deflate) {
        var deflation = '00000000000000000000' + rules.deflate.toString();
        deflation = [deflation.slice(0, (deflation.length - DigiAsset.divisibility)), ".", deflation.slice((deflation.length - DigiAsset.divisibility))].join('').replace(/^0+/, "").replace(/0+$/, "").replace(/\.+$/, "");
        deflation = deflation[0] == '.' ? '0' + deflation : deflation;
        rules.deflate = deflation;
    }

    //Holders
    var holders = Object.keys(DigiAsset.holders);
    holders.forEach(holder => {
        var data = '00000000000000000000' + DigiAsset.holders[holder];
        data = [data.slice(0, (data.length - DigiAsset.divisibility)), ".", data.slice((data.length - DigiAsset.divisibility))].join('').replace(/^0+/, "").replace(/0+$/, "").replace(/\.+$/, "");

        DigiAsset.holders[holder] = data[0] == '.' ? '0' + data : data;
    });

    MetaData.description = MetaData.description.replace(/\r\n/g, "<br>")
                                                .replace(/\n/g, "<br>")
                                                .replace(/\t/g, "");

    MySQL.Connect();
    MySQL.Query('CALL DigiAssets_Update_Data(?, ?, ?, ?)', [DigiAsset.assetId, MetaData.assetName, MetaData.issuer || "Unknown", royalties]);
    MySQL.Disconnect();


    return res.render('asset', { info, DigiAsset, MetaData, royalties, holders, interactive, rules, auction });
});

router.get('/address/:Address', async (req, res) => {
    try {
        var Address = await lookup.getAddress(req.params.Address);
    } catch {
        return res.redirect("/no-address");
    }

    var assets = Object.keys(Address.assets);

    // Censor KYC
    if (Address.kyc) {
        if (Address.kyc.name) {
            var name = Address.kyc.name.split(" ");
            for (var i = 0; i < name.length; i++)
                name[i] = name[i].replace(/(?<!^).(?!$)/g, '*')

            Address.kyc.name = name.join(" ");
        }
    }

    var info = {
        title: "DigiAsset - " + Address.address,
        description: "DigiAsset address",
        path: "/address"
    };
    return res.render('address', { info, Address, assets });
});

router.get('/:info?', async (req, res) => {
    var info = {
        title: "DigiAsset Explorer",
        description: "Lookup for Assets in the DigiByte blockchain",
        path: "/home"
    };

    res.render('home', { info });
});
router.post('/', async (req, res) => {
    if (!req.body.search)
        return res.redirect('/error');

    var search = req.body.search.toString();
    if (search.length == 38 && (search[0] == 'L' || search[0] == 'U'))
        return res.redirect('/asset/' + search);

    if ((search.length == 34 && search[0] == 'D') || (search.length == 34 && search[0] == 'S') || (search.length == 43 && search.startsWith('dgb1')))
        return res.redirect('/address/' + search);

    return res.redirect('/not-found');
});


module.exports = router;