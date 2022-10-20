$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_doc = [];
	conf_doc = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_doc.length > 0) {
				return {
					element: $(errorField_doc[0]),
					message: ''
				}
			}
		},
	};

	var urlParam_doctor = {
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		fixPost:'true',
		table_name: ['hisdb.docalloc AS da','hisdb.doctor AS d'],
		join_type:['LEFT JOIN'],
		join_onCol:['da.doctorcode'],
		join_onVal:['d.doctorcode'],
		filterCol:['da.compcode','da.episno','da.mrn'],
		filterVal:['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()],
	}

	$("#jqGrid_doctor").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'Doctorcode', name: 'da_doctorcode' , width: 20 },
            { label: 'Name', name: 'd_doctorname' , width: 80},
            { label: 'compcode', name: 'da_compcode', hidden: true },
            { label: 'allocno', name: 'da_allocno', hidden: true  },
            { label: 'MRN', name: 'da_mrn', hidden: true  },
            { label: 'Epis no', name: 'da_episno' , hidden: true },
            { label: 'd_disciplinecode', name: 'd_disciplinecode' , hidden: true },
            { label: 'da_asdate', name: 'da_asdate' , hidden: true },
            { label: 'da_astime', name: 'da_astime' , hidden: true },
            { label: 'da_astatus', name: 'da_astatus' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		sortname: 'da_allocno',
		sortorder: 'asc',
		height: 250, 
		rowNum: 30,
		pager: "#jqGridPager_doctor",
		onSelectRow:function(rowid, selected){
			populate_doc(selrowData("#jqGrid_doctor"));
		},
		loadComplete: function(){
			$('#jqGrid_doctor_ilsave,#jqGrid_doctor_ilcancel').hide();

			let reccount = $('#jqGrid_doctor').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_doc('add_edit');
			}else{
				button_state_doc('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_doctor").inlineNav('#jqGridPager_doctor', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_doctor", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_doctor", urlParam_doctor);
		},
	});

	addParamField('#jqGrid_doctor', false, urlParam_doctor);

	$("#tabDoctor").on("shown.bs.collapse", function(){
		$("#jqGrid_doctor").jqGrid ('setGridWidth', Math.floor($("#jqGrid_doctor_c")[0].offsetWidth-$("#jqGrid_doctor_c")[0].offsetLeft-0));
		urlParam_doctor.filterCol = ['da.compcode','da.episno','da.mrn'],
		urlParam_doctor.filterVal = ['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()]
		refreshGrid("#jqGrid_doctor", urlParam_doctor);
	});

	var search_doctor = new ordialog(
		'search_doctor', 'hisdb.doctor', '#doc_doctorcode', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'doctorcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'doctorname', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'disciplinecode', name: 'disciplinecode', hidden: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + search_doctor.gridname);
				console.log(search_doctor.textfield)
				$(search_doctor.textfield).parent().next().html('');

				$('#doc_doctorname').val(data.doctorname);
				$('#doc_discipline').val(data.disciplinecode);
				$("#doc_date").val(moment().format("DD/MM/YYYY"));
				$("#doc_time").val(moment().format("HH:mm:ss"));
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_doctor.urlParam.filterCol=['compcode', 'recstatus'];
				search_doctor.urlParam.filterVal=['session.compcode', 'A'];

				$('div[aria-describedby="otherdialog_search_doctor"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	search_doctor.makedialog(false);
	
	$("#add_doc").click(function(){
		emptyFormdata_div('#form_doc');
		button_state_doc('wait');
		enableForm('#form_doc',['doc_no','doc_doctorcode','doc_doctorname','doc_discipline','doc_date','doc_time']);
		$("#save_doc").data('oper','add');
		search_doctor.on();
		
	});

	$("#edit_doc").click(function(){
		button_state_doc('wait');
		enableForm('#form_doc',['doc_no','doc_doctorcode','doc_doctorname','doc_discipline','doc_date','doc_time']);
		$("#save_doc").data('oper','edit');
		search_doctor.on();
		
	});

	$("#save_doc").click(function(){
		disableForm('#form_doc');
		if( $('#form_doc').isValid({requiredFields: ''}, conf_doc, true) ) {
			saveForm_doc(function(){
				refreshGrid("#jqGrid_doctor", urlParam_doctor);
			});
		}else{
			enableForm('#form_doc',['doc_no','doc_doctorcode','doc_doctorname','doc_discipline','doc_date','doc_time']);
		}

	});

	function saveForm_doc(callback){

	    let doc_status = $('#doc_status').find(":selected").text();

	    var postobj={
	        oper:$("#save_doc").data('oper'),
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#mrn_episode").val(),
	    	episno : $("#txt_epis_no").val(),
	    	epistycode : $("#epistycode").val(),
	    	doctorcode : $('#doc_doctorcode').val(),
	    	allocno : $("#doc_no").val(),
			status : doc_status
	    };

	    $.post( "episode/save_doc", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_doc").click(function(){
		button_state_doc('empty');
		disableForm('#form_doc');
		search_doctor.off();
		emptyFormdata_div('#form_doc');
		refreshGrid("#jqGrid_doctor", urlParam_doctor);

	});

	disableForm('#form_doc');
	button_state_doc('add');
	function button_state_doc(state){
		switch(state){
			case 'empty':
				$('#add_doc,#edit_doc,#save_doc,#cancel_doc').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_doc,#edit_doc").attr('disabled',false);
				$('#save_doc,#cancel_doc').attr('disabled',true);
				break;
			case 'add':
				$("#add_doc").attr('disabled',false);
				$('#edit_doc,#save_doc,#cancel_doc').attr('disabled',true);
				break;
			case 'wait':
				// dialog_tri_col.on();
				// examination.on().enable();
				$("#save_doc,#cancel_doc").attr('disabled',false);
				$('#add_doc,#edit_doc').attr('disabled',true);
				break;
		}
	}

	function populate_doc(obj){
		$("#doc_no").val(obj.da_allocno);
		$("#doc_doctorcode").val(obj.da_doctorcode);
		$("#doc_doctorname").val(obj.d_doctorname);
		$("#doc_discipline").val(obj.d_disciplinecode);
		$("#doc_date").val(obj.da_asdate);
		$("#doc_time").val(obj.da_astime);
		$("#doc_status").val(obj.da_astatus);
	}

});