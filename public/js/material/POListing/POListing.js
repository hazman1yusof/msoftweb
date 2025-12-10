
$.validate({
	modules: 'sanitize',
	language: {
		requiredFields: ''
	},
});

var errorField = [];
conf = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			console.log(errorField);
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

    $("#genreport input[name='datefr']").change(function(){
        $("#genreportpdf input[name='datefr']").val($(this).val());
    });
    $("#genreport input[name='dateto']").change(function(){
        $("#genreportpdf input[name='dateto']").val($(this).val());
    });
    $("#genreport input[name='Status']").change(function(){
        $("#genreportpdf input[name='Status']").val($(this).val());
    });
	
	$('#pdfgen').click(function(){
		window.open('./POListing/showpdf?&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&Status='+$("#Status").val(),  '_blank'); 
	});

	$('#excelgen').click(function(){
		window.location='./POListing/showExcel?&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&Status='+$("#Status").val()+'&deptcode='+$("#deptcode").val();
	});
});
