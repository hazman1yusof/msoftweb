
$(document).ready(function() {
	Object.keys(patmast).forEach(function(key) {
		$('input[name="'+key+'"]').val(patmast[key]);

	});

	$("#btn_register_close").click(function(){
		$('#frm_patient_info').hide();
	});

});