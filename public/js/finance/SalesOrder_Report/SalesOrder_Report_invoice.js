$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode','errorField',
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Unit',name:'sector'},
			],
			urlParam: {
						filterCol:['chgdept', 'recstatus','compcode'],
						filterVal:['1', 'ACTIVE','session.compcode']
					},
			ondblClickRow: function () {
				let data = selrowData('#'+dialog_deptcode.gridname);
			},
			gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#delordhd_srcdocno').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
				}
		},{
			title:"Select Department",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['chgdept', 'recstatus','compcode'];
				dialog_deptcode.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);

	if($('#scope').val() == 'POLI'){
		$('#deptcode').val('POLIKLINIK');
		$('#deptcode').prop('readonly',true);
		dialog_deptcode.off();
		$('#pdfgen1').hide();
		$('#h4_title').text('SALES POLIKLINIK');
	}else if($('#scope').val() == 'RN'){
		$('#deptcode_div').hide();
		dialog_deptcode.off();
		$('#pdfgen1').hide();
		$('#h4_title').text('RAKAN NIAGA');
	}else{
		dialog_deptcode.on();
		$('#pdfgen1').show();
		$('#h4_title').text('INVOICES PDF DOWNLOAD');
	}
    
    $("#genreport input[name='datefr']").change(function(){
        $("#genreportpdf input[name='datefr']").val($(this).val());
    });
    $("#genreport input[name='dateto']").change(function(){
        $("#genreportpdf input[name='dateto']").val($(this).val());
    });

    var dialog_CustomerSO = new ordialog(
		'customer', 'debtor.debtormast', '#db_debtorcode', 'errorField',
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
				{ label: 'Bill Type', name: 'billtypeop', width: 100, classes: 'pointer'},
				{ label: 'idno', name: 'idno',hidden:true},
				{ label: 'name', name: 'name',hidden:true},
				{ label: 'debtortype', name: 'debtortype',hidden:true},
				{ label: 'address1', name: 'address1',hidden:true},
				{ label: 'address2', name: 'address2',hidden:true},
				{ label: 'address3', name: 'address3',hidden:true},
				{ label: 'postcode', name: 'postcode',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_CustomerSO.gridname);
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
		}, {
			title: "Select Debtorcode",
			open: function(){
				dialog_CustomerSO.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerSO.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog(true);
    
    $("#pdfgen1").click(function() {
		window.open('./SalesOrder_Report/table?action=sale_invoices_pdf&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&deptcode='+$("#deptcode").val()+'&debtorcode='+$("#db_debtorcode").val(), '_blank');
	});
	
	// $("#excelgen1").click(function() {
	// 	window.open('./SalesOrder_Report/showExcel?datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val()+'&deptcode='+$("#deptcode").val()+'&scope='+$("#scope").val(), '_blank');
	// });
});