$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_dialysis = [];
	conf_dialysis = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_dialysis.length > 0) {
				return {
					element: $(errorField_dialysis[0]),
					message: ''
				}
			}
		},
	};

	var last_lineno_ = 1;
	$("#jqGrid_dialysis").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'idno', name: 'de_idno' , hidden: true,sortable: false },
            { label: 'MRN', name: 'de_mrn', hidden: true ,sortable: false },
            { label: 'Epis no', name: 'de_episno' , hidden: true,sortable: false },
            { label: 'No.', name: 'de_lineno_' , width: 20,sortable: false},
            { label: 'Arrival Date', name: 'de_arrival_date' , width: 40 ,sortable: false,sortable: false, formatter: dateFormatter, unformat: dateUNFormatter},
            { label: 'Arrival Time', name: 'de_arrival_time' , width: 40 ,sortable: false},
            { label: 'Package', name: 'de_packagecode' , hidden: false ,sortable: false},
		],
		autowidth: true,
		multiSort: false,
		viewrecords: true,
		loadonce: true,
		viewrecords: false,
		scroll: true,
		width: 900,		
		height: 250, 
		rowNum: 100,
		pager: "#jqGridPager_dialysis",
		onSelectRow:function(rowid, selected){
			console.log();
			if(moment(selrowData("#jqGrid_dialysis").de_arrival_date).isSame(moment(), 'day') == true){
				$('#del_dialysis').show();
			}else{
				$('#del_dialysis').hide();
			}

			populate_dialysis(selrowData("#jqGrid_dialysis"));
		},
		loadComplete: function(){
			$('#jqGrid_dialysis_ilsave,#jqGrid_dialysis_ilcancel').hide();

			let reccount = $('#jqGrid_dialysis').jqGrid('getGridParam', 'reccount');
			if(reccount>0){

				$("#jqGrid_dialysis").setSelection($("#jqGrid_dialysis").getDataIDs()[0]);
				last_lineno_ = parseInt(selrowData("#jqGrid_dialysis").de_lineno_) + 1;

				button_state_dialysis('add_edit');
			}else{
				last_lineno_ = 1;
				button_state_dialysis('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_dialysis").inlineNav('#jqGridPager_dialysis', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_dialysis", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
		},
	});

	addParamField('#jqGrid_dialysis', false, urlParam_dialysis);

	$("#tabdialysis").on("shown.bs.collapse", function(){
		$("#jqGrid_dialysis").jqGrid ('setGridWidth', Math.floor($("#jqGrid_dialysis_c")[0].offsetWidth-$("#jqGrid_dialysis_c")[0].offsetLeft-0));
		urlParam_dialysis.filterCol = ['de.compcode','de.episno','de.mrn'],
		urlParam_dialysis.filterVal = ['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()]
		refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
	});
	
	$("#add_dialysis").click(function(){
		emptyFormdata_div('#form_dialysis');
		button_state_dialysis('wait');
		$('#dialysis_no').val(last_lineno_);
		$('#dialysis_patname').val($('#txt_epis_name').text());
		$('#dialysis_date').val(moment().format('YYYY-MM-DD'));
		$('#dialysis_time').val(moment().format('HH:mm:ss'));
		enableForm('#form_dialysis',['dialysis_no','dialysis_patname']);
		$("#save_dialysis").data('oper','add');
		
	});

	$("#edit_dialysis").click(function(){
		button_state_dialysis('wait');
		enableForm('#form_dialysis',['dialysis_no','dialysis_patname','dialysis_date','dialysis_time']);
		$("#save_dialysis").data('oper','edit');
		
	});

	$("#del_dialysis").click(function(){
		if (confirm("Are you sure, you want to delete this record?") == true) {

		    var postobj={
		        oper:'del',
		    	_token : $('#csrf_token').val(),
		    	mrn : $("#mrn_episode").val(),
		    	episno : $("#txt_epis_no").val(),
		    	packagecode : $("#dialysis_pkgcode").val(),
		    	arrival_date : $("#dialysis_date").val(),
				arrival_time : $("#dialysis_time").val(),
		    	idno: $('#dialysis_idno').val()
		    };

		    $.post( "./save_epis_dialysis", $.param(postobj) , function( data ) {
		        
		    },'json').fail(function(data) {
		        alert(data.responseText);
				refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
		    }).success(function(data){
				refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
		    });
		} else {
			
		}
		
	});

	$("#save_dialysis").click(function(){
		disableForm('#form_dialysis');
		if( $('#form_dialysis').isValid({requiredFields: ''}) ) {
			saveForm_dialysis(function(){
				refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
			});
		}else{
			enableForm('#form_dialysis',['dialysis_no','dialysis_patname','dialysis_date','dialysis_time']);
		}

	});

	function saveForm_dialysis(callback){

	    let doc_status = $('#doc_status').find(":selected").text();

	    var postobj={
	        oper:$("#save_dialysis").data('oper'),
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#mrn_episode").val(),
	    	episno : $("#txt_epis_no").val(),
	    	packagecode : $("#dialysis_pkgcode").val(),
	    	arrival_date : $("#dialysis_date").val(),
			arrival_time : $("#dialysis_time").val(),
	    	idno: $('#dialysis_idno').val()
	    };

	    $.post( "./save_epis_dialysis", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        alert(data.responseText);
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_dialysis").click(function(){
		button_state_dialysis('empty');
		disableForm('#form_dialysis');
		emptyFormdata_div('#form_dialysis');
		refreshGrid("#jqGrid_dialysis", urlParam_dialysis);

	});

	disableForm('#form_dialysis');
	button_state_dialysis('add');
	function button_state_dialysis(state){
		switch(state){
			case 'empty':
				$('#add_dialysis,#edit_dialysis,#save_dialysis,#cancel_dialysis,#del_dialysis').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_dialysis,#edit_dialysis,#del_dialysis").attr('disabled',false);
				$('#save_dialysis,#cancel_dialysis').attr('disabled',true);
				break;
			case 'add':
				$("#add_dialysis").attr('disabled',false);
				$('#edit_dialysis,#save_dialysis,#cancel_dialysis,#del_dialysis').attr('disabled',true);
				break;
			case 'wait':
				// dialog_tri_col.on();
				// examination.on().enable();
				$("#save_dialysis,#cancel_dialysis").attr('disabled',false);
				$('#add_dialysis,#edit_dialysis,#del_dialysis').attr('disabled',true);
				break;
		}
	}

	function populate_dialysis(obj){
		$("#dialysis_no").val(obj.de_lineno_);
		$("#dialysis_patname").val(obj.pm_Name);
		$("#dialysis_pkgcode").val(obj.de_packagecode);
		$("#dialysis_date").val(obj.de_arrival_date);
		$("#dialysis_time").val(obj.de_arrival_time);
		$("#dialysis_idno").val(obj.de_idno);
	}

});


var urlParam_dialysis = {
	action:'get_table_default',
	url:'util/get_table_default',
	field: '',
	fixPost:'true',
	table_name: ['hisdb.dialysis_episode AS de','hisdb.episode AS ep'],
	join_type:['LEFT JOIN'],
	join_onCol:['de.mrn'],
	join_onVal:['ep.MRN'],
	join_filterCol : [['ep.episno on =','ep.compcode =']],
    join_filterVal : [['de.episno','session.compcode']],
	filterCol:['de.compcode','de.episno','de.mrn'],
	filterVal:['session.compcode','',''],
	sortby:['de_idno desc']
}

function populate_dialysis_epis(obj){
	urlParam_dialysis.filterVal[1] = obj.Episno;
	urlParam_dialysis.filterVal[2] = obj.MRN;
}

function autoadd_dialysis(){
	var postobj={
	    _token : $('#csrf_token').val(),
        oper:'autoadd',
    	mrn : $("#mrn_episode").val(),
    	episno : $("#txt_epis_no").val(),
    };

    $.post( "./save_epis_dialysis", $.param(postobj) , function( data ) {
        
    },'json').fail(function(data){
		refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
    }).success(function(data){
		refreshGrid("#jqGrid_dialysis", urlParam_dialysis);
    });
}