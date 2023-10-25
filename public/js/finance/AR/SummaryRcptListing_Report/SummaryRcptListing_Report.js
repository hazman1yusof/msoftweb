$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    var status = [['DAILY','DETAIL']];
	if($("#Status").val() == 'DAILY'){
        $("#genreportpdf").attr('href','./SummaryRcptListing_Report/showpdf?');
	}

	$("#genreport input[name='datefr']").change(function(){
		$("#genreportpdf input[name='datefr']").val($(this).val());
	});
	$("#genreport input[name='dateto']").change(function(){
		$("#genreportpdf input[name='dateto']").val($(this).val());
	});
   
});