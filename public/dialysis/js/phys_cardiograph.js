
$(document).ready(function () { 
	
	$('#phys_card').on('shown.bs.collapse', function () {
		delay(function(){
			$("#jqGrid_card").jqGrid ('setGridWidth', Math.floor($("#phys_card")[0].offsetWidth-$("#phys_card")[0].offsetLeft-34));
		}, 50 );
	});
	$('#phys_resi').on('shown.bs.collapse', function () {
		delay(function(){
			$("#jqGrid_resi").jqGrid ('setGridWidth', Math.floor($("#phys_resi")[0].offsetWidth-$("#phys_resi")[0].offsetLeft-34));
		}, 50 );
	});

	$('.ui.selection.dropdown#exercise').dropdown({
    	clearable: true
  	});

  	$('select#exercise').on("change",function(){
  		if($(this).val() == 'treadmill'){
  			$("#jqGrid_card").jqGrid('setLabel', 'speed', 'Speed');
  			urlParam_card.exercise = 'treadmill';
			refreshGrid("#jqGrid_card", urlParam_card);

  		}else if($(this).val() == 'cycling'){
  			$("#jqGrid_card").jqGrid('setLabel', 'speed', 'RPM');
  			urlParam_card.exercise = 'cycling';
			refreshGrid("#jqGrid_card", urlParam_card);

  		}
  	});

  	$('a#opengraph').click(function(){
  		myWindow = window.open("./cardiograph?action=get_graph_cardio&mrn="+$('#mrn_phys').val()+"&exercise="+$('select#exercise').val());
  	});

	$("#jqGrid_card").jqGrid({
		datatype: "local",
		editurl: "./cardiograph/form",
		colModel: [
			{ label: 'idno', name: 'idno', hidden: true,key:true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'Date', name: 'date', width: 40, editable:true, classes: 'wrap',
				editrules:{required: true, custom:true, custom_func:cust_rules},
				edittype:'custom',	editoptions:
				    {  custom_element:chgcodeCustomEdit,
				       custom_value:galGridCustomValue 	
				    },
			},
			{ label: 'exercise', name: 'exercise', hidden: true },
			{ label: 'Sistole', name: 'bp_s', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules}},
			{ label: 'Diastole', name: 'bp_d', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules}},
			{ label: 'Heart Rate', name: 'hr', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules}},
			{ label: 'Speed', name: 'speed', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules}},
			{ label: 'RPE', name: 'rpe', width: 20 , align: 'right', editable:true, classes: 'input',
				editrules:{required: true, custom:true, custom_func:cust_rules}},
			
		],
		autowidth: false,
		viewrecords: true,
		width: 900,
		height: 365,
		rowNum: 30,
		pager:'#jqGrid_pager_card',
		viewrecords: true,
		loadonce:false,
		scroll: true,
		sortname: 'idno',
		sortorder: "desc",
		onSelectRow:function(rowid, selected){

		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			if($('td#jqGrid_card_iledit').is(':visible')){
				$('td#jqGrid_card_iledit').click();
			}
		},
		loadComplete: function () {
        	$('#jqGrid_card_ildelete').removeClass('ui-disabled');
			if(addmore_onadd_card == true){
				$('#jqGrid_card_iladd').click();
			}
		},
	});

	var myEditOptions_card_add = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {
        	addmore_onadd_card = true;
        	$('#jqGrid_card_ildelete').addClass('ui-disabled');

        },
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_card", urlParam_card);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {

			let editurl = "./cardiograph/form?"+
				$.param({
					action:'save_cardiograph',
					mrn: $('#mrn_phys').val(),
					exercise:$('select#exercise').val()
				});


			$("#jqGrid_card").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
        	$('#jqGrid_card_ildelete').removeClass('ui-disabled');
	    }
    };

    var myEditOptions_card_edit = {
        keys: false,
        extraparam:{
		    "_token": $("#_token").val(),
		    "mrn": selrowData('#jqGrid').MRN,
		    "episno": selrowData('#jqGrid').Episno,
        },
        oneditfunc: function (rowid) {

		},
        aftersavefunc: function (rowid, response, options) {
			refreshGrid("#jqGrid_card", urlParam_card);
        }, 
        errorfunc: function(rowid,response){
        	
        },
        beforeSaveRow: function(options, rowid) {
        	let selrow_trans = selrowData('#jqGrid_card');

			let editurl = "./cardiograph/form?"+
				$.param({
					action:'save_cardiograph',
					mrn: $('#mrn_phys').val(),
		    		idno: selrow_trans.idno,
					exercise:$('select#exercise').val()
				});

			$("#jqGrid_card").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function( response ) {
        	$('#jqGrid_card_ildelete').removeClass('ui-disabled');
	    }
    };

	$("#jqGrid_card").inlineNav('#jqGrid_pager_card',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions_card_add
		},
		editParams: myEditOptions_card_edit
	}).jqGrid('navButtonAdd', "#jqGrid_pager_card", {	
		id: "jqGrid_card_ildelete",	
		caption: "", cursor: "pointer", position: "last",	
		buttonicon: "glyphicon glyphicon-trash",	
		title: "Delete Selected Row",	
		onClickButton: function () {	
			var rowid = $("#jqGrid_card").jqGrid('getGridParam', 'selrow');	
			if (!rowid) {	
				alert('Please select row');	
			} else {
	        	let selrow = selrowData('#jqGrid_card');
				$.confirm({
				    title: 'Confirm',
				    content: 'Are you sure you want to delete this row?',
				    buttons: {
				        confirm:{
				        	btnClass: 'btn-blue',
				        	action: function () {
					        	var param = {
									_token: $("#_token").val(),
									mrn: $('#mrn_phys').val(),
						    		id: selrow.idno,
						    		oper: 'del'
								}

								$.post( "./cardiograph/form",param, function( data ){
									addmore_onadd_card = false;
									refreshGrid("#jqGrid_card", urlParam_card);
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

	function dateCustomEdit(val,opt){  	
		val = (val == "undefined") ? "" : val;
		// if(opt.rowId.startsWith("jqg")){
		// 	val = moment().format('YYYY-MM-DD HH:mm:ss');
		// }
		return $(`<input type="text" optid="`+opt.rowId+`" id="`+opt.id+`" value="`+val+`" >`);
	}

	function chgcodeCustomEdit(val,opt){
		val = (val == "undefined") ? "" : val;
		if(opt.rowId.startsWith("jqg")){
			val = moment().format('YYYY-MM-DD HH:mm:ss');
		}
		return $(`<div class="input-group">
				<input jqgrid="jqGrid_trans" optid="`+opt.rowId+`" id="`+opt.id+`" name="chgcode"
				 type="datetime-local" class="form-control input" data-validation="required" value="`+val+`" style="z-index: 0">
				</div>`);
	}


	function galGridCustomValue(elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Code':temp=$('table#jqGrid_trans input[name=chgcode]');break;
			case 'Date':temp=$('table#jqGrid_card input[name=date]');break;
			case 'Sistole':temp=$('table#jqGrid_card input[name=bp_s]');break;
			case 'Diastole':temp=$('table#jqGrid_card input[name=bp_d]');break;
			case 'Heart Rate':temp=$('table#jqGrid_card input[name=hr]');break;
			case 'Speed':temp=$('table#jqGrid_card input[name=speed]');break;
			case 'RPE':temp=$('table#jqGrid_card input[name=rpe]');break;
			case 'RPM':temp=$('table#jqGrid_card input[name=rpm]');break;
		}
		return(temp.val() == '')?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	hide_card_button(true);

});

var addmore_onadd_card = false;
var urlParam_card = {
	url:'./cardiograph/table',
	mrn:null,
	exercise:'treadmill',
	action: 'get_cardio_table',
}

function empty_table_card(kosongkan = 'kosongkan'){
	addmore_onadd_phys = false
	hide_card_button(true);
	refreshGrid("#jqGrid_card", urlParam_card,kosongkan);
}

function hide_card_button(hide=true){
	if(hide){
		$('#jqGrid_card_iladd,#jqGrid_card_iledit,#jqGrid_card_ilsave,#jqGrid_card_ilcancel,#jqGrid_card_ildelete').hide();
	}else{
		$('#jqGrid_card_iladd,#jqGrid_card_iledit,#jqGrid_card_ilsave,#jqGrid_card_ilcancel,#jqGrid_card_ildelete').show();
	}
}