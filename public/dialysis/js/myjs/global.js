var Global = function () {

	function pop_item_select(type,ontab=false)
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
				title = "Select Patient Language";
                mdl = "none";
				break;
			case "Religion":
				act = "get_patient_religioncode";
				title = "Select Patient Religion";
                mdl = "none";
				break;
			case "RaceCode":
				act = "get_patient_race";
				title = "Select Patient race";
                mdl = "none";
				break;
			case "ID_Type":
				act = "get_patient_idtype";
				title = "Select Patient ID Type";
                mdl = "none";
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
				title = "Select Patient Citizen";
                mdl = "none";
				break;
			case "payer_relation":
				act = "get_patient_relationship";
				title = "Select Patient Relationship";
                mdl = "none";
				break;
			case "payer_occupation":
				act = "get_patient_occupation";
				title = "Select Patient Occupation";
                mdl = "none";
				break;
			case "payer_company":
				act = "get_all_company";
				title = "Select Patient Company";
                mdl = "none";
				break;
			case "grtr_relation":
				act = "get_patient_relationship";
				title = "Select Patient Relationship";
                mdl = "none";
				break;
			case "epis_dept":
				act = "get_reg_dept";
				title = "Select Patient Register Department";
                mdl = "none";
				break;
            case "epis_source":
                act = "get_reg_source";
                mdl = "#mdl_add_new_adm";
				break;
			case "epis_case":
				act = "get_reg_case";
				title = "Select Patient Case";
                mdl = "none";
				break;
			case "epis_doctor":
				act = "get_reg_doctor";
				title = "Select Patient Doctor";
                mdl = "none";
				break;
			case "admdoctor":
				act = "get_reg_admdoctor";
				title = "Select Patient Doctor";
                mdl = "none";
				break;
			case "attndoctor":
				act = "get_reg_attndoctor";
				title = "Select Patient Doctor";
                mdl = "none";
				break;
			case "epis_fin":
				act = "get_reg_fin";
				title = "Select Patient Finance";
                mdl = "none";
				break;
			case "epis_bed":
				act = "get_reg_bed";
				title = "Select Patient Bed";
                mdl = "none";
				break;
			case "newgl_occupcode":
				act = "get_patient_occupation";
				title = "Select GL Occupation";
                mdl = "none";
				break;
			case "newgl_relatecode":
				act = "get_patient_relationship";
				title = "Select GL Relationship";
                mdl = "none";
				break;
			case "newgl_corpcomp":
				act = "get_all_company";
				title = "Select Company";
                mdl = "none";
				break;

				
		}

		$("#txt_item_selector").text(title);
		
		selecter = $('#tbl_item_select').DataTable( {
				"ajax": "pat_mast/get_entry?action=" + act,
				"ordering": false,
				"lengthChange": false,
				"info": false,
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
                        return data;
                    }
                  } ],

				"fnInitComplete": function(oSettings, json) {
					if(ontab==true){
                        selecter.search( text_val ).draw();
                    }
                    // if(act == "get_reg_source" || act == "get_patient_occupation" || act == "get_patient_title" || act == "get_patient_areacode"){
                    if(mdl!='none'){
                        $('#add_new_adm').data('modal-target',mdl)
                        $('#add_new_adm').show();
                    }else{
                    	$('#add_new_adm').hide();
                    }
                    if(selecter.page.info().recordsDisplay == 1){
                        $('#tbl_item_select tbody tr:eq(0)').dblclick();
                    }
			    }
		} );
		
		// dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
		$('#tbl_item_select tbody').on('dblclick', 'tr', function () {	
            myerrorIt_only('#txt_' + type,false);
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
			$('#add_new_adm,#adm_save,#new_occup_save,#new_title_save,#new_areacode_save').off('click');
			selecter.destroy();
			type = "";
			item = "";
						//console.dir(selecter);
						//console.dir(item);
			//		} );
		});

        $("#mdl_item_selector,#mdl_add_new_title,#mdl_add_new_occ,#mdl_add_new_adm,#mdl_add_new_relationship,#mdl_add_new_areacode,#mdl_accomodation").on('show.bs.modal', function () {
        	$(this).eq(0).css('z-index','120');
        });

		$('#add_new_adm').click(function(){
			if($(this).data('modal-target') != ''){
            	$($(this).data('modal-target')).modal('show');
			}
        });

        $('#adm_save').click(function(){
              if($('#adm_form').valid()){
                var _token = $('#csrf_token').val();
                let serializedForm = $( "#adm_form" ).serializeArray();
                let obj = {
                        _token: _token
                }
                
                $.post( './pat_mast/save_adm', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
                
                $.post( './pat_mast/new_occup_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
                
                $.post( './pat_mast/new_title_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
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
                
                $.post( './pat_mast/new_areacode_form', $.param(serializedForm)+'&'+$.param(obj) , function( data ) {
                    $("#new_areacode_form").trigger('reset');
                    selecter.ajax.reload()
                    $('#mdl_add_new_title').modal('hide');
                }).fail(function(data) {
                    alert(data.responseText);
                }).success(function(data){
                });
              }
        });

        function religion_auto_save(obj){
        	$.post( './pat_mast/religion_auto_save', obj , function( data ) {
        		
            }).fail(function(data) {
                console.log(data.responseText);
            }).success(function(data){
            });
        }
			
		
	}
	
	return {
        pop_item_select: function (type) {
            pop_item_select(type);
        },
    };

} ();