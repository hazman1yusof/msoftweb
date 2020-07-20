$(document).ready(function () {

	disableForm('#orderCommForm');

	$("#new_orderComm").click(function(){
		button_state_orderComm('wait');
		enableForm('#orderCommForm');
		rdonly('#orderCommForm');
		// dialog_mrn_edit.on();
		
	});

	$("#edit_orderComm").click(function(){
		button_state_orderComm('wait');
		enableForm('#orderCommForm');
		rdonly('#orderCommForm');
		// dialog_mrn_edit.on();
		
	});

	$("#save_orderComm").click(function(){
		disableForm('#orderCommForm');
		if( $('#orderCommForm').isValid({requiredFields: ''}, conf, true) ) {
			saveForm_orderComm(function(){
				$("#cancel_orderComm").data('oper','edit');
				$("#cancel_orderComm").click();
				$("#jqGridPagerRefresh").click();
			});
		}else{
			enableForm('#orderCommForm');
			rdonly('#orderCommForm');
		}

	});

	$("#cancel_orderComm").click(function(){
		disableForm('#orderCommForm');
		button_state_orderComm($(this).data('oper'));
		// dialog_mrn_edit.off();

	});

	// // // //to format number input to two decimal places (0.00)
	// $(".floatNumberField").change(function() {
	// 	$(this).val(parseFloat($(this).val()).toFixed(2));
	// });

	// // // //to limit to two decimal places (onkeypress)
	// $(document).on('keydown', 'input[pattern]', function(e){
	// 	var input = $(this);
	// 	var oldVal = input.val();
	// 	var regex = new RegExp(input.attr('pattern'), 'g');
	  
	// 	setTimeout(function(){
	// 		var newVal = input.val();
	// 		if(!regex.test(newVal)){
	// 			input.val(oldVal); 
	// 	  	}
	// 	}, 0);
	// });

});

var errorField = [];
conf = {
	modules : 'logic',
	language: {
		requiredFields: 'You have not answered all required fields'
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

// button_state_orderComm('empty');
function button_state_orderComm(state){
	switch(state){
		case 'empty':
			$("#toggle_orderComm").removeAttr('data-toggle');
			$('#cancel_orderComm').data('oper','add');
			$('#new_orderComm,#save_orderComm,#cancel_orderComm,#edit_orderComm').attr('disabled',true);
			break;
		case 'add':
			$("#toggle_orderComm").attr('data-toggle','collapse');
			$('#cancel_orderComm').data('oper','add');
			$("#new_orderComm").attr('disabled',false);
			$('#save_orderComm,#cancel_orderComm,#edit_orderComm').attr('disabled',true);
			break;
		case 'edit':
			$("#toggle_orderComm").attr('data-toggle','collapse');
			$('#cancel_orderComm').data('oper','edit');
			$("#edit_orderComm").attr('disabled',false);
			$('#save_orderComm,#cancel_orderComm,#new_orderComm').attr('disabled',true);
			break;
		case 'wait':
			dialog_tri_col.on();
			//examination_orderComm.on().enable();
			$("#toggle_orderComm").attr('data-toggle','collapse');
			$("#save_orderComm,#cancel_orderComm").attr('disabled',false);
			$('#edit_orderComm,#new_orderComm').attr('disabled',true);
			break;
	}

	// if(!moment(gldatepicker_date).isSame(moment(), 'day')){
	// 	$('#new_orderComm,#save_orderComm,#cancel_orderComm,#edit_orderComm').attr('disabled',true);
	// }
}

function populate_orderCommForm(obj,rowdata){
	
	emptyFormdata(errorField,"#orderCommForm");

	//panel header
	$('#name_show_orderComm').text(obj.name);
	$('#mrn_show_orderComm').text(obj.mrn);

	//orderCommForm
	$('#mrn_orderComm').val(obj.mrn);       ///////////////////check balik nama dkt controller
	$("#episno_orderComm").val(obj.episno);

	var saveParam={
        action:'get_table_orderComm',
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    	mrn:obj.mrn,
    	episno:obj.episno

    };

    $.post( "/orderComm/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
    	if(!$.isEmptyObject(data)){
			autoinsert_rowdata("#orderCommForm",data.chargetrx);
			autoinsert_rowdata("#orderCommForm",data.chargetrx);
			if(!$.isEmptyObject(data.orderComm_exm)){
				// examination_orderComm.empty();
				// examination_orderComm.examarray = data.orderComm_exm;
				// examination_orderComm.loadexam().disable();
			}
			
			button_state_orderComm('edit');
        }else{
			button_state_orderComm('add');
			// examination_orderComm.empty();
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

function saveForm_orderComm(callback){
	var saveParam={
        action:'save_table_orderComm',
        oper:$("#cancel_orderComm").data('oper')
    }
    var postobj={
    	_token : $('#csrf_token').val(),
    };

    values = $("#orderCommForm").serializeArray();

    values = values.concat(
        $('#orderCommForm input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );

    values = values.concat(
        $('#orderCommForm input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
	);
	
	values = values.concat(
        $('#orderCommForm input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values = values.concat(
        $('#orderCommForm select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
	);

    $.post( "/ordercomm/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });


	//////////////////// Start Dialog for OrdComm Dtl////////////////////////////////////////////////////////////////////////////////
	var dialog_chgcode= new ordialog(
		'chgcode','hisdb.chgmast','#chgcode',errorField,
		{	colModel:[
			    {label:'Chargecode',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
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
					$('#trxtype').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Type",
			open: function(){
				dialog_chgcode.urlParam.filterCol=['compcode'],
				dialog_chgcode.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_chgcode.makedialog();


	var dialog_trxtype= new ordialog(
		'trxtype','hisdb.chargetrx','#trxtype',errorField,
		{	colModel:[
			    {label:'Type',name:'trxtype',width:200,classes:'pointer',canSearch:true,or_search:true},
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
					$('#quantity').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Type",
			open: function(){
				dialog_trxtype.urlParam.filterCol=['compcode'],
				dialog_trxtype.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_trxtype.makedialog();

	var dialog_description= new ordialog(
		'description','hisdb.chargetrx','#description',errorField,
		{	colModel:[
			    //{label:'Type',name:'trxtype',width:200,classes:'pointer',canSearch:true,or_search:true},
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
					$('#isudept').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},
		{
			title:"Select Description",
			open: function(){
				dialog_description.urlParam.filterCol=['compcode'],
				dialog_description.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_description.makedialog();
}