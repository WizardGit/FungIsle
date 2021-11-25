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
    alert("help)");
    document.getElementById("heroBox").value = document.getElementById("heroes").value;
}
function changeVillage()
{
    document.getElementById("villageBox").value = document.getElementById("villages").value;
}