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

function FetchAssets(api, address) {
    var assets = HTTPGet(api + "/assets/" + address);
    $('#assets-loading').detach();
    if (assets) {
        for (var i = 0; i < assets.assets.length; i++) {
            $("#asset-list").append(`<tr><th scope="row"><a class="link" href="/asset/${assets.assets[i].id}">${assets.assets[i].id}</a></th><td>${assets.assets[i].amount}</td></tr>`)
        }

        var number = document.getElementById("asset-number");
        number.innerHTML = assets.quantity;
    }
}