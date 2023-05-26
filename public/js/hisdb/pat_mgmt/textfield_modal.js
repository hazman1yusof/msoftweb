function textfield_modal(){

    var self= this;
    this.dontcheck = false;
    this.db_search = false;
    this.txt_search = false;

    this.selecter = $('#tbl_item_select').DataTable( {
            "ajax": "",
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

            "columnDefs":[{
                "targets": 0,
                "data": "code",
                "render": function ( data, type, row, meta ) {
                    return data;
                }
            }],
            "fnDrawCallback": function( oSettings ) {
                if(self.db_search == true){
                    self.db_search=false;
                    self.selecter.search(self.txt_search).draw();
                }

                if($('#tbl_item_select').data('iscomplete') == true){
                    if(self.selecter.page.info().recordsDisplay == 1){
                        $('#tbl_item_select tbody tr:eq(0)').dblclick();
                    }
                }
            },
            "fnInitComplete": function(oSettings, json) {
                $('#tbl_item_select').data('iscomplete',true);
            }
    });

    this.ontabbing = function(){
        $("#txt_epis_dept,#txt_epis_source,#txt_epis_case,#txt_epis_doctor,#txt_epis_fin,#hid_newgl_corpcomp,#txt_newgl_occupcode,#txt_newgl_relatecode,#txt_pat_title,#txt_ID_Type,#txt_RaceCode,#txt_Religion,#txt_pat_citizen,#txt_LanguageCode,#txt_pat_area,#txt_payer_company,#txt_pat_occupation").on('keydown',{data:this},onTab);
    }

    this.checking = function(){
        $("#txt_epis_dept,#txt_epis_source,#txt_epis_case,#txt_epis_doctor,#txt_epis_fin,#hid_newgl_corpcomp,#txt_newgl_occupcode,#txt_newgl_relatecode,#txt_pat_title,#txt_ID_Type,#txt_RaceCode,#txt_Religion,#txt_pat_citizen,#txt_LanguageCode,#txt_pat_area,#txt_payer_company,#txt_pat_occupation").on('blur',{data:this},onCheck);
    }

    this.clicking = function(){
        $("#btn_epis_dept,#btn_epis_source,#btn_epis_case,#btn_epis_doctor,#btn_epis_fin,#btn_newgl_corpcomp,#btn_newgl_occupcode,#btn_newgl_relatecode,#btn_pat_title,#btn_ID_Type,#btn_RaceCode,#btn_Religion,#btn_pat_citizen,#btn_LanguageCode,#btn_pat_area,#btn_payer_company,#btn_pat_occupation").on('click',{data:this},onClick);
    }

    function onTab(event){
        var obj = event.data.data;
        var textfield = $(event.currentTarget);
        var id_ = textfield.attr('id');
        var id_use = id_.substring(id_.indexOf("_")+1);

        if(event.key == "Tab" && textfield.val() != ""){
            $('#mdl_item_selector').modal('show');
            obj.db_search = true;
            obj.txt_search = textfield.val();
            pop_item_select(id_use,true,textfield.val(),obj);
            obj.dontcheck = true;
        }
    }

    function onClick(event){
        var obj = event.data.data;
        var textfield = $(event.currentTarget);
        var id_ = textfield.attr('id');
        var id_use = id_.substring(id_.indexOf("_")+1);

        $('#mdl_item_selector').modal('show');
        pop_item_select(id_use,false,textfield.val(),obj);
        obj.dontcheck = true;
    }

    function get_mdl(type){
        let mdl = null;

        switch (type){
            case "pat_title":
                mdl = "#mdl_add_new_title";
                break;
            case "pat_occupation":
                mdl = "#mdl_add_new_occ";
                break;
            case "pat_area":
                mdl = "#mdl_add_new_areacode";
                break;
            case "epis_source":
                mdl = "mdl_add_new_adm";
                break;
        }
        return mdl;
    }

    function after_hide(type){
        switch (type){
            case "pat_title":
                
                break;
            case "ID_Type":
                
                break;
            case "RaceCode":
                
                break;
            case "Religion":
                
                break;
            case "pat_citizen":
                
                break;
            case "LanguageCode":
                
                break;
            case "pat_area":
                
                break;
            case "pat_occupation":
                
                break;
            case "payer_company":
                
                break;
            case "epis_dept":
                $('#txt_epis_source').focus();
                break;
            case "epis_source":
                $('#txt_epis_case').focus();
                break;
            case "epis_case":
                $('#txt_epis_doctor').focus();
                break;
            case "epis_doctor":
                $('#txt_epis_fin').focus();
                break;
            case "epis_fin":
                $('#txt_epis_payer').focus();
                $('#txt_epis_fin').change(); 
                break;
            case "newgl_corpcomp":
                
                break;
            case "newgl_occupcode":
                
                break;
            case "newgl_relatecode":
                
                break;
        }
    }

    function get_url(type){
        let act = null;
        switch (type){
            case "pat_title":
                act = "get_patient_title";
                break;
            case "ID_Type":
                act = "get_patient_idtype";
                break;
            case "RaceCode":
                act = "get_patient_race";
                break;
            case "Religion":
                act = "get_patient_religioncode";
                break;
            case "pat_citizen":
                act = "get_patient_citizen";
                break;
            case "LanguageCode":
                act = "get_patient_language";
                break;
            case "pat_area":
                act = "get_patient_areacode";
                break;
            case "pat_occupation":
                act = "get_patient_occupation";
                break;
            case "payer_company":
                act = "get_all_company";
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
            case "newgl_corpcomp":
                act = "get_debtor_list&type=newgl";
                break;
            case "newgl_occupcode":
                act = "get_patient_occupation";
                break;
            case "newgl_relatecode":
                act = "get_patient_relationship";
                break;
        }
        return act;
    }

    function onCheck(event){
        var obj = event.data.data;
        if(!obj.dontcheck){
            var textfield = $(event.currentTarget);
            var search = textfield.val();
            var id_ = textfield.attr('id');
            var id_use = id_.substring(id_.indexOf("_")+1);

            var act = get_url(id_use);
            if(search.trim() != ""){
                $.get( "./pat_mast/get_entry?action="+act+"&search="+search, function( data ) {
                            
                },'json').done(function(data) {
                    if(!$.isEmptyObject(data) && data.data!=null){
                        myerrorIt_only('#'+id_,false);
                    }else{
                        myerrorIt_only('#'+id_,true);
                    }
                });
            }
        }
        obj.dontcheck = false;
    }

    function pop_item_select(type,ontab=false,text_val,obj){ 
        var obj = obj;   
        var act = null;
        var selecter = obj.selecter;
        var title="Item selector";
        var mdl = null;
        $('#tbl_item_select tbody').off('dblclick');
        $('#add_new_adm,#adm_save,#new_occup_save,#new_title_save,#new_areacode_save').off('click');
            
        act = get_url(type);
        mdl = get_mdl(type);

        selecter.ajax.async = false;
        selecter.ajax.url( "pat_mast/get_entry?action=" + act).load();
        
        // dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
        $('#tbl_item_select tbody').on('dblclick', 'tr', function () {
            myerrorIt_only('#txt_' + type,false);
            item = selecter.row( this ).data();
            
            $('#hid_' + type).val(item["code"]);
            $('#txt_' + type).val(item["description"]);  
                
            $('#mdl_item_selector').modal('hide');
            after_hide(type);
        });

    }
    
    $("#mdl_item_selector").on('hide.bs.modal', function () {
        // $('#add_new_adm').hide();
        // $('#add_new_adm,#adm_save,#new_occup_save,#new_title_save,#new_areacode_save').off('click');
        // type = "";
        // item = "";
        self.selecter.search('');
    });

    $('#add_new_adm').click(function(){
        $('#mdl_add_new_adm').modal('show');
    });

    $('#adm_save').click(function(){
        if($('#adm_form').valid()){
            var _token = $('#csrf_token').val();
            let serializedForm = $( "#adm_form" ).serializeArray();
            let obj = {
                    _token: _token
            }
            
            $.post( 'pat_mast/save_adm', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
            
            $.post( 'pat_mast/new_occup_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
            
            $.post( 'pat_mast/new_title_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
            
            $.post( 'pat_mast/new_areacode_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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