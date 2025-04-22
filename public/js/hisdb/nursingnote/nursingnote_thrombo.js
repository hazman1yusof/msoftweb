
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var urlParam_Thrombo = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.thrombophlebitisadd',
	table_id: 'idno',
	filterCol: ['mrn','episno','cannulationNo'],
	filterVal: ['','', $("#idno_thrombo").val()],
};

$(document).ready(function (){
    
    var fdl = new faster_detail_load();

    textarea_init_thrombo();
    refreshGrid('#jqGridThrombo',urlParam_Thrombo,'add_thrombojqgrid');
    
    /////////////////////////////////////thrombo starts/////////////////////////////////////
    disableForm('#formThrombo');
    
    $("#new_thrombo").click(function (){
        button_state_thrombo('wait');
        enableForm('#formThrombo');
        rdonly('#formThrombo');
        emptyFormdata_div("#formThrombo",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        
        document.getElementById("idno_thrombo").value = "";
        document.getElementById("cannulationNo").value = "";
    });
    
    $("#edit_thrombo").click(function (){
        button_state_thrombo('wait');
        enableForm('#formThrombo');
        rdonly('#formThrombo');
        $("#dateInsert,#timeInsert").attr("readonly", true);
    });
    
    $("#save_thrombo").click(function (){
        disableForm('#formThrombo');
        if($('#formThrombo').isValid({requiredFields: ''}, conf, true)){
            saveForm_thrombo(function (){
                $("#cancel_thrombo").data('oper','edit');
                $("#cancel_thrombo").click();
                // $('#datetimethrombo_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formThrombo');
            rdonly('#formThrombo');
        }
    });
    
    $("#cancel_thrombo").click(function (){
        disableForm('#formThrombo');
        button_state_thrombo($(this).data('oper'));
        $('#datetimethrombo_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////thrombo ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    ////////////////////////////////////////thrombo starts////////////////////////////////////////
    $('#datetimethrombo_tbl tbody').on('click', 'tr', function (){
        var data = datetimethrombo_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimethrombo_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formThrombo",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimethrombo_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_thrombo").val(data.idno);
        $("#cannulationNo").val(data.idno);

        //  // jqGridThrombo
        urlParam_Thrombo.filterVal[0] = data.mrn;
        urlParam_Thrombo.filterVal[1] = data.episno;
        urlParam_Thrombo.filterVal[2] = data.idno;
        refreshGrid('#jqGridThrombo',urlParam_Thrombo,'add_thrombojqgrid');
        
        var saveParam = {
            action: 'get_table_thrombo',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./thrombophlebitis/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formThrombo",data.thrombo);
                
                button_state_thrombo('edit');
            }else{
                button_state_thrombo('add');
            }
            
            textarea_init_thrombo();

        });
       
    });
    /////////////////////////////////////////thrombo ends/////////////////////////////////////////
    var addmore_jqgrid_thrombo = { more:false,state:false,edit:false }

    $("#jqGridThrombo").jqGrid({
		datatype: "local",
		editurl: "thrombophlebitis/form",
		colModel: [
            { label: 'Flushing<br>Done', name: 'flushingDone', width: 100, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "1:YES;0:NO"
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
			{ label: 'Shift', name: 'shift', width: 130, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "M:MORNING;E:EVENING;N:NIGHT"
				}
			},
            { label: 'Dressing<br>Changed', name: 'dressingChanged', width: 130, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
				editoptions: {
					value: "1:YES;0:NO"
				}
			},
			{ label: 'Sign/Name', name: 'staffId', width: 180, editable: true },
			{ label: 'Phlebitis Grade', name: 'phlebitisGrade', width: 100, editable: true },
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
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'adduser', name: 'adduser', hidden: true },
			{ label: 'adddate', name: 'adddate', hidden: true },
			{ label: 'upduser', name: 'upduser', hidden: true },
			{ label: 'upddate', name: 'upddate', hidden: true },
			{ label: 'computerid', name: 'computerid', hidden: true },
            { label: 'cannulationNo', name: 'cannulationNo', hidden: true },

		],
        shrinkToFit : false,
		autowidth: false,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		viewrecords: true,
		loadonce: false,
		width: 1200,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPagerThrombo",
		loadComplete: function (){
			if(addmore_jqgrid_thrombo.more == true){$('#jqGridThrombo_iladd').click();}
			else{
				$('#jqGridThrombo').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid_thrombo.edit = addmore_jqgrid_thrombo.more = false; // reset
			
			// calc_jq_height_onchange("jqGridThrombo");
			
			if($("#jqGridThrombo").data('lastselrow') == undefined){
				$("#jqGridThrombo").setSelection($("#jqGridThrombo").getDataIDs()[0]);
			}else{
				$("#jqGridThrombo").setSelection($("#jqGridThrombo").data('lastselrow'));
				delay(function (){
					$('#jqGridThrombo tr#'+$("#jqGridThrombo").data('lastselrow')).focus();
				}, 300);
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridThrombo_iledit").click();
		},
		gridComplete: function (){
			fdl.set_array().reset();
			if($('#jqGridPagerThrombo').jqGrid('getGridParam', 'reccount') > 0){
				$("#jqGridPagerThrombo").setSelection($("#jqGridPagerThrombo").getDataIDs()[0]);
			}
		},
	});

    $("#jqGridThrombo").jqGrid('setGroupHeaders', {
        useColSpanStyle: true,
        groupHeaders: [
            { startColumnName: 'dateAssessment', numberOfColumns: 4, titleText: 'Daily Assessment Record' },
            { startColumnName: 'phlebitisGrade', numberOfColumns: 8, titleText: 'Reason for Removal' },
            { startColumnName: 'fibrinClot', numberOfColumns: 4, titleText: 'Catheter Removal Status' },

        ]
    });

    /////////////////////////////////////////myEditOptions_add_thrombo/////////////////////////////////////////
    var myEditOptions_add_thrombo = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").hide();
            
            $("input[name='tipDamage']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridThrombo_ilsave').click();
                // addmore_jqgrid_thrombo.state = true;
                // $('#jqGridThrombo_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            addmore_jqgrid_thrombo.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridThrombo',urlParam_Thrombo,'add_thrombojqgrid');
            errorField.length = 0;
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").show();
        },
        errorfunc: function (rowid,response){
            refreshGrid('#jqGridThrombo',urlParam_Thrombo,'add_thrombojqgrid');
        },
        beforeSaveRow: function (options, rowid){
            
            let data = $('#jqGridThrombo').jqGrid('getRowData', rowid);
            
            let editurl = "./thrombophlebitis/form?"+
                $.param({
                    action: 'addThrombo_save',
                    mrn: $('#mrn_nursNote').val(),
                    episno: $('#episno_nursNote').val(),
                    cannulationNo: $('#idno_thrombo').val(),
                    
                });
            $("#jqGridThrombo").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////myEditOptions_edit_thrombo/////////////////////////////////////////
    var myEditOptions_edit_thrombo = {
        keys: true,
        extraparam: {
            "_token": $("#csrf_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").hide();
            
            $("input[name='tipDamage']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridThrombo_ilsave').click();
                // addmore_jqgrid_thrombo.state = true;
                // $('#jqGridThrombo_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            if(addmore_jqgrid_thrombo.state == true)addmore_jqgrid_thrombo.more = true; // only addmore after save inline
            // addmore_jqgrid_thrombo.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridThrombo',urlParam_Thrombo,'edit_thrombojqgrid');
            errorField.length = 0;
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").show();
        },
        errorfunc: function (rowid,response){
            refreshGrid('#jqGridThrombo',urlParam_Thrombo,'edit_thrombojqgrid');
        },
        beforeSaveRow: function (options, rowid){
            let data = $('#jqGridThrombo').jqGrid ('getRowData', rowid);
            
            let editurl = "./thrombophlebitis/form?"+
                $.param({
                    action: 'addThrombo_edit',
                    _token: $("#csrf_token").val(),
                    episno: $('#episno_nursNote').val(),
                    mrn: $('#mrn_nursNote').val(),
                    idno: selrowData('#jqGridThrombo').idno,

                });
            $("#jqGridThrombo").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc: function (response){
            $("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
	
    ////////////////////////jqGridPagerThrombo////////////////////////
	$("#jqGridThrombo").inlineNav('#jqGridPagerThrombo', {
		add: true, edit: true, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_add_thrombo
		},
		editParams: myEditOptions_edit_thrombo,
	}).jqGrid('navButtonAdd', "#jqGridPagerThrombo", {
		id: "jqGridPagerDelete_thrombo",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGridThrombo").jqGrid('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridThrombo').idno,
                        action: 'addThrombo_del',
                    }
                    $.post("./thrombophlebitis/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridThrombo", urlParam_Thrombo);
                    });
                }else{
					$("#jqGridPagerDelete_thrombo,#jqGridPagerRefresh_thrombo").show();
                }
            }
		},
	}).jqGrid('navButtonAdd', "#jqGridPagerThrombo", {
		id: "jqGridPagerRefresh_thrombo",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridThrombo", urlParam_Thrombo);
		},
	});

    $("#jqGridThrombo_ilcancel").click(function (){
        refreshGrid("#jqGridThrombo", urlParam_Thrombo);
    });
    
});

/////////////////////thrombo starts/////////////////////
var datetimethrombo_tbl = $('#datetimethrombo_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateInsert', 'width': '25%' },
        { 'data': 'timeInsert', 'width': '25%' },
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

button_state_thrombo('empty');
function button_state_thrombo(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_thrombo').data('oper','add');
            $('#new_thrombo,#save_thrombo,#cancel_thrombo,#edit_thrombo').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_thrombo').data('oper','add');
            $("#new_thrombo").attr('disabled',false);
            $('#save_thrombo,#cancel_thrombo,#edit_thrombo').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_thrombo').data('oper','edit');
            $("#new_thrombo,#edit_thrombo").attr('disabled',false);
            $('#save_thrombo,#cancel_thrombo').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_thrombo,#cancel_thrombo").attr('disabled',false);
            $('#edit_thrombo,#new_thrombo').attr('disabled',true);
            break;
    }
}

function populate_thrombo_getdata(){
    disableForm('#formThrombo');
    emptyFormdata(errorField,"#formThrombo",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_thrombo',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
    };
    
    $.post("./thrombophlebitis/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formThrombo",data.thrombo);
            button_state_thrombo('edit');
        }else{
            button_state_thrombo('add');
        }
        textarea_init_thrombo();
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

/////////////////////////////////////////////////////thrombo starts/////////////////////////////////////////////////////

function saveForm_thrombo(callback){
    var saveParam = {
        action: 'save_table_thrombo',
        oper: $("#cancel_thrombo").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
    };
    
    values = $("#formThrombo").serializeArray();
    
    values = values.concat(
        $('#formThrombo input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formThrombo select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formThrombo input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./thrombophlebitis/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        alert(data.responseText);

        callback();
    }).success(function (data){
        callback();
    });
}
/////////////////////////////////////////////////////thrombo ends/////////////////////////////////////////////////////

function textarea_init_thrombo(){
    $('textarea#remarksThrombo').each(function () {
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