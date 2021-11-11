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

var assets = HTTPGet("https://api.digiassets.net:443/v3/addressinfo/" + address);
if (assets) {
    var count = 0;
    for (var i = 0; i < assets.utxos.length; i++) {
        for (var j = 0; j < assets.utxos[i].assets.length; j++) {
            count++;
            $("#asset-list").append(`<tr><th scope="row"><a class="link" href="/asset/${assets.utxos[i].assets[j].assetId}">${assets.utxos[i].assets[j].assetId}</a></th><td>${assets.utxos[i].assets[j].amount}</td></tr>`)
        }
    }

    var number = document.getElementById("asset-number");
    number.innerHTML = count;
}