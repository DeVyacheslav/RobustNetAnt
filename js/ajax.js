function showError(str,input) {
    var elInput = document.getElementById(input);
	var err = document.getElementById("error-"+input);
	var errIcon = document.getElementById("error-"+input+"-icon");
	var parInput = elInput.parentElement;
    
    if (str.length == 0) {	
        err.innerHTML = "* Required";
        
        errorClass(parInput, errIcon);
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(!this.responseText){
                	parInput.className ="form-group has-success  has-feedback";
        			errIcon.className ="glyphicon glyphicon-ok form-control-feedback";
        			err.innerHTML = this.responseText;
                }else{
                	err.innerHTML = this.responseText;
                	errorClass(parInput, errIcon);
                }
            }
        };
        xmlhttp.open("GET", "Class/AjaxCheck.php?q=" + str+"&i="+input, true);
        xmlhttp.send();
    }
}

function errorClass(p,e){
	p.className ="form-group has-error has-feedback";
    e.className ="glyphicon glyphicon-remove form-control-feedback";
}
