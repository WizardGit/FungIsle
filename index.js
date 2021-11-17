/*window.onload=backup();
function backup()
{
    location.href = 'SQLDump.php';
}*/
window.addEventListener("beforeunload", function(e){
    location.href = 'SQLDump.php';
 }, false);