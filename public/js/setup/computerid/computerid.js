
var computerid = null;
$(document).ready(function () {
	initcompid();
});

function initcompid(){
	var computerid = localStorage.getItem('computerid');

	if(computerid){
	    $('#computerid').val(computerid);
	    success_fld('#computerid');
	}
}

function setcompid(){
    if($('#computerid').val() !== ''){
        localStorage.setItem('computerid', $('#computerid').val());
        success_fld('#computerid');
    }else{
    	error_fld('#computerid');
        alert('Computerid field cant be blank!');
    }
}

function error_fld(fld){
	$(fld).addClass('error_').removeClass('success_');
}

function success_fld(fld){
	$(fld).removeClass('error_').addClass('success_');
}