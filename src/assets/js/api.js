/* 
 * Copyright 2021 (c) Renzo Diaz
 * Connection to NodeJS API
 */

function GetData(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';

    xhr.onload = function() {
        var status = xhr.status;
        if (status === 200) {
            callback(xhr.response, xhr.status);

        } else {
            callback(null, xhr.status);
        }
    };

    xhr.send();
};

function GetBalance() {
    if (typeof addresses === 'undefined')
        return;

    addresses.forEach(address => {
        var confirmed = document.getElementById(address.addr + "-confirmed");
        var unconfirmed = document.getElementById(address.addr + "-unconfirmed");

        if (confirmed !== null && unconfirmed !== null) {
            console.log(url + '/address/' + address.addr);
            GetData(url + '/address/' + address.addr, function(data, status) {
                if (data !== null) {
                    console.log(data);
                    address.confirmed = parseFloat(data.valueBalance);
                    address.unconfirmed = parseFloat(data.unconfirmedValue);

                    confirmed.style.width = (parseFloat(address.confirmed) / address.max * 100) + '%';
                    unconfirmed.style.width = (parseFloat(address.unconfirmed) / address.max * 100) + '%';

                    confirmed.innerHTML = address.confirmed + ' ' + address.unit;
                    unconfirmed.innerHTML = address.unconfirmed + ' ' + address.unit;
                }
            });
        }
    });
}

GetBalance();
setInterval(GetBalance, 1000);