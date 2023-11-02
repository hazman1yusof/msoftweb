$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';


$(document).ready(function () {

	var mycurrency =new currencymode(['#CashCollected','#CashRefund', '#ChequeCollected', '#ChequeRefund', '#CardCollected','#CardRefund', '#DebitCollected', '#DebitRefund', '#openamt', '#cashBal','#discrepancy', 'input[name=totalrm100]', 'input[name=totalrm50]', 'input[name=totalrm20]','input[name=totalrm10]', 'input[name=totalrm5]', 'input[name=totalrm1]', 'input[name=totalcents]', 'input[name=grandTotal]']);

	calc_cash_bal();

	$('input[name=bilrm100],input[name=bilrm50],input[name=bilrm20],input[name=bilrm10],input[name=bilrm5],input[name=bilrm1],input[name=bilcents]').on( "change", function() {
		mycurrency.formatOff();
		let bill = $(this).data('bill');
		let times = $(this).val();
		let total = parseFloat(times) * parseFloat(bill);

		let total_field = $('input[name='+get_total_field($(this).attr('name'))+']');
		total_field.val(parseFloat(total).toFixed(2));

		calc_grandtotal();
		mycurrency.formatOn();

	});

	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules: 'logic',
		language: {
			requiredFields: ''
		},
	});

	var errorField=[];
	conf = {
		onValidate : function($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					message: ''
				}
			}
		},
	};
	// mycurrency.formatOnBlur();
	// mycurrency.formatOn();

	/////////////////////////////////save close till//////////////////////////////////////////////////////////
	
	// var saveParam = {
	// 	action: 'use_till',
	// 	url:'./till/form',
	// 	field: '',
	// 	oper: 'use_till',
	// 	table_name:'debtor.tilldetl',
	// 	table_id:'tillcode',
	// }

	// function saveHeader(form, oper, saveParam, obj) {
	// 	if (obj == null) {
	// 		obj = {};
	// 	}
	// 	saveParam.oper = oper();

	// 	$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(obj) , function( data ) {
	// 		},'json')
	// 	.fail(function (data) {
	// 		alert(data.responseText);
	// 	}).done(function (data) {
	// 		unsaved = false;

	// 	})
	// }
	
	function saveHeader(callback){
		// let oper = $("#save").data('oper', 'use_till');
		var saveParam={
			action:'save_till',
			oper:'close_till'
		}
		
		// if(oper == 'use_till'){
		// 	saveParam.tillcode = $('#tillcode').val();
		// }else if(oper == 'edit'){
		// 	saveParam.tillcode = $('#tillcode').val();
		// }else{
		// 	return;
		// }
		
		var postobj={
			_token : $('#_token').val(),
			tillno : $('#tillno').val(),
			tillcode : $('#tillcode').val(),
			actclosebal : $('#actclosebal').val(),
			reason : $('#reason').val(),
		};
		
		$.post( './till/form?'+$.param(saveParam),  $.param(postobj), function( data ) {
			
		},'json').done(function(data) {
			callback(data);
		}).fail(function(data){
			callback(data);
		});
	}
	
	function getdata_till(){
		var urlparam={
			action:'get_table_till',
		}
		
		var postobj={
			_token : $('#_token').val(),
			tillno : $('#tillno').val(),
			tillcode : $('#tillcode').val(),
		};
		
		$.post( "./till/form?"+$.param(urlparam), $.param(postobj), function( data ) {
			
		},'json').fail(function(data) {
			alert('there is an error');
		}).done(function(data){
			if(!$.isEmptyObject(data)){
				autoinsert_rowdata("#ctformdata",data.till);
				autoinsert_rowdata("#ctformdata",data.tilldetl);
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
			}else if(input.is("textarea")){
				if(value !== null){
					let newval = value.replaceAll("</br>",'\n');
					input.val(newval);
				}
			}else{
				input.val(value);
			}
		});
	}
	
	$("#save").click(function(){
		if( $('#ctformdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader(function(data){
				disableForm('#ctformdata');
				$('#save').attr('disabled',true);
				getdata_till();
			});
		}else{
			enableForm('#ctformdata');
			rdonly('#ctformdata');
		}
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
		$('#actclosebal').val(parseFloat(grandtotal).toFixed(2));

		calc_discrepancy();
	}

	function calc_discrepancy(){
		let close_bal = parseFloat(currencyRealval('#cashBal'));
		let act_bal = parseFloat(currencyRealval('#actclosebal'));
		let disc = act_bal - close_bal;

		$('#discrepancy').val(parseFloat(disc).toFixed(2));
	}

	function calc_cash_bal() {
		mycurrency.formatOff();
		let open_amt = parseFloat($('#openamt').val());
		let cash_amt = parseFloat($('#CashCollected').val());
		let refund_amt = parseFloat($('#CashRefund').val());

		var close_cashbal = open_amt + cash_amt - refund_amt;
		$('input[name=cashBal]').val(parseFloat(close_cashbal).toFixed(2));
		mycurrency.formatOn();
	}


});