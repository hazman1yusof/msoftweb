$(document).ready(function () {


	$("#phys a.item[data-tab=ordentry]").on('click', function () {
		delay(function(){
			$("#jqGrid_trans_phys").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_phys_c")[0].offsetWidth-$("#jqGrid_trans_phys_c")[0].offsetLeft-14));
		}, 50 );
	});

	// var fdl = new faster_detail_load();
	$("#jqGrid_trans_phys").jqGrid({
		datatype: "local",
		editurl: "./doctornote_transaction_save",
		colModel: [
			{ label: 'id', name: 'id', hidden: true,key:true },
			{ label: 'chg_code', name: 'chg_code', hidden: true },
			{ label: 'isudept', name: 'isudept', hidden: true },
			{ label: 'Code', name: 'chg_desc', width: 40, editable:true, classes: 'wrap',
				editrules:{required: true, custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
				    {  custom_element:chgcodeCustomEdit,
				       custom_value:galGridCustomValue 	
				    },
			},
			{ label: 'Qty', name: 'quantity', width: 35 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules},
				formatter: 'number',formatoptions:{decimalPlaces: 0, defaultValue: '1'}},
			{ label: 'Remarks', name: 'remarks', width: 80, classes: 'wrap', editable:true,edittype:'textarea',editoptions: { rows: 4 }},
			{ label: 'dos_code', name: 'dos_code', hidden: true },
			{ label: 'Dosage', name: 'dos_desc', classes: 'wrap', width: 40 , editable:true,
				editrules:{required: false},
				edittype:'custom',	editoptions:
				    {  custom_element:doscodeCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
			{ label: 'fre_code', name: 'fre_code', hidden: true },
			{ label: 'Frequency', name: 'fre_desc', classes: 'wrap', width: 40 , editable:true,
				editrules:{required: false},
				edittype:'custom',	editoptions:
				    {  custom_element:frequencyCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
			{ label: 'ins_code', name: 'ins_code', hidden: true },
			{ label: 'Instruction', name: 'ins_desc', classes: 'wrap', width: 40 , editable:true,
				editrules:{required: false},
				edittype:'custom',	editoptions:
				    {  custom_element:instructionCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
			{ label: 'dru_code', name: 'dru_code', hidden: true },
			{ label: 'Indicator', name: 'dru_desc', classes: 'wrap', width: 40 , editable:true,
				editrules:{required: false},
				edittype:'custom',	editoptions:
				    {  custom_element:drugindicatorCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
		],
		autowidth: false,
		viewrecords: true,
		width: 900,
		height: 365,
		rowNum: 30,
		pager:'#jqGrid_transPager_phys',
		viewrecords: true,
		loadonce:false,
		scroll: true,
		sortname: 'id',
		sortorder: "desc",
		onSelectRow:function(rowid, selected){

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			if($('td#jqGrid_trans_phys_iledit').is(':visible')){
				$('td#jqGrid_trans_phys_iledit').click();
			}
		},
		loadComplete: function () {
        	$('#jqGrid_trans_phys_ildelete').removeClass('ui-disabled');
			if(addmore_onadd_phys == true){
				$('#jqGrid_trans_phys_iladd').click();
			}
		},
	});
	addParamField('#jqGrid_trans_phys',false,urlParam_trans_phys,[]);
	jqgrid_label_align_right('#jqGrid_trans_phys');

	var myEditOptions_phys_add = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {
        	addmore_onadd_phys = true;
        	let selrow = selrowData('#jqGrid');
        	$('#jqGrid_trans_phys_ildelete').addClass('ui-disabled');

			$("#jqGrid_trans_phys input[name='chgcode'],#jqGrid_trans_phys input[name='dosecode'],#jqGrid_trans_phys input[name='freqcode'],#jqGrid_trans_phys input[name='inscode'],#jqGrid_trans_phys input[name='drugindcode']").on('keydown',{data:this},onTab);

        },
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_trans_phys", urlParam_trans_phys);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans_phys');

			let editurl = "./doctornote_transaction_save?"+
				$.param({
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		trxdate: $('#sel_date').val(),
		    		isudept: 'phys',
				});


			$("#jqGrid_trans_phys").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
        	$('#jqGrid_trans_phys_ildelete').removeClass('ui-disabled');
	    }
    };

    var myEditOptions_phys_edit = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {

        	let selrow = selrowData('#jqGrid');
        	let selrow_tran = selrowData('#jqGrid_trans_phys');
        	$('#jqGrid_trans_phys_ildelete').addClass('ui-disabled');

        	$("#jqGrid_trans_phys input[name='chgcode']").val(selrow_tran.chg_code);
        	$("#jqGrid_trans_phys input[name='inscode']").val(selrow_tran.ins_code);
        	$("#jqGrid_trans_phys input[name='dosecode']").val(selrow_tran.dos_code);
        	$("#jqGrid_trans_phys input[name='freqcode']").val(selrow_tran.fre_code);
        	$("#jqGrid_trans_phys input[name='drugindcode']").val(selrow_tran.dru_code);

			$("#jqGrid_trans_phys input[name='chgcode'],#jqGrid_trans_phys input[name='dosecode'],#jqGrid_trans_phys input[name='freqcode'],#jqGrid_trans_phys input[name='inscode'],#jqGrid_trans_phys input[name='drugindcode']").on('keydown',{data:this},onTab);
        },
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_trans_phys", urlParam_trans_phys);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans_phys');

			let editurl = "./doctornote_transaction_save?"+
				$.param({
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		id: selrow_trans.id,
		    		trxdate: $('#sel_date').val(),
		    		isudept: 'phys',
				});


			$("#jqGrid_trans_phys").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
        	$('#jqGrid_trans_phys_ildelete').removeClass('ui-disabled');
	    }
    };

	$("#jqGrid_trans_phys").inlineNav('#jqGrid_transPager_phys',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_phys_add
		},
		editParams: myEditOptions_phys_edit
	}).jqGrid('navButtonAdd', "#jqGrid_transPager_phys", {	
		id: "jqGrid_trans_phys_ildelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			var rowid = $("#jqGrid_trans_phys").jqGrid('getGridParam', 'selrow');	
			if (!rowid) {	
				alert('Please select row');	
			} else {
	        	let selrow = selrowData('#jqGrid');
	        	let selrow_phys = selrowData('#jqGrid_trans_phys');
				$.confirm({
				    title: 'Confirm',
				    content: 'Are you sure you want to delete this row?',
				    buttons: {
				        confirm:{
				        	btnClass: 'btn-blue',
				        	action: function () {
					        	var param = {
									_token: $("#_token").val(),
									mrn: selrow.MRN,
						    		episno: selrow.Episno,
						    		id: selrow_phys.id,
						    		oper: 'del'
								}

								$.post( "./doctornote_transaction_save",param, function( data ){
									addmore_onadd_phys = false;
									refreshGrid("#jqGrid_trans_phys", urlParam_trans_phys);
								},'json');
					         }

				        },
				        cancel: {
				        	action: function () {
								
					        },
				        }
				    }

				});
			}	
		},	
	});

	hide_tran_button_phys(true);

    function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Code':temp=$('table#jqGrid_trans_phys input[name=chgcode]');break;
			case 'Qty':
					let quan=$('table#jqGrid_trans_phys input[name=quantity]').val();
					if(parseInt(quan) <= 0){
						return [false,"Quantity need to be greater than 0"];
					}else{
						return [true,''];
					}
			break;
		}
		return(temp.val() == '')?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function chgcodeCustomEdit(val,opt){
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans_phys" optid="`+opt.rowId+`" id="`+opt.id+`" name="chgcode" type="text" mytype="chgcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('chgcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function instructionCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans_phys" optid="`+opt.rowId+`" id="`+opt.id+`" name="inscode" type="text" mytype="inscode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('inscode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function doscodeCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans_phys" optid="`+opt.rowId+`" id="`+opt.id+`" name="dosecode" type="text" mytype="dosecode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('dosecode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function frequencyCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans_phys" optid="`+opt.rowId+`" id="`+opt.id+`" name="freqcode" type="text" mytype="freqcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('freqcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function drugindicatorCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans_phys" optid="`+opt.rowId+`" id="`+opt.id+`" name="drugindcode" type="text" mytype="drugindcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('drugindcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

    function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	var errorField = [];

});

var addmore_onadd_phys = false;
var urlParam_trans_phys = {
	url:'./doctornote/table',
	isudept:'phys',
	action: 'get_transaction_table',
}

function hide_tran_button_phys(hide=true){
	if(hide){
		$('#jqGrid_trans_phys_iladd,#jqGrid_trans_phys_iledit,#jqGrid_trans_phys_ilsave,#jqGrid_trans_phys_ilcancel,#jqGrid_trans_phys_ildelete').hide();
	}else{
		$('#jqGrid_trans_phys_iladd,#jqGrid_trans_phys_iledit,#jqGrid_trans_phys_ilsave,#jqGrid_trans_phys_ilcancel,#jqGrid_trans_phys_ildelete').show();
	}
}

function onTab(event){
    var obj = event.data.data;
    var textfield = $(event.currentTarget);
    var type = textfield.attr('mytype');
    var id_ = textfield.attr('id');
    var optid = textfield.attr('optid');

    if(event.key == "Tab" && textfield.val() != ""){
        obj.blurring = true;
        $('#mdl_item_selector').modal('show');
        pop_item_select(type,id_,optid,true);
    }
}

function get_trans_tbl_data(){
	var data = $('#jqGrid_trans_phys').jqGrid('getRowData');

	datable_medication.clear().draw();

	datable_medication.rows.add(data).draw();

}


function empty_transaction_phys(kosongkan = 'kosongkan'){
	addmore_onadd_phys = false
	hide_tran_button_phys(true);
	refreshGrid("#jqGrid_trans_phys", urlParam_trans_phys,kosongkan);
}
