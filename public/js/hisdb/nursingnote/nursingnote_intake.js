$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

/////////////////////////////parameter for jqGridAddNotesIntake1 url/////////////////////////////
var urlParam_AddNotesIntake1 = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INTAKE_MORNING'],
}

/////////////////////////////parameter for jqGridAddNotesIntake2 url/////////////////////////////
var urlParam_AddNotesIntake2 = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INTAKE_EVENING'],
}

/////////////////////////////parameter for jqGridAddNotesIntake3 url/////////////////////////////
var urlParam_AddNotesIntake3 = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INVCHART_NIGHT'],
}

// var mycurrency_nursing = new currencymode(['#oralamt1', '#oralamt2', '#oralamt3', '#oralamt4', '#oralamt5', '#oralamt6', '#oralamt7', '#oralamt8', '#oralamt9', '#oralamt10', '#oralamt11', '#oralamt12', '#oralamt13', '#oralamt14', '#oralamt15', '#oralamt16', '#oralamt17', '#oralamt18', '#oralamt19', '#oralamt20', '#oralamt21', '#oralamt22', '#oralamt23', '#oralamt24', '#intraamt1', '#intraamt2', '#intraamt3', '#intraamt4', '#intraamt5', '#intraamt6', '#intraamt7', '#intraamt8', '#intraamt9', '#intraamt10', '#intraamt11', '#intraamt12', '#intraamt13', '#intraamt14', '#intraamt15', '#intraamt16', '#intraamt17', '#intraamt18', '#intraamt19', '#intraamt20', '#intraamt21', '#intraamt22', '#intraamt23', '#intraamt24', '#otheramt1', '#otheramt2', '#otheramt3', '#otheramt4', '#otheramt5', '#otheramt6', '#otheramt7', '#otheramt8', '#otheramt9', '#otheramt10', '#otheramt11', '#otheramt12', '#otheramt13', '#otheramt14', '#otheramt15', '#otheramt16', '#otheramt17', '#otheramt18', '#otheramt19', '#otheramt20', '#otheramt21', '#otheramt22', '#otheramt23', '#otheramt24', '#urineamt1', '#urineamt2', '#urineamt3', '#urineamt4', '#urineamt5', '#urineamt6', '#urineamt7', '#urineamt8', '#urineamt9', '#urineamt10', '#urineamt11', '#urineamt12', '#urineamt13', '#urineamt14', '#urineamt15', '#urineamt16', '#urineamt17', '#urineamt18', '#urineamt19', '#urineamt20', '#urineamt21', '#urineamt22', '#urineamt23', '#urineamt24', '#vomitamt1', '#vomitamt2', '#vomitamt3', '#vomitamt4', '#vomitamt5', '#vomitamt6', '#vomitamt7', '#vomitamt8', '#vomitamt9', '#vomitamt10', '#vomitamt11', '#vomitamt12', '#vomitamt13', '#vomitamt14', '#vomitamt15', '#vomitamt16', '#vomitamt17', '#vomitamt18', '#vomitamt19', '#vomitamt20', '#vomitamt21', '#vomitamt22', '#vomitamt23', '#vomitamt24', '#aspamt1', '#aspamt2', '#aspamt3', '#aspamt4', '#aspamt5', '#aspamt6', '#aspamt7', '#aspamt8', '#aspamt9', '#aspamt10', '#aspamt11', '#aspamt12', '#aspamt13', '#aspamt14', '#aspamt15', '#aspamt16', '#aspamt17', '#aspamt18', '#aspamt19', '#aspamt20', '#aspamt21', '#aspamt22', '#aspamt23', '#aspamt24', '#otherout1', '#otherout2', '#otherout3', '#otherout4', '#otherout5', '#otherout6', '#otherout7', '#otherout8', '#otherout9', '#otherout10', '#otherout11', '#otherout12', '#otherout13', '#otherout14', '#otherout15', '#otherout16', '#otherout17', '#otherout18', '#otherout19', '#otherout20', '#otherout21', '#otherout22', '#otherout23', '#otherout24']);

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    textarea_init_intake();
    
    /////////////////////////////////////intakeoutput starts/////////////////////////////////////
    disableForm('#formIntake');
    
    $("#new_intake").click(function (){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
		$("#recorddate_intake").val(moment().format('YYYY-MM-DD'));
        emptyFormdata_div("#formIntake",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);

        document.getElementById("idno_intake").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_intake").click(function (){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
        // mycurrency_nursing.formatOnBlur();
        $("#recorddate_intake").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_intake").click(function (){
        disableForm('#formIntake');
        if($('#formIntake').isValid({requiredFields: ''}, conf, true)){
            // mycurrency_nursing.formatOff();
            saveForm_intake(function (){
                $("#cancel_intake").data('oper','edit');
                $("#cancel_intake").click();
                // mycurrency_nursing.formatOn();
                // $("#jqGridPagerRefresh").click();
            });
        }else{
            enableForm('#formIntake');
            rdonly('#formIntake');
        }
    });
    
    $("#cancel_intake").click(function (){
        disableForm('#formIntake');
        button_state_intake($(this).data('oper'));
        $('#tbl_intake_date').DataTable().ajax.reload();
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////intakeoutput ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    /////////////////////////////////////intakeoutput starts/////////////////////////////////////
    $('#tbl_intake_date tbody').on('click', 'tr', function (){
        var data = tbl_intake_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_intake_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formIntake",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_intake_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_intakeoutput_getdata();
        $("#idno_intake").val(data.idno);
        
        var saveParam = {
            action: 'get_table_intake',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formIntake",data.intakeoutput);
                
                button_state_intake('edit');
                textarea_init_intake();
            }else{
                button_state_intake('add');
                textarea_init_intake();
            }
        });

    });
    //////////////////////////////////////intakeoutput ends//////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridIntake1 = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesIntake1///////////////////////////////////////
	$("#jqGridAddNotesIntake1").jqGrid({
		datatype: "local",
		editurl: "./nursingnote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesIntake1",
		loadComplete: function (){
			if(addmore_jqgridIntake1.more == true){$('#jqGridAddNotesIntake1_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridIntake1.edit = addmore_jqgridIntake1.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesIntake1");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesIntake1_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addIntake1 = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesIntake1,#jqGridPagerRefresh_addnoteIntake1").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesIntake1_ilsave').click();
				// addmore_jqgridIntake1.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridIntake1.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesIntake1',urlParam_AddNotesIntake1,'add_notesIntake1');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesIntake1,#jqGridPagerRefresh_addnoteIntake1").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesIntake1',urlParam_AddNotesIntake1,'add_notesIntake1');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesIntake1').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingnote/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesIntake1_save',
				});
			$("#jqGridAddNotesIntake1").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesIntake1,#jqGridPagerRefresh_addnoteIntake1").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesIntake1/////////////////////////////////////
	$("#jqGridAddNotesIntake1").inlineNav('#jqGridPagerAddNotesIntake1', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addIntake1
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesIntake1", {
		id: "jqGridPagerRefresh_addnoteIntake1",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesIntake1", urlParam_AddNotesIntake1);
		},
	});
	//////////////////////////////////////////////end grid jqGridAddNotesIntake1//////////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridIntake2 = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesIntake2///////////////////////////////////////
	$("#jqGridAddNotesIntake2").jqGrid({
		datatype: "local",
		editurl: "./nursingnote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesIntake2",
		loadComplete: function (){
			if(addmore_jqgridIntake2.more == true){$('#jqGridAddNotesIntake2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridIntake2.edit = addmore_jqgridIntake2.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesIntake2");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesIntake2_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addIntake2 = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesIntake2,#jqGridPagerRefresh_addnoteIntake2").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesIntake2_ilsave').click();
				// addmore_jqgridIntake2.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridIntake2.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesIntake2',urlParam_AddNotesIntake2,'add_notesIntake2');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesIntake2,#jqGridPagerRefresh_addnoteIntake2").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesIntake2',urlParam_AddNotesIntake2,'add_notesIntake2');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesIntake2').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingnote/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesIntake2_save',
				});
			$("#jqGridAddNotesIntake2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesIntake2,#jqGridPagerRefresh_addnoteIntake2").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesIntake2/////////////////////////////////////
	$("#jqGridAddNotesIntake2").inlineNav('#jqGridPagerAddNotesIntake2', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addIntake2
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesIntake2", {
		id: "jqGridPagerRefresh_addnoteIntake2",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesIntake2", urlParam_AddNotesIntake2);
		},
	});
	//////////////////////////////////////////////end grid jqGridPagerAddNotesIntake2//////////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridIntake3 = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesIntake3///////////////////////////////////////
	$("#jqGridAddNotesIntake3").jqGrid({
		datatype: "local",
		editurl: "./nursingnote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesIntake3",
		loadComplete: function (){
			if(addmore_jqgridIntake3.more == true){$('#jqGridAddNotesIntake3_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridIntake3.edit = addmore_jqgridIntake3.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesIntake3");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesIntake3_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addIntake3 = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesIntake3,#jqGridPagerRefresh_addnoteIntake3").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesIntake3_ilsave').click();
				// addmore_jqgridIntake3.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridIntake3.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesIntake3',urlParam_AddNotesIntake3,'add_notesIntake3');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesIntake3,#jqGridPagerRefresh_addnoteIntake3").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesIntake3',urlParam_AddNotesIntake3,'add_notesIntake3');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesIntake3').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingnote/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesIntake3_save',
				});
			$("#jqGridAddNotesIntake3").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesIntake3,#jqGridPagerRefresh_addnoteIntake3").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesIntake3/////////////////////////////////////
	$("#jqGridAddNotesIntake3").inlineNav('#jqGridPagerAddNotesIntake3', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addIntake3
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesIntake3", {
		id: "jqGridPagerRefresh_addnoteIntake3",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesIntake3", urlParam_AddNotesIntake3);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
    
});

/////////////////////intakeoutput starts/////////////////////
var tbl_intake_date = $('#tbl_intake_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'recorddate', 'width': '25%' },
		{ 'data': 'adduser', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////intakeoutput ends//////////////////////

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_intake('empty');
function button_state_intake(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_intake').data('oper','add');
            $('#new_intake,#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','add');
            $("#new_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','edit');
            $("#new_intake,#edit_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_intake,#cancel_intake").attr('disabled',false);
            $('#edit_intake,#new_intake').attr('disabled',true);
            break;
    }
}

function populate_intakeoutput_getdata(){
    disableForm('#formIntake');
    emptyFormdata(errorField,"#formIntake",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_intake',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formIntake",data.intakeoutput);
            
            button_state_intake('edit');
            textarea_init_intake();
        }else{
            button_state_intake('add');
            textarea_init_intake();
        }
    });
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input=$(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value==1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else{
            input.val(value);
        }
    });
    // mycurrency_nursing.formatOn();
}

function saveForm_intake(callback){
    var saveParam = {
        action: 'save_table_intake',
        oper: $("#cancel_intake").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formIntake").serializeArray();
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formIntake input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // if(data.responseText !== ''){
        //     // $('#p_error_intake').text(data.responseText);
        //     alert(data.responseText);
        // }
        
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}

function textarea_init_intake(){
    $('textarea#oraltype1,textarea#oraltype2,textarea#oraltype3,textarea#oraltype4,textarea#oraltype5,textarea#oraltype6,textarea#oraltype7,textarea#oraltype8,textarea#oraltype9,textarea#oraltype10,textarea#oraltype11,textarea#oraltype12,textarea#oraltype13,textarea#oraltype14,textarea#oraltype15,textarea#oraltype16,textarea#oraltype17,textarea#oraltype18,textarea#oraltype19,textarea#oraltype20,textarea#oraltype21,textarea#oraltype22,textarea#oraltype23,textarea#oraltype24,textarea#intratype1,textarea#intratype2,textarea#intratype3,textarea#intratype4,textarea#intratype5,textarea#intratype6,textarea#intratype7,textarea#intratype8,textarea#intratype9,textarea#intratype10,textarea#intratype11,textarea#intratype12,textarea#intratype13,textarea#intratype14,textarea#intratype15,textarea#intratype16,textarea#intratype17,textarea#intratype18,textarea#intratype19,textarea#intratype20,textarea#intratype21,textarea#intratype22,textarea#intratype23,textarea#intratype24,textarea#othertype1,textarea#othertype2,textarea#othertype3,textarea#othertype4,textarea#othertype5,textarea#othertype6,textarea#othertype7,textarea#othertype8,textarea#othertype9,textarea#othertype10,textarea#othertype11,textarea#othertype12,textarea#othertype13,textarea#othertype14,textarea#othertype15,textarea#othertype16,textarea#othertype17,textarea#othertype18,textarea#othertype19,textarea#othertype20,textarea#othertype21,textarea#othertype22,textarea#othertype23,textarea#othertype24').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight>40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight < 50){
//         scrollHeight = 50;
//     }else if(scrollHeight > 300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }