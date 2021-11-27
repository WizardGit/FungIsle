/*
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Empty Shell
*/

window.onload = init();
function init()
{
    document.cookie = "username=John Doe";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    let c = ca[ca.length-1];
    console.log(c);
}

function chg()
{
    document.cookie = "username=John Doe";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    let c = ca[ca.length-1];
    console.log(c);
}