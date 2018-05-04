
	$('#frm_patient_info').validate({
        rules: {
            telh: {
              require_from_group: [1, ".phone-group"]
            },
            telhp: {
              require_from_group: [1, ".phone-group"]
            }
        }
    });	// patient form validation
    function destoryallerror(){
        $('#frm_patient_info').find("label.error").detach();
    }//utk buang label error lepas close dialog modal

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

    function populatecombo1(){
        var urlType = 'pat_mast/get_entry?action=get_patient_idtype';
        loadlist($('select#cmb_pat_idtype').get(0),urlType,'Code','Description');

        var urloccupation = 'pat_mast/get_entry?action=get_patient_occupation';
        loadlist($('select#occupcode').get(0),urloccupation,'occupcode','description');

        var urlsex = 'pat_mast/get_entry?action=get_patient_sex';
        loadlist($('select#cmb_pat_sex').get(0),urlsex,'code','description');

        var urlrace = 'pat_mast/get_entry?action=get_patient_race';
        loadlist($('select#cmb_pat_racecode').get(0),urlrace,'Code','Description');

        var urlreligion = 'pat_mast/get_entry?action=get_patient_religioncode';
        loadlist($('select#cmb_pat_religion').get(0),urlreligion,'Code','Description');

        // var urlmarital = 'pat_mast/get_entry?action=get_patient_urlmarital';
        // loadlist($('select#maritalcode').get(0),urlmarital,'Code','Description');

        var urllanguagecode = 'pat_mast/get_entry?action=get_patient_language';
        loadlist($('select#cmb_pat_langcode').get(0),urllanguagecode,'Code','Description');

        // var urlrelationship = 'pat_mast/get_entry?action=get_patient_relationship';
        // loadlist($('select#relatecode').get(0),urlrelationship,'RelationShipCode','Description');

        // var urlactive = 'pat_mast/get_entry?action=get_patient_active';
        // loadlist($('select#active').get(0),urlactive,'RelationShipCode','Description');

        // var urlconfidential = 'pat_mast/get_entry?action=get_patient_urlconfidential';
        // loadlist($('select#confidential').get(0),urlactive,'RelationShipCode','Description');

        // var urlmrfolder = 'pat_mast/get_entry?action=get_patient_mrfolder';
        // loadlist($('select#mrfolder').get(0),urlmrfolder,'RelationShipCode','Description');

        // var urlpatientcat = 'pat_mast/get_entry?action=get_patient_patientcat';
        // loadlist($('select#patientcat').get(0),urlpatientcat,'RelationShipCode','Description');
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

    function populate_patient_episode (episode,rowid) {

        let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];

        $('#hid_pat_title').val(rowdata.TitleCode);
        $('#mrn').val(('0000000' + rowdata.MRN).slice(-7));
        // $('#oldmrn').val(rowdata.oldmrn);

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
        // $('#name').val(rowdata.name);

        // ********* episode ************
        if(episode == "episode"){
            $('#txt_epis_no').val(parseInt(rowdata.Episno) + 1); // dlm modal Episode
            $('#txt_epis_type').val("OP");                    
            $('#txt_epis_date').val(moment().format('DD/MM/YYYY'));
            $('#txt_epis_time').val(moment().format('hh:mm:ss'));

            if (rowdata.Sex == "M") // dlm modal Episode
            {
                $('#rad_epis_pregnancy_no').prop("checked", true);
                $('#rad_epis_pregnancy_yes').prop("disabled", true);
            }
                
        }
			//populate_payer_guarantee_info(d); tgk balik nanti
    }

    $('#mdl_patient_info').on('hidden.bs.modal', function (e) {
        destoryallerror();
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
    });

    $( "#biodata_but").click(function() {
    	populatecombo1();
        $('#mdl_patient_info').modal({backdrop: "static"});
        $("#btn_register_patient").data("oper","add");
        var first_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#first_visit_date').val(first_visit_val);
        var last_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#last_visit_date').val(last_visit_val);
        $('#episno').val('1');
        //patient_empty();
    });
	
	$('#btn_register_patient').click(function(){
        if($('#frm_patient_info').valid()){
            if($(this).data('oper') == 'add'){
                check_existing_patient();
            }else{
                save_patient($(this).data('oper'),$(this).data('idno'));
            }
        }
    });

    $('#btn_reg_proceed').click(function(){
        var checkedbox = $("#tbl_existing_record input[type='checkbox']:checked");
        if(checkedbox.closest("td").next().length>0){
            let mrn = checkedbox.data("mrn");
            let idno = checkedbox.data("idno");
            save_patient('edit',idno,mrn);
        }else{
            save_patient('add');
        }
    });

    function save_patient(oper,idno,mrn="nothing"){
        var saveParam={
            action:'save_patient',
            field:['Name','MRN','Newic','Oldic','ID_Type','idnumber','OccupCode','DOB','telh','telhp','Email','AreaCode','Sex','Citizencode','RaceCode','TitleCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno'],
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
        });
    }
	
	function check_existing_patient(){
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

        $.get( "/util/get_value_default?"+$.param(param), function( data ) {

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
                save_patient('add');
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

    var desc_show = new loading_desc([
        {code:'#hid_pat_citizen',desc:'#txt_pat_citizen',id:'citizencode'},
        {code:'#hid_pat_area',desc:'#txt_pat_area',id:'areacode'},
        {code:'#hid_pat_title',desc:'#txt_pat_title',id:'titlecode'}
    ]);
    desc_show.load_desc();

    function loading_desc(obj){
        this.code_fields=obj;
        this.titlecode={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.citizencode={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.areacode={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.load_desc = function(){
            var urlTitle = 'pat_mast/get_entry?action=get_patient_title';
            load_for_desc(this,'titlecode',urlTitle);

            var urlcitizen = 'pat_mast/get_entry?action=get_patient_citizen';
            load_for_desc(this,'citizencode',urlcitizen);

            var urlareacode = 'pat_mast/get_entry?action=get_patient_areacode';
            load_for_desc(this,'areacode',urlareacode);
        }

        function load_for_desc(selobj,id,url){
            $.getJSON(url,{},function(data){
                selobj[id].data = data.data;
            });
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
