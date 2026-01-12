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

    var DataTable = $('#job_queue').DataTable({
        ajax: './ARAgeingDtl_Report/table?action=job_queue',
        pageLength: 10,
        orderMulti: false,
        responsive: true,
        scrollY: 500,
        processing: true,
        serverSide: true,
        paging: true,
        columns: [
            { data: 'idno',orderable: false},
            { data: 'compcode' ,visible:false,orderable: false},
            { data: 'page' ,visible:false,orderable: false},
            { data: 'filename',orderable: false},
            { data: 'process' ,visible:false,orderable: false},
            { data: 'status' ,orderable: false},
            { data: 'adduser',orderable: false},
            { data: 'adddate' ,orderable: false},
            { data: 'finishdate',orderable: false},
            { data: 'remarks',orderable: false,visible:false},
            { data: 'download',orderable: false},
        ],
        columnDefs: [
            {targets: 10,
                createdCell: function (td, cellData, rowData, row, col) {
                    if(rowData.status == 'DONE'){
                        if($('#scope').val() == '1'){
                            $(td).append(`<a class='btn btn-sm btn-default' target="_blank" href='./ARAgeingDtl_Report/table?action=download_statement&idno=`+rowData.idno+`'><i class='fa fa-download'></i></span>`);
                        }else{
                            $(td).append(`<a class='btn btn-sm btn-default' target="_blank" href='./ARAgeingDtl_Report/table?action=download&idno=`+rowData.idno+`'><i class='fa fa-download'></i></span>`);
                        }
                    }
                }
            },
        ],
        drawCallback: function( settings ) {
            $('#job_queue_filter > label').hide();
            if(!$('#refresh_dtable').length){
                $('#job_queue_filter').append(`<button id='refresh_dtable'><i class='fa fa-refresh'></i></button>`);
                $('#refresh_dtable').click(function(){
                    DataTable.ajax.reload();
                });
            }
        }
    }).on('preXhr.dt', function ( e, settings, data ) {
    }).on('xhr.dt', function ( e, settings, json, xhr ) {
    });

    function myTask() {
        DataTable.ajax.reload();
    }

    // Run forever every 5 seconds (5000 ms)
    setInterval(myTask, 5000);
    
    $("#genreport input[name='debtorcode_from']").change(function(){
        $("#genreportpdf input[name='debtorcode_from']").val($(this).val());
    });
    $("#genreport input[name='debtorcode_to']").change(function(){
        $("#genreportpdf input[name='debtorcode_to']").val($(this).val());
    });
    $("#genreport input[name='date']").change(function(){
        $("#genreportpdf input[name='date']").val($(this).val());
    });
    $("#genreport input[name='groupOne']").change(function(){
        $("#genreportpdf input[name='groupOne']").val($(this).val());
    });
    $("#genreport input[name='groupTwo']").change(function(){
        $("#genreportpdf input[name='groupTwo']").val($(this).val());
    });
    $("#genreport input[name='groupThree']").change(function(){
        $("#genreportpdf input[name='groupThree']").val($(this).val());
    });
    $("#genreport input[name='groupFour']").change(function(){
        $("#genreportpdf input[name='groupFour']").val($(this).val());
    });
    $("#genreport input[name='groupFive']").change(function(){
        $("#genreportpdf input[name='groupFive']").val($(this).val());
    });
    $("#genreport input[name='groupSix']").change(function(){
        $("#genreportpdf input[name='groupSix']").val($(this).val());
    });
    
    // $("#pdfgen1").click(function() {
    //     window.open('./ARAgeingDtl_Report/showpdf?debtortype='+$('#debtortype').val()+'&debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val(), '_blank');
    // });
    
    $("#excelgen1").click(function() {
        $('#excelgen1').attr('disabled',true);

        var groupby = $("input[type=radio][name=groupby]:checked").val();

        let href = './ARAgeingDtl_Report/form?action=showExcel&type='+$('#type').val()+'&debtortype='+$('#debtortype').val()+'&debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val()+'&groupby='+groupby

        $.post( href,{_token:$('#_token').val()}, function( data ) {
        }).fail(function(data) {
            // $('#excelgen1').attr('disabled',false);
        }).success(function(data){
            // $('#excelgen1').attr('disabled',false);
            DataTable.ajax.reload();
        });

        // delay(function(){
        //     DataTable.ajax.reload();
        // }, 4000 );

        // window.open('./ARAgeingDtl_Report/showExcel?type='+$('#type').val()+'&debtortype='+$('#debtortype').val()+'&debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val(), '_blank');
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
                let data = selrowData('#'+dialog_debtorFrom.gridname);
                $("#genreport input[name = debtorcode_to]").val(data['debtorcode']);
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
        'debtorcode_to','debtor.debtormast','#genreport input[name = debtorcode_to]','errorField',
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

    var dialog_debtortype = new ordialog(
        'debtortype','debtor.debtortype','#genreport input#debtortype',errorField,
        {
            colModel: [
                { label: 'Debtor Type', name: 'debtortycode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
                { label: 'Description', name: 'description', width:400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
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
                if(value.toUpperCase() == 'ALL'){
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