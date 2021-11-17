window.onload=backup();
function backup()
{
    location.href = 'SQLDump.php';
}
window.onbeforeunload = closingCode();
function closingCode(){
    location.href = 'SQLRecover.php';
}