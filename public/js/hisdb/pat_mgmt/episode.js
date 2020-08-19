
    $('#editEpisode').on('hidden.bs.modal', function (e) {
        $('#editEpisode').find("label.error").detach();
        $("#editEpisode").find('.error').removeClass("error");
        $("#editEpisode").find('.valid').removeClass("valid");
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end(); //this for clearing input after hide modal
        $("#tabDoctor,#tabBed,#tabNok,#tabPayer,#tabDeposit").collapse("hide");
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
               
            }else{

               $('#txt_epis_payer').val($("#txt_epis_name").val());
               $('#hid_epis_payer').val($("#mrn_episode").val());
            }
        });
    }

    function populate_episode(rowid){
        let rowdata = $("#grid-command-buttons").bootgrid("getCurrentRows")[rowid];
        disableEpisode(true);

        $('#mrn_episode').val(rowdata.MRN);
        $('#epis_rowid').val(rowid);
        $('#txt_epis_name').text(rowdata.Name);
        $('#txt_epis_mrn').text(('0000000' + rowdata.MRN).slice(-7));
        $('#txt_epis_type').val($("#epistycode").val());
        $('#txt_epis_date').val(moment().format('DD/MM/YYYY'));
        $('#txt_epis_time').val(moment().format('hh:mm:ss'));
        $('#btn_epis_payer').data('mrn',$(this).data("mrn"));
        if(rowdata.Sex == "M"){
            $('#cmb_epis_pregnancy').val('Non-Pregnant');
            $('#cmb_epis_pregnancy').prop("disabled", true);
        }else{
            $('#cmb_epis_pregnancy').prop("disabled", false);
        }

        var episno_ = parseInt(rowdata.Episno);
        if(isNaN(episno_)){
            episno_ = 0;
        }

        if(rowdata.PatStatus == 1){
            $("#episode_oper").val('edit');
            $('#txt_epis_no').val(parseInt(episno_));
            populate_episode_by_mrn_episno(rowdata.MRN,rowdata.Episno);
            $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().show();
        }else{
            $("#episode_oper").val('add');
            $('#txt_epis_no').val(parseInt(episno_) + 1);
            $("#toggle_tabDoctor,#toggle_tabBed,#toggle_tabNok,#toggle_tabPayer,#toggle_tabDeposit").parent().hide();
        }
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

    $('#form_episode').validate({
        errorPlacement: function(error, element) {
            error.insertAfter( element.closest(".input-group") );
        }
    });

    $('#btn_save_episode').click(function(){
        if($('#epis_header').valid() && $('#form_episode').valid()){
            add_episode();
        }
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
                pay_mode_arr = ['CASH','CARD','OPEN CARD','CONSULTANT GUARANTEE (PWD)'];
                check_debtormast_exists($('#epis_rowid').val());
            }else if(iregin == 'PR'){
                pay_mode_arr = ['CASH','CARD','OPEN CARD','CONSULTANT GUARANTEE (PWD)'];
                check_debtormast_exists($('#epis_rowid').val());
            }else{
                pay_mode_arr = ['PANEL', 'GUARANTEE LETTER', 'WAITING GL'];
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
                $('#hid_epis_bill_type').val(billtype_item["billtype"]);
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

    // $( "#btngurantorcommit").click(add_guarantor);


    $( "#btn_refno_info").click(function() 
    {
        var refno_table = null;
        var refno_table_name = null;
        var refno_mdl_opened = null;
        var refno_item = null;

        refno_table_name = $('#tbl_epis_reference');
        
        refno_table = $('#tbl_epis_reference').DataTable( {
                        "ajax": "/pat_mast/get_entry?action=get_refno_list&debtorcode=" + $('#hid_epis_payer').val() + "&mrn=" + $('#mrn_episode').val(),
                        "columns": [
                                    {'data': 'staffid'}, 
                                    {'data': 'debtorcode' },
                                    {'data': 'name' },
                                    {'data': 'gltype' },
                                    {'data': 'debtorcode' },
                                   ]
                } );
                
        refno_mdl_opened = $('#mdl_reference');
        refno_mdl_opened.modal('show');
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        refno_table_name.on('dblclick', 'tr', function () {
                //console.dir(debtor_table_name);
                refno_item = refno_table.row( this ).data();                
                //console.log("type2="+type + " refno_item=" + refno_item["description"]);
                $('#txt_epis_our_refno').val(refno_item["debtorcode"]);
                $('#txt_epis_refno').val(refno_item["debtorcode"]);
                
                    
                refno_mdl_opened.modal('hide');
                
                //alert( 'You clicked on ' + refno_item["description"] + '\'s row.' );
            } );
            
        refno_mdl_opened.on('hidden.bs.modal', function () 
        {
            refno_table.destroy();
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
        if($('#glform').valid()){
            var _token = $('#csrf_token').val();
            let serializedForm = $( "#glform" ).serializeArray();
            let obj = {
                    'debtorcode':$('#hid_epis_payer').val(),
                    'mrn':$('#mrn_episode').val(),
                    '_token': _token
            }
            
            $.post( '/pat_mast/save_gl', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                $("#glform").trigger('reset');
                $('#mdl_new_gl').modal('hide');
                $('#mdl_reference').modal('show');
            }).fail(function(data) {
                alert(data.responseText);
            }).success(function(data){
            });
        }
        

    });

    function disableEpisode(status) {

        $('#txt_epis_payer').prop("disabled",status);
        $('#txt_epis_bill_type').prop("disabled",status);
        $('#rad_epis_fee_yes').prop("disabled",status);
        $('#rad_epis_fee_no').prop("disabled",status);

        $('#txt_epis_refno').prop("disabled",status);
        $('#txt_epis_our_refno').prop("disabled",status);

        $('#txt_epis_refno2').prop("disabled",status);
        $('#txt_epis_our_refno2').prop("disabled",status);


        $('#regque').prop("disabled",status);
    }

    function add_episode()
    {    
        var episoper = $("#episode_oper").val();
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
        var episbed = $('#txt_epis_bed').val();
        var _token = $('#csrf_token').val();

        let obj =  { 
                    episoper: episoper,
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
                    epis_bed : episbed,
                    _token: _token
                  }

        $.post( "pat_mast/save_episode", obj , function( data ) {
            
        }).fail(function(data) {

        }).success(function(data){
            $('#editEpisode').modal('hide');
            $("#grid-command-buttons").bootgrid('reload');
        });
    }

    function populate_episode_by_mrn_episno(mrn,episno,form){
        var param={
            action:'get_episode_by_mrn',
            field:"*",
            mrn:mrn,
            episno:episno
        };

        $.get( "/episode/get_episode_by_mrn?"+$.param(param), function( data ) {

        },'json').done(function(data) {

            if(!$.isEmptyObject(data)){
                var episdata = data.episode;
                var epispayer = data.epispayer;
                var debtormast = data.debtormast;
                var bed = data.bed;

                if(episdata.newcaseP == 1){
                    $('#cmb_epis_case_maturity').val(1);
                    $('#cmb_epis_pregnancy').val("Pregnant");
                }else if(episdata.newcaseNP == 1){
                    $('#cmb_epis_case_maturity').val(1);
                    $('#cmb_epis_pregnancy').val("Non-Pregnant");
                }else if(episdata.followupP == 1){
                    $('#cmb_epis_case_maturity').val(2);
                    $('#cmb_epis_pregnancy').val("Pregnant");
                }else if(episdata.followupP == 1){
                    $('#cmb_epis_case_maturity').val(2);
                    $('#cmb_epis_pregnancy').val("Non-Pregnant");
                }

                $('#cmb_epis_pregnancy').val();
                $('#txt_epis_date').val(episdata.reg_date);
                $('#txt_epis_time').val(episdata.reg_time);
                $('#txt_epis_no').val(episdata.episno);
                $('#txt_epis_type').val(episdata.epistycode);

                $('#hid_epis_dept').val(episdata.regdept);
                $('#hid_epis_source').val(episdata.admsrccode);
                $('#hid_epis_case').val(episdata.case_code);
                $('#hid_epis_doctor').val(episdata.admdoctor);
                $('#hid_epis_fin').val(episdata.pay_type);
                $("#txt_epis_bed").val(bed.ward);
                $("#txt_epis_ward").val(bed.ward);
                $("#txt_epis_room").val(bed.room);
                $("#txt_epis_bedtype").val(episdata.bed);
                $('#cmb_epis_pay_mode').removeClass('form-disabled').addClass('form-mandatory');
                $('#cmb_epis_pay_mode').val(episdata.pyrmode.toUpperCase());
                $('#txt_epis_payer').val(debtormast.name);
                $('#hid_epis_bill_type').val(episdata.billtype);
                $('#txt_epis_refno').val();
                $('#txt_epis_our_refno').val();
                $('#txt_epis_queno').val();

            }else{
                alert('MRN not found')
            }

            epis_desc_show.write_desc();

        }).error(function(data){

        });

    }
    // populatecombo_gl();
    function populatecombo_gl(){

        var urloccupation = 'pat_mast/get_entry?action=get_patient_occupation';
        loadlist($('select#newgl-occupcode').get(0),urloccupation,'occupcode','description');

        var urlRel = 'pat_mast/get_entry?action=get_patient_relationship';
        loadlist($('select#newgl-relatecode').get(0),urlRel,'relationshipcode','description');
    }

    function onTab(event){
        var textfield = $(event.currentTarget);
        var id_ = textfield.attr('id');
        var id_use = id_.substring(id_.indexOf("_")+1);

        if(event.key == "Tab" && textfield.val() != ""){
            $('#mdl_item_selector').modal('show');
            pop_item_select(id_use,true,textfield.val());
        }
    }

    function pop_item_select(type,ontab=false,text_val)
    {    
        var act = "";
        var selecter = null;
        var item = null;
        var title="Item selector";
        var mdl = "";
            
        switch (type)
        {
            case "LanguageCode":
                act = "get_patient_language";
                break;
            case "Religion":
                act = "get_patient_religioncode";
                break;
            case "RaceCode":
                act = "get_patient_race";
                break;
            case "ID_Type":
                act = "get_patient_idtype";
                break;
            case "pat_title":
                act = "get_patient_title";
                mdl = "#mdl_add_new_title";
                break;
            case "pat_occupation":
                act = "get_patient_occupation";
                mdl = "#mdl_add_new_occ";
                break;
            case "pat_area":
                act = "get_patient_areacode";
                mdl = "#mdl_add_new_areacode";
                break;
            case "pat_citizen":
                act = "get_patient_citizen";
                break;
            case "payer_relation":
                act = "get_patient_relationship";
                break;
            case "payer_occupation":
                act = "get_patient_occupation";
                break;
            case "payer_company":
                act = "get_all_company";
                break;
            case "grtr_relation":
                act = "get_patient_relationship";
                break;
            case "epis_dept":
                act = "get_reg_dept";
                break;
            case "epis_source":
                act = "get_reg_source";
                mdl = "mdl_add_new_adm";
                break;
            case "epis_case":
                act = "get_reg_case";
                break;
            case "epis_doctor":
                act = "get_reg_doctor";
                break;
            case "epis_fin":
                act = "get_reg_fin";
                break;
        }
        
        selecter = $('#tbl_item_select').DataTable( {
                "ajax": "pat_mast/get_entry?action=" + act,
                "ordering": false,
                "lengthChange": false,
                "info": true,
                "pagingType" : "numbers",
                "search": {
                            "smart": true,
                          },
                "columns": [
                            {'data': 'code'}, 
                            {'data': 'description' },
                           ],

                "columnDefs": [ {
                    "targets": 0,
                    "data": "code",
                    "render": function ( data, type, row, meta ) {
                        if(act == "get_reg_source"){
                            return pad('000000',data,true)
                        }else{
                            return data;
                        }
                    }
                  } ],

                "fnInitComplete": function(oSettings, json) {
                    if(ontab==true){
                        selecter.search( text_val ).draw();
                    }
                    if(act == "get_reg_source" || act == "get_patient_occupation" || act == "get_patient_title" || act == "get_patient_areacode"){
                        console.log(mdl);
                        $('#add_new_adm').data('modal-target',mdl)
                        $('#add_new_adm').show();
                    }
                    if(selecter.page.info().recordsDisplay == 1){
                        $('#tbl_item_select tbody tr:eq(0)').dblclick();
                    }
                }
        } );

        // selecter.on( 'search.dt', function () {
        //     console.log(selecter.page.info().recordsDisplay);
        //     if(selecter.page.info().recordsDisplay == 1){
        //         $('#tbl_item_select tbody tr:eq(0)').dblclick();
        //     }
        // } );
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        $('#tbl_item_select tbody').on('dblclick', 'tr', function () {    

            item = selecter.row( this ).data();
            
            //console.log("type2="+type + " item=" + item["description"]);
            $('#hid_' + type).val(item["code"]);
            $('#txt_' + type).val(item["description"]);            
            
            $('#txt_' + type).change(); // <-- to activate onchange event if any
            //$('#txt' + type).blur(); // <-- to activate onchange event if any
                
            $('#mdl_item_selector').modal('hide');
            
                
                //alert( 'You clicked on ' + item["description"] + '\'s row.' );
            } );
            
        $("#mdl_item_selector").on('hidden.bs.modal', function () {
            //$('#tbl_item_select tbody').off('dblclick', 'tr', function () {
            $('#add_new_adm').hide();
            $('#tbl_item_select').html('');
            selecter.destroy();
            $('#add_new_adm,#adm_save,#new_occup_save,#new_title_save,#new_areacode_save').off('click');
            type = "";
            item = "";
                        //console.dir(selecter);
                        //console.dir(item);
            //        } );
        });

        $('#add_new_adm').click(function(){
            console.log($(this).data('modal-target'));
            $($(this).data('modal-target')).modal('show');
        });

        $('#adm_save').click(function(){
              if($('#adm_form').valid()){
                var _token = $('#csrf_token').val();
                let serializedForm = $( "#adm_form" ).serializeArray();
                let obj = {
                        _token: _token
                }
                
                $.post( '/pat_mast/save_adm', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                    $("#adm_form").trigger('reset');
                    selecter.ajax.reload()
                    $('#mdl_add_new_adm').modal('hide');
                }).fail(function(data) {
                    alert(data.responseText);
                }).success(function(data){
                });
              }
        });

        $('#new_occup_save').click(function(){
              if($('#new_occup_form').valid()){
                var _token = $('#csrf_token').val();
                let serializedForm = $( "#new_occup_form" ).serializeArray();
                let obj = {
                        _token: _token
                }
                
                $.post( '/pat_mast/new_occup_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                    $("#new_occup_form").trigger('reset');
                    selecter.ajax.reload()
                    $('#mdl_add_new_occ').modal('hide');
                }).fail(function(data) {
                    alert(data.responseText);
                }).success(function(data){
                });
              }
        });

        $('#new_title_save').click(function(){
              if($('#new_title_form').valid()){
                var _token = $('#csrf_token').val();
                let serializedForm = $( "#new_title_form" ).serializeArray();
                let obj = {
                        _token: _token
                }
                
                $.post( '/pat_mast/new_title_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                    $("#new_title_form").trigger('reset');
                    selecter.ajax.reload()
                    $('#mdl_add_new_title').modal('hide');
                }).fail(function(data) {
                    alert(data.responseText);
                }).success(function(data){
                });
              }
        });

        $('#new_areacode_save').click(function(){
              if($('#new_areacode_form').valid()){
                var _token = $('#csrf_token').val();
                let serializedForm = $( "#new_areacode_form" ).serializeArray();
                let obj = {
                        _token: _token
                }
                
                $.post( '/pat_mast/new_areacode_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                    $("#new_areacode_form").trigger('reset');
                    selecter.ajax.reload()
                    $('#mdl_add_new_title').modal('hide');
                }).fail(function(data) {
                    alert(data.responseText);
                }).success(function(data){
                });
              }
        });

    }

    function pad(pad, str, padLeft) {
        if (typeof str === 'undefined') 
            return pad;
        if (padLeft) {
            return (pad + str).slice(-pad.length);
        } else {
            return (str + pad).substring(0, pad.length);
        }
    }

    if($('#epistycode').val() == 'OP'){
        var epis_desc_show = new loading_desc_epis([
            {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
            {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
            {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
            {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
            {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
            // {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
            {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'}
        ]);
    }else if($('#epistycode').val() == 'IP'){
        var epis_desc_show = new loading_desc_epis([
            {code:'#hid_epis_dept',desc:'#txt_epis_dept',id:'regdept'},
            {code:'#hid_epis_source',desc:'#txt_epis_source',id:'regsource'},
            {code:'#hid_epis_case',desc:'#txt_epis_case',id:'case'},
            {code:'#hid_epis_doctor',desc:'#txt_epis_doctor',id:'doctor'},
            {code:'#hid_epis_bed',desc:'#txt_epis_bed',id:'epis_bed'},//ada bed pada IP
            {code:'#hid_epis_fin',desc:'#txt_epis_fin',id:'epis_fin'},
            // {code:'#hid_epis_payer',desc:'#txt_epis_payer',id:'epis_payer'},
            {code:'#hid_epis_bill_type',desc:'#txt_epis_bill_type',id:'bill_type'},
            {code:'',desc:'',id:'bed_dept'},
            {code:'',desc:'',id:'bed_ward'}
        ]);
    }
    
    epis_desc_show.load_desc();

    function loading_desc_epis(obj){
        this.code_fields=obj;
        this.regdept={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.regsource={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.case={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.doctor={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_bed={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_fin={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.epis_payer={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.bill_type={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.bed_dept={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.bed_ward={code:'code',desc:'description'};//data simpan dekat dalam ni
        this.load_desc = function(){
            load_for_desc(this,'regdept','pat_mast/get_entry?action=get_reg_dept');
            load_for_desc(this,'regsource','pat_mast/get_entry?action=get_reg_source');
            load_for_desc(this,'case','pat_mast/get_entry?action=get_reg_case');
            load_for_desc(this,'doctor','pat_mast/get_entry?action=get_reg_doctor');
            load_for_desc(this,'epis_bed','pat_mast/get_entry?action=get_reg_bed');
            load_for_desc(this,'epis_fin','pat_mast/get_entry?action=get_reg_fin');
            // load_for_desc(this,'epis_payer','pat_mast/get_entry?action=get_debtor_list');
            load_for_desc(this,'bill_type','pat_mast/get_entry?action=get_billtype_list');
            load_for_desc(this,'bed_dept','pat_mast/get_entry?action=get_bed_type');
            load_for_desc(this,'bed_ward','pat_mast/get_entry?action=get_bed_ward');
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


    function accomodation_selecter(){
        var accomodation_table = null;

        accomodation_table = $('#accomodation_table').DataTable( {
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
                dataSrc: [ "desc_bt" ]
            },
            "initComplete": function(settings, json) {
                let opt_bt = opt_ward = "";
                epis_desc_show.bed_dept.data.forEach(function(e,i){
                    opt_bt+=`<option value="`+e.description+`">`+e.description+`</option>`;
                });
                epis_desc_show.bed_ward.data.forEach(function(e,i){
                    opt_ward+=`<option value="`+e.description+`">`+e.description+`</option>`;
                });
                $("#accomodation_table_filter").html(`
                    <label >Bed Type: <input type="radio" data-seltype="bt" id="search_bed_bedtype" name="search_bed" checked></label>;&nbsp;
                    <label >Ward: <input type="radio" data-seltype="d" id="search_bed_ward" name="search_bed"></label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <select data-dtbid="0" id="search_bed_select_bed_dept" name="search_bed_select" class="form-control">
                          <option value="">-- Select --</option>
                          `+opt_bt+`
                        </select>

                        <select data-dtbid="2" id="search_bed_select_ward" name="search_bed_select" class="form-control" style="display:none;">
                          <option value="">-- Select --</option>
                          `+opt_ward+`
                        </select>
                    </label>
                    `
                );

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

        $('#accomodation_table tbody').on('dblclick', 'tr', function () {    
            let item = accomodation_table.row( this ).data();
            if(item != undefined){
                $('#txt_epis_bed').val(item["bednum"]);
                $('#txt_epis_ward').val(item["ward"]);
                $('#txt_epis_room').val(item["room"]);
                $('#txt_epis_bedtype').val(item["bedtype"]);
                    
                $('#mdl_accomodation').modal('hide');
            }
        });

        $("#mdl_accomodation").on('hidden.bs.modal', function () {
            accomodation_table.destroy();
            $('#accomodation_table tbody').off('dblclick');
        });
    }

