var Global = function () {

	function pop_item_select(type,ontab=false)
	{	
		var act = "";
		var selecter = null;
		var item = null;
			
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
				break;
			case "pat_occupation":
				act = "get_patient_occupation";
				break;
			case "pat_area":
				act = "get_patient_areacode";
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
				"order": [[ 0, "desc" ]],
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
                        if(act == "get_reg_source"){
                            return pad('000000',data,true)
                        }else{
                        	return data;
                        }
                    }
                  } ],

				"fnInitComplete": function(oSettings, json) {
					
                    if(act == "get_reg_source"){
                        $('#add_new_adm').show();
                    }
			    }
		} );
		
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
			type = "";
			item = "";
						//console.dir(selecter);
						//console.dir(item);
			//		} );
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
			
		
	}
	
	return {
        pop_item_select: function (type) {
            pop_item_select(type);
        },
    };

} ();