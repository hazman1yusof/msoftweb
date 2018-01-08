var Global = function () {

	function pop_item_select(type)
	{
		var act = "";
		var selecter = null;
		var item = null;
			
		switch (type)
		{
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
				act = "get_all_relationship";
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
				"ajax": "../../../../assets/php/entry_hisdb.php?action=" + act,
				"lengthChange": false,
				"info": false,
				"pagingType" : "numbers",
				"search": {
							"smart": true,
						  },
				"columns": [
							{'data': 'code'}, 
							{'data': 'description' },
						   ]
		} );
		
		// dbl click will return the description in text box and code into hidden input, dialog will be closed automatically
		$('#tbl_item_select tbody').on('dblclick', 'tr', function () {	

				
				item = selecter.row( this ).data();				
				//console.log("type2="+type + " item=" + item["description"]);
				$('#hid_' + type).val(item["code"]);
				$('#txt_' + type).val(item["description"]);			
				
				$('#txt' + type).focus(); // <-- to activate onchange event if any
				//$('#txt' + type).blur(); // <-- to activate onchange event if any
					
				$('#mdl_item_selector').modal('hide');
				
				//alert( 'You clicked on ' + item["description"] + '\'s row.' );
			} );
			
		$("#mdl_item_selector").on('hidden.bs.modal', function () {
            //$('#tbl_item_select tbody').off('dblclick', 'tr', function () {
						$('#tbl_item_select').html('');
						selecter.destroy();
						type = "";
						item = "";
						//console.dir(selecter);
						//console.dir(item);
			//		} );
		});
			
		
	}
	
	return {
        pop_item_select: function (type) {
            pop_item_select(type);
        },
    };

} ();