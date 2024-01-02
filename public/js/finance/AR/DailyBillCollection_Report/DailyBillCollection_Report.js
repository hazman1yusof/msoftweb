$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    
    $("#genreport input[name='datefr']").change(function(){
        $("#genreportpdf input[name='datefr']").val($(this).val());
    });
    $("#genreport input[name='dateto']").change(function(){
        $("#genreportpdf input[name='dateto']").val($(this).val());
    });
    
});