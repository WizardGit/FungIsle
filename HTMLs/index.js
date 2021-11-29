/*
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Empty Shell
*/

window.onload = init();
function init()
{
    
}

function chgV()
{
    var c = document.getElementById("villages");
    var d = document.getElementById("v");
    if(c.value=="HellCave")
        d.src="./Characters/Saladore.png";
    else
        d.src="./Characters/Saladorian.png"; 
}
function chgA()
{
    var c = document.getElementById("animals");
    var d = document.getElementById("a");
    if(c.value=="Fungivore")
        d.src="./Characters/Fungivore.png";
    else
        d.src="";
}
function chgH()
{
    var c = document.getElementById("heroes");
    var d = document.getElementById("h");
    if(c.value=="Mushronian")
        d.src="./Characters/Mushronian.png";
    else if (c.value=="Amanita")
        d.src="./Characters/Amanita.png";
    else
        d.src="";
}


