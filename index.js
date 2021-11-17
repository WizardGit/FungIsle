window.addEventListener('beforeunload', function (e) {
    alert("you idiot!");
    e.preventDefault();
    
    e.returnValue = '';
});