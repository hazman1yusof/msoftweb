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
        ajax: './unallocated_receipt/table?action=job_queue',
        pageLength: 10,
        orderMulti: false,
        responsive: true,
        scrollY: 500,
        processing: true,
        serverSide: true,
        paging: true,
        columns: [
            { data: 'idno' ,visible:false,orderable: false},
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
                        $(td).append(`<a class='btn btn-sm btn-default' target="_blank" href='./unallocated_receipt/table?action=download&idno=`+rowData.idno+`'><i class='fa fa-download'></i></span>`);
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
    
    $("#excelgen1").click(function() {
        $('#excelgen1').attr('disabled',true);
        let href = './unallocated_receipt/form?action=showExcel&type='+$('#type').val()+'&debtortype='+$('#debtortype').val()+'&debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val()

        $.post( href,{_token:$('#_token').val()}, function( data ) {
        }).fail(function(data) {
            $('#excelgen1').attr('disabled',false);
        }).success(function(data){
            $('#excelgen1').attr('disabled',false);
            DataTable.ajax.reload();
        });

        delay(function(){
            DataTable.ajax.reload();
        }, 4000 );

        // window.open('./ARAgeingDtl_Report/showExcel?type='+$('#type').val()+'&debtortype='+$('#debtortype').val()+'&debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&date='+$("#date").val()+'&groupOne='+$("#groupOne").val()+'&groupTwo='+$("#groupTwo").val()+'&groupThree='+$("#groupThree").val()+'&groupFour='+$("#groupFour").val()+'&groupFive='+$("#groupFive").val()+'&groupSix='+$("#groupSix").val(), '_blank');
    });
    
});