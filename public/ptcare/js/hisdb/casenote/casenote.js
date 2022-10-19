var preepisode;
$(document).ready(function() {

    $('#tab_patient_info a:last').hide();    // hide Medical Info tab
    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });
    // $('#frm_patient_info').validate({
    //     rules: {
    //         telh: {
    //           require_from_group: [1, ".phone-group"]
    //         },
    //         telhp: {
    //           require_from_group: [1, ".phone-group"]
    //         }
    //     }
    // });    // patient form validation

    var counter = 0;
    var grid = $("#grid-command-buttons").bootgrid({
        selection: true,
        rowSelect: true,
        ajax: true,
        ajaxSettings: {
            cache: false
        },
        searchSettings: {
            delay: 350,
            characters: 2
        },
        post: function ()
        {    
            Stext = $('.search-field').val();
            Scol = $('#Scol').val();

            if(Stext.trim() != ''){

                var split = Stext.split(" "),_searchCol=[],_searchVal=[];
                $.each(split, function( index, value ) {
                    _searchCol.push(Scol);
                    _searchVal.push('%'+value+'%');
                });

            }

            let _page = $("#grid-command-buttons").bootgrid("getCurrentPage");
            if($("#load_from_addupd").data('info') == "true" && $("#load_from_addupd").data('oper') == "add"){
                _page = $("#grid-command-buttons").bootgrid("getTotalPageCount");
            }

            return {
                page: _page,
                searchCol:_searchCol,
                searchVal:_searchVal,
                table_name:'hisdb.pat_mast',
                field:'*',
                _token: $('#csrf_token').val(),
            };
        },
        url: "casenote/post_entry?action=get_patient_list&epistycode="+$("#epistycode").val()+"&curpat="+$("#curpat").val()+"&PatClass="+$("#PatClass").val(),
        formatters: {
            "col_add": function (column,row) {
                var retval = "<button title='Address' type='button' class='btn btn-xs btn-default btn-md command-add' data-row-id=\"" + row.MRN + "\"  name=\"cmd_add" + row.MRN + "\" data-telhp=\"" + row.telhp + "\"data-telh=\"" + row.telh + "\"data-Address1=\"" + row.Address1 + "\"data-Address2=\"" + row.Address2 + "\"data-Address3=\"" + row.Address3 + "\"data-Postcode=\"" + row.Postcode + "\"data-OffAdd1=\"" + row.OffAdd1 + "\"data-OffAdd2=\"" + row.OffAdd2 + "\"data-OffAdd3=\"" + row.OffAdd3 + "\"data-OffPostcode=\"" + row.OffPostcode + "\"data-pAdd1=\"" + row.pAdd1 + "\"data-pAdd2=\"" + row.pAdd2 + "\"data-pAdd3=\"" + row.pAdd3 + "\"data-pPostCode=\"" + row.pPostCode + "\" ><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></button>";
                if(row.PatStatus == 1 && row.q_epistycode=='IP'){
                    retval+="&nbsp;<a class='btn btn-xs btn-default'><img src='img/warded.png' width='15' title='In Patinet'></a>";
                }else if(row.PatStatus == 1 && row.q_epistycode=='OP'){
                    retval+="&nbsp;<a class='btn btn-xs btn-default'><img src='img/op.png' width='15' title='Out Patient'></a>";
                }
                return retval;
            },
            "col_mrn": function (column,row) {
                return ('0000000' + row.MRN).slice(-7);
            },
            "col_dob": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday.getFullYear()) ? '' : moment(birthday).format('DD/MM/YYYY'));
            },
            "col_age": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday)) ? '' : moment().diff(birthday, 'years',false);;
            },
            "col_preg": function (column,row) {
                var retval;
                if(row.pregnant == 1){
                    retval="&nbsp;<a class='btn btn-xs btn-default'><img src='img/pregnant.png' width='25' title='In Patinet'></a>";
                }else{
                    retval="";
                }
                return retval;
            },
            "commands": function (column,row) {
                let rowid = counter++;//just for specify each row
                if(row.q_epistycode == '' || row.q_epistycode == undefined){
                    return "<button title='Edit' type='button' class='btn btn-xs btn-warning btn-md command-edit' data-row-id=\"" + rowid + "\"  id=\"cmd_edit" + row.MRN + "\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button> " +
                           "<button title='Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" data-patstatus=\"" + row.PatStatus + "\"  id=\"cmd_history" + row.MRN + "\"><b>"+$("#epistycode").val()+"</b></button>" +
                           "<button title='OTC Episode' type='button' class='btn btn-xs btn-danger btn-md command-otc-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" id=\"cmd_otc" + row.MRN + "\"><b>"+$("#epistycode2").val()+"</b></button>";

                }else{
                    if(row.q_epistycode == $("#epistycode").val() ){
                        return "<button title='Edit' type='button' class='btn btn-xs btn-warning btn-md command-edit' data-row-id=\"" + rowid + "\"  id=\"cmd_edit" + row.MRN + "\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button> " +
                           "<button title='Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode' data-row-id=\"" + rowid + "\" data-mrn=\"" + row.MRN + "\" data-patstatus=\"" + row.PatStatus + "\"  id=\"cmd_history" + row.MRN + "\"><b>"+row.q_epistycode+"</b></button>";
                    }else{
                        return "";
                    }
                    
                }
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function(){
        counter = 0;

        if(!$("#Scol").length){ //tambah search col kat atas utk search by field
            $(".actionBar").prepend(`
                <select id='Scol' class='search form-group form-control' style='width: fit-content !important;'>
                    <option value='MRN'>MRN</option>
                    <option selected='true' value='Name'>Name</option>
                    <option value='Newic'>Newic</option>
                    <option value='Staffid'>Staffid</option>
                    <option value='telhp'>Handphone</option>
                    <option value='doctor'>Doctor</option>
                </select>`);
        }

        var detailRows = '';

        /* Executes after data is loaded and rendered */
        grid.find(".command-edit").on("click", function(e){
            let rowid = $(this).data("rowId");

            let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];
            populate_patient(rowdata);
            $('#mdl_patient_info').modal({backdrop: "static"});
            $("#btn_register_patient").data("oper","edit");
            // console.log($("#grid-command-buttons").bootgrid("getCurrentRows")[rowid]);
            $("#btn_register_patient").data("idno",$("#grid-command-buttons").bootgrid("getCurrentRows")[rowid].idno);
            
            desc_show.write_desc();
        }).end().find(".command-episode").on("click", function(e) {
            populate_episode($(this).data("rowId"));
            $('#editEpisode').modal({backdrop: "static"});

        }).end().find(".command-add").on("click", function(e) {
            var html = '';

            html = html + '<table class=\"table table-bordered\">';

            html = html + '<tr>';
            html = html + '<th width=\"25%\">Contact No</th>';
            html = html + '<th width=\"25%\">Current Address</th>';
            html = html + '<th width=\"25%\">Office Address</th>';
            html = html + '<th width=\"25%\">Home Address</th>';
            html = html + '</tr>';

            html = html + '<tr>';
            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + 'Phone (Mobile): 0' + $(this).data("telhp") + '<BR/>';
            html = html + 'Phone (House): 0' + $(this).data("telh") + '<BR/>';
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"text-left\">';
            html = html + $(this).data("address1") + '<BR/>';
            html = html + $(this).data("address2") + '<BR/>';
            html = html + $(this).data("address3") + '<BR/>';
            html = html + $(this).data("postcode");
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + $(this).data("offadd1") + '<BR/>';
            html = html + $(this).data("offadd2") + '<BR/>';
            html = html + $(this).data("offadd3") + '<BR/>';
            html = html + $(this).data("offpostcode");
            html = html + '</td>';

            html = html + '<td valign=\"top\" align=\"left\">';
            html = html + $(this).data("padd1") + '<BR/>';
            html = html + $(this).data("padd2") + '<BR/>';
            html = html + $(this).data("padd3") + '<BR/>';
            html = html + $(this).data("ppostcode");
            html = html + '</td>';

            html = html + '</tr>';
            html = html + '</table>';

            detailRows = html;

            var tr = $(this).closest('tr');
            var childID = $(this).data("rowId");
            var childName = "cmd_add" + childID;
            $('[name='+childName+']').hide();

            var addDetail='';
            addDetail = '<tr ><td><a href="javascript:void(0);" class="remCF btn btn-xs btn-primary btn-md"> <span class=\"glyphicon glyphicon-minus\" aria-hidden=\"true\"></span> </a></td><td colspan=\"8\">';
            addDetail =  addDetail + detailRows;
            addDetail = addDetail + '<td></tr>';

            tr.after(addDetail);

            $(".remCF").on('click',function(){
                $('[name='+childName+']').show();
                $(this).parent().parent().remove();
            });
        }).end().find("tr").on("click", function(e) {
            $("#grid-command-buttons tr").removeClass( "justbc" );
            $(e.currentTarget).addClass( "justbc" );
        });

        if($("#load_from_addupd").data('info') == "true"){
            if($("#load_from_addupd").data('oper') == "add"){
                $("#grid-command-buttons tr:nth-last-child(1)").click();
            }else{
                $("#grid-command-buttons tr[data-row-id='"+bootgrid_last_rowid+"']").click();
            }
        }
        $("#load_from_addupd").data('info','false');

        // if($('#showTriage_curpt').length > 0){
        //     document.getElementById('showTriage_curpt').style.display = 'inline'; //hide and show heading details dekat triage
        // }

        $("table#grid-command-buttons tr[data-row-id=0]").click();

    }).on("click.rs.jquery.bootgrid", function (e,c,r){
        bootgrid_last_rowid = $("#grid-command-buttons tr.justbc").data("row-id");
        let rows = $("#grid-command-buttons").bootgrid("getCurrentRows");
        
        populate_triage_casenote(rows[bootgrid_last_rowid]);
        populate_antenatal(rows[bootgrid_last_rowid]);
        populate_doctorNote_currpt(rows[bootgrid_last_rowid]);
        populate_dieteticCareNotes_currpt(rows[bootgrid_last_rowid]);
        populate_ordcom_currpt(rows[bootgrid_last_rowid]);

    });
    var bootgrid_last_rowid = 0;

    // populatecombo1();

    // $("#txt_pat_dob").blur(function(){
    //    $('#txt_pat_age').val(gettheage($(this).val()));
    //    $("#txt_pat_dob-error").detach();
    // })

    // function gettheage(dob){
    //     if(dob != ''){
    //         var day = new Date();
    //         var dob = new Date(dob);
    //         var age_val =  day.getFullYear() - dob.getFullYear();
    //         if(isNaN(age_val))return null;
    //         return age_val;
    //     }
    //     return null;
    // }

    $( "#patientBox").click(function() { // register new patient
        $("#toggle_tabNok_emr,#toggle_tabNok_pat").parent().hide();
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","add");
        $('#PatClass').val(getUrlParameter('PatClass'));
        $('#episno').val('1');
    });

    /////////////////mykad///////////////

    $('#btn_mykad').click(function(){
       $('#mdl_mykad').modal('show');
    });

    $('#read_mykad').click(function(){
        $.getJSON('http://mycard.test/mycard_read', function(data){
            if(data.status == 'failed'){
                alert("Error reading Mycard");
            }else{
                $("#mykad_reponse").text("");
                $("#mykad_newic").val(data.ic);
                $("#mykad_DOB").val(data.dob);
                $("#mykad_birthPlace").val(data.birthplace);
                $("#mykad_pat_name").val(data.name);
                $("#mykad_oldic").val(data.oldic);
                $("#mykad_religion").val(data.religion);
                $("#mykad_gender").val(data.sex);
                $("#mykad_race").val(data.race);
                $("#mykad_address1").val(data.addr1);
                $("#mykad_address2").val(data.addr2);
                $("#mykad_address3").val(data.addr3);
                $("#mykad_city").val(data.city);
                $("#mykad_state").val(data.state);
                $("#mykad_postcode").val(data.postcode);
                $("#mykad_photo").attr('src', data.mykad_photo);
            }
        });
    });

    ////////////////habis mykad///////

    if($('#curpat').val() == "true"){
        preepisode = new preepisode_init();
        preepisode.makejqgrid();
    }

    function preepisode_init(){
        this.urlParam_preepis;

        this.refreshGrid = function(){
            refreshGrid("#jqGrid_preepis", this.urlParam_preepis);
        }

        this.makejqgrid = function(){

            this.urlParam_preepis = {
                    action:'get_table_default',
                    url:'/util/get_table_default',
                    field: '',
                    table_name: ['hisdb.pre_episode AS pe','hisdb.pat_mast AS p'],
                    join_type : ['LEFT JOIN'],
                    join_onCol : ['pe.mrn'],
                    join_onVal : ['p.mrn'],
                    fixPost:'true',
                    filterCol:['pe.compcode','pe.apptdate','pe.episno'],
                    filterVal:['session.compcode','raw.CURDATE()',0],
                }

            $("#jqGrid_preepis").jqGrid({
                datatype: "local",
                colModel: [
                    { label: 'MRN', name: 'pe_mrn' , width: 5, formatter: padzero, unformat: unpadzero},
                    { label: 'Name', name: 'pe_Name' , width: 30},
                    { label: 'Newic', name: 'pe_Newic', width: 15 },
                    { label: 'apptidno', name: 'pe_apptidno', hidden: true},
                    { label: 'Handphone', name: 'pe_telhp' , width: 10},
                    { label: 'Episode No.', name: 'pe_episno', width: 10 },
                    { label: 'Birth Date.', name: 'p_DOB', width: 10 },
                    { label: 'Sex', name: 'p_sex', width: 5 },
                    { label: 'Info &nbsp;&nbsp;&nbsp;&nbsp; Type', name: 'action', width: 12, formatter:formataction , classes: 'td_nowhitespace'},
                    { label: 'idno', name: 'pe_idno', hidden:true},
                ],
                autowidth: true,
                multiSort: true,
                viewrecords: true,
                loadonce: false,
                viewrecords: false,
                width: 900,
                height: 200, 
                rowNum: 30,
                pager: "#jqGridPager_preepis",
                onSelectRow:function(rowid, selected){
                },
                beforeProcessing: function(data, status, xhr){
                    data.rows.forEach(function(e,i,a){
                        e.action=e.pe_mrn+','+e.pe_episno+','+e.pe_apptidno;
                    })
                },
                loadComplete: function(data){
                    $("#jqGrid_preepis .preepis_bio").on('click',{data:this},preepis_bio_click);
                    $("#jqGrid_preepis .preepis_epis").on('click',{data:this},preepis_epis_click);

                },
                ondblClickRow: function(rowid, iRow, iCol, e){
                },
                gridComplete: function () {
                },
            });

            addParamField('#jqGrid_preepis', false, this.urlParam_preepis);
            var self = this;
            $("#tabpreepis").on("shown.bs.collapse", function(){
                $("#jqGrid_preepis").jqGrid ('setGridWidth', Math.floor($("#jqGrid_preepis_c")[0].offsetWidth-$("#jqGrid_preepis_c")[0].offsetLeft-0));
                refreshGrid("#jqGrid_preepis", self.urlParam_preepis);
            });
            $("#jqGridDoctorNote_panel").on("show.bs.collapse", function(){
                $("#jqGridAddNotes").jqGrid ('setGridWidth', Math.floor($("#jqGridDoctorNote_c")[0].offsetWidth-$("#jqGridDoctorNote_c")[0].offsetLeft-228));
            });


            function formataction(cellvalue, options, rowObject){
                let mrn = cellvalue.split(',')[0];
                let episno = cellvalue.split(',')[1];
                let apptidno = cellvalue.split(',')[2];
                let idno = rowObject.pe_idno;

                let return_val = "";

                if(mrn != "00000"){
                    return_val+=`
                        <button title="Edit" type="button" class="btn btn-xs btn-warning btn-md command-edit preepis_bio" data-mrn=`+mrn+` data-idno=`+idno+` data-episno=`+episno+` data-apptidno=`+apptidno+`><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
                        &nbsp;&nbsp;
                    `
                }else{
                    return_val+=`
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    `
                }

                if(apptidno == 'null' || apptidno == ''){
                    return_val+=`
                        <button title="Episode" type="button" class="btn btn-xs btn-danger btn-md command-episode preepis_epis" data-mrn=`+mrn+` data-idno=`+idno+` data-episno=`+episno+` data-apptidno=`+apptidno+`><b>&nbsp;WIN&nbsp;</b></button>
                    `;
                }else{
                   return_val+=`
                        <button title="Episode" type="button" class="btn btn-xs btn-danger btn-md command-episode preepis_epis" data-mrn=`+mrn+` data-idno=`+idno+` data-episno=`+episno+` data-apptidno=`+apptidno+`><b>APPT</b></button>
                    `; 
                }

                if(mrn == "00000"){
                    return_val+=`
                        &nbsp;&nbsp;<button title="Add" type="button" class="btn btn-xs btn-warning btn-md command-edit preepis_bio" data-mrn=`+mrn+` data-idno=`+idno+` data-episno=`+episno+` data-apptidno=`+apptidno+`>&nbsp;R&nbsp;</button>
                    `;
                }

                return return_val;

                
                // <button title="OTC Episode" type="button" class="btn btn-xs btn-danger btn-md command-otc-episode preepis_otc" data-mrn=`+mrn+` data-episno=`+episno+`><b>DC</b></button>
            }

            function preepis_epis_click(event){
                var button = $(event.currentTarget);
                var mrn = button.data('mrn');
                var episno = button.data('episno');
                var apptidno = button.data('apptidno');
                $("#apptidno_epis").val(apptidno);
                $("#episode_oper").val('add');

                var param={
                    action:'get_value_default',
                    field:"*",
                    table_name:['hisdb.pre_episode AS pe','hisdb.pat_mast AS p'],
                    join_type : ['LEFT JOIN'],
                    join_onCol : ['pe.mrn'],
                    join_onVal : ['p.mrn'],
                    // fixPost:'true',
                    filterCol:['pe.compcode','pe.mrn','pe.episno'],
                    filterVal:['session.compcode',mrn,episno],
                };

                $.get( "/util/get_value_default?"+$.param(param), function( data ) {

                },'json').done(function(data) {

                    if(data.rows.length > 0){

                        $('#editEpisode').modal({backdrop: "static"});

                        var episdata = data.rows[0];
                        $('#mrn_episode').val(episdata.MRN);
                        $('#txt_epis_name').text(episdata.Name);
                        $('#txt_epis_mrn').text(('0000000' + episdata.MRN).slice(-7));

                        $('#txt_epis_date').val(moment().format('DD/MM/YYYY'));
                        $('#txt_epis_time').val(moment().format('hh:mm:ss'));
                        $('#txt_epis_no').val(episdata.episno);
                        $('#txt_epis_type').val($("#epistycode").val());
                        $('#btn_epis_payer').data('mrn',episdata.MRN);
                        if(episdata.Sex == "M"){
                            $('#cmb_epis_pregnancy').val('Non-Pregnant');
                            $('#cmb_epis_pregnancy').prop("disabled", true);
                        }else{
                            $('#cmb_epis_pregnancy').prop("disabled", false);
                        }

                        $('#hid_epis_case').val(episdata.case_code);
                        $('#hid_epis_doctor').val(episdata.admdoctor);
                        
                        $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().hide();

                        // epis_desc_show.write_desc();

                    }else{
                        alert('MRN not found')
                    }


                }).error(function(data){

                });

            }

            function preepis_bio_click(event){
                var button = $(event.currentTarget);
                var mrn = button.data('mrn');
                var apptidno = button.data('apptidno');
                var idno = button.data('idno');

                if(mrn != '00000'){
                    var param={
                        action:'get_value_default',
                        url:'/util/get_value_default',
                        field:"*",
                        table_name:'hisdb.pat_mast',
                        filterCol:['compcode','mrn'],filterVal:['session.compcode',mrn]
                    };
                }else{
                    var param={
                        action:'get_preepis_data',
                        url:'/get_preepis_data',
                        apptidno:apptidno,
                        idno:idno
                    };
                }

                

                $.get( param.url+"?"+$.param(param), function( data ) {

                },'json').done(function(data) {

                    if(data.rows.length > 0){

                        var episdata = data.rows[0];

                        populate_patient(episdata);
                        desc_show.write_desc();
                        $('#mdl_patient_info').modal({backdrop: "static"});

                        if(episdata.idno){//kalau dah ada mrn
                            $("#btn_register_patient").data("oper","edit");
                            $("#btn_register_patient").data('idno',episdata.idno);
                        }else{//kalau belum ada mrn
                            $('#PatClass').val(getUrlParameter('PatClass'));
                            $("#btn_register_patient").data("oper","add");
                            $("#func_after_pat").val('save_preepis');
                            $("#apptidno_pat").val(apptidno);
                        }

                    }else{
                        alert('MRN not found')
                    }


                }).error(function(data){

                });
            }
        }
    }

    $('#mdl_accomodation').on('show.bs.modal', function () {
        $(this).css('z-index',120);
        var accomodation_selecter_ = new accomodation_selecter();
    });


});

// if($('#epistycode').val() == 'OP'){
//     var epis_desc_show = new loading_desc_epis([
//         {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
//         {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
//         {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
//         {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
//         {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
//         {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
//         {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'},
//         {code:'#hid_newgl_occupcode',desc:'#txt_newgl_occupcode',id:'newgl_occupcode'},
//         {code:'#hid_newgl_relatecode',desc:'#txt_newgl_relatecode',id:'newgl_relatecode'}
//     ]);
// }else if($('#epistycode').val() == 'IP'){
//     var epis_desc_show = new loading_desc_epis([
//         {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
//         {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
//         {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
//         {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
//         {code:'#hid_epis_bed',desc:'#txt_epis_bed',id:'epis_bed'},//ada bed pada IP
//         {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
//         {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
//         {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'},
//         {code:'#hid_newgl_occupcode',desc:'#txt_newgl_occupcode',id:'newgl_occupcode'},
//         {code:'#hid_newgl_relatecode',desc:'#txt_newgl_relatecode',id:'newgl_relatecode'},
//         {code:'#hid_newgl_corpcomp',desc:'#txt_newgl_corpcomp',id:'newgl_corpcomp'}
//         // {code:'',desc:'',id:'bed_dept'},
//         // {code:'',desc:'',id:'bed_ward'}
//     ]);
// }

// epis_desc_show.load_desc();