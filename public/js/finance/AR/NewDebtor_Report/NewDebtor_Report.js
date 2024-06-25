$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
	
	$("#genreport input[name='yearfrom']").change(function (){
		$("#genreportpdf input[name='yearfrom']").val($(this).val());
	});
	$("#genreport input[name='yearto']").change(function (){
		$("#genreportpdf input[name='yearto']").val($(this).val());
	});
	
	$("#pdfgen1").click(function (){
		window.open('./NewDebtor_Report/showpdf?yearfrom='+$("#yearfrom").val()+'&yearto='+$("#yearto").val(), '_blank');
	});
	
	$("#excelgen1").click(function (){
		window.location='./NewDebtor_Report/showExcel?yearfrom='+$("#yearfrom").val()+'&yearto='+$("#yearto").val();
	});
	
	set_yearperiod();
	function set_yearperiod(){
		param = {
			action: 'get_value_default',
			field: ['year'],
			table_name: 'sysdb.period',
			table_id: 'idno',
			sortby: ['year desc']
		}
		$.get("util/get_value_default?"+$.param(this.param), function (data){
			
		}, 'json').done(function (data){
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function (element){
					$('#yearfrom').append("<option>"+element.year+"</option>")
					$('#yearto').append("<option>"+element.year+"</option>")
				});
			}
		});
		
		$('select#monthfrom').val(moment().format('MM'));
		$('select#monthto').val(moment().format('MM'));
	}
	
});