$(document).ready(function(){
	get_authdtl_alert();
});

function get_authdtl_alert(){

	var param_authdtl_alert={
		action:'get_authdtl_alert',
		url: './authorizationDtl/table',
	}
	$.get( param_authdtl_alert.url+"?"+$.param(param_authdtl_alert), function( data ) {
		
	},'json').done(function(data) {
		$('#authdtl_alert_div').html('');
		if(!$.isEmptyObject(data)){
			populate_authdtl_alert_pv(data.queuepv,data.queuepvv2);
			populate_authdtl_alert_pd(data.queuepd,data.queuepdv2);
			populate_authdtl_alert_dp(data.queuedp,data.queuedpv2);
			populate_authdtl_alert_pr(data.queuepr,data.queueprv2);
			populate_authdtl_alert_po(data.queuepo,data.queuepov2);
			populate_authdtl_alert_so(data.queueso);
			populate_authdtl_alert_iv(data.queueiv);
			populate_authdtl_alert_ivreq(data.ivreq);
		}
	});
}

function populate_authdtl_alert_pr(data,datav2){
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
		}
		$('#authdtl_alert_div').append(block_pr);
	});

	let keys = Object.keys(datav2);

	keys.forEach(function(e,i){
		let trantype = e;
		let count = datav2[keys[i]].length
		var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-green'>
					<div class='panel-heading top-h'>
						<span>Purchase Request</span>
					</div>
					<div class='panel-heading no-br'>
						<div class='row'>
							<div class='col-xs-12'>`;
								datav2[keys[i]].forEach(function(e,i){
									block_pr += `<a onclick="authdtl_alert_click('pr','`+trantype+`','`+e.recno+`')" class='dtl_a'><div><b>Recno:</b><span>`+e.recno+`</span><b> by </b><span>`+e.adduser+` - `+e.reqdept+` - `+e.purreqdt+`</span></div></a>`;
								})
								
			block_pr += `	</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('prv2','`+trantype+`')">
								<span class='pull-left'>`+count+` PR to be `+trantype+`</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
			$('#authdtl_alert_div').append(block_pr);
	});
}

function populate_authdtl_alert_po(data,datav2){
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
		}
		$('#authdtl_alert_div').append(block_pr);
	})

	let keys = Object.keys(datav2);

	keys.forEach(function(e,i){
		let trantype = e;
		let count = datav2[keys[i]].length
		var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-yellow'>
					<div class='panel-heading top-h'>
						<span>Purchase Order</span>
					</div>
					<div class='panel-heading no-br'>
						<div class='row'>
							<div class='col-xs-12'>`;
								datav2[keys[i]].forEach(function(e,i){
									block_pr += `<a onclick="authdtl_alert_click('po','`+trantype+`','`+e.recno+`')" class='dtl_a'><div><b>Recno:</b><span>`+e.recno+`</span><b> by </b><span>`+e.adduser+` - `+e.prdept+` - `+e.purdate+`</span></div></a>`;
								})
								
			block_pr += `	</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pov2','`+trantype+`')">
								<span class='pull-left'>`+count+` PO to be `+trantype+`</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
			$('#authdtl_alert_div').append(block_pr);
	});
}

function populate_authdtl_alert_pv(data,datav2){
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
		}
		$('#authdtl_alert_div').append(block_pr);
	});

	let keys = Object.keys(datav2);

	keys.forEach(function(e,i){
		let trantype = e;
		let count = datav2[keys[i]].length
		var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-teal'>
					<div class='panel-heading top-h'>
						<span>Payment Voucher</span>
					</div>
					<div class='panel-heading no-br'>
						<div class='row'>
							<div class='col-xs-12'>`;
								datav2[keys[i]].forEach(function(e,i){
									block_pr += `<a onclick="authdtl_alert_click('pv','`+trantype+`','`+e.auditno+`')" class='dtl_a'><div><b>Auditno:</b><span>`+e.auditno+`</span><b> by </b><span>`+e.adduser+` - `+e.suppcode+` - `+e.actdate+`</span></div></a>`;
								})
								
			block_pr += `	</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pvv2','`+trantype+`')">
								<span class='pull-left'>`+count+` PV to be `+trantype+`</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
			$('#authdtl_alert_div').append(block_pr);
	});
}

function populate_authdtl_alert_dp(data,datav2){
	data.forEach(function(e,i){
		if(e.trantype == 'REOPEN'){
			var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-teal'>
					<div class='panel-heading'>
						<div class='row'>
							<div class='col-xs-2 nopadleft'><i class='fa fa-suitcase fa-4x'></i></div>
							<div class='col-xs-10 text-right'>
								<div class='huge'><span class='reject_span1'>(Rejected)</span> Direct Payment</div>
								<div><b>Auditno: </b><span>`+e.auditno+`</span><b> Status: </b><span>`+e.recstatus+`</span></div>
								<div><b>Reject On: </b><span>`+moment(e.canceldate, 'YYYY-MM-D').format('DD-MM-YYYY')+` by `+e.cancelby+`</span></div>
							</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('dp','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	});

	let keys = Object.keys(datav2);

	keys.forEach(function(e,i){
		let trantype = e;
		let count = datav2[keys[i]].length
		var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-teal'>
					<div class='panel-heading top-h'>
						<span>Direct Payment</span>
					</div>
					<div class='panel-heading no-br'>
						<div class='row'>
							<div class='col-xs-12'>`;
								datav2[keys[i]].forEach(function(e,i){
									block_pr += `<a onclick="authdtl_alert_click('dp','`+trantype+`','`+e.auditno+`')" class='dtl_a'><div><b>Auditno:</b><span>`+e.auditno+`</span><b> by </b><span>`+e.adduser+` - `+e.suppcode+` - `+e.actdate+`</span></div></a>`;
								})
								
			block_pr += `	</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('dpv2','`+trantype+`')">
								<span class='pull-left'>`+count+` PV to be `+trantype+`</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
			$('#authdtl_alert_div').append(block_pr);
	});
}

function populate_authdtl_alert_pd(data,datav2){
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
							<a onclick="authdtl_alert_click('pd','`+e.trantype+`','`+e.auditno+`')">
								<span class='pull-left'>Detail</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
		}
		$('#authdtl_alert_div').append(block_pr);
	});

	let keys = Object.keys(datav2);

	keys.forEach(function(e,i){
		let trantype = e;
		let count = datav2[keys[i]].length
		var block_pr = `
			<div class='col-md-3'>
				<div class='panel panel-grey'>
					<div class='panel-heading top-h'>
						<span>Payment Deposit</span>
					</div>
					<div class='panel-heading no-br'>
						<div class='row'>
							<div class='col-xs-12'>`;
								datav2[keys[i]].forEach(function(e,i){
									block_pr += `<a onclick="authdtl_alert_click('pd','`+trantype+`','`+e.auditno+`')" class='dtl_a'><div><b>Auditno:</b><span>`+e.auditno+`</span><b> by </b><span>`+e.adduser+` - `+e.suppcode+` - `+e.actdate+`</span></div></a>`;
								})
								
			block_pr += `	</div>
						</div>
					</div>
						<div class='panel-footer'>
							<a onclick="authdtl_alert_click('pdv2','`+trantype+`')">
								<span class='pull-left'>`+count+` PD to be `+trantype+`</span>
								<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
								<div class='clearfix'></div>
							</a>
						</div>
				</div>
			</div>`;
			$('#authdtl_alert_div').append(block_pr);
	});
}

function populate_authdtl_alert_pd_LAMA(data){
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
							<a onclick="authdtl_alert_click('iv','`+e.trantype+`','`+e.recno+`')">
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
							<a onclick="authdtl_alert_click('iv','`+e.trantype+`','`+e.recno+`')">
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

function populate_authdtl_alert_ivreq(data){
	if(data.length > 0){
		var block_pr = `
		<div class='col-md-3'>
			<div class='panel panel-purple'>
				<div class='panel-heading' style='padding:5px'>
					<div class='row'>
						<div class='col-xs-12'>
							<div class='huge'>Inventory Request</div>`;
							data.forEach(function(e,i){
								block_pr += `<div><b>Recno:</b><span>`+e.recno+`</span><b> Request By:</b><span>`+e.postedby+` - `+e.dept+`</span></div>`;
							})
							
		block_pr += `	</div>
					</div>
				</div>
					<div class='panel-footer'>
						<a onclick="authdtl_alert_click('ivreq')">
							<span class='pull-left'>Detail</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
					</div>
			</div>
		</div>`;
		$('#authdtl_alert_div').append(block_pr);
	}
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
		case 'prv2':
			Menu.new_dialog('PurReq_dataentry','purchaseRequest?scope='+trantype,'Purchase Request');
			break;
		case 'po':
			Menu.new_dialog('PurOrd_DataEntry','purchaseOrder?scope='+trantype+'&recno='+recno,'Purchase Order');
			break;
		case 'pov2':
			Menu.new_dialog('PurOrd_DataEntry','purchaseOrder?scope='+trantype,'Purchase Order');
			break;
		case 'pv':
			Menu.new_dialog('dataentryPV','paymentVoucher?source=AP&scope='+trantype+'&auditno='+recno,'Payment Voucher');
			break;
		case 'pvv2':
			Menu.new_dialog('dataentryPV','paymentVoucher?source=AP&scope='+trantype,'Payment Voucher');
			break;
		case 'pd':
			Menu.new_dialog('dataentryPD','paymentVoucher?source=AP&scope='+trantype+'&ttype=PD&auditno='+recno,'Payment Deposit');
			break;
		case 'pdv2':
			Menu.new_dialog('dataentryPD','paymentVoucher?source=AP&scope='+trantype+'&ttype=PD','Payment Deposit');
			break;
		case 'dp':
			Menu.new_dialog('dataentryDP','directPayment?scope='+trantype+'&auditno='+recno,'Direct Payment');
			break;
		case 'dpv2':
			Menu.new_dialog('dataentryDP','directPayment?scope='+trantype,'Direct Payment');
			break;
		case 'so':
			Menu.new_dialog('salesorder_datentry','SalesOrder?scope='+trantype+'&auditno='+recno,'Sales Order');
			break;
		case 'iv':
			Menu.new_dialog('material_invtran_AIAO','inventoryTransaction?scope='+trantype+'&ttype=AI&recno='+recno,'Inventory Transaction');
			break;
		case 'ivreq':
			Menu.new_dialog('material_invtran_TUITUO','inventoryTransaction?scope=ALL&ttype=TUO','Inventory Transaction');
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