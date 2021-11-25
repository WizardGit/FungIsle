window.onload = init();
function init()
{
    
}

function attack()
{
    window.location.href="./phps/attack.php";
}
function reset()
{
    window.location.href="./phps/resetDB.php";
}
function changeHero()
{
    var h = document.getElementById("heroes");
    var hb = document.getElementById("heroBox");
    hb.value = h.value;
}
function changeVillage()
{
    var v = document.getElementById("villages");
    var vb = document.getElementById("villageBox");
    vb.value = v.value;
}