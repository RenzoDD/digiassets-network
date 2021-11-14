/* 
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * JavaScript File
 */

function search() {
    var search = document.getElementById("search");
    if (search.value.startsWith("L") || search.value.startsWith("U"))
        location = "/asset/" + search.value;
    else if (search.value.startsWith("D") || search.value.startsWith("S") || search.value.startsWith("dgb1"))
        location = "/address/" + search.value;
}