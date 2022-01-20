function HTTPGet(theUrl, callback) {
    const req = new XMLHttpRequest();
    req.responseType = 'json';
    req.open('GET', theUrl);
    req.onload = () => {
        if (req.status == 200)
            callback(req.response);
        else
            callback(null);
    };

    req.send();
}

function FetchMetaData(api, ipfs) {
    for (var i = 0; i < ipfs.length; i++) {
        console.log(api + "/ipfs/" + ipfs[i])
        HTTPGet(api + "/ipfs/" + ipfs[i], (data) => {
            $('#holders-' + data.cid).detach();
            var img = document.getElementById("asset-img");
            if (data) {
                if (data.ipfs.data.urls[0]) {
                    if (data.ipfs.data.urls[0].mimeType.startsWith("image/")) {
                        if (data.ipfs.data.urls[0].url.startsWith("ipfs://"))
                            img.src = "https://ipfs.io/ipfs/" + data.ipfs.data.urls[0].url.substring(7);
                        else
                            img.src = data.ipfs.data.urls[0].url
                    }
                }
                $("#asset-meta").append(`<div class="col-md-10"><span class="text-break">IPFS: <a class="link" href="https://ipfs.io/ipfs/${data.cid}">${data.cid}</a></span><pre class="code">${JSON.stringify(data.ipfs, undefined, 2)}</pre></div>`);
            }
        });
    }
}

function FetchHolders(api, assetID) {
    HTTPGet(api + "/holders/" + assetID, (holders) => {
        $('#holders-loading').detach();
        if (holders) {
            if (holders.holders) {
                for (var i = 0; i < holders.holders.length; i++) {
                    $("#asset-holders").append(`<tr><th scope="row"><a class="link" href="/address/${holders.holders[i].address}">${holders.holders[i].address}</a></th><td>${holders.holders[i].amount}</td></tr>`)
                }

                var hold = document.getElementById("asset-n-holders");
                hold.innerHTML = holders.holders.length;

                var creator = document.getElementById("asset-creator");
                creator.innerHTML = `<a class="link" href="/address/${holders.issuer}">${holders.issuer}</a>`;

                var supply = document.getElementById("asset-supply");
                supply.innerHTML = holders.supply;

                var current = document.getElementById("asset-current");
                current.innerHTML = holders.current;
            }
        }
    });
}