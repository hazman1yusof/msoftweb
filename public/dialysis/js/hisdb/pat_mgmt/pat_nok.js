$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_nok_pat = [];
	conf_nok = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_nok_pat.length > 0) {
				return {
					element: $(errorField_nok_pat[0]),
					message: ''
				}
			}
		},
	};

	var urlParam_nok_pat = {
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		table_name: 'hisdb.nok_ec',
		filterCol:['compcode','mrn'],
		filterVal:['session.compcode',$("#pat_mrn").val()],
	}


	$("#jqGrid_nok_pat").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'idno', name: 'idno'  , hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'Name', name: 'name', width: 80 },
            { label: 'Relatecode', name: 'relationshipcode', hidden: true  },
            { label: 'address1', name: 'address1' , hidden: true },
            { label: 'address2', name: 'address2' , hidden: true },
            { label: 'address3', name: 'address3' , hidden: true },
            { label: 'postcode', name: 'postcode' , hidden: true },
            { label: 'tel_h', name: 'tel_h' , hidden: true },
            { label: 'tel_hp', name: 'tel_hp' , hidden: true },
            { label: 'tel_o', name: 'tel_o' , hidden: true },
            { label: 'tel_o_ext', name: 'tel_o_ext' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 250, 
		rowNum: 30,
		pager: "#jqGridPager_nok_pat",
		onSelectRow:function(rowid, selected){
			populate_nok_pat(selrowData("#jqGrid_nok_pat"));
		},
		loadComplete: function(){
			$('#jqGrid_nok_pat_ilsave,#jqGrid_nok_pat_ilcancel').hide();

			let reccount = $('#jqGrid_nok_pat').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_nok_pat('add_edit');
			}else{
				button_state_nok_pat('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_nok_pat").inlineNav('#jqGridPager_nok_pat', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_nok_pat", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			if(!if_addnew_nok()){
				refreshGrid("#jqGrid_nok_pat", urlParam_nok_pat);
			}
		},
	});

	addParamField('#jqGrid_nok_pat', false, urlParam_nok_pat);

	$("#tabNok_pat").on("shown.bs.collapse", function(){
		$("#jqGrid_nok_pat").jqGrid ('setGridWidth', Math.floor($("#jqGrid_nok_pat_c")[0].offsetWidth-$("#jqGrid_nok_pat_c")[0].offsetLeft-0));
		urlParam_nok_pat.filterCol = ['compcode','mrn'],
		urlParam_nok_pat.filterVal = ['session.compcode',$("#pat_mrn").val()]
		if(!if_addnew_nok()){
			refreshGrid("#jqGrid_nok_pat", urlParam_nok_pat);
		}
	});

	var search_relate_pat = new ordialog(
		'search_relate_pat', 'hisdb.relationship', '#nok_relate_pat', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'relationshipcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				// $(search_relate_pat.textfield).parent().next().html('');
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_relate_pat.urlParam.filterCol=['compcode', 'recstatus'];
				search_relate_pat.urlParam.filterVal=['session.compcode', 'ACTIVE'];

				$('div[aria-describedby="otherdialog_search_relate"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	search_relate_pat.makedialog(false);
	
	$("#add_nok_pat").click(function(){
		emptyFormdata_div('#form_nok_pat');
		button_state_nok_pat('wait');
		enableForm('#form_nok_pat',['nok_relate_pat']);
		search_relate_pat.on();
		$("#save_nok_pat").data('oper','add');
		
	});

	$("#edit_nok_pat").click(function(){
		button_state_nok_pat('wait');
		enableForm('#form_nok_pat',['nok_relate_pat']);
		search_relate_pat.on();
		$("#save_nok_pat").data('oper','edit');
	});

	$("#save_nok_pat").click(function(){
		disableForm('#form_nok_pat');
		if( $('#form_nok_pat').isValid({requiredFields: ''}, conf_nok, true) ) {
			if(if_addnew_nok()){
				add_newlocal_nok();
			}else{
				saveForm_nok_pat(function(){
					refreshGrid("#jqGrid_nok_pat", urlParam_nok_pat);
				});
			}
		}else{
			enableForm('#form_nok_pat',['nok_relate_pat']);
		}

	});

	function saveForm_nok_pat(callback){

	    var postobj={
	        oper:$("#save_nok_pat").data('oper'),
	        idno:$("#nok_idno_pat").val(),
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#pat_mrn").val(),
	    	episno : $("#txt_pat_episno").val(),
			name : $("#nok_name_pat").val(),
			relationshipcode : $("#nok_relate_pat").val(),
			address1 : $("#nok_addr1_pat").val(),
			address2 : $("#nok_addr2_pat").val(),
			address3 : $("#nok_addr3_pat").val(),
			postcode : $("#nok_postcode_pat").val(),
			tel_h : $("#nok_telh_pat").val(),
			tel_hp : $("#nok_telhp_pat").val(),
			tel_o : $("#nok_telo_pat").val(),
			tel_o_ext : $("#nok_ext_pat").val()
	    };

	    $.post( "episode/save_nok", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_nok_pat").click(function(){
		button_state_nok_pat('empty');
		disableForm('#form_nok_pat');
		search_relate_pat.off();
		emptyFormdata_div('#form_nok_pat');
		if(!if_addnew_nok()){
			refreshGrid("#jqGrid_nok_pat", urlParam_nok_pat);
		}else{
			let reccount = $('#jqGrid_nok_pat').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_nok_pat('add_edit');
			}else{
				button_state_nok_pat('add');
			}
		}

	});

	disableForm('#form_nok_pat');
	button_state_nok_pat('add');

	function populate_nok_pat(obj){
		$("#nok_idno_pat").val(obj.idno);
		$("#nok_name_pat").val(obj.name);
		$("#nok_relate_pat").val(obj.relationshipcode);
		$("#nok_addr1_pat").val(obj.address1);
		$("#nok_addr2_pat").val(obj.address2);
		$("#nok_addr3_pat").val(obj.address3);
		$("#nok_postcode_pat").val(obj.postcode);
		$("#nok_telh_pat").val(obj.tel_h);
		$("#nok_telhp_pat").val(obj.tel_hp);
		$("#nok_telo_pat").val(obj.tel_o);
		$("#nok_ext_pat").val(obj.tel_o_ext);
	}

});

function button_state_nok_pat(state){
	switch(state){
		case 'empty':
			$('#add_nok_pat,#edit_nok_pat,#save_nok_pat,#cancel_nok_pat').attr('disabled',true);
			break;
		case 'add_edit':
			$("#add_nok_pat,#edit_nok_pat").attr('disabled',false);
			$('#save_nok_pat,#cancel_nok_pat').attr('disabled',true);
			break;
		case 'add':
			$("#add_nok_pat").attr('disabled',false);
			$('#edit_nok_pat,#save_nok_pat,#cancel_nok_pat').attr('disabled',true);
			break;
		case 'wait':
			$("#save_nok_pat,#cancel_nok_pat").attr('disabled',false);
			$('#add_nok_pat,#edit_nok_pat').attr('disabled',true);
			break;
	}
}

function add_newlocal_nok(){
	var reccount = $('#jqGrid_nok_pat').jqGrid('getGridParam', 'reccount');
	var rowid = 1;
	if(reccount==0){
		rowid=1
	}else{
		rowid=reccount+1;
	}
	var rowdata={
		idno:rowid,
		mrn:'',
		episno:'',
		name:$("#nok_name_pat").val(),
		relationshipcode:$("#nok_relate_pat").val(),
		address1:$("#nok_addr1_pat").val(),
		address2:$("#nok_addr2_pat").val(),
		address3:$("#nok_addr3_pat").val(),
		postcode:$("#nok_postcode_pat").val(),
		tel_h:$("#nok_telh_pat").val(),
		tel_hp:$("#nok_telhp_pat").val(),
		tel_o:$("#nok_telo_pat").val(),
		tel_o_ext:$("#nok_ext_pat").val()
	}
	$('#jqGrid_nok_pat').jqGrid ('addRowData', rowid, rowdata,'first');

	$('#jqGrid_nok_pat').jqGrid('setSelection', rowid);
	button_state_nok_pat('add_edit');
}

function if_addnew_nok(){
	if($("#btn_register_patient").data("oper") == "add"){
		return true;
	}else if($("#btn_register_patient").data("oper") == "edit"){
		return false;
	}else{
		return true;
	}
}

function get_nok_table_fornewpt(){
	let rowdatas = $('#jqGrid_nok_pat').jqGrid ('getRowData');
	return rowdatas;
}

function empty_nok_jq(){
	$("#jqGrid_nok_pat").jqGrid("clearGridData");
	refreshGrid("#jqGrid_nok_pat", 'urlParam_nok_pat','kosongkan');
}