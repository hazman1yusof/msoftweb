$.validate({
    modules: 'sanitize',
    language: {
        requiredFields: ''
    },
});

var errorField = [];
conf = {
    onValidate: function ($form){
        if(errorField.length > 0){
            console.log(errorField);
            return [{
                element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
                message: ' '
            }]
        }
    },
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    disableForm('#formdata');
    
    $("#edit").click(function (){
        button_state('wait');
        enableForm('#formdata');
        rdonly('#formdata');
        dialog_debtorTo.on();
    });
    
    $("#save").click(function (){
        disableForm('#formdata');
        if($('#formdata').isValid({requiredFields: ''}, conf, true)){
            saveForm(function (){
                $("#cancel").data('oper','edit');
                $("#cancel").click();
                // $("#jqGridPagerRefresh").click();
            });
        }else{
            enableForm('#formdata');
            rdonly('#formdata');
        }
    });
    
    $("#cancel").click(function (){
        disableForm('#formdata');
        button_state($(this).data('oper'));
        dialog_debtorTo.off();
    });
    
    $('#excel').click(function (){
        $("#ExcelDialog").dialog("open");
    });
    
    $('button.mybtn').click(function (){
        $('input[name=action]').val($(this).attr('name'));
        
        if($('#formdata').isValid({requiredFields:''},conf,true)){
            var serializedForm =  $('#formdata').serializeArray()
            
            // var param = {
            //     dept_from: 'get_value_default',
            //     dept_to: './util/get_value_default',
            //     item_from: ['backday'],
            //     item_to: 'material.sequence',
            //     year: ['compcode','dept','trantype'],
            //     period: ['session.compcode',this.deptcode,this.trxtype]
            // }
            
            window.open('./ClaimBatchList_Report/report?'+$.param(serializedForm), '_blank');
        }
    });
    
    $("#ExcelDialog").dialog({
        autoOpen: false,
        width: 5/10 * $(window).width(),
        modal: true,
        open: function (){
            $("#refresh_fields").click();
            // dialog_debtorFrom.on();
            // dialog_debtorTo.on();
            parent_close_disabled(true);
        },
        close: function (event, ui){
            // dialog_debtorFrom.off();
            // dialog_debtorTo.off();
            parent_close_disabled(false);
        },
        buttons: [],
    });
    
    $("#exit_dialog").click(function (){
        $("#ExcelDialog").dialog("close");
    });
    
    // $("#seqno").click(function (){
    //     if($('#seqno').is(':checked')){
    //         $('#selectedFields').val("Seq No.");
    //     }else{
    //         $('#selectedFields').val(" ");
    //     }
    // });
    
    $("#refresh_fields").on('click', function (e){
        e.preventDefault();
        let array = [];
        $("input:checkbox[id=fields]:checked").each(function (){
            // array.push($(this).val());
            array.push($(this).parent().text().trim());
        });
        if(array.length){
            array1 = array.join('\r\n');
            $('#selectedFields').text(`${array1}`);
        }
        // else{
        //     $('#selectedFields').text("Checkbox is not selected, Please select one!");
        // }
    });
});

var dialog_debtorFrom = new ordialog(
    'debtorcode_from','debtor.debtormast','#formdata input[name = debtorcode_from]','errorField',
    {
        colModel: [
            { label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
            { label: 'debtortype', name: 'debtortype', hidden: true },
            { label: 'actdebccode', name: 'actdebccode', hidden: true },
            { label: 'actdebglacc', name: 'actdebglacc', hidden: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (){
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                $('#'+obj.dialogname).dialog('close');
            }
        }
    },{
        title: "Select Debtor Code",
        open: function (){
            dialog_debtorFrom.urlParam.filterCol = ['recstatus', 'compcode'],
            dialog_debtorFrom.urlParam.filterVal = ['ACTIVE', 'session.compcode']
        },
        close: function (obj_){
        },
        after_check: function (data,self,id,fail,errorField){
            let value = $(id).val();
            if(value.toUpperCase() == 'ZZZ'){
                ordialog_buang_error_shj(id,errorField);
                if($.inArray('debtorcode_to',errorField)!==-1){
                    errorField.splice($.inArray('debtorcode_to',errorField), 1);
                }
            }
        },
        justb4refresh: function (obj_){
            obj_.urlParam.searchCol2 = [];
            obj_.urlParam.searchVal2 = [];
        },
        justaftrefresh: function (obj_){
            $("#Dtext_"+obj_.unique).val('');
        }
    },'urlParam','radio','tab'
);
dialog_debtorFrom.makedialog(true);

var dialog_debtorTo = new ordialog(
    'debtorcode_to','debtor.debtormast','#formdata input[name = debtorcode_to]',errorField,
    {
        colModel: [
            { label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
            { label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
            { label: 'debtortype', name: 'debtortype', hidden: true },
            { label: 'actdebccode', name: 'actdebccode', hidden: true },
            { label: 'actdebglacc', name: 'actdebglacc', hidden: true },
        ],
        urlParam: {
            filterCol: ['compcode','recstatus'],
            filterVal: ['session.compcode','ACTIVE']
        },
        ondblClickRow: function (){
        },
        gridComplete: function (obj){
            var gridname = '#'+obj.gridname;
            if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                $(gridname+' tr#1').click();
                $(gridname+' tr#1').dblclick();
            }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                $('#'+obj.dialogname).dialog('close');
            }
        }
    },{
        title: "Select Debtor Code",
        open: function (){
            dialog_debtorTo.urlParam.filterCol = ['recstatus', 'compcode'],
            dialog_debtorTo.urlParam.filterVal = ['ACTIVE', 'session.compcode']
        },
        close: function (obj_){
        },
        after_check: function (data,self,id,fail,errorField){
            let value = $(id).val();
            if(value.toUpperCase() == 'ZZZ'){
                ordialog_buang_error_shj(id,errorField);
                if($.inArray('debtorcode_to',errorField)!==-1){
                    errorField.splice($.inArray('debtorcode_to',errorField), 1);
                }
            }
        },
        justb4refresh: function (obj_){
            obj_.urlParam.searchCol2 = [];
            obj_.urlParam.searchVal2 = [];
        },
        justaftrefresh: function (obj_){
            $("#Dtext_"+obj_.unique).val('');
        }
    },'urlParam','radio','tab'
);
dialog_debtorTo.makedialog(true);

button_state('edit');
populate_formdata();
function button_state(state){
    switch(state){
        case 'empty':
            // $("#toggle").removeAttr('data-toggle');
            $('#cancel').data('oper','add');
            $('#save,#cancel,#edit').attr('disabled',true);
            break;
        case 'edit':
            // $("#toggle").attr('data-toggle','collapse');
            $('#cancel').data('oper','edit');
            $("#edit").attr('disabled',false);
            $('#save,#cancel').attr('disabled',true);
            break;
        case 'wait':
            dialog_debtorTo.on();
            // $("#toggle").attr('data-toggle','collapse');
            $("#save,#cancel").attr('disabled',false);
            $('#edit').attr('disabled',true);
            break;
    }
}

function populate_formdata(){
    // emptyFormdata(errorField,"#formdata");
    
    var saveParam = {
        action: 'get_table',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // mrn: obj.mrn,
        // episno: obj.episno
    };
    
    $.get("./ClaimBatchList_Report/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        button_state('edit');
        autoinsert_rowdata("#formdata",data.sysparam1);
        autoinsert_rowdata("#formdata",data.sysparam2);
    });
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
        }else{
            input.val(value);
        }
    });
}

function saveForm(callback){
    var saveParam = {
        action: 'save_table',
        oper: $("#cancel").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formdata").serializeArray();
    
    values = values.concat(
        $('#formdata input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formdata input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formdata input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formdata select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formdata input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./ClaimBatchList_Report/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        // alert('there is an error');
        callback();
    }).success(function (data){
        callback();
    });
}