$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_nok = [];
	conf_nok = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_nok.length > 0) {
				return {
					element: $(errorField_nok[0]),
					message: ''
				}
			}
		},
	};

	var urlParam_nok = {
		action:'get_table_default',
		url:'/util/get_table_default',
		field: '',
		table_name: 'hisdb.nok_ec',
		filterCol:['compcode','episno','mrn'],
		filterVal:['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()],
	}

	$("#jqGrid_nok").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'idno', name: 'idno'  , hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'Name', name: 'name', width: 80 },
            { label: 'Relatecode', name: 'relationshipcode', hidden: true  },
            { label: 'address1', name: 'address1' , hidden: true },
            { label: 'address2', name: 'address3' , hidden: true },
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
		pager: "#jqGridPager_nok",
		onSelectRow:function(rowid, selected){
			populate_nok(selrowData("#jqGrid_nok"));
		},
		loadComplete: function(){
			$('#jqGrid_nok_ilsave,#jqGrid_nok_ilcancel').hide();

			let reccount = $('#jqGrid_nok').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_nok('add_edit');
			}else{
				button_state_nok('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_nok").inlineNav('#jqGridPager_nok', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_nok", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_nok", urlParam_nok);
		},
	});

	addParamField('#jqGrid_nok', false, urlParam_nok);

	$("#tabNok").on("shown.bs.collapse", function(){
		$("#jqGrid_nok").jqGrid ('setGridWidth', Math.floor($("#jqGrid_nok_c")[0].offsetWidth-$("#jqGrid_nok_c")[0].offsetLeft-0));
		urlParam_nok.filterCol = ['compcode','episno','mrn'],
		urlParam_nok.filterVal = ['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()]
		refreshGrid("#jqGrid_nok", urlParam_nok);
	});

	var search_relate = new ordialog(
		'search_relate', 'hisdb.relationship', '#nok_relate', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'relationshipcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				// $(search_relate.textfield).parent().next().html('');
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_relate.urlParam.filterCol=['compcode', 'recstatus'];
				search_relate.urlParam.filterVal=['session.compcode', 'A'];

				$('div[aria-describedby="otherdialog_search_relate"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	search_relate.makedialog(false);
	
	$("#add_nok").click(function(){
		button_state_nok('wait');
		enableForm('#form_nok',['nok_relate']);
		search_relate.on();
		
	});

	$("#edit_nok").click(function(){
		button_state_nok('wait');
		enableForm('#form_nok',['nok_relate']);
		search_relate.on();
		
	});

	$("#save_nok").click(function(){
		disableForm('#form_nok');
		if( $('#form_nok').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_nok(function(){
				refreshGrid("#jqGrid_nok", urlParam_nok);
			});
		}else{
			enableForm('#form_nok',['nok_relate']);
		}

	});

	function saveForm_nok(callback){
		var saveParam={
	        action:'save_table_ti',
	        oper:$("#cancel_ti").data('oper')
	    }

	    let doc_status = $('#doc_status').find(":selected").text();

	    var postobj={
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#mrn_episode").val(),
	    	episno : $("#txt_epis_no").val(),
	    	epistycode : $("#epistycode").val(),
	    	doctorcode : $('#doc_doctorcode').val(),
			status : doc_status
	    };

	    $.post( "/episode/save_nok", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_nok").click(function(){
		button_state_nok('empty');
		disableForm('#form_nok');
		search_relate.off();
		emptyFormdata_div('#form_nok');
		refreshGrid("#jqGrid_nok", urlParam_nok);

	});

	disableForm('#form_nok');
	button_state_nok('add');
	function button_state_nok(state){
		switch(state){
			case 'empty':
				$('#add_nok,#edit_nok,#save_nok,#cancel_nok').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_nok,#edit_nok").attr('disabled',false);
				$('#save_nok,#cancel_nok').attr('disabled',true);
				break;
			case 'add':
				$("#add_nok").attr('disabled',false);
				$('#edit_nok,#save_nok,#cancel_nok').attr('disabled',true);
				break;
			case 'wait':
				// dialog_tri_col.on();
				// examination.on().enable();
				$("#save_nok,#cancel_nok").attr('disabled',false);
				$('#add_nok,#edit_nok').attr('disabled',true);
				break;
		}
	}

	function populate_nok(obj){
		$("#doc_no").val(obj.da_allocno);
		$("#doc_doctorcode").val(obj.da_doctorcode);
		$("#doc_doctorname").val(obj.d_doctorname);
		$("#doc_discipline").val(obj.d_disciplinecode);
		$("#doc_date").val(obj.da_asdate);
		$("#doc_time").val(obj.da_astime);
		$("#doc_status").val(obj.da_astatus);
	}

});