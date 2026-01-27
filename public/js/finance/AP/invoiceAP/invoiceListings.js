
$.validate({
	modules: 'sanitize',
	language: {
		requiredFields: ''
	},
});

var errorField = [];
conf = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			console.log(errorField);
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var supp_from = new ordialog(
		'supp_from','material.supplier','#supp_from','errorField',
		{	
			colModel:[
				{label:'Supplier code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'suppcode',
			sortorder:'asc',
			ondblClickRow: function () {
				let data=selrowData('#'+supp_from.gridname);

				$("#supp_to").val(data['suppcode']);
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
			title:"Select Department",
			open: function(){
				supp_from.urlParam.filterCol=['recstatus','compcode'];//,'sector'
				supp_from.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
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
	supp_from.makedialog(true);

	var supp_to = new ordialog(
		'supp_to','sysdb.department','#supp_to','errorField',
		{	
			colModel:[
				{label:'Supplier code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'suppcode',
			sortorder:'asc',
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
			title:"Select Department",
			open: function(){
				supp_to.urlParam.filterCol=['recstatus','compcode'];//,'sector'
				supp_to.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('supp_to',errorField)!==-1){
						errorField.splice($.inArray('supp_to',errorField), 1);
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
	supp_to.makedialog(true);

	$('#invoiceListings').click(function(){
		$("#span_dlexcel").hide();
		$('#job_id').val('');
		$('input[name=action]').val($(this).attr('name'));

		if($('#formdata').isValid({requiredFields:''},conf,true)){
			$(this).prop('disabled',true);
			$(this).html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');

			let serializedForm =  $('#formdata').serializeArray();
			let href = './invoiceAP/table?'+$.param(serializedForm);

			$.get( href, function( data ) {

			},'json').fail(function(data) {

			}).done(function(data){
				$('#job_id').val(data.job_id);
			});
			
			startProcessInterval();
		}
	});

	let intervalId = null;
  	function startProcessInterval() {
	    intervalId = setInterval(check_running_process, 8000);
	}
	function stopProcessInterval() {
	    if (intervalId !== null) {
	        clearInterval(intervalId);
	        intervalId = null;
	    }
	}

	function check_running_process() {
		let job_id = $('#job_id').val();
		if(job_id == ''){
			console.log('no job id');
			return 0;
		}

		$.get( './invoiceAP/table?action=check_running_process&job_id='+job_id, function( data ) {
			
		},'json').done(function(data) {
	    	if(data.jobdone=='true'){
	    		stopProcessInterval();
				$('#invoiceListings').attr('disabled',false);
				$('#invoiceListings').html(`<span class="fa fa-file-excel-o fa-lg"></span> Process XLS`);
				$("#span_dlexcel").show();
			}else{
				$('#invoiceListings').attr('disabled',true);
				$('#invoiceListings').html('Processing.. <i class="fa fa-refresh fa-spin fa-fw">');
				$("#span_dlexcel").hide();
			}
		});
	}

	$("#download_excel").click(function() {
		if($('#job_id').val() == ''){
			
		}else{
			window.open('./invoiceAP/table?action=download_excel&job_id='+$('#job_id').val(), '_blank');
		}
	});
});