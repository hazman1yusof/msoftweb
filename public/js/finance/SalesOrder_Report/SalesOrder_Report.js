$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode','errorField',
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['chgdept', 'recstatus','compcode'],
						filterVal:['1', 'ACTIVE','session.compcode']
					},
			ondblClickRow: function () {
				let data = selrowData('#'+dialog_deptcode.gridname);
			},
			gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#delordhd_srcdocno').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['chgdept', 'recstatus','compcode'];
				dialog_deptcode.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);

	if($('#scope').val() == 'POLI'){
		$('#deptcode').val('POLIKLINIK');
		$('#deptcode').prop('readonly',true);
		dialog_deptcode.off();
		$('#pdfgen1').hide();
		$('#h4_title').text('SALES POLIKLINIK');
	}else{
		dialog_deptcode.on();
		$('#pdfgen1').show();
		$('#h4_title').text('SALES');
	}
    
    $("#genreport input[name='datefr']").change(function(){
        $("#genreportpdf input[name='datefr']").val($(this).val());
    });
    $("#genreport input[name='dateto']").change(function(){
        $("#genreportpdf input[name='dateto']").val($(this).val());
    });
    
    $("#pdfgen1").click(function() {
		window.open('./SalesOrder_Report/showpdf?datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&deptcode='+$("#deptcode").val(), '_blank');
	});
	
	$("#excelgen1").click(function() {
		window.location='./SalesOrder_Report/showExcel?datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&deptcode='+$("#deptcode").val()+'&scope='+$("#scope").val();
	});
});