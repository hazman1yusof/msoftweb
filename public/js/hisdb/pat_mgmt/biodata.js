
	$('#frm_patient_info').validate({
        rules: {
            telh: {
              require_from_group: [1, ".phone-group"]
            },
            telhp: {
              require_from_group: [1, ".phone-group"]
            },
            Newic: {
              require_from_group: [1, ".ic-group"]
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
            if( elem_dialog.includes(element.attr("name")) ){
                error.insertAfter( element.closest(".input-group") );
            }else{
                error.insertAfter(element)
            }
        }
    });	// patient form validation

    $('#txt_pat_newic').blur(function(){
        if($(this).val() != ''){
            let newrplc = $(this).val().replace(/-/g, "");
            $(this).val(newrplc);//untuk buang hyphen lepas tulis i/c
            let first6dig = $(this).val().substring(0,6);
            let dobval = turntoappropriatetime(first6dig)
            $("#txt_pat_dob").val(dobval);//utk auto letak dob lepas tulis i/c
            $('#txt_pat_age').val(gettheage(dobval));
        }
    })

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
            console.log(data);
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

    $("#txt_pat_dob").blur(function(){
       $('#txt_pat_age').val(gettheage($(this).val()));
       $("#txt_pat_dob-error").detach();
    })

    function gettheage(dob){
        if(dob != ''){
            var day = new Date();
            var dob = new Date(dob);
            var age_val =  day.getFullYear() - dob.getFullYear();
            return age_val;
        }
        return null;
    }

    $('#mdl_patient_info').on('hidden.bs.modal', function (e) {
        $('#frm_patient_info').find("label.error").detach();
        $("#frm_patient_info").find('.error').removeClass("error");
        $("#frm_patient_info").find('.valid').removeClass("valid");
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
        $("#tabNok_emr,#tabCorp,#tabPatrec,#tabNok_pat").collapse("hide");
    });

    $("#biodata_but").click(function() {
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","add");
        $('#episno').val('1');
    });

    function default_click_register(){
        if($('#frm_patient_info').valid()){
            if($(this).data('oper') == 'add'){
                check_existing_patient(save_patient,{action:'default',param:'add'});
            }else{
                save_patient($(this).data('oper'),$(this).data('idno'));
            }
        }
    }
	
	$('#btn_register_patient').on('click',default_click_register);

    function default_click_proceed(){
        var checkedbox = $("#tbl_existing_record input[type='checkbox']:checked");
        if(checkedbox.closest("td").next().length>0){
            let mrn = checkedbox.data("mrn");
            let idno = checkedbox.data("idno");
            save_patient('edit',idno,mrn);
        }else{
            save_patient('add');
        }
    }

    $('#btn_reg_proceed').on('click',default_click_proceed);

    function save_patient(oper,idno,mrn="nothing"){
        var saveParam={
            action:'save_patient',
            field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','OccupCode','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno','first_visit_date','last_visit_date','loginid','pat_category','Active','MRFolder','bloodgrp','NewMrn'],
            oper:oper,
            table_name:'hisdb.pat_mast',
            table_id:'idno',
            sysparam:null
        },_token = $('#csrf_token').val();

        if(oper=='add'){
            saveParam.sysparam = {source:'HIS',trantype:'MRN',useOn:'MRN'};
        }
        var postobj = (mrn!="nothing")?{_token:_token,idno:idno,MRN:mrn}:{_token:_token,idno:idno};//kalu ada mrn, maksudnya dia dari merging duplicate 

        $.post( "/pat_mast/save_patient?"+$.param(saveParam), $("#frm_patient_info").serialize()+'&'+$.param(postobj) , function( data ) {
            
        },'json').fail(function(data) {
            alert('there is an error');
        }).success(function(data){
            $('#mdl_patient_info').modal('hide');
            $('#mdl_existing_record').modal('hide');
            $("#grid-command-buttons").bootgrid('reload');
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
            filterCol:['compcode'],filterVal:['session.company'],
            searchCol:['Newic'],searchVal:['%'+patnewic+'%']
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
                if(obj_callback.action=='default'){
                    callback(obj_callback.param);
                }else if(obj_callback.action=='apptrsc'){
                    let oper = obj_callback.param[0];
                    let apptbook_idno = obj_callback.param[3];
                    callback(oper,null,null,apptbook_idno);
                }
                
            }
        }).error(function(data){
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

    var desc_show = new loading_desc_bio([
        {code:'#hid_pat_citizen',desc:'#txt_pat_citizen',id:'citizencode'},
        {code:'#hid_pat_area',desc:'#txt_pat_area',id:'areacode'},
        {code:'#hid_pat_title',desc:'#txt_pat_title',id:'titlecode'},
        {code:'#hid_ID_Type',desc:'#txt_ID_Type',id:'idtype'},
        {code:'#hid_LanguageCode',desc:'#txt_LanguageCode',id:'language'},
        {code:'#hid_RaceCode',desc:'#txt_RaceCode',id:'race'},
        {code:'#hid_Religion',desc:'#txt_Religion',id:'religioncode'}
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
        this.load_desc = function(){
            load_for_desc(this,'titlecode','pat_mast/get_entry?action=get_patient_title');
            load_for_desc(this,'citizencode','pat_mast/get_entry?action=get_patient_citizen');
            load_for_desc(this,'areacode','pat_mast/get_entry?action=get_patient_areacode');
            load_for_desc(this,'idtype','pat_mast/get_entry?action=get_patient_idtype');
            load_for_desc(this,'language','pat_mast/get_entry?action=get_patient_language');
            load_for_desc(this,'race','pat_mast/get_entry?action=get_patient_race');
            load_for_desc(this,'religioncode','pat_mast/get_entry?action=get_patient_religioncode');
        }

        function load_for_desc(selobj,id,url){

            let storage_name = 'fastload_bio_'+id;
            let storage_obj = localStorage.getItem(storage_name);


            if(!storage_obj){

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

        // $('#occupcode').val(rowdata.occupcode);
        $('#hid_pat_occupation').val(rowdata.OccupCode);
        
        $('#txt_pat_mrn').val(('0000000' + rowdata.MRN).slice(-7));
        $('#txt_pat_episno').val(rowdata.Episno);
        $('#pat_mrn').val(rowdata.MRN);
        $('#hid_LanguageCode').val(rowdata.LanguageCode);
        $('#hid_RaceCode').val(rowdata.RaceCode);
        $('#hid_Religion').val(rowdata.Religion);
        $('#hid_ID_Type').val(rowdata.ID_Type);
        $('#txt_pat_dob').val(rowdata.DOB);
        $('#txt_pat_age').val(gettheage(rowdata.DOB));
        $('#txt_pat_telh').val('0' +rowdata.telh);
        $('#txt_pat_telhp').val('0' +rowdata.telhp);
        $('#txt_pat_email').val(rowdata.Email);
        $('#hid_pat_area').val(rowdata.AreaCode);
        $('#cmb_pat_sex').val(rowdata.Sex); // dlm modal Patient                    
        $('#hid_pat_citizen').val(rowdata.Citizencode);
        $('#cmb_pat_racecode').val(rowdata.RaceCode);
        $('#cmb_pat_religion').val(rowdata.Religion);
        $('#maritalcode').val(rowdata.MaritalCode);
        $('#cmb_pat_langcode').val(rowdata.LanguageCode);
        $('#remarks').val(rowdata.Remarks);
        $('#relatecode').val(rowdata.RelateCode);
        $('#corpcomp').val(rowdata.CorpComp);
        $('#email_official').val(rowdata.Email_official);
        $('#childno').val(rowdata.Childno);
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
        // $('#name').val(rowdata.name);

        //populate_payer_guarantee_info(d); tgk balik nanti

        $("#toggle_tabNok_emr").parent().show();
        if(rowdata.Episno == 0 || rowdata.Episno == null){
            $("#toggle_tabNok_pat").parent().hide();
        }else{
            $("#toggle_tabNok_pat").parent().show();
        }
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
                alert('MRN not found')
            }

        }).error(function(data){

        });

    }
