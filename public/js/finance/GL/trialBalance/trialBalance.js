$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	set_yearperiod();

	$('#summary_pdf').click(function(){
		window.open('./trialBalance/table?action=pdfTBnett&monthfrom='+$("#monthfrom").val()+'&monthto='+$("#monthto").val()+'&yearfrom='+$("#yearfrom").val()+'&yearto='+$("#yearto").val()+'&acctfrom='+$("#acctfrom").val()+'&acctto='+$("#acctto").val(), '_blank');
	});

	$('#summary_excel').click(function(){
		window.open('./trialBalance/table?action=excelTBnett&monthfrom='+$("#monthfrom").val()+'&monthto='+$("#monthto").val()+'&yearfrom='+$("#yearfrom").val()+'&yearto='+$("#yearto").val()+'&acctfrom='+$("#acctfrom").val()+'&acctto='+$("#acctto").val(), '_blank');
	});
});

function set_yearperiod(){
	// param={
	// 	action:'get_value_default',
	// 	field: ['year'],
	// 	table_name:'sysdb.period',
	// 	table_id:'idno',
	// 	sortby:['year desc']
	// }
	// $.get( "util/get_value_default?"+$.param(this.param), function( data ) {
			
	// },'json').done(function(data) {
	// 	if(!$.isEmptyObject(data.rows)){
	// 		data.rows.forEach(function(element){	
	// 			$('#yearfrom').append("<option>"+element.year+"</option>")
	// 			$('#yearto').append("<option>"+element.year+"</option>")
	// 		});
	// 	}
	// });

	$('select#monthfrom').val(moment().format('M'));
	$('select#monthto').val(moment().format('M'));
}