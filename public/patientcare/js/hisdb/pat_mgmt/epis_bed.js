$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {


	var errorField_doc = [];
	conf_bed = {
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

	var urlParam_bed = {
		action:'get_table_default',
		url:'util/get_table_default',
		field: '',
		table_name: 'hisdb.bedalloc',
		filterCol:['compcode','mrn','episno'],
		filterVal:['session.compcode','',''],
	}

	$("#jqGrid_bed").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'idno', name: 'idno' , hidden: true },
            { label: 'mrn', name: 'mrn' , hidden: true },
            { label: 'episno', name: 'episno' , hidden: true},
            { label: 'Assign Date', name: 'asdate' , width:3 },
            { label: 'Assign Time', name: 'astime' , width:3 },
            { label: 'Ward', name: 'ward' , width: 2 },
            { label: 'Room', name: 'room' , width: 1 },
            { label: 'Bed no', name: 'bednum', width: 2 },
            { label: 'name', name: 'name', hidden: true }
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 250, 
		rowNum: 30,
		sortname: 'idno',
		sortorder: 'desc',
		pager: "#jqGridPager_bed",
		onSelectRow:function(rowid, selected){
			// populate_bed(selrowData("#jqGrid_bed"));
		},
		loadComplete: function(){
			$('#jqGrid_bed_ilsave,#jqGrid_bed_ilcancel').hide();

			let reccount = $('#jqGrid_bed').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_bed('add_edit');
			}else{
				button_state_bed('add');
			}

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		}
	});

	$("#jqGrid_bed").inlineNav('#jqGridPager_bed', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_bed", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_bed", urlParam_bed);
		},
	});

	addParamField('#jqGrid_bed', false, urlParam_bed);

	$("#tabBed").on("shown.bs.collapse", function(){
		$("#jqGrid_bed").jqGrid ('setGridWidth', Math.floor($("#jqGrid_bed_c")[0].offsetWidth-$("#jqGrid_bed_c")[0].offsetLeft-0));
		urlParam_bed.filterCol = ['compcode','episno','mrn'],
		urlParam_bed.filterVal = ['session.compcode',$("#txt_epis_no").val(),$("#mrn_episode").val()]
		refreshGrid("#jqGrid_bed", urlParam_bed);
	});

	var search_bed = new ordialog(
		'search_bed', 'hisdb.bed', '#bed_bednum', 'errorField',
		{
			colModel: [
				{ label: 'Bed no.', name: 'bednum', width: 2, classes: 'pointer',checked: true, canSearch: true, or_search: true },
				{ label: 'Ward', name: 'ward', width: 4, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Room', name: 'room', width: 2, classes: 'pointer'},
				{ label: 'Bed Type', name: 'bedtype', width: 4, classes: 'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'occup'],
				filterVal:['session.compcode','A', 'VACANT']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + search_bed.gridname);
				$(search_bed.textfield).parent().next().html('');

				$('#bed_room').val(data.room);
				$('#bed_ward').val(data.ward);
				$("#bed_bedtype").val(data.bedtype);
			}
		},{
			title: "Select Bed Type search",
			open: function () {
				search_bed.urlParam.filterCol=['compcode', 'recstatus', 'occup'];
				search_bed.urlParam.filterVal=['session.compcode', 'A', 'VACANT'];

				$('div[aria-describedby="otherdialog_search_bed"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	search_bed.makedialog(false);

	var bed_lodger = new ordialog(
		'bed_lodger', 'debtor.debtormast', '#bed_lodger', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 2, classes: 'pointer',checked: true, canSearch: true, or_search: true },
				{ label: 'Name', name: 'debtorname', width: 4, classes: 'pointer', canSearch: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','A']
			},
			ondblClickRow: function () {
			}
		},{
			title: "Select Lodger",
			open: function () {
				search_bed.urlParam.filterCol=['compcode', 'recstatus'];
				search_bed.urlParam.filterVal=['session.compcode', 'A'];

				$('div[aria-describedby="otherdialog_search_bed"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			}
		},'urlParam','radio','tab'
	);
	bed_lodger.makedialog(false);
	
	$("#add_bed").click(function(){
		button_state_bed('wait');
		$("#bed_date").val(moment().format("DD/MM/YYYY"));
		$("#bed_time").val(moment().format("HH:mm:ss"));
		enableForm('#form_bed',['bed_date','bed_time','bed_bedtype','bed_bednum','bed_room','bed_ward','bed_lodger']);
		search_bed.on();
		bed_lodger.on();
		
	});

	$("#save_bed").click(function(){
		disableForm('#form_bed');
		if( $('#form_bed').isValid({requiredFields: ''}, conf_bed, true) ) {
			saveForm_bed(function(){
				refreshGrid("#jqGrid_bed", urlParam_bed);
			});
		}else{
			enableForm('#form_bed',['bed_date','bed_time','bed_bedtype','bed_bednum','bed_room','bed_ward','bed_lodger']);
		}

	});

	function saveForm_bed(callback){
	    var postobj={
	    	name : $('#txt_epis_name').text(),
	    	_token : $('#csrf_token').val(),
	    	mrn : $("#mrn_episode").val(),
	    	episno : $("#txt_epis_no").val(),
	    	epistycode : $("#epistycode").val(),
	    	bed_date : $('#bed_date').val(),
	    	bed_time : $('#bed_time').val(),
	    	bed_bednum : $('#bed_bednum').val(),
	    	bed_room : $('#bed_room').val(),
	    	bed_ward : $('#bed_ward').val(),
	    	bed_status : $('#bed_status').val(),
	    	bed_isolate : $('#bed_isolate').val(),
	    	bed_lodger : $('#bed_lodger').val()
	    };

	    $.post( "episode/save_bed", $.param(postobj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_bed").click(function(){
		button_state_bed('add');
		disableForm('#form_bed');
		search_bed.off();
		bed_lodger.off();
		emptyFormdata_div('#form_bed');
		refreshGrid("#jqGrid_bed", urlParam_bed);

	});

	disableForm('#form_bed');
	button_state_bed('add');
	function button_state_bed(state){
		switch(state){
			case 'empty':
				$('#add_bed,#save_bed,#cancel_bed').attr('disabled',true);
				break;
			case 'add':
				$("#add_bed").attr('disabled',false);
				$('#save_bed,#cancel_bed').attr('disabled',true);
				break;
			case 'wait':
				$("#save_bed,#cancel_bed").attr('disabled',false);
				$('#add_bed').attr('disabled',true);
				break;
		}
	}

	function populate_bed(obj){
		$("#doc_no").val(obj.da_allocno);
		$("#doc_doctorcode").val(obj.da_doctorcode);
		$("#doc_doctorname").val(obj.d_doctorname);
		$("#doc_discipline").val(obj.d_disciplinecode);
		$("#doc_date").val(obj.da_asdate);
		$("#doc_time").val(obj.da_astime);
		$("#doc_status").val(obj.da_astatus);
	}

});