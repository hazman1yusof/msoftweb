
	
	////////////////////////////////////////till checking at start of program supposedly///////////////////
	
	var def_tillcode,def_tillno;
	$(document).ready(function () {

	// dialog_till=new makeDialog('debtor.till','#tilldetTillcode',['tillcode','description','tillstatus'], 'Select Till');
	var dialog_till = new ordialog(
		'till','debtor.till','#tilldetTillcode','errorField',
		{	colModel:[
				{label:'Till Code',name:'tillcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Till Name',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Till Status',name:'tillstatus',hidden:true},
				{label:'defopenamt',name:'defopenamt',hidden:true}
			],
			urlParam: {
						filterCol:['compcode','recstatus', 'tillstatus'],
						filterVal:['session.compcode','ACTIVE','C']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_till.gridname);
				$( "#tilldetCheck" ).button( "option", "disabled", false );
				$( "#openamt" ).val(data.defopenamt);
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
			title:"Select Till",
			open: function(){
				dialog_till.urlParam.filterCol=['recstatus', 'compcode', 'tillstatus'],
				dialog_till.urlParam.filterVal=['ACTIVE', 'session.compcode','C']
				}
			},'urlParam','radio','tab'
		);
	dialog_till.makedialog(true);

	function updateTillUsage(){
		var param={
				action:'use_till',
				url:'till/form',
			};

		var obj = {
			_token:$('#csrf_token').val(),
			cashier:$('#cashier').val(),
			tillcode:$('#tilldetTillcode').val(),
			openamt:$('#openamt').val()
		}
		$.post( param.url+"?"+$.param(param),obj, 
			function( data ) {
				
			}
		).fail(function(data) {
			alert('Error');
		}).done(function(data){
			$( "#tilldet" ).dialog('close');
		});
	}
	
	// function checkIfTillOpen(callback){
	// 	var param={
	// 		action:'get_value_default',
	// 		url:'util/get_value_default',
	// 		field:['tilldetl.tillcode','till.lastrcnumber','till.dept','tilldetl.tillno','tilldetl.opendate','tilldetl.opentime','tilldetl.closedate','tilldetl.closetime','tilldetl.cashier','department.sector','department.region'],
	// 		table_name:['debtor.till','debtor.tilldetl','sysdb.department'],
	// 		join_type:['LEFT JOIN','LEFT JOIN'],
	// 		join_onCol:['till.tillcode','till.dept'],
	// 		join_onVal:['tilldetl.tillcode','department.deptcode'],
	// 		filterCol:['cashier','closedate'],
	// 		filterVal:['session.username','IS NULL']
	// 	}
	// 	$.get( param.url+"?"+$.param(param), function( data ) {
			
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data.rows)){
	// 			def_tillcode = data.rows[0].tillcode;
	// 			def_tillno = data.rows[0].tillno;
	// 			if (callback !== undefined) {
	// 				// urlParam.filterVal = [data.rows[0].tillno];
	// 				// refreshGrid('#jqGrid',urlParam);
	// 				callback(data);
	// 			}
	// 			// $("#formdata input[name='dbacthdr_tillcode']").val(data.rows[0].tillcode);
	// 			// $("#formdata input[name='dbacthdr_lastrcnumber']").val(parseInt(data.rows[0].lastrcnumber) + 1);
	// 			// $("#formdata input[name='dbacthdr_recptno']").val(data.rows[0].tillcode+"-"+pad('000000000',parseInt(data.rows[0].lastrcnumber) + 1,true));
	// 			// $("#formdata input[name='dbacthdr_tillno']").val(data.rows[0].tillno);
	// 			// $("#formdata input[name='dbacthdr_units']").val(data.rows[0].sector);

	// 			$( "#tilldet" ).dialog('close');
	// 		}else{
	// 			// dialog_till.handler([]);
	// 		}
	// 	});
	// }

	$( "#tilldet" ).dialog({
		autoOpen: true,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function() { 
			// checkIfTillOpen();
			$(this).parent().find(".ui-dialog-titlebar-close").hide();                       
		},
		buttons: [
			{
				text:'Open Till',
				disabled: true,
				id: "tilldetCheck",
				click:function(){
					updateTillUsage();
				}
			},{
				text:'Reset',
				click:function(){
					emptyFormdata([],'#formTillDet');
					$( "#tilldetCheck" ).button( "option", "disabled", true );
				}
			},
		],
		closeOnEscape: false,
	});

	//////////////////////////////////////////End till checking/////////////////////////


});

function checkifuserlogin(callback){
	var param={
		action:'get_value_default',
		url:'util/get_value_default',
		field:['*'],
		table_name:['debtor.tilldetl'],
		filterCol:['cashier','compcode','closedate'],
		filterVal:['session.username','session.compcode','null.null']
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(data.rows.length == 0){
			if (callback !== undefined) {
				callback(data);
			}
		}else{
			$( "#tilldet" ).dialog('close');
		}
	});
}