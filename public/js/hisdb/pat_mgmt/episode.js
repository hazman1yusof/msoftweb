    
    function destoryallerror(){
        $('#frm_patient_info').find("label.error").detach();
    }//utk buang label error lepas close dialog modal

    $('#editEpisode').on('hidden.bs.modal', function (e) {
        destoryallerror();
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
    });

    function check_debtormast_exists(rowid){
        let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];

        //check debtormast, kalau ada sama dgn mrn, paste
        let obj_param = {
               action:'check_debtormast',
               mrn:rowdata.MRN,
               mrn_trailzero:('0000000' + rowdata.MRN).slice(-7),
               name:rowdata.Name,
               address1:rowdata.Address1,
               address2:rowdata.Address2,
               address3:rowdata.Address3,
               address4:rowdata.Address4,
               postcode:rowdata.Postcode
           };

        $.get( "pat_mast/get_entry?"+$.param(obj_param), function( data ) {
            
        },'json').done(function(data) {
            if(!$.isEmptyObject(data)){
               $('#txt_epis_payer').val(data.data.name);
               $('#hid_epis_payer').val(data.data.debtorcode);
               
            }
        });
    }

    function populate_patient_episode (episode,rowid) {

        let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];


        $('#hid_pat_title').val(rowdata.TitleCode);
        $('#mrn').val(('0000000' + rowdata.MRN).slice(-7));
        $('#mrn_episode').val(rowdata.MRN);

        $('#rowid').val(rowid);

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
	
	function populate_payer_guarantee_info(d)
	{
		if (d > 0) {
            $.ajax({
                type: 'GET',
                url: '../../../../assets/php/entry_hisdb.php?action=get_patient_payer_guarantee',
                data: { patid: d, limit: 1 },
                dataType: 'json',
                error: function() {
                    $('#info').html('<p>An error has occurred</p>');
                },
                success: function(data) {
                    // console.log(data.payer[0].mrn);

					$('#txt_pat_relation').val(data.payer[0].relatedesc);
					$('#hid_payer_relation').val(data.payer[0].relatecode);
					
					$('#txt_payer_childno').val(data.payer[0].childno);
                    $('#txt_payer_staffid').val(data.payer[0].staffid);					
					
                    //$('#mrn').val(('0000000' + data.payer[0].mrn).slice(-7));
				
                }
            });
        }
	}

     // *************************** episode ******************************

    $('#btn_save_episode').click(add_episode);

    $('#cmb_epis_case').change (function (e) {

        var icase = $('#cmb_epis_case').val();

        //$('#cmb_epis_doctor').empty();
        
        var urlReg = '../../../../assets/php/entry_hisdb.php?action=get_doctor_by_discipline&disc=' + icase;
        loadlist($('#cmb_epis_doctor').get(0),urlReg,'doctorcode','doctorname');
    });


    $('#txt_epis_fin').change(function (e){
        var iregin = $('#hid_epis_fin').val();
        if (iregin == '0' || iregin == '') {
            disableEpisode (true);
        } else {  

            disableEpisode (false);
            $('#cmb_epis_pay_mode').empty();

            $('#txt_epis_payer').prop('disabled',false);
            $("#btn_epis_payer").on('click',epis_payer_onclick);
            let pay_mode_arr = [];
            if (iregin == 'PT'){
                $('#txt_epis_payer').prop('disabled',true);
                $("#btn_epis_payer").off('click',epis_payer_onclick);
                pay_mode_arr = ['Cash','Card','Open Card','Consultant Guarantee (PWD)'];
                check_debtormast_exists($('#rowid').val());
            }else if(iregin == 'PR'){
                pay_mode_arr = ['Cash','Card','Open Card','Consultant Guarantee (PWD)'];
                check_debtormast_exists($('#rowid').val());
            }else{
                pay_mode_arr = ['Panel', 'Guarantee letter', 'waiting GL'];
            }

            $.each(pay_mode_arr, function(i,val)
            {

                $("#cmb_epis_pay_mode").append(
                    $('<option></option>')
                        .val(val)
                        .html(val)
                );
            });

        }
    });

    $('#cmb_epis_pay_mode').click(function (e){  
        $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
    });

    var debtor_table =
        $('#tbl_epis_debtor').DataTable( {
            "columns": [
                        {'data': 'debtortype'}, 
                        {'data': 'debtorcode' }, 
                        {'data': 'name'},
                    ]
        } );

    $("#btn_epis_payer").on('click',epis_payer_onclick);

    function epis_payer_onclick(){
        let debtor_mdl_opened = $('#mdl_epis_pay_mode');
        debtor_mdl_opened.modal('show');
        if($('#hid_epis_fin').val() == 'PT'  || $('#hid_epis_fin').val() == 'PR'){
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=1' ).load();
        }else{
            debtor_table.ajax.url( 'pat_mast/get_entry?action=get_debtor_list&type=2' ).load();
        }
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        $('#tbl_epis_debtor').on('dblclick', 'tr', function () {
            let debtor_item = debtor_table.row( this ).data();              
            $('#hid_epis_payer').val(debtor_item["debtorcode"]);
            $('#txt_epis_payer').val(debtor_item["name"]);
            debtor_mdl_opened.modal('hide');
        } );
            
    }

    var billtype_table = 
            $('#tbl_epis_billtype').DataTable( {
                "columns": [
                    {'data': 'billtype'}, 
                    {'data': 'description' },
                   ]
            });

    $("#btn_bill_type_info").on('click',bill_type_info_onclick);
    function bill_type_info_onclick(){
        billtype_mdl_opened = $('#mdl_bill_type');
        billtype_mdl_opened.modal('show');
        billtype_table.ajax.url( 'pat_mast/get_entry?action=get_billtype_list&type=' + $('#txt_epis_type').val() ).load();
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        $('#tbl_epis_billtype').on('dblclick', 'tr', function () {
                let billtype_item = billtype_table.row( this ).data();              
                $('#hid_epis_bill_type').val(billtype_item["idno"]);
                $('#txt_epis_bill_type').val(billtype_item["description"]);
                billtype_mdl_opened.modal('hide');
            } );
    }

    $( "#btngurantor").click(function() {
        $('#bs-guarantor').modal('show');
        $('#mdl_epis_pay_mode').modal('hide');
    });

    $( "#btngurantorclose").click(function() {
        $('#mdl_epis_pay_mode').modal('show');
    });

    $( "#btngurantorcommit").click(add_guarantor);


    var refno_table = null;
    var refno_table_name = null;
    var refno_mdl_opened = null;
    var refno_item = null;
    $( "#btn_refno_info").click(function() 
    {
        refno_table_name = $('#tbl_epis_reference');
        
        refno_table = $('#tbl_epis_reference').DataTable( {
                        "ajax": "../../../../assets/php/entry_hisdb.php?action=get_refno_list&tp=" + $('#hid_epis_payer').val() + "&mrn=" + $('#mrn').val(),
                        "columns": [
                                    {'data': 'staffid'}, 
                                    {'data': 'debtorcode' },
                                    {'data': 'name' },
                                    {'data': 'ourrefno' },
                                    {'data': 'refno' },
                                   ]
                } );
                
        refno_mdl_opened = $('#mdl_reference');
        refno_mdl_opened.modal('show');
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        refno_table_name.on('dblclick', 'tr', function () {
                //console.dir(debtor_table_name);
                refno_item = refno_table.row( this ).data();                
                //console.log("type2="+type + " refno_item=" + refno_item["description"]);
                $('#txt_epis_our_refno').val(refno_item["ourrefno"]);
                $('#txt_epis_refno').val(refno_item["refno"]);
                
                    
                refno_mdl_opened.modal('hide');
                
                //alert( 'You clicked on ' + refno_item["description"] + '\'s row.' );
            } );
            
        refno_mdl_opened.on('hidden.bs.modal', function () 
        {
            refno_table_name.html('');
            refno_item = null;
        });
    });

    $( "#txt_epis_our_refno2").click(function() {
        $('#glReference').modal('show');
    });
    
    $( "#btn_payer_new_gl").click(function() {
        $('#mdl_new_gl').modal('show');
    });

    $( "#btn_epis_new_gl").click(function() {
        $('#mdl_reference').modal('hide');
        $('#mdl_new_gl').modal('show');
    });

    $( "#btnglclose").click(function() {
        $('#mdl_reference').modal('show');
    });

    $( "#btnglsave").click(function() {
        $('#mdl_reference').modal('show');
    });

    function disableEpisode(status) {

        //$('#cmb_epis_pay_mode').prop("disabled",status);
        $('#txt_epis_payer').prop("disabled",status);
        $('#txt_epis_bill_type').prop("disabled",status);
        $('#rad_epis_fee_yes').prop("disabled",status);
        $('#rad_epis_fee_no').prop("disabled",status);

        $('#txt_epis_refno').prop("disabled",status);
        $('#txt_epis_our_refno').prop("disabled",status);

        $('#txt_epis_refno2').prop("disabled",status);
        $('#txt_epis_our_refno2').prop("disabled",status);


        $('#regque').prop("disabled",status);

        // $('#np').prop("disabled",status);
        // $('#nnp').prop("disabled",status);
        // $('#fp').prop("disabled",status);
        // $('#fnp').prop("disabled",status);

    }

    function add_episode()
    {
        var epismrn = $('#mrn_episode').val();
        var episno = $("#txt_epis_no").val();
        var epistype = $("#txt_epis_type").val();
        var epismaturity = $("#cmb_epis_case_maturity").val();
        var episdate = moment($("#txt_epis_date").val(), 'DD/MM/Y').format('Y-MM-DD');
        var epistime = moment($("#txt_epis_time").val(), 'hh:mm:ss').format('hh:mm');
        var episdept = $("#hid_epis_dept").val();
        var epissrc = $("#hid_epis_source").val();
        var episcase = $("#hid_epis_case").val();
        var episdoctor = $("#hid_epis_doctor").val();
        var episfin = $("#hid_epis_fin").val();
        var epispay = $("#cmb_epis_pay_mode").val();
        var epispayer = $("#hid_epis_payer").val();
        var episbilltype = $("#hid_epis_bill_type").val();
        var episrefno = $("#txt_epis_refno").val();
        var episourrefno = $("#txt_epis_our_refno").val();
        var epispreg = $('input[name=rad_epis_pregnancy]:checked').val();
        var episfee = $('input[name=rad_epis_fee]:checked').val();
        var _token = $('#csrf_token').val();

        let obj =  { 
                    epis_mrn: epismrn,
                    epis_no : episno,
                    epis_type : epistype, 
                    epis_maturity : epismaturity,
                    epis_date : episdate,
                    epis_time : epistime,
                    epis_dept : episdept,
                    epis_src : epissrc,
                    epis_case : episcase,
                    epis_doctor : episdoctor,
                    epis_fin : episfin,
                    epis_pay : epispay,
                    epis_payer : epispayer,
                    epis_billtype : episbilltype,
                    epis_refno : episrefno,
                    epis_ourrefno : episourrefno,
                    epis_preg : epispreg,
                    epis_fee : episfee,
                    _token: _token
                  }

        $.post( "pat_mast/save_episode", obj , function( data ) {
            
        }).fail(function(data) {

        }).success(function(data){
            alert("New episode has been saved!");
        });
    }
    
    function add_guarantor()
    {
        var url = "../../../../assets/php/entry_hisdb.php?action=add_new_guarantor";
        var data = $("#frm_guarantor").serialize();
        
        if (confirm('Are you sure you want to save these details?')) {  
            $.getJSON(url, data, function (data, status) {
                if(data.result != ''){
                    alert(data.result.msg);
                }else{
                    alert('Error getting result!');
                }
            })
            .success(function(data) { //if(data.result.err == ''){
                console.log('Updated successfully');
                //location.href = 'form_srequest.php?wrno='+data.result.id+'&ws='+$('#ws').val();
            //} 
            })
            .error(function() { alert("Error getting network connection"); })
        }
        
    
        /*var epismrn = $('#mrn').val();

        var episno = $("#txt_epis_no").val();
        var epistype = $("#txt_epis_type").val();
        var epismaturity = $("#cmb_epis_case_maturity").val();
        var episdate = moment($("#txt_epis_date").val(), 'DD/MM/Y').format('Y-MM-DD');
        var epistime = moment($("#txt_epis_time").val(), 'hh:mm:ss').format('hh:mm');
        var episdept = $("#cmb_epis_dept").val();
        var epissrc = $("#cmb_epis_source").val();
        var episcase = $("#cmb_epis_case").val();
        var episdoctor = $("#cmb_epis_doctor").val();
        var episfin = $("#cmb_epis_fin").val();
        var epispay = $("#cmb_epis_pay_mode").val();
        var epispayer = $("#hid_epis_payer").val();
        var episbilltype = $("#hid_epis_bill_type").val();
        var episrefno = $("#txt_epis_refno").val();
        var episourrefno = $("#txt_epis_our_refno").val();
        var epispreg = $('input[name=rad_epis_pregnancy]:checked').val();
        var episfee = $('input[name=rad_epis_fee]:checked').val();

        $.post( 
                  "../../../../assets/php/entry_hisdb.php?action=save_new_episode",
                  { 
                    epis_mrn: epismrn,
                    epis_no : episno,
                    epis_type : epistype, 
                    epis_maturity : epismaturity,
                    epis_date : episdate,
                    epis_time : epistime,
                    epis_dept : episdept,
                    epis_src : epissrc,
                    epis_case : episcase,
                    epis_doctor : episdoctor,
                    epis_fin : episfin,
                    epis_pay : epispay,
                    epis_payer : epispayer,
                    epis_billtype : episbilltype,
                    epis_refno : episrefno,
                    epis_ourrefno : episourrefno,
                    epis_preg : epispreg,
                    epis_fee : episfee
                  },
                  function(data) 
                  {                    
                    //users_table.ajax.reload( null, false );                    
                    //reset_form();

                    alert("New episode has been saved!");
                  }
               );
        */
    }


    var desc_show_epi = new loading_desc_episode([
        {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'epis_dept'},
        {code:'#hid_epis_source',desc:'#txt_epis_source',id:'epis_source'},
        {code:'#hid_epis_case',desc:'#txt_epis_case',id:'epis_case'},
        {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'epis_doctor'},
        {code:'#txt_epis_fin',desc:'#hid_epis_fin',id:'epis_fin'}
    ]);
    desc_show_epi.load_desc();

    function loading_desc_episode(obj){
        this.code_fields=obj;
        this.epis_dept={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_source={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_case={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_doctor={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_fin={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.load_desc = function(){
            load_for_desc(this,'epis_dept','pat_mast/get_entry?action=get_reg_dept');

            load_for_desc(this,'epis_source','pat_mast/get_entry?action=get_reg_source');

            load_for_desc(this,'epis_case','pat_mast/get_entry?action=get_reg_case');

            load_for_desc(this,'epis_doctor','pat_mast/get_entry?action=get_reg_doctor');

            load_for_desc(this,'epis_fin','pat_mast/get_entry?action=get_reg_fin');
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

    function click_episode_button() {
        populate_patient_episode("episode",$(this).data("rowId"));
        check_last_episde();
        $('#editEpisode').modal({backdrop: "static"});
        $('#btn_epis_payer').data('mrn',$(this).data("mrn"));

        disableEpisode(true);
    }

    function populate_episode_by_mrn_episno(mrn,episno,form){
        var param={
            action:'get_value_default',
            field:"*",
            table_name:'hisdb.episode',
            table_id:'_none',
            filterCol:['compcode','mrn','episno'],filterVal:['session.company',mrn,episno]
        };

        $.get( "/util/get_value_default?"+$.param(param), function( data ) {

        },'json').done(function(data) {

            if(data.rows.length > 0){
                
                $.each(data.rows[0], function( index, value ) {
                    var input=$(form+" [name='"+index+"']");

                    if(input.is("[type=radio]")){
                        $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
                    }else{
                        input.val(value);
                    }
                });


            }else{
                alert('MRN not found')
            }

        }).error(function(data){

        });

    }

    function check_last_episode(){

    }