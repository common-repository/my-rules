/*Javascript*/
close = document.getElementById("closeThisBar");
close.addEventListener('click', function() {
	note = document.getElementById("wpNotificationBar");
	note.style.display = 'none';
}, false);
