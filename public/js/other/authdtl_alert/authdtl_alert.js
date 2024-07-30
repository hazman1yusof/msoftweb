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
			populate_authdtl_alert_pr(data.queuepr);
			populate_authdtl_alert_po(data.queuepo);
		}
	});
}

function populate_authdtl_alert_pr(data){
	data.forEach(function(e,i){
		var block_pr = `
		<div class='col-lg-3 col-md-6'>
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
		$('#authdtl_alert_div').append(block_pr);
	})
}

function populate_authdtl_alert_po(data){
	data.forEach(function(e,i){
		var block_pr = `
		<div class='col-lg-3 col-md-6'>
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
			Menu.new_dialog('PurReq_dataentry','purchaseRequest?scope='+trantype+'&recno='+recno,'Inventory > Purchase Request > Data Entry');
			break;
		case 'po':
			Menu.new_dialog('PurOrd_DataEntry','purchaseOrder?scope='+trantype+'&recno='+recno,'Inventory > Purchase Order > Data Entry');
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
	}
}

function close_and_refresh(){
	open_mobile_page('');
	get_authdtl_alert()
}