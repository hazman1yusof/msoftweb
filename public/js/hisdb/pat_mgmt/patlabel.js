$(document).ready(function () {
	$("#dialog_patlabel")
	  .dialog({
		width: 2/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {

		},
		close: function( event, ui ) {

		}
	  });

    $('#btn_patlabel').click(function(){
        $('#dialog_patlabel').dialog('open');
    });

    $('#patlabel_print').click(function(){
	    var lastrowdata = getrow_bootgrid();
	    var pages=$('#patlabel_pages').val();
	    var param = {
	    	name:lastrowdata.Name,
	    	mrn:lastrowdata.MRN,
	    	sex:lastrowdata.Sex,
	    	age:gettheage(lastrowdata.DOB),
	    	date:lastrowdata.reg_date,
	    	newic:lastrowdata.Newic,
	    	dob:lastrowdata.DOB,
	    	race:lastrowdata.raceDesc,
	    	bedno:lastrowdata.bednum,
	    	ward:lastrowdata.ward,
	    	doc:lastrowdata.q_doctorname,
	    	pages:pages,
	    }
	    window.open("./pat_mast/patlabel"+"?"+$.param(param));
    });
});