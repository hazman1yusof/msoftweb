
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

//////////////////////////////////parameter for jqGridThrombo_ED url//////////////////////////////////
var urlParam_Thrombo_ED = {
    action: 'get_table_default',
    url: './util/get_table_default',
    field: '',
    table_name: 'nursing.thrombophlebitisadd',
    table_id: 'idno',
    filterCol: ['mrn','episno','cannulationNo'],
    filterVal: ['','',$("#formThrombo_ED :input[name='idno_thrombo']").val()],
    
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'add_thrombojqgrid_ED');
        
    /////////////////////////////////////thrombo starts/////////////////////////////////////
    disableForm('#formThrombo_ED');
    
    $("#new_thrombo_ED").click(function (){
        button_state_thrombo_ED('wait');
        enableForm('#formThrombo_ED');
        rdonly('#formThrombo_ED');
        emptyFormdata_div("#formThrombo_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_thrombo").value = "";
    });
    
    $("#edit_thrombo_ED").click(function (){
        button_state_thrombo_ED('wait');
        enableForm('#formThrombo_ED');
        rdonly('#formThrombo_ED');
        $("#formThrombo_ED :input[name='dateInsert']").attr("readonly", true);
    });
    
    $("#save_thrombo_ED").click(function (){
        disableForm('#formThrombo_ED');
        if($('#formThrombo_ED').isValid({requiredFields: ''}, conf, true)){
            saveForm_thrombo_ED(function (){
                $("#cancel_thrombo_ED").data('oper','edit');
                $("#cancel_thrombo_ED").click();
                // $('#datetimethrombo_ED_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formThrombo_ED');
            rdonly('#formThrombo_ED');
        }
    });
    
    $("#cancel_thrombo_ED").click(function (){
        disableForm('#formThrombo_ED');
        button_state_thrombo_ED($(this).data('oper'));
        $('#datetimethrombo_ED_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////thrombo ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

    ////////////////////////////////////print button starts////////////////////////////////////
    
    $("#thrombo_ED_chart").click(function (){
        window.open('./thrombophlebitis/thrombophlebitis_chart?mrn='+$('#mrn_doctorNote').val()+'&episno='+$("#episno_doctorNote").val()+'&dateInsert='+$("#dateInsert_ED").val(), '_blank');
    });
    
    ////////////////////////////////////////thrombo starts////////////////////////////////////////
    $('#datetimethrombo_ED_tbl tbody').on('click', 'tr', function (){
        var data = datetimethrombo_ED_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimethrombo_ED_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formThrombo_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimethrombo_ED_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#formThrombo_ED :input[name='idno_thrombo']").val(data.idno);
        $("#formThrombo_ED :input[name='cannulationNo']").val(data.idno);

        //// jqGridThrombo_ED
        urlParam_Thrombo_ED.filterVal[0] = data.mrn;
        urlParam_Thrombo_ED.filterVal[1] = data.episno;
        urlParam_Thrombo_ED.filterVal[2] = data.idno;
        refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'add_thrombojqgrid_ED');

        var saveParam = {
            action: 'get_table_thrombo_ED',
        }
        
        var postobj = {
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formThrombo_ED",data.thrombo);
                
                button_state_thrombo_ED('edit');
            }else{
                button_state_thrombo_ED('add');
            }
        });
    });
    /////////////////////////////////////////thrombo ends/////////////////////////////////////////
    
    /////////////////////////////////////////parameter for saving url/////////////////////////////////////////
    var addmore_jqgrid_thrombo_ED = { more:false,state:false,edit:false }
    
    //////////////////////////////////////////////jqGridThrombo_ED//////////////////////////////////////////////
    $("#jqGridThrombo_ED").jqGrid({
        datatype: "local",
        editurl: "./ptcare_nursingnote/form",
        colModel: [
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', width: 10, hidden: true },
            { label: 'mrn', name: 'mrn', width: 10, hidden: true },
            { label: 'episno', name: 'episno', width: 10, hidden: true },
            { label: 'Flushing<br>Done', name: 'flushingDone', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "Yes:YES;No:NO"
				}
			},
            { label: 'Date', name: 'dateAssessment', width: 100, classes: 'wrap', editable: true, 
				formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' }, 
				editoptions: {
					dataInit: function (element){
						$(element).datepicker({
							id: 'dateAssessment_datePicker',
							dateFormat: 'yy-mm-dd',
							minDate: new Date($("#dateInsert").val()),
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect : function (){
								$(this).focus();
							}
						});
					}
				}
			},
            { label: 'Shift', name: 'shift', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "Morning:MORNING;Evening:EVENING;Night:NIGHT"
				}
			},
            { label: 'Dressing<br>Changed', name: 'dressingChanged', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "Yes:YES;No:NO"
				}
			},
			{ label: 'Sign/Name', name: 'staffId', width: 180, editable: false },
			{ label: 'Phlebitis<br>Grade', name: 'phlebitisGrade', width: 100, editable: true },
            { label: 'Infiltration', name: 'infiltration', width: 100, editable: true },
			{ label: 'Hematoma', name: 'hematoma', width: 100, editable: true },
			{ label: 'Extravasation', name: 'extravasation', width: 100, editable: true },
			{ label: 'Occlusion', name: 'occlusion', width: 100, editable: true },
			{ label: 'As per<br>protocol', name: 'asPerProtocol', width: 100, editable: true },
			{ label: 'Pt Discharged', name: 'ptDischarged', width: 100, editable: true },
			{ label: 'IV Terminate', name: 'ivTerminate', width: 100, editable: true },
			{ label: 'Fibrin Clot', name: 'fibrinClot', width: 100, editable: true },
			{ label: 'Kinked<br>Hub', name: 'kinkedHub', width: 100, editable: true },
			{ label: 'Kinked<br>Shaft', name: 'kinkedShaft', width: 100, editable: true },
			{ label: 'Tip Damage', name: 'tipDamage', width: 100, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden: true },
            { label: 'adddate', name: 'adddate', width: 50, hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
            { label: 'lastcomputerid', name: 'computerid', hidden: true },
            { label: 'cannulationNo', name: 'cannulationNo', hidden: true },

        ],
        shrinkToFit: false,
        autowidth: false,
        multiSort: false,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 1800,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerThrombo_ED",
        loadComplete: function (){
            if(addmore_jqgrid_thrombo_ED.more == true){$('#jqGridThrombo_ED_iladd').click();}
            else{
                $('#jqGridThrombo_ED').jqGrid('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid_thrombo_ED.edit = addmore_jqgrid_thrombo_ED.more = false; // reset
            calc_jq_height_onchange("jqGridThrombo_ED");
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridThrombo_ED_iledit").click();
        },
    });
    
    $("#jqGridThrombo_ED").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            { startColumnName: 'dateAssessment', numberOfColumns: 4, titleText: 'Daily Assessment Record' },
            { startColumnName: 'phlebitisGrade', numberOfColumns: 8, titleText: 'Reason for Removal' },
            { startColumnName: 'fibrinClot', numberOfColumns: 4, titleText: 'Catheter Removal Status' },
        ]
    });
    
    /////////////////////////////////////////myEditOptions_add_thrombo_ED/////////////////////////////////////////
    var myEditOptions_add_thrombo_ED = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").hide();
            
            $("input[name='tipDamage']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridThrombo_ED_ilsave').click();
                // addmore_jqgrid_thrombo_ED.state = true;
                // $('#jqGridThrombo_ED_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            addmore_jqgrid_thrombo_ED.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'add_thrombojqgrid_ED');
            errorField.length = 0;
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'add_thrombojqgrid_ED');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridThrombo_ED').jqGrid('getRowData', rowid);
            
            let editurl = "./ptcare_nursingnote/form?"+
                $.param({
                    action: 'thrombo_ED_save',
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    cannulationNo: $("#formThrombo_ED :input[name='idno_thrombo']").val()
                });
            $("#jqGridThrombo_ED").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////myEditOptions_edit_thrombo_ED/////////////////////////////////////////
    var myEditOptions_edit_thrombo_ED = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").hide();
            
            $("input[name='tipDamage']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridThrombo_ED_ilsave').click();
                // addmore_jqgrid_thrombo_ED.state = true;
                // $('#jqGridThrombo_ED_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid_thrombo_ED.state == true)addmore_jqgrid_thrombo_ED.more = true; // only addmore after save inline
            // addmore_jqgrid_thrombo_ED.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'edit_thrombojqgrid_ED');
            errorField.length = 0;
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridThrombo_ED',urlParam_Thrombo_ED,'edit_thrombojqgrid_ED');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGridThrombo_ED').jqGrid ('getRowData', rowid);
            
            let editurl = "./ptcare_nursingnote/form?"+
                $.param({
                    action: 'thrombo_ED_edit',
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    idno: selrowData('#jqGridThrombo_ED').idno,
                });
            $("#jqGridThrombo_ED").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////////////////jqGridPager////////////////////////////////////////////////
    $("#jqGridThrombo_ED").inlineNav('#jqGridPagerThrombo_ED', {
        add: true,
        edit: true,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_thrombo_ED
        },
        editParams: myEditOptions_edit_thrombo_ED
    }).jqGrid('navButtonAdd', "#jqGridPagerThrombo_ED", {
        id: "jqGridPagerDelete_thrombo_ED",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridThrombo_ED").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'thrombo_ED_del',
                        idno: selrowData('#jqGridThrombo_ED').idno,
                    }
                    $.post("./ptcare_nursingnote/form?"+$.param(param),{oper:'del_thrombojqgrid_ED'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridThrombo_ED", urlParam_Thrombo_ED);
                    });
                }else{
                    $("#jqGridPagerDelete_thrombo_ED,#jqGridPagerRefresh_thrombo_ED").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerThrombo_ED", {
        id: "jqGridPagerRefresh_thrombo_ED",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridThrombo_ED", urlParam_Thrombo_ED);
        },
    });
    ////////////////////////////////////////////////jqGrid rof ends////////////////////////////////////////////////
});

/////////////////////thrombo starts/////////////////////
var datetimethrombo_ED_tbl = $('#datetimethrombo_ED_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateInsert', 'width': '25%' },
        { 'data': 'timeInsert', 'width': '25%' },
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
//////////////////////thrombo ends//////////////////////

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

button_state_thrombo_ED('empty');
function button_state_thrombo_ED(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_thrombo_ED').data('oper','add');
            $('#new_thrombo_ED,#save_thrombo_ED,#cancel_thrombo_ED,#edit_thrombo_ED').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_thrombo_ED').data('oper','add');
            $("#new_thrombo_ED").attr('disabled',false);
            $('#save_thrombo_ED,#cancel_thrombo_ED,#edit_thrombo_ED').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_thrombo_ED').data('oper','edit');
            $("#new_thrombo_ED,#edit_thrombo_ED").attr('disabled',false);
            $('#save_thrombo_ED,#cancel_thrombo_ED').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_thrombo_ED,#cancel_thrombo_ED").attr('disabled',false);
            $('#edit_thrombo_ED,#new_thrombo_ED').attr('disabled',true);
            break;
    }
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

/////////////////////////////////////////////////////thrombo starts/////////////////////////////////////////////////////

function saveForm_thrombo_ED(callback){
    let oper = $("#cancel_thrombo_ED").data('oper');

    var saveParam = {
        action: 'save_table_thrombo_ED',
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
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
    };
    
    values = $("#formThrombo_ED").serializeArray();
    
    values = values.concat(
        $('#formThrombo_ED input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo_ED input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo_ED input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo_ED select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formThrombo_ED input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}

function populate_thrombo_ED_getdata(){
    disableForm('#formThrombo_ED');
    emptyFormdata(errorField,"#formThrombo_ED",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_thrombo_ED',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formThrombo_ED",data.thrombo);
           
            button_state_thrombo_ED('edit');
        }else{
            button_state_thrombo_ED('add');
        }
    });
}
/////////////////////////////////////////////////////thrombo ends/////////////////////////////////////////////////////

function calc_jq_height_onchange(jqgrid){
	let offsetWidth = $('#'+jqgrid+'>tbody').prop('offsetWidth');
	if(offsetWidth<50){
		offsetWidth = 50;
	}else if(offsetWidth>1800){
		offsetWidth = 1800;
	}
	// $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('width',offsetWidth+1);
    // $('#gview_'+jqgrid+' > div.ui-jqgrid-hdiv').css('width',offsetWidth+1);
}
