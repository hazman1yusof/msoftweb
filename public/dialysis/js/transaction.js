
var curpage_tran=null; // to prevent duplicate entry 
$(document).ready(function () {

	// var fdl = new faster_detail_load();
	$("#jqGrid_trans").jqGrid({
		datatype: "local",
		editurl: "./dialysis_doctornote_transaction_save",
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
		width: 900,
		height: 80,
		rowNum: 30,
		pager:'#jqGrid_transPager',
		viewrecords: true,
		loadonce:false,
		scroll: true,
		sortname: 'id',
		sortorder: "desc",
		onSelectRow:function(rowid, selected){
			calc_jq_height_onchange("jqGrid_trans");
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			if($('td#jqGrid_trans_iledit').is(':visible')){
				$('td#jqGrid_trans_iledit').click();
			}
		},
		loadComplete: function () {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
			// get_trans_tbl_data();
        	$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
			if(addmore_onadd == true){
				$('#jqGrid_trans_iladd').click();
			}
			// if($('#jqGrid_transPager_right').data('loaded') == undefined){
			// 	let button = `
			// 					<button type="button" class="btn btn-sm btn-success" id="discharge_btn">Patient Complete</button>
			// 				`;
			// 	$('#jqGrid_transPager_right').html(button);
			// 	$('#jqGrid_transPager_right').data('loaded','loaded');

			// 	$('#discharge_btn').click(function(){
			// 		let episno = selrowData('#jqGrid').Episno;
			// 		let mrn = selrowData('#jqGrid').MRN;
			// 		let idno = selrowData('#jqGrid').idno;
			// 		if(episno != undefined || episno != null ){
			// 			var r = confirm("Do you want to complete this patient order?");
			// 			if (r == true) {

			// 				$('button#discharge_btn').data('idno',idno);

			// 				var postobj={
		 //    					_token : $('#_token').val(),
			// 			        episno: episno,
			// 			        mrn: mrn,
			// 			        reg_date: $('#sel_date').val()
			// 			    }

			// 				$.post( "./dialysis_doctornote/form?action=submit_patient", postobj , function( data ) {
		        	
			// 			    },'json').done(function(data) {
			// 			    	SmoothScrollToTop();
			// 			        refreshGrid("#jqGrid");
			// 			    }).fail(function(data){
						        
			// 			    });
			// 			}
			// 		}
					
			// 	});
			// }
			// fdl.set_array().reset();
		},
		beforeProcessing: function(data, status, xhr){
			if(curpage_tran == data.page){
				return false;
			}else{
				curpage_tran = data.page;
			}
		}
	});
	addParamField('#jqGrid_trans',false,urlParam_trans,[]);
	jqgrid_label_align_right('#jqGrid_trans');

	// $("#tab_trans").on("shown.bs.collapse", function(){
	// 	SmoothScrollTo('#tab_trans', 300);
	// 	$("#jqGrid_trans").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
	// });


	// $("#jqGrid_trans").jqGrid('navGrid', '#jqGrid_transPager', {
	// 	view: false, edit: true, add: true, del: false, search: false,
	// 	beforeRefresh: function () {
	// 		refreshGrid("#jqGrid", urlParam);
	// 	},
	// });

	var myEditOptions_add = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {
        	addmore_onadd = true;
        	let selrow = selrowData('#jqGrid');
        	$('#jqGrid_trans_ildelete').addClass('ui-disabled');

			$("#jqGrid_trans").jqGrid("setRowData", rowid, {
					t_trxdate:$('#sel_date').val(),
					t_trxtime:moment().format("hh:mm A"),
					t_isudept:$('#user_dept').val()
				});

			$("#jqGrid_trans input[name='chgcode'],#jqGrid_trans input[name='dosecode'],#jqGrid_trans input[name='freqcode'],#jqGrid_trans input[name='inscode'],#jqGrid_trans input[name='drugindcode']").on('keydown',{data:this},onTab);

			// $("input[name='t_quantity']").keydown(function(e) {//when click tab, auto save
			// 	var code = e.keyCode || e.which;
			// 	if (code == '9')$('#jqGrid_trans_ilsave').click();
			// });
        },
        aftersavefunc: function (rowid, response, options) {
        	curpage_tran = null;
			refreshGrid("#jqGrid_trans", urlParam_trans);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans');

			let editurl = "./dialysis_doctornote_transaction_save?"+
				$.param({
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		trxdate: $('#sel_date').val(),
		    		isudept: 'CLINIC',
				});


			$("#jqGrid_trans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
        	$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
	    }
    };

    var myEditOptions_edit = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {

        	let selrow = selrowData('#jqGrid');
        	let selrow_tran = selrowData('#jqGrid_trans');
        	$('#jqGrid_trans_ildelete').addClass('ui-disabled');

        	$("#jqGrid_trans input[name='chgcode']").val(selrow_tran.chg_code);
        	$("#jqGrid_trans input[name='inscode']").val(selrow_tran.ins_code);
        	$("#jqGrid_trans input[name='dosecode']").val(selrow_tran.dos_code);
        	$("#jqGrid_trans input[name='freqcode']").val(selrow_tran.fre_code);
        	$("#jqGrid_trans input[name='drugindcode']").val(selrow_tran.dru_code);

			$("#jqGrid_trans input[name='chgcode'],#jqGrid_trans input[name='dosecode'],#jqGrid_trans input[name='freqcode'],#jqGrid_trans input[name='inscode'],#jqGrid_trans input[name='drugindcode']").on('keydown',{data:this},onTab);
        },
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_trans", urlParam_trans);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans');

			let editurl = "./dialysis_doctornote_transaction_save?"+
				$.param({
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		id: selrow_trans.id,
		    		trxdate: $('#sel_date').val(),
		    		isudept: 'CLINIC',
				});


			$("#jqGrid_trans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
        	$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
	    }
    };

	$("#jqGrid_trans").inlineNav('#jqGrid_transPager',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_add
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGrid_transPager", {	
		id: "jqGrid_trans_ildelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			var rowid = $("#jqGrid_trans").jqGrid('getGridParam', 'selrow');	
			if (!rowid) {	
				alert('Please select row');	
			} else {
	        	let selrow = selrowData('#jqGrid');
	        	let selrow_trans = selrowData('#jqGrid_trans');
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
						    		id: selrow_trans.id,
						    		oper: 'del'
								}

								$.post( "./dialysis_doctornote_transaction_save",param, function( data ){
									refreshGrid("#jqGrid_trans", urlParam_trans);
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

	hide_tran_button(true);

 //    function showdetail(cellvalue, options, rowObject){
	// 	var field,table,case_;
	// 	switch(options.colModel.name){
	// 		case 't_chgcode':field=['chgcode','description'];table="chgmast";case_='chgcode';break;
	// 	}
	// 	var param={action:'input_check',url:'./dialysis_util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};

	// 	fdl.get_array('deliveryOrder',options,param,case_,cellvalue);
	// 	// faster_detail_array.push(faster_detail_load('deliveryOrder',options,param,case_,cellvalue));
		
	// 	return cellvalue;
	// }

    function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Code':temp=$('table#jqGrid_trans input[name=chgcode]');break;
			case 'Qty':
					let quan=$('table#jqGrid_trans input[name=quantity]').val();
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
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="chgcode" type="text" mytype="chgcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('chgcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function instructionCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="inscode" type="text" mytype="inscode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('inscode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function doscodeCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="dosecode" type="text" mytype="dosecode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('dosecode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function frequencyCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="freqcode" type="text" mytype="freqcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('freqcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function drugindicatorCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="drugindcode" type="text" mytype="drugindcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('drugindcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
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

var addmore_onadd = false;
var urlParam_trans = {
	url:'./dialysis_doctornote/table',
	isudept:'CLINIC',
	action: 'get_transaction_table',
}

function hide_tran_button(hide=true){
	if(hide){
		$('#jqGrid_trans_iladd,#jqGrid_trans_iledit,#jqGrid_trans_ilsave,#jqGrid_trans_ilcancel,#jqGrid_trans_ildelete').hide();
	}else{
		$('#jqGrid_trans_iladd,#jqGrid_trans_iledit,#jqGrid_trans_ilsave,#jqGrid_trans_ilcancel,#jqGrid_trans_ildelete').show();
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

function pop_item_select(type,id,rowid,ontab=false){ 
    var act = null;
    var id = id;
    var rowid = rowid;
    var selecter = null;
    var title="Item selector";
    var mdl = null;
    var text_val = $('input#'+id).val();
        
    act = get_url(type);

	$('#mdl_item_selector').modal({
		'closable':false,
		onHidden : function(){
        	switch(id){
	        	case rowid+'_chg_desc':
    	        	delay(function(){
    					$('#jqGrid_trans input#'+rowid+'_quantity').select().focus();
    				}, 10 );
	        		break;
	        	case rowid+'_dos_desc':
    	        	delay(function(){
    					$('#jqGrid_trans input#'+rowid+'_fre_desc').select().focus();
    				}, 10 );
	        		break;
	        	case rowid+'_fre_desc':
    	        	delay(function(){
    					$('#jqGrid_trans input#'+rowid+'_ins_desc').select().focus();
    				}, 10 );
	        		break;
	        	case rowid+'_ins_desc':
    	        	delay(function(){
    					$('#jqGrid_trans input#'+rowid+'_dru_desc').select().focus();
    				}, 10 );
	        		break;
	        	case rowid+'_dru_desc':
	        		break;
	        }
	        $('#tbl_item_select').html('');
	        selecter.destroy();
	    },
	}).modal('show');
	$('body,#mdl_item_selector').addClass('scrolling');
    
    selecter = $('#tbl_item_select').DataTable( {
            "ajax": "./dialysis_doctornote/table?action=" + act,
            "ordering": false,
            "lengthChange": false,
            "info": true,
            "pagingType" : "numbers",
            "search": {
                        "smart": true,
                        "search": text_val
                      },
            "columns": [
                        {'data': 'code'}, 
                        {'data': 'description'},
                       ],

            "columnDefs": [ {
            	"width": "20%",
                "targets": 0,
                "data": "code",
                "render": function ( data, type, row, meta ) {
                    return data;
                }
              } ],

            "initComplete": function(oSettings, json) {
		        delay(function(){
                	$('div.dataTables_filter input', selecter.table().container()).get(0).focus();
	        	}, 10 );
            },
    });


    
    // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
    $('#tbl_item_select tbody').on('click', 'tr', function () {
        // $('#txt_' + type).removeClass('error myerror').addClass('valid');
        // setTimeout(function(type){
        //     $('#txt_' + type).removeClass('error myerror').addClass('valid'); 
        // }, 1000,type);
        
        // $('#hid_' + type).val(item["code"]);
        // $('#txt_' + type).val(item["description"]);
        item = selecter.row( this ).data();
        $('input[name='+type+'][optid='+rowid+']').val(item["code"]);
        $('input[name='+type+'][optid='+rowid+']').parent().next().html(item["description"])
        // $("#jqGrid_trans").jqGrid('setRowData', rowid ,{m_description:item["description"]});
            
        $('#mdl_item_selector').modal('hide');
    });
        
    // $("#mdl_item_selector").on('hidden.bs.modal', function () {
    //     $('#tbl_item_select').html('');
    //     selecter.destroy();
        
    //     type = "";
    //     item = "";
    //     // obj.blurring = true;
    // });
}

function get_url(type){
    let act = null;
    switch (type){
        case "chgcode":
            act = "get_chgcode";
            break;
        case "drugindcode":
            act = "get_drugindcode";
            break;
        case "freqcode":
            act = "get_freqcode";
            break;
        case "dosecode":
            act = "get_dosecode";
            break;
        case "inscode":
            act = "get_inscode";
            break;
    }
    return act;
}

function get_trans_tbl_data(){
	var data = $('#jqGrid_trans').jqGrid('getRowData');

	datable_medication.clear().draw();

	datable_medication.rows.add(data).draw();

}

function empty_transaction(kosongkan = 'kosongkan'){
	addmore_onadd = false;
	hide_tran_button(true);
	refreshGrid("#jqGrid_trans", urlParam_trans,kosongkan);
}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}