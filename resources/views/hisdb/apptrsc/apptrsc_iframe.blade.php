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
    <input type="hidden" value="{{$apptresrc->resourcecode}}" id="loccode">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="ui grid" style="margin: 0px;">
        <div class="field">
            <label>Operation Theatre: </label>
            <select class="ui selection dropdown" id="loccode_sel">
                @foreach($apptresrc_all as $apptresrc_obj)
                <option value="{{$apptresrc_obj->resourcecode}}">{{$apptresrc_obj->description}}</option>
                @endforeach
            </select>
        </div>
        <button type="button" id="reserve" class="ui button small blue">Reserve</button>
    </div>
    <div class="ui stackable two column grid" style="margin-top: 0px;">
        <div class="five wide tablet five wide computer column" id="calendar_div">
            <div class="ui orange segment">
                <div id="calendar"></div>
            </div>
        </div>

        <div class="eleven wide tablet eleven wide computer right floated column" style="margin:0px;"  id="jqgrid_div">
            <div class="ui teal segment jqgridsegment" style="padding-bottom: 40px;" id="jqgrid_c">
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
            </div>
        </div>
    </div>

    <div class="ui modal" id="modal_reserve">
        <form class="content ui form" style="padding:0px" id="modalform">
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column centered grid">
                        <div class="ui fields">
                            <div class="four wide field">
                                <label>Resource</label>
                                <input name="resourcecode" id="resourcecode" type="text" readonly value="{{$apptresrc->resourcecode}}">
                            </div>
                            <div class="two wide field">
                                <label>MRN</label>
                                <input name="mrn" id="mrn" type="text" readonly value="{{$episode->mrn}}">
                            </div>
                            <div class="two wide field">
                                <label>Episode</label>
                                <input name="episno" id="episno" type="text" readonly value="{{$episode->episno}}">
                            </div>
                            <div class="eight wide field">
                                <label>Name</label>
                                <input name="pat_name" id="pat_name" type="text" readonly value="{{$patmast->Name}}">
                            </div>
                        </div>

                        <div class="ui fields">
                            <div class="eight wide field">
                                <label>Resource Date</label>
                                <input name="date" type="date">
                            </div>
                            <div class="four wide field">
                                <label>Time Start</label>
                                <input name="time_start" type="time">
                            </div>
                            <div class="four wide field">
                                <label>Time End</label>
                                <input name="time_end" type="time">
                            </div>
                        </div>

                        <div class="ui three fields">
                            <div class="four wide field">
                                <label>Tel No.</label>
                                <input name="telno" type="text" value="{{$patmast->telh}}">
                            </div>
                            <div class="four wide field">
                                <label>Tel HP</label>
                                <input name="telhp" type="text" value="{{$patmast->telhp}}">
                            </div>
                            <div class="eight wide field">
                                <label>Remarks</label>
                                <textarea rows="1" name="remarks"></textarea>
                            </div>
                        </div>

                        <div class="ui horizontal divider">
                            Operation Theatre
                        </div>

                        <div class="ui two fields">
                            <div class="field">
                                <label>Operation Unit</label>
                                <select class="ui selection dropdown" id="op_unit" name="op_unit">
                                    <option value=""></option>
                                    @foreach($op_unit as $op_unit_obj)
                                    <option value="{{$op_unit_obj->code}}">{{$op_unit_obj->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Operation Type</label>
                                <select class="ui selection dropdown" id="op_type" name="op_type">
                                    <option></option>
                                    <option>MINOR</option>
                                    <option>MAJOR</option>
                                </select>
                            </div>
                        </div>

                        <div class="ui two fields">
                            <div class="field">
                                <label>ICD</label>
                                <button class="ui button small blue" type="button" id="btn_icd">ICD</button>
                                <textarea rows="4" id="diagnosis" name="diagnosis"></textarea>
                            </div>
                            <div class="field">
                                <label>MMA</label>
                                <button class="ui button small blue" type="button" id="btn_mma">MMA</button>
                                <textarea rows="4" id="procedure" name="procedure"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="center aligned actions">
            <div id="fail_msg" style="color:darkred"></div>
            <div class="ui negative button">Cancel</div>
            <div class="ui positive button">Submit</div>
        </div>
    </div>

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
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';

        var urlParam = {
            action: 'get_table_ot_iframe',
            url: './apptrsc_rsc/table',
            date : moment().format("YYYY-MM-DD"),
            loccode : $('#loccode').val(),
        }

	    $( document ).ready(function() {

            $('#loccode_sel').change(function(){
                let newloccode = $(this).val();
                urlParam.loccode = newloccode;

                var event_apptbook = {   
                    id: 'apptbook',
                    url: "apptrsc_rsc/getEvent",
                    type: 'GET',
                    data: {
                        type: 'apptbook_iframe',
                        drrsc: newloccode
                    }
                }

                $('#calendar').fullCalendar( 'removeEventSource', 'apptbook');
                $('#calendar').fullCalendar( 'addEventSource', event_apptbook);
                $('#resourcecode').val(newloccode);

                refreshGrid("#jqGrid", urlParam);
            });
	    	
            $('#calendar').fullCalendar({
                // events: events,
                defaultView: 'month',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,listMonth'
                },
                buttonText:{
                    today: "Today"
                },
                contentHeight:"auto",
                dayClick: function(date, allDay, jsEvent, view) {
                    $( ".fc-bg td.fc-day" ).removeClass( "selected_day" );
                    $(this).addClass( "selected_day" );
                    urlParam.date = date.format('YYYY-MM-DD');
                    refreshGrid("#jqGrid", urlParam);

                },
                eventRender: function(eventObj, $el) {
                    $(".fc-today-button").html('<small class="mysmall">'+moment().format('ddd')+'</small><br/><b class="myb">'+moment().format('DD')+'</b>');
                    // $('div.fc-right').append('<p>sdssd</p>').insertAfter
                },
                eventAfterRender: function(event, element, view){
                    let d1 = new Date(event.start.format('YYYY-MM-DD'));
                    let d2 = new Date($('#sel_date').val());
                    if(d1.getTime() === d2.getTime()){
                        $('#no_of_pat').text(event.title.split(" ")[0]);
                    }
                },
                eventClick: function(event) {
                    var view = $('#calendar').fullCalendar('getView');
                    if(view.type == 'listMonth'){
                        urlParam.filterVal[0] = event.start.format('YYYY-MM-DD');
                        refreshGrid("#jqGrid", urlParam);
                    }
                },
                eventSources: [
                    {   
                        id: 'apptbook',
                        url: "apptrsc_rsc/getEvent",
                        type: 'GET',
                        data: {
                            type: 'apptbook_iframe',
                            drrsc: $('#loccode').val()
                        }
                    },
                ]
            });

            $("#jqGrid").jqGrid({
                datatype: "local",
                colModel: [
                    { label:'idno', name:'idno', hidden:true},
                    { label:'MRN', name:'mrn', width:10,hidden:true },
                    { label:'I/C', name:'icnum', width:20,hidden:true },
                    { label:'admdoctor', name:'admdoctor', width:20,hidden:true },
                    { label:'Name', name:'pat_name', width:50, formatter: pat_name_f},
                    { label:'Doctor', name:'doctor', width:50, formatter: doctor_f },
                    { label:'Date/Time From', name:'start', width:20 },
                    { label:'Date/Time To', name:'end', width:20 },
                    { label:'admdoctor', name:'admdoctor', hidden:true},
                ],
                autowidth: true,
                viewrecords: true,
                width: 900,
                height: 365,
                rowNum: 30,
                onSelectRow:function(rowid, selected){
                },
                ondblClickRow: function (rowid, iRow, iCol, e) {
                },
                gridComplete: function () {
                },
            });

            function pat_name_f(cellvalue, options, rowObject){
                console.log(rowObject);
                return '<b>'+pad('0000000',rowObject.mrn,true)+'</b><br/>'+rowObject.pat_name;
            }
            function doctor_f(cellvalue, options, rowObject){
                console.log(rowObject);
                return '<b>'+rowObject.admdoctor+'</b><br/>'+rowObject.doctorname;
            }

            $('#reserve').click(function(){
                $('#modal_reserve').modal({
                    centered: false,
                    closable: false,
                    closeIcon: true,
                    onHidden : function(){
                        emptyFormdata_div("#modalform",['#mrn','#episno','#resourcecode','#pat_name']);
                        $('#fail_msg').html('');
                    },
                    onApprove : function() {
                        var obj={
                            'action':'reserve_ot_iframe',
                            '_token': $('#_token').val()
                        };

                        var serializedForm = trimmall("#modalform",true)

                        $.post("apptrsc_rsc/form",serializedForm+'&'+$.param(obj), function (data) {

                        }).fail(function (data) {
                            $('#fail_msg').html("<li>"+data.responseText+"</li>");
                        }).done(function (data) {
                            $('#calendar').fullCalendar( 'refetchEventSources', 'apptbook' );
                            refreshGrid("#jqGrid", urlParam);
                            $('#modal_reserve').modal('hide');
                        });

                        return false;
                    }
                }).modal('show').modal('refresh').modal('refresh');
            });

            init_icd_but();
            init_mma_but();
	    });

        function init_icd_but(){
            var gridname = 'icd_grid';
            var dialogname = 'icd_dialog'
            var title = 'Pick ICD';
            var unique = 'icd_apptrsc';
            var textare = 'diagnosis';
            var urlParam = {
                action:'get_table_default',
                url:'util/get_table_default',
                field:'',
                table_name:'hisdb.diagtab',
                table_id:'idno',
                filterCol:['type'],
                filterVal:['icd-10']
            }

            var dialog = "<div id='"+dialogname+"' title='"+title+"'><div class='panel panel-default'><div class='panel-heading'><form id='checkForm_"+unique+"' class='form-inline'><div class='form-group'><b>Search: </b><div id='Dcol_"+unique+"' name='Dcol_"+unique+"'></div></div><div class='form-group' style='width:70%' id='Dparentdiv_"+unique+"'><input id='Dtext_"+unique+"' name='Dtext_"+unique+"' type='search' style='width:100%' placeholder='Search here ...' class='form-control text-uppercase' autocomplete='off'></div></form></div><div class=panel-body><div id='"+gridname+"_c' class='col-xs-12' align='center'><table id='"+gridname+"' class='table table-striped'></table><div id='"+gridname+"Pager'></div></div></div></div></div>";

            $("html").append(dialog);

            $("#"+dialogname).dialog({
                autoOpen: false,
                width:  7/10 * $(window).width(),
                modal: true,
                open: function(event, ui){
                    $("#"+gridname).jqGrid ('setGridWidth', Math.floor($("#"+gridname+"_c")[0].offsetWidth-$("#"+gridname+"_c")[0].offsetLeft));
                    $("div[aria-describedby="+dialogname+"]").css('z-index',10000);
                },
                close: function( event, ui ){
                    $("#Dtext_"+unique).val('')
                },
            });

            $("#"+gridname).jqGrid({
                datatype: "local",
                colModel: [
                    { label: 'Code', name: 'icdcode', width: 30, canSearch:true , checked:true},
                    { label: 'Description', name: 'description', width: 80, classes: 'pointer', canSearch:true },
                ],
                autowidth: true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,hoverrows:false,
                pager: "#"+gridname+"Pager",
                onSelectRow:function(rowid, selected){

                },
                ondblClickRow: function(rowid, iRow, iCol, e){
                    $('#'+textare).val(selrowData("#"+gridname).icdcode+' '+selrowData("#"+gridname).description);
                    $("#"+dialogname).dialog( "close" );
                },
                loadComplete: function(data) {

                },
                gridComplete: function() {

                },
            });
            othDialog_radio_btnicd(gridname,unique);

            $('#btn_icd').click(function(){
                urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
                refreshGrid("#"+gridname,urlParam);
                $("#"+dialogname).dialog( "open" );
            });


            $("#Dtext_"+unique).on('keyup',{unique:unique,gridname:gridname,urlParam:urlParam},onChange_btnicd);
            $("#Dcol_"+unique).on('change',{unique:unique,gridname:gridname,urlParam:urlParam},onChange_btnicd);
        }

        function init_mma_but(){
            var gridname = 'mma_grid';
            var dialogname = 'mma_dialog'
            var title = 'Pick MMA';
            var unique = 'mma_apptrsc';
            var textare = 'procedure';
            var urlParam = {
                action:'get_table_default',
                url:'util/get_table_default',
                field:'',
                table_name:'hisdb.mmamaster',
                table_id:'idno'
            }

            var dialog = "<div id='"+dialogname+"' title='"+title+"'><div class='panel panel-default'><div class='panel-heading'><form id='checkForm_"+unique+"' class='form-inline'><div class='form-group'><b>Search: </b><div id='Dcol_"+unique+"' name='Dcol_"+unique+"'></div></div><div class='form-group' style='width:70%' id='Dparentdiv_"+unique+"'><input id='Dtext_"+unique+"' name='Dtext_"+unique+"' type='search' style='width:100%' placeholder='Search here ...' class='form-control text-uppercase' autocomplete='off'></div></form></div><div class=panel-body><div id='"+gridname+"_c' class='col-xs-12' align='center'><table id='"+gridname+"' class='table table-striped'></table><div id='"+gridname+"Pager'></div></div></div></div></div>";

            $("html").append(dialog);

            $("#"+dialogname).dialog({
                autoOpen: false,
                width:  7/10 * $(window).width(),
                modal: true,
                open: function(event, ui){
                    $("#"+gridname).jqGrid ('setGridWidth', Math.floor($("#"+gridname+"_c")[0].offsetWidth-$("#"+gridname+"_c")[0].offsetLeft));
                    $("div[aria-describedby="+dialogname+"]").css('z-index',10000);
                },
                close: function( event, ui ){
                    $("#Dtext_"+unique).val('')
                },
            });

            $("#"+gridname).jqGrid({
                datatype: "local",
                colModel: [
                    { label: 'Code', name: 'mmacode', width: 30, canSearch:true , checked:true},
                    { label: 'Description', name: 'description', width: 80, classes: 'pointer', canSearch:true },
                ],
                autowidth: true,viewrecords:true,loadonce:false,width:200,height:200,owNum:30,hoverrows:false,
                pager: "#"+gridname+"Pager",
                onSelectRow:function(rowid, selected){

                },
                ondblClickRow: function(rowid, iRow, iCol, e){
                    $('#'+textare).val(selrowData("#"+gridname).mmacode+' '+selrowData("#"+gridname).description);
                    $("#"+dialogname).dialog( "close" );
                },
                loadComplete: function(data) {

                },
                gridComplete: function() {

                },
            });
            othDialog_radio_btnicd(gridname,unique);

            $('#btn_mma').click(function(){
                urlParam.searchCol2=urlParam.searchVal2=urlParam.searchCol=urlParam.searchVal=null;
                refreshGrid("#"+gridname,urlParam);
                $("#"+dialogname).dialog( "open" );
            });


            $("#Dtext_"+unique).on('keyup',{unique:unique,gridname:gridname,urlParam:urlParam},onChange_btnicd);
            $("#Dcol_"+unique).on('change',{unique:unique,gridname:gridname,urlParam:urlParam},onChange_btnicd);
        }

        function othDialog_radio_btnicd(gridname,unique){
            $.each($("#"+gridname).jqGrid('getGridParam','colModel'), function( index, value ) {
                if(value['canSearch']){
                    if(value['checked']){
                        $("#Dcol_"+unique+"").append("<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' checked>"+value['label']+"</input></label>" );
                    }else{
                        $("#Dcol_"+unique+"").append( "<label class='radio-inline'><input type='radio' name='dcolr' value='"+value['name']+"' >"+value['label']+"</input></label>" );
                    }
                }
            });
        }

        function onChange_btnicd(event){
            let unique = event.data.unique;
            let gridname = event.data.gridname;
            let urlParam = event.data.urlParam;

            let Dtext=$("#Dtext_"+unique).val().trim();
            if(Dtext.length == 1){
                return false;
            }
            var Dcol=$("#Dcol_"+unique+" input:radio[name=dcolr]:checked").val();

            let split = Dtext.split(" "),searchCol=[],searchVal=[];
            $.each(split, function( index, value ) {
                searchCol.push(Dcol);
                searchVal.push('%'+value+'%');
            });
            if(event.type=="keyup" && Dtext != ''){
                delay(function(){
                    urlParam.searchCol=searchCol;
                    urlParam.searchVal=searchVal;
                    refreshGrid("#"+gridname,urlParam);
                },500);
            }else if(event.type=="change" && Dtext != ''){
                urlParam.searchCol=searchCol;
                urlParam.searchVal=searchVal;
                refreshGrid("#"+gridname,urlParam);
            }else{
                refreshGrid("#"+gridname,urlParam);
            }
        }
	</script>
@endsection


