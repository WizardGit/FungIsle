/*window.onload=backup();
function backup()
{
    location.href = 'SQLDump.php';
}*/
window.addEventListener("beforeunload", function(e){
    
    console.log("oka");
    this.alert("we go");
    location.href = 'SQLDump.php';
    this.alert("We done");
    console.log("oka");
 }, false);