$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	
	$("#genreport input[name='datefr']").change(function(){
		$("#genreportpdf input[name='datefr']").val($(this).val());
	});
	$("#genreport input[name='dateto']").change(function(){
		$("#genreportpdf input[name='dateto']").val($(this).val());
	});
	
	$("#pdfgen1").click(function() {
		window.open('./SummaryRcptListingDtl_Report/showpdf?datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val(), '_blank');
	});
	
	$("#excelgen1").click(function() {
		window.location='./SummaryRcptListingDtl_Report/showExcel?datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val();
	});
	
});