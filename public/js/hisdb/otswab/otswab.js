
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

//////////////////////////////////parameter for jqGrid_otswab url//////////////////////////////////
var urlParam_otswab = {
    action: 'get_table_default',
    url: './util/get_table_default',
    field: '',
    table_name: 'nursing.otswab_sets',
    table_id: 'idno',
    filterCol: ['compcode','mrn','episno'],
    filterVal: ['session.compcode','',''],
    
    // action: 'get_grid_otswab',
    // url: './otswab/table',
    // mrn: '',
    // episno: '',
}

$(document).ready(function (){
    
    // textare_init_otswab();
    
    var fdl = new faster_detail_load();
    
    $('#starttime,#endtime')
        .calendar({
            type: 'time',
            formatter: {
                time: 'HH:mm',
                cellTime: 'HH:mm'
            }
        });
    
    disableForm('#form_otswab');
    
    $("#new_otswab").click(function (){
        $('#cancel_otswab').data('oper','add');
        button_state_otswab('wait');
        enableForm('#form_otswab');
        rdonly('#form_otswab');
        // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_otswab").click(function (){
        button_state_otswab('wait');
        enableForm('#form_otswab');
        rdonly('#form_otswab');
        // dialog_mrn_edit.on();
    });
    
    $("#save_otswab").click(function (){
        if($('#form_otswab').isValid({requiredFields: ''}, conf, true)){
            saveForm_otswab(function (data){
                // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
                disableForm('#form_otswab');
            });
        }else{
            enableForm('#form_otswab');
            rdonly('#form_otswab');
        }
    });
    
    $("#cancel_otswab").click(function (){
        // emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
        disableForm('#form_otswab');
        button_state_otswab($(this).data('oper'));
        getdata_otswab();
        // dialog_mrn_edit.off();
    });
    
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
    var addmore_jqgridOTSwab = { more:false,state:false,edit:false }
    
    //////////////////////////////////////////////jqGrid_otswab//////////////////////////////////////////////
    $("#jqGrid_otswab").jqGrid({
        datatype: "local",
        editurl: "./otswab/form",
        colModel: [
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', width: 10, hidden: true },
            { label: 'mrn', name: 'mrn', width: 10, hidden: true },
            { label: 'episno', name: 'episno', width: 10, hidden: true },
            { label: 'Items', name: 'items', classes: 'wrap', width: 30, editable: true },
            { label: 'Initial<br>Count', name: 'countInitial', width: 15, editable: true },
            { label: 'Additional', name: 'add1', width: 12, editable: true },
            { label: 'Additional', name: 'add2', width: 12, editable: true },
            { label: 'Additional', name: 'add3', width: 12, editable: true },
            { label: 'Additional', name: 'add4', width: 12, editable: true },
            { label: 'Extra<br>Count', name: 'count1st', width: 15, editable: true },
            { label: 'Additional', name: 'add5', width: 12, editable: true },
            { label: 'Additional', name: 'add6', width: 12, editable: true },
            { label: 'Additional', name: 'add7', width: 12, editable: true },
            { label: 'Additional', name: 'add8', width: 12, editable: true },
            { label: '2nd<br>Count', name: 'count2nd', width: 15, editable: true },
            { label: 'Additional', name: 'add9', width: 12, editable: true },
            { label: 'Additional', name: 'add10', width: 12, editable: true },
            { label: 'Additional', name: 'add11', width: 12, editable: true },
            { label: 'Additional', name: 'add12', width: 12, editable: true },
            { label: 'Final<br>Count', name: 'countFinal', width: 15, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden: true },
            { label: 'adddate', name: 'adddate', width: 50, hidden: true },
        ],
        shrinkToFit: true,
        autowidth: false,
        multiSort: true,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 1200,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPager_otswab",
        loadComplete: function (){
            if(addmore_jqgridOTSwab.more == true){$('#jqGrid_otswab_iladd').click();}
            else{
                $('#jqGrid2').jqGrid('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgridOTSwab.edit = addmore_jqgridOTSwab.more = false; // reset
            
            // calc_jq_height_onchange("jqGrid_otswab");
            
            $("#jqGrid_otswab_add1,#jqGrid_otswab_add2,#jqGrid_otswab_add3,#jqGrid_otswab_add4").hide();
            $("#jqGrid_otswab_add5,#jqGrid_otswab_add6,#jqGrid_otswab_add7,#jqGrid_otswab_add8").hide();
            $("#jqGrid_otswab_add9,#jqGrid_otswab_add10,#jqGrid_otswab_add11,#jqGrid_otswab_add12").hide();
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGrid_otswab_iledit").click();
        },
    });
    
    $("#jqGrid_otswab").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            { startColumnName: 'add1', numberOfColumns: 4, titleText: 'Additional' },
            { startColumnName: 'add5', numberOfColumns: 4, titleText: 'Additional' },
            { startColumnName: 'add9', numberOfColumns: 4, titleText: 'Additional' },
        ]
    });
    
    /////////////////////////////////////////myEditOptions_add_otswab/////////////////////////////////////////
    var myEditOptions_add_otswab = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").hide();
            
            $("#jqGrid_otswab input[name='countInitial'],#jqGrid_otswab input[name='add1'],#jqGrid_otswab input[name='add2'],#jqGrid_otswab input[name='add3'],#jqGrid_otswab input[name='add4']").on('blur', calculate1stCount);
            $("#jqGrid_otswab input[name='count1st'],#jqGrid_otswab input[name='add5'],#jqGrid_otswab input[name='add6'],#jqGrid_otswab input[name='add7'],#jqGrid_otswab input[name='add8']").on('blur', calculate2ndCount);
            $("#jqGrid_otswab input[name='count2nd'],#jqGrid_otswab input[name='add9'],#jqGrid_otswab input[name='add10'],#jqGrid_otswab input[name='add11'],#jqGrid_otswab input[name='add12']").on('blur', calculateFinalCount);
            
            $("input[name='countFinal']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_otswab_ilsave').click();
                // addmore_jqgridOTSwab.state = true;
                // $('#jqGrid_otswab_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            addmore_jqgridOTSwab.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
            errorField.length = 0;
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_otswab').jqGrid('getRowData', rowid);
            
            let editurl = "./otswab/form?"+
                $.param({
                    episno: $('#episno_otswab').val(),
                    mrn: $('#mrn_otswab').val(),
                    action: 'addJqgrid_save',
                });
            $("#jqGrid_otswab").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").show();
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
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").hide();
            
            $("#jqGrid_otswab input[name='countInitial'],#jqGrid_otswab input[name='add1'],#jqGrid_otswab input[name='add2'],#jqGrid_otswab input[name='add3'],#jqGrid_otswab input[name='add4'],#jqGrid_otswab input[name='count1st'],#jqGrid_otswab input[name='add5'],#jqGrid_otswab input[name='add6'],#jqGrid_otswab input[name='add7'],#jqGrid_otswab input[name='add8'],#jqGrid_otswab input[name='count2nd'],#jqGrid_otswab input[name='add9'],#jqGrid_otswab input[name='add10'],#jqGrid_otswab input[name='add11'],#jqGrid_otswab input[name='add12']").on('blur', calculateCount);
            
            // $("#jqGrid_otswab input[name='countInitial'],#jqGrid_otswab input[name='add1'],#jqGrid_otswab input[name='add2'],#jqGrid_otswab input[name='add3'],#jqGrid_otswab input[name='add4']").on('blur', calculate1stCount);
            // $("#jqGrid_otswab input[name='count1st'],#jqGrid_otswab input[name='add5'],#jqGrid_otswab input[name='add6'],#jqGrid_otswab input[name='add7'],#jqGrid_otswab input[name='add8']").on('blur', calculate2ndCount);
            // $("#jqGrid_otswab input[name='count2nd'],#jqGrid_otswab input[name='add9'],#jqGrid_otswab input[name='add10'],#jqGrid_otswab input[name='add11'],#jqGrid_otswab input[name='add12']").on('blur', calculateFinalCount);
            
            $("input[name='countFinal']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_otswab_ilsave').click();
                // addmore_jqgridOTSwab.state = true;
                // $('#jqGrid_otswab_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgridOTSwab.state == true)addmore_jqgridOTSwab.more = true; // only addmore after save inline
            // addmore_jqgridOTSwab.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
            errorField.length = 0;
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_otswab').jqGrid ('getRowData', rowid);
            
            let editurl = "./otswab/form?"+
                $.param({
                    episno: $('#episno_otswab').val(),
                    mrn: $('#mrn_otswab').val(),
                    idno: selrowData('#jqGrid_otswab').idno,
                    action: 'addJqgrid_edit',
                });
            $("#jqGrid_otswab").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////////////////jqGridPager////////////////////////////////////////////////
    $("#jqGrid_otswab").inlineNav('#jqGridPager_otswab', {
        add: true,
        edit: true,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_otswab
        },
        editParams: myEditOptions_edit_otswab
    }).jqGrid('navButtonAdd', "#jqGridPager_otswab", {
        id: "jqGridPagerDelete_otswab",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGrid_otswab").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'addJqgrid_delete',
                        idno: selrowData('#jqGrid_otswab').idno,
                    }
                    $.post("./otswab/form?"+$.param(param),{oper:'del_jqgrid'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGrid_otswab", urlParam_otswab);
                    });
                }else{
                    $("#jqGridPagerDelete_otswab,#jqGridPagerRefresh_otswab").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPager_otswab", {
        id: "jqGridPagerRefresh_otswab",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGrid_otswab", urlParam_otswab);
        },
    });
    ////////////////////////////////////////////////jqGrid ends////////////////////////////////////////////////
    
    function calculate1stCount(event){
        var optid = event.currentTarget.id;
        var id_optid = optid.substring(0,optid.search("_"));
        
        let countInitial = parseFloat($("#jqGrid_otswab #"+id_optid+"_countInitial").val());
        let add1 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add1").val());
        let add2 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add2").val());
        let add3 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add3").val());
        let add4 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add4").val());
        
        if(isNaN(countInitial))countInitial = 0;
        if(isNaN(add1))add1 = 0;
        if(isNaN(add2))add2 = 0;
        if(isNaN(add3))add3 = 0;
        if(isNaN(add4))add4 = 0;
        
        // if(!isNaN(add1)){
            var count1st = countInitial + add1 + add2 + add3 + add4;
        // }else{
        //     var count1st = countInitial;
        // }
        
        $("#jqGrid_otswab #"+id_optid+"_count1st").val(count1st);
    }
    
    function calculate2ndCount(event){
        var optid = event.currentTarget.id;
        var id_optid = optid.substring(0,optid.search("_"));
        
        let count1st = parseFloat($("#jqGrid_otswab #"+id_optid+"_count1st").val());
        let add5 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add5").val());
        let add6 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add6").val());
        let add7 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add7").val());
        let add8 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add8").val());
        
        if(isNaN(count1st))count1st = 0;
        if(isNaN(add5))add5 = 0;
        if(isNaN(add6))add6 = 0;
        if(isNaN(add7))add7 = 0;
        if(isNaN(add8))add8 = 0;
        
        // if(!isNaN(add5)){
            var count2nd = count1st + add5 + add6 + add7 + add8;
        // }else{
        //     var count2nd = count1st;
        // }
        
        $("#jqGrid_otswab #"+id_optid+"_count2nd").val(count2nd);
    }
    
    function calculateFinalCount(event){
        var optid = event.currentTarget.id;
        var id_optid = optid.substring(0,optid.search("_"));
        
        let count2nd = parseFloat($("#jqGrid_otswab #"+id_optid+"_count2nd").val());
        let add9 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add9").val());
        let add10 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add10").val());
        let add11 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add11").val());
        let add12 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add12").val());
        
        if(isNaN(count2nd))count2nd = 0;
        if(isNaN(add9))add9 = 0;
        if(isNaN(add10))add10 = 0;
        if(isNaN(add11))add11 = 0;
        if(isNaN(add12))add12 = 0;
        
        // if(!isNaN(add9)){
            var countFinal = count2nd + add9 + add10 + add11 + add12;
        // }else{
        //     var countFinal = count2nd;
        // }
        
        $("#jqGrid_otswab #"+id_optid+"_countFinal").val(countFinal);
    }
    
    // guna bila onedit, sebab nak calculate the rest of the field
    function calculateCount(event){
        var optid = event.currentTarget.id;
        var id_optid = optid.substring(0,optid.search("_"));
        
        let countInitial = parseFloat($("#jqGrid_otswab #"+id_optid+"_countInitial").val());
        let add1 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add1").val());
        let add2 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add2").val());
        let add3 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add3").val());
        let add4 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add4").val());
        let count1st = parseFloat($("#jqGrid_otswab #"+id_optid+"_count1st").val());
        let add5 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add5").val());
        let add6 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add6").val());
        let add7 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add7").val());
        let add8 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add8").val());
        let count2nd = parseFloat($("#jqGrid_otswab #"+id_optid+"_count2nd").val());
        let add9 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add9").val());
        let add10 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add10").val());
        let add11 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add11").val());
        let add12 = parseFloat($("#jqGrid_otswab #"+id_optid+"_add12").val());
        
        if(isNaN(countInitial))countInitial = 0;
        if(isNaN(add1))add1 = 0;
        if(isNaN(add2))add2 = 0;
        if(isNaN(add3))add3 = 0;
        if(isNaN(add4))add4 = 0;
        if(isNaN(count2nd))count2nd = 0;
        if(isNaN(add9))add9 = 0;
        if(isNaN(add10))add10 = 0;
        if(isNaN(add11))add11 = 0;
        if(isNaN(add12))add12 = 0;
        if(isNaN(count2nd))count2nd = 0;
        if(isNaN(add9))add9 = 0;
        if(isNaN(add10))add10 = 0;
        if(isNaN(add11))add11 = 0;
        if(isNaN(add12))add12 = 0;
        
        var count_1st = countInitial + add1 + add2 + add3 + add4;
        var count_2nd = count_1st + add5 + add6 + add7 + add8;
        var countFinal = count_2nd + add9 + add10 + add11 + add12;
        
        $("#jqGrid_otswab #"+id_optid+"_count1st").val(count_1st);
        $("#jqGrid_otswab #"+id_optid+"_count2nd").val(count_2nd);
        $("#jqGrid_otswab #"+id_optid+"_countFinal").val(countFinal);
    }
    
});

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

button_state_otswab('empty');
function button_state_otswab(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_otswab").removeAttr('data-toggle');
            $('#cancel_otswab').data('oper','add');
            $('#new_otswab,#save_otswab,#cancel_otswab,#edit_otswab').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#cancel_otswab').data('oper','add');
            $("#new_otswab").attr('disabled',false);
            $('#save_otswab,#cancel_otswab,#edit_otswab').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#cancel_otswab').data('oper','edit');
            $("#edit_otswab").attr('disabled',false);
            $('#save_otswab,#cancel_otswab,#new_otswab').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $("#save_otswab,#cancel_otswab").attr('disabled',false);
            $('#edit_otswab,#new_otswab').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_otswab").attr('data-toggle','collapse');
            $('#new_otswab,#edit_otswab,#save_otswab,#cancel_otswab').attr('disabled',true);
            break;
    }
}

function empty_otswab(){
    emptyFormdata_div("#form_otswab");
    button_state_otswab('empty');
    
    // panel header
    $('#name_show_otswab').text('');
    $('#mrn_show_otswab').text('');
    $('#icpssprt_show_otswab').text('');
    $('#sex_show_otswab').text('');
    $('#height_show_otswab').text('');
    $('#weight_show_otswab').text('');
    $('#dob_show_otswab').text('');
    $('#age_show_otswab').text('');
    $('#race_show_otswab').text('');
    $('#religion_show_otswab').text('');
    $('#occupation_show_otswab').text('');
    $('#citizenship_show_otswab').text('');
    $('#area_show_otswab').text('');
    $('#ward_show_otswab').text('');
    $('#bednum_show_otswab').text('');
    $('#oproom_show_otswab').text('');
    $('#diagnosis_show_otswab').text('');
    $('#procedure_show_otswab').text('');
    $('#unit_show_otswab').text('');
    $('#type_show_otswab').text('');
    
    // form_otswab
    $('#mrn_otswab').val('');
    $("#episno_otswab").val('');
}

function populate_otswab(obj){
    // panel header
    $('#name_show_otswab').text(obj.pat_name);
    $('#mrn_show_otswab').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_otswab').text(obj.icnum);
    $('#sex_show_otswab').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_otswab').text(obj.height+' (CM)');
    $('#weight_show_otswab').text(obj.weight+' (KG)');
    $('#dob_show_otswab').text(dob_chg(obj.DOB));
    $('#age_show_otswab').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_otswab').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_otswab').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_otswab').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_otswab').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_otswab').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_otswab').text(obj.ward);
    $('#bednum_show_otswab').text(obj.bednum);
    $('#oproom_show_otswab').text(obj.ot_description);
    $('#diagnosis_show_otswab').text(obj.appt_diag);
    $('#procedure_show_otswab').text(obj.appt_prcdure);
    $('#unit_show_otswab').text(obj.op_unit);
    $('#type_show_otswab').text(obj.oper_type);
    
    // form_otswab
    $('#mrn_otswab').val(obj.mrn);
    $("#episno_otswab").val(obj.latest_episno);
    
    // table jqGrid_otswab
	urlParam_otswab.filterVal[1] = obj.mrn;
	urlParam_otswab.filterVal[2] = obj.latest_episno;
    // urlParam_otswab.mrn = obj.mrn;
    // urlParam_otswab.episno = obj.latest_episno;
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

function saveForm_otswab(callback){
    let oper = $("#cancel_otswab").data('oper');
    var saveParam = {
        action: 'save_table_otswab',
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
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#form_otswab").serializeArray();
    
    values = values.concat(
        $('#form_otswab input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_otswab select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./otswab/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_otswab('edit');
    }).fail(function (data){
        callback(data);
        button_state_otswab($(this).data('oper'));
    });
}

function textare_init_otswab(){
    $('textarea#otswab_basicset,textarea#otswab_supplemntryset,textarea#otswab_issuesOccured,textarea#otswab_actualOper,textarea#otswab_specimenSent').each(function (){
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

$('#tab_otswab').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_otswab', 300, 114);
    $("#jqGrid_otswab").jqGrid('setGridWidth', Math.floor($("#jqGrid_otswab_c")[0].offsetWidth-$("#jqGrid_otswab_c")[0].offsetLeft-14));
    
    if($('#mrn_otswab').val() != ''){
        getdata_otswab();
    }
});

$('#tab_otswab').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_otswab",['#mrn_otswab','#episno_otswab']);
    button_state_otswab('empty');
});

function getdata_otswab(){
    var urlparam = {
        action: 'get_table_otswab',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_otswab').val(),
        episno: $("#episno_otswab").val()
    };
    
    $.post("./otswab/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_otswab('edit');
            autoinsert_rowdata("#form_otswab",data.otswab);
            // textare_init_otswab();
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
        }else{
            button_state_otswab('add');
            // textare_init_otswab();
            refreshGrid('#jqGrid_otswab',urlParam_otswab,'add_jqgrid');
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