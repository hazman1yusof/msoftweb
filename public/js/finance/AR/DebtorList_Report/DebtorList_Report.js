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
    
    $("#genreport input[name='debtortype']").change(function(){
        $("#genreportpdf input[name='debtortype']").val($(this).val());
    });
    
    $("#summary_pdf").click(function() {
        window.open('./DebtorList_Report/summarypdf?debtortype='+$('#debtortype').val(), '_blank');
    });
    
    $("#summary_excel").click(function() {
        window.location='./DebtorList_Report/summaryExcel?debtortype='+$('#debtortype').val();
    });
    
    $("#dtl_pdf").click(function() {
        window.open('./DebtorList_Report/dtlpdf?debtortype='+$('#debtortype').val(), '_blank');
    });
    
    $("#dtl_excel").click(function() {
        window.location='./DebtorList_Report/dtlExcel?debtortype='+$('#debtortype').val();
    });
    
    var dialog_debtortype = new ordialog(
        'debtortype','debtor.debtortype','#genreport input[name = debtortype]','errorField',
        {
            colModel: [
                { label: 'Debtor Type', name: 'debtortycode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Description', name: 'description', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
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
            title:"Select Debtor Type",
            open: function(){
                dialog_debtortype.urlParam.filterCol= ['recstatus', 'compcode'],
                dialog_debtortype.urlParam.filterVal= ['ACTIVE', 'session.compcode']
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
    dialog_debtortype.makedialog(true);
    
});