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
        ajax: './uninvgrn/table?action=job_queue',
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
                        $(td).append(`<a class='btn btn-sm btn-default' target="_blank" href='./uninvgrn/table?action=download&idno=`+rowData.idno+`'><i class='fa fa-download'></i></span>`);
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
    
    $("#excelgen1").click(function() {
        $('#excelgen1').attr('disabled',true);

        let href = './uninvgrn/form?action=processLink&dateFrom='+$("#dateFrom").val()+'&dateTo='+$("#dateTo").val();

        $.post( href,{_token:$('#_token').val()}, function( data ) {
        }).fail(function(data) {

        }).success(function(data){

            DataTable.ajax.reload();
        });
    });
    
});