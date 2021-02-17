$(document).ready(function () {

	disableForm('#formtransferFA');

	// $("#new_transferFA").click(function(){
	// 	button_state_transferFA('wait');
	// 	enableForm('#formtransferFA');
	// 	rdonly('#formtransferFA');
	// 	// dialog_mrn_edit.on();
		
	// });

	$("#edit_transferFA").click(function(){
		button_state_transferFA('wait');
		enableForm('#formtransferFA');
		frozeOnEdit('#formtransferFA');
		dialog_deptcode.on();
		dialog_loccode.on();
	});

	$("#save_transferFA").click(function(){
		disableForm('#formtransferFA');
		if( $('#formtransferFA').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_transferFA(function(){
				$("#cancel_transferFA").data('oper','edit');
				$("#cancel_transferFA").click();
				refreshGrid("#jqGrid",urlParam,'edit');
			});
		}else{
			enableForm('#formtransferFA');
			frozeOnEdit('#formtransferFA');
		}
	});

	$("#cancel_transferFA").click(function(){
		disableForm('#formtransferFA');
		button_state_transferFA('edit');
		dialog_deptcode.off();
		dialog_loccode.off();
	});
});


var errorField = [];
conf = {
	modules : 'logic',
	language: {
		// requiredFields: 'You have not answered all required fields'
	},
	onValidate: function ($form) {
		if (errorField.length > 0) {
			return {
				element: $(errorField[0]),
				message: ''
			}
		}
	},
};

var dialog_deptcode= new ordialog(
	'deptcode','sysdb.department','input[name="newdeptcode"]',errorField,
	{	colModel:[
			{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
			filterCol:['compcode'],
			filterVal:['session.compcode']
		},
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				$('#loccode').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},
	{
		title:"Select Department",
		open: function(){
			dialog_deptcode.urlParam.filterCol=['compcode'],
			dialog_deptcode.urlParam.filterVal=['session.compcode']
		}
	},'urlParam','radio','tab'
);
dialog_deptcode.makedialog();

var dialog_loccode= new ordialog(
	'loccode','sysdb.location','input[name="newloccode"]',errorField,
	{	colModel:[
			{label:'Location Code',name:'loccode',width:200,classes:'pointer',canSearch:true,or_search:true},
			{label:'Description',name:'description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
		],
		urlParam: {
			filterCol:['compcode'],
			filterVal:['session.compcode']
		},
		sortname:'idno',
		sortorder:'desc',
		gridComplete: function(obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},
	{
		title:"Select Location",
		open: function(){
			dialog_loccode.urlParam.filterCol=['compcode'];
			dialog_loccode.urlParam.filterVal=['session.compcode'];
			
		}
	},'urlParam','radio','tab'
);
dialog_loccode.makedialog();

button_state_transferFA('edit');
function button_state_transferFA(state){
	switch(state){
		case 'empty':
			$("#toggle_transferFA").removeAttr('data-toggle');
			$('#cancel_transferFA').data('oper','add');
			$('#new_transferFA,#save_transferFA,#cancel_transferFA,#edit_transferFA').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_transferFA").attr('data-toggle','collapse');
			$('#cancel_transferFA').data('oper','add');
			$("#new_transferFA").attr('disabled',false);
			$('#save_transferFA,#cancel_transferFA,#edit_transferFA').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_transferFA").attr('data-toggle','collapse');
			$('#cancel_transferFA').data('oper','edit');
			$("#edit_transferFA").attr('disabled',false);
			$('#save_transferFA,#cancel_transferFA,#new_transferFA').attr('disabled',true);
			break;
		case 'wait':
			$("#toggle_transferFA").attr('data-toggle','collapse');
			$("#save_transferFA,#cancel_transferFA").attr('disabled',false);
			$('#edit_transferFA,#new_transferFA').attr('disabled',true);
			break;
	}
}

function populate_transferAE(obj,rowdata){

	emptyFormdata(errorField,"#formtransferFA");
	
	//panel header
	$('#category_show_transferAE').text(obj.assetcode);
	$('#description_show_transferAE').text('Description: '+obj.description);
	$('#assetno_show_transferAE').text(obj.assetno);

	var saveParam={
        action:'get_table_transferFA',
    }
    var postobj={
		_token : $('#_token').val(),
    	delordno:obj.delordno,
    	assetno:obj.assetno

    };

    $.post( "/assettransfer2/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#formtransferFA",data.transferFA);
			autoinsert_rowdata("#formtransferFA",data.faregister);
			button_state_transferFA('edit');
        }else{
			button_state_transferFA('add');
        }

    });
	
}

function autoinsert_rowdata(form,rowData){
	$.each(rowData, function( index, value ) {
		var input=$(form+" [name='"+index+"']");
		if(input.is("[type=radio]")){
			$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
		}else if(input.is("[type=checkbox]")){
			if(value==1){
				$(form+" [name='"+index+"']").prop('checked', true);
			}
		}else{
			input.val(value);
		}
	});
}

function saveForm_transferFA(callback){
	var saveParam={
        action:'save_table_transferFA',
        oper:$("#cancel_transferFA").data('oper')
    }
    var postobj={
    	// _token : $('#_token').val(),
    };

	values = $("#formtransferFA").serializeArray();

    $.post( "/assettransfer2/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}


