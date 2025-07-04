
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
    filterCol: ['mrn','episno','idno_rof'],
    filterVal: ['','',$("#formROF :input[name='idno_rof']").val()],
    
}

//////////////////////////////////parameter for jqGrid_hand url//////////////////////////////////
var urlParam_hand = {
    action: 'get_table_default',
    url: './util/get_table_default',
    field: '',
    table_name: 'hisdb.ot_upperExtremity_hand',
    table_id: 'idno',
    filterCol: ['mrn','episno','idno_hand'],
    filterVal: ['','',$("#formHand :input[name='idno_hand']").val()],
    
}

$(document).ready(function (){

    textarea_init_upperExtremity();

    var fdl = new faster_detail_load();
    
    //////////////////////////////////////upperExtremity starts//////////////////////////////////////

    disableForm('#formOccupTherapyUpperExtremity');
    
    $("#new_upperExtremity").click(function (){
        button_state_upperExtremity('wait');
        enableForm('#formOccupTherapyUpperExtremity');
        rdonly('#formOccupTherapyUpperExtremity');
        emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity']);

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
            });
        }else{
            enableForm('#formOccupTherapyUpperExtremity');
            rdonly('#formOccupTherapyUpperExtremity');
        }
    });
    
    $("#cancel_upperExtremity").click(function (){
        disableForm('#formOccupTherapyUpperExtremity');
        button_state_upperExtremity($(this).data('oper'));
        $('#datetimeUpperExtremity_tbl').DataTable().ajax.reload();            
    });

    //////////////////////////////////////upperExtremity ends//////////////////////////////////////

    //////////////////////////////////////rof starts//////////////////////////////////////

    disableForm('#formROF');
    
    $("#new_rof").click(function (){
        console.log($("#idno_rof").val());
        $('#cancel_rof').data('oper','add');
        button_state_rof('wait');
        enableForm('#formROF');
        rdonly('#formROF');
        emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof','#rof_impressions']);

        // document.getElementById("idno_rof").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_rof").click(function (){
        button_state_rof('wait');
        enableForm('#formROF');
        rdonly('#formROF');
    });
    
    $("#save_rof").click(function (){
        disableForm('#formROF');
        if($('#formROF').isValid({requiredFields: ''}, conf, true)){
            saveForm_rof(function (data){
                $("#cancel_rof").data('oper','edit');
                $("#cancel_rof").click();          
            });
        }else{
            enableForm('#formROF');
            rdonly('#formROF');
        }
    });
    
    $("#cancel_rof").click(function (){
        // emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof']);
        disableForm('#formROF');
        button_state_rof($(this).data('oper'));
    });
    //////////////////////////////////////rof ends//////////////////////////////////////

    //////////////////////////////////////hand starts//////////////////////////////////////

    disableForm('#formHand');
    
    $("#new_hand").click(function (){
        console.log($("#idno_hand").val());
        $('#cancel_hand').data('oper','add');
        button_state_hand('wait');
        enableForm('#formHand');
        rdonly('#formHand');
        emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand','#hand_impressions']);

        // document.getElementById("idno_hand").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_hand").click(function (){
        button_state_hand('wait');
        enableForm('#formHand');
        rdonly('#formHand');
    });
    
    $("#save_hand").click(function (){
        disableForm('#formHand');
        if($('#formHand').isValid({requiredFields: ''}, conf, true)){
            saveForm_hand(function (data){
                $("#cancel_hand").data('oper','edit');
                $("#cancel_hand").click();          
            });
        }else{
            enableForm('#formHand');
            rdonly('#formHand');
        }
    });
    
    $("#cancel_hand").click(function (){
        // emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand']);
        disableForm('#formHand');
        button_state_hand($(this).data('oper'));
    });
    //////////////////////////////////////hand ends//////////////////////////////////////

    //////////////////////////////////////strength starts//////////////////////////////////////

    disableForm('#formStrength');
    
    $("#new_strength").click(function (){
        console.log($("#idno_strength").val());
        $('#cancel_strength').data('oper','add');
        button_state_strength('wait');
        enableForm('#formStrength');
        rdonly('#formStrength');
        emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_strength']);

        // document.getElementById("idno_strength").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_strength").click(function (){
        button_state_strength('wait');
        enableForm('#formStrength');
        rdonly('#formStrength');
    });
    
    $("#save_strength").click(function (){
        disableForm('#formStrength');
        if($('#formStrength').isValid({requiredFields: ''}, conf, true)){
            saveForm_strength(function (data){
                $("#cancel_strength").data('oper','edit');
                $("#cancel_strength").click();          
            });
        }else{
            enableForm('#formStrength');
            rdonly('#formStrength');
        }
    });
    
    $("#cancel_strength").click(function (){
        // emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_strength']);
        disableForm('#formStrength');
        button_state_strength($(this).data('oper'));
    });
    //////////////////////////////////////strength ends//////////////////////////////////////

    //////////////////////////////////////sensation starts//////////////////////////////////////

    disableForm('#formSensation');
    
    $("#new_sensation").click(function (){
        console.log($("#idno_sensation").val());
        $('#cancel_sensation').data('oper','add');
        button_state_sensation('wait');
        enableForm('#formSensation');
        rdonly('#formSensation');
        emptyFormdata_div("#formSensation",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);

        // document.getElementById("idno_sensation").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_sensation").click(function (){
        button_state_sensation('wait');
        enableForm('#formSensation');
        rdonly('#formSensation');
    });
    
    $("#save_sensation").click(function (){
        disableForm('#formSensation');
        if($('#formSensation').isValid({requiredFields: ''}, conf, true)){
            saveForm_sensation(function (data){
                $("#cancel_sensation").data('oper','edit');
                $("#cancel_sensation").click();          
            });
        }else{
            enableForm('#formSensation');
            rdonly('#formSensation');
        }
    });
    
    $("#cancel_sensation").click(function (){
        // emptyFormdata_div("#formSensation",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);
        disableForm('#formSensation');
        button_state_sensation($(this).data('oper'));
    });
    //////////////////////////////////////sensation ends//////////////////////////////////////

    //////////////////////////////////////prehensive starts//////////////////////////////////////

    disableForm('#formPrehensive');
    
    $("#new_prehensive").click(function (){
        console.log($("#idno_prehensive").val());
        $('#cancel_prehensive').data('oper','add');
        button_state_prehensive('wait');
        enableForm('#formPrehensive');
        rdonly('#formPrehensive');
        emptyFormdata_div("#formPrehensive",['#mrn_occupTherapy','#episno_occupTherapy','#idno_prehensive']);

        // document.getElementById("idno_prehensive").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_prehensive").click(function (){
        button_state_prehensive('wait');
        enableForm('#formPrehensive');
        rdonly('#formPrehensive');
    });
    
    $("#save_prehensive").click(function (){
        disableForm('#formPrehensive');
        if($('#formPrehensive').isValid({requiredFields: ''}, conf, true)){
            saveForm_prehensive(function (data){
                $("#cancel_prehensive").data('oper','edit');
                $("#cancel_prehensive").click();          
            });
        }else{
            enableForm('#formPrehensive');
            rdonly('#formPrehensive');
        }
    });
    
    $("#cancel_prehensive").click(function (){
        // emptyFormdata_div("#formPrehensive",['#mrn_occupTherapy','#episno_occupTherapy','#idno_prehensive]);
        disableForm('#formPrehensive');
        button_state_prehensive($(this).data('oper'));
    });
    //////////////////////////////////////prehensive ends//////////////////////////////////////

    //////////////////////////////////////skin starts//////////////////////////////////////

    disableForm('#formSkin');
    
    $("#new_skin").click(function (){
        console.log($("#idno_skin").val());
        $('#cancel_skin').data('oper','add');
        button_state_skin('wait');
        enableForm('#formSkin');
        rdonly('#formSkin');
        emptyFormdata_div("#formSkin",['#mrn_occupTherapy','#episno_occupTherapy','#idno_skin']);

        // document.getElementById("idno_skin").value = "";
        document.getElementById("idno_upperExtremity").value = "";
    });
    
    $("#edit_skin").click(function (){
        button_state_skin('wait');
        enableForm('#formSkin');
        rdonly('#formSkin');
    });
    
    $("#save_skin").click(function (){
        disableForm('#formSkin');
        if($('#formSkin').isValid({requiredFields: ''}, conf, true)){
            saveForm_skin(function (data){
                $("#cancel_skin").data('oper','edit');
                $("#cancel_skin").click();          
            });
        }else{
            enableForm('#formSkin');
            rdonly('#formSkin');
        }
    });
    
    $("#cancel_skin").click(function (){
        // emptyFormdata_div("#formSkin",['#mrn_occupTherapy','#episno_occupTherapy','#idno_skin']);
        disableForm('#formSkin');
        button_state_skin($(this).data('oper'));
    });
    //////////////////////////////////////skin ends//////////////////////////////////////

    //////////////////////////////////////edema starts//////////////////////////////////////

    disableForm('#formEdema');
    
    $("#new_edema").click(function (){
        console.log($("#idno_edema").val());
        $('#cancel_edema').data('oper','add');
        button_state_edema('wait');
        enableForm('#formEdema');
        rdonly('#formEdema');
        emptyFormdata_div("#formEdema",['#mrn_occupTherapy','#episno_occupTherapy','#idno_edema']);

        // document.getElementById("idno_edema").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_edema").click(function (){
        button_state_edema('wait');
        enableForm('#formEdema');
        rdonly('#formEdema');
    });
    
    $("#save_edema").click(function (){
        disableForm('#formEdema');
        if($('#formEdema').isValid({requiredFields: ''}, conf, true)){
            saveForm_edema(function (data){
                $("#cancel_edema").data('oper','edit');
                $("#cancel_edema").click();          
            });
        }else{
            enableForm('#formEdema');
            rdonly('#formEdema');
        }
    });
    
    $("#cancel_edema").click(function (){
        // emptyFormdata_div("#formEdema",['#mrn_occupTherapy','#episno_occupTherapy','#idno_edema']);
        disableForm('#formEdema');
        button_state_edema($(this).data('oper'));
    });
    //////////////////////////////////////edema ends//////////////////////////////////////

    //////////////////////////////////////functional starts//////////////////////////////////////

    disableForm('#formFunctional');
    
    $("#new_functional").click(function (){
        console.log($("#idno_func").val());
        $('#cancel_functional').data('oper','add');
        button_state_func('wait');
        enableForm('#formFunctional');
        rdonly('#formFunctional');
        emptyFormdata_div("#formFunctional",['#mrn_occupTherapy','#episno_occupTherapy','#idno_func']);

        // document.getElementById("idno_func").value = "";
        document.getElementById("idno_upperExtremity").value = "";

    });
    
    $("#edit_functional").click(function (){
        button_state_func('wait');
        enableForm('#formFunctional');
        rdonly('#formFunctional');
    });
    
    $("#save_functional").click(function (){
        disableForm('#formFunctional');
        if($('#formFunctional').isValid({requiredFields: ''}, conf, true)){
            saveForm_func(function (data){
                $("#cancel_functional").data('oper','edit');
                $("#cancel_functional").click();          
            });
        }else{
            enableForm('#formFunctional');
            rdonly('#formFunctional');
        }
    });
    
    $("#cancel_functional").click(function (){
        // emptyFormdata_div("#formFunctional",['#mrn_occupTherapy','#episno_occupTherapy','#idno_func']);
        disableForm('#formFunctional');
        button_state_func($(this).data('oper'));
    });
    //////////////////////////////////////functional ends//////////////////////////////////////
    
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
            { label: 'Date', name: 'daterof', width: 45, classes: 'wrap', editable: true, 
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
            { label: 'Indicate R/L', name: 'dominant', width: 35, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "R:RIGHT;L:LEFT"
				}
			},
            { label: '<center>Ext<br>(0-50)</center>', name: 'shoulder_ext', width: 30, editable: true },
            { label: '<center>Flex<br>(0-180)</center>', name: 'shoulder_flex', width: 30, editable: true },
            { label: '<center>Add/Abd<br>(0-180)</center>', name: 'shoulder_addAbd', width: 30, editable: true },
            { label: '<center>Internal<br>Rotation (0-90)</center>', name: 'shoulder_intRotation', width: 40, editable: true },
            { label: '<center>External<br>Rotation (0-90)</center>', name: 'shoulder_extRotation', width: 40, editable: true },
            { label: '<center>Ext/Flex<br>(0-160)</center>', name: 'elbow_extFlex', width: 30, editable: true },
            { label: '<center>Pronation<br>(0-90)</center>', name: 'forearm_pronation', width: 30, editable: true },
            { label: '<center>Supination<br>(0-90)</center>', name: 'forearm_supination', width: 30, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden: true },
            { label: 'adddate', name: 'adddate', width: 50, hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
            { label: 'lastcomputerid', name: 'computerid', hidden: true },
            { label: 'idno_rof', name: 'idno_rof', hidden: true },

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
                    action: 'addJqgridrof_save',
                    mrn: $('#mrn_occupTherapy').val(),
                    episno: $('#episno_occupTherapy').val(),
                    idno_rof: $("#formROF :input[name='idno_rof']").val()
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
    
    /////////////////////////////////////////myEditOptions_edit_rof/////////////////////////////////////////
    var myEditOptions_edit_rof = {
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
                    action: 'addJqgridrof_edit',
                    mrn: $('#mrn_occupTherapy').val(),
                    episno: $('#episno_occupTherapy').val(),
                    idno: selrowData('#jqGrid_rof').idno,
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
        editParams: myEditOptions_edit_rof
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
    ////////////////////////////////////////////////jqGrid rof ends////////////////////////////////////////////////

    /////////////////////////////////////////parameter for saving url/////////////////////////////////////////
    var addmore_jqgridhand = { more:false,state:false,edit:false }
    
    //////////////////////////////////////////////jqGrid_hand//////////////////////////////////////////////
    $("#jqGrid_hand").jqGrid({
        datatype: "local",
        editurl: "./occupTherapy_upperExtremity/form",
        colModel: [
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', width: 10, hidden: true },
            { label: 'mrn', name: 'mrn', width: 10, hidden: true },
            { label: 'episno', name: 'episno', width: 10, hidden: true },
            { label: 'Date', name: 'datehand', width: 100, classes: 'wrap', editable: true, 
				formatter: dateFormatter, unformat: dateUNFormatter, formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' }, 
				editoptions: {
					dataInit: function (element){
						$(element).datepicker({
							id: 'datehand_datePicker',
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
            { label: 'Indicate R/L', name: 'dominants', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "R:RIGHT;L:LEFT"
				}
			},
            { label: '<center>Flex<br>(0-90)</center>', name: 'wrist_flex', width: 100, editable: true },
            { label: '<center>Ext<br>(0-90)</center>', name: 'wrist_ext', width: 100, editable: true },
            { label: '<center>Ulna/<br>Radial Deviation<br>(0-30)</center>', name: 'wrist_ulna', width: 120, editable: true },
            { label: '<center>Ext/Flex MP<br>(0-50)</center>', name: 'thumb_extFlexMP', width: 100, editable: true },
            { label: '<center>Ext/Flex IP<br>(0-80)</center>', name: 'thumb_extFlexIP', width: 100, editable: true },
            { label: '<center>Ext/Flex CMC<br>(0-15)</center>', name: 'thumb_extFlexCMC', width: 100, editable: true },
            { label: '<center>Palmar<br>Abduction<br>(0-75)</center>', name: 'thumb_palmar', width: 100, editable: true },
            { label: '<center>Thumb to tip<br>5th Digit<br>(inches)</center>', name: 'thumb_tip', width: 100, editable: true },
            { label: '<center>Thumb to base<br>5th Digit<br>(inches)</center>', name: 'thumb_base', width: 100, editable: true },
            { label: '<center>MCP<br>(0-90)</center>', name: 'index_MCP', width: 100, editable: true },
            { label: '<center>PIP<br>(0-110)</center>', name: 'index_PIP', width: 100, editable: true },
            { label: '<center>DIP<br>(0-90)</center>', name: 'index_DIP', width: 100, editable: true },
            { label: '<center>MCP<br>(0-90)</center>', name: 'middle_MCP', width: 100, editable: true },
            { label: '<center>PIP<br>(0-110)</center>', name: 'middle_PIP', width: 100, editable: true },
            { label: '<center>DIP<br>(0-90)</center>', name: 'middle_DIP', width: 100, editable: true },
            { label: '<center>MCP<br>(0-90)</center>', name: 'ring_MCP', width: 100, editable: true },
            { label: '<center>PIP<br>(0-110)</center>', name: 'ring_PIP', width: 100, editable: true },
            { label: '<center>DIP<br>(0-90)</center>', name: 'ring_DIP', width: 100, editable: true },
            { label: '<center>MCP<br>(0-90)</center>', name: 'little_MCP', width: 100, editable: true },
            { label: '<center>PIP<br>(0-110)</center>', name: 'little_PIP', width: 100, editable: true },
            { label: '<center>DIP<br>(0-90)</center>', name: 'little_DIP', width: 100, editable: true },
            { label: 'adduser', name: 'adduser', width: 50, hidden: true },
            { label: 'adddate', name: 'adddate', width: 50, hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
            { label: 'lastcomputerid', name: 'computerid', hidden: true },
            { label: 'idno_hand', name: 'idno_hand', hidden: true },

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
        pager: "#jqGridPager_hand",
        loadComplete: function (){
            if(addmore_jqgridhand.more == true){$('#jqGrid_hand_iladd').click();}
            else{
                $('#jqGrid_hand').jqGrid('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgridhand.edit = addmore_jqgridhand.more = false; // reset
        // calc_jq_height_onchange("jqGrid_hand");
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGrid_hand_iledit").click();
        },
    });
    
    $("#jqGrid_hand").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            { startColumnName: 'wrist_flex', numberOfColumns: 3, titleText: 'Wrist' },
            { startColumnName: 'thumb_extFlexMP', numberOfColumns: 6, titleText: 'Thumb' },
            { startColumnName: 'index_MCP', numberOfColumns: 3, titleText: 'Index' },
            { startColumnName: 'middle_MCP', numberOfColumns: 3, titleText: 'Middle' },
            { startColumnName: 'ring_MCP', numberOfColumns: 3, titleText: 'Ring' },
            { startColumnName: 'little_MCP', numberOfColumns: 3, titleText: 'Little' },
        ]
    });
    
    /////////////////////////////////////////myEditOptions_add_hand/////////////////////////////////////////
    var myEditOptions_add_hand = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").hide();
            
            $("input[name='little_DIP']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_hand_ilsave').click();
                // addmore_jqgridhand.state = true;
                // $('#jqGrid_hand_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            addmore_jqgridhand.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_hand',urlParam_hand,'add_jqgridhand');
            errorField.length = 0;
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_hand',urlParam_hand,'add_jqgridhand');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_hand').jqGrid('getRowData', rowid);
            
            let editurl = "./occupTherapy_upperExtremity/form?"+
                $.param({
                    action: 'addJqgridhand_save',
                    mrn: $('#mrn_occupTherapy').val(),
                    episno: $('#episno_occupTherapy').val(),
                    idno_hand: $("#formHand :input[name='idno_hand']").val(),
                });
            $("#jqGrid_hand").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////myEditOptions_edit_hand/////////////////////////////////////////
    var myEditOptions_edit_hand = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").hide();
            
            $("input[name='little_DIP']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGrid_hand_ilsave').click();
                // addmore_jqgridhand.state = true;
                // $('#jqGrid_hand_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgridhand.state == true)addmore_jqgridhand.more = true; // only addmore after save inline
            // addmore_jqgridhand.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGrid_hand',urlParam_hand,'add_jqgridhand');
            errorField.length = 0;
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGrid_hand',urlParam_hand,'add_jqgridhand');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            
            let data = $('#jqGrid_hand').jqGrid ('getRowData', rowid);
            
            let editurl = "./occupTherapy_upperExtremity/form?"+
                $.param({
                    action: 'addJqgridhand_edit',
                    mrn: $('#mrn_occupTherapy').val(),
                    episno: $('#episno_occupTherapy').val(),
                    idno: selrowData('#jqGrid_hand').idno,
                });
            $("#jqGrid_hand").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ////////////////////////////////////////////////jqGridPager_hand////////////////////////////////////////////////
    $("#jqGrid_hand").inlineNav('#jqGridPager_hand', {
        add: true,
        edit: true,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_hand
        },
        editParams: myEditOptions_edit_hand
    }).jqGrid('navButtonAdd', "#jqGridPager_hand", {
        id: "jqGridPagerDelete_hand",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGrid_hand").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'addJqgridhand_delete',
                        idno: selrowData('#jqGrid_hand').idno,
                    }
                    $.post("./occupTherapy_upperExtremity/form?"+$.param(param),{oper:'del_jqgridhand'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGrid_hand", urlParam_hand);
                    });
                }else{
                    $("#jqGridPagerDelete_hand,#jqGridPagerRefresh_hand").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPager_hand", {
        id: "jqGridPagerRefresh_hand",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGrid_hand", urlParam_hand);
        },
    });
    ////////////////////////////////////////////////jqGrid hand ends////////////////////////////////////////////////

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
        
        emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity']);
        emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof','#rof_impressions']);
        emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand','#hand_impressions']);

        $('#datetimeUpperExtremity_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#formOccupTherapyUpperExtremity :input[name='idno_upperExtremity'],#formROF :input[name='idno_rof'],#formHand :input[name='idno_hand'],#formStrength :input[name='idno_strength'],#formSensation :input[name='idno_sensation'],#formPrehensive :input[name='idno_prehensive'],#formSkin :input[name='idno_skin'],#formEdema :input[name='idno_edema'],#formFunctional :input[name='idno_func']").val(data.idno);
       
        $("#formROF :input[name='rof_impressions']").val('ROF');
        $("#formHand :input[name='hand_impressions']").val('hand');

        //// jqGrid_rof
        urlParam_rof.filterVal[0] = data.mrn;
        urlParam_rof.filterVal[1] = data.episno;
        urlParam_rof.filterVal[2] = data.idno;

        refreshGrid('#jqGrid_rof',urlParam_rof,'add_jqgridrof');

        //// jqGrid_hand
        urlParam_hand.filterVal[0] = data.mrn;
        urlParam_hand.filterVal[1] = data.episno;
        urlParam_hand.filterVal[2] = data.idno;

        refreshGrid('#jqGrid_hand',urlParam_hand,'add_jqgridhand');

        var saveParam={
            action: 'get_table_upperExtremity',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyUpperExtremity",data.upperExtremity);

                button_state_upperExtremity('edit');
                populate_rof_getdata();
                populate_hand_getdata();
                populate_strength_getdata();
                populate_sensation_getdata();               
                populate_prehensive_getdata();             
                populate_skin_getdata();             
                populate_edema_getdata();             
                populate_func_getdata();             

            }else{
                button_state_upperExtremity('add');
            }
            textarea_init_upperExtremity();
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
        { 'data': 'dateAssess', 'width': '10%' },
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
            $("#edit_upperExtremity,#new_upperExtremity").attr('disabled',false);
            $('#save_upperExtremity,#cancel_upperExtremity').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_upperExtremity,#cancel_upperExtremity").attr('disabled',false);
            $('#edit_upperExtremity,#new_upperExtremity').attr('disabled',true);
            break;
    }
}

button_state_hand('empty');
function button_state_hand(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_hand').data('oper','add');
            $('#new_hand,#save_hand,#cancel_hand,#edit_hand').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_hand').data('oper','add');
            $("#new_hand").attr('disabled',false);
            $('#save_hand,#cancel_hand,#edit_hand').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_hand').data('oper','edit');
            $("#edit_hand").attr('disabled',false);
            $('#save_hand,#cancel_hand,#new_hand').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_hand,#cancel_hand").attr('disabled',false);
            $('#edit_hand,#new_hand').attr('disabled',true);
            break;
    }
}

button_state_rof('empty');
function button_state_rof(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_rof').data('oper','add');
            $('#new_rof,#save_rof,#cancel_rof,#edit_rof').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_rof').data('oper','add');
            $("#new_rof").attr('disabled',false);
            $('#save_rof,#cancel_rof,#edit_rof').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_rof').data('oper','edit');
            $("#edit_rof").attr('disabled',false);
            $('#save_rof,#cancel_rof,#new_rof').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_rof,#cancel_rof").attr('disabled',false);
            $('#edit_rof,#new_rof').attr('disabled',true);
            break;
    }
}

button_state_rof('empty');
function button_state_rof(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_rof').data('oper','add');
            $('#new_rof,#save_rof,#cancel_rof,#edit_rof').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_rof').data('oper','add');
            $("#new_rof").attr('disabled',false);
            $('#save_rof,#cancel_rof,#edit_rof').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_rof').data('oper','edit');
            $("#edit_rof").attr('disabled',false);
            $('#save_rof,#cancel_rof,#new_rof').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_rof,#cancel_rof").attr('disabled',false);
            $('#edit_rof,#new_rof').attr('disabled',true);
            break;
    }
}

button_state_strength('empty');
function button_state_strength(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_strength').data('oper','add');
            $('#new_strength,#save_strength,#cancel_strength,#edit_strength').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_strength').data('oper','add');
            $("#new_strength").attr('disabled',false);
            $('#save_strength,#cancel_strength,#edit_strength').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_strength').data('oper','edit');
            $("#edit_strength").attr('disabled',false);
            $('#save_strength,#cancel_strength,#new_strength').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_strength,#cancel_strength").attr('disabled',false);
            $('#edit_strength,#new_strength').attr('disabled',true);
            break;
    }
}

button_state_sensation('empty');
function button_state_sensation(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_sensation').data('oper','add');
            $('#new_sensation,#save_sensation,#cancel_sensation,#edit_sensation').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_sensation').data('oper','add');
            $("#new_sensation").attr('disabled',false);
            $('#save_sensation,#cancel_sensation,#edit_sensation').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_sensation').data('oper','edit');
            $("#edit_sensation").attr('disabled',false);
            $('#save_sensation,#cancel_sensation,#new_sensation').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_sensation,#cancel_sensation").attr('disabled',false);
            $('#edit_sensation,#new_sensation').attr('disabled',true);
            break;
    }
}

button_state_prehensive('empty');
function button_state_prehensive(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_prehensive').data('oper','add');
            $('#new_prehensive,#save_prehensive,#cancel_prehensive,#edit_prehensive').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_prehensive').data('oper','add');
            $("#new_prehensive").attr('disabled',false);
            $('#save_prehensive,#cancel_prehensive,#edit_prehensive').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_prehensive').data('oper','edit');
            $("#edit_prehensive").attr('disabled',false);
            $('#save_prehensive,#cancel_prehensive,#new_prehensive').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_prehensive,#cancel_prehensive").attr('disabled',false);
            $('#edit_prehensive,#new_prehensive').attr('disabled',true);
            break;
    }
}

button_state_skin('empty');
function button_state_skin(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_skin').data('oper','add');
            $('#new_skin,#save_skin,#cancel_skin,#edit_skin').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_skin').data('oper','add');
            $("#new_skin").attr('disabled',false);
            $('#save_skin,#cancel_skin,#edit_skin').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_skin').data('oper','edit');
            $("#edit_skin").attr('disabled',false);
            $('#save_skin,#cancel_skin,#new_skin').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_skin,#cancel_skin").attr('disabled',false);
            $('#edit_skin,#new_skin').attr('disabled',true);
            break;
    }
}

button_state_edema('empty');
function button_state_edema(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_edema').data('oper','add');
            $('#new_edema,#save_edema,#cancel_edema,#edit_edema').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_edema').data('oper','add');
            $("#new_edema").attr('disabled',false);
            $('#save_edema,#cancel_edema,#edit_edema').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_edema').data('oper','edit');
            $("#edit_edema").attr('disabled',false);
            $('#save_edema,#cancel_edema,#new_edema').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_edema,#cancel_edema").attr('disabled',false);
            $('#edit_edema,#new_edema').attr('disabled',true);
            break;
    }
}

button_state_func('empty');
function button_state_func(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_functional').data('oper','add');
            $('#new_functional,#save_functional,#cancel_functional,#edit_functional').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_functional').data('oper','add');
            $("#new_functional").attr('disabled',false);
            $('#save_functional,#cancel_functional,#edit_functional').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_functional').data('oper','edit');
            $("#edit_functional").attr('disabled',false);
            $('#save_functional,#cancel_functional,#new_functional').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_functional,#cancel_functional").attr('disabled',false);
            $('#edit_functional,#new_functional').attr('disabled',true);
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
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
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
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}

function saveForm_rof(callback){
    let oper = $("#cancel_rof").data('oper');
    var saveParam = {
        action: 'save_table_impressions',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // tabName: $("#rof_impressions").val(),
        // idno_rof: $("#idno_rof").val(),
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
    
    values = $("#formROF").serializeArray();
    
    values = values.concat(
        $('#formROF input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formROF input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formROF input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formROF select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_rof',
        value: $('#formROF input[name=idno_rof]').val()
    });

    values.push({
        name: 'tabName',
        value: $('#formROF input[name=rof_impressions]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_hand(callback){
    let oper = $("#cancel_hand").data('oper');
    var saveParam = {
        action: 'save_table_impressions',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // tabName: $("#hand_impressions").val(),
        // idno_hand: $("#idno_hand").val(),
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
    
    values = $("#formHand").serializeArray();
    
    values = values.concat(
        $('#formHand input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formHand input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formHand input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formHand select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_hand',
        value: $('#formHand input[name=idno_hand]').val()
    });

    values.push({
        name: 'tabName',
        value: $('#formHand input[name=hand_impressions]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_strength(callback){
    let oper = $("#cancel_strength").data('oper');
    var saveParam = {
        action: 'save_table_strength',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_strength: $("#idno_strength").val(),
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
    
    values = $("#formStrength").serializeArray();
    
    values = values.concat(
        $('#formStrength input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formStrength input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formStrength input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formStrength select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_strength',
        value: $('#formStrength input[name=idno_strength]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_sensation(callback){
    let oper = $("#cancel_sensation").data('oper');
    var saveParam = {
        action: 'save_table_sensation',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_sensation: $("#idno_sensation").val(),
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
    
    values = $("#formSensation").serializeArray();
    
    values = values.concat(
        $('#formSensation input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formSensation input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSensation input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formSensation select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_sensation',
        value: $('#formSensation input[name=idno_sensation]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_prehensive(callback){
    let oper = $("#cancel_prehensive").data('oper');
    var saveParam = {
        action: 'save_table_prehensive',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_prehensive: $("#idno_prehensive").val(),
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
    
    values = $("#formPrehensive").serializeArray();
    
    values = values.concat(
        $('#formPrehensive input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formPrehensive input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSensation input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formPrehensive select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_prehensive',
        value: $('#formPrehensive input[name=idno_prehensive]').val()
    });
    
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_prehensive('edit');
    }).fail(function (data){
        callback(data);
        button_state_prehensive($(this).data('oper'));
    });
}

function saveForm_skin(callback){
    let oper = $("#cancel_skin").data('oper');
    var saveParam = {
        action: 'save_table_skin',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_skin: $("#idno_skin").val(),
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
    
    values = $("#formPrehensive").serializeArray();
    
    values = values.concat(
        $('#formSkin input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formSkin input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSkin input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formSkin select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_skin',
        value: $('#formSkin input[name=idno_skin]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_skin('edit');
    }).fail(function (data){
        callback(data);
        button_state_skin($(this).data('oper'));
    });
}

function saveForm_edema(callback){
    let oper = $("#cancel_edema").data('oper');
    var saveParam = {
        action: 'save_table_edema',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_edema: $("#idno_edema").val(),
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
    
    values = $("#formEdema").serializeArray();
    
    values = values.concat(
        $('#formEdema input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formEdema input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formEdema input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formEdema select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

    values.push({
        name: 'idno_edema',
        value: $('#formEdema input[name=idno_edema]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_edema('edit');
    }).fail(function (data){
        callback(data);
        button_state_edema($(this).data('oper'));
    });
}

function saveForm_func(callback){
    let oper = $("#cancel_functional").data('oper');
    var saveParam = {
        action: 'save_table_func',
        oper: oper,
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        // idno_func: $("#idno_func").val(),
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
    
    values = $("#formFunctional").serializeArray();
    
    values = values.concat(
        $('#formFunctional input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formFunctional input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formFunctional input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formFunctional select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );

     values.push({
        name: 'idno_func',
        value: $('#formFunctional input[name=idno_func]').val()
    });
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_func('edit');
    }).fail(function (data){
        callback(data);
        button_state_func($(this).data('oper'));
    });
}

function populate_upperExtremity_getdata(){
    // console.log('populate');
    disableForm('#formOccupTherapyUpperExtremity');
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
            autoinsert_rowdata("#formOccupTherapyUpperExtremity",data.upperExtremity);
            button_state_upperExtremity('edit');
        }else{
            button_state_upperExtremity('add');
        }
        textarea_init_upperExtremity();

    });
}

function populate_rof_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formROF",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_rof','#rof_impressions']);

    var saveParam = {
        action: 'get_table_impressions',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_imp: $("#formROF :input[name='idno_rof']").val(),
        tabName: $("#rof_impressions").val()
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formROF",data.impressions);
            button_state_rof('edit');
        }else{
            button_state_rof('add');
        }
    });
}

function populate_hand_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formHand",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_hand','#hand_impressions']);

    var saveParam = {
        action: 'get_table_impressions',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_imp: $("#formHand :input[name='idno_hand']").val(),
        tabName: $("#hand_impressions").val()
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formHand",data.impressions);
            button_state_hand('edit');
        }else{
            button_state_hand('add');
        }
    });
}

function populate_strength_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formStrength",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_strength']);

    var saveParam = {
        action: 'get_table_strength',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_strength: $("#idno_strength").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formStrength",data.strength);
            button_state_strength('edit');
        }else{
            button_state_strength('add');
        }
    });
}

function populate_sensation_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formSensation",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_sensation']);

    var saveParam = {
        action: 'get_table_sensation',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_sensation: $("#idno_sensation").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formSensation",data.sensation);
            button_state_sensation('edit');
        }else{
            button_state_sensation('add');
        }
    });
}

function populate_prehensive_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formPrehensive",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_prehensive']);

    var saveParam = {
        action: 'get_table_prehensive',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_prehensive: $("#idno_prehensive").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_prehensive('edit');
            autoinsert_rowdata("#formPrehensive",data.prehensive);
        }else{
            button_state_prehensive('add');
        }
    });
}

function populate_skin_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formSkin",["#mrn_occupTherapy","#episno_occupTherapy",'#idno_skin']);

    var saveParam = {
        action: 'get_table_skin',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_skin: $("#idno_skin").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_skin('edit');
            autoinsert_rowdata("#formSkin",data.skin);
        }else{
            button_state_skin('add');
        }
    });
}

function populate_edema_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formEdema",["#mrn_occupTherapy","#episno_occupTherapy","#idno_edema"]);

    var saveParam = {
        action: 'get_table_edema',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_edema: $("#idno_edema").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_edema('edit');
            autoinsert_rowdata("#formEdema",data.edema);
        }else{
            button_state_edema('add');
        }
    });
}

function populate_func_getdata(){
    // console.log('populate');
    emptyFormdata(errorField,"#formFunctional",["#mrn_occupTherapy","#episno_occupTherapy","#idno_func"]);

    var saveParam = {
        action: 'get_table_func',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
        idno_func:$("#idno_func").val(),
    };
    
    $.post("./occupTherapy_upperExtremity/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_func('edit');
            autoinsert_rowdata("#formFunctional",data.func);
        }else{
            button_state_func('add');
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
    $('textarea#diagnosis,textarea#skinCondition').each(function (){
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

// function calc_jq_height_onchange(jqgrid){
// 	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
// 	if(scrollHeight<50){
// 		scrollHeight = 50;
// 	}else if(scrollHeight>300){
// 		scrollHeight = 300;
// 	}
// 	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
// }

function calc_jq_height_onchange(jqgrid){
	let offsetWidth = $('#'+jqgrid+'>tbody').prop('offsetWidth');
	if(offsetWidth<50){
		offsetWidth = 50;
	}else if(offsetWidth>1300){
		offsetWidth = 1300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('width',offsetWidth+1);
    $('#gview_'+jqgrid+' > div.ui-jqgrid-hdiv').css('width',offsetWidth+1);
}
