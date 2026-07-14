$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

///////////////////////////////////parameter for jqGridAddNotesProgressED url///////////////////////////////////
var urlParam_AddNotesProgressED = {
	action: 'get_table_default',
	url: './util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','PROGRESSNOTE_ED'],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    ////////////////////////////////////////////progressnote starts////////////////////////////////////////////
    disableForm('#formProgress_ED');
    
    $("#new_progress_ED").click(function (){
        button_state_progress_ED('wait');
        enableForm('#formProgress_ED');
        rdonly('#formProgress_ED');
        emptyFormdata_div("#formProgress_ED",['#mrn_emergencyMain','#episno_emergencyMain','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_progress_ED").value = "";
    });
    
    $("#edit_progress_ED").click(function (){
        button_state_progress_ED('wait');
        enableForm('#formProgress_ED');
        rdonly('#formProgress_ED');
        $("#formProgress_ED :input[name='datetaken'],#formProgress_ED :input[name='timetaken']").attr("readonly", true);
    });

    $("#save_progress_ED").click(function (){
        disableForm('#formProgress_ED');
        if($('#formProgress_ED').isValid({requiredFields: ''}, conf, true)){
            saveForm_progress_ED(function (){
                $("#cancel_progress_ED").data('oper','edit');
                $("#cancel_progress_ED").click();
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_ED_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress_ED');
            rdonly('#formProgress_ED');
        }
    });
    
    $("#cancel_progress_ED").click(function (){
        disableForm('#formProgress_ED');
        button_state_progress_ED($(this).data('oper'));
        $('#datetime_ED_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////////////progressnote ends//////////////////////////////////////////////  

    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

    // to limit to two decimal places (onkeypress)
    $(document).on('keydown', 'input[pattern]', function (e){
        var input = $(this);
        var oldVal = input.val();
        var regex = new RegExp(input.attr('pattern'), 'g');
        
        setTimeout(function (){
            var newVal = input.val();
            if(!regex.test(newVal)){
                input.val(oldVal);
            }
        }, 0);
    });

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridProgressED = {more:false,state:false,edit:false}
	
	///////////////////////////////////////////jqGridAddNotesProgressED///////////////////////////////////////////
	$("#jqGridAddNotesProgressED").jqGrid({
		datatype: "local",
		editurl: "/ptcare_nursingnote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
		],
		autowidth: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		scroll: true,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerAddNotesProgressED",
		loadComplete: function (){
			if(addmore_jqgridProgressED.more == true){$('#jqGridAddNotesProgressED_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridProgressED.edit = addmore_jqgridProgressED.more = false; // reset
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridAddNotesProgressED_iledit").click();
		},
	});
	
	////////////////////////////////////////////myEditOptions////////////////////////////////////////////
	var myEditOptions_addProgressED = {
		keys: true,
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotes_ilsave').click();
				// addmore_jqgridProgressED.state = true;
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridProgressED.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesProgressED',urlParam_AddNotesProgressED,'add_ProgressED');
			errorField.length = 0;
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesProgressED',urlParam_AddNotesProgressED,'add_ProgressED');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			if(errorField.length > 0)return false;
			
			let data = $('#jqGridAddNotesProgressED').jqGrid('getRowData', rowid);
			
			let editurl = "/ptcare_nursingnote/form?"+
				$.param({
					_token: $('#_token').val(),
					episno: $('#episno_emergencyMain').val(),
					mrn: $('#mrn_emergencyMain').val(),
					action: 'add_ProgressED_save',
				});
			$("#jqGridAddNotesProgressED").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function (response){
			$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////////jqGridPagerAddNotesProgressED/////////////////////////////////////////
	$("#jqGridAddNotesProgressED").inlineNav('#jqGridPagerAddNotesProgressED', {
		add: true,
		edit: false,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addProgressED
		},
		// editParams: myEditOptions_edit
	})
	// .jqGrid('navButtonAdd', "#jqGridPagerAddNotesProgressED", {
	// 	id: "jqGridPagerDelete",
	// 	caption: "", cursor: "pointer", position: "last",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function (){
	// 		selRowId = $("#jqGridAddNotesProgressED").jqGrid('getGridParam', 'selrow');
	// 		if(!selRowId){
	// 			alert('Please select row');
	// 		}else{
	// 			var result = confirm("Are you sure you want to delete this row?");
	// 			if(result == true){
	// 				param = {
	// 					_token: $("#csrf_token").val(),
	// 					action: 'doctornote_save',
	// 					idno: selrowData('#jqGridAddNotesProgressED').idno,
	// 				}
					
	// 				$.post("/doctornote/form?"+$.param(param), {oper:'del'}, function (data){
						
	// 				}).fail(function (data){
	// 					//////////////////errorText(dialog,data.responseText);
	// 				}).done(function (data){
	// 					refreshGrid("#jqGridAddNotesProgressED", urlParam_AddNotesProgressED);
	// 				});
	// 			}else{
	// 				$("#jqGridPagerDelete,#jqGridPagerRefresh_addnotes").show();
	// 			}
	// 		}
	// 	},
	// })
	.jqGrid('navButtonAdd', "#jqGridPagerAddNotesProgressED", {
		id: "jqGridPagerRefresh_addnotes",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesProgressED", urlParam_AddNotesProgressED);
		},
	});
	///////////////////////////////////////////////end grid///////////////////////////////////////////////
    
    ////////////////////////////////////////progressnote starts////////////////////////////////////////
    $('#datetime_ED_tbl tbody').on('click', 'tr', function (){
        var data = datetime_ED_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetime_ED_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formProgress_ED",['#mrn_emergencyMain','#episno_emergencyMain','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetime_ED_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#formProgress_ED :input[name='idno_progress_ED']").val(data.idno);

        urlParam_AddNotesProgressED.filterVal[0] = data.mrn;
        urlParam_AddNotesProgressED.filterVal[1] = data.episno;
        urlParam_AddNotesProgressED.filterVal[2] = 'PROGRESSNOTE_ED';
        refreshGrid('#jqGridAddNotesProgressED',urlParam_AddNotesProgressED,'add_ProgressED');

        var saveParam={
            action: 'get_table_progress_ED',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno,
            // date:data.date

        };
        
        $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formProgress_ED",data.nurshandover);
                // $("#formProgress_ED :input[name='datetaken']").val(data.date);
                // $("#formProgress_ED :input[name='timetaken']").val(data.time);
                
                button_state_progress_ED('edit');
                textarea_init_nursingnote();
            }else{
                button_state_progress_ED('add');
                textarea_init_nursingnote();
            }
        });
    });
    
});

/////////////////////progressnote starts/////////////////////
var datetime_ED_tbl = $('#datetime_ED_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetaken', 'width': '25%' },
        { 'data': 'timetaken', 'width': '25%' },
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'location', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////progressnote ends//////////////////////

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

button_state_progress_ED('empty');
function button_state_progress_ED(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_progress_ED').data('oper','add');
            $('#new_progress_ED,#save_progress_ED,#cancel_progress_ED,#edit_progress_ED').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress_ED').data('oper','add');
            $("#new_progress_ED").attr('disabled',false);
            $('#save_progress_ED,#cancel_progress_ED,#edit_progress_ED').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress_ED').data('oper','edit');
            $("#edit_progress_ED,#new_progress_ED").attr('disabled',false);
            $('#save_progress_ED,#cancel_progress_ED').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress_ED,#cancel_progress_ED").attr('disabled',false);
            $('#edit_progress_ED,#new_progress_ED').attr('disabled',true);
            break;
    }
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input = $(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value == 1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else if(input.is("textarea")){
            if(value !== null){
                let newval = value.replaceAll("</br>",'\n');
                input.val(newval);
            }
        }else{
            input.val(value);
        }
    });
}

function saveForm_progress_ED(callback){
    let oper = $("#cancel_progress_ED").data('oper');

    var saveParam = {
        action: 'save_table_progress_ED',
        oper: oper,
    }

    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn_emergencyMain: $('#mrn_emergencyMain').val(),
        episno_emergencyMain: $("#episno_emergencyMain").val(),
        epistycode: 'OP'
    };
    
    values = $("#formProgress_ED").serializeArray();
    
    values = values.concat(
        $('#formProgress_ED input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function populate_progressnote_ED_getdata(){
    disableForm('#formProgress_ED');
    emptyFormdata(errorField,"#formProgress_ED",["#mrn_emergencyMain","#episno_emergencyMain","#doctor_nursNote","#ordcomtt_phar"]);

    var saveParam = {
        action: 'get_table_progress_ED',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_emergencyMain").val(),
        episno: $("#episno_emergencyMain").val(),
        // idno: $("#idno_progress_ED").val(),

    };
    
    $.get("./ptcare_nursingnote/table?"+$.param(saveParam), $.param(postobj), function (data){
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress_ED",data.nurshandover);
            // $("#formProgress_ED :input[name='datetaken']").val(data.date);
            // $("#formProgress_ED :input[name='timetaken']").val(data.time);
            
            button_state_progress_ED('edit');
            textarea_init_nursingnote();
        }else{
            button_state_progress_ED('add');
            textarea_init_nursingnote();
        }
        
    });

    // var urlparam_datetime_ED_tbl = {
    //     action: 'get_table_datetime_ED',
    //     mrn: $("#mrn_emergencyMain").val(),
    //     episno: $("#episno_emergencyMain").val()
    // }
    
    // datetime_ED_tbl.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_datetime_ED_tbl)).load(function (data){
    //     emptyFormdata_div("#formProgress_ED",['#mrn_emergencyMain','#episno_emergencyMain','#doctor_nursNote','#ordcomtt_phar']);
    //     $('#datetime_ED_tbl tbody tr:eq(0)').click();  // to select first row
    // });
}
