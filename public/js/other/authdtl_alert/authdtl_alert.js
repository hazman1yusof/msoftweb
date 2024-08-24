$(document).ready(function(){
	get_authdtl_alert();
});

function get_authdtl_alert(){
	$('#authdtl_alert_div').html('');

	var param_authdtl_alert={
		action:'get_authdtl_alert',
		url: './authorizationDtl/table',
	}
	$.get( param_authdtl_alert.url+"?"+$.param(param_authdtl_alert), function( data ) {
		
	},'json').done(function(data) {
		if(!$.isEmptyObject(data)){
			populate_authdtl_alert_pv(data.queuepv);
			populate_authdtl_alert_pd(data.queuepd);
			populate_authdtl_alert_pr(data.queuepr);
			populate_authdtl_alert_po(data.queuepo);
			populate_authdtl_alert_so(data.queueso);
			populate_authdtl_alert_IV(data.queueso);
		}
	});
}

function populate_authdtl_alert_pr(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-green'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Purchase Request</div>
								<div><b>Recno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pr','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-green'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-archive fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Purchase Request</div>
								<div><b>Recno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Request Dept: </b><span>`+e.reqdept+` - `+e.purreqno+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.purreqdt, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pr','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>
			`;
		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_po(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-yellow'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Purchase Order</div>
								<div><b>Recno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('po','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-yellow'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Purchase Order</div>
								<div><b>Recno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Request Dept: </b><span>`+e.prdept+` - `+e.purordno+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.purdate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('po','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>
			`;

		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_pv(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-teal'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Payment Voucher</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pv','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-teal'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Payment Voucher</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Supplier: </b><span>`+e.suppcode+` - `+e.Name+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.actdate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pv','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_pd(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-grey'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Payment Deposit</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pv','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-grey'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Payment Deposit</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Supplier: </b><span>`+e.suppcode+` - `+e.Name+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.actdate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pv','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_so(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-purple'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Sales Order</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('so','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-purple'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Sales Order</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Supplier: </b><span>`+e.payercode+` - `+e.name+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.adddate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('so','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_iv(data){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-purple'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Inventory Transaction</div>
								<div><b>Auditno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('so','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}else{
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-purple'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'>Inventory Transaction</div>
								<div><b>Auditno: </b><span>`+e.recno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Department: </b><span>`+e.deptcode+` - `+e.deptcode_desc+`</span></div>
								<div><b>Prepared On: </b><span>`+moment(e.adddate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.adduser+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('so','`+e.trantype+`','`+e.recno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	})
}

function authdtl_alert_click(type,trantype,recno){
    let mql = window.matchMedia("(max-width: 768px)");
	if(mql.matches){
		return authdtl_alert_click_mobile(type,trantype,recno);
    }
    
	switch(type){
		case 'pr':
			Menu.new_dialog('PurReq_dataentry','purchaseRequest?scope='+trantype+'&recno='+recno,'Purchase Request');
			break;
		case 'po':
			Menu.new_dialog('PurOrd_DataEntry','purchaseOrder?scope='+trantype+'&recno='+recno,'Purchase Order');
			break;
		case 'pv':
			Menu.new_dialog('dataentryPV','paymentVoucher?source=AP&scope='+trantype+'&auditno='+recno,'Payment Voucher');
			break;
		case 'pd':
			Menu.new_dialog('dataentryPV','paymentVoucher?source=AP&scope='+trantype+'&auditno='+recno,'Payment Voucher');
			break;
		case 'so':
			Menu.new_dialog('salesorder_datentry','SalesOrder?scope='+trantype+'&auditno='+recno,'Sales Order');
			break;
		case 'iv':
			Menu.new_dialog('material_invtran_AIAO','inventoryTransaction?scope='+trantype+'&ttype=AI&recno='+recno,'Sales Order');
			break;
	}
}

function authdtl_alert_click_mobile(type,trantype,recno){
	switch(type){
		case 'pr':
			open_mobile_page('purchaseRequest_mobile?scope='+trantype+'&recno='+recno);
			break;
		case 'po':
			open_mobile_page('purchaseOrder_mobile?scope='+trantype+'&recno='+recno);
			break;
		case 'pv':
			open_mobile_page('paymentVoucher_mobile?scope='+trantype+'&auditno='+recno+'&type=PV');
			break;
		case 'pd':
			open_mobile_page('paymentVoucher_mobile?scope='+trantype+'&auditno='+recno+'&type=PD');
			break;
		case 'so':
			open_mobile_page('SalesOrder_mobile?scope='+trantype+'&auditno='+recno);
			break;
		case 'iv':
			open_mobile_page('inventoryTransaction_mobile?scope='+trantype+'&recno='+recno);
			break;
	}
}

function close_and_refresh(){
	open_mobile_page('');
	get_authdtl_alert()
}