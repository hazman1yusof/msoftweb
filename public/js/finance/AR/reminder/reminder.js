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

    $('input[name=trantype]').change(function(){
        let val = $(this).val();
        $('.span_tt').hide();
        get_comment();
        if(val == 'remind1'){
            $('#span_1').show();
        }else if(val == 'remind2'){
            $('#span_2').show();
        }else if(val == 'remind3'){
            $('#span_3').show();
        }else if(val == 'remind4'){
            $('#span_4').show();
        }
    });

    $('#edit_btn').click(function(){
        $('#edit_btn,#print_btn').prop('disabled',true);
        $('#cancel_btn,#save_btn').prop('disabled',false);
        $('#comment_').prop('readonly',false);
    });

    $('#cancel_btn').click(function(){
        $('#edit_btn,#print_btn').prop('disabled',false);
        $('#cancel_btn,#save_btn').prop('disabled',true);
        $('#comment_').prop('readonly',true);
    });

    $('#save_btn').click(function(){
        let obj = {
            _token:$('#_token').val(),
            trantype: $('input[name=trantype]:checked').val(),
            comment_: $('#comment_').val()
        }
        $.post('./reminder/form?oper=edit',obj, function( data ) {

        }).fail(function(data) {

        }).success(function(data){
            $('#edit_btn,#print_btn').prop('disabled',false);
            $('#cancel_btn,#save_btn').prop('disabled',true);
            $('#comment_').prop('readonly',true);
        });
    });

    $('#print_btn').click(function(){
        let val = $('input[name=trantype]:checked').val();
        let days = 14;
        // $('input.days').data('validation','');
        if(val == 'remind1'){
            $('#days1').data('validation','required');
            days = $('#days1').val();
        }else if(val == 'remind2'){
            $('#days2').data('validation','required');
            days = $('#days2').val();
        }else if(val == 'remind3'){
            $('#days3').data('validation','required');
            days = $('#days3').val();
        }else if(val == 'remind4'){
            $('#days4').data('validation','required');
            days = $('#days4').val();
        }

        if($('#formdata').isValid({requiredFields:''},conf,true)){
            window.open('./reminder/table?action=print&trantype='+$('input[name=trantype]:checked').val()+'&date='+$('#date').val()+'&debtorcode='+$('#debtorcode').val()+'&days='+days, '_blank');
        }
    });

    function get_comment(){
        $.get( './reminder/table?action=get_comment&trantype='+$('input[name=trantype]:checked').val(), function( data ) {
            
        },'json').done(function(data) {
            $('#comment_').val(data.comment_);
        });
    }
    
    var dialog_debtorTo = new ordialog(
        'debtorcode','debtor.debtormast','#debtorcode',errorField,
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
        },'urlParam','radio','tab'
    );
    dialog_debtorTo.makedialog(true);
    
});