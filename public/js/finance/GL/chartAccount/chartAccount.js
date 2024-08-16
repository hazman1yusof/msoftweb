
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

var mycurrency =new currencymode(
	[
		'#actamount1', '#bdgamount1', '#varamount1', 
		'#actamount2', '#bdgamount2', '#varamount2', 
		'#actamount3', '#bdgamount3', '#varamount3', 
		'#actamount4', '#bdgamount4', '#varamount4', 
		'#actamount5', '#bdgamount5', '#varamount5', 
		'#actamount6', '#bdgamount6', '#varamount6', 
		'#actamount7', '#bdgamount7', '#varamount7', 
		'#actamount8', '#bdgamount8', '#varamount8', 
		'#actamount9', '#bdgamount9', '#varamount9', 
		'#actamount10', '#bdgamount10', '#varamount10', 
		'#actamount11', '#bdgamount11', '#varamount11', 
		'#actamount12', '#bdgamount12', '#varamount12', 
		'#totalActual', '#totalBdg', '#totalVar']
);

$(document).ready(function () {
	$("body").show();
    $('#year').attr('disabled', 'disabled');

    set_yearDefault();
	function set_yearDefault(){
		param={
            action:'get_value_default',
            url: 'util/get_value_default',
			field: ['pvalue2'],
			table_name:'sysdb.sysparam',
			table_id:'idno',
			filterCol:['compcode', 'source', 'trantype'],
			filterVal:['session.compcode', 'GL', 'PY'],
        }
		    
		$.get( "util/get_value_default?"+$.param(this.param), function( data ) {
	
		},'json').done(function(data) {
          	if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(element){
					$('#year').append("<option>"+element.pvalue2+"</option>")

				});
			}	
        });
	}

	/////////////////////////validation//////////////////////////
	$.validate({
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	var fdl = new faster_detail_load();
	// var err_reroll = new err_reroll('#jqGrid',['catcode', 'description']);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'chartAccount/table',
		field:'',
		table_name:['finance.glmasdtl','finance.costcenter'],
		table_id:'costcode',
		join_type:['LEFT JOIN'],
		join_onCol:['glmasdtl.costcode'],
		join_onVal:['costcenter.costcode'],
		sort_idno: true,
	}

	//////////////////////////////// jQgrid /////////////////////////////////////////////////////
	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "./chartAccount/form",
		 colModel: [
            {label: 'idno', name: 'idno', width: 10, hidden: true,key:true},
			{label: 'compcode', name: 'compcode', width: 10, hidden: true},
			{label: 'Cost Code', name: 'costcode', width: 90, canSearch:true, checked:true, classes: 'wrap', editable:true,
                editrules:{required: true,custom:true, custom_func:cust_rules},formatter: showdetail,
                    edittype:'custom',	editoptions:
                        {  custom_element:costcodeCustomEdit,
                           custom_value:galGridCustomValue 	
                        },
            },
			{label: 'Description', name: 'description', width: 90, canSearch:false, checked:false, hidden: true},
			{label: 'GL Account', name: 'glaccount', width: 90, editable:true, formatter: showdetail, unformat:un_showdetail,editoptions:{readonly: "readonly"}},
            {label: 'Year', name: 'year', width: 90, editable:true, editrules:{required: false},editoptions:{readonly: "readonly"},},
			{label: 'Open Balance', name: 'openbalance',formatter:'currency', width: 90, readonly: true, align: 'right'},
			{label: 'actamount1', name: 'actamount1', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount2', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount3', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount4', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount5', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount6', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount7', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount8', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount9', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount10', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount11', width: 90 , hidden: true},
			{label: 'actamount1', name: 'actamount12', width: 90 , hidden: true},

		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 200,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#jqGrid').data('lastselrow',rowid);
			getActual();
			getTotalActual();
			// if(!err_reroll.error)$('#p_error').text('');   //hilangkan error msj after save
		},
		loadComplete: function(){
			if(addmore_jqgrid.more == true){
				$('#jqGrid_iladd').click();
			}else if($('#jqGrid').data('lastselrow') == 'none'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($('#jqGrid').data('lastselrow'));
				$('#jqGrid tr#' + $('#jqGrid').data('lastselrow')).focus();
			}
				addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
				// if(err_reroll.error == true){
				// 	err_reroll.reroll();
				// }
			},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
			// $('#p_error').text('');   //hilangkan duplicate error msj after save
		},
		gridComplete: function () {
			let lastselrow = $('#jqGrid').data('lastselrow');
			if(lastselrow == undefined){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			}else{
				$("#jqGrid").setSelection(lastselrow);
				$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus().click();
			}
			fdl.set_array().reset();
		},
	});

	function check_cust_rules(rowid){
		var chk = ['catcode','description'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid input[name='"+e+"']",false);
			}
		})

	}

	//////////////////////////My edit options /////////////////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid').data('lastselrow','none');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_costcode.on();

			$("input[name='costcode']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			// $("#jqGrid input[type='text']").on('focus',function(){
			// 	$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
			// 	$("#jqGrid input[type='text']").removeClass( "error" );
			// });

		},
		aftersavefunc: function (rowid, response, options) {
			//if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			addmore_jqgrid.more = true;
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'add');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			//var data = JSON.parse(response.responseText)
			//$('#p_error').text(response.responseText);
			// err_reroll.old_data = data.request;
			// err_reroll.error = true;
			// err_reroll.errormsg = data.errormsg;
			//alert(response.responseText);
			refreshGrid('#jqGrid',urlParam,'add');
		},
		beforeSaveRow: function (options, rowid) {
			// $('#p_error').text('');
			//if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			// check_cust_rules();
			let editurl = "./chartAccount/form?"+
				$.param({
					action: 'chartAccount_save',
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid',urlParam,'add');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	var myEditOptions_edit = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$('#jqGrid').data('lastselrow',rowid);
			$("#jqGridPagerDelete,#jqGridPagerRefresh").hide();

			dialog_costcode.on();

			$("input[name='costcode']").keydown(function(e) {//when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_ilsave').click();
				/*addmore_jqgrid.state = true;
				$('#jqGrid_ilsave').click();*/
			});
			$("#jqGrid input[type='text']").on('focus',function(){
				$("#jqGrid input[type='text']").parent().removeClass( "has-error" );
				$("#jqGrid input[type='text']").removeClass( "error" );
			});

		},
		aftersavefunc: function (rowid, response, options) {
			if(addmore_jqgrid.state == true)addmore_jqgrid.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid',urlParam,'edit');
			errorField.length=0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorfunc: function(rowid,response){
			// $('#p_error').text(response.responseText);
			refreshGrid('#jqGrid',urlParam,'edit');
		},
		beforeSaveRow: function (options, rowid) {
			// $('#p_error').text('');
			if(errorField.length>0)return false;

			let data = $('#jqGrid').jqGrid ('getRowData', rowid);
			console.log(data);

			// check_cust_rules();
			let editurl = "./chartAccount/form?"+
				$.param({
					action: 'chartAccount_save',
					// source: $('#source').val(),
					// cattype:$('#cattype').val(),
				});
			$("#jqGrid").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			refreshGrid('#jqGrid',urlParam,'edit');
			$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
		},
		errorTextFormat: function (data) {
			alert(data);
		}
	};

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Cost Code':temp=$('#costcode');break;
			// case 'Gl Account':temp=$('#glaccount');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
    
	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,case_;
		switch(options.colModel.name){
			case 'costcode':field=['costcode','description'];table="finance.costcenter";case_='costcode';break;
			case 'glaccount':field=['glaccno','description'];table="finance.glmasref";case_='glaccount';break;

		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

		fdl.get_array('chartAccount',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	function unformat_showdetail(cellvalue, options, rowObject){
		return $(rowObject).attr('title');
	}

	function costcodeCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="costcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function glAccCustomEdit(val, opt) {
		val = !(opt.rowId >>> 0 === parseFloat(opt.rowId)) ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="glaccount" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerDelete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'chartAccount_save',
								idno: selrowData('#jqGrid').idno,
							}
							$.post( "./chartAccount/form?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								refreshGrid("#jqGrid", urlParam);
							});
						}else{
							$("#jqGridPagerDelete,#jqGridPagerRefresh").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
    }).jqGrid('navButtonAdd',"#jqGridPager",{
		id: "select_year",
		caption:"Year",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Year",
		onClickButton: function(){
            $("#year").prop("disabled",false);
            set_yearperiod();
			//refreshGrid("#jqGrid",urlParam);
		}
	});


	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	//addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
    var dialog_glaccount = new ordialog(
		'glaccountSearch','finance.glmasref','#glaccountSearch',errorField,
		{	colModel:[
				{label:'Code',name:'glaccno',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
			filterCol:['compcode','recstatus'],
			filterVal:['session.compcode','ACTIVE']
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
			title:"Select GL Account",
			open: function(){
				dialog_glaccount.urlParam.filterCol=['compcode','recstatus'],
				dialog_glaccount.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_glaccount.makedialog(true);
	dialog_glaccount.on();

	var dialog_costcode = new ordialog(
		'costcode','finance.costcenter',"#jqGrid input[name='costcode']",errorField,
		{	colModel:[
                {label:'Cost Code',name:'costcode',width:200,classes:'pointer',canSearch:true,or_search:true},
                {label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
            ],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
				ondblClickRow: function () {
					let data=selrowData('#'+dialog_costcode.gridname);
					$("#jqGrid input[name='glaccount']").val([$("#glaccountSearch").val()]);
					$("#jqGrid input[name='year']").val([$("#year").val()]);
					dialog_glaccount.check(errorField,'costcode');
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
			title:"Select Cost Code",
			open: function(){
				dialog_costcode.urlParam.filterCol=['compcode','recstatus'];
				dialog_costcode.urlParam.filterVal=['session.compcode','ACTIVE'];
				
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_costcode.makedialog();

	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll=function(){

			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();

			this.data_array.forEach(function(item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
		
	}

    $('#search').click(function(){
		urlParam.glaccount = $('#glaccountSearch').val();
		urlParam.year = $('#year').val();
		refreshGrid("#jqGrid",urlParam);

		$('#bdgamount1,#bdgamount2,#bdgamount3,#bdgamount4,#bdgamount5,#bdgamount6,#bdgamount7,#bdgamount8,#bdgamount9,#bdgamount10,#bdgamount11,#bdgamount12').prop("readonly", false);

	});

	function set_yearperiod(){
		param={
            action:'get_value_default',
			field: ['year'],
			table_name:'sysdb.period',
			table_id:'idno',
			sortby:['year desc']
		}
		$.get( "util/get_value_default?"+$.param(this.param), function( data ) {
				
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				data.rows.forEach(function(element){	
					$('#year').append("<option>"+element.year+"</option>")
				});
			}
		});
	}
});
function getActual(){
	selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
	rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
	var actamount=0;
	// $.each(rowData, function( index, value ) {
	// 	// if(value){
	// 	// 	$('#addChartAcc #'+index+' input').text(numeral(value).format('0,0.00'))
	// 	// }else{
	// 	// 	$('#addChartAcc #'+index+' input').text("0.00");
	// 	// }
	// 	// console.log(value);
	// });
}

function getTotalActual(){
	mycurrency.formatOn();

	selrow = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
	rowdata = $("#jqGrid").jqGrid ('getRowData', selrow);
	var total=0;
	var actamount=0;
	$.each(rowdata, function( index, value ) {
		if(!isNaN(parseFloat(value)) && index.indexOf('actamount') !== -1){
			total+=parseFloat(value);
		}
	});
	$('#totalActual').val(numeral(total).format('0,0.00'));
}
