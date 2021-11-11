function HTTPGet(theUrl) {
    var req = new XMLHttpRequest();
    req.open('GET', theUrl, false);
    req.send(null);
    if (req.status == 200)
        if (req.responseText != "")
            return JSON.parse(req.responseText);
        else
            return null;
    return null;
}

for (var i = 0; i < ipfs.length; i++) {
    var data = HTTPGet("https://ipfs.io/ipfs/" + ipfs[i]);
    var img = document.getElementById("asset-img");

    if (data) {
        if (data.data.urls[0]) {
            if (data.data.urls[0].mimeType.startsWith("image/")) {
                if (data.data.urls[0].url.startsWith("ipfs://"))
                    img.src = "https://ipfs.io/ipfs/" + data.data.urls[0].url.substring(7);
                else
                    img.src = data.data.urls[0].url
            }
        }
    }

    $("#asset-meta").append(`<div class="col-md-10"><span class="text-break">IPFS: <a class="link" href="https://ipfs.io/ipfs/${ipfs[i]}">${ipfs[i]}</a></span><pre class="code">${JSON.stringify(data, undefined, 2)}</pre></div>`);
}

var holders = HTTPGet("https://api.digiassets.net:443/v3/stakeholders/" + assetID);

if (holders) {
    if (holders.holders) {
        for (var i = 0; i < holders.holders.length; i++) {
            $("#asset-holders").append(`<tr><th scope="row"><a class="link" href="/address/${holders.holders[i].address}">${holders.holders[i].address}</a></th><td>${holders.holders[i].amount}</td></tr>`)
        }

        var hold = document.getElementById("asset-n-holders");
        hold.innerHTML = holders.holders.length;
    }
}