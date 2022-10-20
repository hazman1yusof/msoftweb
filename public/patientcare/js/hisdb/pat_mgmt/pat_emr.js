$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_nok_emr = [];
	conf_nok = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_nok_emr.length > 0) {
				return {
					element: $(errorField_nok_emr[0]),
					message: ''
				}
			}
		},
	};

	var urlParam_nok_emr = {
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		table_name: 'hisdb.pat_emergency',
		filterCol:['compcode','mrn'],
		filterVal:['session.compcode',$("#pat_mrn").val()],
	}

	$("#jqGrid_nok_emr").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'idno', name: 'idno'  , hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'Name', name: 'name', width: 80 },
            { label: 'Relatecode', name: 'relationship', hidden: true  },
            { label: 'Telephone', name: 'telh' , width: 20 },
            { label: 'Handphone', name: 'telhp' , width: 20 },
            { label: 'Email', name: 'email' , width: 20 },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 250, 
		rowNum: 30,
		pager: "#jqGridPager_nok_emr",
		onSelectRow:function(rowid, selected){
			populate_nok_emr(selrowData("#jqGrid_nok_emr"));
		},
		loadComplete: function(){
			$('#jqGrid_nok_emr_ilsave,#jqGrid_nok_emr_ilcancel').hide();

			let reccount = $('#jqGrid_nok_emr').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_nok_emr('add_edit');
			}else{
				button_state_nok_emr('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_nok_emr").inlineNav('#jqGridPager_nok_emr', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_nok_emr", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_nok_emr", urlParam_nok_emr);
		},
	});

	addParamField('#jqGrid_nok_emr', false, urlParam_nok_emr);

	$("#tabNok_emr").on("shown.bs.collapse", function(){
		$("#jqGrid_nok_emr").jqGrid ('setGridWidth', Math.floor($("#jqGrid_nok_emr_c")[0].offsetWidth-$("#jqGrid_nok_emr_c")[0].offsetLeft-0));
		urlParam_nok_emr.filterCol = ['compcode','mrn'],
		urlParam_nok_emr.filterVal = ['session.compcode',$("#pat_mrn").val()]
		refreshGrid("#jqGrid_nok_emr", urlParam_nok_emr);
	});

	var search_relate_emr = new ordialog(
		'search_relate_emr', 'hisdb.relationship', '#emr_relate', 'errorField',
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
				// $(search_relate_emr.textfield).parent().next().html('');
			}
		},{
			title: "Select Relationship",
			open: function () {
				search_relate_emr.urlParam.filterCol=['compcode', 'recstatus'];
				search_relate_emr.urlParam.filterVal=['session.compcode', 'A'];

				$('div[aria-describedby="otherdialog_search_relate"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	search_relate_emr.makedialog(false);

	$("#otherdialog_search_relate_emr").append( $( "<button id='relate_emr_new'>Add New Relationship Code</button>" ) );

	$('#new_relationship_save').click(function(){
	      if($('#new_relationship_form').valid()){
	        var _token = $('#csrf_token').val();
	        let serializedForm = $( "#new_relationship_form" ).serializeArray();
	        let obj = {
	                _token: _token
	        }
	        
	        $.post( 'pat_mast/new_relationship_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
	            $('#mdl_add_new_title').modal('hide');
	        }).fail(function(data) {
	            alert(data.responseText);
	        }).success(function(data){
	        });
	      }
	});
	
	$("#add_nok_emr").click(function(){
		button_state_nok_emr('wait');
		emptyFormdata_div('#form_nok_emr');
		enableForm('#form_nok_emr',['emr_relate']);
		search_relate_emr.on();
		$("#save_nok_emr").data('oper','add');
		
	});

	$("#edit_nok_emr").click(function(){
		button_state_nok_emr('wait');
		enableForm('#form_nok_emr',['emr_relate']);
		search_relate_emr.on();
		$("#save_nok_emr").data('oper','edit');
	});

	$("#save_nok_emr").click(function(){
		disableForm('#form_nok_emr');
		if( $('#form_nok_emr').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_nok_emr(function(){
				refreshGrid("#jqGrid_nok_emr", urlParam_nok_emr);
			});
		}else{
			enableForm('#form_nok_emr',['emr_relate']);
		}

	});

	function saveForm_nok_emr(callback){

	    var postobj={
	        oper:$("#save_nok_emr").data('oper'),
	        idno:$("#emr_idno").val(),
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#pat_mrn").val(),
			name : $("#emr_name").val(),
			relationshipcode : $("#emr_relate").val(),
			tel_h : $("#emr_telh").val(),
			tel_hp : $("#emr_telhp").val(),
			email : $("#emr_email").val()
	    };

	    $.post( "episode/save_emr", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_nok_emr").click(function(){
		button_state_nok_emr('empty');
		disableForm('#form_nok_emr');
		search_relate_emr.off();
		emptyFormdata_div('#form_nok_emr');
		refreshGrid("#jqGrid_nok_emr", urlParam_nok_emr);

	});

	disableForm('#form_nok_emr');
	button_state_nok_emr('add');
	function button_state_nok_emr(state){
		switch(state){
			case 'empty':
				$('#add_nok_emr,#edit_nok_emr,#save_nok_emr,#cancel_nok_emr').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_nok_emr,#edit_nok_emr").attr('disabled',false);
				$('#save_nok_emr,#cancel_nok_emr').attr('disabled',true);
				break;
			case 'add':
				$("#add_nok_emr").attr('disabled',false);
				$('#edit_nok_emr,#save_nok_emr,#cancel_nok_emr').attr('disabled',true);
				break;
			case 'wait':
				$("#save_nok_emr,#cancel_nok_emr").attr('disabled',false);
				$('#add_nok_emr,#edit_nok_emr').attr('disabled',true);
				break;
		}
	}

	function populate_nok_emr(obj){
		$("#emr_idno").val(obj.idno);
		$("#emr_name").val(obj.name);
		$("#emr_relate").val(obj.relationship);
		$("#emr_telh").val(obj.telh);
		$("#emr_telhp").val(obj.telhp);
		$("#emr_email").val(obj.email);
	}

});