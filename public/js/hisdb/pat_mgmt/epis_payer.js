var urlParam_epno_payer = {
	action:'pat_enq_payer',
	url:'./pat_enq/table',
	mrn:null,
	episno:null,
}

$(document).ready(function () {

	$('#my_a_payr').click(function(){
		var selrow = $("#jqGrid_episodelist").jqGrid ('getGridParam', 'selrow');
		if(selrow != null){
			$('#mdl_payer').modal('show');
		}else{
			alert('Please select episode first')
		}
	});

	var errorField_epno_payer = [];
	conf_epno_payer = {
		modules : 'logic',
		language: {
			requiredFields: 'You have not answered all required fields'
		},
		onValidate: function ($form) {
			if (errorField_epno_payer.length > 0) {
				return {
					element: $(errorField_epno_payer[0]),
					message: ''
				}
			}
		},
	};

	$("#jqGrid_epno_payer").jqGrid({
		datatype: "local",
		colModel: [
            { label: 'No', name: 'lineno', width: 30 },
            { label: 'Payer', name: 'payercode', width: 80  },
            { label: 'Name', name: 'payercode_desc', width: 200  },
            { label: 'Fin Class', name: 'pay_type' , width: 50 },
            { label: 'Limit Amt.', name: 'pyrlmtamt' , width: 100 },
            { label: 'All Group', name: 'allgroup' , width: 50, formatter: allgroupformat, unformat: allgroupunformat },
            { label: 'billtype_desc', name: 'billtype_desc' , hidden: true },
            { label: 'idno', name: 'idno', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true  },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'epistycode', name: 'epistycode', hidden: true },
            { label: 'pyrmode', name: 'pyrmode' , hidden: true },
            { label: 'alldept', name: 'alldept' , hidden: true },
            { label: 'lastupdate', name: 'lastupdate' , hidden: true },
            { label: 'lastuser', name: 'lastuser' , hidden: true },
            { label: 'billtype', name: 'billtype' , hidden: true },
            { label: 'refno', name: 'refno' , hidden: true },
            { label: 'computerid', name: 'computerid' , hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		viewrecords: false,
		width: 900,
		height: 150, 
		rowNum: 30,
		pager: "#jqGridPager_epno_payer",
		onSelectRow:function(rowid, selected){
			populate_epno_payer(selrowData("#jqGrid_epno_payer"));
		},
		loadComplete: function(){
			emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
			$('#jqGrid_epno_payer_ilsave,#jqGrid_epno_payer_ilcancel').hide();

			let reccount = $('#jqGrid_epno_payer').jqGrid('getGridParam', 'reccount');
			if(reccount>0){
				button_state_epno_payer('add_edit');
			}else{
				button_state_epno_payer('add');
			}
			$("#jqGrid_epno_payer").setSelection($("#jqGrid_epno_payer").getDataIDs()[0]);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
		},
		gridComplete: function () {
		},
	});

	$("#jqGrid_epno_payer").inlineNav('#jqGridPager_epno_payer', {edit:false,add:false,del:false,search:false,
		restoreAfterSelect: false
	}).jqGrid('navButtonAdd', "#jqGridPager_epno_payer", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		},
	});

	$("#tabPayer").on("shown.bs.collapse", function(){
		$("#jqGrid_epno_payer").jqGrid ('setGridWidth', Math.floor($("#jqGrid_epno_payer_c")[0].offsetWidth-$("#jqGrid_epno_payer_c")[0].offsetLeft-0));
		urlParam_epno_payer.mrn = $("#mrn_episode").val();
		urlParam_epno_payer.episno = $("#txt_epis_no").val();
		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
		$('#mrn_epno_payer').val($("#mrn_episode").val());
		$('#episno_epno_payer').val($("#txt_epis_no").val());
		$('#epistycode_epno_payer').val(selrowData('#jqGrid_episodelist').epistycode);
		$('#name_epno_payer').val($('#txt_epis_name').text());

		$("#btnglsave").data('from','payer');
		$("#refno_epno_payer_btn").on('click',btn_refno_info_onclick);
	});

	var epno_payer_payercode = new ordialog(
		'epno_payer_payercode', 'debtor.debtormast', '#payercode_epno_payer', 'errorField',
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 2, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 4, classes: 'pointer', checked: true, canSearch: true, or_search: true },
				{ label: 'debtortype', name: 'debtortype', width: 2, hidden:true },
			],
			urlParam: {
				url:'./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val()
			},
			ondblClickRow: function () {
				let selrow = selrowData('#'+epno_payer_payercode.gridname);
				$(epno_payer_payercode.textfield).parent().next().html('');
				$('#payercode_desc_epno_payer').val(selrow.name);
				$('#pay_type_epno_payer').val(selrow.debtortype);

			}
		},{
			title: "Select Payer Code",
			open: function () {
				epno_payer_payercode.urlParam.url='./pat_enq/table?action2=getpayercode&epistycode='+$('#epistycode_epno_payer').val();


				$('div[aria-describedby="otherdialog_epno_payer_payercode"]').css("z-index", "1200");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1100");
			}
		},'urlParam','radio','tab'
	);
	epno_payer_payercode.makedialog(false);
	
	$("#add_epno_payer").click(function(){
		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		button_state_epno_payer('wait');
		enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		epno_payer_payercode.on();
		$("#save_epno_payer").data('oper','add');
		$('#pyrlmtamt_epno_payer').val(9999999.99);
		$('#allgroup_epno_payer').val(1);

		var rows = $('#jqGrid_epno_payer').getGridParam("reccount");
		$('#lineno_epno_payer').val(rows+1);
	});

	$("#edit_epno_payer").click(function(){
		let selrow = $('#jqGrid_epno_payer').jqGrid ('getGridParam', 'selrow');
		if(selrow == null){
			alert('Select payer first!');
		}else{
			button_state_epno_payer('wait');
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
			epno_payer_payercode.on();
			$("#save_epno_payer").data('oper','edit');
		}
	});

	$("#save_epno_payer").click(function(){
		disableForm('#form_epno_payer');
		if( $('#form_epno_payer').isValid({requiredFields: ''}, conf_nok, true) ) {
			saveForm_epno_payer(function(){
				refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
			});
		}else{
			enableForm('#form_epno_payer',['mrn','episno','epistycode','name','billtype_desc','payercode_desc','ourrefno','lineno','pay_type','computerid','lastuser','lastupdate']);
		}

	});

	function saveForm_epno_payer(callback){

        var serializedForm = $("#form_epno_payer").serializeArray();

	    serializedForm = serializedForm.concat(
	        $('#form_epno_payer select').map(
	        function() {
	            return {"name": this.name, "value": this.value}
	        }).get()
		);

	    var obj={
	    	action:'save_payer',
	        oper:$("#save_epno_payer").data('oper'),
	    	_token : $('#csrf_token').val()
	    };

	    $.post( "./pat_enq/form", $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
	        
	    },'json').fail(function(data) {
	        // alert('there is an error');
	        callback();
	    }).success(function(data){
	        callback();
	    });
	}

	$("#cancel_epno_payer").click(function(){
		button_state_epno_payer('empty');
		disableForm('#form_epno_payer');
		epno_payer_payercode.off();

		emptyFormdata_div('#form_epno_payer',['#mrn_epno_payer','#episno_epno_payer','#epistycode_epno_payer','#name_epno_payer']);
		refreshGrid("#jqGrid_epno_payer", urlParam_epno_payer);
	});

	disableForm('#form_epno_payer');
	button_state_epno_payer('add');
	function button_state_epno_payer(state){
		switch(state){
			case 'empty':
				$('#add_epno_payer,#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add_edit':
				$("#add_epno_payer,#edit_epno_payer").attr('disabled',false);
				$('#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'add':
				$("#add_epno_payer").attr('disabled',false);
				$('#edit_epno_payer,#save_epno_payer,#cancel_epno_payer').attr('disabled',true);
				break;
			case 'wait':
				$("#save_epno_payer,#cancel_epno_payer").attr('disabled',false);
				$('#add_epno_payer,#edit_epno_payer').attr('disabled',true);
				break;
		}
	}

	function populate_epno_payer(obj){
		var form = '#form_epno_payer';
		var except = [];

		$.each(obj, function( index, value ) {
			var input=$(form+" [name='"+index+"']");
			console.log(input);
			if(input.is("[type=radio]")){
				$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
			}else if( except != undefined && except.indexOf(index) === -1){
				input.val(decodeEntities(value));
			}
		});
	}

});


// var refno_object = null;
// function btn_refno_info_onclick(){
//     if(refno_object == null){
//         refno_object = new refno_class();
//         refno_object.show_mdl();
//     }else{
//         refno_object.show_mdl();
//     }
// }

// function refno_class(){
//     var self = this;
//     var selrowdata = null;
//     $("#btn_epis_new_gl").click(function() {
//         $('#mdl_new_gl').data('oper','add');
//         $('#btnglsave').show();
//         $('#mdl_new_gl').modal('show');
//     });

//     $("#btn_epis_view_gl").click(function() {
//         $('#mdl_new_gl').data('oper','edit');
//         $('#btnglsave').hide();
//         $('#mdl_new_gl').modal('show');
//     });

//     $("#btnglclose").click(function() {
//         $('#glform').find("label").detach();
//         $("#glform").trigger('reset');
//         $("#glform").find('.has-error').removeClass("has-error");
//         $("#glform").find('.error').removeClass("error");
//         $("#glform").find('.has-success').removeClass("has-success");
//         $("#glform").find('.valid').removeClass("valid");
//         self.show_mdl();
//     });

//     this.refno_table = $('#tbl_epis_reference').DataTable( {
//         // "ajax": "/pat_mast/get_entry?action=get_refno_list&debtorcode=" + $('#hid_epis_payer').val() + "&mrn=" + $('#mrn_episode').val(),
//         "columns": [
//                     {'data': 'debtorcode' ,'width':'20%'},
//                     {'data': 'name' ,'width':'30%'},
//                     {'data': 'gltype' ,'width':'10%'},
//                     {'data': 'staffid','width':'10%' },
//                     {'data': 'refno','width':'10%' },
//                     {'data': 'startdate','width':'10%' },
//                     {'data': 'enddate','width':'10%' },
//                     {'data': 'ourrefno' ,'visible': false},
//                     {'data': 'childno' , 'visible': false, 'searchable':false}, 
//                     {'data': 'episno' , 'visible': false, 'searchable':false},
//                     {'data': 'medcase' , 'visible': false, 'searchable':false},
//                     {'data': 'mrn' , 'visible': false, 'searchable':false},
//                     {'data': 'relatecode' , 'visible': false, 'searchable':false},
//                     {'data': 'remark' , 'visible': false, 'searchable':false},
//                     {'data': 'startdate' , 'visible': false, 'searchable':false},
//                     {'data': 'enddate' , 'visible': false, 'searchable':false},
//             ],
//     });

//     this.show_mdl = function(first = false){
//         $('#mdl_reference').modal('show');
//         $('#btn_epis_view_gl').prop('disabled',true);
//         this.refno_table.ajax.url("pat_mast/get_entry?action=get_refno_list&debtorcode=" + $('#payercode_epno_payer').val() + "&mrn=" + $('#mrn_epno_payer').val()).load();
//     }

//     $('#tbl_epis_reference').on('dblclick', 'tr', function () {
//         let refno_item = self.refno_table.row( this ).data();
//         $('#ourrefno_epno_payer').val(refno_item["ourrefno"]);
//         $('#refno_epno_payer').val(refno_item["refno"]);
        
//         $('#mdl_reference').modal('hide');
//     });

//     $('#tbl_epis_reference').on('click', 'tr', function () {
//         let refno_item = self.refno_table.row( this ).data();
//         selrowdata = refno_item;
//         $('#btn_epis_view_gl').prop('disabled',false);
        
//         $('#tbl_epis_reference tr').removeClass('active');
//         $(this).addClass('active');
//     });

//     $('#mdl_reference').on('shown.bs.modal', function (e) {
//         self.refno_table.columns.adjust().draw();
//     });

//     $('#mdl_reference').on('show.bs.modal', function (e) {
//     	$(this).css('z-index',121);
//     });

//     $('#mdl_new_gl').on('shown.bs.modal', function (e) {
//         let oper = $('#mdl_new_gl').data('oper');
//         $('#newgl-textmrn').text($('#mrn_epno_payer').val());
//         $('#newgl-textname').text($('#name_epno_payer').val());
//         $('#newgl-gltype').val('Multi Volume');
//         $('#select_gl_tab li:first-child a').tab('show');
//         $('#txt_newgl_corpcomp').val($('#payercode_desc_epno_payer').val());
//         $('#hid_newgl_corpcomp').val($('#payercode_epno_payer').val());

//         if(oper=='edit'){
//             autoinsert_rowdata_gl(selrowdata);
//         }else if(oper=='add'){
//             loadcorpstaff_gl(selrowdata);
//         }
//         onchg_gltype();
//     });

//     $('#mdl_new_gl').on('show.bs.modal', function (e) {
//     	$(this).css('z-index',131);
//     });

//     $('#select_gl_tab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//         let selected_tab = $(e.target).text();
//         $('#newgl-gltype').val(selected_tab);
//         onchg_gltype();
//     });

//     function onchg_gltype(){
//         let selected_tab = $('#newgl-gltype').val();
//         $('#newgl-effdate').off('change');
//         $('#newgl-effdate,#newgl-expdate').val('');
//         $('#newgl-expdate').prop('readonly',false);
//         $('#newgl-visitno_div,#newgl-expdate_div,#newgl-effdate_div').show();
//         $('#newgl-effdate_div,#newgl-expdate_div,#newgl-visitno_div').removeClass('form-mandatory');

//         switch(selected_tab){
//             case 'Multi Volume':
//                 $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
//                 $('#newgl-effdate,#newgl-visitno').prop('required',true).addClass('form-mandatory');
//                 $('#newgl-expdate').prop('required',false);
//                 $('#newgl-expdate_div').hide();
//                 break;
//             case 'Multi Date':
//                 $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
//                 $('#newgl-effdate,#newgl-expdate').prop('required',true).addClass('form-mandatory');
//                 $('#newgl-visitno').prop('required',false);
//                 $('#newgl-visitno_div').hide();
//                 break;
//             case 'Open':
//                 $('#newgl-effdate').val(moment().format('YYYY-MM-DD'));
//                 $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
//                 $('#newgl-visitno,#newgl-expdate').prop('required',false);
//                 $('#newgl-visitno_div,#newgl-expdate_div').hide();
//                 break;
//             case 'Single Use':
//                 $('#newgl-effdate,#newgl-expdate').val(moment().format('YYYY-MM-DD'));
//                 $('#newgl-expdate').prop('readonly',true);
//                 $('#newgl-effdate').prop('required',true).addClass('form-mandatory');
//                 $('#newgl-visitno').prop('required',false);
//                 $('#newgl-visitno_div').hide();

//                 $('#newgl-effdate').on('change',function(){
//                     $('#newgl-expdate').val($(this).val());
//                 });

//                 break;
//             case 'Limit Amount':
//                 $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
//                 break;
//             case 'Monthly Amount':
//                 $('#newgl-effdate,#newgl-expdate,#newgl-visitno').prop('required',false);
//                 break;
//         }
//     }

//     $("#btnglsave").on('click',function(){
//         if($('#glform').valid()){
//             $("#btnglsave").prop('disabled',true);

//             var _token = $('#csrf_token').val();
//             let serializedForm = $( "#glform" ).serializeArray();
//             let obj = {
//                 'debtorcode':$('#hid_epis_payer').val(),
//                 'mrn':$('#mrn_epno_payer').val(),
//                 '_token': _token,
//                 'episno': $('#episno_epno_payer').val(),
//             };
            
//             $.post('pat_mast/save_gl', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                
//             },'json').fail(function(data) {
//                 alert(data.responseText);
//                 $("#btnglsave").prop('disabled',false);
//             }).success(function(data){
//                 $('#ourrefno_epno_payer').val(data.ourrefno);
//                 $('#refno_epno_payer').val(data.refno);
//                 $('#mdl_new_gl').modal('hide');
//                 $('#mdl_reference').modal('hide');
//                 $("#btnglsave").prop('disabled',false);

//                 $("#glform").trigger('reset');
//             });
//         }
//     });

// }

// function autoinsert_rowdata_gl(selrowdata){
//     $('#newgl-staffid').val(selrowdata.staffid);
//     $('#newgl-name').val(selrowdata.name);
//     $('#txt_newgl_corpcomp').val(selrowdata.debtor_name);
//     $('#hid_newgl_corpcomp').val(selrowdata.debtorcode);
//     $('#txt_newgl_occupcode').val(selrowdata.occup_desc);
//     $('#hid_newgl_occupcode').val(selrowdata.occupcode);
//     $('#txt_newgl_relatecode').val(selrowdata.relate_desc);
//     $('#hid_newgl_relatecode').val(selrowdata.relatecode);
//     $('#newgl-childno').val(selrowdata.childno);
//     $('#newgl-gltype').val(selrowdata.gltype);
//     $('#newgl-effdate').val(selrowdata.startdate);
//     $('#newgl-expdate').val(selrowdata.enddate);
//     $('#newgl-visitno').val(selrowdata.visitno);
//     $('#newgl-case').val(selrowdata.case);
//     $('#newgl-refno').val(selrowdata.refno);
//     $('#newgl-ourrefno').val(selrowdata.ourrefno);
//     $('#newgl-remark').val(selrowdata.remark);


//     $('#select_gl_tab a[href="'+selrowdata.gltype+'"]').tab('show');
// }

// function loadcorpstaff_gl(){
//     let obj_param = {
//            action:'loadcorpstaff',
//            mrn:parseInt($('#mrn_epno_payer').val())
//        };

//     $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
        
//     },'json').done(function(data) {
//         if(data.data != null){
//             $('#newgl-staffid').val(data.data.staffid);
//             $('#newgl-name').val(data.data.name);
//             $('#txt_newgl_corpcomp').val(data.data.debtor_name);
//             $('#hid_newgl_corpcomp').val(data.data.debtorcode);
//             $('#txt_newgl_occupcode').val(data.data.occup_desc);
//             $('#hid_newgl_occupcode').val(data.data.occupcode);
//             $('#txt_newgl_relatecode').val(data.data.relate_desc);
//             $('#hid_newgl_relatecode').val(data.data.relatecode);
//             $('#newgl-childno').val(data.data.childno);
//         }
//     });
// }

function allgroupformat(cellvalue, options, rowObject){
	if(cellvalue == '1'){
		return '<span data-orig='+cellvalue+'>Yes</span>';
	}else{
		return '<span data-orig='+cellvalue+'>No</span>';
	}
}

function allgroupunformat(cellvalue, options, rowObject){
	return $(rowObject).find('span').data('orig');
}

// var textfield_modal = new textfield_modal();
// textfield_modal.ontabbing();
// textfield_modal.checking();
// textfield_modal.clicking();