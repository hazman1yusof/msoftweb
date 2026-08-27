$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

/////////////////////////////parameter for jqGridAddNotesPivc url/////////////////////////////
var urlParam_AddNotesPivc = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','PIVC'],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    /////////////////////////////////////pivc starts/////////////////////////////////////
    disableForm('#formPivc');
    
    $("#new_pivc").click(function (){
        button_state_pivc('wait');
        enableForm('#formPivc');
        rdonly('#formPivc');
        $("#practiceDate").val(moment().format('YYYY-MM-DD'));
        emptyFormdata_div("#formPivc",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_pivc").value = "";
    });
    
    $("#edit_pivc").click(function (){
        button_state_pivc('wait');
        enableForm('#formPivc');
        rdonly('#formPivc');
        $("#practiceDate").attr("readonly", true);
    });
    
    $("#save_pivc").click(function (){
        disableForm('#formPivc');
        if($('#formPivc').isValid({requiredFields: ''}, conf, true)){
            saveForm_pivc(function (){
                $("#cancel_pivc").data('oper','edit');
                $("#cancel_pivc").click();
                // $('#datetimepivc_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formPivc');
            rdonly('#formPivc');
        }
    });
    
    $("#cancel_pivc").click(function (){
        disableForm('#formPivc');
        button_state_pivc($(this).data('oper'));
        $('#datetimepivc_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////pivc ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    ////////////////////////////////////print button starts////////////////////////////////////

    $("#PIVCDialog").dialog({
        autoOpen: false,
        width: 5/10 * $(window).width(),
        modal: true,
        open: function (){
            parent_close_disabled(true);
        },
        close: function (event, ui){
            parent_close_disabled(false);
            emptyFormdata(errorField,'#formdata_PIVC');
        },
        buttons: [{
            text: "Print", click: function (){
                window.open('./pivc/pivc_chart?mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()+'&datefr='+$("#datefr_pivc").val()+'&dateto='+$("#dateto_pivc").val(), '_blank');
            }
        },{
            text: "Cancel", click: function (){
                $(this).dialog('close');
                emptyFormdata(errorField,'#formdata_PIVC');
            }
        }],
    });

    $('#pivc_chart').click(function(){
		$( "#PIVCDialog" ).dialog( "open" );
	});
    /////////////////////////////////////print button ends/////////////////////////////////////

    ////////////////////////////////////print button starts////////////////////////////////////
    
    // $("#pivc_chart").click(function (){
    //     window.open('./pivc/pivc_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&practiceDate='+$("#practiceDate").val(), '_blank');
    // });
    /////////////////////////////////////print button ends/////////////////////////////////////

    ////////////////////////////////////////pivc starts////////////////////////////////////////
    $('#datetimepivc_tbl tbody').on('click', 'tr', function (){
        var data = datetimepivc_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimepivc_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPivc",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimepivc_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_pivc").val(data.idno);
        
        var saveParam = {
            action: 'get_table_pivc',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./pivc/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formPivc",data.pivc);
                
                button_state_pivc('edit');
            }else{
                button_state_pivc('add');
            }
        });
    });
    /////////////////////////////////////////pivc ends/////////////////////////////////////////

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridPivc = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesPivc///////////////////////////////////////
	$("#jqGridAddNotesPivc").jqGrid({
		datatype: "local",
		editurl: "./pivc/form",
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
		pager: "#jqGridPagerAddNotesPivc",
		loadComplete: function (){
			if(addmore_jqgridPivc.more == true){$('#jqGridAddNotesPivc_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridPivc.edit = addmore_jqgridPivc.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesPivc");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesPivc_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addPivc = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesPivc,#jqGridPagerRefresh_addnotePivc").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesPivc_ilsave').click();
				// addmore_jqgridPivc.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridPivc.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesPivc',urlParam_AddNotesPivc,'add_notesPivc');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesPivc,#jqGridPagerRefresh_addnotePivc").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesPivc',urlParam_AddNotesPivc,'add_notesPivc');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesPivc').jqGrid ('getRowData', rowid);
			
			let editurl = "./pivc/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesPivc_save',
				});
			$("#jqGridAddNotesPivc").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesPivc,#jqGridPagerRefresh_addnotePivc").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesPivc/////////////////////////////////////
	$("#jqGridAddNotesPivc").inlineNav('#jqGridPagerAddNotesPivc', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addPivc
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesPivc", {
		id: "jqGridPagerRefresh_addnotePivc",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesPivc", urlParam_AddNotesPivc);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////

    
});

/////////////////////pivc starts/////////////////////
var datetimepivc_tbl = $('#datetimepivc_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'practiceDate', 'width': '20%' },
        { 'data': 'adduser', 'width': '20%' },

    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////pivc ends//////////////////////

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

button_state_pivc('empty');
function button_state_pivc(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_pivc').data('oper','add');
            $('#new_pivc,#save_pivc,#cancel_pivc,#edit_pivc').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc').data('oper','add');
            $("#new_pivc").attr('disabled',false);
            $('#save_pivc,#cancel_pivc,#edit_pivc').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc').data('oper','edit');
            $("#new_pivc,#edit_pivc").attr('disabled',false);
            $('#save_pivc,#cancel_pivc').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_pivc,#cancel_pivc").attr('disabled',false);
            $('#edit_pivc,#new_pivc').attr('disabled',true);
            break;
    }
}

function populate_pivc_getdata(){
    disableForm('#formPivc');
    emptyFormdata(errorField,"#formPivc",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_pivc',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./pivc/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formPivc",data.pivc);
           
            button_state_pivc('edit');
        }else{
            button_state_pivc('add');
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
}

/////////////////////////////////////////////////////pivc starts/////////////////////////////////////////////////////

function saveForm_pivc(callback){
    var saveParam = {
        action: 'save_table_pivc',
        oper: $("#cancel_pivc").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
    };
    
    values = $("#formPivc").serializeArray();
    
    values = values.concat(
        $('#formPivc input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formPivc input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./pivc/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        
        callback();
    }).success(function (data){
        callback();
    });
}
/////////////////////////////////////////////////////pivc ends/////////////////////////////////////////////////////
