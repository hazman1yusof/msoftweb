$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    var status = [['DAILY','DETAIL']];
	if($("#Status").val() == 'DAILY'){
        $("#genreportpdf").attr('href','./SummaryRcptListing_Report/showpdf?');
	}
   
});