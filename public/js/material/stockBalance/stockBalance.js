
$.validate({
	modules: 'sanitize',
	language: {
		requiredFields: ''
	},
});

var errorField = [];
conf = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			console.log(errorField);
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	set_yearperiod();
	var dept_from = new ordialog(
		'dept_from','sysdb.department','#dept_from',errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
			},
			ondblClickRow: function () {
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
			title:"Select Department",
			open: function(){
				dept_from.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dept_from.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dept_from.makedialog(true);

	var dept_to = new ordialog(
		'dept_to','sysdb.department','#dept_to',errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
			},
			ondblClickRow: function () {
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
			title:"Select Department",
			open: function(){
				dept_to.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dept_to.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('dept_to',errorField)!==-1){
						errorField.splice($.inArray('dept_to',errorField), 1);
					}
				}
			},
		},'urlParam','radio','tab'
	);
	dept_to.makedialog(true);

	var dialog_itemcodefrom = new ordialog(
		'item_from',['material.stockloc AS s','material.product AS p'],"#item_from",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'s_uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'deptcode',name:'s_deptcode',width:200,classes:'pointer',hidden:true},
				{label: 'year',name:'s_year',width:100,classes:'pointer', hidden:true},
				{label: 'unitcost',name:'p_currprice',width:100,classes:'pointer', hidden:true},

			],
			urlParam: {
				fixPost : "true",
				filterCol:['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'],//,'sector'
				filterVal:['ACTIVE','session.compcode', $('#dept_from').val(), moment().year(),'session.unit'],
				join_type:['LEFT JOIN'],
				join_onCol:['s.itemcode'],
				join_onVal:['p.itemcode'],
				join_filterCol:[['s.compcode on =','s.uomcode on =','s.unit on =']],
				join_filterVal:[['p.compcode','p.uomcode','p.unit']],
			},
			ondblClickRow: function () {
	
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
			title:"Select Department",
			open: function(){
				dialog_itemcodefrom.urlParam.table_name = ['material.stockloc AS s','material.product AS p'];
				dialog_itemcodefrom.urlParam.fixPost = "true";
				dialog_itemcodefrom.urlParam.table_id = "none_";
				dialog_itemcodefrom.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode', $('#dept_from').val(), moment().year(),'session.unit'];//, 'session.unit'
				dialog_itemcodefrom.urlParam.join_type = ['LEFT JOIN'];
				dialog_itemcodefrom.urlParam.join_onCol = ['s.itemcode'];
				dialog_itemcodefrom.urlParam.join_onVal = ['p.itemcode'];
				dialog_itemcodefrom.urlParam.join_filterCol = [['s.compcode on =','s.uomcode on =','s.unit on =']];
				dialog_itemcodefrom.urlParam.join_filterVal = [['p.compcode','p.uomcode','p.unit']];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_itemcodefrom.makedialog(true);
	
	var dialog_itemcodeto = new ordialog(
		'item_to',['material.stockloc AS s','material.product AS p'],"#item_to",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'s_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'s_uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'deptcode',name:'s_deptcode',width:200,classes:'pointer',hidden:true},
				{label: 'year',name:'s_year',width:100,classes:'pointer', hidden:true},
				{label: 'unitcost',name:'p_currprice',width:100,classes:'pointer', hidden:true},

			],
			urlParam: {
				fixPost : "true",
				filterCol:['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'],//,'sector'
				filterVal:['ACTIVE','session.compcode', $('#dept_from').val(), moment().year(),'session.unit'],
				join_type:['LEFT JOIN',],
				join_onCol:['s.itemcode'],
				join_onVal:['p.itemcode'],
				join_filterCol:[['s.compcode on =','s.uomcode on =','s.unit on =']],
				join_filterVal:[['p.compcode','p.uomcode','p.unit']],
			},
			ondblClickRow: function () {
				$("#jqGrid2").jqGrid("clearGridData", true);
				let item_from = $('#item_from').val();
				let item_to = $('#item_to').val();

			
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
			title:"Select Department",
			open: function(){
				dialog_itemcodeto.urlParam.table_name = ['material.stockloc AS s','material.product AS p'];
				dialog_itemcodeto.urlParam.fixPost = "true";
				dialog_itemcodeto.urlParam.table_id = "none_";
				dialog_itemcodeto.urlParam.filterCol=['s.recstatus','s.compcode', 's.deptcode', 's.year','s.unit'];//,'sector'
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode', $('#dept_from').val(), moment().year(),'session.unit'];//, 'session.unit'
				dialog_itemcodeto.urlParam.join_type = ['LEFT JOIN'];
				dialog_itemcodeto.urlParam.join_onCol = ['s.itemcode'];
				dialog_itemcodeto.urlParam.join_onVal = ['p.itemcode'];
				dialog_itemcodeto.urlParam.join_filterCol = [['s.compcode on =','s.uomcode on =','s.unit on =']];
				dialog_itemcodeto.urlParam.join_filterVal = [['p.compcode','p.uomcode','p.unit']];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('item_to',errorField)!==-1){
						errorField.splice($.inArray('item_to',errorField), 1);
					}
				}
			},
		},'urlParam','radio','tab'
	);
	dialog_itemcodeto.makedialog(true);

	$('button.mybtn').click(function(){
		$('input[name=action]').val($(this).attr('name'));

		if($('#formdata').isValid({requiredFields:''},conf,true)){
			var serializedForm =  $('#formdata').serializeArray()

			// var param={
			// 	dept_from:'get_value_default',
			// 	dept_to: './util/get_value_default',
			// 	item_from:['backday'],
			// 	item_to:'material.sequence',
			// 	year:['compcode','dept','trantype'],
			// 	period:['session.compcode',this.deptcode,this.trxtype]
			// }

			window.open('./stockBalance/report?'+$.param(serializedForm), '_blank');
		}

		
	});
});


function set_yearperiod(){
	param={
		action:'get_value_default',
		url: 'util/get_value_default',
		field: ['year'],
		sortby:['year desc'],
		table_name:'sysdb.period',
		table_id:'idno'
	}
	$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(element){	
					$('#year').append("<option>"+element.year+"</option>")
				});
			}
		});
}