$(document).ready(function() {
    $('#frm_patient_info_MR').validate({
        rules: {
            telh: {
              require_from_group: [1, ".phone-group"]
            },
            telhp: {
              require_from_group: [1, ".phone-group"]
            },
            Newic: {
              require_from_group: [1, ".ic-group"],
              rangelength: [12,12]
            },
            Oldic: {
              require_from_group: [1, ".ic-group"]
            },
            idnumber: {
              require_from_group: [1, ".ic-group"]
            },
        },
        errorPlacement: function(error, element) {
            var elem_dialog = ['txt_ID_Type_MR','txt_RaceCode_MR','txt_Religion_MR','txt_LanguageCode_MR','txt_pat_citizen_MR','txt_pat_area_MR','txt_payer_company_MR','txt_pat_occupation_MR','DOB'];
            if(element.attr("name") == 'Newic'){
                return false;
            }else if(elem_dialog.includes(element.attr("name")) ){
                error.insertAfter( element.closest(".input-group") );
            }else{
                error.insertAfter(element);
            }
        }
    }); // patient form validation

    $('#txt_pat_newic_MR').blur(function(){

        if($(this).val() != ''){
            let newrplc = $(this).val().replace(/-/g, "");
            $(this).val(newrplc);//untuk buang hyphen lepas tulis i/c

            if(newrplc.length == 12){
                let first6dig = $(this).val().substring(0,6);
                let dobval = turntoappropriatetime(first6dig)
                $("#txt_pat_dob_MR").val(dobval);//utk auto letak dob lepas tulis i/c
                $('#txt_pat_age_MR').val(gettheage(dobval));

                let lastdigit = newrplc.slice(-1);
                if(lastdigit % 2 == 0){
                    $('#cmb_pat_sex_MR').val('F');
                }else{
                    $('#cmb_pat_sex_MR').val('M');
                }
            }

            if($("#btn_register_patient").data('oper') == 'add'){
                check_existing_patient();
            }
        }
    });

    $("#txt_pat_dob_MR").blur(function(){
       $('#txt_pat_age_MR').val(gettheage($(this).val()));
       $("#txt_pat_dob_MR-error").detach();
    });

    $('#mdl_patient_info_MR').on('shown.bs.modal', function (e) {
        parent_close_disabled(true);
        // $('#txt_pat_newic_MR').blur();
        if($("#btn_register_patient").data("oper") == 'add'){
            $('#txt_ID_Type_MR').val('OWN IC');
            $('#hid_ID_Type_MR').val('O');
            $('#cmb_pat_category_MR').val('LOCAL');
            $('#txt_pat_episno_MR').val(0);
            $('#last_visit_date_MR').val(moment().format('DD/MM/YYYY'));
            $('#first_visit_date_MR').val(moment().format('DD/MM/YYYY'));
        }
    });

    $('#mdl_patient_info_MR').on('hidden.bs.modal', function (e) {
        $('#frm_patient_info_MR').find("label.error").detach();
        $("#frm_patient_info_MR").find('.error').removeClass("error");
        $("#frm_patient_info_MR").find('.valid').removeClass("valid");
        $("#frm_patient_info_MR").find('.has-success').removeClass("has-success");
        $("#frm_patient_info_MR").find('.has-error').removeClass("has-error");
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
        $("#tabNok_emr_MR,#tabCorp_MR,#tabPatrec_MR,#tabNok_pat_MR").collapse("hide");
        $("img#photobase64").attr('src',$("img#photobase64").attr("defaultsrc"));
        parent_close_disabled(false);
    });

    // $("#biodata_but").click(function() {
    //     $('#mdl_patient_info_MR').modal({backdrop: "static"});
    //     $("#btn_register_patient").data("oper","add");
    //     $('#episno').val('1');
    // });
    
    $('#btn_register_patient').on('click',default_click_register);

    $('#btn_reg_proceed').on('click',default_click_proceed);

});

function turntoappropriatetime(moments){
    let year = moments.substring(0,2);
    let month = moments.substring(2,4);
    let day = moments.substring(4,6);
    let yearnow = String(moment().get('year')).substring(2,4);
    if(parseInt(yearnow)<=parseInt(year)){
        year = "19".concat(year);
    }else{
        year = "20".concat(year);
    }
    return moment(year+month+day, "YYYYMMDD").format("YYYY-MM-DD");
}

function loadlist(selobj,url,nameattr,descattr){
    $(selobj).empty();
    $.getJSON(url,{},function(data)
    {
        $.each(data.data, function(i,obj)
        {   
            if(url == `pat_mast_MR/get_entry?action=get_patient_idtype`){
                
            }
            $(selobj).append(
                $('<option></option>')
                    .val(obj['code'])
                    .html(obj['code'] + ' - ' + obj['description'] )
            );
        });
    });
}

function loadlistEmpty(selobj,url,nameattr,descattr){
    $(selobj).empty();
    $.getJSON(url,{},function(data)
    {   
        $.each(data.data, function(i,obj)
        {
            $(selobj).append(
                $('<option></option>')
                    .val(obj[nameattr])
                    // .html(obj[descattr]));
                    .html(obj[nameattr] + ' - ' + obj[descattr] ));
        });
    });
}

function populatecombo1(){
    var urlType = 'pat_mast_MR/get_entry?action=get_patient_idtype';
    loadlist($('select#cmb_pat_idtype_MR').get(0),urlType,'Code','Description');

    var urlsex = 'pat_mast_MR/get_entry?action=get_patient_sex';
    loadlist($('select#cmb_pat_sex_MR').get(0),urlsex,'code','description');

    var urlrace = 'pat_mast_MR/get_entry?action=get_patient_race';
    loadlist($('select#cmb_pat_racecode_MR').get(0),urlrace,'Code','Description');

    var urlreligion = 'pat_mast_MR/get_entry?action=get_patient_religioncode';
    loadlist($('select#cmb_pat_religion_MR').get(0),urlreligion,'Code','Description');

    var urllanguagecode = 'pat_mast_MR/get_entry?action=get_patient_language';
    loadlist($('select#cmb_pat_langcode').get(0),urllanguagecode,'Code','Description');

    var urlRel = 'pat_mast_MR/get_entry?action=get_patient_relationship';
    loadlist($('select#cmb_grtr_relation').get(0),urlRel,'relationshipcode','description');
}

function gettheage(dob){
    if(dob != ''){
        var day = new Date();
        var dob = new Date(dob);
        var age_val =  day.getFullYear() - dob.getFullYear();
        if(isNaN(age_val))return null;
        return age_val;
    }
    return null;
}

function default_click_register(){
    if($('#frm_patient_info_MR').valid()){
        if($(this).data('oper') == 'add'){
            save_patient('add');
        }else{
            save_patient($(this).data('oper'),$(this).data('idno'));
        }
    }
}

function default_click_proceed(){
    var checkedbox = $("#tbl_existing_record input[type='checkbox']:checked");
    if(checkedbox.closest("td").next().length>0){
        let mrn = checkedbox.data("mrn");
        let idno = checkedbox.data("idno");
        $("#btn_register_patient").data('oper','edit');
        $("#btn_register_patient").data('idno',idno);
        populate_data_from_mrn(mrn,"#frm_patient_info_MR");
    }else{
        $('#mdl_existing_record').modal('hide');
    }
}

function save_patient(oper,idno,mrn="nothing"){
    var saveParam={
        action:'save_patient',
        oper:oper,
        table_name:'hisdb.pat_mast',
        table_id:'idno',
        sysparam:null
    },_token = $('#csrf_token').val();

    if(oper=='add'){
        saveParam.sysparam = {source:$('#PatClass_MR').val(),trantype:'MRN',useOn:'MRN'};
    }
    var postobj = (mrn!="nothing")?
                {_token:_token,func_after_pat:$('#func_after_pat').val(),idno:idno,MRN:mrn}:
                {_token:_token,func_after_pat:$('#func_after_pat').val(),idno:idno};
                //kalu ada mrn, maksudnya dia dari merging duplicate

    // var image = ($("img#photobase64").attr('src').startsWith('data'))?
    //             {PatientImage:$("img#photobase64").attr('src')}:
    //             {PatientImage:null}

    var image = {PatientImage:null,field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Staffid','OccupCode','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno','first_visit_date','last_visit_date','loginid','pat_category','MRFolder','bloodgrp','NewMrn','iPesakit'],};

    $.post( "./pat_mast_MR/save_patient?"+$.param(saveParam), $("#frm_patient_info_MR").serialize()+'&'+$.param(postobj)+'&'+$.param(image) , function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
        
        $("#load_from_addupd").data('info','true');
        $("#load_from_addupd").data('oper',oper);
        $("#lastMrn").val(data.lastMrn);
        $("#lastidno").val(data.lastidno);

        if($('#func_after_pat').val() != ''){
            preepisode.refreshGrid();
        }

        $('#mdl_patient_info_MR').modal('hide');
        $('#mdl_existing_record').modal('hide');
        $("#grid-command-buttons").bootgrid('reload');
        // if(oper == 'edit'){

        //     $("#grid-command-buttons tr").removeClass( "justbc" );
        //     $("#grid-command-buttons tr[data-row-id='"+$('#lastrowid').val()+"']").addClass( "justbc" );
        //     // $("#grid-command-buttons").bootgrid('select',[]);
        // }
    });
}
	
function check_existing_patient(callback,obj_callback){
	var patname = $("#txt_pat_name_MR").val();
	var patdob = moment($("#txt_pat_dob_MR").val(), 'DD/MM/Y').format('Y-MM-DD');
	var patnewic = $("#txt_pat_newic_MR").val();
	var patoldic = $("#txt_pat_oldic_MR").val();
	var patidno = $("#txt_pat_idno_MR").val();

    var param={
        action:'get_value_default',
        field:['MRN as merge','MRN','Name','Newic','Oldic','idnumber','DOB','idno'],
        table_name:'hisdb.pat_mast',
        table_id:'_none',
        filterCol:['compcode','Newic'],
        filterVal:['session.compcode',patnewic],
        // searchCol:['Newic'],searchVal:['patnewic+'%']
    };

    $.get( "./util/get_value_default?"+$.param(param), function( data ) {

    },'json').done(function(data) {
        if(data.rows.length > 0){
            let current_pat = {
                merge: null,
                MRN: "N/A",
                Name: $("#txt_pat_name_MR").val(),
                Newic: $("#txt_pat_newic_MR").val(),
                Oldic: $("#txt_pat_oldic_MR").val(),
                idnumber: $("#txt_pat_idno_MR").val(),
                DOB: $("#txt_pat_dob_MR").val(),
                idno: "N/A"
            };
            data.rows.unshift(current_pat);
            tbl_exist_rec.clear();
            tbl_exist_rec.rows.add(data.rows).draw();
            $('#mdl_existing_record').modal('show');
        }else{
            // if(obj_callback.action=='default'){
            //     callback(obj_callback.param);
            // }else if(obj_callback.action=='apptrsc'){
            //     let oper = obj_callback.param[0];
            //     let apptbook_idno = obj_callback.param[3];
            //     callback(oper,null,null,apptbook_idno);
            // }
            
        }
    }).fail(function(data){
        alert('there is an error on check existing patient!');
    });
}

var tbl_exist_rec = $('#tbl_existing_record').DataTable( {
    "lengthChange": false,"info": false,"pagingType" : "numbers", "ordering": false,
    "search": {"smart": true, },
    "columns": [
                {'data' : 'merge'},
                {'data' : 'MRN'}, 
                {'data' : 'Name' },
                {'data' : 'Newic'}, 
                {'data' : 'Oldic' },
                {'data' : 'idnumber'}, 
                {'data' : 'DOB' },
                {'data' : "idno"},
               ],
    columnDefs: [{
        targets:   0,
        'render': function (data, type, full, meta){
            if(data==null){
                return "<small>Merge this</br>with?</small>"
            }else{
                return '<input type="checkbox" name="chk_' + data + '" id="chk_' + data + '" data-mrn="' + data + '" data-idno="' + full.idno + '">';
            }
         }
    }],
    select: {
        style:    'os',
        selector: 'td:first-child'
    },
});

desc_show = new loading_desc_bio([
    {code:'#hid_pat_citizen_MR',desc:'#txt_pat_citizen_MR',id:'citizencode'},
    {code:'#hid_pat_area_MR',desc:'#txt_pat_area_MR',id:'areacode'},
    {code:'#hid_pat_title_MR',desc:'#txt_pat_title_MR',id:'titlecode'},
    {code:'#hid_ID_Type_MR',desc:'#txt_ID_Type_MR',id:'idtype'},
    {code:'#hid_LanguageCode_MR',desc:'#txt_LanguageCode_MR',id:'language'},
    {code:'#hid_RaceCode_MR',desc:'#txt_RaceCode_MR',id:'race'},
    {code:'#hid_Religion_MR',desc:'#txt_Religion_MR',id:'religioncode'},
    {code:'#hid_pat_occupation_MR',desc:'#txt_pat_occupation_MR',id:'occupation'},
    {code:'#hid_payer_company_MR',desc:'#txt_payer_company_MR',id:'company'}
]);
desc_show.load_desc();

function loading_desc_bio(obj){
    this.code_fields=obj;
    this.titlecode_MR={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.citizencode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.areacode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.idtype={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.language={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.race={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.religioncode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.occupation={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.company={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.load_desc = function(){
        load_for_desc(this,'titlecode_MR','pat_mast_MR/get_entry?action=get_patient_title_MR');
        load_for_desc(this,'citizencode','pat_mast_MR/get_entry?action=get_patient_citizen');
        load_for_desc(this,'areacode','pat_mast_MR/get_entry?action=get_patient_areacode');
        load_for_desc(this,'idtype','pat_mast_MR/get_entry?action=get_patient_idtype');
        load_for_desc(this,'language','pat_mast_MR/get_entry?action=get_patient_language');
        load_for_desc(this,'race','pat_mast_MR/get_entry?action=get_patient_race');
        load_for_desc(this,'religioncode','pat_mast_MR/get_entry?action=get_patient_religioncode');
        load_for_desc(this,'occupation','pat_mast_MR/get_entry?action=get_patient_occupation');
        load_for_desc(this,'company','pat_mast_MR/get_entry?action=get_all_company');
    }

    this.load_sp_desc = function(code,url){
        load_for_desc(this,code,url,true);
    }

    function load_for_desc(selobj,id,url,reload=false){

        let storage_name = 'fastload_bio_'+id;
        let storage_obj = localStorage.getItem(storage_name);

        if(!storage_obj){

            $.ajaxSetup({async: false});
            $.get( url, function( data ) {
                    
            },'json').done(function(data) {
                if(!$.isEmptyObject(data)){

                    selobj[id].data = data.data;

                    let desc = data.data;
                    let now = moment();

                    var json = JSON.stringify({
                        'description':desc,
                        'timestamp': now
                    });

                    localStorage.setItem(storage_name,json);
                }
            });

        }else{

            let obj_stored = {
                'json':JSON.parse(storage_obj),
            }

            selobj[id].data = obj_stored.json.description

            //remove storage after 7 days
            let moment_stored = obj_stored.json.timestamp;
            if(moment().diff(moment(moment_stored),'days') > 7){
                localStorage.removeItem(storage_name);
            }

        }

        if(reload){
            selobj.write_desc();
        }
    }

    this.write_desc = function(){
        self=this;
        obj.forEach(function(elem){
            if($(elem.code).val().trim() != ""){
                $(elem.desc).val(self.get_desc($(elem.code).val(),elem.id));
            }
        });
    }

    this.get_desc = function(search_code,id){
        let code_ = this[id].code;
        let desc_ = this[id].desc;
        let retdata="N/A";

        retdata = this[id].data.find(function(obj){
            return obj[code_] == search_code;
        });

        return (retdata == undefined)? "N/A" : retdata[desc_];
    }
}

function populate_patient(rowdata) {

    $('#hid_pat_title_MR').val(rowdata.TitleCode);
    var first_visit = new Date(rowdata['first_visit_date']);
    var first_visit_val = (isNaN(first_visit.getFullYear()) ? 'n/a' : moment(first_visit).format('DD/MM/YYYY'));
    $('#first_visit_date_MR').val(first_visit_val);
    var last_visit = new Date(rowdata['last_visit_date']);
    var last_visit_val = (isNaN(last_visit.getFullYear()) ? 'n/a' : moment(last_visit).format('DD/MM/YYYY'));
    $('#last_visit_date_MR').val(last_visit_val);
    $('#episno').val(rowdata.Episno); // dlm modal Patient   
    
    $('#txt_pat_name_MR').val(rowdata.Name);
    $('#txt_pat_newic_MR').val(rowdata.Newic);
    $('#txt_pat_oldic_MR').val(rowdata.Oldic);
    $('#cmb_pat_idtype_MR').val(rowdata['ID_Type_MR']);
    $('#txt_pat_idno_MR').val(rowdata.idnumber);

    //corporate info
    $('#hid_payer_company_MR').val(rowdata.CorpComp);
    $('#txt_payer_staffid_MR').val(rowdata.Staffid);
    $('#hid_pat_occupation_MR').val(rowdata.OccupCode);
    $('#txt_payer_email_official_MR').val(rowdata.Email_official);
    
    $('#txt_pat_mrn_MR').val(('0000000' + rowdata.MRN).slice(-7));
    $('#txt_pat_episno_MR').val(rowdata.Episno);
    $('#pat_mrn_MR').val(rowdata.MRN);
    $('#hid_LanguageCode_MR').val(rowdata.LanguageCode);
    $('#hid_RaceCode_MR').val(rowdata.RaceCode);
    $('#hid_Religion_MR').val(rowdata.Religion);
    $('#hid_ID_Type_MR').val(rowdata.ID_Type);
    $('#txt_pat_dob_MR').val(moment(rowdata.DOB).format('Y-MM-DD'));
    $('#txt_pat_age_MR').val(gettheage(rowdata.DOB));
    $('#txt_pat_telh_MR').val(rowdata.telh);
    $('#txt_pat_telhp_MR').val(rowdata.telhp);
    $('#txt_pat_email_MR').val(rowdata.Email);
    $('#hid_pat_area_MR').val(rowdata.AreaCode);
    $('#cmb_pat_sex_MR').val(rowdata.Sex); // dlm modal Patient                    
    $('#hid_pat_citizen_MR').val(rowdata.Citizencode);
    $('#cmb_pat_racecode_MR').val(rowdata.RaceCode);
    $('#cmb_pat_religion_MR').val(rowdata.Religion);
    $('#maritalcode').val(rowdata.MaritalCode);
    $('#cmb_pat_langcode').val(rowdata.LanguageCode);
    $('#txt_pat_curradd1_MR').val(rowdata.Address1);
    $('#txt_pat_curradd2_MR').val(rowdata.Address2);
    $('#txt_pat_curradd3_MR').val(rowdata.Address3);
    $('#txt_pat_offadd1_MR').val(rowdata.Offadd1);
    $('#txt_pat_offadd2_MR').val(rowdata.Offadd2);
    $('#txt_pat_offadd3_MR').val(rowdata.Offadd3);
    $('#txt_pat_padd1_MR').val(rowdata.pAdd1);
    $('#txt_pat_padd2_MR').val(rowdata.pAdd2);
    $('#txt_pat_padd3_MR').val(rowdata.pAdd3);
    $('#txt_pat_currpostcode_MR').val(rowdata.Postcode);
    $('#txt_pat_offpostcode_MR').val(rowdata.OffPostcode);
    $('#txt_pat_ppostcode_MR').val(rowdata.pPostCode);
    $('#active').val(rowdata.Active);
    $('#confidential').val(rowdata.Confidential);
    $('#mrfolder').val(rowdata.MRFolder);
    $('#patientcat').val(rowdata.PatientCat);
    $('#newmrn').val(rowdata.NewMrn);
    $('#blood_grp').val(rowdata.bloodgrp);
    $('#cmb_pat_category_MR').val(rowdata.pat_category);
    $('#cmb_pat_active_MR').val(rowdata.Active);
    $('#cmb_pat_Confidential_MR').val(rowdata.Confidential);
    $('#cmb_pat_MRFolder_MR').val(rowdata.MRFolder);
    $('#txt_bloodgroup_MR').val(rowdata.bloodgrp);
    $('#txt_newmrn_MR').val(rowdata.NewMrn);
    $('#txt_pat_iPesakit_MR').val(rowdata.iPesakit);
    if(rowdata.PatientImage != null && rowdata.PatientImage.startsWith('data')){
        $("img#photobase64").attr('src',rowdata.PatientImage);
    }else{
        $("img#photobase64").attr('src',$("img#photobase64").attr("defaultsrc"));
    }
    // $('#name').val(rowdata.name);

    //populate_payer_guarantee_info(d); tgk balik nanti

    $("#toggle_tabNok_emr_MR").parent().show();
    if(rowdata.Episno == 0 || rowdata.Episno == null){
        $("#toggle_tabNok_pat_MR").parent().hide();
    }else{
        $("#toggle_tabNok_pat_MR").parent().show();
    }

    $("#toggle_tabNok_emr_MR").parent().show();
}

function populate_data_from_mrn(mrn,form){
    var param={
        action:'get_value_default',
        field:"*",
        table_name:'hisdb.pat_mast',
        table_id:'_none',
        filterCol:['compcode'],filterVal:['session.compcode'],
        searchCol:['mrn'],searchVal:[mrn]
    };

    $.get( "./util/get_value_default?"+$.param(param), function( data ) {

    },'json').done(function(data) {

        if(data.rows.length > 0){
            
            $.each(data.rows[0], function( index, value ) {
                var input=$(form+" [name='"+index+"']");

                if(input.is("[type=radio]")){
                    $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
                }else{
                    input.val(value);
                }
                desc_show.write_desc();
            });

        }else{
            alert('MRN not found');
        }

        $('#mdl_existing_record').modal('hide');

    }).fail(function(data){

    });
}

function mykad_check_existing_patient(callback){
    $("#btn_register_patient").data("oper_mykad","add");

    var patnewic = $("#txt_pat_newic_MR").val();
    var param={
        action:'get_value_default',
        field:"*",
        table_name:'hisdb.pat_mast',
        table_id:'_none',
        filterCol:['compcode','Newic'],filterVal:['session.compcode',patnewic]
    };

    $.get( "./util/get_value_default?"+$.param(param), function( data ) {

    },'json').done(function(data) {
        if(data.rows.length > 0){
            var form = '#frm_patient_info_MR';
            $("#btn_register_patient").data("oper_mykad","edit");
            $("#btn_register_patient").data('idno',data.rows[0].idno);
            $("#pat_mrn_MR").val(data.rows[0].MRN);
            $("#txt_pat_idno_MR").val(data.rows[0].idno);
            
            
            $.each(data.rows[0], function( index, value ) {
                var input=$(form+" [name='"+index+"']");
                
                if(input.val() != '' || input.val() != undefined){
                    if(input.is("[type=radio]")){
                        $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
                    }else{
                        input.val(value);
                    }
                }
            });
            desc_show.write_desc();

            if (callback !== undefined) {
                callback(data.rows[0]);
            }

        }

    }).fail(function(data){

    });
}
