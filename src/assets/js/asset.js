function HTTPGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", theUrl, false); // false for synchronous request
    xmlHttp.send(null);
    return JSON.parse(xmlHttp.responseText);
}

for (var i = 0; i < ipfs.length; i++) {
    var data = HTTPGet("https://ipfs.io/ipfs/" + ipfs[i]);
    var img = document.getElementById("asset-img");

    if (data.data.urls[0]) {
        if (data.data.urls[0].mimeType.startsWith("image/")) {
            img.src = data.data.urls[0].url
        }
    }

    $("#asset-meta").append(`<div class="col-md-10"><span class="text-break">IPFS: <a class="link" href="https://ipfs.io/ipfs/${ipfs[i]}">${ipfs[i]}</a></span><pre class="code">${JSON.stringify(data, undefined, 2)}</pre></div>`);
}

var holders = HTTPGet("https://api.digiassets.net:443/v3/stakeholders/" + assetID);
if (holders.holders) {
    for (var i = 0; i < holders.holders.length; i++) {
        $("#asset-holders").append(`<tr><th scope="row">${holders.holders[i].address}</th><td>${holders.holders[i].amount}</td></tr>`)
    }

    var hold = document.getElementById("asset-n-holders");
    hold.innerHTML = holders.holders.length;
}