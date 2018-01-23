
	
	////////////////////////////////////////till checking at start of program supposedly///////////////////
	
	var def_tillcode,def_tillno;

	function updateTillUsage(){
		var param={action:'save_table_default_arr',array:
			[{	oper:'add',
				table_name:'debtor.tilldetl',
				field:['cashier','tillcode','opendate','opentime','tillno'],
				table_id:'sysno',
				sysparam:{source:'AR',trantype:'TN',useOn:'tillno'},
			},{	oper:'edit',
				table_name:'debtor.till',
				field:['tillstatus'],
				table_id:'tillcode',
			}],
		};
		$.post( "../../../../assets/php/entry.php?"+$.param(param),
			{cashier:$('#cashier').val(),tillcode:$('#tilldetTillcode').val(),opendate:'NOW()',opentime:'NOW()',tillstatus:'O'}, 
			function( data ) {
				
			}
		).fail(function(data) {
			alert('Error');
		}).success(function(data){
			checkIfTillOpen();
			$( "#tilldet" ).dialog('close');
		});
	}
	
	function checkIfTillOpen(){
		var param={
			action:'get_value_default',
			field:['tilldetl.tillcode','till.lastrcnumber','till.dept','tilldetl.tillno','tilldetl.opendate','tilldetl.opentime','tilldetl.closedate','tilldetl.closetime','tilldetl.cashier','department.sector','department.region'],
			table_name:['debtor.till','debtor.tilldetl','sysdb.department'],
			join_type:['LEFT JOIN','LEFT JOIN'],
			join_onCol:['till.tillcode','till.dept'],
			join_onVal:['tilldetl.tillcode','department.deptcode'],
			filterCol:['cashier','closedate'],
			filterVal:['session.username','IS NULL']
		}
		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				def_tillcode = data.rows[0].tillcode;
				def_tillno = data.rows[0].tillno;
				urlParam.filterVal = [data.rows[0].tillno];
				refreshGrid('#jqGrid',urlParam);
				$("#formdata input[name='dbacthdr_tillcode']").val(data.rows[0].tillcode);
				$("#formdata input[name='dbacthdr_lastrcnumber']").val(parseInt(data.rows[0].lastrcnumber) + 1);
				$("#formdata input[name='dbacthdr_recptno']").val(data.rows[0].tillcode+"-"+pad('000000000',parseInt(data.rows[0].lastrcnumber) + 1,true));
				$("#formdata input[name='dbacthdr_tillno']").val(data.rows[0].tillno);
				$("#formdata input[name='dbacthdr_units']").val(data.rows[0].sector);

				$( "#tilldet" ).dialog('close');
			}else{
				dialog_till.handler([]);
			}
		});
	}

	$( "#tilldet" ).dialog({
		autoOpen: true,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function() { 
			checkIfTillOpen();
			$(this).parent().find(".ui-dialog-titlebar-close").hide();                       
		},
		buttons: [
			{
				text:'Open Till',
				disabled: true,
				id: "tilldetCheck",
				click:function(){
					updateTillUsage();
				}
			},{
				text:'Reset',
				click:function(){
					emptyFormdata([],'#formTillDet');
					$( "#tilldetCheck" ).button( "option", "disabled", true );
				}
			},
		],
		closeOnEscape: false,
	});

	//////////////////////////////////////////End till checking/////////////////////////