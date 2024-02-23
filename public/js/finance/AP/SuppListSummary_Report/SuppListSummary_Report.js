$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    $("#genreport input[name='suppgroup']").change(function(){
		$("#genreportpdf input[name='suppgroup']").val($(this).val());
	});
   
	$('#pdfgen').click(function(){
		window.open('./SuppListSummary_Report/showpdf?suppgroup='+$('#suppgroup').val(), '_blank'); 
	});

	$('#excel').click(function(){
		window.location='./SuppListSummary_Report/showExcel?suppgroup='+$('#suppgroup').val();
	});

    /////////////////////////////////////dialog handler///////////////////////////////
	var suppgroup = new ordialog(
		'suppgroup','material.suppgroup','#suppgroup','errorField',
		{	
			colModel:[
				{label:'Supplier Group',name:'suppgroup',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
				{label:'GL Account',name:'glaccno',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Supplier Group",
			open: function(){
				suppgroup.urlParam.filterCol=['compcode','recstatus'];
				suppgroup.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
		},'urlParam','radio','tab'
	);
	suppgroup.makedialog(true);
});