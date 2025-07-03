$(document).ready(function() {
    $('#frm_patient_info').validate({
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
            var elem_dialog = ['txt_ID_Type','txt_RaceCode','txt_Religion','txt_LanguageCode','txt_pat_citizen','txt_pat_area','txt_payer_company','txt_pat_occupation','DOB'];
            if(element.attr("name") == 'Newic'){
                return false;
            }else if(elem_dialog.includes(element.attr("name")) ){
                error.insertAfter( element.closest(".input-group") );
            }else{
                error.insertAfter(element);
            }
        }
    }); // patient form validation

    $('#txt_pat_newic').blur(function(){

        if($(this).val() != ''){
            let newrplc = $(this).val().replace(/-/g, "");
            $(this).val(newrplc);//untuk buang hyphen lepas tulis i/c

            if(newrplc.length == 12){
                let first6dig = $(this).val().substring(0,6);
                let dobval = turntoappropriatetime(first6dig)
                $("#txt_pat_dob").val(dobval);//utk auto letak dob lepas tulis i/c
                $('#txt_pat_age').val(gettheage(dobval));

                let lastdigit = newrplc.slice(-1);
                if(lastdigit % 2 == 0){
                    $('#cmb_pat_sex').val('F');
                }else{
                    $('#cmb_pat_sex').val('M');
                }
            }

            if($("#btn_register_patient").data('oper') == 'add'){
                check_existing_patient();
            }
        }
    });

    $("#txt_pat_dob").blur(function(){
       $('#txt_pat_age').val(gettheage($(this).val()));
       $("#txt_pat_dob-error").detach();
    });

    $('#mdl_patient_info').on('shown.bs.modal', function (e) {
        parent_close_disabled(true);
        // $('#txt_pat_newic').blur();
        if($("#btn_register_patient").data("oper") == 'add'){
            $('#txt_ID_Type').val('OWN IC');
            $('#hid_ID_Type').val('O');
            $('#cmb_pat_category').val('LOCAL');
            $('#txt_pat_episno').val(0);
            $('#last_visit_date').val(moment().format('DD/MM/YYYY'));
            $('#first_visit_date').val(moment().format('DD/MM/YYYY'));
        }
    });

    $('#mdl_patient_info').on('hidden.bs.modal', function (e) {
        $('#frm_patient_info').find("label.error").detach();
        $("#frm_patient_info").find('.error').removeClass("error");
        $("#frm_patient_info").find('.valid').removeClass("valid");
        $("#frm_patient_info").find('.has-success').removeClass("has-success");
        $("#frm_patient_info").find('.has-error').removeClass("has-error");
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
        $("#tabNok_emr,#tabCorp,#tabPatrec,#tabNok_pat").collapse("hide");
        $("img#photobase64").attr('src',$("img#photobase64").attr("defaultsrc"));
        parent_close_disabled(false);
    });

    // $("#biodata_but").click(function() {
    //     $('#mdl_patient_info').modal({backdrop: "static"});
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
            if(url == `pat_mast/get_entry?action=get_patient_idtype`){
                
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
    var urlType = 'pat_mast/get_entry?action=get_patient_idtype';
    loadlist($('select#cmb_pat_idtype').get(0),urlType,'Code','Description');

    var urlsex = 'pat_mast/get_entry?action=get_patient_sex';
    loadlist($('select#cmb_pat_sex').get(0),urlsex,'code','description');

    var urlrace = 'pat_mast/get_entry?action=get_patient_race';
    loadlist($('select#cmb_pat_racecode').get(0),urlrace,'Code','Description');

    var urlreligion = 'pat_mast/get_entry?action=get_patient_religioncode';
    loadlist($('select#cmb_pat_religion').get(0),urlreligion,'Code','Description');

    var urllanguagecode = 'pat_mast/get_entry?action=get_patient_language';
    loadlist($('select#cmb_pat_langcode').get(0),urllanguagecode,'Code','Description');

    var urlRel = 'pat_mast/get_entry?action=get_patient_relationship';
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
    if($('#frm_patient_info').valid()){
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
        populate_data_from_mrn(mrn,"#frm_patient_info");
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
        saveParam.sysparam = {source:$('#PatClass').val(),trantype:'MRN',useOn:'MRN'};
    }
    var postobj = (mrn!="nothing")?
                {_token:_token,func_after_pat:$('#func_after_pat').val(),idno:idno,MRN:mrn}:
                {_token:_token,func_after_pat:$('#func_after_pat').val(),idno:idno};
                //kalu ada mrn, maksudnya dia dari merging duplicate

    // var image = ($("img#photobase64").attr('src').startsWith('data'))?
    //             {PatientImage:$("img#photobase64").attr('src')}:
    //             {PatientImage:null}

    var image = {PatientImage:null,field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Staffid','OccupCode','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno','first_visit_date','last_visit_date','loginid','pat_category','MRFolder','bloodgrp','NewMrn','iPesakit'],};

    $.post( "./pat_mast/save_patient?"+$.param(saveParam), $("#frm_patient_info").serialize()+'&'+$.param(postobj)+'&'+$.param(image) , function( data ) {
        
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

        $('#mdl_patient_info').modal('hide');
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
	var patname = $("#txt_pat_name").val();
	var patdob = moment($("#txt_pat_dob").val(), 'DD/MM/Y').format('Y-MM-DD');
	var patnewic = $("#txt_pat_newic").val();
	var patoldic = $("#txt_pat_oldic").val();
	var patidno = $("#txt_pat_idno").val();

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
                Name: $("#txt_pat_name").val(),
                Newic: $("#txt_pat_newic").val(),
                Oldic: $("#txt_pat_oldic").val(),
                idnumber: $("#txt_pat_idno").val(),
                DOB: $("#txt_pat_dob").val(),
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
    {code:'#hid_pat_citizen',desc:'#txt_pat_citizen',id:'citizencode'},
    {code:'#hid_pat_area',desc:'#txt_pat_area',id:'areacode'},
    {code:'#hid_pat_title',desc:'#txt_pat_title',id:'titlecode'},
    {code:'#hid_ID_Type',desc:'#txt_ID_Type',id:'idtype'},
    {code:'#hid_LanguageCode',desc:'#txt_LanguageCode',id:'language'},
    {code:'#hid_RaceCode',desc:'#txt_RaceCode',id:'race'},
    {code:'#hid_Religion',desc:'#txt_Religion',id:'religioncode'},
    {code:'#hid_pat_occupation',desc:'#txt_pat_occupation',id:'occupation'},
    {code:'#hid_payer_company',desc:'#txt_payer_company',id:'company'}
]);
desc_show.load_desc();

function loading_desc_bio(obj){
    this.code_fields=obj;
    this.titlecode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.citizencode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.areacode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.idtype={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.language={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.race={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.religioncode={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.occupation={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.company={code:'code',desc:'description'};//data simpan dekat dalam ni
    this.load_desc = function(){
        load_for_desc(this,'titlecode','pat_mast/get_entry?action=get_patient_title');
        load_for_desc(this,'citizencode','pat_mast/get_entry?action=get_patient_citizen');
        load_for_desc(this,'areacode','pat_mast/get_entry?action=get_patient_areacode');
        load_for_desc(this,'idtype','pat_mast/get_entry?action=get_patient_idtype');
        load_for_desc(this,'language','pat_mast/get_entry?action=get_patient_language');
        load_for_desc(this,'race','pat_mast/get_entry?action=get_patient_race');
        load_for_desc(this,'religioncode','pat_mast/get_entry?action=get_patient_religioncode');
        load_for_desc(this,'occupation','pat_mast/get_entry?action=get_patient_occupation');
        load_for_desc(this,'company','pat_mast/get_entry?action=get_all_company');
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

    $('#hid_pat_title').val(rowdata.TitleCode);
    var first_visit = new Date(rowdata['first_visit_date']);
    var first_visit_val = (isNaN(first_visit.getFullYear()) ? 'n/a' : moment(first_visit).format('DD/MM/YYYY'));
    $('#first_visit_date').val(first_visit_val);
    var last_visit = new Date(rowdata['last_visit_date']);
    var last_visit_val = (isNaN(last_visit.getFullYear()) ? 'n/a' : moment(last_visit).format('DD/MM/YYYY'));
    $('#last_visit_date').val(last_visit_val);
    $('#episno').val(rowdata.Episno); // dlm modal Patient   
    
    $('#txt_pat_name').val(rowdata.Name);
    $('#txt_pat_newic').val(rowdata.Newic);
    $('#txt_pat_oldic').val(rowdata.Oldic);
    $('#cmb_pat_idtype').val(rowdata['ID_Type']);
    $('#txt_pat_idno').val(rowdata.idnumber);

    //corporate info
    $('#hid_payer_company').val(rowdata.CorpComp);
    $('#txt_payer_staffid').val(rowdata.Staffid);
    $('#hid_pat_occupation').val(rowdata.OccupCode);
    $('#txt_payer_email_official').val(rowdata.Email_official);
    
    $('#txt_pat_mrn').val(('0000000' + rowdata.MRN).slice(-7));
    $('#txt_pat_episno').val(rowdata.Episno);
    $('#pat_mrn').val(rowdata.MRN);
    $('#hid_LanguageCode').val(rowdata.LanguageCode);
    $('#hid_RaceCode').val(rowdata.RaceCode);
    $('#hid_Religion').val(rowdata.Religion);
    $('#hid_ID_Type').val(rowdata.ID_Type);
    $('#txt_pat_dob').val(rowdata.DOB);
    $('#txt_pat_age').val(gettheage(rowdata.DOB));
    $('#txt_pat_telh').val(rowdata.telh);
    $('#txt_pat_telhp').val(rowdata.telhp);
    $('#txt_pat_email').val(rowdata.Email);
    $('#hid_pat_area').val(rowdata.AreaCode);
    $('#cmb_pat_sex').val(rowdata.Sex); // dlm modal Patient                    
    $('#hid_pat_citizen').val(rowdata.Citizencode);
    $('#cmb_pat_racecode').val(rowdata.RaceCode);
    $('#cmb_pat_religion').val(rowdata.Religion);
    $('#maritalcode').val(rowdata.MaritalCode);
    $('#cmb_pat_langcode').val(rowdata.LanguageCode);
    $('#txt_pat_curradd1').val(rowdata.Address1);
    $('#txt_pat_curradd2').val(rowdata.Address2);
    $('#txt_pat_curradd3').val(rowdata.Address3);
    $('#txt_pat_offadd1').val(rowdata.Offadd1);
    $('#txt_pat_offadd2').val(rowdata.Offadd2);
    $('#txt_pat_offadd3').val(rowdata.Offadd3);
    $('#txt_pat_padd1').val(rowdata.pAdd1);
    $('#txt_pat_padd2').val(rowdata.pAdd2);
    $('#txt_pat_padd3').val(rowdata.pAdd3);
    $('#txt_pat_currpostcode').val(rowdata.Postcode);
    $('#txt_pat_offpostcode').val(rowdata.OffPostcode);
    $('#txt_pat_ppostcode').val(rowdata.pPostCode);
    $('#active').val(rowdata.Active);
    $('#confidential').val(rowdata.Confidential);
    $('#mrfolder').val(rowdata.MRFolder);
    $('#patientcat').val(rowdata.PatientCat);
    $('#newmrn').val(rowdata.NewMrn);
    $('#blood_grp').val(rowdata.bloodgrp);
    $('#cmb_pat_category').val(rowdata.pat_category);
    $('#cmb_pat_active').val(rowdata.Active);
    $('#cmb_pat_Confidential').val(rowdata.Confidential);
    $('#cmb_pat_MRFolder').val(rowdata.MRFolder);
    $('#txt_bloodgroup').val(rowdata.bloodgrp);
    $('#txt_newmrn').val(rowdata.NewMrn);
    $('#txt_pat_iPesakit').val(rowdata.iPesakit);
    if(rowdata.PatientImage != null && rowdata.PatientImage.startsWith('data')){
        $("img#photobase64").attr('src',rowdata.PatientImage);
    }else{
        $("img#photobase64").attr('src',$("img#photobase64").attr("defaultsrc"));
    }
    // $('#name').val(rowdata.name);

    //populate_payer_guarantee_info(d); tgk balik nanti

    $("#toggle_tabNok_emr").parent().show();
    if(rowdata.Episno == 0 || rowdata.Episno == null){
        $("#toggle_tabNok_pat").parent().hide();
    }else{
        $("#toggle_tabNok_pat").parent().show();
    }

    $("#toggle_tabNok_emr").parent().show();
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

    var patnewic = $("#txt_pat_newic").val();
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
            var form = '#frm_patient_info';
            $("#btn_register_patient").data("oper_mykad","edit");
            $("#btn_register_patient").data('idno',data.rows[0].idno);
            $("#pat_mrn").val(data.rows[0].MRN);
            $("#txt_pat_idno").val(data.rows[0].idno);
            
            
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
