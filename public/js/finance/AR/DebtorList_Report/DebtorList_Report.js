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
    
    //////////////////////////////parameter for jqgrid url//////////////////////////////
    var urlParam={
        action: 'get_table_default',
        url: 'util/get_table_default',
        field: '',
        table_name: 'debtor.debtortype',
        table_id: 'idno',
        filterCol: ['compcode'],
        filterVal: ['session.compcode'],
        sort_idno: true,
    }
    
    $("#jqGrid").jqGrid({
        datatype: "local",
        colModel: [
            { label: 'idno', name: 'idno', hidden: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'Debtor Type', name: 'debtortycode', width: 30 },
            { label: 'Deposit Acc', name: 'depglacc', width: 30 },
            { label: 'Debtor Acc', name: 'actdebglacc', width: 30 },
            { label: 'Description', name: 'description', width: 30, classes: 'wrap' },
            { label: 'adduser', name: 'adduser', width: 90, hidden: true },
            { label: 'adddate', name: 'adddate', width: 90, hidden: true },
            { label: 'upduser', name: 'upduser', width: 90, hidden: true },
            { label: 'upddate', name: 'upddate', width: 90, hidden: true },
        ],
        autowidth: true,
        multiSort: true,
        viewrecords: true,
        loadonce: false,
        sortname: 'idno',
        sortorder: "desc",
        width: 900,
        height: 350,
        rowNum: 30,
        pager: "#jqGridPager",
        onSelectRow:function(rowid, selected){
            $("#summary_pdf").click(function() {
                window.open('./DebtorList_Report/summarypdf?debtortype='+selrowData("#jqGrid").debtortycode, '_blank');
            });
            
            $("#summary_excel").click(function() {
                window.location='./DebtorList_Report/summaryExcel?debtortype='+selrowData("#jqGrid").debtortycode;
            });
            
            $("#dtl_pdf").click(function() {
                window.open('./DebtorList_Report/dtlpdf?debtortype='+selrowData("#jqGrid").debtortycode, '_blank');
            });
            
            $("#dtl_excel").click(function() {
                window.location='./DebtorList_Report/dtlExcel?debtortype='+selrowData("#jqGrid").debtortycode;
            });
        },
        ondblClickRow: function(rowid, iRow, iCol, e){
        },
        gridComplete: function(){
            var ids = $("#jqGrid").jqGrid('getDataIDs');
            var cl = ids[0];
            $("#jqGrid").jqGrid('setSelection', cl);
        },
    });
    
    //////////////////////////////////start grid pager//////////////////////////////////
    $("#jqGrid").jqGrid('navGrid','#jqGridPager',{
        view:false, edit:false, add:false, del:false, search:false,
        beforeRefresh: function(){
            refreshGrid("#jqGrid",urlParam);
        },
    });
    
    ////////////////////add field into param, refresh grid if needed////////////////////
    addParamField('#jqGrid',true,urlParam);
    //////////////////////////////////////end grid//////////////////////////////////////
    
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
    
    // $("#jqGrid").jqGrid ('setGridWidth', Math.floor($("#jqGrid_debtortype_c")[0].offsetWidth-$("#jqGrid_debtortype_c")[0].offsetLeft-20));
    
});