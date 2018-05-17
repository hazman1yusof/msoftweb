    
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
        } 
        else 
        {           
            disableEpisode (false);
            $('#cmb_epis_pay_mode').empty();

            if (iregin == 'PT' || iregin == 'PR'){
                var urlReg = '../../../../assets/php/entry_hisdb.php?action=get_reg_pay1';
                loadlistEmpty($('select#cmb_epis_pay_mode').get(0),urlReg,'Code','Description');
            }else{
                var urlReg = '../../../../assets/php/entry_hisdb.php?action=get_reg_pay2';
                loadlistEmpty($('select#cmb_epis_pay_mode').get(0),urlReg,'Code','Description');

            }
        }
    });

    $('#cmb_epis_pay_mode').click(function (e)  
    {   
        console.log('hid_epis_fin=' + $('#hid_epis_fin').val());
        //alert('boleh enable');
        
         $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
    });

    var debtor_table = null;
    var debtor_table_name = null;
    var debtor_mdl_opened = null;
    var debtor_item = null;
    $( "#btn_epis_payer").click(function() {
        var iregin = $('#hid_epis_fin').val();
        if (iregin == 'PT' || iregin == 'PR'){
            debtor_table_name = $('#tbl_epis_debtor1');
        
            debtor_table = $('#tbl_epis_debtor1').DataTable( {
                            "ajax": "../../../../assets/php/entry_hisdb.php?action=get_debtor_list&tp=1&mrn="+$(this).data('mrn'),
                            "columns": [
                                        {'data': 'debtortype'}, 
                                        {'data': 'debtorcode' }, 
                                        {'data': 'name'},
                                        ]
                    } );
                    
            debtor_mdl_opened = $('#mdl_epis_pay_mode_1');
            debtor_mdl_opened.modal('show');
        }else{
            debtor_table_name = $('#tbl_epis_debtor2');
        
            debtor_table = $('#tbl_epis_debtor2').DataTable( {
                            "ajax": "../../../../assets/php/entry_hisdb.php?action=get_debtor_list&tp=2&mrn="+$(this).data('mrn'),
                            "columns": [
                                        {'data': 'debtortype'}, 
                                        {'data': 'debtorcode'}, 
                                        {'data': 'description'},
                                       ]
                    } );
        
            debtor_mdl_opened = $('#mdl_epis_pay_mode_2')
            debtor_mdl_opened.modal('show');
        }
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
            debtor_table_name.on('dblclick', 'tr', function () {
                //console.dir(debtor_table_name);
                debtor_item = debtor_table.row( this ).data();              
                //console.log("type2="+type + " debtor_item=" + debtor_item["description"]);
                $('#hid_epis_payer').val(debtor_item["debtorcode"]);
                $('#txt_epis_payer').val(debtor_item["name"]);
                
                    
                debtor_mdl_opened.modal('hide');
                
                //alert( 'You clicked on ' + debtor_item["description"] + '\'s row.' );
            } );
            
        debtor_mdl_opened.on('hidden.bs.modal', function () {
            //$('#tbl_item_select tbody').off('dblclick', 'tr', function () {
                        debtor_table_name.html('');
                        //table_name.destroy();
                        //type = "";
                        debtor_item = null;
                        //console.dir(debtor_table_name);
                        //console.dir(debtor_item);
            //      } );
        });

    });

    var billtype_table = null;
    var billtype_table_name = null;
    var billtype_mdl_opened = null;
    var billtype_item = null;
    $( "#btn_bill_type_info").click(function() 
    {
        billtype_table_name = $('#tbl_epis_billtype');
        
        billtype_table = $('#tbl_epis_billtype').DataTable( {
                        "ajax": "../../../../assets/php/entry_hisdb.php?action=get_billtype_list&tp=" + $('#txt_epis_type').val(),
                        "columns": [
                                    {'data': 'billtype'}, 
                                    {'data': 'description' },
                                   ]
                } );
                
        billtype_mdl_opened = $('#mdl_bill_type');
        billtype_mdl_opened.modal('show');
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        billtype_table_name.on('dblclick', 'tr', function () {
                //console.dir(debtor_table_name);
                billtype_item = billtype_table.row( this ).data();              
                //console.log("type2="+type + " billtype_item=" + billtype_item["description"]);
                $('#hid_epis_bill_type').val(billtype_item["idno"]);
                $('#txt_epis_bill_type').val(billtype_item["description"]);
                
                    
                billtype_mdl_opened.modal('hide');
                
                //alert( 'You clicked on ' + billtype_item["description"] + '\'s row.' );
            } );
            
        billtype_mdl_opened.on('hidden.bs.modal', function () 
                    {
                        billtype_table_name.html('');
                        billtype_item = null;
                    });
    });

    $( "#btngurantor").click(function() {
        $('#bs-guarantor').modal('show');
        $('#mdl_epis_pay_mode_1').modal('hide');
    });

    $( "#btngurantorclose").click(function() {
        $('#mdl_epis_pay_mode_1').modal('show');
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
        var epismrn = $('#mrn').val();

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

    function click_episode_button() {
        populate_patient_episode("episode",$(this).data("rowId"));
        $('#editEpisode').modal({backdrop: "static"});
        $('#btn_epis_payer').data('mrn',$(this).data("mrn"));

        disableEpisode(true);
    }