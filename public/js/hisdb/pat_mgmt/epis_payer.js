var urlParam_epno_payer = {
	action:'pat_enq_payer',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

var urlParam_gletdept = {
	action:'gletdept',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

var urlParam_gletitem = {
	action:'gletitem',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

$(document).ready(function () {

	$('#my_a_payr').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			$('#mdl_payer').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	var errorField_epno_payer = [];
	conf_epno_payer = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_epno_payer.length > 0) {
				return {
					element: $(errorField_epno_payer[0]),
					message: ''
				}
			}
		},
	};

	$("#jqGrid_epno_payer").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'No', name: 'lineno', width: 30 },
            { label: 'Payer', name: 'payercode', width: 80  },
            { label: 'Name', name: 'payercode_desc', width: 200  },
            { label: 'Fin Class', name: 'pay_type' , width: 50 },
            { label: 'Limit Amt.', name: 'pyrlmtamt' , width: 100 ,formatter:'currency',formatoptions:{thousandsSeparator: ",",}},
            { label: 'All Group', name: 'allgroup' , width: 50, formatter: allgroupformat, unformat: allgroupunformat },
            { label: 'billtype_desc', name: 'billtype_desc' , hidden: true },
            { label: 'idno', name: 'idno', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'name', name: 'name', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'epistycode', name: 'epistycode', hidden: true },
            { label: 'pyrmode', name: 'pyrmode' , hidden: true },
            { label: 'alldept', name: 'alldept' , hidden: true },
            { label: 'lastupdate', name: 'lastupdate' , hidden: true },
            { label: 'lastuser', name: 'lastuser' , hidden: true },
            { label: 'billtype', name: 'billtype' , hidden: true },
            { label: 'refno', name: 'refno' , hidden: true },
            { label: 'ourrefno', name: 'ourrefno' , hidden: true },
            { label: 'computerid', name: 'computerid' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_epno_payer",
		onSelectRow:function(rowid, selected){
			populate_epno_payer(selrowData("#jqGrid_epno_payer"));
			let rowdata = selrowData("#jqGrid_epno_payer");

			if(rowdata.pay_type == 'PT'){
				button_state_epno_payer('add');
			}else{
				if(rowdata.allgroup == 0){
					$('#except_epno_payer').attr('disabled',false);
				}
			}
		},
		loadComplete: function(){
			emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
			$('#jqGrid_epno_payer_ilsave,#jqGrid_epno_payer_ilcancel').hide();

			let reccount = $('#jqGrid_epno_payer').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_epno_payer('add_edit');
			}else{
				button_state_epno_payer('add');
			}
			$('#except_epno_payer').attr('disabled',true);
			$("#jqGrid_epno_payer").setSelection($("#jqGrid_epno_payer").getDataIDs()[0]);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	var dialog_grpcode = new ordialog(
		'grpcode',['hisdb.chggroup'],"#jqGrid_gletdept input[name='grpcode']",errorField,
		{	colModel:
			[
				{label:'Group code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(event){
				let data=selrowData('#'+dialog_grpcode.gridname);
				//$("#jqGrid2 input[name='qtydelivered']").focus().select();
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid_gletdept span#"+id_optid+"_grpcode.help-block").text('');

				var rowid = $("#jqGrid_gletdept").jqGrid ('getGridParam', 'selrow');
				$("#jqGrid_gletdept").jqGrid('setRowData', rowid ,{grpcode_desc:data['description']});

			},
			gridComplete: function(obj){
        		// calc_jq_height_onchange("jqGrid2");
				// var gridname = '#'+obj.gridname;
				// if($(gridname).jqGrid('getDataIDs').length == 1){
				// 	$(gridname+' tr#1').click();
				// 	$(gridname+' tr#1').dblclick();
				// 	//$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				// }
			}
		},{
			title:"Select Group Code For Item",
			open: function(e,ui){
				$("div[aria-describedby='otherdialog_grpcode']").css('z-index','132');
				$('div.ui-widget-overlay.ui-front').css("z-index", "131");

				dialog_grpcode.urlParam.filterCol=['compcode','recstatus'];
				dialog_grpcode.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
				$("#jqGrid_gletdept input[name='grplimit']").focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_grpcode.makedialog(false);

	var dialog_chgcode = new ordialog(
		'chgcode',['hisdb.chgmast'],"#jqGrid_gletitem input[name='chgcode']",errorField,
		{	colModel:
			[
				{label:'Charge code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'UOM',name:'uom',width:100,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','unit','active','chggroup'],
				filterVal:['session.compcode','<>.DEACTIVE','session.unit','1','']
			},
			ondblClickRow:function(event){
				let data=selrowData('#'+dialog_chgcode.gridname);
				//$("#jqGrid2 input[name='qtydelivered']").focus().select();
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid_gletitem span#"+id_optid+"_chgcode.help-block").text('');

				var rowid = $("#jqGrid_gletitem").jqGrid('getGridParam', 'selrow');
				$("#jqGrid_gletitem").jqGrid('setRowData', rowid ,{chgcode_desc:data['description']});

			},
			gridComplete: function(obj){
        		// calc_jq_height_onchange("jqGrid2");
				// var gridname = '#'+obj.gridname;
				// if($(gridname).jqGrid('getDataIDs').length == 1){
				// 	$(gridname+' tr#1').click();
				// 	$(gridname+' tr#1').dblclick();
				// 	//$(obj.textfield).closest('td').next().find("input[type=text]").focus().select();
				// }
			}
		},{
			title:"Select Charge Code For Item",
			open: function(e,ui){
				$("div[aria-describedby='otherdialog_chgcode']").css('z-index','132');
				$('div.ui-widget-overlay.ui-front').css("z-index", "131");
				dialog_chgcode.urlParam.filterCol=['compcode','recstatus','unit','active','chggroup'];
				dialog_chgcode.urlParam.filterVal=['session.compcode','<>.DEACTIVE','session.unit','1',selrowData('#jqGrid_gletdept').grpcode];
			},
			close: function(obj_){
			}
		},'urlParam','radio','tab'
	);
	dialog_chgcode.makedialog(false);

	function grpcodeCustomEdit(val,opt){
		return $('<div class="input-group"><input jqgrid="jqGrid_gletdept" optid="'+opt.id+'" id="'+opt.id+'" name="grpcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function chgcodeCustomEdit(val,opt){
		return $('<div class="input-group"><input jqgrid="jqGrid_gletitem" optid="'+opt.id+'" id="'+opt.id+'" name="chgcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	function cust_rules(value,name){
		var temp=null;
		switch(name){
			case 'grpcode':temp=$("#jqGrid_gletdept input[name='grpcode']");break;
			case 'chgcode':temp=$("#jqGrid_gletitem input[name='chgcode']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	var mycurrency_gletdept =new currencymode([]);
	$("#jqGrid_gletdept").jqGrid({
		datatype: "local",
		colModel: [			
			{label:'idno', name:'idno', key:true,hidden:true},
			{label:'Group code', name:'grpcode', width: 80, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:grpcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{label:'Description', name:'grpcode_desc', width: 200},
			{label: 'All Item', name: 'allitem', width: 50, classes: 'wrap', editable: true,editrules: { required: true }, edittype:"select",formatter:'select', editoptions:{value:"1:YES;0:NO"}},
			{label:'Group limit', name:'grplimit',editable: true, editrules: { required: true }, width: 50, formatter: 'currency'},
			{label:'Group Balance', name:'grpbal',editable: true, editrules: { required: true }, width: 50, formatter: 'currency'},
			{label:'Individual Item Limit', name:'inditemlimit',editable: true, editrules: { required: true }, width: 50, formatter: 'currency'},
			{label:'compcode', name:'compcode', hidden:true},
			{label:'payercode', name:'payercode', hidden:true},
			{label:'mrn', name:'mrn', hidden:true},
			{label:'episno', name:'episno', hidden:true},
			{label:'deptcode', name:'deptcode', hidden:true},
			{label:'deptlimit', name:'deptlimit', hidden:true},
			{label:'deptbal', name:'deptbal', hidden:true},
			// {label:'grpbal', name:'grpbal', hidden:true},
			{label:'lastupdate', name:'lastupdate', hidden:true},
			{label:'lastuser', name:'lastuser', hidden:true},
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_gletdept",
		onSelectRow:function(rowid, selected){
			let rowdata = selrowData('#jqGrid_gletdept');
			if(rowdata.allitem == '0'){
				$('#jqGridPager_gletitem_left > table').eq(0).show();
				urlParam_gletitem.grpcode=rowdata.grpcode;
				refreshGrid("#jqGrid_gletitem", urlParam_gletitem);
			}else{
				$('#jqGridPager_gletitem_left > table').eq(0).hide();
			}
			dialog_chgcode.jqgrid_.urlParam.filterVal[4] = rowdata.grpcode;
		},
		loadComplete: function(){
			if(($('#jqGrid_gletdept').data('lastselrow') == 'none' || $('#jqGrid_gletdept').data('lastselrow') == null) && $("#jqGrid_gletdept").getGridParam("reccount")>0){
				$("#jqGrid_gletdept").setSelection($("#jqGrid_gletdept").getDataIDs()[0]);
			}else{
				$("#jqGrid_gletdept").setSelection($('#jqGrid_gletdept').data('lastselrow'));
				$('#jqGrid_gletdept tr#' + $('#jqGrid_gletdept').data('lastselrow')).focus();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});
	var myEditOptions_gletdept = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			let is_add = (rowid.startsWith("jqg"))?true:false;
			dialog_grpcode.on();
			$("#jqGridPagerRefresh_gletdept,#jqGridPager_gletdeptDelete").hide();
			$("input[name='Description']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_gletdept_ilsave').click();
			});

			mycurrency_gletdept.array.length = 0;
			Array.prototype.push.apply(mycurrency_gletdept.array, ["#jqGrid_gletdept input[name='inditemlimit']","#jqGrid_gletdept input[name='grplimit']"]);
			mycurrency_gletdept.formatOnBlur();//make field to currency on leave cursor

			if(is_add){
				$("#jqGrid_gletdept input[name='inditemlimit']").val(9999999.99);
				mycurrency_gletdept.formatOn();
				$('#jqGrid_gletdept').data('lastselrow','none');
			}else{
				$('#jqGrid_gletdept').data('lastselrow',rowid);
			}

			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			let resjson = JSON.parse(response.responseText);
			$('#jqGrid_gletdept').data('lastselrow',resjson.idno);
			//state true maksudnyer ada isi, tak kosong
			refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
			errorField.length=0;
			$("#jqGridPagerRefresh_gletdept,#jqGridPager_gletdeptDelete").show();
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText);
			alert(data.res);
			//$('#p_error').text(response.responseText);
			refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
		},
		beforeSaveRow: function (options, rowid) {
			mycurrency_gletdept.formatOff();
			if(errorField.length>0)return false;

			let data = $('#jqGrid_gletdept').jqGrid ('getRowData', rowid);

			// check_cust_rules();
			let editurl = "./pat_enq/form?"+
				$.param({
					action: 'save_gletdept',
					payercode: $('#glet_payercode').val(),
					mrn: $('#glet_mrn').val(),
					episno: $('#glet_episno').val()
				});
			$("#jqGrid_gletdept").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
			$("#jqGridPagerRefresh_gletdept,#jqGridPager_gletdeptDelete").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	$("#jqGrid_gletdept").inlineNav('#jqGridPager_gletdept', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_gletdept
		},
		editParams: myEditOptions_gletdept
	}).jqGrid('navButtonAdd',"#jqGridPager_gletdept",{
		id: "jqGridPager_gletdeptDelete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid_gletdept").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
			}else{
				if (confirm("Are you sure you want to delete this row?") == true) {
				    param={
		    			_token: $("#csrf_token").val(),
		    			action: 'save_gletdept',
						idno: selRowId,
		    		}
		    		$.post( "./pat_enq/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function(data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function(data){
						refreshGrid("#jqGrid_gletdept",urlParam_gletdept);
					});
				}
				
			}
		},
	}).jqGrid('navButtonAdd', "#jqGrid_gletdept", {
		id: "jqGridPagerRefresh_gletdept",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
		},
	});

	$("#jqGrid_gletitem").jqGrid({
		datatype: "local",
		colModel: [
			{label:'idno', name:'idno', key:true,hidden:true},
			{ label:'Charge code', name:'chgcode', width: 80, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:chgcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{label:'Description', name:'chgcode_desc', width: 200},
			{label:'Total Item Limit', name:'totitemlimit', width: 80,editable: true, editrules: { required: true }, formatter: 'currency'},
			{label:'Total Item Balance', name:'totitembal', width: 80,editable: true, editrules: { required: true }, formatter: 'currency'},
			{label:'compcode', name:'compcode',hidden:true},
			{label:'payercode', name:'payercode',hidden:true},
			{label:'mrn', name:'mrn',hidden:true},
			{label:'episno', name:'episno',hidden:true},
			{label:'deptcode', name:'deptcode',hidden:true},
			{label:'grpcode', name:'grpcode',hidden:true},
			// {label:'totitembal', name:'totitembal',hidden:true},
			{label:'lastupdate', name:'lastupdate',hidden:true},
			{label:'lastuser', name:'lastuser',hidden:true},
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_gletitem",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
			if(($('#jqGrid_gletitem').data('lastselrow') == 'none' || $('#jqGrid_gletitem').data('lastselrow') == null) && $("#jqGrid_gletdept").getGridParam("reccount")>0){
				$("#jqGrid_gletitem").setSelection($("#jqGrid_gletitem").getDataIDs()[0]);
			}else{
				$("#jqGrid_gletitem").setSelection($('#jqGrid_gletitem').data('lastselrow'));
				$('#jqGrid_gletitem tr#' + $('#jqGrid_gletitem').data('lastselrow')).focus();
			}
			if(selrowData('#jqGrid_gletdept').allitem == '0'){
				$('#jqGridPager_gletitem_left > table').eq(0).show();
			}else{
				$('#jqGridPager_gletitem_left > table').eq(0).hide();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});
	var myEditOptions_gletitem = {
		keys: true,
		extraparam:{
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid) {
			dialog_chgcode.on();
			$('#jqGrid_gletitem').data('jqGrid_gletitem','none');
			$("#jqGridPagerRefresh_gletitem").hide();
			// $("input[name='Description']").keydown(function(e) {//when click tab at last column in header, auto save
			// 	var code = e.keyCode || e.which;
			// 	if (code == '9')$('#jqGrid_ilsave').click();
			// });
			mycurrency_gletdept.array.length = 0;
			Array.prototype.push.apply(mycurrency_gletdept.array, ["#jqGrid_gletdept input[name='totitemlimit']"]);
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});
		},
		aftersavefunc: function (rowid, response, options) {
			let resjson = JSON.parse(response.responseText);
			$('#jqGrid_gletitem').data('lastselrow',resjson.idno);
			//state true maksudnyer ada isi, tak kosong
			errorField.length=0;
			refreshGrid("#jqGrid_gletitem", urlParam_gletitem);
		},
		errorfunc: function(rowid,response){
			var data = JSON.parse(response.responseText);
			alert(data.res);
			//$('#p_error').text(response.responseText);
			refreshGrid("#jqGrid_gletitem", urlParam_gletitem);
		},
		beforeSaveRow: function (options, rowid) {
			mycurrency_gletdept.formatOff();
			if(errorField.length>0)return false;

			let selrowdata = selrowData('#jqGrid_gletdept');
			let data = $('#jqGrid_gletitem').jqGrid ('getRowData', rowid);

			// check_cust_rules();

			let editurl = "./pat_enq/form?"+
				$.param({
					action: 'save_gletitem',
					grpcode: selrowdata.grpcode,
					payercode: $('#glet_payercode').val(),
					mrn: $('#glet_mrn').val(),
					episno: $('#glet_episno').val()
				});
			$("#jqGrid_gletitem").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid("#jqGrid_gletitem", urlParam_gletitem);
			$("#jqGridPagerRefresh_gletitem").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};
	$("#jqGrid_gletitem").inlineNav('#jqGridPager_gletitem', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_gletitem
		},
		editParams: myEditOptions_gletitem
	}).jqGrid('navButtonAdd',"#jqGridPager_gletitem",{
		id: "jqGridPager_gletitemDelete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid_gletitem").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
			}else{
				if (confirm("Are you sure you want to delete this row?") == true) {
				    param={
		    			_token: $("#csrf_token").val(),
		    			action: 'save_gletitem',
						idno: selRowId,
		    		}
		    		$.post( "./pat_enq/form?"+$.param(param),{oper:'del'}, function( data ){
					}).fail(function(data) {
						//////////////////errorText(dialog,data.responseText);
					}).done(function(data){
						refreshGrid("#jqGrid_gletitem",urlParam_gletitem);
					});
				}
				
			}
		},
	}).jqGrid('navButtonAdd', "#jqGrid_gletitem", {
		id: "jqGridPagerRefresh_gletitem",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_gletitem", urlParam_gletitem);
		},
	});

	$("#jqGrid_epno_payer").inlineNav('#jqGridPager_epno_payer', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_epno_payer", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		},
	});

	$("#tabPayer").on("shown.bs.collapse", function(){
		$("#jqGrid_epno_payer").jqGrid ('setGridWidth', Math.floor($("#jqGrid_epno_payer_c")[0].offsetWidth-$("#jqGrid_epno_payer_c")[0].offsetLeft-0));
		$('#jqGridPager_gletitem_left > table').eq(0).hide();
		urlParam_epno_payer.mrn = $("#mrn_episode").val();
		urlParam_epno_payer.episno = $("#txt_epis_no").val();
		urlParam_gletdept.mrn = $("#mrn_episode").val();
		urlParam_gletdept.episno = $("#txt_epis_no").val();
		urlParam_gletitem.mrn = $("#mrn_episode").val();
		urlParam_gletitem.episno = $("#txt_epis_no").val();

		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		$('#mrn_epno_payer').val($("#mrn_episode").val());
		$('#episno_epno_payer').val($("#txt_epis_no").val());
		$('#epistycode_epno_payer').val(selrowData('#jqGrid_episodelist').epistycode);
		$('#name_epno_payer').val($('#txt_epis_name').text());

		$("#mdl_reference").data('from','payer');
    	$("#refno_epno_payer_btn").off('click',btn_refno_info_onclick);
		$("#refno_epno_payer_btn").on('click',btn_refno_info_onclick);
	});

	var epno_payer_payercode = new ordialog(
		'epno_payer_payercode', 'debtor.debtormast', '#payercode_epno_payer', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'debtortype', name: 'debtortype', width: 2, hidden:true },
			],
			urlParam: {
				url:'./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val()
			},
			ondblClickRow: function () {
				let selrow = selrowData('#'+epno_payer_payercode.gridname);
				$(epno_payer_payercode.textfield).parent().next().html('');
				$('#payercode_desc_epno_payer').val(selrow.name);
				$('#pay_type_epno_payer').val(selrow.debtortype);

			}
		},{
			title: "Select Payer Code",
			open: function () {
				epno_payer_payercode.urlParam.url='./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val();


				$('div[aria-describedby="otherdialog_epno_payer_payercode"]').css("z-index", "132");
				$('div.ui-widget-overlay.ui-front').css("z-index", "131");
			}
		},'urlParam','radio','tab'
	);
	epno_payer_payercode.makedialog(false);
	
	$("#add_epno_payer").click(function(){
		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		button_state_epno_payer('wait');
		enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		epno_payer_payercode.on();
		$("#save_epno_payer").data('oper','add');
		$('#pyrlmtamt_epno_payer').val(9999999.99);
		$('#allgroup_epno_payer').val(1);

		var rows = $('#jqGrid_epno_payer').getGridParam("reccount");
		$('#lineno_epno_payer').val(rows+1);
	});

	$("#edit_epno_payer").click(function(){
		let selrow = $('#jqGrid_epno_payer').jqGrid ('getGridParam', 'selrow');
		if(selrow == null){
			alert('Select payer first!');
		}else{
			button_state_epno_payer('wait');
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
			epno_payer_payercode.on();
			$("#save_epno_payer").data('oper','edit');
		}
	});

	$("#save_epno_payer").click(function(){
		disableForm('#form_epno_payer');
		if( $('#form_epno_payer').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_epno_payer(function(){
				refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
			});
		}else{
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		}

	});

	function saveForm_epno_payer(callback){

        var serializedForm = $("#form_epno_payer").serializeArray();

	    serializedForm = serializedForm.concat(
	        $('#form_epno_payer select').map(
	        function() {
	            return {"name": this.name, "value": this.value}
	        }).get()
		);

	    var obj={
	    	action:'save_payer',
	        oper:$("#save_epno_payer").data('oper'),
	    	_token : $('#csrf_token').val()
	    };

	    $.post( "./pat_enq/form", $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_epno_payer").click(function(){
		button_state_epno_payer('empty');
		disableForm('#form_epno_payer');
		epno_payer_payercode.off();

		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
	});

	disableForm('#form_epno_payer');
	button_state_epno_payer('add');
	function button_state_epno_payer(state){
		switch(state){
			case 'empty':
				$('#add_epno_payer,#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_epno_payer,#edit_epno_payer").attr('disabled',false);
				$('#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add':
				$("#add_epno_payer").attr('disabled',false);
				$('#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'wait':
				$("#save_epno_payer,#cancel_epno_payer").attr('disabled',false);
				$('#add_epno_payer,#edit_epno_payer').attr('disabled',true);
				break;
		}
	}

	function populate_epno_payer(obj){
		var form = '#form_epno_payer';
		var except = [];

		$.each(obj, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if( except != undefined && except.indexOf(index) === -1){
				input.val(decodeEntities(value));
			}
		});

		$('#pyrlmtamt_epno_payer').val(numeral($('#pyrlmtamt_epno_payer').val()).format('0,0.00'));
	}
		
	$('#except_epno_payer').click(function(){
		$("#mdl_glet").off("shown.bs.modal");

		if(selrowData('#jqGrid_epno_payer').allgroup == 0){
			$("#mdl_glet").on("shown.bs.modal", function(){
				var epno_payer = selrowData("#jqGrid_epno_payer");
				$('#glet_mrn').val(padzero7(epno_payer.mrn));
				$('#glet_name').val(epno_payer.name);
				$('#glet_episno').val(padzero7(epno_payer.episno));
				$('#glet_payercode').val(epno_payer.payercode);
				$('#glet_payercode_desc').val(epno_payer.payercode_desc);
				$('#glet_totlimit').val(numeral(epno_payer.pyrlmtamt).format('0,0.00'));
				$('#glet_allgroup').val(allgroupformat2(epno_payer.allgroup));
				$('#glet_refno').val(epno_payer.refno);
				$("#jqGrid_gletdept").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
				$("#jqGrid_gletitem").jqGrid ('setGridWidth', Math.floor($("#glet_row")[0].offsetWidth-$("#glet_row")[0].offsetLeft-0));
				
				refreshGrid("#jqGrid_gletdept", urlParam_gletdept);
			});

        	$('#mdl_glet').modal('show');
		}
		
	});

});

function allgroupformat(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return '<span data-orig='+cellvalue+'>Yes</span>';
	}else if(cellvalue == '0'){
		return '<span data-orig='+cellvalue+'>No</span>';
	}else{
		return '<span data-orig='+cellvalue+'></span>';
	}
}

function allgroupformat2(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return 'Yes';
	}else if(cellvalue == '0'){
		return 'No';
	}else{
		return cellvalue;
	}
}

function allgroupunformat(cellvalue, options, rowObject){
	return $(rowObject).find('span').data('orig');
}

// var textfield_modal = new textfield_modal();
// textfield_modal.ontabbing();
// textfield_modal.checking();
// textfield_modal.clicking();