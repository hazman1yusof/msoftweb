// $.jgrid.defaults.responsive = true;
// $.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	calc_cash_bal();

	$('input[name=bilrm100],input[name=bilrm50],input[name=bilrm20],input[name=bilrm10],input[name=bilrm5],input[name=bilrm1],input[name=bilcents]').on( "change", function() {
		let bill = $(this).data('bill');
		let times = $(this).val();
		let total = parseFloat(times) * parseFloat(bill);

		let total_field = $('input[name='+get_total_field($(this).attr('name'))+']');
		total_field.val(parseFloat(total).toFixed(2));

		calc_grandtotal();

	});

	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#ctformdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};
	/////////////////////////////////save close till//////////////////////////////////////////////////////////
	var saveParam = {
		action: 'use_till',
		url:'./till/form',
		field: '',
		//oper: 'use_till',
		fixPost: true,
	}

	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			},'json')
		.fail(function (data) {
			$('.noti').text(data.responseText);
		}).done(function (data) {
			unsaved = false;

			if (selfoper == 'use_till') {
				oper = 'use_till';
				$('#tillcode').val(data.tillcode);
				$('#ActCloseBal').val(data.ActCloseBal);

			} else if (selfoper == 'edit') {
				//doesnt need to do anything
			}
		})
	}

	$("#save").click(function(){
		unsaved = false;
		// mycurrency.formatOff();
		// mycurrency.check0value(errorField);
		if($('#ctformdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#ctformdata", oper,saveParam,{idno:$('#idno').val()},'refreshGrid');
			unsaved = false;
		}else{
			//mycurrency.formatOn();
		}
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

function calc_cash_bal() {
	let open_amt = parseFloat($('#openamt').val());
	let cash_amt = parseFloat($('#CashCollected').val());
	let refund_amt = parseFloat($('#CashRefund').val());

	var close_cashbal = open_amt + cash_amt - refund_amt;
	$('input[name=cashBal]').val(parseFloat(close_cashbal).toFixed(2));
}
