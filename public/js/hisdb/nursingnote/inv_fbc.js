
var urlParam_fbc={
	action:'inv_table',
	url:'./nursingnote/table',
	inv_code: 'FBC',
	mrn:'',
	episno:''
};

$(document).ready(function(){

	$("#jqGridInvestigation_fbc").jqGrid({
		datatype: "local",
		editurl: "nursingnote/form",
		colModel: [
			{ label: 'inv_cat', name: 'inv_cat', width: 30, classes: 'wrap'},
            { label: 'Start Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true,
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' },
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'dd-mm-yy',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true,
                editrules: { required: false, custom: true, custom_func: cust_rules_fbc }, edittype: 'custom',
                editoptions: {
                    custom_element: enteredtimeCustomEdit_fbc,
                    custom_value: galGridCustomValue_fbc
                }
            },
			{ label: 'Value', name: 'value_fbc', width: 35, editable: true, editrules: { required: true } },
            { label: 'Entered By', name: 'enteredby', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
			{ label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },

        ],
		autowidth: true,
		shrinkToFit: true,
		multiSort: false,
		viewrecords: true,
		loadonce: false,
		width: 1500,
		height: 200,
	    rowNum: 30,
	    pgbuttons: false,
	    pginput: false,
	    pgtext: "",
		sortname: 'id',
		sortorder: "desc",
		pager: "#jqGridPagerInvestigation_fbc",
		gridview: true,
		// rowattr:function(data){
		// 	let trxtype = data.trxtype;
		//     if (trxtype == 'PD') {
		//         return {"class": "tr_pdclass"};
		//     }
		// },
		loadComplete: function(data){
			calc_jq_height_onchange("jqGridInvestigation_fbc",false,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
			
			if($("#jqGridInvestigation_fbc").data('lastselrow')==undefined||$("#jqGridInvestigation_fbc").data('lastselrow')==null||$("#jqGridInvestigation_fbc").data('lastselrow').includes("jqg")){
				$("#jqGridInvestigation_fbc").setSelection($("#jqGridInvestigation_fbc").getDataIDs()[0]);
			}else{
				$("#jqGridInvestigation_fbc").setSelection($("#jqGridInvestigation_fbc").data('lastselrow'));
			}
			$("#jqGridInvestigation_fbc").data('lastselrow',null);
		},
		gridComplete: function(){
			// fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		},
		beforeSelectRow:function(rowid, e){
		},
		onSelectRow:function(rowid){
			$('#jqGridInvestigation_fbc_iledit,#jqGridPagerInvestigation_fbcDelete').hide();
			if($('#jqGridInvestigation_fbc_iladd').hasClass('ui-disabled')){
				$('#jqGridInvestigation_fbc_iledit,#jqGridPagerInvestigation_fbcDelete').hide();
			}else if(selrowData('#jqGridInvestigation_fbc').trxtype == 'OE' || selrowData('#jqGridInvestigation_fbc').trxtype == 'PK'){
				$('#jqGridInvestigation_fbc_iledit,#jqGridPagerInvestigation_fbcDelete').show();
			}
		},
		ondblClickRow: function(rowId) {
			if(selrowData('#jqGridInvestigation_fbc').trxtype != 'PD'){
				$('#jqGridInvestigation_fbc_iledit').click();
			}
		},
		subGridBeforeExpand(pID, id){
			if($("#jqGridInvestigation_fbc").data('lastselrow')==id){
				return true;
			}else if($('#jqGridInvestigation_fbc_iladd').hasClass('ui-disabled')){
				return false;
			}
		},
    });
	jqgrid_label_align_right("#jqGridInvestigation_fbc");
	
	$("#jqGridInvestigation_fbc").inlineNav('#jqGridPagerInvestigation_fbc', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_fbc
		},
		editParams: myEditOptions_fbc_edit,
			
	}).jqGrid('navButtonAdd', "#jqGridPagerInvestigation_fbc", {	
		id: "jqGridPagerInvestigation_fbcDelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			selRowId = $("#jqGridInvestigation_fbc").jqGrid('getGridParam', 'selrow');	
			if(selrowData('#jqGridInvestigation_fbc').trxtype == 'PD'){
				return false;
			}
			if (!selRowId) {	
				alert('Please select row');
			} else {

				if (confirm("Are you sure you want to delete this row?") == true) {
				    let urlparam = {	
						action: 'inv_fbc',	
						oper: 'del',	
					};
					let urlobj={
						oper:'del',
						_token: $("#csrf_token").val(),
						id: selrowData('#jqGridInvestigation_fbc').id
					};
					$.post( "./nursingnote/form?"+$.param(urlparam),urlobj, function( data ){	
					}).fail(function (data) {	
						refreshGrid("#jqGridInvestigation_fbc", urlParam_fbc);	
					}).done(function (data) {	
						refreshGrid("#jqGridInvestigation_fbc", urlParam_fbc);	
					});	
				}else{
					$("#jqGridPagerDelete,#jqGridPagerRefresh").show();	
				}
			}	
		},	
	}).jqGrid('navButtonAdd', "#jqGridPagerInvestigation_fbc", {	
		id: "jqGridPagerInvestigation_fbcRefresh",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-refresh",	
		title: "Refresh Table",	
		onClickButton: function () {
			refreshGrid("#jqGridInvestigation_fbc", urlParam_fbc);	
		},
	});

	
});
	
var myEditOptions_fbc = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGridInvestigation_fbc").data('lastselrow',rowid);

		collapseallsubgrid(rowid);

		var selrowdata = $('#jqGridInvestigation_fbc').jqGrid ('getRowData', rowid);
		write_detail_dosage(selrowdata,true,rowid);

		errorField.length=0;
		$("#jqGridInvestigation_fbc input[name='entereddate']").val(moment().format('YYYY-MM-DD'));
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").hide();

		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		$("#jqGridInvestigation_fbc input[name='entereddate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGridInvestigation_fbc input#"+rowid+"_chgcode").focus();
			}
		});

		$("input[name='enteredtime']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGridInvestigation_fbc_ilsave').click();
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    	// $("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;

		if(parseInt($('#jqGridInvestigation_fbc input[name="quantity"]').val()) == 0)return false;

		let rowdata = getrow_bootgrid();

		let editurl = "./nursingnote/form?"+
			$.param({
				action: 'inv_fbc',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    entereddate: $("#entereddate"+rowid).val(),
				enteredtime: $("#enteredtime"+rowid).val(),
				fbc_hb: $("#fbc_hb"+rowid).val(),

				
			});
		$("#jqGridInvestigation_fbc").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc : function( response ) {
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
		errorField.length=0;
		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

var myEditOptions_fbc_edit = {
	keys: true,
	extraparam:{
	    "_token": $("#csrf_token").val()
    },
	oneditfunc: function (rowid) {
		$("#jqGridInvestigation_fbc").data('lastselrow',rowid);
		var selrowdata = $('#jqGridInvestigation_fbc').jqGrid ('getRowData', rowid);

		$("#jqGridInvestigation_fbc input[name='trxdate']").val(moment().format('YYYY-MM-DD'));
		errorField.length=0;
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").hide();

		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		
		$("#jqGridInvestigation_fbc input[name='trxdate']").on('focus',function(){
			let focus = $(this).data('focus');
			if(focus == undefined){
				$(this).data('focus',1);
				$("#jqGridInvestigation_fbc input#"+rowid+"_chgcode").focus();
			}
		});
	},
	aftersavefunc: function (rowid, response, options) {
		let retval = JSON.parse(response.responseText);
		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
		errorField.length=0;
	},
	errorfunc: function(rowid,response){
    	alert(response.responseText);
    	// refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    	// $("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
    },
	beforeSaveRow: function (options, rowid) {
    	if(errorField.length>0)return false;

		if(parseInt($('#jqGridInvestigation_fbc input[name="quantity"]').val()) == 0)return false;

		let rowdata = getrow_bootgrid();

		let editurl = "./nursingnote/form?"+
			$.param({
				action: 'inv_fbc',
				mrn: rowdata.MRN,
				episno: rowdata.Episno,
			    entereddate: $("#entereddate"+rowid).val(),
				enteredtime: $("#enteredtime"+rowid).val(),
				
			});
		$("#jqGridInvestigation_fbc").jqGrid('setGridParam', { editurl: editurl });
	},
	afterrestorefunc: function( response ) {
    	$("#jqGridPagerInvestigation_fbcRefresh,#jqGridPagerInvestigation_fbcDelete").show();
		errorField.length=0;
		calc_jq_height_onchange("jqGridInvestigation_fbc",true,parseInt($('#jqGridInvestigation_c_fbc').prop('clientHeight'))-241);
		refreshGrid('#jqGridInvestigation_fbc',urlParam_fbc,'add');
    },
    errorTextFormat: function (data) {
    	alert(data);
    }
};

function enteredtimeCustomEdit_fbc(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_fbc (elem, operation, value){
	if(operation == 'get') {
		return $(elem).find("input").val();
	} 
	else if(operation == 'set') {
		$('input',elem).val(value);
	}
}

function cust_rules_fbc(value, name) {
	var temp=null;
	switch (name) {
        case 'Time': temp = $("#jqGridInvestigation_fbc input[name='enteredtime']"); break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}

function collapseallsubgrid(except){
	var dataid = $("#jqGridInvestigation_fbc").jqGrid('getDataIDs');
	dataid.forEach(function(e,i){
		$("#jqGridInvestigation_fbc").jqGrid("collapseSubGridRow",e);
	});
	$("#jqGridInvestigation_fbc").jqGrid("expandSubGridRow",except);
}
