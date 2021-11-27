/*
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Empty Shell
*/

window.onload = init();
function init()
{
    document.cookie = "username=John Doe; expires=Thu, 18 Dec 2013 12:00:00 UTC";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++)
    {
        console.log(ca[i]);
    }
    console.log(ca);
}

function chg()
{
    document.cookie = "username=John Doe; expires=Thu, 18 Dec 2013 12:00:00 UTC";
    let decodedCookie = decodeURIComponent(document.cookie);
    
    
    console.log("cookie: ", decodedCookie);
}