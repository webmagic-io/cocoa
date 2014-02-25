function moveCaretToEnd(el) {
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
}

var search = function() {
	q = document.getElementById("google_q");
	if (q.value != "") {

		window.open('http://www.google.com/search?q=site:ourcoders.com%20' + q.value, "_blank");
        $.post('/log/sitesearch/',{keyword:q.value},function(data){},"json");
		return false;
	} else {
		return false;
	}
}



