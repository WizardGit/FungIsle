window.addEventListener('beforeunload', function (e) {
    e.preventDefault();
    alert("you idiot!");
    e.returnValue = '';
});