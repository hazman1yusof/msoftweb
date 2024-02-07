$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
    
    /////////////////////////////////////validation/////////////////////////////////////
    $.validate({
        modules : 'sanitize',
        language : {
            requiredFields: 'Please Enter Value'
        },
    });
    
    var errorField=[];
    conf = {
        onValidate : function($form) {
            if(errorField.length>0){
                show_errors(errorField,'#formdata');
                return [{
                    element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
                    message : ''
                }];
            }
        },
    };
    
    $("#genreport input[name='debtorcode_from']").change(function(){
        $("#genreportpdf input[name='debtorcode_from']").val($(this).val());
    });
    $("#genreport input[name='debtorcode_to']").change(function(){
        $("#genreportpdf input[name='debtorcode_to']").val($(this).val());
    });
    $("#genreport input[name='datefr']").change(function(){
        $("#genreportpdf input[name='datefr']").val($(this).val());
    });
    
    $("#pdfgen1").click(function() {
        window.open('./ARAgeingDtl_Report/showpdf?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val(), '_blank');
    });
    
    $("#excelgen1").click(function() {
        window.location='./ARAgeingDtl_Report/showExcel?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val();
    });
    
    var dialog_debtorFrom = new ordialog(
        'debtorcode_from','debtor.debtormast','#genreport input[name = debtorcode_from]','errorField',
        {
            colModel: [
                { label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Debtor Name', name: 'name', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
                { label: 'debtortype', name: 'debtortype', hidden: true },
                { label: 'actdebccode', name: 'actdebccode', hidden: true },
                { label: 'actdebglacc', name: 'actdebglacc', hidden: true },
            ],
            urlParam: {
                filterCol: ['compcode','recstatus'],
                filterVal: ['session.compcode','ACTIVE']
            },
            ondblClickRow: function () {
            },
            gridComplete: function(obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    $('#'+obj.dialogname).dialog('close');
                }
            }
        },{
            title:"Select Debtor Code",
            open: function(){
                dialog_debtorFrom.urlParam.filterCol= ['recstatus', 'compcode'],
                dialog_debtorFrom.urlParam.filterVal= ['ACTIVE', 'session.compcode']
            },
            close: function(obj_){
            },
            after_check: function(data,self,id,fail,errorField){
                let value = $(id).val();
                if(value.toUpperCase() == 'ZZZ'){
                    ordialog_buang_error_shj(id,errorField);
                    if($.inArray('debtorcode_to',errorField)!==-1){
                        errorField.splice($.inArray('debtorcode_to',errorField), 1);
                    }
                }
            },
            justb4refresh: function(obj_){
                obj_.urlParam.searchCol2=[];
                obj_.urlParam.searchVal2=[];
            },
            justaftrefresh: function(obj_){
                $("#Dtext_"+obj_.unique).val('');
            }
        },'urlParam','radio','tab'
    );
    dialog_debtorFrom.makedialog(true);
    
    var dialog_debtorTo = new ordialog(
        'debtorcode_to','debtor.debtormast','#genreport input[name = debtorcode_to]',errorField,
        {
            colModel: [
                { label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Debtor Name', name: 'name', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
                { label: 'debtortype', name: 'debtortype', hidden: true },
                { label: 'actdebccode', name: 'actdebccode', hidden: true },
                { label: 'actdebglacc', name: 'actdebglacc', hidden: true },
            ],
            urlParam: {
                filterCol: ['compcode','recstatus'],
                filterVal: ['session.compcode','ACTIVE']
            },
            ondblClickRow: function () {
            },
            gridComplete: function(obj){
                var gridname = '#'+obj.gridname;
                if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
                    $(gridname+' tr#1').click();
                    $(gridname+' tr#1').dblclick();
                }else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
                    $('#'+obj.dialogname).dialog('close');
                }
            }
        },{
            title:"Select Debtor Code",
            open: function(){
                dialog_debtorTo.urlParam.filterCol= ['recstatus', 'compcode'],
                dialog_debtorTo.urlParam.filterVal= ['ACTIVE', 'session.compcode']
            },
            close: function(obj_){
            },
            after_check: function(data,self,id,fail,errorField){
                let value = $(id).val();
                if(value.toUpperCase() == 'ZZZ'){
                    ordialog_buang_error_shj(id,errorField);
                    if($.inArray('debtorcode_to',errorField)!==-1){
                        errorField.splice($.inArray('debtorcode_to',errorField), 1);
                    }
                }
            },
            justb4refresh: function(obj_){
                obj_.urlParam.searchCol2=[];
                obj_.urlParam.searchVal2=[];
            },
            justaftrefresh: function(obj_){
                $("#Dtext_"+obj_.unique).val('');
            }
        },'urlParam','radio','tab'
    );
    dialog_debtorTo.makedialog(true);
    
});