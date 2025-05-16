
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

//////////////////////////////////parameter for jqGrid_rof url//////////////////////////////////
var urlParam_rof = {
    action: 'get_table_default',
    url: './util/get_table_default',
    field: '',
    table_name: 'hisdb.ot_upperExtremity_rof',
    table_id: 'idno',
    filterCol: ['compcode','mrn','episno'],
    filterVal: ['session.compcode','',''],
    
}

//////////////////////////////////parameter for jqGrid_hand url//////////////////////////////////
// var urlParam_hand = {
//     action: 'get_table_default',
//     url: './util/get_table_default',
//     field: '',
//     table_name: 'hisdb.ot_upperExtremity_hand',
//     table_id: 'idno',
//     filterCol: ['compcode','mrn','episno'],
//     filterVal: ['session.compcode','',''],
    
// }

$(document).ready(function (){

    textarea_init_upperExtremity();

    var fdl = new faster_detail_load();
    
    //////////////////////////////////////upperExtremity starts//////////////////////////////////////

    disableForm('#formOccupTherapyUpperExtremity');
    
    $("#new_upperExtremity").click(function (){
        $('#cancel_upperExtremity').data('oper','add');
        button_state_upperExtremity('wait');
        enableForm('#formOccupTherapyUpperExtremity');
        rdonly('#formOccupTherapyUpperExtremity');
        emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy']);

        document.getElementById("idno_upperExtremity").value = "";
    });
    
    $("#edit_upperExtremity").click(function (){
        button_state_upperExtremity('wait');
        enableForm('#formOccupTherapyUpperExtremity');
        rdonly('#formOccupTherapyUpperExtremity');
        $("#dateofexam").attr("readonly", true);

    });
    
    $("#save_upperExtremity").click(function (){
        disableForm('#formOccupTherapyUpperExtremity');
        if($('#formOccupTherapyUpperExtremity').isValid({requiredFields: ''}, conf, true)){
            saveForm_upperExtremity(function (data){
                $("#cancel_upperExtremity").data('oper','edit');
                $("#cancel_upperExtremity").click();
                $('#datetimeMMSE_tbl').DataTable().ajax.reload();            
            });
        }else{
            enableForm('#formOccupTherapyUpperExtremity');
            rdonly('#formOccupTherapyUpperExtremity');
        }
    });
    
    $("#cancel_upperExtremity").click(function (){
        // emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy']);
        disableForm('#formOccupTherapyUpperExtremity');
        button_state_upperExtremity($(this).data('oper'));
        // getdata_upperExtremity();
    });

    //////////////////////////////////////upperExtremity ends//////////////////////////////////////
    
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

    /////////////////////////////////////////parameter for saving url/////////////////////////////////////////
    var addmore_jqgridrof = { more:false,state:false,edit:false }
    
    //////////////////////////////////////////////jqGrid_rof//////////////////////////////////////////////
    $("#jqGrid_rof").jqGrid({
        datatype: "local",
        editurl: "./occupTherapy_upperExtremity/form",
        colModel: [
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', width: 10, hidden: true },
            { label: 'mrn', name: 'mrn', width: 10, hidden: true },
            { label: 'episno', name: 'episno', width: 10, hidden: true },
            { label: 'Date', name: 'daterof', width: 10, classes: 'wrap', editable: true, 
				formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' }, 
				editoptions: {
					dataInit: function (element){
						$(element).datepicker({
							id: 'daterof_datePicker',
							dateFormat: 'yy-mm-dd',
							// minDate: new Date($("#dateInsert").val()),
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
            { label: 'Indicate R/L', name: 'dominant', width: 10, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "R:RIGHT;L:LEFT"
				}
			},
            { label: 'Ext (0-50)', name: 'shoulder_ext', width: 12, editable: true },
            { label: 'Flex (0-180)', name: 'shoulder_flex', width: 12, editable: true },
            { label: 'Add/Abd<br>(0-180)', name: 'shoulder_addAbd', width: 12, editable: true },
            { label: 'Internal<br>Rotation (0-90)', name: 'shoulder_intRotation', width: 15, editable: true },
            { label: 'External<br>Rotation (0-90)', name: 'shoulder_extRotation', width: 15, editable: true },
            { label: 'Ext/Flex<br>(0-160)', name: 'elbow_extFlex', width: 12, editable: true },
            { label: 'Pronation<br>(0-90)', name: 'forearm_pronation', width: 12, editable: true },
            { label: 'Supination<br>(0-90)', name: 'forearm_supination', width: 12, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden: true },
            { label: 'adddate', name: 'adddate', width: 50, hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
        ],
        // shrinkToFit: false,
        autowidth: true,
        multiSort: false,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 2600,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPager_rof",
        loadComplete: function (){
            if(addmore_jqgridrof.more == true){$('#jqGrid_rof_iladd').click();}
            else{
                $('#jqGrid_rof').jqGrid('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgridrof.edit = addmore_jqgridrof.more = false; // reset
        
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGrid_rof_iledit").click();
        },
    });
    
    $("#jqGrid_rof").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            { startColumnName: 'shoulder_ext', numberOfColumns: 5, titleText: 'Shoulder' },
            { startColumnName: 'elbow_extFlex', numberOfColumns: 1, titleText: 'Elbow' },
            { startColumnName: 'forearm_pronation', numberOfColumns: 2, titleText: 'Forearm' },
        ]
    });
    
    /////////////////////////////////////////myEditOptions_add_rof/////////////////////////////////////////
    var myEditOptions_add_rof = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").hide();
            
            $("input[name='forearm_supination']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_rof_ilsave').click();
                // addmore_jqgridrof.state = true;
                // $('#jqGrid_rof_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            addmore_jqgridrof.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
            errorField.length = 0;
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_rof').jqGrid('getRowData', rowid);
            
            let editurl = "./occupTherapy_upperExtremity/form?"+
                $.param({
                    episno: $('#episno_occupTherapy').val(),
                    mrn: $('#mrn_occupTherapy').val(),
                    action: 'addJqgridrof_save',
                });
            $("#jqGrid_rof").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////myEditOptions_edit_otswab/////////////////////////////////////////
    var myEditOptions_edit_otswab = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").hide();
            
            $("input[name='forearm_supination']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_rof_ilsave').click();
                // addmore_jqgridrof.state = true;
                // $('#jqGrid_rof_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgridrof.state == true)addmore_jqgridrof.more = true; // only addmore after save inline
            // addmore_jqgridrof.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
            errorField.length = 0;
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_rof').jqGrid ('getRowData', rowid);
            
            let editurl = "./occupTherapy_upperExtremity/form?"+
                $.param({
                    episno: $('#episno_occupTherapy').val(),
                    mrn: $('#mrn_occupTherapy').val(),
                    idno: selrowData('#jqGrid_rof').idno,
                    action: 'addJqgridrof_edit',
                });
            $("#jqGrid_rof").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////////////////jqGridPager////////////////////////////////////////////////
    $("#jqGrid_rof").inlineNav('#jqGridPager_rof', {
        add: true,
        edit: true,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_rof
        },
        editParams: myEditOptions_edit_otswab
    }).jqGrid('navButtonAdd', "#jqGridPager_rof", {
        id: "jqGridPagerDelete_rof",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGrid_rof").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'addJqgridrof_delete',
                        idno: selrowData('#jqGrid_rof').idno,
                    }
                    $.post("./occupTherapy_upperExtremity/form?"+$.param(param),{oper:'del_jqgridrof'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGrid_rof", urlParam_rof);
                    });
                }else{
                    $("#jqGridPagerDelete_rof,#jqGridPagerRefresh_rof").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPager_rof", {
        id: "jqGridPagerRefresh_rof",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGrid_rof", urlParam_rof);
        },
    });
    ////////////////////////////////////////////////jqGrid ends////////////////////////////////////////////////

    ////////////////////////////////////////upperExtremity starts////////////////////////////////////////
    $('#datetimeUpperExtremity_tbl tbody').on('click', 'tr', function (){
        var data = datetimeUpperExtremity_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimeUpperExtremity_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy']);
        $('#datetimeUpperExtremity_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_upperExtremity").val(data.idno);
        
        var saveParam={
            action: 'get_table_upperExtremity',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyUpperExtremity",data.upperExtremity);

                button_state_upperExtremity('edit');
            }else{
                button_state_upperExtremity('add');
            }
        });
    });

   
});

/////////////////////upperExtremity starts/////////////////////
var datetimeUpperExtremity_tbl = $('#datetimeUpperExtremity_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateAssess', 'width': '15%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////upperExtremity ends//////////////////////

var errorField = [];
conf = {
    modules: 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if(errorField.length > 0){
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_upperExtremity('empty');
function button_state_upperExtremity(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_upperExtremity').data('oper','add');
            $('#new_upperExtremity,#save_upperExtremity,#cancel_upperExtremity,#edit_upperExtremity').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_upperExtremity').data('oper','add');
            $("#new_upperExtremity").attr('disabled',false);
            $('#save_upperExtremity,#cancel_upperExtremity,#edit_upperExtremity').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_upperExtremity').data('oper','edit');
            $("#edit_upperExtremity").attr('disabled',false);
            $('#save_upperExtremity,#cancel_upperExtremity,#new_upperExtremity').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_upperExtremity,#cancel_upperExtremity").attr('disabled',false);
            $('#edit_upperExtremity,#new_upperExtremity').attr('disabled',true);
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

function saveForm_upperExtremity(callback){
    let oper = $("#cancel_upperExtremity").data('oper');
    var saveParam = {
        action: 'save_table_upperExtremity',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
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
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formOccupTherapyUpperExtremity").serializeArray();
    
    values = values.concat(
        $('#formOccupTherapyUpperExtremity input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyUpperExtremity input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyUpperExtremity input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyUpperExtremity select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_upperExtremity('edit');
    }).fail(function (data){
        callback(data);
        button_state_upperExtremity($(this).data('oper'));
    });
}

function populate_upperExtremity_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyUpperExtremity",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var saveParam = {
        action: 'get_table_upperExtremity',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_upperExtremity('edit');
            autoinsert_rowdata("#formOccupTherapyUpperExtremity",data.upperExtremity);
        }else{
            button_state_upperExtremity('add');
        }
    });
}

function getdata_upperExtremity(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyUpperExtremity",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var urlparam = {
        action: 'get_table_upperExtremity',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_upperExtremity('edit');
            autoinsert_rowdata("#formOccupTherapyUpperExtremity",data.upperExtremity);
            textarea_init_upperExtremity();
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
        }else{
            button_state_upperExtremity('add');
            textarea_init_upperExtremity();
            refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');
        }
    });
}

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == undefined){
        return false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        return false;
    }
    
    return same;
}

function textarea_init_upperExtremity(){
    $('textarea#diagnosis').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}
