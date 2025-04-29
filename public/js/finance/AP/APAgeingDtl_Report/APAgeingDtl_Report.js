$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    $("#genreport input[name='suppcode_from']").change(function(){
		$("#genreportpdf input[name='suppcode_from']").val($(this).val());
	});
	$("#genreport input[name='suppcode_to']").change(function(){
		$("#genreportpdf input[name='suppcode_to']").val($(this).val());
	});
	$("#genreport input[name='date_ag']").change(function(){
		$("#genreportpdf input[name='date_ag']").val($(this).val());
	});
   
	$('#pdfgen').click(function(){
		window.open('./APAgeingDtl_Report/showpdf?suppcode_from='+$('#suppcode_from').val()+'&suppcode_to='+$("#suppcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val(),  '_blank'); 
	});

	$('#excel').click(function(){
		window.open('./APAgeingDtl_Report/showExcel?type='+$('#type').val()+'&suppcode_from='+$('#suppcode_from').val()+'&suppcode_to='+$("#suppcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val(),  '_blank'); 
	});

    /////////////////////////////////////dialog handler///////////////////////////////
	var suppcode_from = new ordialog(
		'suppcode_from','material.supplier','#suppcode_from','errorField',
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
			title:"Select Creditor",
			open: function(){
				suppcode_from.urlParam.filterCol=['compcode','recstatus'];
				suppcode_from.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_from.makedialog(true);

	var suppcode_to = new ordialog(
		'suppcode_to','material.supplier','#suppcode_to','errorField',
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
			title:"Select Creditor",
			open: function(){
				suppcode_to.urlParam.filterCol=['compcode','recstatus'];
				suppcode_to.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_to.makedialog(true);
});