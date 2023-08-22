$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
		$.validate({
			modules : 'sanitize',
			language : {
				requiredFields: 'Please Enter Value'
			},
		});

		var errorField=[];
		conf = {
			onValidate : function($form) {
				if(errorField.length>0){
					show_errors(errorField,'#formdata');
					return [{
						element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
						message : ''
					}];
				}
			},
		};

		var fdl = new faster_detail_load();
	
	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];
	

	////////////////////////////////////ordialog/////////////////////////////////////////////////////////
	var dialog_tillcode = new ordialog(
		'tillcode',['debtor.tilldetl AS td','debtor.till AS t'],'#tillcode',errorField,
		{	colModel:[
				{label:'Till Code',name:'td_tillcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'t_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'dept',name:'t_dept',width:400,classes:'pointer', hidden:true},
				{label:'opendate',name:'td_opendate',width:400,classes:'pointer', hidden:true},
				{label:'opentime',name:'td_opentime',width:400,classes:'pointer', hidden:true},
				{label:'closedate',name:'td_closedate',width:400,classes:'pointer', hidden:true},
				{label:'closetime',name:'td_closetime',width:400,classes:'pointer', hidden:true},
				{label:'openamt',name:'td_openamt',width:400,classes:'pointer', hidden:true},
				{label:'cashier',name:'td_cashier',width:400,classes:'pointer', hidden:true},
			],
			sortname: 'td_tillcode',
			sortorder: "desc",
			urlParam: {
				filterCol:['td.compcode','td.cashier', 'td.closedate'],
				filterVal:['session.compcode','session.username', '']
		},
		ondblClickRow: function () {
			let data = selrowData('#' + dialog_tillcode.gridname);
			$("#dept").val(data['t_dept']);
			$("#opendate").val(data['td_opendate']);
			$("#opentime").val(data['td_opentime']);
			$("#openamt").val(data['td_openamt']);
			$("#cashier").val(data['td_cashier']);

			var param={
				action:'get_tillclose',
				url: './till/table',
				tillcode:$('#tillcode').val(),
				//tillno:$('#tillno').val(),
			}
			$.get( param.url+"?"+$.param(param), function( data ) {
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.till)){
					$("#CashCollected").val(data.sum_cash);
					$("#ChequeCollected").val(data.sum_chq);
					$("#CardCollected").val(data.sum_card);
					$("#DebitCollected").val(data.sum_bank);
					$("#cashBal").val(data.sum_all);
				}
			});
			
			dialog_tillcode.check(errorField);

		},
		gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#actdebglacc').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}

		},{
			title:"Select Till Code",
			open: function(){
				dialog_tillcode.urlParam.fixPost = "true";
				dialog_tillcode.urlParam.filterCol=['td.compcode','td.cashier', 'td.closedate'];
				dialog_tillcode.urlParam.filterVal=['session.compcode','session.username', ''];
				dialog_tillcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_tillcode.urlParam.join_onCol = ['td.tillcode'];
				dialog_tillcode.urlParam.join_onVal = ['t.tillcode'];
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_tillcode.makedialog(true);

	$('input[name=bilrm100],input[name=bilrm50],input[name=bilrm20],input[name=bilrm10],input[name=bilrm5],input[name=bilrm1],input[name=bilcents]').on( "change", function() {
		let bill = $(this).data('bill');
		let times = $(this).val();
		let total = parseFloat(times) * parseFloat(bill);

		let total_field = $('input[name='+get_total_field($(this).attr('name'))+']');
		total_field.val(parseFloat(total).toFixed(2));

		calc_grandtotal();

	});


});

function get_total_field(bill){
	switch(bill){
		case 'bilrm100' : return 'totalrm100'; break;
		case 'bilrm50' : return 'totalrm50'; break;
		case 'bilrm20' : return 'totalrm20'; break;
		case 'bilrm10' : return 'totalrm10'; break;
		case 'bilrm5' : return 'totalrm5'; break;
		case 'bilrm1' : return 'totalrm1'; break;
		case 'bilcents' : return 'totalcents'; break;
	}
}

function calc_grandtotal(){
	var totalrm100 = parseFloat($('input[name=totalrm100]').val());
	var totalrm50 = parseFloat($('input[name=totalrm50]').val());
	var totalrm20 = parseFloat($('input[name=totalrm20]').val());
	var totalrm10 = parseFloat($('input[name=totalrm10]').val());
	var totalrm5 = parseFloat($('input[name=totalrm5]').val());
	var totalrm1 = parseFloat($('input[name=totalrm1]').val());
	var totalcents = parseFloat($('input[name=totalcents]').val());

	var grandtotal = totalrm100+totalrm50+totalrm20+totalrm10+totalrm5+totalrm1+totalcents;
	$('input[name=grandTotal]').val(parseFloat(grandtotal).toFixed(2));
	$('#ActCloseBal').val(parseFloat(grandtotal).toFixed(2));

	calc_discrepancy();
}

function calc_discrepancy(){
	let close_bal = parseFloat($('#cashBal').val());
	let act_bal = parseFloat($('#ActCloseBal').val());
	let disc = act_bal - close_bal;

	$('#discrepancy').val(parseFloat(disc).toFixed(2));

}