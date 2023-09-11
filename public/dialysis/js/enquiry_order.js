
var curpage_tran=null; // to prevent duplicate entry 
$(document).ready(function () {

 	// $('#filterdate').click(function(){
 	// 	if( $('#mrn').val() == ''){
 	// 		alert('Pick patient first');
 	// 	}else{
		// 	urlParam_trans.mrn = $('#mrn').val();
		// 	urlParam_trans.trxdate = moment($('#month_year_calendar').calendar('get date')).format('YYYY-MM-DD');
		// 	refreshGrid("#jqGrid_trans", urlParam_trans);
 	// 	}
 	// });

	$("#tab_trans").on("show.bs.collapse", function(){
		addmore_onadd = false;
		closealltab("#tab_trans");
		hide_tran_button(true);

		if( $('#mrn').val() == ''){
 			alert('Pick patient first');
 		}else{
			curpage_tran = null;
			urlParam_trans.mrn = $('#mrn').val();
			urlParam_trans.epismonth = moment($('#month_year_calendar').calendar('get date')).format('M');
			urlParam_trans.episyear = moment($('#month_year_calendar').calendar('get date')).format('YYYY');
			urlParam_trans.episno = $('#episno').val();
			refreshGrid("#jqGrid_trans", urlParam_trans);
			hide_tran_button(false);
		}
	});

	$("#tab_trans").on("shown.bs.collapse", function(){
		$('#medicationtype').dropdown('set selected', selrowData('#jqGrid').packagecode);
		SmoothScrollTo('#tab_trans', 300,function(){
			$("#jqGrid_trans").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
			calc_jq_height_onchange("jqGrid_trans");
		},90);
	});

	// var fdl = new faster_detail_load();
	$("#jqGrid_trans").jqGrid({
		datatype: "local",
		editurl: "./dialysis_transaction_save",
		colModel: [
			{ label: 'id', name: 'id', hidden: true,key:true },
			{ label: 'chg_code', name: 'chg_code', hidden: true },
			{ label: 'isudept', name: 'isudept', hidden: true },
			{ label: 'Item', name: 'chg_desc', width: 40, editable:true, classes: 'wrap',
				editrules:{required: true, custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
				    {  custom_element:chgcodeCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
			{ label: 'Date', name: 'trxdate', width: 30 , editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d-m-Y'},
				editoptions: {
                    dataInit: function (element) {
                        $(element).datepicker({
                            id: 'trxdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            showOn: 'focus',
                            changeMonth: true,
		  					changeYear: true,
							onSelect : function(){
								// $(this).focus();
							}
                        });
                    }
                }
			},
			{ label: 'Time', name: 'trxtime', width: 30 , editable:true,edittype:'custom',editoptions:
				    {  custom_element:trxtimeCustomEdit,
				       custom_value:galGridCustomValue 	
				    },},
			{ label: 'Qty', name: 'quantity', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules},
				formatter: 'number',formatoptions:{decimalPlaces: 0, defaultValue: '1'}},
			{ label: 'Remarks', name: 'remarks', width: 65, classes: 'wrap', editable:true,edittype:'textarea',editoptions: { rows: 4 }},
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
			{ label: 'patmedication', name: 'patmedication', hidden: true },
			{ label: 'Entered<br/>Date', name: 'lastupdate', width: 30 ,formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d-m-Y'}},
		],
		autowidth: false,
		width: 900,
		height: 50,
		rowNum: 30,
		pager:'#jqGrid_transPager',
		viewrecords: true,
		loadonce:false,
		scroll: true,
		sortname: 'id',
		sortorder: "desc",
		onSelectRow:function(rowid, selected){
        	$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
			calc_jq_height_onchange("jqGrid_trans");
			// if(!$('#jqGrid_trans_iladd').hasClass('ui-disabled') && selrowData('#jqGrid_trans').patmedication != '1'){
			// 	var trxdate = selrowData('#jqGrid_trans').trxdate;
			// 	if(moment().isSame(moment(trxdate, "DD-MM-YYYY"), 'day')){
   //      			$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
			// 	}

			// 	if($('#viewallcenter').val() == 1){
   //      			$('#jqGrid_trans_ildelete').removeClass('ui-disabled');
			// 	}
			// }
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {

		},
		onSortCol: function(index,iCol,sortorder){
			curpage_tran = null;
		},
		gridComplete:function(){
			calc_jq_height_onchange("jqGrid_trans");
		},
		loadComplete: function () {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
			if(addmore_onadd == true){
				$('#jqGrid_trans_iladd').click();
			}
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

	var myEditOptions_add = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {
        	calc_jq_height_onchange("jqGrid_trans");
        	addmore_onadd = true;
        	$("#jqGrid_trans input[name='trxdate']").val(moment().format("YYYY-MM-DD"));
        	$("#jqGrid_trans input[name='trxtime']").val(moment().format("HH:mm:ss"));

        	$('#jqGrid_trans_ildelete').addClass('ui-disabled');

			$("#jqGrid_trans").jqGrid("setRowData", rowid, {
					t_trxdate:$('#sel_date').val(),
					t_trxtime:moment().format("hh:mm A"),
					t_isudept:$('#user_dept').val()
				});

			$("#jqGrid_trans input[name='chgcode'],#jqGrid_trans input[name='dosecode'],#jqGrid_trans input[name='freqcode'],#jqGrid_trans input[name='inscode']").on('keydown',{data:this},onTab);
        },
        aftersavefunc: function (rowid, response, options) {
        	curpage_tran = null;
			refreshGrid("#jqGrid_trans", urlParam_trans);
        }, 
        errorfunc: function(rowid,response){
 			// console.log(response);
	        alert(response.responseText);
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans');

			let editurl = "./dialysis_transaction_save?"+
				$.param({
					dialysis_episode_idno: '',
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		isudept: 'CLINIC',
		    		mode: 'enquiry',
				});


			$("#jqGrid_trans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
        	// $('#jqGrid_trans_ildelete').removeClass('ui-disabled');
	    }
    };

    var myEditOptions_edit = { // sepatutnya takkan diguna
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
        	$("#jqGrid_trans input[name='dosecode']").val(selrow_tran.dos_code);
        	$("#jqGrid_trans input[name='freqcode']").val(selrow_tran.fre_code);
        	$("#jqGrid_trans input[name='inscode']").val(selrow_tran.ins_code);

			$("#jqGrid_trans input[name='chgcode'],#jqGrid_trans input[name='dosecode'],#jqGrid_trans input[name='freqcode']").on('keydown',{data:this},onTab);
        },
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_trans", urlParam_trans);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow = selrowData('#jqGrid');
        	let selrow_trans = selrowData('#jqGrid_trans');

			let editurl = "./dialysis_transaction_save?"+
				$.param({
					mrn: selrow.MRN,
		    		episno: selrow.Episno,
		    		id: selrow_trans.id,
		    		isudept: 'CLINIC',
				});


			$("#jqGrid_trans").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
			$("#jqGrid_trans").setSelection($("#jqGrid_trans").getDataIDs()[0]);
	    }
    };

	$("#jqGrid_trans").inlineNav('#jqGrid_transPager',{	
		add:true,
		edit:false,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_add
		},
		editParams: myEditOptions_edit
	})
	.jqGrid('navButtonAdd', "#jqGrid_transPager", {	
		id: "jqGrid_trans_ildelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash error",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			var rowid = $("#jqGrid_trans").jqGrid('getGridParam', 'selrow');	
			if (!rowid) {	
				alert('Please select row');	
			} else {
				let selrow_trans = selrowData('#jqGrid_trans');
				$.confirm({
				    title: 'Confirm',
				    content: 'Are you sure you want to delete this item? <span class="error">'+selrow_trans.chg_desc+'</error>',
				    buttons: {
				        confirm:{
				        	btnClass: 'btn-blue',
				        	action: function () {

					        	emptyFormdata([],'form#verify_form');
						  		$('#verify_btn').off();
						  		$('#verify_btn').on('click',function(){
									if($("form#verify_form").valid()) {
						  				verifyuser_delete();
									}
						  		});
						  		$('#password_mdl').modal('show');
						  		$('body,#password_mdl').addClass('scrolling');
						  		$('#verify_error').hide();

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

    function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Item':temp=$('table#jqGrid_trans input[name=chgcode]');break;
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

	function trxtimeCustomEdit(val,opt){
		val = (val == "undefined") ? "" : val;
		return $(`<div class="ui input"><input type="time" name="trxtime" ></div>`);
	}

	function chgcodeCustomEdit(val,opt){
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="chgcode" type="text" mytype="chgcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('chgcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function doscodeCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="dosecode" type="text" mytype="dosecode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('dosecode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function frequencyCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="freqcode" type="text" mytype="freqcode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('freqcode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
	}

	function instructionCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		return $(`<div class="input-group"><input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="inscode" type="text" mytype="inscode" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0" ><a class="input-group-addon btn btn-primary" onclick="pop_item_select('inscode','`+opt.id+`','`+opt.rowId+`',true);"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block wrap"></span>`);
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

	// $('#medicationtype_button').on('click',function(){

	// 	if($('#dialysis_episode_idno').val().trim() != ''){
	//         loader_transaction(true);
	// 		var param = {
	// 			action: 'medicationtype_change',
	// 			_token: $("#_token").val(),
	// 			mrn: $("#mrn").val(),
	// 			episno: $("#episno").val(),
	// 			dialysis_episode_idno: $('#dialysis_episode_idno').val(),
	// 			packagecode: $('#medicationtype').val()
	// 		}

	// 		$.post( "./dialysis/form",param, function( data ){
	// 		},'json').fail(function(data) {
	//             alert(data.responseText);
	//             loader_transaction(false);
	//         }).done(function(data){
	//             loader_transaction(false);
	//         });
	// 	}

		
	// });

});

var addmore_onadd = false;
var urlParam_trans = {
	url:'./enquiry/table',
	isudept:'CLINIC',
	action: 'get_enquiry_order',
	mrn:'',
	episno:'',
	trxdate:''
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
            "ajax": "./dialysis/table?action=" + act,
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
                        {'data': 'doseqty'},
                        {'data': 'dosecode'},
                        {'data': 'dosecode_'},
                        {'data': 'freqcode'},
                        {'data': 'freqcode_'},
                        {'data': 'instruction'},
                        {'data': 'instruction_'},
                       ],

            "columnDefs": [ {
	            	"width": "20%",
	                "targets": 0,
	                "data": "code",
	                "render": function ( data, type, row, meta ) {
	                    return data;
	                }
	              },{
	                "targets": 2,visible: false,searchable: false,
	              },{
	                "targets": 3,visible: false,searchable: false,
	              },{
	                "targets": 4,visible: false,searchable: false,
	              },{
	                "targets": 5,visible: false,searchable: false,
	              },{
	                "targets": 6,visible: false,searchable: false,
	              },{
	                "targets": 7,visible: false,searchable: false,
	              },{
	                "targets": 8,visible: false,searchable: false,
	              }

            ],

            "initComplete": function(oSettings, json) {
		        delay(function(){
                	$('div.dataTables_filter input', selecter.table().container()).get(0).focus();
	        	}, 10 );
            },
    });


    
    // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
    $('#tbl_item_select tbody').on('click', 'tr', function () {
        item = selecter.row( this ).data();
        $('input[name='+type+'][optid='+rowid+']').val(item["code"]);
        $('input[name='+type+'][optid='+rowid+']').parent().next().html(item["description"]);
        if(type == "chgcode"){
	        $('input[name=quantity][optid='+rowid+']').val(item["doseqty"]);

	        $('input[name=dosecode][optid='+rowid+']').val(item["dosecode"]);
	        $('input[name=dosecode][optid='+rowid+']').parent().next().html(item["dosecode_"]);

	        $('input[name=freqcode][optid='+rowid+']').val(item["freqcode"]);
	        $('input[name=freqcode][optid='+rowid+']').parent().next().html(item["freqcode_"]);

	        $('input[name=inscode][optid='+rowid+']').val(item["instruction"]);
	        $('input[name=inscode][optid='+rowid+']').parent().next().html(item["instruction_"]);
        }
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
            act = "get_chgcode&mrn="+$("#mrn").val()+"&episno="+$("#episno").val()+"&arrivalno=";
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

function verifyuser_delete(){
	var param={
		action:'verifyuser',
		username:$('#username_verify').val(),
		password:$('#password_verify').val(),
    };

    $.get( "./verifyuser_admin_dialysis?"+$.param(param), function( data ) {

    },'json').done(function(data) {
    	if(data.success == 'fail'){
  			$('#verify_error').show();
    	}else{
    		deleting();
  			$('#verify_error').hide();
  			$('#password_mdl').modal('hide');
    	}
    }).fail(function(data){
        alert('error verify');
    });
}

function deleting(){
	let selrow_trans = selrowData('#jqGrid_trans');
	var param = {
		_token: $("#_token").val(),
		id: selrow_trans.id,
		mrn: $("#mrn").val(),
		episno: $("#episno").val(),
		dialysis_episode_idno: '',
		oper: 'del'
	}

	$.post( "./dialysis_transaction_save",param, function( data ){
		curpage_tran = null;
		addmore_onadd = false;
		refreshGrid("#jqGrid_trans", urlParam_trans);
	},'json');
}

function loader_transaction(load){
	if(load){
		$('#loader_transaction').addClass('active');
	}else{
		$('#loader_transaction').removeClass('active');
	}
}
