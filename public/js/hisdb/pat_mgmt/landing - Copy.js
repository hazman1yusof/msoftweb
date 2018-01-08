
$(document).ready(function() {

	$('#tab_patient_info a:last').hide();	// hide Medical Info tab
	$('#frm_patient_info').validate();		// patient form validation

    var counter = 0;
    var grid = $("#grid-command-buttons").bootgrid({
        selection: true,
        ajax: true,
		ajaxSettings: {
			cache: false
		},
        post: function ()
        {
            return {
                Scol: $('#Scol').val()
            };
        },
        url: "../../../../assets/php/entry.php?action=get_patient_list",
        formatters: {
            "col_add": function (column,row) {
                return "<button title='Address' type='button' class='btn btn-xs btn-default btn-md command-add' data-row-id=\"" + row.MRN + "\"  name=\"cmd_add" + row.MRN + "\" data-telhp=\"" + row.telhp + "\"data-telh=\"" + row.telh + "\"data-Address1=\"" + row.Address1 + "\"data-Address2=\"" + row.Address2 + "\"data-Address3=\"" + row.Address3 + "\"data-Postcode=\"" + row.Postcode + "\"data-OffAdd1=\"" + row.OffAdd1 + "\"data-OffAdd2=\"" + row.OffAdd2 + "\"data-OffAdd3=\"" + row.OffAdd3 + "\"data-OffPostcode=\"" + row.OffPostcode + "\"data-pAdd1=\"" + row.pAdd1 + "\"data-pAdd2=\"" + row.pAdd2 + "\"data-pAdd3=\"" + row.pAdd3 + "\"data-pPostCode=\"" + row.pPostCode + "\" ><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></button>";
            },
            "col_mrn": function (column,row) {
                return ('0000000' + row.MRN).slice(-7);
            },
            "col_dob": function (column,row) {
                var birthday = new Date(row.DOB);
                return (isNaN(birthday.getFullYear()) ? 'n/a' : moment(birthday).format('DD/MM/YYYY'));
            },
            "col_age": function (column,row) {
                var day = new Date();
                var dob = new Date(row.DOB);
                return day.getFullYear() - dob.getFullYear();
            },
            "commands": function (column,row) {
                let rowid = counter++;
                return "<button title='Edit Patient' type='button' class='btn btn-xs btn-warning btn-md command-edit' data-row-id=\"" + rowid + "\"  id=\"cmd_edit" + row.MRN + "\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></button> " +
                       "<button title='New Episode' type='button' class='btn btn-xs btn-danger btn-md command-episode' data-row-id=\"" + rowid + "\"  id=\"cmd_history" + row.MRN + "\"><span class='glyphicon glyphicon-header' aria-hidden='true'></span></button>" +
					   "<button title='New OTC Episode' type='button' class='btn btn-xs btn-danger btn-md command-otc-episode' data-row-id=\"" + rowid + "\"  id=\"cmd_otc" + row.MRN + "\"><span class='fa fa-user-md' aria-hidden='true'></span></button>";

            }
        }
    }).on("loaded.rs.jquery.bootgrid", function(){
        counter = 0;

        if(!$("#Scol").length){ //tambah search col kat atas utk search by field and shit
            $(".actionBar").prepend("<select id='Scol' class='search form-group form-control'><option>MRN</option><option selected='true'>Name</option><option>Newic</option><option>Staffid</option></select>");
        }

        var detailRows = '';

        /* Executes after data is loaded and rendered */
        grid.find(".command-edit").on("click", function(e){
            populate_patient_episode($(this).data("rowId"));
            $('#mdl_patient_info').modal('show');
            $("#btn_register_patient").data("oper","edit");

        }).end().find(".command-episode").on("click", function(e) {
            populate_patient_episode($(this).data("rowId"));
            $('#editEpisode').modal('show');

            disableEpisode(true);
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
        });
    })

    populatecombo1();

    function loadlist(selobj,url,nameattr,descattr){
        // $(selobj).empty();
        $.getJSON(url,{},function(data)
        {
            $.each(data, function(i,obj)
            {
                if (nameattr == "doctorcode") console.log(obj[nameattr]);

                $(selobj).append(
                    $('<option></option>')
                        .val(obj[nameattr])
                        // .html(obj[descattr]));
                .html(obj[nameattr] + ' - ' + obj[descattr] ));
            });
        });
    }

    function loadlistEmpty(selobj,url,nameattr,descattr){
        $(selobj).empty();
        $.getJSON(url,{},function(data)
        {
            $.each(data, function(i,obj)
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

        var urlTitle = '../../../../assets/php/entry.php?action=get_patient_title';
        loadlist($('select#titlecode').get(0),urlTitle,'Code','Description');
        var urlType = '../../../../assets/php/entry.php?action=get_patient_idtype';
        loadlist($('select#cmb_pat_idtype').get(0),urlType,'Code','Description');
        var urloccupation = '../../../../assets/php/entry.php?action=get_patient_occupation';
        loadlist($('select#occupcode').get(0),urloccupation,'occupcode','description');
        var urlareacode = '../../../../assets/php/entry.php?action=get_patient_areacode';
        loadlist($('select#areacode').get(0),urlareacode,'areacode','description');
        var urlsex = '../../../../assets/php/entry.php?action=get_patient_sex';
        loadlist($('select#cmb_pat_sex').get(0),urlsex,'code','description');
        var urlcitizen = '../../../../assets/php/entry.php?action=get_patient_citizen';
        loadlist($('select#citizencode').get(0),urlcitizen,'Code','Description');
        var urlrace = '../../../../assets/php/entry.php?action=get_patient_race';
        loadlist($('select#cmb_pat_racecode').get(0),urlrace,'Code','Description');
        var urlreligion = '../../../../assets/php/entry.php?action=get_patient_religioncode';
        loadlist($('select#cmb_pat_religion').get(0),urlreligion,'Code','Description');
        var urlmarital = '../../../../assets/php/entry.php?action=get_patient_urlmarital';
        loadlist($('select#maritalcode').get(0),urlmarital,'Code','Description');
        var urllanguagecode = '../../../../assets/php/entry.php?action=get_patient_language';
        loadlist($('select#cmb_pat_langcode').get(0),urllanguagecode,'Code','Description');
        var urlrelationship = '../../../../assets/php/entry.php?action=get_patient_relationship';
        loadlist($('select#relatecode').get(0),urlrelationship,'RelationShipCode','Description');
        var urlactive = '../../../../assets/php/entry.php?action=get_patient_active';
        loadlist($('select#active').get(0),urlactive,'RelationShipCode','Description');
        var urlconfidential = '../../../../assets/php/entry.php?action=get_patient_urlconfidential';
        loadlist($('select#confidential').get(0),urlactive,'RelationShipCode','Description');
        var urlmrfolder = '../../../../assets/php/entry.php?action=get_patient_mrfolder';
        loadlist($('select#mrfolder').get(0),urlmrfolder,'RelationShipCode','Description');


        var urlpatientcat = '../../../../assets/php/entry.php?action=get_patient_patientcat';
        loadlist($('select#patientcat').get(0),urlpatientcat,'RelationShipCode','Description');


        populatecombo2();


    }

    function populatecombo2(){
        var urlReg = '../../../../assets/php/entry.php?action=get_reg_dept';
        loadlist($('select#cmb_epis_dept').get(0),urlReg,'deptcode','description');

        var urlReg = '../../../../assets/php/entry.php?action=get_reg_source';
        loadlist($('select#cmb_epis_source').get(0),urlReg,'admsrccode','description');

        var urlReg = '../../../../assets/php/entry.php?action=get_reg_case';
        loadlist($('select#cmb_epis_case').get(0),urlReg,'case_code','description');

        // var urlReg = '../../../../assets/php/entry.php?action=get_reg_doctor';
        // loadlist($('#cmb_epis_doctor').get(0),urlReg,'doctorcode','doctorname');

        var urlReg = '../../../../assets/php/entry.php?action=get_reg_fin';
        loadlist($('select#cmb_epis_fin').get(0),urlReg,'debtortycode','description');

        // var urlReg = '../../../../assets/php/entry.php?action=get_reg_pay(1)';
        // loadlist($('select#cmb_epis_pay_mode').get(0),urlReg,'Code','Description');
		
		var urlRel = '../../../../assets/php/entry.php?action=get_all_relationship';
        loadlist($('select#cmb_grtr_relation').get(0),urlRel,'relationshipcode','description');
    }

    function populate_patient_episode (rowid) {

        let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];

        $('#hid_pat_title').val(rowdata.TitleCode);
        $('#mrn').val(('0000000' + rowdata.MRN).slice(-7));
        // $('#oldmrn').val(rowdata.oldmrn);

        var first_visit = new Date(rowdata['first-visit-date']);
        var first_visit_val = (isNaN(first_visit.getFullYear()) ? 'n/a' : moment(first_visit).format('DD/MM/YYYY'));
        $('#first_visit_date').val(first_visit_val);
        var last_visit = new Date(rowdata['last-visit-date']);
        var last_visit_val = (isNaN(last_visit.getFullYear()) ? 'n/a' : moment(last_visit).format('DD/MM/YYYY'));
        $('#last_visit_date').val(last_visit_val);
        $('#episno').val(rowdata.Episno); // dlm modal Patient   
		
        $('#txt_pat_name').val(rowdata.Name);
        $('#txt_pat_newic').val(rowdata.Newic);
        $('#txt_pat_oldic').val(rowdata.Oldic);
        $('#cmb_pat_idtype').val(rowdata['ID-Type']);
        $('#txt_pat_idno').val(rowdata.idnumber);

        // $('#occupcode').val(rowdata.occupcode);
        $('#hid_pat_occupation').val(rowdata.OccupCode);

        $('#txt_pat_dob').val(rowdata.DOB);
        var day = new Date();
        var dob = new Date(rowdata.DOB);
        var age_val =  day.getFullYear() - dob.getFullYear();
        $('#txt_pat_age').val(age_val);
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

        $('#txt_epis_no').val(parseInt(rowdata.Episno) + 1); // dlm modal Episode
        $('#txt_epis_type').val("OP");                    
        $('#txt_epis_date').val(moment().format('DD/MM/YYYY'));
        $('#txt_epis_time').val(moment().format('hh:mm:ss'));

        if (rowdata.Sex == "M") // dlm modal Episode
        {
            $('#rad_epis_pregnancy_no').prop("checked", true);
            $('#rad_epis_pregnancy_yes').prop("disabled", true);
        }
			
			//populate_payer_guarantee_info(d); tgk balik nanti
    }
	
	function populate_payer_guarantee_info(d)
	{
		if (d > 0) {
            $.ajax({
                type: 'GET',
                url: '../../../../assets/php/entry.php?action=get_patient_payer_guarantee',
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

    function patient_empty (){

        $.ajax({
            type: 'GET',
            url: '../../../../assets/php/entry.php?action=get_patient_last',
            data: {},
            dataType: 'json',
            error: function() {
                $('#info').html('<p>An error has occurred getting last patient MRN No.</p>');
            },
            success: function(data) {

                var mrn = new Number(data.mrn[0].mrn);

                mrn = mrn+ 1;

                $('#mrn').val(('0000000' + mrn).slice(-7));

                var first_visit_val =moment(new Date()).format('DD/MM/YYYY');
                $('#first_visit_date').val(first_visit_val);
                var last_visit_val =moment(new Date()).format('DD/MM/YYYY');
                $('#last_visit_date').val(last_visit_val);
                $('#episno').val('1');
                // $('#oldmrn').removeAttr("disabled");

            }
        });

    }

    $('#mdl_patient_info').on('hidden.bs.modal', function (e) {
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
    });

    $( "#patientBox").click(function() {
        $("#btn_register_patient").data("oper","add");
        var first_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#first_visit_date').val(first_visit_val);
        var last_visit_val =moment(new Date()).format('DD/MM/YYYY');
        $('#last_visit_date').val(last_visit_val);
        $('#episno').val('1');
        //patient_empty();
    });
	
	$('#btn_register_patient').click(function(){
        if($(this).data('oper') == 'add'){
            check_existing_patient();   
        }else{
            save_patient($(this).data('oper'));
        }
    });

    function save_patient(oper){
        var saveParam={
            action:'save_table_default',
            field:['Name','Newic','Oldic','ID-Type','idnumber','OccupCode','DOB','telh','telhp','Email','AreaCode','Sex''Citizencode','RaceCode','Religion','MaritalCode','LanguageCode','Remarks','RelateCode','CorpComp','Email_official','Childno','Address1','Address2','Address3','Offadd1','Offadd2','Offadd3','pAdd1','pAdd2','pAdd3','Postcode','OffPostcode','pPostCode','Active','Confidential','MRFolder','PatientCat','NewMrn','bloodgrp','Episno'],
            oper:oper,
            table_name:'hisdb.patmast',
            table_id:'idno'
        };
    }
	
	function check_existing_patient()
	{
		var patname = $("#txt_pat_name").val();
		var patdob = moment($("#txt_pat_dob").val(), 'DD/MM/Y').format('Y-MM-DD');
		var patnewic = $("#txt_pat_newic").val();
		var patoldic = $("#txt_pat_oldic").val();
		var patidno = $("#txt_pat_idno").val();
		
		var frm_data = $( "#frm_patient_info" ).serialize();
		
		if ( (patname !== '' && patdob !== '') || (patname !== '' && ((patnewic !== '') || (patoldic !== '') || (patidno !== '')) ) )
		{
			$.getJSON( "../../../../assets/php/entry.php?action=check_existing_patient", 
						frm_data, 
						function (data, status) 
						{
							if(data != '')
							{
								//alert(data.rows);
								
								tbl_exist_rec = $('#tbl_existing_record').DataTable( {
    								"data": data.rows,
    								"lengthChange": false,
    								"info": false,
    								"pagingType" : "numbers",
    								"search": {
    											"smart": true,
    										  },
    								"columns": [
    											{'data' : 'mrn'},
    											{'data' : 'mrn'}, 
    											{'data' : 'name' },
    											{'data' : 'newic'}, 
    											{'data' : 'oldic' },
    											{'data' : 'id_number'}, 
    											{'data' : 'dob' },
    										   ],
    								columnDefs: [{
    									orderable: false,
    									className: 'select-checkbox',
    									targets:   0,
    									'render': function (data, type, full, meta){
    												 return '<input type="checkbox" name="chk_' + data + '" id="chk_' + data + '" value="' + data + '">';
    											 }
    								}],
    								select: {
    									style:    'os',
    									selector: 'td:first-child'
    								},
        						});
													
								$('#mdl_existing_record').modal('show');
							}
							else
							{
								alert('Error getting result!');
							}
						}
					)
					.success(function(data) { //if(data.result.err == ''){
							console.log('Checked successfully');
							//location.href = 'form_srequest.php?wrno='+data.result.id+'&ws='+$('#ws').val();
							//} 
						}
					)
				.error(function() { alert("Error getting network connection"); })
				
				//// Handle click on checkbox to set state of "Select all" control
				   // $('#example tbody').on('change', 'input[type="checkbox"]', function(){
					  // If checkbox is not checked
					  // if(!this.checked){
						 // var el = $('#example-select-all').get(0);
						// If "Select all" control is checked and has 'indeterminate' property
						 // if(el && el.checked && ('indeterminate' in el)){
							// Set visual state of "Select all" control 
							// as 'indeterminate'
							// el.indeterminate = true;
						 // }
					  // }
				   // });
		}
	}
	
	$('#btn_reg_proceed').click(register_patient);
	
	function register_patient()
    {
        //var epismrn = $('#mrn').val();

        // var pattitle = $("#hid_pat_title").val();
        // var patname = $("#txt_pat_name").val();
		// var patdob = moment($("#txt_pat_dob").val(), 'DD/MM/Y').format('Y-MM-DD');
        // var patsex = $("#cmb_pat_sex").val();
        // var patoccup = $("#hid_pat_occupation").val();
		// var patidtype = $("#cmb_pat_idtype").val();
		// var patnewic = $("#txt_pat_newic").val();
		// var patoldic = $("#txt_pat_oldic").val();
		// var patidno = $("#txt_pat_idno").val();
		// var patemail = $("#txt_pat_email").val();
		// var patrace = $("#cmb_pat_racecode").val();
		// var patreligion = $("#cmb_pat_religion").val();
		// var patcitizen = $("#hid_pat_citizen").val();
		// var patarea = $("#hid_pat_area").val();
		// var patlanguage = $("#cmb_pat_langcode").val();
		
		// var patcurradd1 = $("#txt_pat_curradd1").val();
		// var patcurradd2 = $("#txt_pat_curradd2").val();
		// var patcurradd3 = $("#txt_pat_curradd3").val();
		// var patcurrpostcode = $("#txt_pat_currpostcode").val();
		// var patcurrtelh = $("#txt_pat_currtelh").val();
		// var patcurrtelhp = $("#txt_pat_currtelhp").val();
		
		// var patoffadd1 = $("#txt_pat_offadd1").val();
		// var patoffadd2 = $("#txt_pat_offadd2").val();
		// var patoffadd3 = $("#txt_pat_offadd3").val();;
		// var patoffpostcode = $("#txt_pat_offpostcode").val();
		// var pattelo = $("#txt_pat_telo").val();
		// var patteloext = $("#txt_pat_teloext").val();
		
		// var patpadd1 = $("#txt_pat_padd1").val();
		// var patpadd2 = $("#txt_pat_padd2").val();
		// var patpadd3 = $("#txt_pat_padd3").val();
		// var patppostcode = $("#txt_pat_ppostcode").val();
		// var patptel = $("#txt_pat_ptel").val();
		// var patptelhp = $("#txt_pat_ptelhp").val();
		
        ////var epistime = moment($("#txt_epis_time").val(), 'hh:mm:ss').format('hh:mm');
        
        ////var epissrc = $("#cmb_epis_source").val();
        ////var episcase = $("#cmb_epis_case").val();
        ////var episdoctor = $("#cmb_epis_doctor").val();
        ////var episfin = $("#cmb_epis_fin").val();
        ////var epispay = $("#cmb_epis_pay_mode").val();
        ////var epispayer = $("#hid_epis_payer").val();
        ////var episbilltype = $("#hid_epis_bill_type").val();
        ////var episrefno = $("#txt_epis_refno").val();
        ////var episourrefno = $("#txt_epis_our_refno").val();
        ////var epispreg = $('input[name=rad_epis_pregnancy]:checked').val();
        ////var episfee = $('input[name=rad_epis_fee]:checked').val();

        // $.post( 
                  // "../../../../assets/php/entry.php?action=save_new_episode",
                  // { 
                    // epis_mrn: epismrn,
                    // epis_no : episno,
                    // epis_type : epistype, 
                    // epis_maturity : epismaturity,
                    // epis_date : episdate,
                    // epis_time : epistime,
                    // epis_dept : episdept,
                    // epis_src : epissrc,
                    // epis_case : episcase,
                    // epis_doctor : episdoctor,
                    // epis_fin : episfin,
                    // epis_pay : epispay,
                    // epis_payer : epispayer,
                    // epis_billtype : episbilltype,
                    // epis_refno : episrefno,
                    // epis_ourrefno : episourrefno,
                    // epis_preg : epispreg,
                    // epis_fee : episfee
                  // },
                  // function(data) 
                  // {                    
                    ////users_table.ajax.reload( null, false );                    
                    ////reset_form();

                    // alert("This patient has been registered.");
                  // }
               // );
			   
			   
		alert("This patient has been registered.");
    }
	

    // *************************** episode ******************************

    $('#btn_save_episode').click(add_episode);

    $('#cmb_epis_case').change (function (e) {

        var icase = $('#cmb_epis_case').val();

        //$('#cmb_epis_doctor').empty();
        
        var urlReg = '../../../../assets/php/entry.php?action=get_doctor_by_discipline&disc=' + icase;
        loadlist($('#cmb_epis_doctor').get(0),urlReg,'doctorcode','doctorname');
    });


    //$('#txt_epis_fin').blur(function (e)
	$('#cmb_epis_pay_mode').click(function (e)	
	{	
		console.log('hid_epis_fin=' + $('#hid_epis_fin').val());
		//alert('boleh enable');
		
		 $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');

        var iregin = $('#hid_epis_fin').val();

        if (iregin == '0' || iregin == '') {
            disableEpisode (true);
        } 
		else 
		{			
            disableEpisode (false);
			$('#cmb_epis_pay_mode').empty();

            if (iregin == 'PT' || iregin == 'PR') {
                var urlReg = '../../../../assets/php/entry.php?action=get_reg_pay1';
                loadlistEmpty($('select#cmb_epis_pay_mode').get(0),urlReg,'Code','Description');
            }else {
                var urlReg = '../../../../assets/php/entry.php?action=get_reg_pay2';
                loadlistEmpty($('select#cmb_epis_pay_mode').get(0),urlReg,'Code','Description');

            }
        }
    });

	var debtor_table = null;
	var debtor_table_name = null;
	var debtor_mdl_opened = null;
	var debtor_item = null;
    $( "#btn_epis_payer").click(function() {
        var iregin = $('#hid_epis_fin').val();
        if (iregin == 'PT' || iregin == 'PR') 
		{
			debtor_table_name = $('#tbl_epis_debtor1');
		
			debtor_table = $('#tbl_epis_debtor1').DataTable( {
							"ajax": "../../../../assets/php/entry.php?action=get_debtor_list&tp=1&mrn=" + $('#mrn').val(),
							"columns": [
										{'data': 'debtortype'}, 
										{'data': 'debtorcode' }, 
										{'data': 'name'},
                                       ]
					} );
					
            debtor_mdl_opened = $('#mdl_epis_pay_mode_1');
			debtor_mdl_opened.modal('show');
        } 
		else 
		{
			debtor_table_name = $('#tbl_epis_debtor2');
		
			debtor_table = $('#tbl_epis_debtor2').DataTable( {
							"ajax": "../../../../assets/php/entry.php?action=get_debtor_list&tp=2&mrn=" + $('#mrn').val(),
							"columns": [
										{'data': 'debtortype'}, 
										{'data': 'debtorcode' }, 
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
			//		} );
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
						"ajax": "../../../../assets/php/entry.php?action=get_billtype_list&tp=" + $('#txt_epis_type').val(),
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
						"ajax": "../../../../assets/php/entry.php?action=get_refno_list&tp=" + $('#hid_epis_payer').val() + "&mrn=" + $('#mrn').val(),
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
	
	$( "#btn_payer_new_gl").click(function() 
	{
        $('#mdl_new_gl').modal('show');
    });

    $( "#btn_epis_new_gl").click(function() 
	{
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
                  "../../../../assets/php/entry.php?action=save_new_episode",
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
		var url = "../../../../assets/php/entry.php?action=add_new_guarantor";
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
                  "../../../../assets/php/entry.php?action=save_new_episode",
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




} );




