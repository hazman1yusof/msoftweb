@extends('patientcare.layouts.main')

@section('style')
    .container_sem {
        padding:0px !important;
    }
    #modal_reserve input[type=text],#modal_reserve input[type=date],#modal_reserve input[type=time]{
        padding:5px !important;
    }
@endsection

@section('content')
    
    <table id="accomodation_table" style="margin-left: 12px;" class="ui celled striped table" width="100%">
        <thead>
            <tr>
                <th>desc_bt</th>
                <th>Bed Number</th>
                <th>Ward</th>
                <th>Room</th>
                <th>Status</th>
                <th>Bed Type</th>
                <th>Ward</th>
            </tr>
        </thead>
    </table>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('patientcare/css/doctornote.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/fullcalendar-3.7.0/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;500&family=Open+Sans:wght@300;700&family=Syncopate&display=swap" rel="stylesheet">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/trirand/jquery.jqGrid.min.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/fullcalendar-3.7.0/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script type="text/javascript" src="{{ asset('js/myjs/utility.js') }}"></script>


	<script type="text/javascript">
	    $( document ).ready(function() {
            var accomodation_table = $('#accomodation_table').DataTable( {
                    "ajax": "pat_mast/get_entry?action=accomodation_table",
                    "paging":false,
                    "columns": [
                        {'data': 'desc_bt'},
                        {'data': 'bednum'},
                        {'data': 'ward'},
                        {'data': 'room'},
                        {'data': 'bedtype'},
                        {'data': 'occup'},
                        {'data': 'desc_d'},
                    ],
                    order: [[5, 'asc'],[6, 'asc']],
                    columnDefs: [ {
                        targets: [0,5,6],
                            visible: false
                        },{
                            targets: 2,
                            createdCell: function (td, cellData, rowData, row, col) {
                                if(rowData.desc_d == null){
                                    $(td).append(`<span class='help-block'> </span>`);
                                }else{
                                    $(td).append(`<span class='help-block'>`+rowData.desc_d+`</span>`);
                                }
                            }
                        },{
                            targets: 4,
                            createdCell: function (td, cellData, rowData, row, col) {
                                if(rowData.desc_bt == null){
                                    $(td).append(`<span class='help-block'> </span>`);
                                }else{
                                    $(td).append(`<span class='help-block'>`+rowData.desc_bt+`</span>`);
                                }
                            }
                        }
                    ],
                    rowGroup: {
                        dataSrc: [ "desc_bt" ],
                        startRender: function ( rows, group ) {
                            return group + `<i class="arrow fa fa-angle-double-down"></i>`;
                        }
                    },
                    "createdRow": function( row, data, dataIndex ) {
                        $(row).addClass( data['desc_bt'] );
                        // if(data.occup != 'VACANT'){
                        //     $(row).addClass('disabled red');
                        // }else{
                            $('td', row).eq(3).append(`<input type="checkbox" style="float:right">`);
                        // }
                    },
                    "initComplete": function(settings, json) {
                        let opt_bt = opt_ward = "";

                        $('input[type="radio"][name="search_bed"]').on('click',function(){
                            let seltype = $(this).data('seltype');
                            if(seltype == 'bt'){
                                $("#search_bed_select_bed_dept").show();
                                $("#search_bed_select_ward").hide();
                            }else{
                                $("#search_bed_select_bed_dept").hide();
                                $("#search_bed_select_ward").show();
                            }
                        });

                        $("select[name='search_bed_select']").on('change',function(){
                            // accomodation_table.columns( $(this).data('dtbid') ).search( this.value ).draw();
                            accomodation_table.search( this.value ).draw();
                        });
                    }
                } );

            $('#accomodation_table tbody').on('click', 'tr', function () {
                let rowdata = accomodation_table.row(this).data();
                // $('#ReqFor_bed').val(rowdata.bednum);
                // $('#ReqFor_ward').val(rowdata.ward);
                // $('#ReqFor_room').val(rowdata.room);
                // $('#ReqFor_bedtype').val(rowdata.bedtype);
                window.parent.message_parent_wardbook(rowdata);
                $('#accomodation_table tbody tr').removeClass('blue');
                $('#accomodation_table tbody tr').find('input[type=checkbox]').prop('checked', false);
                $(this).find('input[type=checkbox]').prop('checked', true);
                $(this).addClass('blue');
            });
        });
	</script>
@endsection


