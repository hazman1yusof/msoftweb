
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $("button.refreshbtn_requestFor").click(function (){
    //     empty_requestFor_ptcare();
    //     populate_requestFor_ptcare(selrowData('#jqGrid'));
    // });
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formRequestFor');
    
    ////////////////////////////////////////////otbook starts////////////////////////////////////////////
    disableForm('#formOTBookReqFor');
    
    $("#new_otbookReqFor").click(function (){
        get_default_otbookReqFor();
        $('#cancel_otbookReqFor').data('oper','add');
        button_state_otbookReqFor('wait');
        enableForm('#formOTBookReqFor');
        rdonly('#formOTBookReqFor');
        emptyFormdata_div("#formOTBookReqFor",['#mrn_requestFor','#episno_requestFor','#otReqFor_doctorname']);
    });
    
    $("#edit_otbookReqFor").click(function (){
        button_state_otbookReqFor('wait');
        enableForm('#formOTBookReqFor');
        rdonly('#formOTBookReqFor');
    });
    
    $("#save_otbookReqFor").click(function (){
        disableForm('#formOTBookReqFor');
        if($('#formOTBookReqFor').isValid({requiredFields: ''}, conf, true)){
            saveForm_otbookReqFor(function (data){
                // emptyFormdata_div("#formOTBookReqFor",['#mrn_requestFor','#episno_requestFor']);
                // disableForm('#formOTBookReqFor');
                $('#cancel_otbookReqFor').data('oper','edit');
                $("#cancel_otbookReqFor").click();
                populate_otbookReqFor_getdata();
                $('#refresh_main').click();
                SmoothScrollToTop();
            });
        }else{
            enableForm('#formOTBookReqFor');
            rdonly('#formOTBookReqFor');
        }
    });
    
    $("#cancel_otbookReqFor").click(function (){
        // emptyFormdata_div("#formOTBookReqFor",['#mrn_requestFor','#episno_requestFor']);
        disableForm('#formOTBookReqFor');
        button_state_otbookReqFor($(this).data('oper'));
    });
    //////////////////////////////////////////////otbook ends//////////////////////////////////////////////
    
    ///////////////////////////////////////////radClinic starts///////////////////////////////////////////
    disableForm('#formRadClinicReqFor');
    
    $("#new_radClinicReqFor").click(function (){
        get_default_radClinicReqFor();
        $('#cancel_radClinicReqFor').data('oper','add');
        button_state_radClinicReqFor('wait');
        enableForm('#formRadClinicReqFor');
        rdonly('#formRadClinicReqFor');
        emptyFormdata_div("#formRadClinicReqFor",['#mrn_requestFor','#episno_requestFor']);
        // $('#ReqFor_clinicaldata').prop('disabled',true);
    });
    
    $("#edit_radClinicReqFor").click(function (){
        button_state_radClinicReqFor('wait');
        enableForm('#formRadClinicReqFor');
        rdonly('#formRadClinicReqFor');
        // $('#ReqFor_clinicaldata').prop('disabled',true);
    });
    
    $("#save_radClinicReqFor").click(function (){
        disableForm('#formRadClinicReqFor');
        if($('#formRadClinicReqFor').isValid({requiredFields: ''}, conf, true)){
            saveForm_radClinicReqFor(function (data){
                // emptyFormdata_div("#formRadClinicReqFor",['#mrn_requestFor','#episno_requestFor']);
                // disableForm('#formRadClinicReqFor');
                $('#cancel_radClinicReqFor').data('oper','edit');
                $("#cancel_radClinicReqFor").click();
                populate_radClinicReqFor_getdata();
            });
        }else{
            enableForm('#formRadClinicReqFor');
            rdonly('#formRadClinicReqFor');
        }
    });
    
    $("#cancel_radClinicReqFor").click(function (){
        // emptyFormdata_div("#formRadClinicReqFor",['#mrn_requestFor','#episno_requestFor']);
        disableForm('#formRadClinicReqFor');
        button_state_radClinicReqFor($(this).data('oper'));
    });
    ////////////////////////////////////////////radClinic ends////////////////////////////////////////////
    
    //////////////////////////////////////////////mri starts//////////////////////////////////////////////
    disableForm('#formMRIReqFor');
    
    $("#new_mriReqFor").click(function (){
        get_default_mriReqFor();
        $('#cancel_mriReqFor').data('oper','add');
        button_state_mriReqFor('wait');
        enableForm('#formMRIReqFor');
        rdonly('#formMRIReqFor');
        emptyFormdata_div("#formMRIReqFor",['#mrn_requestFor','#episno_requestFor']);
    });
    
    $("#edit_mriReqFor").click(function (){
        button_state_mriReqFor('wait');
        enableForm('#formMRIReqFor');
        rdonly('#formMRIReqFor');
    });
    
    $("#save_mriReqFor").click(function (){
        disableForm('#formMRIReqFor');
        if($('#formMRIReqFor').isValid({requiredFields: ''}, conf, true)){
            saveForm_mriReqFor(function (data){
                // emptyFormdata_div("#formMRIReqFor",['#mrn_requestFor','#episno_requestFor']);
                // disableForm('#formMRIReqFor');
                $('#cancel_mriReqFor').data('oper','edit');
                $("#cancel_mriReqFor").click();
                populate_mriReqFor_getdata();
            });
        }else{
            enableForm('#formMRIReqFor');
            rdonly('#formMRIReqFor');
        }
    });
    
    $("#cancel_mriReqFor").click(function (){
        // emptyFormdata_div("#formMRIReqFor",['#mrn_requestFor','#episno_requestFor']);
        disableForm('#formMRIReqFor');
        button_state_mriReqFor($(this).data('oper'));
    });
    
    $("#accept_mriReqFor").click(function (){
        radiographer_acceptReqFor();
    });
    ///////////////////////////////////////////////mri ends///////////////////////////////////////////////
    
    ////////////////////////////////////////////physio starts////////////////////////////////////////////
    disableForm('#formPhysioReqFor');
    
    $("#new_physioReqFor").click(function (){
        $('#cancel_physioReqFor').data('oper','add');
        button_state_physioReqFor('wait');
        enableForm('#formPhysioReqFor');
        rdonly('#formPhysioReqFor');
        emptyFormdata_div("#formPhysioReqFor",['#mrn_requestFor','#episno_requestFor','#phyReqFor_doctorname']);
    });
    
    $("#edit_physioReqFor").click(function (){
        button_state_physioReqFor('wait');
        enableForm('#formPhysioReqFor');
        rdonly('#formPhysioReqFor');
    });
    
    $("#save_physioReqFor").click(function (){
        disableForm('#formPhysioReqFor');
        if($('#formPhysioReqFor').isValid({requiredFields: ''}, conf, true)){
            saveForm_physioReqFor(function (data){
                // emptyFormdata_div("#formPhysioReqFor",['#mrn_requestFor','#episno_requestFor']);
                // disableForm('#formPhysioReqFor');
                $('#cancel_physioReqFor').data('oper','edit');
                $("#cancel_physioReqFor").click();
                populate_physioReqFor_getdata();
            });
        }else{
            enableForm('#formPhysioReqFor');
            rdonly('#formPhysioReqFor');
        }
    });
    
    $("#cancel_physioReqFor").click(function (){
        // emptyFormdata_div("#formPhysioReqFor",['#mrn_requestFor','#episno_requestFor']);
        disableForm('#formPhysioReqFor');
        button_state_physioReqFor($(this).data('oper'));
    });
    /////////////////////////////////////////////physio ends/////////////////////////////////////////////
    
    ///////////////////////////////////////////dressing starts///////////////////////////////////////////
    disableForm('#formDressingReqFor');
    
    $("#new_dressingReqFor").click(function (){
        $('#cancel_dressingReqFor').data('oper','add');
        button_state_dressingReqFor('wait');
        enableForm('#formDressingReqFor');
        rdonly('#formDressingReqFor');
        emptyFormdata_div("#formDressingReqFor",['#mrn_requestFor','#episno_requestFor','#dressingReqFor_patientname','#ReqFor_patientnric','#dressingReqFor_doctorname']);
    });
    
    $("#edit_dressingReqFor").click(function (){
        button_state_dressingReqFor('wait');
        enableForm('#formDressingReqFor');
        rdonly('#formDressingReqFor');
    });
    
    $("#save_dressingReqFor").click(function (){
        disableForm('#formDressingReqFor');
        if($('#formDressingReqFor').isValid({requiredFields: ''}, conf, true)){
            saveForm_dressingReqFor(function (data){
                // emptyFormdata_div("#formDressingReqFor",['#mrn_requestFor','#episno_requestFor']);
                // disableForm('#formDressingReqFor');
                $('#cancel_dressingReqFor').data('oper','edit');
                $("#cancel_dressingReqFor").click();
                populate_dressingReqFor_getdata();
            });
        }else{
            enableForm('#formDressingReqFor');
            rdonly('#formDressingReqFor');
        }
    });
    
    $("#cancel_dressingReqFor").click(function (){
        // emptyFormdata_div("#formDressingReqFor",['#mrn_requestFor','#episno_requestFor']);
        disableForm('#formDressingReqFor');
        button_state_dressingReqFor($(this).data('oper'));
    });
    ////////////////////////////////////////////dressing ends////////////////////////////////////////////
    
    /////////////////////////////////////////print button starts/////////////////////////////////////////
    $("#otbookReqFor_chart").click(function (){
        window.open('./doctornote/otbook_chart?mrn='+$('#mrn_requestFor').val()+'&episno='+$("#episno_requestFor").val(), '_blank');
    });
    
    $("#radClinicReqFor_chart").click(function (){
        window.open('./doctornote/radClinic_chart?mrn='+$('#mrn_requestFor').val()+'&episno='+$("#episno_requestFor").val()+'&age='+$("#age_requestFor").val(), '_blank');
    });
    
    $("#mriReqFor_chart").click(function (){
        window.open('./doctornote/mri_chart?mrn='+$('#mrn_requestFor').val()+'&episno='+$("#episno_requestFor").val(), '_blank');
    });
    
    $("#physioReqFor_chart").click(function (){
        window.open('./doctornote/physio_chart?mrn='+$('#mrn_requestFor').val()+'&episno='+$("#episno_requestFor").val(), '_blank');
    });
    
    $("#dressingReqFor_chart").click(function (){
        window.open('./doctornote/dressing_chart?mrn='+$('#mrn_requestFor').val()+'&episno='+$("#episno_requestFor").val(), '_blank');
    });
    //////////////////////////////////////////print button ends//////////////////////////////////////////
    
    $('#requestFor .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        console.log(tab);
        
        switch(tab){
            case 'otbookReqFor':
                populate_otbookReqFor_getdata();
                // textarea_init_otbookReqFor();
                break;
            case 'radReqFor':
                break;
            case 'physioReqFor':
                populate_physioReqFor_getdata();
                // textarea_init_physioReqFor();
                break;
            case 'dressingReqFor':
                populate_dressingReqFor_getdata();
                // textarea_init_dressingReqFor();
                break;
        }
    }});
    
    $('#radiology .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        console.log(tab);
        
        switch(tab){
            case 'radClinicReqFor':
                populate_radClinicReqFor_getdata();
                // textarea_init_radClinicReqFor();
                break;
            case 'mriReqFor':
                populate_mriReqFor_getdata();
                // textarea_init_mriReqFor();
                break;
        }
    }});
    
    var accomodation_table = $('#accomodation_table').DataTable( {
            "ajax": "pat_mast/get_entry?action=accomodation_table",
            "paging":false,
            "columns": [
                {'data': 'desc_bt'},
                {'data': 'bednum'},
                {'data': 'desc_d'},
                {'data': 'room'},
                {'data': 'occup'},
                {'data': 'bedtype'},
                {'data': 'ward'},
            ],
            order: [[5, 'asc'],[6, 'asc']],
            columnDefs: [ {
                targets: [0,5,6],
                visible: false
            } ],
            rowGroup: {
                dataSrc: [ "desc_bt" ],
                startRender: function ( rows, group ) {
                    return group + `<i class="arrow fa fa-angle-double-down"></i>`;
                }
            },
            "createdRow": function( row, data, dataIndex ) {
                $(row).addClass( data['desc_bt'] );
                if(data.occup != 'VACANT'){
                    $(row).addClass('disabled red');
                }
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
        $('#ReqFor_bed').val(rowdata.bednum);
        $('#ReqFor_ward').val(rowdata.ward);
        $('#ReqFor_room').val(rowdata.room);
        $('#ReqFor_bedtype').val(rowdata.bedtype);
        $('#accomodation_table tbody tr').removeClass('blue');
        $(this).addClass('blue');
    });
});

var errorField = [];
conf = {
    modules: 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if(errorField.length > 0){
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_otbookReqFor('empty');
function button_state_otbookReqFor(state){
    switch(state){
        case 'empty':
            $("#toggle_requestFor").removeAttr('data-toggle');
            $('#cancel_otbookReqFor').data('oper','add');
            $('#new_otbookReqFor,#save_otbookReqFor,#cancel_otbookReqFor,#edit_otbookReqFor').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_otbookReqFor').data('oper','add');
            $("#new_otbookReqFor").attr('disabled',false);
            $('#save_otbookReqFor,#cancel_otbookReqFor,#edit_otbookReqFor').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_otbookReqFor').data('oper','edit');
            $("#edit_otbookReqFor").attr('disabled',false);
            $('#save_otbookReqFor,#cancel_otbookReqFor,#new_otbookReqFor').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $("#save_otbookReqFor,#cancel_otbookReqFor").attr('disabled',false);
            $('#edit_otbookReqFor,#new_otbookReqFor').attr('disabled',true);
            break;
    }
}

button_state_radClinicReqFor('empty');
function button_state_radClinicReqFor(state){
    switch(state){
        case 'empty':
            $("#toggle_requestFor").removeAttr('data-toggle');
            $('#cancel_radClinicReqFor').data('oper','add');
            $('#new_radClinicReqFor,#save_radClinicReqFor,#cancel_radClinicReqFor,#edit_radClinicReqFor').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_radClinicReqFor').data('oper','add');
            $("#new_radClinicReqFor").attr('disabled',false);
            $('#save_radClinicReqFor,#cancel_radClinicReqFor,#edit_radClinicReqFor').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_radClinicReqFor').data('oper','edit');
            $("#edit_radClinicReqFor").attr('disabled',false);
            $('#save_radClinicReqFor,#cancel_radClinicReqFor,#new_radClinicReqFor').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $("#save_radClinicReqFor,#cancel_radClinicReqFor").attr('disabled',false);
            $('#edit_radClinicReqFor,#new_radClinicReqFor').attr('disabled',true);
            break;
    }
}

button_state_mriReqFor('empty');
function button_state_mriReqFor(state){
    switch(state){
        case 'empty':
            $("#toggle_requestFor").removeAttr('data-toggle');
            $('#cancel_mriReqFor').data('oper','add');
            $('#new_mriReqFor,#save_mriReqFor,#cancel_mriReqFor,#edit_mriReqFor,#accept_mriReqFor').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_mriReqFor').data('oper','add');
            $("#new_mriReqFor").attr('disabled',false);
            $('#save_mriReqFor,#cancel_mriReqFor,#edit_mriReqFor,#accept_mriReqFor').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_mriReqFor').data('oper','edit');
            $("#edit_mriReqFor,#accept_mriReqFor").attr('disabled',false);
            $('#save_mriReqFor,#cancel_mriReqFor,#new_mriReqFor').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $("#save_mriReqFor,#cancel_mriReqFor").attr('disabled',false);
            $('#edit_mriReqFor,#new_mriReqFor,#accept_mriReqFor').attr('disabled',true);
            break;
    }
}

button_state_physioReqFor('empty');
function button_state_physioReqFor(state){
    switch(state){
        case 'empty':
            $("#toggle_requestFor").removeAttr('data-toggle');
            $('#cancel_physioReqFor').data('oper','add');
            $('#new_physioReqFor,#save_physioReqFor,#cancel_physioReqFor,#edit_physioReqFor').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_physioReqFor').data('oper','add');
            $("#new_physioReqFor").attr('disabled',false);
            $('#save_physioReqFor,#cancel_physioReqFor,#edit_physioReqFor').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_physioReqFor').data('oper','edit');
            $("#edit_physioReqFor").attr('disabled',false);
            $('#save_physioReqFor,#cancel_physioReqFor,#new_physioReqFor').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $("#save_physioReqFor,#cancel_physioReqFor").attr('disabled',false);
            $('#edit_physioReqFor,#new_physioReqFor').attr('disabled',true);
            break;
    }
}

button_state_dressingReqFor('empty');
function button_state_dressingReqFor(state){
    switch(state){
        case 'empty':
            $("#toggle_requestFor").removeAttr('data-toggle');
            $('#cancel_dressingReqFor').data('oper','add');
            $('#new_dressingReqFor,#save_dressingReqFor,#cancel_dressingReqFor,#edit_dressingReqFor').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_dressingReqFor').data('oper','add');
            $("#new_dressingReqFor").attr('disabled',false);
            $('#save_dressingReqFor,#cancel_dressingReqFor,#edit_dressingReqFor').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $('#cancel_dressingReqFor').data('oper','edit');
            $("#edit_dressingReqFor").attr('disabled',false);
            $('#save_dressingReqFor,#cancel_dressingReqFor,#new_dressingReqFor').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_requestFor").attr('data-toggle','collapse');
            $("#save_dressingReqFor,#cancel_dressingReqFor").attr('disabled',false);
            $('#edit_dressingReqFor,#new_dressingReqFor').attr('disabled',true);
            break;
    }
}

function empty_requestFor_ptcare(obj){
    emptyFormdata(errorField,"#formRequestFor");
    
    // panel header
    $('#name_show_requestFor').text('');
    $('#mrn_show_requestFor').text('');
    $('#sex_show_requestFor').text('');
    $('#dob_show_requestFor').text('');
    $('#age_show_requestFor').text('');
    $('#race_show_requestFor').text('');
    $('#religion_show_requestFor').text('');
    $('#occupation_show_requestFor').text('');
    $('#citizenship_show_requestFor').text('');
    $('#area_show_requestFor').text('');
    
    // formRequestFor
    $('#mrn_requestFor').val('');
    $("#episno_requestFor").val('');
    $('#ptname_requestFor').val('');
    $('#preg_requestFor').val('');
    $('#ic_requestFor').val('');
    $('#doctorname_requestFor').val('');
}

function populate_requestFor_ptcare(obj){
    emptyFormdata(errorField,"#formRequestFor");
    
    // panel header
    $('#name_show_requestFor').text(obj.Name);
    $('#mrn_show_requestFor').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_requestFor').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_requestFor').text(dob_chg(obj.DOB));
    $('#age_show_requestFor').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_requestFor').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_requestFor').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_requestFor').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_requestFor').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_requestFor').text(if_none(obj.areaDesc).toUpperCase());
    
    // formRequestFor
    $('#mrn_requestFor').val(obj.MRN);
    $("#episno_requestFor").val(obj.Episno);
    $("#age_requestFor").val(dob_age(obj.DOB));
    $('#ptname_requestFor').val(obj.Name);
    $('#preg_requestFor').val(obj.pregnant);
    $('#ic_requestFor').val(obj.Newic);
    $('#doctorname_requestFor').val(obj.doctorname);
}

function populate_otbookReqFor_getdata(){
    emptyFormdata(errorField,"#formOTBookReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_otbook',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_otbook").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val()
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data.pat_otbook)){
            autoinsert_rowdata("#formOTBookReqFor",data.pat_otbook);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshandover);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshistory);
            
            if(!emptyobj_(data.pat_otbook.ot_doctorname)){
                $("#otReqFor_doctorname").val(data.pat_otbook.ot_doctorname);
            }else{
                $("#otReqFor_doctorname").val($('#doctorname_requestFor').val());
            }
            
            button_state_otbookReqFor('edit');
        }else{
            autoinsert_rowdata("#formOTBookReqFor",data.nurshandover);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshistory);
            // by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
            $("#otReqFor_doctorname").val($('#doctorname_requestFor').val());
            
            button_state_otbookReqFor('add');
        }
        
        // textarea_init_otbookReqFor();
        toggle_reqfor_reqtype();
    });
}

function get_default_otbookReqFor(){
    emptyFormdata(errorField,"#formOTBookReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_otbook',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_otbook").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val()
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data.pat_otbook)){
            autoinsert_rowdata("#formOTBookReqFor",data.pat_otbook);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshandover);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshistory);
            
            if(!emptyobj_(data.pat_otbook.ot_doctorname)){
                $("#otReqFor_doctorname").val(data.pat_otbook.ot_doctorname);
            }else{
                $("#otReqFor_doctorname").val($('#doctorname_requestFor').val());
            }
        }else{
            autoinsert_rowdata("#formOTBookReqFor",data.nurshandover);
            autoinsert_rowdata("#formOTBookReqFor",data.nurshistory);
            // by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
            $("#otReqFor_doctorname").val($('#doctorname_requestFor').val());
        }
        
        // textarea_init_otbookReqFor();
        toggle_reqfor_reqtype();
    });
}

function populate_radClinicReqFor_getdata(){
    emptyFormdata(errorField,"#formRadClinicReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_radClinic',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_radClinic").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            if(!$.isEmptyObject(data.pat_radiology)){
                autoinsert_rowdata("#formRadClinicReqFor",data.pat_radiology);
                
                button_state_radClinicReqFor('edit');
            }else{
                button_state_radClinicReqFor('add');
            }
            
            if(!emptyobj_(data.rad_weight))$("#radReqFor_weight").val(data.rad_weight);
            // $("#radReqFor_pregnant").val($('#preg_requestFor').val());
            if(!emptyobj_(data.rad_allergy))$("#radReqFor_allergy").val(data.rad_allergy);
            // $("#radClinicReqFor_doctorname").val($('#doctorname_requestFor').val());
            
            pregnant = document.getElementById("pregnantReqFor");
            not_pregnant = document.getElementById("not_pregnantReqFor");
            if(data.pregnant == 1){
                pregnant.checked = true;
            }else{
                not_pregnant.checked = true;
            }
            
            // textarea_init_radClinicReqFor();
        }
    });
}

function get_default_radClinicReqFor(){
    emptyFormdata(errorField,"#formRadClinicReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_radClinic',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_radClinic").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formRadClinicReqFor",data.pat_radiology);
        }else{
            
        }
        
        if(!emptyobj_(data.rad_weight))$("#radReqFor_weight").val(data.rad_weight);
        // $("#radReqFor_pregnant").val($('#preg_requestFor').val());
        if(!emptyobj_(data.rad_allergy))$("#radReqFor_allergy").val(data.rad_allergy);
        // $("#radClinicReqFor_doctorname").val($('#doctorname_requestFor').val());
        
        pregnant = document.getElementById("pregnantReqFor");
        not_pregnant = document.getElementById("not_pregnantReqFor");
        if(data.pregnant == 1){
            pregnant.checked = true;
        }else{
            not_pregnant.checked = true;
        }
        
        // textarea_init_radClinicReqFor();
    });
}

function populate_mriReqFor_getdata(){
    emptyFormdata(errorField,"#formMRIReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_mri',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_mri").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data.pat_mri)){
            autoinsert_rowdata("#formMRIReqFor",data.pat_mri);
            
            if(!emptyobj_(data.pat_mri.mri_doctorname)){
                $("#mriReqFor_doctorname").val(data.pat_mri.mri_doctorname);
            }else{
                $("#mriReqFor_doctorname").val($('#doctorname_requestFor').val());
            }
            
            // if(!emptyobj_(data.pat_mri.radiographer)){
            //     button_state_mriReqFor('empty');
            // }else{
                button_state_mriReqFor('edit');
            // }
        }else{
            // by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
            $("#mriReqFor_doctorname").val($('#doctorname_requestFor').val());
            
            button_state_mriReqFor('add');
        }
        
        if(!emptyobj_(data.mri_weight))$("#mriReqFor_weight").val(data.mri_weight);
        $("#mriReqFor_patientname").val($('#ptname_requestFor').val());
        // textarea_init_mriReqFor();
    });
}

function get_default_mriReqFor(){
    emptyFormdata(errorField,"#formMRIReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_mri',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_mri").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formMRIReqFor",data.pat_mri);
            
            if(!emptyobj_(data.pat_mri.mri_doctorname)){
                $("#mriReqFor_doctorname").val(data.pat_mri.mri_doctorname);
            }else{
                $("#mriReqFor_doctorname").val($('#doctorname_requestFor').val());
            }
        }else{
            // by default, baca admdoctor first. Lepastu baca from db sebab maybe key in diff name.
            $("#mriReqFor_doctorname").val($('#doctorname_requestFor').val());
        }
        
        if(!emptyobj_(data.mri_weight))$("#mriReqFor_weight").val(data.mri_weight);
        $("#mriReqFor_patientname").val($('#ptname_requestFor').val());
        // textarea_init_mriReqFor();
    });
}

function radiographer_acceptReqFor(){
    // bootbox.confirm({
    //     message: "Are you sure you want to accept?",
    //     buttons: { confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
    //     callback: function (result){
    //         if(result == true){
    //             var saveParam = {
    //                 action: 'accept_mri',
    //                 mrn: $('#mrn_requestFor').val(),
    //                 episno: $("#episno_requestFor").val(),
    //             }
                
    //             var postobj = {
    //                 _token: $('#_token').val(),
    //             };
                
    //             $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj), function (data){
                    
    //             },'json').done(function (data){
    //                 callback(data);
    //             });
    //         }else{
                
    //         }
    //     }
    // });
    
    var result = confirm("Are you sure you want to accept?");
    if(result == true){
        var saveParam = {
            action: 'accept_mri',
            mrn: $('#mrn_requestFor').val(),
            episno: $("#episno_requestFor").val(),
        }
        
        var postobj = {
            _token: $('#_token').val(),
        };
        
        $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').done(function (data){
            // callback(data);
            button_state_mriReqFor('empty');
            get_default_mriReqFor();
        });
    }else{
        
    }
}

function populate_physioReqFor_getdata(){
    emptyFormdata(errorField,"#formPhysioReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_physio',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_physio").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val()
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formPhysioReqFor",data.pat_physio);
            
            button_state_physioReqFor('edit');
        }else{
            button_state_physioReqFor('add');
        }
        
        // $("#phyReqFor_doctorname").val($('#doctorname_requestFor').val());
        // textarea_init_physioReqFor();
    });
}

function populate_dressingReqFor_getdata(){
    emptyFormdata(errorField,"#formDressingReqFor",["#mrn_requestFor","#episno_requestFor"]);
    
    var saveParam = {
        action: 'get_table_dressing',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_dressing").val(),
        mrn: $("#mrn_requestFor").val(),
        episno: $("#episno_requestFor").val()
    };
    
    $.get("./ptcare_requestfor/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formDressingReqFor",data.pat_dressing);
            
            button_state_dressingReqFor('edit');
        }else{
            button_state_dressingReqFor('add');
        }
        
        $("#dressingReqFor_patientname").val($('#ptname_requestFor').val());
        $("#ReqFor_patientnric").val($('#ic_requestFor').val());
        // $("#dressingReqFor_doctorname").val($('#doctorname_requestFor').val());
        // textarea_init_dressingReqFor();
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
        }else if(input.is("textarea")){
            if(value !== null){
                let newval = value.replaceAll("</br>",'\n');
                input.val(newval);
            }
        }else{
            input.val(value);
        }
    });
}

function saveForm_otbookReqFor(callback){

    var result = confirm("This patient will be transfer to ward : "+$('#ReqFor_bed').val()+"");
    if(result != true){
        return 0;
    }

    var saveParam = {
        action: 'save_otbook',
        oper: $("#cancel_otbookReqFor").data('oper'),
        mrn: $('#mrn_requestFor').val(),
        episno: $("#episno_requestFor").val(),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formOTBookReqFor").serializeArray();
    
    values = values.concat(
        $('#formOTBookReqFor input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOTBookReqFor input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOTBookReqFor input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOTBookReqFor select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values.push({
        name: 'op_date',
        value: $('#formOTBookReqFor input[name=op_date]').val()
    })
    values.push({
        name: 'oper_type',
        value: $('#formOTBookReqFor input[name=oper_type]').val()
    })
    values.push({
        name: 'ot_diagnosis',
        value: $('#formOTBookReqFor textarea[name=ot_diagnosis]').val()
    })
    values.push({
        name: 'ot_diagnosedby',
        value: $('#formOTBookReqFor input[name=ot_diagnosedby]').val()
    })
    values.push({
        name: 'ot_remarks',
        value: $('#formOTBookReqFor textarea[name=ot_remarks]').val()
    })
    values.push({
        name: 'ot_doctorname',
        value: $('#formOTBookReqFor input[name=ot_doctorname]').val()
    })
    values.push({
        name: 'ot_lastuser',
        value: $('#formOTBookReqFor input[name=ot_lastuser]').val()
    })
    
    $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_radClinicReqFor(callback){
    var saveParam = {
        action: 'save_radClinic',
        oper: $("#cancel_radClinicReqFor").data('oper'),
        mrn: $('#mrn_requestFor').val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formRadClinicReqFor").serializeArray();
    
    values = values.concat(
        $('#formRadClinicReqFor input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formRadClinicReqFor input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formRadClinicReqFor input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formRadClinicReqFor select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values.push({
        name: 'rad_weight',
        value: $('#formRadClinicReqFor input[name=rad_weight]').val()
    })
    values.push({
        name: 'rad_allergy',
        value: $('#formRadClinicReqFor textarea[name=rad_allergy]').val()
    })
    values.push({
        name: 'xray_date',
        value: $('#formRadClinicReqFor input[name=xray_date]').val()
    })
    values.push({
        name: 'xray_remark',
        value: $('#formRadClinicReqFor textarea[name=xray_remark]').val()
    })
    values.push({
        name: 'mri_date',
        value: $('#formRadClinicReqFor input[name=mri_date]').val()
    })
    values.push({
        name: 'mri_remark',
        value: $('#formRadClinicReqFor textarea[name=mri_remark]').val()
    })
    values.push({
        name: 'angio_date',
        value: $('#formRadClinicReqFor input[name=angio_date]').val()
    })
    values.push({
        name: 'angio_remark',
        value: $('#formRadClinicReqFor textarea[name=angio_remark]').val()
    })
    values.push({
        name: 'ultrasound_date',
        value: $('#formRadClinicReqFor input[name=ultrasound_date]').val()
    })
    values.push({
        name: 'ultrasound_remark',
        value: $('#formRadClinicReqFor textarea[name=ultrasound_remark]').val()
    })
    values.push({
        name: 'ct_date',
        value: $('#formRadClinicReqFor input[name=ct_date]').val()
    })
    values.push({
        name: 'ct_remark',
        value: $('#formRadClinicReqFor textarea[name=ct_remark]').val()
    })
    values.push({
        name: 'fluroscopy_date',
        value: $('#formRadClinicReqFor input[name=fluroscopy_date]').val()
    })
    values.push({
        name: 'fluroscopy_remark',
        value: $('#formRadClinicReqFor textarea[name=fluroscopy_remark]').val()
    })
    values.push({
        name: 'mammogram_date',
        value: $('#formRadClinicReqFor input[name=mammogram_date]').val()
    })
    values.push({
        name: 'mammogram_remark',
        value: $('#formRadClinicReqFor textarea[name=mammogram_remark]').val()
    })
    values.push({
        name: 'bmd_date',
        value: $('#formRadClinicReqFor input[name=bmd_date]').val()
    })
    values.push({
        name: 'bmd_remark',
        value: $('#formRadClinicReqFor textarea[name=bmd_remark]').val()
    })
    values.push({
        name: 'clinicaldata',
        value: $('#formRadClinicReqFor textarea[name=clinicaldata]').val()
    })
    values.push({
        name: 'radClinic_doctorname',
        value: $('#formRadClinicReqFor input[name=radClinic_doctorname]').val()
    })
    values.push({
        name: 'rad_note',
        value: $('#formRadClinicReqFor textarea[name=rad_note]').val()
    })
    values.push({
        name: 'radClinic_radiologist',
        value: $('#formRadClinicReqFor input[name=radClinic_radiologist]').val()
    })
    
    $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_mriReqFor(callback){
    var saveParam = {
        action: 'save_mri',
        oper: $("#cancel_mriReqFor").data('oper'),
        mrn: $('#mrn_requestFor').val(),
        episno: $("#episno_requestFor").val(),
        // recorddate: $("#recorddate_doctorNote").val(),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formMRIReqFor").serializeArray();
    
    values = values.concat(
        $('#formMRIReqFor input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formMRIReqFor input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formMRIReqFor input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formMRIReqFor select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values.push({
        name: 'mri_weight',
        value: $('#formMRIReqFor input[name=mri_weight]').val()
    })
    values.push({
        name: 'mri_entereddate',
        value: $('#formMRIReqFor input[name=mri_entereddate]').val()
    })
    values.push({
        name: 'prosvalve_rmk',
        value: $('#formMRIReqFor textarea[name=prosvalve_rmk]').val()
    })
    values.push({
        name: 'oper3mth_remark',
        value: $('#formMRIReqFor textarea[name=oper3mth_remark]').val()
    })
    values.push({
        name: 'bloodurea',
        value: $('#formMRIReqFor input[name=bloodurea]').val()
    })
    values.push({
        name: 'serum_creatinine',
        value: $('#formMRIReqFor input[name=serum_creatinine]').val()
    })
    values.push({
        name: 'mri_doctorname',
        value: $('#formMRIReqFor input[name=mri_doctorname]').val()
    })
    values.push({
        name: 'mri_radiologist',
        value: $('#formMRIReqFor input[name=mri_radiologist]').val()
    })
    values.push({
        name: 'radiographer',
        value: $('#formMRIReqFor input[name=radiographer]').val()
    })
    values.push({
        name: 'mri_lastuser',
        value: $('#formMRIReqFor input[name=mri_lastuser]').val()
    })
    
    $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_physioReqFor(callback){
    var saveParam = {
        action: 'save_physio',
        oper: $("#cancel_physioReqFor").data('oper'),
        mrn: $('#mrn_requestFor').val(),
        episno: $("#episno_requestFor").val(),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formPhysioReqFor").serializeArray();
    
    values = values.concat(
        $('#formPhysioReqFor input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioReqFor input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioReqFor input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioReqFor select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values.push({
        name: 'req_date',
        value: $('#formPhysioReqFor input[name=req_date]').val()
    })
    values.push({
        name: 'clinic_diag',
        value: $('#formPhysioReqFor textarea[name=clinic_diag]').val()
    })
    values.push({
        name: 'findings',
        value: $('#formPhysioReqFor textarea[name=findings]').val()
    })
    values.push({
        name: 'phy_treatment',
        value: $('#formPhysioReqFor textarea[name=phy_treatment]').val()
    })
    values.push({
        name: 'phy_doctorname',
        value: $('#formPhysioReqFor input[name=phy_doctorname]').val()
    })
    values.push({
        name: 'phy_lastuser',
        value: $('#formPhysioReqFor input[name=phy_lastuser]').val()
    })
    
    $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function saveForm_dressingReqFor(callback){
    var saveParam = {
        action: 'save_dressing',
        oper: $("#cancel_dressingReqFor").data('oper'),
        mrn: $('#mrn_requestFor').val(),
        episno: $("#episno_requestFor").val(),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formDressingReqFor").serializeArray();
    
    values = values.concat(
        $('#formDressingReqFor input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formDressingReqFor input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formDressingReqFor input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formDressingReqFor select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values.push({
        name: 'od_dressing',
        value: $('#formDressingReqFor input[name=od_dressing]').val()
    })
    values.push({
        name: 'bd_dressing',
        value: $('#formDressingReqFor input[name=bd_dressing]').val()
    })
    values.push({
        name: 'eod_dressing',
        value: $('#formDressingReqFor input[name=eod_dressing]').val()
    })
    values.push({
        name: 'others_dressing',
        value: $('#formDressingReqFor input[name=others_dressing]').val()
    })
    values.push({
        name: 'others_name',
        value: $('#formDressingReqFor input[name=others_name]').val()
    })
    values.push({
        name: 'solution',
        value: $('#formDressingReqFor textarea[name=solution]').val()
    })
    values.push({
        name: 'dressing_doctorname',
        value: $('#formDressingReqFor input[name=dressing_doctorname]').val()
    })
    values.push({
        name: 'dressing_lastuser',
        value: $('#formDressingReqFor input[name=dressing_lastuser]').val()
    })
    
    $.post("./ptcare_requestfor/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

$('#tab_requestFor').on('shown.bs.collapse', function (){
    SmoothScrollTo("#tab_requestFor", 500);
    
    // let curtype = $(this).data('curtype');
    // $('#requestFor.menu a#'+curtype).tab('show');
    
    populate_otbookReqFor_getdata();
    populate_radClinicReqFor_getdata();
    populate_mriReqFor_getdata();
    populate_physioReqFor_getdata();
    populate_dressingReqFor_getdata();
});

$("#tab_requestFor").on("hide.bs.collapse", function (){
    // button_state_requestFor('empty');
    disableForm('#formRequestFor');
    disableForm('#formOTBookReqFor');
    disableForm('#formRadClinicReqFor');
    disableForm('#formMRIReqFor');
    disableForm('#formPhysioReqFor');
    disableForm('#formDressingReqFor');
});

function textarea_init_otbookReqFor(){
    $('textarea#otReqFor_remarks').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

function textarea_init_radClinicReqFor(){
    $('textarea#radReqFor_allergy,textarea#ReqFor_xray_remark,textarea#ReqFor_mri_remark,textarea#ReqFor_angio_remark,textarea#ReqFor_ultrasound_remark,textarea#ReqFor_ct_remark,textarea#ReqFor_fluroscopy_remark,textarea#ReqFor_mammogram_remark,textarea#ReqFor_bmd_remark,textarea#ReqFor_clinicaldata,textarea#ReqFor_rad_note').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

function textarea_init_mriReqFor(){
    $('textarea#ReqFor_prosvalve_rmk,textarea#ReqFor_oper3mth_remark').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

function textarea_init_physioReqFor(){
    $('textarea#ReqFor_clinic_diag,textarea#ReqFor_findings,textarea#phyReqFor_treatment').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

function textarea_init_dressingReqFor(){
    $('textarea#ReqFor_solution').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

$("#formOTBookReqFor input[name=req_type]").on('click', function (){
    toggle_reqfor_reqtype();
});

function toggle_reqfor_reqtype(){
    if(document.getElementById("req_type_ward").checked){
        $('#ReqFor_Bed_div').show();
        $('#ReqFor_OT_div').hide();
        let newurl = './wardbook_iframe';
        let cururl = $('iframe#wardbook_iframe').attr('src');

        if(newurl != cururl){
            $('iframe#wardbook_iframe').attr('src',newurl);
        }
    }else if(document.getElementById("req_type_ot").checked){
        $('#ReqFor_Bed_div').hide();
        $('#ReqFor_OT_div').show();
        let newurl = './apptrsc_rsc_iframe?mrn='+$('#mrn_requestFor').val()+'&episno='+$('#episno_requestFor').val();
        let cururl = $('iframe#otbook_iframe').attr('src');

        if(newurl != cururl){
            $('iframe#otbook_iframe').attr('src',newurl);
        }
    }
}

window.message_parent_wardbook = function(data) { // inside the iframe
    console.log(data);
    $('#ReqFor_bed').val(data.bednum);
    $('#ReqFor_ward').val(data.ward);
    $('#ReqFor_room').val(data.room);
    $('#ReqFor_bedtype').val(data.bedtype);
};